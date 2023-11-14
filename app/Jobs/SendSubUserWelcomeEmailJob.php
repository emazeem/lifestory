<?php

namespace App\Jobs;

use App\Mail\SendSubUserWelcomeEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendSubUserWelcomeEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function handle(): void
    {
        $email = new SendSubUserWelcomeEmail($this->getData());
        Mail::to($this->data['email'])->send($email);
    }

    protected function getData()
    {
        return $this->data;
    }
}
