<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\User;
use App\Models\Story;
use App\Models\Session;
use App\Models\Subscription;
use App\Traits\CommonTraits;
use Illuminate\Http\Request;
use App\Traits\ZoomOAuthTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;

class SessionController extends Controller
{
    use CommonTraits, ZoomOAuthTrait;
    public function fetchAll(Request $request)
    {
        $search = $request->search;
        $sessions = Session::latest()
            ->when($search, function ($query) use ($search) {
                $query->where("id", "like", "%$search%")
                    ->orWhere("start_time", "like", "%$search%")
                    ->orWhere("duration", "like", "%$search%")
                    ->orWhere("status", "like", "%$search%")
                    ->orWhere("id", "like", "%$search%")
                    ->orWhereHas('customer', function ($subQuery) use ($search) {
                        $subQuery->where('fname', 'like', "%$search%")
                            ->orWhere('lname', 'like', "%$search%");
                    });
            })
            ->with('customer')
            ->latest()
            ->paginate(10);
        return response()->json($sessions);
    }
    public function fetch(Request $request)
    {
        $session = Session::with(['customer', 'customer.details', 'customer.video'])->where('id', $request->id)->first();
        $customer = $session->customer;

        $subscription = Subscription::where("user_id", $customer->id)->first();
        if ($subscription && $subscription->is_active) {
            $session->subscribed = true;
        } else {
            $session->subscribed = false;
        }

        $customer->account_created = Hash::check(predefinedPassword(), $customer->password) ? false : true;

        $completed = 0;

        $subUsers = [];
        foreach ($customer->subUsers as $subUser) {
            if ($customer->id != $subUser->id) {
                $subUsers[] = $subUser;
            }
        }

        if ($session->customer->video) {
            $session->customer->video->o_file = null;
            $vSeg = explode('/', $session->customer->video->file);
            $vSeg = array_reverse($vSeg)[0];
            $session->customer->video->o_file = $vSeg;
            if ($session->payment && $customer->account_created && $customer->is_active) {
                $completed = 1;
            } elseif ($session->payment && $customer->account_created && !$customer->is_active) {
                $completed = 2;
            }

        }

        return response()->json([
            "success" => true,
            "session" => $session,
            "subusers" => $subUsers,
            "completed" => $completed,
        ]);
    }
    public function sessionsAsEvents()
    {
        $sessionsAsEvents = Session::select('id', 'uuid', 'topic', 'start_time', DB::raw("DATE_FORMAT(start_time, '%Y-%m-%d') as start"))
            ->get()->map(function ($session) {
            $session['start'] = $session['start_time'];
            $session['title'] = $session['topic'];
            unset($session['topic']);
            unset($session['link']);
            return $session;
        })->toArray();

        return response()->json([
            "success" => true,
            "events" => $sessionsAsEvents,
        ]);
    }

    public function delete(Request $request)
    {
        $validators = Validator($request->all(), ['id' => 'required|numeric']);

        if ($validators->fails()) {
            return $this->sendError($validators->messages(), 422);
        }

        $session = Session::find($request->id);
        if ($session) {
            try {
                $this->delete_meeting($session->meeting_id);
            } finally {
                $user = User::where("id", $session->user_id)->first();
                if ($user) {
                    if ($user->role == \RoleString::Customer && !$user->video && !$user->session->payment) {
                        // Perform a hard delete for inactive users
                        $user->forceDelete();
                    } else {
                        $user->delete();
                    }

                }
                $session->delete();
            }
        }

        return $this->sendSuccess('Session deleted successfully', true);
    }

