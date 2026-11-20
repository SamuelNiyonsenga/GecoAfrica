@component('emails.layout', ['title' => $invitation ? 'Set up your account' : 'Reset your password'])
<p style="margin:0 0 14px;">Hello {{ $user->name }},</p>
@if ($invitation)
<p style="margin:0 0 18px;">An account has been created for you on the GECO Africa website CMS. Choose your password to sign in.</p>
@else
<p style="margin:0 0 18px;">Someone asked to reset the password for your GECO Africa CMS account. If it was you, choose a new password below.</p>
@endif
<p style="margin:0 0 22px;"><a href="{{ $url }}" style="display:inline-block;background:#dd1c26;color:#ffffff;text-decoration:none;font-weight:bold;padding:12px 20px;border-radius:7px;">{{ $invitation ? 'Choose my password' : 'Choose a new password' }}</a></p>
<p style="margin:0 0 12px;font-size:13px;color:#566c81;">This link works once and expires in {{ $expiresMinutes >= 120 ? intdiv($expiresMinutes, 60).' hours' : $expiresMinutes.' minutes' }}. If the button does not work, copy this address into your browser:<br><span style="word-break:break-all;">{{ $url }}</span></p>
@unless ($invitation)<p style="margin:0;font-size:13px;color:#566c81;">If you did not ask for this, you can ignore this email. Your password has not changed.</p>@endunless
@endcomponent
