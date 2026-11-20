<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CmsAuditLog;
use App\Models\CmsContentEntry;
use App\Models\CmsContentTranslation;
use App\Models\CmsContentVersion;
use App\Models\CmsMediaAsset;
use App\Models\CmsRedirect;
use App\Support\CmsContentTypes;
use App\Support\CmsSlug;
use App\Support\HtmlSanitizer;
use App\Support\RichText;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Throwable;

class CmsContentController extends Controller
{
    public function index(Request $request, string $section): View
    {
        $definition = $this->definition($section);
        $query = CmsContentEntry::query()->where('type', $definition['type'])->with('translations');
        $status = $request->string('status')->toString();
        if (in_array($status, ['draft', 'published'], true)) {
            $query->where('status', $status);
        }
        $search = trim($request->string('q')->toString());
        if ($search !== '') {
            $query->whereHas('translations', function (Builder $translationQuery) use ($search): void {
                $translationQuery->where('locale_code', 'en')->whereRaw('CAST(published_fields AS CHAR) LIKE ?', ['%'.$search.'%']);
            });
        }
        $entries = $query->orderBy('sort_order')->orderBy('id')->paginate(30)->withQueryString();

        $galleryCategories = $section === 'gallery' ? $this->categoryOptions('gallery_category') : [];

        return view('admin.content.index', compact('section', 'definition', 'entries', 'status', 'search', 'galleryCategories'));
    }

    public function create(string $section): View
    {
        $definition = $this->definition($section);
        return view('admin.content.form', [
            'section' => $section,
            'definition' => $definition,
            'entry' => null,
            'fields' => [],
            'nextSortOrder' => $this->nextSortOrder($definition['type']),
            'assets' => $this->mediaOptions(),
            'parents' => $this->parents($definition),
            'categoryOptions' => $this->categoryFieldOptions($definition),
            'versions' => collect(),
        ]);
    }

    public function store(Request $request, string $section): RedirectResponse
    {
        $definition = $this->definition($section);
        if (blank($request->input('slug'))) {
            $title = $request->input('fields.title', $request->input('fields.name', ''));
            $request->merge(['slug' => CmsSlug::make((string) $title)]);
        }
        if (blank($request->input('sort_order'))) {
            $request->merge(['sort_order' => $this->nextSortOrder($definition['type'])]);
        }
        $meta = $this->validateMeta($request, $definition, null);
        $fields = $this->validateFields($request, $definition);
        $publish = $request->input('intent') === 'publish';
        $entry = new CmsContentEntry(array_merge($meta, [
            'type' => $definition['type'],
            'status' => $publish ? 'published' : 'draft',
            'published_at' => $publish ? now() : null,
        ]));
        if ($publish) $this->validatePublish($entry, $fields, $definition);

        DB::transaction(function () use ($entry, $fields, $publish, $request): void {
            $entry->save();
            $entry->translations()->create([
                'locale_code' => 'en',
                'draft_fields' => $fields,
                'published_fields' => $publish ? $fields : null,
            ]);
            if ($publish) $this->saveVersion($entry, $fields, $request->user()?->id);
        });
        $this->audit($request, $publish ? 'content.publish' : 'content.create', $entry);

        return to_route('admin.content.edit', [$section, $entry])->with('success', $publish ? 'Content created and published.' : 'Draft created. Publish it when it is ready.');
    }
    public function edit(string $section, CmsContentEntry $entry): View
    {
        $definition = $this->definition($section);
        abort_unless($entry->type === $definition['type'], 404);
        $entry->load('translations');
        $fields = $entry->draftFields('en');
        // Older articles were written as heading + paragraph boxes: open them in the rich text editor.
        if (isset($definition['fields']['body'], $definition['fields']['sections']) && trim((string) ($fields['body'] ?? '')) === '' && !empty($fields['sections'])) {
            $fields['body'] = RichText::fromSections((array) $fields['sections']);
        }

        return view('admin.content.form', [
            'section' => $section,
            'definition' => $definition,
            'entry' => $entry,
            'fields' => $fields,
            'trashedMediaFields' => $this->trashedMediaFields($definition, $entry->draftFields('en')),
            'assets' => $this->mediaOptions(),
            'parents' => $this->parents($definition),
            'categoryOptions' => $this->categoryFieldOptions($definition),
            'versions' => CmsContentVersion::query()->where('entity_type', 'content')->where('entity_key', (string) $entry->id)->latest('created_at')->limit(30)->get(),
        ]);
    }

