@component('emails.layout', ['title' => 'We received your enquiry'])
<p style="margin:0 0 14px;">Dear {{ $enquiry->name }},</p>
<p style="margin:0 0 14px;">{!! nl2br(e($introText)) !!}</p>
<p style="margin:0 0 8px;color:#566c81;font-size:13px;">A copy of your message:</p>
<div style="padding:14px 16px;background:#f5f8fc;border-radius:8px;font-size:14px;">
    <strong>{{ $enquiry->service }}</strong><br>{!! nl2br(e($enquiry->message)) !!}
</div>
<p style="margin:20px 0 0;">If your request is urgent, call us on
@php($phones = is_array($settings['phone_numbers'] ?? null) ? $settings['phone_numbers'] : [])
<strong>{{ $phones[0] ?? '+250 788 842 990' }}</strong> or reply to this email.</p>
<p style="margin:20px 0 0;">Kind regards,<br>{{ $settings['business_name'] ?? 'GECO Africa' }}</p>
@endcomponent
