<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendSubUserWelcomeEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->data['customer_name'] . ' has sent you a personal invitation!',
        );
    }

    public function build()
    {
        return $this->view('emails.sub-user-welcome-email')->with($this->data);
    }

}
