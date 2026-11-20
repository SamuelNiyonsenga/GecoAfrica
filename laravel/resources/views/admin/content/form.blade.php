@extends('layouts.admin')
@section('title', $entry ? 'Edit '.$definition['singular'] : 'New '.$definition['singular'])
@section('content')
@php
    use App\Support\CmsContentTypes;
    $firstKey = array_key_exists('title', $definition['fields']) ? 'title' : (array_key_exists('name', $definition['fields']) ? 'name' : null);
    $mainFields = array_filter($definition['fields'], fn ($spec, $key) => $key !== $firstKey && empty($spec['legacy']) && ($spec['panel'] ?? null) !== 'seo', ARRAY_FILTER_USE_BOTH);
    $seoFields = array_filter($definition['fields'], fn ($spec) => ($spec['panel'] ?? null) === 'seo');
    $publicTemplate = CmsContentTypes::publicUrl($definition['type'], '__slug__');
    $fieldValue = fn ($key) => old('fields.'.$key, $fields[$key] ?? '');
    $trashedMediaFields = $trashedMediaFields ?? [];
@endphp

<header class="page-heading">
    <div>
        <p class="eyebrow"><a href="{{ route('admin.content.index', $section) }}">{{ $definition['label'] }}</a></p>
        <h1>{{ $entry ? 'Edit '.$definition['singular'] : 'New '.$definition['singular'] }}</h1>
        <p class="muted">Save a draft to keep changes private, or publish the finished version.</p>
    </div>
    @if ($entry)<span class="status-pill {{ $entry->status === 'published' ? 'status-active' : 'status-draft' }}">{{ ucfirst($entry->status) }}</span>@endif
</header>