    public function update(Request $request, string $section, CmsContentEntry $entry): RedirectResponse
    {
        $definition = $this->definition($section);
        abort_unless($entry->type === $definition['type'], 404);
        $meta = $this->validateMeta($request, $definition, $entry);
        $fields = $this->validateFields($request, $definition, $entry->draftFields('en'));
        $this->rememberMovedAddress($entry, $meta['slug']);

        DB::transaction(function () use ($entry, $meta, $fields): void {
            $entry->update($meta);
            $translation = $entry->translations()->firstOrNew(['locale_code' => 'en']);
            $translation->draft_fields = $fields;
            $translation->save();
        });
        $this->audit($request, 'content.save-draft', $entry);

        if ($request->input('intent') === 'preview') return to_route('admin.content.preview', [$section, $entry]);

        return back()->with('success', 'Draft saved. Visitors will continue to see the published version until you publish.');
    }

    public function publish(Request $request, string $section, CmsContentEntry $entry): RedirectResponse
    {
        $definition = $this->definition($section);
        abort_unless($entry->type === $definition['type'], 404);
        $meta = $this->validateMeta($request, $definition, $entry);
        $fields = $this->validateFields($request, $definition, $entry->draftFields('en'));
        $this->rememberMovedAddress($entry, $meta['slug']);
        $entry->fill($meta);
        $this->validatePublish($entry, $fields, $definition);

        DB::transaction(function () use ($entry, $meta, $fields, $request): void {
            $entry->update(array_merge($meta, ['status' => 'published', 'published_at' => now()]));
            $translation = $entry->translations()->firstOrNew(['locale_code' => 'en']);
            if (!empty($translation->published_fields)) {
                $this->saveVersion($entry, $translation->published_fields, $request->user()?->id);
            }
            $translation->draft_fields = $fields;
            $translation->published_fields = $fields;
            $translation->save();
            $this->saveVersion($entry, $fields, $request->user()?->id);
        });
        $this->audit($request, 'content.publish', $entry);

        return back()->with('success', 'Changes saved and published. The website now shows this version.');
    }
    public function unpublish(Request $request, string $section, CmsContentEntry $entry): RedirectResponse
    {
        $definition = $this->definition($section);
        abort_unless($entry->type === $definition['type'], 404);
        $entry->update(['status' => 'draft', 'published_at' => null]);
        $this->audit($request, 'content.unpublish', $entry);

        return back()->with('success', 'Content is now a draft and hidden from the website.');
    }

    public function destroy(Request $request, string $section, CmsContentEntry $entry): RedirectResponse
    {
        $definition = $this->definition($section);
        abort_unless($entry->type === $definition['type'], 404);
        $entry->delete();
        $this->audit($request, 'content.trash', $entry);

        return to_route('admin.content.index', $section)->with('success', 'Content moved to Trash.');
    }

