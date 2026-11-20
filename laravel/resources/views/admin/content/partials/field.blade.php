{{-- One editor field. Expects: $key, $spec, $value, $categoryOptions, $trashedMediaFields --}}
@php
    $id = 'field-'.$key;
    $wide = in_array($spec['type'], ['textarea', 'json', 'richtext', 'gallery'], true);
    $isImagePath = fn ($path) => $path && preg_match('/\.(jpe?g|png|webp|gif|svg)$/i', (string) $path);
@endphp
<div class="field {{ $wide ? 'field-wide' : '' }} field-type-{{ $spec['type'] }}">
    @if ($spec['type'] === 'toggle')
        <input type="hidden" name="fields[{{ $key }}]" value="0">
        <label class="check-field" for="{{ $id }}"><input id="{{ $id }}" type="checkbox" name="fields[{{ $key }}]" value="1" @checked((string) $value === '1')> {{ $spec['label'] }}</label>
    @else
        <label for="{{ $id }}" @if(in_array($spec['type'], ['richtext', 'gallery'], true)) id="{{ $id }}-label" @endif>{{ $spec['label'] }} @if(!empty($spec['required']))<span aria-hidden="true">*</span>@endif</label>
    @endif

    @switch($spec['type'])
        @case('textarea')
            <textarea id="{{ $id }}" name="fields[{{ $key }}]" rows="4" @if(!empty($spec['max'])) maxlength="{{ $spec['max'] }}" data-char-count="{{ $spec['max'] }}" @endif @required(!empty($spec['required']))>{{ is_array($value) ? implode("\n", $value) : $value }}</textarea>
            @break

        @case('list')
            <textarea id="{{ $id }}" name="fields[{{ $key }}]" rows="5" placeholder="One item per line">{{ is_array($value) ? implode("\n", $value) : $value }}</textarea>
            @break

        @case('json')
            <div class="structured-editor" data-structured-editor data-label="{{ $spec['label'] }}">
                <input type="hidden" name="fields[{{ $key }}]" value="{{ is_array($value) ? json_encode($value, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) : ($value ?: '[]') }}" data-structured-value>
                <input type="hidden" value="{{ json_encode($spec['template'] ?? null, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) }}" data-structured-template>
                <div data-structured-root></div>
            </div>
            @break

        @case('richtext')
            <div class="richtext" data-richtext>
                <div class="richtext-toolbar" role="toolbar" aria-label="Text formatting" aria-controls="{{ $id }}">
                    <button type="button" data-command="formatBlock" data-value="p" title="Normal text">Text</button>
                    <button type="button" data-command="formatBlock" data-value="h2" title="Heading (adds it to the contents box)">Heading</button>
                    <button type="button" data-command="formatBlock" data-value="h3" title="Smaller heading">Subheading</button>
                    <span class="richtext-sep" aria-hidden="true"></span>
                    <button type="button" data-command="bold" title="Bold (Ctrl+B)"><strong>B</strong></button>
                    <button type="button" data-command="italic" title="Italic (Ctrl+I)"><em>I</em></button>
                    <button type="button" data-command="insertUnorderedList" title="Bullet list">• List</button>
                    <button type="button" data-command="insertOrderedList" title="Numbered list">1. List</button>
                    <button type="button" data-command="formatBlock" data-value="blockquote" title="Quote or highlighted note">Quote</button>
                    <span class="richtext-sep" aria-hidden="true"></span>
                    <button type="button" data-richtext-link title="Add a link to the selected words">Link</button>
                    <button type="button" data-richtext-image title="Insert a photo from the media library">Photo</button>
                    <button type="button" data-command="removeFormat" title="Remove bold, italic and links from the selection">Clear</button>
                </div>
                <div id="{{ $id }}" class="richtext-area" contenteditable="true" role="textbox" aria-multiline="true" aria-labelledby="{{ $id }}-label" data-richtext-area data-placeholder="Start writing…">{!! is_string($value) ? $value : '' !!}</div>
                <input type="hidden" name="fields[{{ $key }}]" value="{{ is_string($value) ? $value : '' }}" data-richtext-value>
            </div>
            @break

        @case('gallery')
            <div class="gallery-field" data-gallery-field data-name="fields[{{ $key }}][]" aria-labelledby="{{ $id }}-label">
                <input type="hidden" name="fields[{{ $key }}][]" value="">
                <ul class="gallery-field-list" data-gallery-list>
                    @foreach ((array) $value as $path)
                        @continue(!is_string($path) || $path === '')
                        <li class="gallery-field-item" data-gallery-item>
                            <input type="hidden" name="fields[{{ $key }}][]" value="{{ $path }}">
                            @if ($isImagePath($path))<img src="{{ asset(ltrim($path, '/')) }}" alt="">@else<span class="media-picker-file">{{ strtoupper(pathinfo($path, PATHINFO_EXTENSION)) }}</span>@endif
                            <span class="gallery-field-actions">
                                <button type="button" class="button button-small button-quiet" data-gallery-move="up" aria-label="Move photo earlier">←</button>
                                <button type="button" class="button button-small button-quiet" data-gallery-move="down" aria-label="Move photo later">→</button>
                                <button type="button" class="button button-small button-quiet" data-gallery-remove aria-label="Remove photo">Remove</button>
                            </span>
                        </li>
                    @endforeach
                </ul>
                <button type="button" id="{{ $id }}" class="button button-small button-outline" data-gallery-add>Add photos</button>
            </div>
            @break

        @case('media')
            <input type="hidden" id="{{ $id }}" name="fields[{{ $key }}]" value="{{ $value }}" data-media-value>
            <div class="media-picker-control">
                <img class="media-thumb" data-media-thumb src="{{ $isImagePath($value) ? asset(ltrim($value, '/')) : '' }}" alt="" @if(!$isImagePath($value)) hidden @endif>
                <span data-media-selected>{{ $value ? basename($value) : 'No file selected' }}</span>
                <button type="button" class="button button-small button-outline" data-media-picker-open data-field="{{ $id }}">Choose {{ preg_match('/pdf|document|priceList|cv/i', $key) ? 'file' : 'image' }}</button>
                <button type="button" class="button button-small button-quiet" data-media-remove="{{ $id }}" @if(!$value) hidden @endif>Remove</button>
            </div>
            @if (!empty($trashedMediaFields[$key]))<small class="media-trash-warning" role="status">This image is in Trash. Restore it or choose another.</small>@endif
            @break

        @case('category')
            <select id="{{ $id }}" name="fields[{{ $key }}]">
                <option value="">No {{ $spec['source'] === 'service' ? 'service' : 'category' }}</option>
                @foreach (($categoryOptions[$key] ?? []) as $optionValue => $optionLabel)
                    <option value="{{ $optionValue }}" @selected((string) $value === (string) $optionValue)>{{ $optionLabel }}</option>
                @endforeach
            </select>
            @if (empty($categoryOptions[$key]))
                @php($sourceSection = \App\Support\CmsContentTypes::sectionFor($spec['source']))
                <small class="field-note">Nothing to choose yet. @if($sourceSection)<a href="{{ route('admin.content.create', $sourceSection) }}" target="_blank">Add one first</a> (opens in a new tab), then reload this page.@endif</small>
            @endif
            @break

        @case('url')
            <input id="{{ $id }}" name="fields[{{ $key }}]" type="url" value="{{ is_scalar($value) ? $value : '' }}" placeholder="https://">
            @break

        @case('date')
            <input id="{{ $id }}" name="fields[{{ $key }}]" type="date" value="{{ is_scalar($value) ? $value : '' }}">
            @break

        @case('toggle')
            @break

        @default
            <input id="{{ $id }}" name="fields[{{ $key }}]" value="{{ is_scalar($value) ? $value : '' }}" @if(!empty($spec['max'])) maxlength="{{ $spec['max'] }}" data-char-count="{{ $spec['max'] }}" @endif @required(!empty($spec['required']))>
    @endswitch

    <small>{{ $spec['hint'] }}</small>
    @error('fields.'.$key)<span class="field-error">{{ $message }}</span>@enderror
</div>
