<?php

namespace App\Mail;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/** Sent to the GECO team when someone submits the contact form. Reply goes straight to the sender. */
class ContactEnquiryReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public ContactMessage $enquiry)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New website enquiry from '.$this->enquiry->name.($this->enquiry->service ? ' · '.$this->enquiry->service : ''),
            replyTo: [new Address($this->enquiry->email, $this->enquiry->name)],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact-enquiry-received',
            text: 'emails.contact-enquiry-received-text',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
