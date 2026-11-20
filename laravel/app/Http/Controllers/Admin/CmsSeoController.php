<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CmsAuditLog;
use App\Models\CmsContentEntry;
use App\Models\CmsPage;
use App\Models\CmsRedirect;
use App\Support\CmsContentTypes;
use App\Support\RichText;
use App\Support\Seo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/** CMS → SEO health and Redirects. */
class CmsSeoController extends Controller
{
    private const PAGE_LABELS = ['home' => 'Homepage', 'about' => 'About', 'services' => 'Services', 'projects' => 'Projects', 'team' => 'Team', 'partners' => 'Partners', 'gallery' => 'Gallery', 'blog' => 'Insights', 'activities' => 'Activities', 'contact' => 'Contact'];

    /** Every public page with its search title and description, and plain-English problems to fix. */
    public function index(): View
    {
        $rows = [];
        $suffix = Seo::titleSuffix();

        foreach (CmsPage::query()->with('translations')->get()->keyBy('page_key') as $key => $page) {
            if (!isset(self::PAGE_LABELS[$key])) continue;
            $fields = $page->publishedFields('en');
            $rows[] = $this->row(self::PAGE_LABELS[$key], 'Page', route($key), (string) ($fields['pageTitle'] ?? ''), (string) ($fields['pageDescription'] ?? ''), (string) ($fields['ogImage'] ?? 'x'), false, route('admin.pages.edit', $key), null);
        }

        $entries = CmsContentEntry::query()->published()->whereIn('type', ['blog_post', 'service', 'project_feature'])->with('translations')->orderBy('type')->orderBy('sort_order')->get();
        foreach ($entries as $entry) {
            $fields = $entry->translatedFields('en');
            $seo = Seo::forItem($fields + ['hasImage' => trim((string) ($fields['image'] ?? '')) !== '']);
            $section = CmsContentTypes::sectionFor($entry->type);
            $bodyWords = str_word_count(RichText::plainText((string) ($fields['body'] ?? '')));
            $extra = [];
            if (trim((string) ($fields['image'] ?? '')) !== '' && trim((string) ($fields['imageAlt'] ?? '')) === '') $extra[] = 'The main photo has no description for screen readers and Google Images.';
            if ($entry->type === 'blog_post' && $bodyWords < 300 && empty($fields['sections'])) $extra[] = 'The article is short ('.$bodyWords.' words). Google tends to favour guides of 600+ words.';
            if ($entry->type !== 'blog_post' && $bodyWords === 0) $extra[] = 'Its own page has no full description yet, so it has little for Google to show.';
            if (preg_match('#<img(?![^>]*alt="[^"]+")[^>]*>#i', (string) ($fields['body'] ?? ''))) $extra[] = 'A photo inside the text has no description.';
            $label = ['blog_post' => 'Article', 'service' => 'Service', 'project_feature' => 'Project'][$entry->type];
            $rows[] = $this->row((string) ($fields['title'] ?? $entry->slug), $label, CmsContentTypes::publicUrl($entry->type, $entry->slug), $seo['pageTitle'], $seo['pageDescription'], $seo['ogImage'], !empty($fields['noindex']), route('admin.content.edit', [$section, $entry]), $extra, trim((string) ($fields['seoDescription'] ?? '')) === '' && trim((string) ($fields['summary'] ?? '')) === '');
        }

        $titles = array_count_values(array_map(fn ($row) => mb_strtolower($row['title']), $rows));
        foreach ($rows as &$row) {
            if ($row['title'] !== '' && ($titles[mb_strtolower($row['title'])] ?? 0) > 1) $row['issues'][] = 'Another page has the same Google title. Make each one different.';
        }
        unset($row);
        usort($rows, fn ($a, $b) => count($b['issues']) <=> count($a['issues']));

        return view('admin.seo.index', [
            'rows' => $rows,
            'siteHidden' => Seo::siteHidden(),
            'settings' => Seo::settings(),
            'issueCount' => array_sum(array_map(fn ($row) => count($row['issues']), $rows)),
        ]);
    }

    public function redirects(): View
    {
        return view('admin.seo.redirects', ['redirects' => CmsRedirect::query()->orderByDesc('updated_at')->paginate(50)]);
    }

    public function storeRedirect(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'from_path' => ['required', 'string', 'max:500'],
            'to_path' => ['required', 'string', 'max:500'],
        ]);
        $from = CmsRedirect::normalisePath($data['from_path']);
        $to = trim($data['to_path']);
        $to = preg_match('#^https?://#i', $to) && !Str::startsWith(mb_strtolower($to), mb_strtolower(rtrim((string) config('app.url'), '/'))) ? $to : CmsRedirect::normalisePath($to);
        if ($from === '/' || str_starts_with($from, '/admin')) {
            throw ValidationException::withMessages(['from_path' => 'The homepage and CMS addresses cannot be redirected.']);
        }
        if ($from === $to) {
            throw ValidationException::withMessages(['to_path' => 'The new address must be different from the old one.']);
        }
        CmsRedirect::query()->updateOrCreate(['from_path' => $from], ['to_path' => $to, 'status_code' => 301, 'source' => 'manual']);
        $this->audit($request, 'redirect.save', $from);

        return back()->with('success', 'Redirect saved. Visitors to '.$from.' now go to '.$to.'.');
    }

    public function destroyRedirect(Request $request, CmsRedirect $redirect): RedirectResponse
    {
        $redirect->delete();
        $this->audit($request, 'redirect.delete', $redirect->from_path);

        return back()->with('success', 'Redirect removed.');
    }

    private function row(string $name, string $type, ?string $url, string $title, string $description, string $image, bool $noindex, string $editUrl, ?array $extra, bool $descriptionFallback = false): array
    {
        $issues = [];
        $titleLength = mb_strlen($title);
        $descriptionLength = mb_strlen($description);
        if ($title === '') $issues[] = 'No Google title.';
        elseif ($titleLength > 65) $issues[] = 'Google title is long ('.$titleLength.' characters). Google cuts titles after about 60.';
        elseif ($titleLength < 25) $issues[] = 'Google title is short ('.$titleLength.' characters). Add what the page is about and where, e.g. "in Rwanda".';
        if ($description === '') $issues[] = 'No Google description.';
        elseif ($descriptionLength < 70) $issues[] = 'Google description is short ('.$descriptionLength.' characters). Aim for 120–160.';
        elseif ($descriptionFallback) $issues[] = 'No summary or Google description, so Google is shown the start of the text. Write a summary.';
        if ($image === '') $issues[] = 'No sharing image.';
        if ($noindex) $issues[] = 'Hidden from Google (by choice). Untick "Hide this page from Google" to list it.';

        return ['name' => $name, 'type' => $type, 'url' => $url, 'title' => $title, 'description' => $description, 'issues' => array_merge($issues, $extra ?? []), 'edit' => $editUrl];
    }

    private function audit(Request $request, string $action, string $key): void
    {
        CmsAuditLog::query()->create(['user_id' => $request->user()?->id, 'action' => $action, 'entity_type' => 'redirect', 'entity_key' => Str::limit($key, 170, ''), 'created_at' => now()]);
    }
}
