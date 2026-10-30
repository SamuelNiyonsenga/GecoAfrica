@extends('layouts.admin')
@section('title','Activity log')
@section('content')
<header class="page-heading"><div><p class="eyebrow">System</p><h1>Activity log</h1><p class="muted">Recent content and account changes made by CMS users.</p></div></header>
<section class="panel"><div class="table-scroll"><table class="content-table"><thead><tr><th>When</th><th>Person</th><th>Action</th><th>Area</th><th>Record</th></tr></thead><tbody>@forelse($logs as $log)<tr><td>{{ $log->created_at?->format('d M Y H:i') }}</td><td>{{ $log->user?->name ?? 'Former user' }}</td><td>{{ str($log->action)->replace('.',' ')->title() }}</td><td>{{ str($log->entity_type)->replace('_',' ')->title() }}</td><td><code>{{ $log->entity_key }}</code></td></tr>@empty<tr><td colspan="5" class="empty-row">No activity recorded yet.</td></tr>@endforelse</tbody></table></div><div class="pagination-wrap">{{ $logs->links('vendor.pagination.cms') }}</div></section>
@endsection