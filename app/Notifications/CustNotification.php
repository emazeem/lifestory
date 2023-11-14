<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustNotification extends Notification
{
    use Queueable;
    public $notification;
    public $route;
    public $sender;
    public function __construct($title,$route,$sender)
    {
        $this->notification = $title;
        $this->route        = $route;
        $this->sender       = $sender;
    }
    public function via(object $notifiable)
    {
        return ['database'];
    }
    public function toDatabase($notifiable)
    {
        return [
            'sender' => $this->sender,
            'msg'    => $this->notification,
            'url'    => $this->route
        ];
    }
    public function toArray($notifiable)
    {
        return [
            ];
    }
}
