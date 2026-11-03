@extends('layouts.admin')
@section('title', 'Edit '.$label)
@section('content')
@php
    use App\Support\CmsPageFields;
    $longText = static fn (string $key, $value): bool => is_string($value)
        && (mb_strlen($value) > 90 || preg_match('/(Description|Intro|Body|Lede|Text|Alt)$/', $key));
@endphp
<header class="page-heading">
    <div>
        <p class="eyebrow"><a href="{{ route('admin.pages.index') }}">Pages</a> / English</p>
        <h1>{{ $label }}</h1>
        <p class="muted">Edit the words on this page. Visitors keep seeing the published version until you publish.</p>
    </div>
    <a class="button button-outline" href="{{ route($page->page_key === 'home' ? 'home' : $page->page_key) }}" target="_blank" rel="noopener">View live page ↗</a>
</header>

<form method="post" action="{{ route('admin.pages.draft', $page->page_key) }}" class="editor-form page-editor-form" data-cms-page-form>
    @csrf

    @if ($page->page_key === 'home')
        <section class="panel home-section-manager">
            <h2>Homepage sections</h2>
            <p class="muted">Use the arrows to change the order sections appear in, and the switch to show or hide a section.</p>
            <ol class="section-order-list" data-section-order>
                @foreach ($sectionSettings as $key => $section)
                    <li class="section-order-row" data-section-row>
                        <span class="section-order-handle" aria-hidden="true">⋮⋮</span>
                        <span class="section-order-label">{{ $section['label'] }}</span>
                        <input type="hidden" name="home_sections[{{ $key }}][order]" value="{{ $section['order'] }}" data-section-rank>
                        <input type="hidden" name="home_sections[{{ $key }}][visible]" value="0">
                        <label class="section-visible">
                            <input type="checkbox" role="switch" name="home_sections[{{ $key }}][visible]" value="1" @checked($section['visible'])>
                            <span>Shown</span>
                        </label>
                        <span class="section-order-buttons">
                            <button class="button button-small button-outline" type="button" data-move-up aria-label="Move {{ $section['label'] }} up">↑</button>
                            <button class="button button-small button-outline" type="button" data-move-down aria-label="Move {{ $section['label'] }} down">↓</button>
                        </span>
                    </li>
                @endforeach
            </ol>
        </section>
    @endif

    @foreach ($fieldGroups as $group => $groupFields)
        @php($isSeo = $group === CmsPageFields::SEO_GROUP)
        @if ($isSeo)
            <details class="panel page-field-section search-sharing" @if($errors->hasAny(array_map(fn ($k) => 'fields.'.$k, array_keys($groupFields)))) open @endif>
                <summary><h2>Search &amp; sharing</h2><span class="muted">How this page appears in Google and when shared. Most people never need to change this.</span></summary>
        @else
            <section class="panel page-field-section">
                <h2>{{ $group }}</h2>
        @endif
                <div class="form-grid">
                    @foreach ($groupFields as $key => $value)
                        @php($meta = CmsPageFields::meta($key))
                        @php($oldValue = old('fields.'.$key))
                        <div class="field {{ is_array($value) || $longText($key, $value) ? 'field-wide' : '' }}">
                            <label for="page-field-{{ $key }}">{{ $meta['label'] }}</label>
                            @if (is_array($value))
                                <div class="structured-editor" data-structured-editor data-label="{{ $meta['label'] }}">
                                    <input type="hidden" id="page-field-{{ $key }}" name="fields[{{ $key }}]" value="{{ is_string($oldValue) ? $oldValue : json_encode($value, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) }}" data-structured-value>
                                    <div data-structured-root></div>
                                </div>
                            @elseif ($longText($key, $value))
                                <textarea id="page-field-{{ $key }}" name="fields[{{ $key }}]" rows="3" aria-describedby="page-hint-{{ $key }}">{{ $oldValue ?? $value }}</textarea>
                            @else
                                <input id="page-field-{{ $key }}" name="fields[{{ $key }}]" value="{{ $oldValue ?? $value }}" maxlength="20000" aria-describedby="page-hint-{{ $key }}">
                            @endif
                            <small id="page-hint-{{ $key }}">{{ $meta['hint'] }}</small>
                            @error('fields.'.$key)<span class="field-error">{{ $message }}</span>@enderror
                        </div>
                    @endforeach
                </div>
        @if ($isSeo)
            </details>
        @else
            </section>
        @endif
    @endforeach

    <div class="editor-actions page-editor-actions">
        <p class="editor-save-status muted" aria-live="polite">Last published {{ $translation->published_at?->diffForHumans() ?? 'never' }}.</p>
        <div class="editor-action-buttons">
            <button class="button button-outline" type="submit" name="intent" value="preview" formtarget="_blank">Preview</button>
            <button class="button button-outline" type="submit">Save draft</button>
            <button class="button button-primary" type="submit" formaction="{{ route('admin.pages.publish', $page->page_key) }}">Publish</button>
        </div>
    </div>
</form>

@if ($versions->isNotEmpty())
    <section class="panel">
        <h2>Version history</h2>
        <p class="muted">Restoring a version puts it back as a draft so you can check it before publishing.</p>
        <div class="version-list">
            @foreach ($versions as $version)
                <form method="post" action="{{ route('admin.pages.restore-version', [$page->page_key, $version]) }}">
                    @csrf
                    <span title="{{ $version->created_at?->format('d M Y, H:i') }}">{{ $version->note ?? 'Saved' }} · {{ $version->created_at?->diffForHumans() }}</span>
                    <button class="button button-small button-outline" type="submit">Restore to draft</button>
                </form>
            @endforeach
        </div>
    </section>
@endif
@endsection
