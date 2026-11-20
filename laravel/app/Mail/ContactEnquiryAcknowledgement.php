<?php

namespace App\Mail;

use App\Models\ContactMessage;
use App\Support\EnquiryMailer;
use App\Support\Seo;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/** Automatic "we received your enquiry" reply to the person who used the contact form. */
class ContactEnquiryAcknowledgement extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public ContactMessage $enquiry)
    {
    }

    public function envelope(): Envelope
    {
        $settings = Seo::settings();
        $replyTo = (string) ($settings['public_email'] ?? '') ?: EnquiryMailer::recipients()[0];

        return new Envelope(
            subject: 'We received your enquiry · '.($settings['business_name'] ?? 'GECO Africa'),
            replyTo: [new Address($replyTo, (string) ($settings['business_name'] ?? 'GECO Africa'))],
        );
    }

    public function content(): Content
    {
        $settings = Seo::settings();

        return new Content(
            view: 'emails.contact-enquiry-acknowledgement',
            text: 'emails.contact-enquiry-acknowledgement-text',
            with: [
                'introText' => trim((string) ($settings['auto_reply_message'] ?? '')) ?: 'Thank you for contacting GECO Africa. We have received your enquiry and a member of our team will get back to you, usually within two working days.',
                'settings' => $settings,
            ],
        );
    }
}
