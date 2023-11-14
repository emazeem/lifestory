<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use App\Traits\CommonTraits;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    use CommonTraits;
    public function fetch(Request $request)
    {
        $id = $request->id ? $request->id : auth()->user()->id;
        $user = User::find($id);
        $posts = Post::latest()->with('user', 'comments')->where('customer_id', getActiveCustomer($user)->id)->get();
        return $this->sendSuccess("Posts fetched", $posts);
    }
    public function timeline(Request $request)
    {

        $user = $request->id ? User::find($request->id) : auth()->user();

        $postsByYear = Post::select(DB::raw('YEAR(actual) as year'))
            ->selectRaw('COUNT(*) as post_count')
            ->groupBy(DB::raw('YEAR(actual)'))->where('customer_id', getActiveCustomer($user)->id);

        $posts = [];
        foreach ($postsByYear->get() as $postByYear) {
            $year = $postByYear->year;
            $posts[$year] = Post::with('comments', 'user')->whereYear('actual', $year)->where('customer_id', getActiveCustomer($user)->id)->get();
        }
        return $this->sendSuccess("Posts fetched", $posts);
    }

    public function fetchSingle(Request $request)
    {
        $post = Post::with('user')->where('id', $request->id)->first();
        $created = new Carbon($post->created_at);
        $post->created_at_hum = $created->diffForHumans();

        return $this->sendSuccess("Post fetched", $post);
    }

    public function store(Request $request)
    {
        $validators = Validator($request->all(), [
            "caption" => "required",
            "month" => "nullable|string",
            "image" => "required|image|mimes:jpg,jpeg,png",
        ]);
        if ($validators->fails()) {
            return $this->sendError($validators->messages(), 422);
        }

        if ($request->has('month') && $request->has('year')) {
            $requestedDate = date('Y-m', strtotime($request->input('year') . '-' . $request->input('month')));
            $currentDate = date('Y-m');

            if ($requestedDate > $currentDate) {
                return $this->sendError("Please upload past memories.", 421);
            }
        }

        if ($request->month && $request->month !='undefined') {
            $month = $request->input('month');
        } else {
            $month = date('m');
        }

        if ($request->year && $request->year !='undefined') {
            $year = $request->input('year');
        } else {
            $year = date('Y');
        }

        $actual = $year . '-' . $month . '-01';
        $stored_at = 's3';

        $image = $request->file('image');

        $dbName = date("m-d-Y-h_i_s_A") . "-" . $image->getClientOriginalName();
        $originalName = auth()->user()->id . "/" . $dbName;

        if(config('app.switch_storage') == 'local')
        {
            $image->storeAs('memories', $originalName, 'public');
            $url = $dbName;
            $stored_at = 'public';
        }
        else
        {
            $path = $image->storeAs('memories', $originalName, 's3');
            $image->storeAs('memories', $originalName, 's3_backup');
            $url = Storage::disk('s3')->url($path);
        }

        auth()->user()->posts()->create([
            "customer_id" => getActiveCustomer(auth()->user())->id,
            "caption" => $request->caption,
            "stored_at" => $stored_at,
            "actual" => $actual,
            "image" => $url,
        ]);

        return $this->sendSuccess("Post uploaded successfully", true);
    }
}