    /** Copy an item as a new draft, so similar items are quick to add. */
    public function duplicate(Request $request, string $section, CmsContentEntry $entry): RedirectResponse
    {
        $definition = $this->definition($section);
        abort_unless($entry->type === $definition['type'], 404);
        $fields = $entry->draftFields('en') ?: $entry->translatedFields('en');
        $titleKey = array_key_exists('title', $definition['fields']) ? 'title' : 'name';
        $fields[$titleKey] = trim((string) ($fields[$titleKey] ?? '')).' (copy)';
        $base = CmsSlug::make($fields[$titleKey]) ?: $entry->slug.'-copy';
        $slug = $base;
        for ($n = 2; CmsContentEntry::withTrashed()->where('type', $entry->type)->where('slug', $slug)->exists(); $n++) {
            $slug = $base.'-'.$n;
        }
        $copy = new CmsContentEntry([
            'type' => $entry->type, 'slug' => $slug, 'parent_id' => $entry->parent_id, 'group_key' => $entry->group_key,
            'status' => 'draft', 'sort_order' => $this->nextSortOrder($entry->type), 'is_featured' => $entry->is_featured,
        ]);
        DB::transaction(function () use ($copy, $fields): void {
            $copy->save();
            $copy->translations()->create(['locale_code' => 'en', 'draft_fields' => $fields, 'published_fields' => null]);
        });
        $this->audit($request, 'content.duplicate', $copy, ['from' => $entry->id]);

        return to_route('admin.content.edit', [$section, $copy])->with('success', 'Copy created as a draft. Change what you need, then publish it.');
    }

    /** Move an item one place up or down in the website order. */
    public function move(Request $request, string $section, CmsContentEntry $entry): RedirectResponse
    {
        $definition = $this->definition($section);
        abort_unless($entry->type === $definition['type'], 404);
        $direction = $request->input('direction') === 'up' ? -1 : 1;
        $siblings = CmsContentEntry::query()->where('type', $entry->type)
            ->when($entry->parent_id, fn (Builder $query) => $query->where('parent_id', $entry->parent_id))
            ->when(!empty($definition['group_options']), fn (Builder $query) => $query->where('group_key', $entry->group_key))
            ->orderBy('sort_order')->orderBy('id')->pluck('id')->all();
        $index = array_search($entry->id, $siblings, true);
        $target = $index === false ? false : $index + $direction;
        if ($index !== false && isset($siblings[$target])) {
            [$siblings[$index], $siblings[$target]] = [$siblings[$target], $siblings[$index]];
            DB::transaction(function () use ($siblings): void {
                foreach ($siblings as $position => $id) {
                    CmsContentEntry::query()->whereKey($id)->update(['sort_order' => ($position + 1) * 10]);
                }
            });
            $this->audit($request, 'content.reorder', $entry);
        }

        return back()->with('success', 'Order updated. The website now shows the new order.');
    }

    /** Upload many photos at once; each becomes a gallery item. */
    public function bulkGallery(Request $request): RedirectResponse
    {
        $definition = $this->definition('gallery');
        $data = $request->validate([
            'files' => ['required', 'array', 'max:30'],
            'files.*' => ['required', 'file', 'mimes:jpg,jpeg,png,webp,gif,mp4,webm', 'max:51200'],
            'category' => ['nullable', 'string', 'max:180'],
            'publish' => ['nullable', 'boolean'],
        ]);
        $category = (string) ($data['category'] ?? '');
        if ($category !== '' && !array_key_exists($category, $this->categoryOptions('gallery_category'))) {
            throw ValidationException::withMessages(['category' => 'Choose a category from the list.']);
        }
        $publish = $request->boolean('publish');
        $order = $this->nextSortOrder($definition['type']);
        $created = 0;
        foreach ($data['files'] as $file) {
            $path = $file->store('cms/'.now()->format('Y/m'), 'public');
            [$width, $height] = @getimagesize($file->getRealPath()) ?: [null, null];
            CmsMediaAsset::query()->create(['disk' => 'public', 'path' => $path, 'original_name' => $file->getClientOriginalName(), 'mime_type' => $file->getMimeType() ?: 'application/octet-stream', 'size_bytes' => $file->getSize(), 'width' => $width, 'height' => $height, 'uploaded_by' => $request->user()?->id]);
            $title = Str::of(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))->replace(['_', '-'], ' ')->squish()->ucfirst()->limit(150, '')->toString() ?: 'Photo';
            $slug = CmsSlug::make($title) ?: 'photo';
            for ($base = $slug, $n = 2; CmsContentEntry::withTrashed()->where('type', $definition['type'])->where('slug', $slug)->exists(); $n++) {
                $slug = $base.'-'.$n;
            }
            $fields = array_replace(CmsContentTypes::defaultsForType($definition['type']), ['title' => $title, 'category' => $category, 'image' => 'storage/'.$path]);
            $entry = new CmsContentEntry(['type' => $definition['type'], 'slug' => $slug, 'status' => $publish ? 'published' : 'draft', 'published_at' => $publish ? now() : null, 'sort_order' => $order]);
            DB::transaction(function () use ($entry, $fields, $publish, $request): void {
                $entry->save();
                $entry->translations()->create(['locale_code' => 'en', 'draft_fields' => $fields, 'published_fields' => $publish ? $fields : null]);
                if ($publish) $this->saveVersion($entry, $fields, $request->user()?->id);
            });
            $order += 10;
            $created++;
        }
        $this->audit($request, 'content.bulk-gallery', new CmsContentEntry(['type' => $definition['type']]), ['count' => $created]);

