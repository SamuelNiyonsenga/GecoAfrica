@extends('layouts.admin')
@section('title', 'Edit '.$label)
@section('content')
<header class="page-heading"><div><p class="eyebrow"><a href="{{ route('admin.pages.index') }}">Pages</a> / English</p><h1>{{ $label }}</h1><p class="muted">Edit page text and search details. Published content stays live until you publish.</p></div></header>
@php($editorFields = $translation->draft_fields ?? [])
@if($page->page_key === 'home') @php(unset($editorFields['homeSections'])) @endif
<form method="post" action="{{ route('admin.pages.draft', $page->page_key) }}" class="panel editor-form">@csrf
@if($page->page_key === 'home')
<section class="home-section-manager"><h2>Homepage sections</h2><p class="muted">Choose which sections visitors see and set their display order. Lower numbers appear first.</p><div class="table-scroll"><table class="content-table"><thead><tr><th>Section</th><th>Show on site</th><th>Display order</th></tr></thead><tbody>@foreach($sectionSettings as $key => $section)<tr><td><strong>{{ $section['label'] }}</strong></td><td><input type="hidden" name="home_sections[{{ $key }}][visible]" value="0"><input type="checkbox" name="home_sections[{{ $key }}][visible]" value="1" @checked($section['visible']) aria-label="Show {{ $section['label'] }}"></td><td><input type="number" name="home_sections[{{ $key }}][order]" min="1" max="100" value="{{ $section['order'] }}" required aria-label="Display order for {{ $section['label'] }}"></td></tr>@endforeach</tbody></table></div></section><hr class="rule">
@endif
<h2>Page content and metadata</h2><div class="form-grid">
@foreach($editorFields as $key => $value)
@php($oldValue = old('fields.'.$key))
<div class="field {{ is_array($value) || in_array($key, ['pageDescription','ogImageAlt'], true) ? 'field-wide' : '' }}"><label for="page-field-{{ $key }}">{{ str($key)->headline() }}</label>
@if(is_array($value))<textarea id="page-field-{{ $key }}" name="fields[{{ $key }}]" rows="{{ in_array($key,['heroSlides','structuredData','projects'],true)?16:8 }}" class="code-field" spellcheck="false">{{ is_string($oldValue) ? $oldValue : json_encode($oldValue ?? $value, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) }}</textarea><small>Structured data. Keep valid JSON; this supports lists and nested fields.</small>
@elseif(in_array($key, ['pageDescription','ogImageAlt'], true))<textarea id="page-field-{{ $key }}" name="fields[{{ $key }}]" rows="3">{{ $oldValue ?? $value }}</textarea>
@else<input id="page-field-{{ $key }}" name="fields[{{ $key }}]" value="{{ $oldValue ?? $value }}" maxlength="20000">@endif
@error('fields.'.$key)<span class="field-error">{{ $message }}</span>@enderror</div>
@endforeach</div>
<div class="form-actions"><button class="button button-quiet" type="submit">Save draft</button><button class="button button-brand" type="submit" formaction="{{ route('admin.pages.publish', $page->page_key) }}">Publish this version</button></div></form>
<p class="muted">Last published {{ $translation->published_at?->diffForHumans() ?? 'never' }}</p>
@if($versions->isNotEmpty())<section class="panel"><h2>Version history</h2><div class="version-list">@foreach($versions as $version)<form method="post" action="{{ route('admin.pages.restore-version', [$page->page_key,$version]) }}">@csrf<span>{{ $version->created_at?->format('d M Y, H:i') }}</span><button class="button button-small button-outline">Restore to draft</button></form>@endforeach</div></section>@endif
@endsection