    public function uploadCustomerVideo(Request $request)
    {
        ini_set('memory_limit', '2048M');
        $session = Session::find($request->session_id);
        $customer = $session->customer;
        $receiver = new FileReceiver('file', $request, HandlerFactory::classFromRequest($request));

        $fileReceived = $receiver->receive(); // receive file
        if ($fileReceived->isFinished()) { // file uploading is complete / all chunks are uploaded
            $file = $fileReceived->getFile();
            $fileName = $customer->fname . '_' . $customer->lname . '_' . date('Y-m-d_h_i_s_A') . '.' . $file->getClientOriginalExtension();

            $disk = Storage::disk('public');

            $path = $disk->putFileAs('session_recordings/' . $customer->id, $file, $fileName);

            $screenshotFileName = $customer->fname . '_' . $customer->lname . '_' . date('Y-m-d_h_i_s_A') . '_screenshot.png';

            // Specify the path where you want to save the screenshot
            $screenshotPath = storage_path('app/public/session_recordings/' . $customer->id . '/' . $screenshotFileName);

            

            try {
                // Execute FFmpeg command to capture a screenshot from the video at the start
            $ffmpegCommand = "ffmpeg -i " . storage_path('app/public/' . $path) . " -ss 00:00:00.000 -vframes 1 " . $screenshotPath;

            // Execute the FFmpeg command
            shell_exec($ffmpegCommand);

            // Image path to write the customer name on it
            $imagePath = storage_path('app/public/session_recordings/' . $customer->id . '/' . $screenshotFileName);
            $image = imagecreatefrompng($imagePath);

            // Define the text to write
            $customer_name = $customer->fullName();

            // Define the font size and color
            $fontSize = 30;
            $fontColor = imagecolorallocate($image, 255, 255, 255); // White color

            // Get the image dimensions
            $imageWidth = imagesx($image);
            $imageHeight = imagesy($image);

            // Calculate the position to center the text
            $fontPath = public_path('open-sans/opensans-bold.ttf');

            $textBox = imagettfbbox($fontSize, 0, $fontPath, $customer_name);
            $textWidth = $textBox[2] - $textBox[0];
            $textHeight = $textBox[1] - $textBox[7];
            $textX = ($imageWidth - $textWidth) / 2;
            $textY = ($imageHeight - $textHeight) / 2 + abs($textBox[7]); // Adjust for font baseline

            // Write the text on the image using TTF font
            imagettftext($image, $fontSize, 0, $textX, $textY, $fontColor, $fontPath, $customer_name);

            // Save or output the modified image
            header('Content-Type: image/png');
            imagepng($image, $imagePath);

            // Clean up memory
            imagedestroy($image);

            } catch (\Throwable $th) {
                $screenshotFileName = '';
                Log::error('Error occurred: ' . $th->getMessage(), ['exception' => $th]);
            }


            $story = Story::where('user_id', $customer->id)->first();

            if ($story) {
                $videoUrl = 'session_recordings/' . $story->customer->id . '/' . $story->name;
                $thumbnailUrl = 'session_recordings/' . $story->customer->id . '/' . $story->thumbnail;
                if ($story->stored_at != 's3') {
                    // Delete video file
                    Storage::disk('public')->delete([$videoUrl, $thumbnailUrl]);
                } elseif ($story->stored_at == 's3') {

                    $s3VideoPath = parse_url($story->file, PHP_URL_PATH);
                    $s3ThumbnailPath = parse_url($story->thumbnail, PHP_URL_PATH);

                    Storage::disk('s3')->delete([$s3VideoPath, $s3ThumbnailPath]);

                    $s3BackUpVideoPath = parse_url($story->file, PHP_URL_PATH);
                    $s3BackUpThumbnailPath = parse_url($story->thumbnail, PHP_URL_PATH);

                    Storage::disk('s3')->delete([$s3BackUpVideoPath, $s3BackUpThumbnailPath]);
                }
                Storage::disk('public')->delete('session_recordings/' . $story->customer->id . '/' . $story->file);
                $story->update([
                    "user_id" => $customer->id,
                    "file" => $fileName,
                    "name" => $fileName,
                    "stored_at" => "public",
                    "thumbnail" => $screenshotFileName,
                    "backup_1" => null,
                ]);
            } else {
                $story = Story::create(["user_id" => $customer->id, "file" => $fileName, "name" => $fileName, "thumbnail" => $screenshotFileName]);
            }
            $session->status = "completed";
            $session->save();

            unlink($file->getPathname());
            return [
                'path' => asset('storage/' . $path),
                'filename' => $fileName,
            ];

        }

        // otherwise return percentage information
        $handler = $fileReceived->handler();
        return [
            'done' => $handler->getPercentageDone(),
            'status' => true,
        ];
    }

}
