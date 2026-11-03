<?php

namespace App\Http\Controllers\Admin;

use App\Models\CmsAuditLog;
use App\Models\CmsPage;
use App\Models\CmsPageTranslation;
use App\Models\CmsContentVersion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CmsPageController
{
    private const HOME_SECTIONS = ['hero' => 'Hero carousel', 'facts' => 'At a glance', 'services' => 'Services', 'projects' => 'Featured projects', 'about' => 'About GECO', 'principles' => 'Our principles', 'laboratory' => 'Laboratory', 'experience' => 'Experience', 'contact' => 'Contact callout'];

    private const PAGES = [
        'home' => 'Homepage', 'about' => 'About', 'contact' => 'Contact',
        'services' => 'Services page', 'projects' => 'Projects page', 'team' => 'Team page',
        'partners' => 'Partners page', 'gallery' => 'Gallery page', 'blog' => 'Blog page', 'activities' => 'Activities page',
    ];

    public function index(): View
    {
        return view('admin.pages.index', ['pages' => CmsPage::query()->with('translations')->orderBy('page_key')->get(), 'labels' => self::PAGES]);
    }

    public function edit(string $pageKey): View
    {
        abort_unless(isset(self::PAGES[$pageKey]), 404);
        $page = CmsPage::query()->firstOrCreate(['page_key' => $pageKey]);
        $translation = $page->translations()->firstOrCreate(['locale_code' => 'en'], ['draft_fields' => [], 'published_fields' => null]);
        $fields = $translation->draft_fields ?? [];
        unset($fields['structuredData']);
        $sectionSettings = [];
        if ($pageKey === 'home') {
            foreach (self::HOME_SECTIONS as $key => $label) {
                $sectionSettings[$key] = array_merge(['label' => $label, 'order' => count($sectionSettings) + 1, 'visible' => true], $fields['homeSections'][$key] ?? []);
            }
        }
        return view('admin.pages.edit', ['page' => $page, 'translation' => $translation, 'label' => self::PAGES[$pageKey], 'sectionSettings' => $sectionSettings, 'fieldGroups' => \App\Support\CmsPageFields::grouped($fields), 'versions' => CmsContentVersion::query()->where('entity_type', 'page')->where('entity_key', $pageKey)->latest('created_at')->limit(30)->get()]);
    }

    public function saveDraft(Request $request, string $pageKey): RedirectResponse
    {
        abort_unless(isset(self::PAGES[$pageKey]), 404);
        $fields = $this->decodeFields($request, $pageKey);
        if ($pageKey === 'home') $fields['homeSections'] = $this->validatedHomeSections($request);
        $page = CmsPage::query()->firstOrCreate(['page_key' => $pageKey]);
        $translation = $page->translations()->firstOrNew(['locale_code' => 'en']);
        $this->version($pageKey, $translation->draft_fields ?? [], 'Draft saved');
        $translation->fill(['draft_fields' => $fields])->save();
        $page->update(['updated_by' => $request->user()->id]);
        $this->audit($request, 'page.draft', $pageKey);
        if ($request->input('intent') === 'preview') return to_route('admin.pages.preview', $pageKey);
        return to_route('admin.pages.edit', $pageKey)->with('success', 'Page draft saved.');
    }

    public function publish(Request $request, string $pageKey): RedirectResponse
    {
        abort_unless(isset(self::PAGES[$pageKey]), 404);
        $fields = $this->decodeFields($request, $pageKey);
        if ($pageKey === 'home') $fields['homeSections'] = $this->validatedHomeSections($request);
        $page = CmsPage::query()->firstOrCreate(['page_key' => $pageKey]);
        $translation = $page->translations()->firstOrNew(['locale_code' => 'en']);
        $this->version($pageKey, $translation->published_fields ?? [], 'Published snapshot');
        $translation->fill(['draft_fields' => $fields, 'published_fields' => $fields, 'published_at' => now()])->save();
        $page->update(['updated_by' => $request->user()->id]);
        $this->audit($request, 'page.publish', $pageKey);
        return to_route('admin.pages.edit', $pageKey)->with('success', 'Page published.');
    }

    public function restoreVersion(Request $request, string $pageKey, CmsContentVersion $version): RedirectResponse
    {
        abort_unless(isset(self::PAGES[$pageKey]) && $version->entity_type === 'page' && $version->entity_key === $pageKey, 404);
        $translation = CmsPageTranslation::query()->where('page_id', CmsPage::query()->where('page_key', $pageKey)->value('id'))->where('locale_code', 'en')->firstOrFail();
        $fields = $version->snapshot['fields'] ?? [];
        $translation->update(['draft_fields' => $fields]);
        $this->audit($request, 'page.restore', $pageKey);
        return to_route('admin.pages.edit', $pageKey)->with('success', 'Version restored as a draft. Publish it when ready.');
    }


    private function validatedHomeSections(Request $request): array
    {
        $data = $request->validate(['home_sections' => ['required', 'array']]);
        $sections = [];
        foreach (self::HOME_SECTIONS as $key => $label) {
            $section = $data['home_sections'][$key] ?? [];
            $validated = validator($section, ['order' => ['required', 'integer', 'min:1', 'max:100'], 'visible' => ['nullable', 'boolean']])->validate();
            $sections[$key] = ['label' => $label, 'order' => (int) $validated['order'], 'visible' => (bool) ($validated['visible'] ?? false)];
        }
        return $sections;
    }
    private function decodeFields(Request $request, string $pageKey): array
    {
        $currentPage = CmsPage::query()->where('page_key', $pageKey)->with('translations')->first();
        $current = $currentPage?->draftFields('en') ?? [];
        unset($current['homeSections'], $current['structuredData']);
        $submitted = $request->input('fields', []);
        if (!is_array($submitted)) throw \Illuminate\Validation\ValidationException::withMessages(['fields' => 'Enter page content and try again.']);
        $fields = [];
        foreach ($current as $key => $original) {
            if (in_array($key, \App\Support\CmsPageFields::PRESERVED, true)) {
                $fields[$key] = $original; // generated values the editor never shows
                continue;
            }
            $raw = $submitted[$key] ?? '';
            if (is_array($original)) {
                if (!is_string($raw) || strlen($raw) > 200000) throw \Illuminate\Validation\ValidationException::withMessages(['fields.'.$key => 'This structured field is too long.']);
                try { $value = json_decode($raw, true, 64, JSON_THROW_ON_ERROR); }
                catch (\JsonException) { throw \Illuminate\Validation\ValidationException::withMessages(['fields.'.$key => 'Enter valid JSON for this structured field.']); }
                if (!is_array($value)) throw \Illuminate\Validation\ValidationException::withMessages(['fields.'.$key => 'This structured field must be a JSON object or list.']);
                $fields[$key] = $value;
            } else {
                if (!is_scalar($raw) || mb_strlen((string) $raw) > 20000) throw \Illuminate\Validation\ValidationException::withMessages(['fields.'.$key => 'Enter valid text for this field.']);
                $fields[$key] = trim((string) $raw);
            }
        }
        return $fields;
    }
    private function version(string $key, array $fields, string $note): void
    {
        if ($fields !== []) CmsContentVersion::query()->create(['entity_type' => 'page', 'entity_key' => $key, 'snapshot' => ['fields' => $fields], 'note' => $note, 'created_by' => request()->user()->id, 'created_at' => now()]);
    }

    private function audit(Request $request, string $action, string $key): void
    {
        CmsAuditLog::query()->create(['user_id' => $request->user()->id, 'action' => $action, 'entity_type' => 'page', 'entity_key' => $key, 'created_at' => now()]);
    }
}
