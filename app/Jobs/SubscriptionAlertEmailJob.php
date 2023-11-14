<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Mail\SubscriptionAlertEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SubscriptionAlertEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public array $data){}

    public function handle(): void
    {
        $email = new SubscriptionAlertEmail($this->data);
        Mail::to($this->data['email'])->send($email);
    }
}