        return to_route('admin.content.index', 'gallery')->with('success', $created.' '.Str::plural('photo', $created).' added'.($publish ? ' and published.' : ' as drafts. Add descriptions, then publish them.'));
    }

    public function restoreVersion(Request $request, string $section, CmsContentEntry $entry, CmsContentVersion $version): RedirectResponse
    {
        $definition = $this->definition($section);
        abort_unless($entry->type === $definition['type'], 404);
        abort_unless($version->entity_type === 'content' && $version->entity_key === (string) $entry->id, 404);
        $snapshot = $version->snapshot;
        $translation = $entry->translations()->firstOrNew(['locale_code' => 'en']);
        $translation->draft_fields = $snapshot['fields'] ?? [];
        $translation->save();
        $this->audit($request, 'content.restore-version', $entry, ['version_id' => $version->id]);

        return back()->with('success', 'The earlier version is now a draft. Review and publish it when ready.');
    }

    private function definition(string $section): array
    {
        $definition = CmsContentTypes::get($section);
        abort_if($definition === null, 404);
        return $definition;
    }

    private function parents(array $definition)
    {
        if (empty($definition['parent_type'])) {
            return collect();
        }
        // Drafts are listed too (marked as drafts) so a new group can be used straight away.
        return CmsContentEntry::query()->where('type', $definition['parent_type'])->with('translations')->orderBy('sort_order')->get();
    }

    private function mediaOptions(): array
    {
        return CmsMediaAsset::query()->orderByDesc('created_at')->limit(300)->get()->mapWithKeys(function (CmsMediaAsset $asset): array {
            $value = $asset->disk === 'legacy-public' ? $asset->path : 'storage/'.$asset->path;
            return [$value => $asset->original_name.' · '.$value];
        })->all();
    }

    private function validateMeta(Request $request, array $definition, ?CmsContentEntry $entry): array
    {
        $data = $request->validate([
            'slug' => ['required', 'string', 'max:180', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:100000'],
            'group_key' => ['nullable', 'string', 'max:100'],
            'parent_id' => ['nullable', 'integer', 'exists:cms_content_entries,id'],
            'is_featured' => ['nullable', 'boolean'],
        ]);
        $duplicate = CmsContentEntry::withTrashed()->where('type', $definition['type'])->where('slug', $data['slug']);
        if ($entry) {
            $duplicate->where('id', '<>', $entry->id);
        }
        $duplicateEntry = $duplicate->first();
        if ($duplicateEntry) {
            $message = $duplicateEntry->trashed()
                ? 'That address is used by an item in Trash. Restore that item, or choose a different address.'
                : 'That address is already used by another item of this type.';
            throw ValidationException::withMessages(['slug' => $message]);
        }
        if (!empty($definition['parent_type']) && !empty($data['parent_id'])) {
            $validParent = CmsContentEntry::query()->whereKey($data['parent_id'])->where('type', $definition['parent_type'])->exists();
            if (!$validParent) {
                throw ValidationException::withMessages(['parent_id' => 'Choose a valid project group.']);
            }
        }

        $meta = [
            'slug' => $data['slug'],
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'group_key' => $data['group_key'] ?? null,
            'is_featured' => $request->boolean('is_featured'),
        ];
        if (array_key_exists('parent_type', $definition)) {
            $meta['parent_id'] = $data['parent_id'] ?? null;
        }
        return $meta;
    }

    private function validateFields(Request $request, array $definition, array $existing = []): array
    {
        $submitted = $request->input('fields', []);
        if (!is_array($submitted)) {
            throw ValidationException::withMessages(['fields' => 'Enter the content fields and try again.']);
        }
        $fields = $existing;
        foreach ($definition['fields'] as $key => $spec) {
            // Older-format fields are no longer shown in the editor: keep what is stored.
            if (!empty($spec['legacy']) && !array_key_exists($key, $submitted)) {
                continue;
            }
            $raw = $submitted[$key] ?? null;
            if ($spec['type'] === 'list') {
                $value = is_array($raw) ? $raw : preg_split('/\r?\n/', (string) $raw);
                $value = array_values(array_filter(array_map(static fn ($item) => trim((string) $item), $value), static fn ($item) => $item !== ''));
            } elseif ($spec['type'] === 'json') {
                $raw = trim((string) $raw);
                if ($raw === '') {
                    $value = [];
                } else {
                    try { $value = json_decode($raw, true, 512, JSON_THROW_ON_ERROR); }
                    catch (\JsonException) { throw ValidationException::withMessages(['fields.'.$key => 'Enter valid JSON for this structured field.']); }
                    if (!is_array($value)) {
                        throw ValidationException::withMessages(['fields.'.$key => 'This structured field must be a JSON object or list.']);
                    }
                }
            } elseif ($spec['type'] === 'richtext') {
                $value = HtmlSanitizer::clean(is_scalar($raw) ? (string) $raw : '');
            } elseif ($spec['type'] === 'gallery') {
                $value = array_values(array_unique(array_filter(array_map(static fn ($item) => trim((string) $item), is_array($raw) ? $raw : []), static fn ($item) => $item !== '')));
                foreach ($value as $path) {
                    if (!CmsMediaAsset::query()->where('path', str_starts_with($path, 'storage/') ? substr($path, 8) : $path)->exists()) {
                        throw ValidationException::withMessages(['fields.'.$key => 'Choose photos from the media library.']);
                    }
                }
            } elseif ($spec['type'] === 'toggle') {
                $value = filter_var($raw, FILTER_VALIDATE_BOOLEAN) ? '1' : '';
            } elseif ($spec['type'] === 'date') {
                $value = is_scalar($raw) ? trim((string) $raw) : '';
                if ($value !== '' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
                    throw ValidationException::withMessages(['fields.'.$key => 'Choose a date from the calendar.']);
                }
            } else {
                $value = is_scalar($raw) ? trim((string) $raw) : '';
            }
            if (!empty($spec['max']) && is_string($value) && mb_strlen($value) > $spec['max']) {
                throw ValidationException::withMessages(['fields.'.$key => $spec['label'].' must be '.$spec['max'].' characters or fewer.']);
            }
            if (!empty($spec['required']) && ($value === '' || $value === [])) {
                throw ValidationException::withMessages(['fields.'.$key => $spec['label'].' is required.']);
            }
            if ($spec['type'] === 'url' && $value !== '' && filter_var($value, FILTER_VALIDATE_URL) === false) {
                throw ValidationException::withMessages(['fields.'.$key => 'Enter a full website address beginning with https://.']);
            }
            if ($spec['type'] === 'media' && $value !== '') {
                $mediaPath = str_starts_with($value, 'storage/') ? substr($value, 8) : $value;
                $asset = CmsMediaAsset::withTrashed()->where('path', $mediaPath)->first();
                if (!$asset || ($asset->trashed() && ($existing[$key] ?? null) !== $value)) {
                    throw ValidationException::withMessages(['fields.'.$key => 'Choose a file from the media library.']);
                }
            }
            if ($spec['type'] === 'category' && $value !== '' && !array_key_exists($value, $this->categoryOptions($spec['source']))) {
                throw ValidationException::withMessages(['fields.'.$key => 'Choose a category from the list.']);
            }
            $fields[$key] = $value;
        }
        return $fields;
    }

    /** Category choices for a `category` field: web address => display name. */
    private function categoryOptions(string $sourceType): array
    {
        return CmsContentEntry::query()->where('type', $sourceType)->where('slug', '<>', 'all')->with('translations')->orderBy('sort_order')->get()
            ->mapWithKeys(function (CmsContentEntry $entry): array {
                $fields = array_merge($entry->translatedFields('en'), array_filter($entry->draftFields('en')));
                $name = ($fields['name'] ?? '') ?: ($fields['title'] ?? '') ?: ($fields['label'] ?? '') ?: $entry->slug;
                return [$entry->slug => $name.($entry->status === 'published' ? '' : ' (draft)')];
            })->all();
    }

    private function categoryFieldOptions(array $definition): array
    {
        $options = [];
        foreach ($definition['fields'] as $key => $spec) {
            if ($spec['type'] === 'category') {
                $options[$key] = $this->categoryOptions($spec['source']);
            }
        }
        return $options;
    }

    private function trashedMediaFields(array $definition, array $fields): array
    {
        $trashed = [];
        foreach ($definition['fields'] as $key => $spec) {
            if (($spec['type'] ?? null) !== 'media' || empty($fields[$key])) continue;
            $path = str_starts_with($fields[$key], 'storage/') ? substr($fields[$key], 8) : $fields[$key];
            if (CmsMediaAsset::withTrashed()->where('path', $path)->first()?->trashed()) $trashed[$key] = true;
        }
        return $trashed;
    }

    private function validatePublish(CmsContentEntry $entry, array $fields, array $definition): void
    {
        $title = trim((string) ($fields['title'] ?? $fields['name'] ?? ''));
        if ($title === '') {
            throw ValidationException::withMessages(['fields.title' => 'Add an English title or name before publishing.']);
        }
        if ($entry->slug === '') {
            throw ValidationException::withMessages(['slug' => 'Add a web address before publishing.']);
        }
    }

    private function nextSortOrder(string $type): int
    {
        return ((int) CmsContentEntry::withTrashed()->where('type', $type)->max('sort_order')) + 10;
    }

    /** When a published item with its own page changes address, send the old address to the new one. */
    private function rememberMovedAddress(CmsContentEntry $entry, string $newSlug): void
    {
        if ($entry->status !== 'published' || $entry->slug === $newSlug || !CmsContentTypes::hasOwnPage($entry->type) || (string) $entry->slug === '') {
            return;
        }
        try {
            CmsRedirect::remember(CmsContentTypes::publicUrl($entry->type, $entry->slug), CmsContentTypes::publicUrl($entry->type, $newSlug));
        } catch (Throwable $exception) {
            report($exception);
        }
    }

    private function saveVersion(CmsContentEntry $entry, array $fields, ?int $userId): void
    {
        CmsContentVersion::query()->create([
            'entity_type' => 'content',
            'entity_key' => (string) $entry->id,
            'snapshot' => ['fields' => $fields, 'slug' => $entry->slug],
            'created_by' => $userId,
            'created_at' => now(),
        ]);
        $oldIds = CmsContentVersion::query()->where('entity_type', 'content')->where('entity_key', (string) $entry->id)->latest('created_at')->pluck('id')->slice(30)->values(); // keep the newest 30
        if ($oldIds->isNotEmpty()) {
            CmsContentVersion::query()->whereIn('id', $oldIds)->delete();
        }
    }

    private function audit(Request $request, string $action, CmsContentEntry $entry, array $metadata = []): void
    {
        try {
            CmsAuditLog::query()->create([
                'user_id' => $request->user()?->id,
                'action' => $action,
                'entity_type' => $entry->type,
                'entity_key' => (string) $entry->id,
                'metadata' => $metadata,
                'created_at' => now(),
            ]);
        } catch (Throwable $exception) {
            report($exception);
        }
    }
}
