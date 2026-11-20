@php($phones = is_array($settings['phone_numbers'] ?? null) ? $settings['phone_numbers'] : [])
Dear {!! $enquiry->name !!},

{!! $introText !!}

A copy of your message ({!! $enquiry->service !!}):
{!! $enquiry->message !!}

If your request is urgent, call us on {!! $phones[0] ?? '+250 788 842 990' !!} or reply to this email.

Kind regards,
{!! $settings['business_name'] ?? 'GECO Africa' !!}
