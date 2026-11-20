@extends('layouts.admin')
@section('title', $definition['label'])
@section('content')
@php $canReorder = $search === '' && $status === ''; @endphp
<header class="page-heading">
    <div>
        <p class="eyebrow">Website content</p>
        <h1>{{ $definition['label'] }}</h1>
        <p class="muted">Manage the English content shown on the public website. Use ↑ ↓ to change the order visitors see.</p>
    </div>
    <a class="button button-primary" href="{{ route('admin.content.create', $section) }}">Add {{ $definition['singular'] }}</a>
</header>

@if ($section === 'gallery')
    <section class="panel bulk-upload-panel">
        <div class="panel-heading"><div><h2>Add many photos at once</h2><p class="muted">Each photo becomes its own gallery item, named after the file. You can add descriptions afterwards.</p></div></div>
        <form method="post" action="{{ route('admin.content.gallery-bulk') }}" enctype="multipart/form-data" class="bulk-upload-form" data-bulk-upload>
            @csrf
            <div class="field"><label for="bulk-files">Photos or videos (up to 30)</label><input id="bulk-files" name="files[]" type="file" multiple required accept="image/jpeg,image/png,image/webp,image/gif,video/mp4,video/webm">@error('files')<span class="field-error">{{ $message }}</span>@enderror @error('files.*')<span class="field-error">{{ $message }}</span>@enderror</div>
            <div class="field"><label for="bulk-category">Category</label><select id="bulk-category" name="category"><option value="">No category</option>@foreach($galleryCategories as $value => $label)<option value="{{ $value }}">{{ $label }}</option>@endforeach</select></div>
            <label class="check-field"><input type="checkbox" name="publish" value="1" checked> Publish straight away</label>
            <button class="button button-primary" type="submit">Upload photos</button>
            <p class="muted" data-bulk-status aria-live="polite"></p>
        </form>
    </section>
@endif

<section class="panel">
    <form method="get" class="filter-row">
        <label class="sr-only" for="search-content">Search content</label><input id="search-content" name="q" value="{{ $search }}" placeholder="Search titles and content">
        <label class="sr-only" for="filter-status">Filter by status</label>
        <select id="filter-status" name="status"><option value="">All statuses</option><option value="published" @selected($status === 'published')>Published</option><option value="draft" @selected($status === 'draft')>Drafts</option></select>
        <button class="button button-outline" type="submit">Filter</button>
    </form>
    <div class="table-scroll">
        <table class="content-table content-list-table">
            <thead><tr>@if($canReorder)<th><span class="sr-only">Order</span></th>@endif<th>Title</th><th>Status</th><th>Updated</th><th>Actions</th></tr></thead>
            <tbody>
            @forelse ($entries as $entry)
                @php
                    $contentFields = $entry->status === 'published' ? $entry->translatedFields('en') : $entry->draftFields('en');
                    $title = ($contentFields['title'] ?? '') ?: (($contentFields['name'] ?? '') ?: (($contentFields['label'] ?? '') ?: 'Untitled'));
                    $image = $contentFields['image'] ?? $contentFields['coverImage'] ?? $contentFields['portrait'] ?? null;
                    $publicUrl = \App\Support\CmsContentTypes::publicUrl($entry->type, $entry->slug);
                @endphp
                <tr>
                    @if ($canReorder)
                        <td class="order-cell">
                            <form method="post" action="{{ route('admin.content.move', [$section, $entry]) }}">@csrf<input type="hidden" name="direction" value="up"><button class="button button-small button-quiet" type="submit" aria-label="Move {{ $title }} up" @disabled($loop->first && $entries->onFirstPage())>↑</button></form>
                            <form method="post" action="{{ route('admin.content.move', [$section, $entry]) }}">@csrf<input type="hidden" name="direction" value="down"><button class="button button-small button-quiet" type="submit" aria-label="Move {{ $title }} down" @disabled($loop->last && !$entries->hasMorePages())>↓</button></form>
                        </td>
                    @endif
                    <td><div class="content-title-cell">@if($image && is_string($image))<img src="{{ asset(ltrim($image, '/')) }}" alt="" loading="lazy">@endif<div><strong>{{ $title }}</strong><small>{{ $entry->slug }}</small></div></div></td>
                    <td><span class="status-pill {{ $entry->status === 'published' ? 'status-active' : 'status-draft' }}">{{ ucfirst($entry->status) }}</span></td>
                    <td><time title="{{ $entry->updated_at?->format('d M Y, H:i') }}">{{ $entry->updated_at?->diffForHumans() }}</time></td>
                    <td><div class="content-actions">
                        <a class="button button-small button-outline" href="{{ route('admin.content.edit', [$section, $entry]) }}">Edit</a>
                        <form method="post" action="{{ route('admin.content.duplicate', [$section, $entry]) }}">@csrf<button class="button button-small button-quiet" type="submit">Duplicate</button></form>
                        @if ($entry->status === 'published' && $publicUrl)<a class="button button-small button-quiet" href="{{ $publicUrl }}" target="_blank" rel="noopener">View on site ↗</a>@endif
                    </div></td>
                </tr>
            @empty
                <tr><td colspan="{{ $canReorder ? 5 : 4 }}" class="empty-row"><p>No {{ strtolower($definition['label']) }} yet.</p><a class="button button-primary" href="{{ route('admin.content.create', $section) }}">Add {{ $definition['singular'] }}</a></td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination-wrap">{{ $entries->links('vendor.pagination.cms') }}</div>
</section>
@endsection
