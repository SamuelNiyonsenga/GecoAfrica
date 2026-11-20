Hello {!! $user->name !!},

@if ($invitation)
An account has been created for you on the GECO Africa website CMS. Choose your password here:
@else
Someone asked to reset the password for your GECO Africa CMS account. If it was you, choose a new password here:
@endif
{!! $url !!}

This link works once and expires in {!! $expiresMinutes >= 120 ? intdiv($expiresMinutes, 60).' hours' : $expiresMinutes.' minutes' !!}.
@unless ($invitation)
If you did not ask for this, you can ignore this email. Your password has not changed.
@endunless
