<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/** Link to choose a new CMS password. Also used to invite a new user to set their first password. */
class CmsPasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $user, public string $token, public bool $invitation = false)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: $this->invitation ? 'Set up your GECO Africa CMS account' : 'Reset your GECO Africa CMS password');
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.password-reset',
            text: 'emails.password-reset-text',
            with: [
                'url' => route('admin.password.reset', ['token' => $this->token, 'email' => $this->user->email]),
                'expiresMinutes' => (int) config('auth.passwords.users.expire', 60),
            ],
        );
    }
}
