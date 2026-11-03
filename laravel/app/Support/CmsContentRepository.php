<?php

namespace App\Support;

use App\Data\ActivitiesCatalog;
use App\Data\GalleryCatalog;
use App\Data\InsightsCatalog;
use App\Data\PartnersCatalog;
use App\Data\ProjectsCatalog;
use App\Data\ServicesCatalog;
use App\Data\TeamCatalog;
use App\Models\CmsContentEntry;
use App\Models\CmsMediaAsset;

class CmsContentRepository
{
    public static function all(string $type, string $locale = 'en'): array
    {
        $items = CmsContentEntry::query()->published()->where('type', $type)->with('translations')->orderBy('sort_order')->get()
            ->map(fn (CmsContentEntry $entry): array => self::fields($entry, $locale))->all();

        // Display numbers ("01", "02"…) follow the list order when the client leaves them blank.
        foreach ($items as $index => &$item) {
            if (array_key_exists('number', $item) && trim((string) $item['number']) === '') {
                $item['number'] = str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT);
            }
        }
        unset($item);

        return $items;
    }

    public const PLACEHOLDER_IMAGE = 'images/placeholder.svg';

    public static function services(): array
    {
        $items = self::all('service');
        return $items ?: (self::hasCmsRecords('service') ? [] : ServicesCatalog::all());
    }

    public static function homepageServices(): array
    {
        return array_map(static function (array $service): array {
            $service['description'] = $service['summary'] ?? $service['intro'] ?? '';
            return $service;
        }, self::services());
    }
    public static function projectGroups(): array
    {
        $groups = CmsContentEntry::query()->published()->where('type', 'project_group')->with('translations')->orderBy('sort_order')->get();
        if ($groups->isEmpty()) return self::hasCmsRecords('project_group') ? [] : ProjectsCatalog::groups();
        return $groups->map(function (CmsContentEntry $group): array {
            $data = self::fields($group);
            $data['id'] = $data['id'] ?? $group->slug;
            $data['projects'] = CmsContentEntry::query()->published()->where('parent_id', $group->id)->with('translations')->orderBy('sort_order')->get()
                ->map(fn (CmsContentEntry $project): array => self::fields($project))->all();
            return $data;
        })->all();
    }

    public static function featuredProjects(): array
    {
        $items = self::all('project_feature');
        return $items ?: (self::hasCmsRecords('project_feature') ? [] : ProjectsCatalog::featured());
    }

    public static function referenceCount(): int
    {
        return array_sum(array_map(static fn (array $group): int => count($group['projects'] ?? []), self::projectGroups()));
    }

    public static function selectedExperience(): array
    {
        return ProjectsCatalog::selectedExperience();
    }

    public static function coreTeam(): array
    {
        $items = CmsContentEntry::query()->published()->where('type', 'team_member')->where('group_key', 'core')->with('translations')->orderBy('sort_order')->get()
            ->map(fn (CmsContentEntry $entry): array => self::fields($entry))->all();
        return $items ?: (self::hasCmsRecords('team_member') ? [] : TeamCatalog::core());
    }

    public static function supportingTeam(): array
    {
        $items = CmsContentEntry::query()->published()->where('type', 'team_member')->where('group_key', 'supporting')->with('translations')->orderBy('sort_order')->get()
            ->map(fn (CmsContentEntry $entry): array => self::fields($entry))->all();
        return $items ?: (self::hasCmsRecords('team_member') ? [] : TeamCatalog::supporting());
    }

    public static function partners(): array
    {
        $items = self::all('partner');
        return $items ?: (self::hasCmsRecords('partner') ? [] : PartnersCatalog::all());
    }

    public static function gallery(): array
    {
        $items = self::all('gallery_item');
        if (!$items) {
            return self::hasCmsRecords('gallery_item') ? [] : GalleryCatalog::all();
        }
        $labels = array_column(self::galleryCategories(), 'label', 'id');
        $items = array_values(array_filter($items, static fn (array $photo): bool => $photo['hasImage'] ?? true));
        return array_map(static function (array $photo) use ($labels): array {
            $photo['category'] = (string) ($photo['category'] ?? '');
            $photo['categoryLabel'] = $labels[$photo['category']] ?? ($photo['categoryLabel'] ?? '');
            return $photo;
        }, $items);
    }

    /** Gallery filters as [id, label]. The id is the category's web address, which photos store. */
    public static function galleryCategories(): array
    {
        $entries = CmsContentEntry::query()->published()->where('type', 'gallery_category')->with('translations')->orderBy('sort_order')->get();
        if ($entries->isEmpty()) {
            return self::hasCmsRecords('gallery_category') ? [] : array_values(array_filter(GalleryCatalog::categories(), static fn (array $item): bool => ($item['id'] ?? null) !== 'all'));
        }
        return $entries->map(function (CmsContentEntry $entry): array {
            $fields = self::fields($entry);
            $id = (string) (($fields['id'] ?? '') ?: $entry->slug);
            return array_merge($fields, ['id' => $id, 'label' => (string) (($fields['name'] ?? '') ?: ($fields['label'] ?? '') ?: $id)]);
        })->filter(fn (array $item): bool => $item['id'] !== 'all')->values()->all();
    }

    public static function blog(): array
    {
        $items = self::all('blog_post');
        if (!$items) {
            return self::hasCmsRecords('blog_post') ? [] : InsightsCatalog::all();
        }
        return array_map(static fn (array $article): array => self::withBlogCategory($article), $items);
    }

    public static function findBlog(string $slug): ?array
    {
        $entry = CmsContentEntry::query()->published()->where('type', 'blog_post')->where('slug', $slug)->with('translations')->first();
        return $entry ? self::withBlogCategory(self::fields($entry)) : (self::hasCmsRecords('blog_post') ? null : InsightsCatalog::find($slug));
    }

    public static function blogCategories(): array
    {
        $items = CmsContentEntry::query()->published()->where('type', 'blog_category')->with('translations')->orderBy('sort_order')->get();
        if ($items->isEmpty()) return self::hasCmsRecords('blog_category') ? ['all' => 'All insights'] : InsightsCatalog::categories();
        $categories = ['all' => 'All insights'];
        foreach ($items as $entry) {
            $fields = self::fields($entry);
            $categories[$entry->slug] = ($fields['name'] ?? '') ?: $entry->slug;
        }
        return $categories;
    }

    private static function withBlogCategory(array $article): array
    {
        $categories = self::blogCategories();
        $article['categorySlug'] = (string) ($article['categorySlug'] ?? '');
        $article['category'] = $article['categorySlug'] !== '' && isset($categories[$article['categorySlug']])
            ? $categories[$article['categorySlug']]
            : (string) ($article['category'] ?? '');
        $serviceTitles = array_column(self::services(), 'title', 'slug');
        $article['serviceAnchor'] = (string) ($article['serviceAnchor'] ?? '');
        $article['serviceLabel'] = $serviceTitles[$article['serviceAnchor']] ?? (string) ($article['serviceLabel'] ?? '');
        $article['sections'] = array_values(array_filter(
            is_array($article['sections'] ?? null) ? $article['sections'] : [],
            'is_array'
        ));
        return $article;
    }

    public static function activities(): array
    {
        $items = self::all('activity');
        return $items ?: (self::hasCmsRecords('activity') ? [] : ActivitiesCatalog::all());
    }

    private static function hasCmsRecords(string $type): bool
    {
        return CmsContentEntry::withTrashed()->where('type', $type)->exists();
    }

    private static function fields(CmsContentEntry $entry, string $locale = 'en'): array
    {
        $fields = array_replace(CmsContentTypes::defaultsForType($entry->type), $entry->translatedFields($locale));
        foreach ([['image', 'imageAlt'], ['image', 'alt'], ['coverImage', 'coverImageAlt'], ['portrait', 'alt']] as [$imageKey, $altKey]) {
            if (!array_key_exists($altKey, $fields)) {
                continue;
            }
            if (!empty($fields[$imageKey] ?? null) && empty($fields[$altKey])) {
                $path = str_starts_with($fields[$imageKey], 'storage/') ? substr($fields[$imageKey], 8) : $fields[$imageKey];
                $fields[$altKey] = CmsMediaAsset::query()->where('path', $path)->value('alt_text') ?? '';
            }
        }
        $name = (string) (($fields['title'] ?? '') ?: ($fields['name'] ?? ''));
        // Sensible fallbacks so optional fields the client leaves blank never show as gaps.
        if (array_key_exists('shortName', $fields) && trim((string) $fields['shortName']) === '') {
            $fields['shortName'] = $name;
        }
        foreach (['alt', 'imageAlt'] as $altKey) {
            if (array_key_exists($altKey, $fields) && trim((string) $fields[$altKey]) === '') {
                $fields[$altKey] = $name;
            }
        }
        if (array_key_exists('websiteLabel', $fields) && trim((string) $fields['websiteLabel']) === '' && !empty($fields['website'])) {
            $fields['websiteLabel'] = preg_replace('/^www\./', '', (string) parse_url((string) $fields['website'], PHP_URL_HOST));
        }
        if (array_key_exists('image', $fields)) {
            $fields['hasImage'] = trim((string) $fields['image']) !== '';
            if (!$fields['hasImage']) {
                $fields['image'] = self::PLACEHOLDER_IMAGE;
            }
        }
        $fields['slug'] = $entry->slug;
        $fields['cmsId'] = $entry->id;
        return $fields;
    }
}