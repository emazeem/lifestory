<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubscriptionAlertEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public array $data){}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Subscription Alert',
        );
    }

    public function build()
    {
        return $this->view('emails.subscription-alert-email')->with($this->data);
    }
}
