<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/** Sent from CMS → Email delivery to prove the mail server settings work. */
class CmsTestMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public string $requestedBy)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Test email from the GECO Africa website');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.test', text: 'emails.test-text');
    }
}
