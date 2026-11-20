New website enquiry

From: {!! $enquiry->name !!}
Email: {!! $enquiry->email !!}
Phone: {!! $enquiry->phone !!}
Enquiry about: {!! $enquiry->service !!}
Received: {!! $enquiry->created_at?->timezone('Africa/Kigali')->format('j M Y, H:i') !!} (Kigali time)

Message:
{!! $enquiry->message !!}

Reply to this email to answer {!! $enquiry->name !!} directly.
Track follow-up in the CMS: {!! route('admin.enquiries.index') !!}
