<?php

namespace App\Mail;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactEnquiryReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public ContactMessage $enquiry)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New GECO Africa website enquiry',
            replyTo: [$this->enquiry->email],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact-enquiry-received',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
