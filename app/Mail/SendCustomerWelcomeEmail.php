<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendCustomerWelcomeEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public array $data){}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Customer Account On Lifestory Is Created',
        );
    }

    public function build()
    {
        return $this->view('emails.send-customer-welcome-email')->with($this->data);
    }

}
