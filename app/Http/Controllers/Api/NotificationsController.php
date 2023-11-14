<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\CommonTraits;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationsController extends Controller
{
    use CommonTraits;
    public function fetch()
    {
        $notifications=Auth::user()->notifications->map(function ($row){
            $created = new Carbon($row->created_at);
            $row->notifiable=User::find($row->notifiable_id);
            //$row->data=getDataAttributeNotification($row->data);
            $row->created=$created->diffForHumans();
            return $row;
        });
        return $this->sendSuccess('Notifications fetched!',$notifications);
    }
    public function fetchUnread()
    {
        $notifications=Auth::user()->unreadNotifications;
        auth()->user()->show_notification;
        return $this->sendSuccess('Unread notifications fetched!',[
            "showCount" => auth()->user()->show_notification,
            "notifications" => $notifications
        ]);
    }
    public function old()
    {
        $notifications=Auth::user()->readNotifications->map(function ($row){
            $created = new Carbon($row->created_at);
            $read = new Carbon($row->read_at);
            $row->notifiable=User::find($row->notifiable_id);
            $row->created=$created->diffForHumans();
            $row->read=$read->diffForHumans();
            return $row;
        });
        return $this->sendSuccess('Old notifications fetched!',$notifications);
    }

    public function readOne(Request $request)
    {
        auth()->user()->notifications->where("id",$request->id)->markAsRead();
        return $this->sendSuccess('Notifications marked as read!', true);
    }
    public function readAll(Request $request)
    {
        if ($request->option=='delete')
        {
            auth()->user()->notifications->each(function ($notification) {
                $notification->delete();
            });

        } else {
            auth()->user()->unreadNotifications->markAsRead();
            return $this->sendSuccess('All notifications marked as read!', true);
        }
    }

    public function delete(Request $request)
    {
        $notification = auth()->user()->notifications->where('id', $request->id)->first();
        
        if($notification) $notification->delete();
        
        return $this->sendSuccess('Notification delete successfully!', true);
    }

    public function readCounter()
    {
        $user = User::find(auth()->user()->id);
        $user->show_notification = 0;
        $user->save();
        return $this->sendSuccess("Notification counter cleared successfully", true);
    }

}
