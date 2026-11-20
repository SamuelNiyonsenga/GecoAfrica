@component('emails.layout', ['title' => 'Test email'])
<h1 style="margin:0 0 14px;font-size:22px;color:#05284d;">Email is working</h1>
<p style="margin:0 0 12px;">This test was sent from the GECO Africa CMS by {{ $requestedBy }} on {{ now()->timezone('Africa/Kigali')->format('j M Y \a\t H:i') }} (Kigali time).</p>
<p style="margin:0;">If you can read this, the website can send contact-form notifications, automatic replies and password reset emails.</p>
@endcomponent
