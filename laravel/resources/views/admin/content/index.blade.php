@extends('layouts.admin')
@section('title', $definition['label'])
@section('content')
<header class="page-heading"><div><p class="eyebrow">Website content</p><h1>{{ $definition['label'] }}</h1><p class="muted">Manage the English content shown on the public website.</p></div><a class="button button-primary" href="{{ route('admin.content.create', $section) }}">Add {{ $definition['singular'] }}</a></header>
<section class="panel">
<form method="get" class="filter-row"><label class="sr-only" for="search-content">Search content</label><input id="search-content" name="q" value="{{ $search }}" placeholder="Search titles and content"><label class="sr-only" for="filter-status">Filter by status</label><select id="filter-status" name="status"><option value="">All statuses</option><option value="published" @selected($status === 'published')>Published</option><option value="draft" @selected($status === 'draft')>Drafts</option></select><button class="button button-outline" type="submit">Filter</button></form>
<div class="table-scroll"><table class="content-table content-list-table"><thead><tr><th>Title</th><th>Status</th><th>Updated</th><th>Actions</th></tr></thead><tbody>
@forelse ($entries as $entry)
    @php($contentFields = $entry->translatedFields('en'))
    @php($title = $contentFields['title'] ?? $contentFields['name'] ?? $contentFields['label'] ?? 'Untitled')
    @php($image = $contentFields['image'] ?? $contentFields['coverImage'] ?? $contentFields['portrait'] ?? null)
    @php($viewRoutes = ['service' => 'services', 'project_reference' => 'projects', 'project_feature' => 'projects', 'team_member' => 'team', 'partner' => 'partners', 'gallery_item' => 'gallery', 'blog_post' => 'blog.index', 'activity' => 'activities'])
    @php($publicUrl = isset($viewRoutes[$entry->type]) ? url('/'.str_replace('.', '/', $viewRoutes[$entry->type]).'/'.($entry->type === 'blog_post' ? $entry->slug : '')) : null)
    <tr><td><div class="content-title-cell">@if($image)<img src="{{ asset(ltrim($image, '/')) }}" alt="" loading="lazy">@endif<div><strong>{{ $title }}</strong><small>{{ $entry->slug }}</small></div></div></td><td><span class="status-pill {{ $entry->status === 'published' ? 'status-active' : 'status-draft' }}">{{ ucfirst($entry->status) }}</span></td><td><time title="{{ $entry->updated_at?->format('d M Y, H:i') }}">{{ $entry->updated_at?->diffForHumans() }}</time></td><td><div class="content-actions"><a class="button button-small button-outline" href="{{ route('admin.content.edit', [$section, $entry]) }}">Edit</a>@if($entry->status === 'published' && isset($viewRoutes[$entry->type]))<a class="button button-small button-quiet" href="{{ $entry->type === 'blog_post' ? route('blog.show', $entry->slug) : url('/'.str_replace('.', '/', $viewRoutes[$entry->type]).'/') }}" target="_blank" rel="noopener">View on site ↗</a>@endif</div></td></tr>
@empty <tr><td colspan="4" class="empty-row"><p>No {{ strtolower($definition['label']) }} yet.</p><a class="button button-primary" href="{{ route('admin.content.create', $section) }}">Add {{ $definition['singular'] }}</a></td></tr> @endforelse
</tbody></table></div><div class="pagination-wrap">{{ $entries->links('vendor.pagination.cms') }}</div>
</section>
@endsection