<form method="post" action="{{ $entry ? route('admin.content.update', [$section, $entry]) : route('admin.content.store', $section) }}" class="content-editor-form" data-cms-editor-form data-title-field="{{ $firstKey }}" data-entry-existing="{{ $entry ? '1' : '0' }}">
    @csrf
    @if ($entry) @method('PUT') @endif

    <div class="content-editor-layout">
        <div class="content-editor-main panel">
            @if ($firstKey)
                @php($spec = $definition['fields'][$firstKey])
                <div class="field title-first">
                    <label for="field-{{ $firstKey }}">{{ $spec['label'] }} @if(!empty($spec['required']))<span aria-hidden="true">*</span>@endif</label>
                    <input id="field-{{ $firstKey }}" name="fields[{{ $firstKey }}]" value="{{ is_scalar($fieldValue($firstKey)) ? $fieldValue($firstKey) : '' }}" @required(!empty($spec['required']))>
                    <small>{{ $spec['hint'] }}</small>
                    @error('fields.'.$firstKey)<span class="field-error">{{ $message }}</span>@enderror
                </div>
            @endif

            <h2>Content</h2>
            <div class="form-grid">
                @foreach ($mainFields as $key => $spec)
                    @include('admin.content.partials.field', ['key' => $key, 'spec' => $spec, 'value' => $fieldValue($key)])
                @endforeach
            </div>

            <div class="editor-actions content-editor-actions">
                <p data-editor-status aria-live="polite">{{ $entry?->status === 'published' ? 'Save a draft to keep changes private, or save and publish to update the website.' : 'Save a draft or publish this content.' }}</p>
                <div class="editor-action-buttons">
                    <a class="button button-quiet" href="{{ route('admin.content.index', $section) }}">Back</a>
                    @if ($entry)<button class="button button-outline" type="submit" name="intent" value="preview" formtarget="_blank">Preview</button>@endif
                    <button class="button button-outline" type="submit" @if(!$entry) name="intent" value="draft" @endif>Save draft</button>
                    <button class="button button-primary" type="submit" @if($entry) formaction="{{ route('admin.content.publish', [$section, $entry]) }}" @else name="intent" value="publish" @endif data-editor-publish>Save &amp; publish</button>
                </div>
            </div>
        </div>

        <aside class="content-editor-aside">
            <section class="panel">
                <h2>Item details</h2>
                <div class="field">
                    <label for="slug">Web address</label>
                    <div class="slug-control">
                        <input id="slug" name="slug" value="{{ old('slug', $entry?->slug) }}" maxlength="180" pattern="[a-z0-9]+(-[a-z0-9]+)*" readonly @required((bool) $entry) data-slug-input aria-describedby="slug-help">
                        <button class="button button-small button-outline" type="button" data-slug-toggle aria-controls="slug" aria-pressed="false">Edit</button>
                    </div>
                    <small id="slug-help">
                        @if ($entry)
                            @if (CmsContentTypes::hasOwnPage($definition['type']) && $entry->status === 'published')
                                If you change this address, visitors using the old link are sent to the new one automatically.
                            @else
                                Changing this address changes the item's link.
                            @endif
                        @else
                            Created automatically from the {{ strtolower($definition['fields'][$firstKey]['label'] ?? 'title') }}. Select Edit to choose your own.
                        @endif
                        @if ($publicTemplate)<br>On the website: <span class="public-url" data-public-url data-public-template="{{ $publicTemplate }}">{{ str_replace('__slug__', old('slug', $entry?->slug) ?: '…', $publicTemplate) }}</span>@endif
                    </small>
                    @error('slug')<span class="field-error">{{ $message }}</span>@enderror
                </div>

                <div class="field">
                    <label for="sort-order">Display order</label>
                    <input id="sort-order" name="sort_order" type="number" min="0" value="{{ old('sort_order', $entry?->sort_order ?? ($nextSortOrder ?? 0)) }}">
                    <small>Lower numbers appear first. You can also use the ↑ ↓ buttons in the list.</small>
                </div>

                @if (!empty($definition['parent_type']))
                    <div class="field">
                        <label for="parent-id">Project group</label>
                        <select id="parent-id" name="parent_id">
                            <option value="">Choose a group</option>
                            @foreach ($parents as $parent)
                                <option value="{{ $parent->id }}" @selected((string) old('parent_id', $entry?->parent_id) === (string) $parent->id)>{{ $parent->draftFields('en')['title'] ?? $parent->translatedFields('en')['title'] ?? $parent->slug }}{{ $parent->status === 'published' ? '' : ' (draft, hidden until published)' }}</option>
                            @endforeach
                        </select>
                        <small>@if($parents->isEmpty())No groups yet. @endif<a href="{{ route('admin.content.create', 'project-groups') }}" target="_blank">Add a new group</a> (opens in a new tab), then reload this page.</small>
                    </div>
                @elseif (!empty($definition['group_options']))
                    <div class="field">
                        <label for="group-key">Group</label>
                        <select id="group-key" name="group_key">
                            @foreach ($definition['group_options'] as $optionValue => $optionLabel)
                                <option value="{{ $optionValue }}" @selected(old('group_key', $entry?->group_key ?? array_key_first($definition['group_options'])) === $optionValue)>{{ $optionLabel }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                @if ($entry?->is_featured || $section === 'featured-projects')
                    <label class="check-field"><input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $entry?->is_featured ?? true))> Feature this item</label>
                @endif

                @if ($entry)
                    <p class="muted">{{ $entry->status === 'published' ? 'Visitors see the published version until you publish changes.' : 'This draft is private until published.' }}</p>
                    <button class="button button-small button-outline" type="submit" form="duplicate-form">Duplicate as a new draft</button>
                @endif
            </section>

            @if ($seoFields)
                <section class="panel seo-panel" data-seo-panel data-fallback-title="{{ $firstKey ? 'fields['.$firstKey.']' : '' }}" data-fallback-description="{{ array_key_exists('summary', $definition['fields']) ? 'fields[summary]' : (array_key_exists('intro', $definition['fields']) ? 'fields[intro]' : '') }}" data-title-suffix="{{ $seoTitleSuffix ?? ' | GECO Africa' }}">
                    <h2>Search &amp; sharing</h2>
                    <p class="muted">How this page appears in Google. Everything here is optional.</p>
                    <div class="google-preview" aria-label="Google result preview">
                        <span class="google-preview-url" data-seo-preview-url>{{ $publicTemplate ? str_replace('__slug__', old('slug', $entry?->slug) ?: '…', $publicTemplate) : '' }}</span>
                        <span class="google-preview-title" data-seo-preview-title></span>
                        <span class="google-preview-description" data-seo-preview-description></span>
                    </div>
                    @foreach ($seoFields as $key => $spec)
                        @include('admin.content.partials.field', ['key' => $key, 'spec' => $spec, 'value' => $fieldValue($key)])
                    @endforeach
                </section>
            @endif
        </aside>
    </div>

    <dialog class="media-picker-dialog" data-media-dialog aria-labelledby="media-picker-title">
        <div class="media-picker-head"><h2 id="media-picker-title">Choose media</h2><button type="button" class="button button-quiet" data-media-close aria-label="Close media picker">Close</button></div>
        <p class="media-picker-hint" data-picker-hint hidden>Select each photo you want to add, then choose Done.</p>
        <div class="media-picker-filters">
            <label class="sr-only" for="picker-search">Search files</label><input id="picker-search" type="search" placeholder="Search file names" data-picker-search>
            <label class="sr-only" for="picker-type">Filter file type</label><select id="picker-type" data-picker-type><option value="">All types</option><option value="images">Images</option><option value="documents">Documents</option><option value="video">Video</option></select>
        </div>
        <div class="media-picker-grid" data-picker-grid aria-live="polite"></div>
        <button type="button" class="button button-outline" data-picker-more hidden>Load more</button>
        <label class="media-dropzone">Upload new files<input type="file" multiple data-picker-upload accept="image/*,video/mp4,video/webm,application/pdf"></label>
        <button type="button" class="button button-primary" data-media-close data-picker-done hidden>Done</button>
    </dialog>
</form>

@if ($entry)
    <form method="post" action="{{ route('admin.content.duplicate', [$section, $entry]) }}" id="duplicate-form" hidden>@csrf</form>
    @if ($entry->status === 'published')
        <form method="post" action="{{ route('admin.content.unpublish', [$section, $entry]) }}" class="inline-danger">@csrf<button class="button button-danger" type="submit">Unpublish</button><span class="muted">Visitors will no longer see this item.</span></form>
    @endif
    <form method="post" action="{{ route('admin.content.destroy', [$section, $entry]) }}" class="inline-danger" onsubmit="return confirm('Move this content to Trash?')">@csrf @method('DELETE')<button class="button button-danger" type="submit">Move to Trash</button></form>
    @if ($versions->isNotEmpty())
        <section class="panel">
            <h2>Version history</h2>
            <p class="muted">Restore an earlier published version to the draft for review.</p>
            <div class="version-list">
                @foreach ($versions as $version)
                    <form method="post" action="{{ route('admin.content.restore-version', [$section, $entry, $version]) }}">@csrf<span>{{ $version->created_at?->diffForHumans() }}</span><button class="button button-small button-outline">Restore to draft</button></form>
                @endforeach
            </div>
        </section>
    @endif
@endif
@endsection
