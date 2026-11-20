<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\CmsTestMail;
use App\Models\CmsAuditLog;
use App\Models\ContactMessage;
use App\Support\EnquiryMailer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Throwable;

/** CMS → Email delivery: see how the site sends email, send a test, and resend failed enquiry emails. */
class CmsEmailController extends Controller
{
    public function index(): View
    {
        $mailer = (string) config('mail.default');
        $smtp = (array) config('mail.mailers.smtp', []);

        return view('admin.system.email', [
            'mailer' => $mailer,
            'smtp' => [
                'host' => $smtp['host'] ?? '',
                'port' => $smtp['port'] ?? '',
                'scheme' => $smtp['scheme'] ?? '',
                'username' => $smtp['username'] ?? '',
                'password_set' => filled($smtp['password'] ?? null),
            ],
            'from' => config('mail.from'),
            'recipients' => EnquiryMailer::recipients(),
            'autoReply' => EnquiryMailer::autoReplyEnabled(),
            'notSending' => in_array($mailer, ['log', 'array'], true),
            'failed' => ContactMessage::query()->whereNull('notification_sent_at')->whereNotNull('notification_error')->latest()->limit(20)->get(),
        ]);
    }

    public function test(Request $request): RedirectResponse
    {
        $data = $request->validate(['to' => ['required', 'email', 'max:255']]);
        try {
            Mail::to($data['to'])->send(new CmsTestMail($request->user()->name));
        } catch (Throwable $exception) {
            report($exception);
            return back()->withInput()->withErrors(['to' => 'The test email could not be sent: '.EnquiryMailer::describe($exception)]);
        }
        $this->audit($request, 'email.test', 'email', $data['to']);
        if (in_array(config('mail.default'), ['log', 'array'], true)) {
            return back()->withErrors(['to' => 'No email left the website: MAIL_MAILER is "'.config('mail.default').'", so messages are only written to the log. Set the SMTP details in the .env file (see DEPLOY.md).']);
        }

        return back()->with('success', 'The mail server accepted the test email for '.$data['to'].'. Check that inbox (and its spam folder) in the next few minutes.');
    }

    public function resend(Request $request, ContactMessage $enquiry): RedirectResponse
    {
        $which = $request->input('email') === 'acknowledgement' ? 'acknowledgement' : 'notification';
        $sent = $which === 'acknowledgement' ? EnquiryMailer::sendAcknowledgement($enquiry) : EnquiryMailer::sendNotification($enquiry);
        $this->audit($request, 'enquiry.resend-'.$which, 'enquiry', (string) $enquiry->id);

        return $sent
            ? back()->with('success', $which === 'acknowledgement' ? 'Automatic reply sent again to '.$enquiry->email.'.' : 'Enquiry notification sent again to '.implode(', ', EnquiryMailer::recipients()).'.')
            : back()->withErrors(['email' => 'Sending failed again: '.$enquiry->fresh()->{$which.'_error'}]);
    }

    private function audit(Request $request, string $action, string $type, string $key): void
    {
        CmsAuditLog::query()->create(['user_id' => $request->user()?->id, 'action' => $action, 'entity_type' => $type, 'entity_key' => $key, 'created_at' => now()]);
    }
}
