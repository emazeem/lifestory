<?php

namespace App\Http\Controllers\Api\Customer;

use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use App\Traits\CommonTraits;
use Illuminate\Http\Request;
use App\Events\RealTimeMessage;
use App\Http\Controllers\Controller;

class CommentController extends Controller
{
    use CommonTraits;
    public function fetch(Request $request)
    {
        $comments = Comment::with('user')->where('post_id', $request->id)->orderBy('created_at', 'DESC')->get();
        return $this->sendSuccess("Comments fetched", $comments);

    }
    public function store(Request $request)
    {
        $validators = Validator($request->all(), [
            "comment" => "required",
        ]);
        if ($validators->fails()) {
            return $this->sendError($validators->messages(), 422);
        }
        $comment = new Comment();
        $comment->user_id = auth()->user()->id;
        $comment->post_id = $request->post_id;
        $comment->comment = $request->comment;
        $comment->save();
        $post = Post::find($request->post_id);
        $postUser = User::find($post->user_id); 
        $postUser->show_notification = 1;
        $postUser->save();
        if (auth()->user()->id != $post->user_id) {
            $sender = [
                "id" => auth()->user()->id,
                "profile" => auth()->user()->profile,
                "fname" => auth()->user()->fname,
                "lname" => auth()->user()->lname,
            ];
            LS_Notification($post->user_id, '<b>' . auth()->user()->fullName() . '</b> commented on your post.', 'post/' . $request->post_id, $sender);
        }

        // $options = array(
        //     'cluster' => 'ap2',
        //     'encrypted' => true
        // );

        // $pusher = new Pusher(
        //     Env::get('PUSHER_APP_KEY'),
        //     Env::get('PUSHER_APP_SECRET'),
        //     Env::get('PUSHER_APP_ID'), $options
        // );
        // if (auth()->user()->id != $userID) {

        //     $data = [
        //         "data"    => $comment,
        //         "user_id" => $userID,
        //     ];

        //     // $pusher->trigger('channel', 'App\Events\NotificationEvent',$data);
        //     event(new RealTimeMessage($data));
        // }

        return $this->sendSuccess("Comment added successfully", true);

    }
    public function update(Request $request)
    {
        $validators = Validator($request->all(), [
            "comment" => "required",
        ]);
        if ($validators->fails()) {
            return $this->sendError($validators->messages(), 422);
        }
        $comment = Comment::find($request->comment_id);
        $comment->comment = $request->comment;
        $comment->save();
        return $this->sendSuccess("Comment updated successfully", true);

    }
    public function delete(Request $request)
    {
        $validators = Validator($request->all(), [
            "id" => "required",
        ]);
        if ($validators->fails()) {
            return $this->sendError($validators->messages(), 422);
        }
        Comment::find($request->id)->delete();
        return $this->sendSuccess("Comment deleted successfully", true);

    }

}
