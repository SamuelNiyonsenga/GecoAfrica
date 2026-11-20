@extends('layouts.admin')
@section('title', 'Email delivery')
@section('content')
<header class="page-heading"><div><p class="eyebrow">Search &amp; email</p><h1>Email delivery</h1><p class="muted">Check how the website sends email and send yourself a test.</p></div></header>

@if ($notSending)
    <div class="notice notice-error" role="alert"><strong>Emails are not being sent.</strong> The website is set to "{{ $mailer }}", which only writes emails to a log file. Add the SMTP details to the <code>.env</code> file on the server (see DEPLOY.md, section 5), then send a test below.</div>
@endif

<div class="two-column">
    <section class="panel">
        <h2>Send a test email</h2>
        <p class="muted">Sends a short message through the same mail server the contact form uses.</p>
        <form method="post" action="{{ route('admin.email.test') }}" class="form-stack">
            @csrf
            <label for="test-to">Send to</label>
            <input id="test-to" name="to" type="email" value="{{ old('to', auth()->user()->email) }}" required>
            @error('to')<p class="field-error">{{ $message }}</p>@enderror
            <button class="button button-primary" type="submit">Send test email</button>
        </form>
    </section>

    <section class="panel">
        <h2>Current settings</h2>
        <p class="muted">Server settings come from the <code>.env</code> file. Recipients and the automatic reply are in <a href="{{ route('admin.settings.edit') }}#email-settings">Site settings</a>.</p>
        <dl class="config-list">
            <dt>Method</dt><dd>{{ $mailer }}</dd>
            @if ($mailer === 'smtp')
                <dt>Mail server</dt><dd>{{ $smtp['host'] }}:{{ $smtp['port'] }} {{ $smtp['scheme'] ? '('.$smtp['scheme'].')' : '' }}</dd>
                <dt>Sign-in</dt><dd>{{ $smtp['username'] ?: 'none' }} · password {{ $smtp['password_set'] ? 'set' : 'not set' }}</dd>
            @endif
            <dt>Sent from</dt><dd>{{ $from['name'] ?? '' }} &lt;{{ $from['address'] ?? '' }}&gt;</dd>
            <dt>Enquiries go to</dt><dd>{{ implode(', ', $recipients) }}</dd>
            <dt>Automatic reply</dt><dd>{{ $autoReply ? 'On: the sender gets a confirmation email' : 'Off' }}</dd>
        </dl>
    </section>
</div>

<section class="panel">
    <div class="panel-heading"><div><h2>Enquiry notifications that failed</h2><p class="muted">The enquiry itself is always saved. Resend once the mail settings are fixed.</p></div><a href="{{ route('admin.enquiries.index') }}">All enquiries</a></div>
    @if ($failed->isEmpty())
        <p class="empty-state">No failed notifications.</p>
    @else
        <div class="table-scroll"><table class="content-table"><thead><tr><th>Received</th><th>From</th><th>Reason</th><th></th></tr></thead><tbody>
        @foreach ($failed as $enquiry)
            <tr><td>{{ $enquiry->created_at?->format('d M Y H:i') }}</td><td>{{ $enquiry->name }}<br><small>{{ $enquiry->email }}</small></td><td><p class="mail-error">{{ $enquiry->notification_error }}</p></td>
            <td><form method="post" action="{{ route('admin.enquiries.resend', $enquiry) }}">@csrf<input type="hidden" name="email" value="notification"><button class="button button-small button-outline">Resend</button></form></td></tr>
        @endforeach
        </tbody></table></div>
    @endif
</section>
@endsection
