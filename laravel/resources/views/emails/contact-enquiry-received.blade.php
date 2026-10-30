<h1>New GECO Africa website enquiry</h1>

<p><strong>From:</strong> {{ $enquiry->name }}</p>
<p><strong>Email:</strong> {{ $enquiry->email }}</p>
<p><strong>Phone:</strong> {{ $enquiry->phone }}</p>
<p><strong>Enquiry area:</strong> {{ $enquiry->service }}</p>

<h2>Message</h2>
<p>{!! nl2br(e($enquiry->message)) !!}</p>
