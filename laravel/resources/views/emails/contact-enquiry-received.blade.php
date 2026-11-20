@component('emails.layout', ['title' => 'New website enquiry'])
<h1 style="margin:0 0 16px;font-size:22px;color:#05284d;">New website enquiry</h1>
<table role="presentation" cellpadding="0" cellspacing="0" style="width:100%;font-size:15px;margin-bottom:18px;">
    <tr><td style="padding:4px 12px 4px 0;color:#566c81;width:120px;">From</td><td style="padding:4px 0;"><strong>{{ $enquiry->name }}</strong></td></tr>
    <tr><td style="padding:4px 12px 4px 0;color:#566c81;">Email</td><td style="padding:4px 0;"><a href="mailto:{{ $enquiry->email }}" style="color:#00376d;">{{ $enquiry->email }}</a></td></tr>
    <tr><td style="padding:4px 12px 4px 0;color:#566c81;">Phone</td><td style="padding:4px 0;"><a href="tel:{{ preg_replace('/[^0-9+]/', '', $enquiry->phone) }}" style="color:#00376d;">{{ $enquiry->phone }}</a></td></tr>
    <tr><td style="padding:4px 12px 4px 0;color:#566c81;">Enquiry about</td><td style="padding:4px 0;">{{ $enquiry->service }}</td></tr>
    <tr><td style="padding:4px 12px 4px 0;color:#566c81;">Received</td><td style="padding:4px 0;">{{ $enquiry->created_at?->timezone('Africa/Kigali')->format('j M Y, H:i') }} (Kigali time)</td></tr>
</table>
<h2 style="margin:0 0 8px;font-size:16px;color:#05284d;">Message</h2>
<div style="padding:14px 16px;background:#f5f8fc;border-radius:8px;">{!! nl2br(e($enquiry->message)) !!}</div>
<p style="margin:22px 0 0;">Reply to this email to answer {{ $enquiry->name }} directly, or <a href="{{ route('admin.enquiries.index') }}" style="color:#00376d;">open the enquiry in the CMS</a> to track follow-up.</p>
@endcomponent
