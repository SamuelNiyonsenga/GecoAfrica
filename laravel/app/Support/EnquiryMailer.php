<?php

namespace App\Support;

use App\Mail\ContactEnquiryAcknowledgement;
use App\Mail\ContactEnquiryReceived;
use App\Models\ContactMessage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Throwable;

/**
 * Sends the two emails an enquiry triggers and records on the enquiry whether each
 * one was handed to the mail server, so the CMS can show failures and resend them.
 */
class EnquiryMailer
{
    /** Where enquiry notifications go: the CMS setting (comma-separated allowed), else CONTACT_RECIPIENT. */
    public static function recipients(): array
    {
        $configured = (string) (Seo::settings()['notification_email'] ?? '');
        $emails = array_values(array_unique(array_filter(
            array_map(static fn (string $email): string => mb_strtolower(trim($email)), preg_split('/[,;\s]+/', $configured) ?: []),
            static fn (string $email): bool => filter_var($email, FILTER_VALIDATE_EMAIL) !== false,
        )));

        return $emails ?: [(string) config('contact.recipient')];
    }

    public static function autoReplyEnabled(): bool
    {
        $settings = Seo::settings();

        return !array_key_exists('auto_reply_enabled', $settings) || !empty($settings['auto_reply_enabled']);
    }

    /** Sends both emails. Returns true when the team notification was sent. */
    public static function deliver(ContactMessage $enquiry): bool
    {
        $sent = self::sendNotification($enquiry);
        if (!self::autoReplyEnabled()) {
            return $sent;
        }
        // If the mail server could not even be reached, the reply would fail the same way; skip the second wait.
        if (!$sent && preg_match('/timed out|could not be established|Connection refused|getaddrinfo|Unable to connect/i', (string) $enquiry->notification_error)) {
            $enquiry->forceFill(['acknowledgement_error' => now()->format('d M Y H:i').' · Not attempted: the mail server could not be reached.'])->save();

            return false;
        }
        self::sendAcknowledgement($enquiry);

        return $sent;
    }

    public static function sendNotification(ContactMessage $enquiry): bool
    {
        try {
            Mail::to(self::recipients())->send(new ContactEnquiryReceived($enquiry));
            $enquiry->forceFill(['notification_sent_at' => now(), 'notification_error' => null])->save();

            return true;
        } catch (Throwable $exception) {
            report($exception);
            $enquiry->forceFill(['notification_error' => self::describe($exception)])->save();

            return false;
        }
    }

    public static function sendAcknowledgement(ContactMessage $enquiry): bool
    {
        try {
            Mail::to($enquiry->email, $enquiry->name)->send(new ContactEnquiryAcknowledgement($enquiry));
            $enquiry->forceFill(['acknowledgement_sent_at' => now(), 'acknowledgement_error' => null])->save();

            return true;
        } catch (Throwable $exception) {
            report($exception);
            $enquiry->forceFill(['acknowledgement_error' => self::describe($exception)])->save();

            return false;
        }
    }

    /** A short, readable reason for the CMS. Never includes the mail password. */
    public static function describe(Throwable $exception): string
    {
        $message = $exception->getMessage();
        $password = (string) config('mail.mailers.smtp.password');
        if ($password !== '') {
            $message = str_replace($password, '••••', $message);
        }

        return Str::limit(now()->format('d M Y H:i').' · '.trim(preg_replace('/\s+/', ' ', $message) ?? $message), 900);
    }
}
