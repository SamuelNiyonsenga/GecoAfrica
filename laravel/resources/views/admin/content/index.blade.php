@extends('layouts.admin')
@section('title', $definition['label'])
@section('content')
<header class="page-heading"><div><p class="eyebrow">Website content</p><h1>{{ $definition['label'] }}</h1><p class="muted">Manage the English content shown on the public website.</p></div><a class="button button-primary" href="{{ route('admin.content.create', $section) }}">Add {{ $definition['singular'] }}</a></header>
<section class="panel">
<form method="get" class="filter-row"><label class="sr-only" for="search-content">Search content</label><input id="search-content" name="q" value="{{ $search }}" placeholder="Search titles and content"><label class="sr-only" for="filter-status">Filter by status</label><select id="filter-status" name="status"><option value="">All statuses</option><option value="published" @selected($status === 'published')>Published</option><option value="draft" @selected($status === 'draft')>Drafts</option></select><button class="button button-outline" type="submit">Filter</button></form>
<div class="table-scroll"><table class="content-table"><thead><tr><th>Title</th><th>Address</th><th>Status</th><th>Updated</th><th><span class="sr-only">Actions</span></th></tr></thead><tbody>
@forelse ($entries as $entry)
    @php($contentFields = $entry->translatedFields('en'))
    <tr><td><strong>{{ $contentFields['title'] ?? $contentFields['name'] ?? $contentFields['label'] ?? 'Untitled' }}</strong></td><td><code>{{ $entry->slug }}</code></td><td><span class="status-pill {{ $entry->status === 'published' ? 'status-active' : 'status-draft' }}">{{ ucfirst($entry->status) }}</span></td><td>{{ $entry->updated_at?->format('d M Y') }}</td><td><a class="button button-small button-outline" href="{{ route('admin.content.edit', [$section, $entry]) }}">Edit</a></td></tr>
@empty <tr><td colspan="5" class="empty-row">No content in this section yet.</td></tr> @endforelse
</tbody></table></div><div class="pagination-wrap">{{ $entries->links('vendor.pagination.cms') }}</div>
</section>
@endsection