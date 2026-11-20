@extends('layouts.admin')
@section('title','Enquiries')
@section('content')
<header class="page-heading"><div><p class="eyebrow">Website messages</p><h1>Enquiries</h1><p class="muted">Review and track messages submitted through the contact form. Every enquiry is saved here, even if an email fails.</p></div><a class="button button-outline" href="{{ route('admin.email.index') }}">Email delivery</a></header>
<section class="panel">
<form method="get" class="filter-row"><label class="sr-only" for="enquiry-status">Filter by status</label><select id="enquiry-status" name="status"><option value="">All statuses</option>@foreach(['new','contacted','closed'] as $choice)<option value="{{ $choice }}" @selected($status===$choice)>{{ ucfirst($choice) }}</option>@endforeach</select><button class="button button-outline">Filter</button></form>
<div class="table-scroll"><table class="content-table"><thead><tr><th>Received</th><th>Sender</th><th>Service</th><th>Message and follow-up</th><th>Emails</th><th>Status</th></tr></thead><tbody>
@forelse($enquiries as $enquiry)
<tr>
<td>{{ $enquiry->created_at?->format('d M Y H:i') }}</td>
<td><strong>{{ $enquiry->name }}</strong><br><a href="mailto:{{ $enquiry->email }}">{{ $enquiry->email }}</a><br>{{ $enquiry->phone }}</td>
<td>{{ $enquiry->service }}</td>
<td><p>{{ $enquiry->message }}</p><form method="post" action="{{ route('admin.enquiries.update',$enquiry) }}" class="form-stack">@csrf @method('PUT')<label class="sr-only" for="status-{{ $enquiry->id }}">Status</label><select id="status-{{ $enquiry->id }}" name="status">@foreach(['new','contacted','closed'] as $choice)<option value="{{ $choice }}" @selected($enquiry->status===$choice)>{{ ucfirst($choice) }}</option>@endforeach</select><label class="sr-only" for="note-{{ $enquiry->id }}">Internal note</label><textarea id="note-{{ $enquiry->id }}" name="admin_note" rows="2" maxlength="5000" placeholder="Internal follow-up note">{{ $enquiry->admin_note }}</textarea><button class="button button-small button-outline">Save follow-up</button></form></td>
<td>
    @foreach (['notification' => 'Team notification', 'acknowledgement' => 'Reply to sender'] as $which => $label)
        @php($sentAt = $enquiry->{$which.'_sent_at'})
        @php($error = $enquiry->{$which.'_error'})
        <div class="mail-row">
            <small>{{ $label }}</small><br>
            @if ($sentAt)<span class="mail-status mail-status-ok" title="{{ $sentAt->format('d M Y H:i') }}">Sent</span>
            @elseif ($error)<span class="mail-status mail-status-failed">Failed</span><p class="mail-error">{{ \Illuminate\Support\Str::limit($error, 160) }}</p>
            @else<span class="mail-status mail-status-pending">Not sent</span>@endif
            @if (!$sentAt)<form method="post" action="{{ route('admin.enquiries.resend', $enquiry) }}">@csrf<input type="hidden" name="email" value="{{ $which }}"><button class="button button-small button-quiet">{{ $error ? 'Try again' : 'Send' }}</button></form>@endif
        </div>
    @endforeach
</td>
<td><span class="status-pill status-{{ $enquiry->status==='new'?'draft':'active' }}">{{ ucfirst($enquiry->status) }}</span></td>
</tr>
@empty<tr><td colspan="6" class="empty-row">No enquiries yet.</td></tr>@endforelse
</tbody></table></div><div class="pagination-wrap">{{ $enquiries->links('vendor.pagination.cms') }}</div></section>
@endsection
