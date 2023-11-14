<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendForgetPasswordEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public array $data){}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: config('app.name').' Password reset email',
        );
    }

    public function build()
    {
        return $this->view('emails.send-forget-password-email')->with($this->data);
    }
}
