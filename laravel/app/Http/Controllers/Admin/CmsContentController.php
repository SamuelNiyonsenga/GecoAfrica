<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CmsAuditLog;
use App\Models\CmsContentEntry;
use App\Models\CmsContentTranslation;
use App\Models\CmsContentVersion;
use App\Models\CmsMediaAsset;
use App\Support\CmsContentTypes;
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
        $entries = $query->orderBy('sort_order')->orderByDesc('updated_at')->paginate(30)->withQueryString();

        return view('admin.content.index', compact('section', 'definition', 'entries', 'status', 'search'));
    }

    public function create(string $section): View
    {
        $definition = $this->definition($section);
        return view('admin.content.form', [
            'section' => $section,
            'definition' => $definition,
            'entry' => null,
            'fields' => [],
            'assets' => $this->mediaOptions(),
            'parents' => $this->parents($definition),
            'versions' => collect(),
        ]);
    }

    public function store(Request $request, string $section): RedirectResponse
    {
        $definition = $this->definition($section);
        $meta = $this->validateMeta($request, $definition, null);
        $fields = $this->validateFields($request, $definition);

        $entry = CmsContentEntry::query()->create(array_merge($meta, [
            'type' => $definition['type'],
            'status' => 'draft',
            'published_at' => null,
        ]));
        $entry->translations()->create(['locale_code' => 'en', 'draft_fields' => $fields, 'published_fields' => null]);
        $this->audit($request, 'content.create', $entry);

        return to_route('admin.content.edit', [$section, $entry])->with('success', 'Draft created. Publish it when it is ready.');
    }

    public function edit(string $section, CmsContentEntry $entry): View
    {
        $definition = $this->definition($section);
        abort_unless($entry->type === $definition['type'], 404);
        $entry->load('translations');

        return view('admin.content.form', [
            'section' => $section,
            'definition' => $definition,
            'entry' => $entry,
            'fields' => $entry->draftFields('en'),
            'assets' => $this->mediaOptions(),
            'parents' => $this->parents($definition),
            'versions' => CmsContentVersion::query()->where('entity_type', 'content')->where('entity_key', (string) $entry->id)->latest('created_at')->limit(30)->get(),
        ]);
    }

    public function update(Request $request, string $section, CmsContentEntry $entry): RedirectResponse
    {
        $definition = $this->definition($section);
        abort_unless($entry->type === $definition['type'], 404);
        $meta = $this->validateMeta($request, $definition, $entry);
        $fields = $this->validateFields($request, $definition, $entry->draftFields('en'));

        DB::transaction(function () use ($entry, $meta, $fields): void {
            $entry->update($meta);
            $translation = $entry->translations()->firstOrNew(['locale_code' => 'en']);
            $translation->draft_fields = $fields;
            $translation->save();
        });
        $this->audit($request, 'content.save-draft', $entry);

        return back()->with('success', 'Draft saved. Visitors will continue to see the published version until you publish.');
    }

    public function publish(Request $request, string $section, CmsContentEntry $entry): RedirectResponse
    {
        $definition = $this->definition($section);
        abort_unless($entry->type === $definition['type'], 404);
        $translation = $entry->translations()->where('locale_code', 'en')->first();
        $fields = $translation?->draft_fields ?? [];
        $this->validatePublish($entry, $fields, $definition);

        DB::transaction(function () use ($entry, $translation, $fields, $request): void {
            if ($translation?->published_fields) {
                $this->saveVersion($entry, $translation->published_fields, $request->user()?->id);
            }
            $translation->published_fields = $fields;
            $translation->draft_fields = $fields;
            $translation->save();
            $entry->update(['status' => 'published', 'published_at' => now()]);
            $this->saveVersion($entry, $fields, $request->user()?->id);
        });
        $this->audit($request, 'content.publish', $entry);

        return back()->with('success', 'Content published.');
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
        return CmsContentEntry::query()->where('type', $definition['parent_type'])->published()->with('translations')->orderBy('sort_order')->get();
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
        $duplicate = CmsContentEntry::query()->where('type', $definition['type'])->where('slug', $data['slug']);
        if ($entry) {
            $duplicate->where('id', '<>', $entry->id);
        }
        if ($duplicate->exists()) {
            throw ValidationException::withMessages(['slug' => 'That address is already used by another item of this type.']);
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
            } else {
                $value = is_scalar($raw) ? trim((string) $raw) : '';
            }
            if (!empty($spec['required']) && ($value === '' || $value === [])) {
                throw ValidationException::withMessages(['fields.'.$key => $spec['label'].' is required.']);
            }
            if ($spec['type'] === 'url' && $value !== '' && filter_var($value, FILTER_VALIDATE_URL) === false) {
                throw ValidationException::withMessages(['fields.'.$key => 'Enter a full website address beginning with https://.']);
            }
            if ($spec['type'] === 'media' && $value !== '') {
                $mediaPath = str_starts_with($value, 'storage/') ? substr($value, 8) : $value;
                if (!CmsMediaAsset::query()->where('path', $mediaPath)->exists()) {
                    throw ValidationException::withMessages(['fields.'.$key => 'Choose a file from the media library.']);
                }
            }
            $fields[$key] = $value;
        }
        return $fields;
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

    private function saveVersion(CmsContentEntry $entry, array $fields, ?int $userId): void
    {
        CmsContentVersion::query()->create([
            'entity_type' => 'content',
            'entity_key' => (string) $entry->id,
            'snapshot' => ['fields' => $fields, 'slug' => $entry->slug],
            'created_by' => $userId,
            'created_at' => now(),
        ]);
        $oldIds = CmsContentVersion::query()->where('entity_type', 'content')->where('entity_key', (string) $entry->id)->latest('created_at')->skip(30)->pluck('id');
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
