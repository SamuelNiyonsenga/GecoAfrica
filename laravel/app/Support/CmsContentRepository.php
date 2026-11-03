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
        return CmsContentEntry::query()->published()->where('type', $type)->with('translations')->orderBy('sort_order')->get()
            ->map(fn (CmsContentEntry $entry): array => self::fields($entry, $locale))->all();
    }

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
        return $items ?: (self::hasCmsRecords('gallery_item') ? [] : GalleryCatalog::all());
    }

    public static function galleryCategories(): array
    {
        $items = CmsContentEntry::query()->published()->where('type', 'gallery_category')->with('translations')->orderBy('sort_order')->get()
            ->map(fn (CmsContentEntry $entry): array => self::fields($entry))->filter(fn (array $item): bool => ($item['id'] ?? null) !== 'all')->values()->all();
        return $items ?: (self::hasCmsRecords('gallery_category') ? [] : array_values(array_filter(GalleryCatalog::categories(), static fn (array $item): bool => ($item['id'] ?? null) !== 'all')));
    }

    public static function blog(): array
    {
        $items = self::all('blog_post');
        return $items ?: (self::hasCmsRecords('blog_post') ? [] : InsightsCatalog::all());
    }

    public static function findBlog(string $slug): ?array
    {
        $entry = CmsContentEntry::query()->published()->where('type', 'blog_post')->where('slug', $slug)->with('translations')->first();
        return $entry ? self::fields($entry) : (self::hasCmsRecords('blog_post') ? null : InsightsCatalog::find($slug));
    }

    public static function blogCategories(): array
    {
        $items = CmsContentEntry::query()->published()->where('type', 'blog_category')->with('translations')->orderBy('sort_order')->get();
        if ($items->isEmpty()) return self::hasCmsRecords('blog_category') ? [] : InsightsCatalog::categories();
        $categories = ['all' => 'All insights'];
        foreach ($items as $entry) {
            $fields = self::fields($entry);
            $categories[$entry->slug] = $fields['name'] ?? $entry->slug;
        }
        return $categories;
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
        $fields = $entry->translatedFields($locale);
        foreach ([['image', 'imageAlt'], ['coverImage', 'coverImageAlt'], ['portrait', 'alt']] as [$imageKey, $altKey]) {
            if (!empty($fields[$imageKey]) && empty($fields[$altKey])) {
                $path = str_starts_with($fields[$imageKey], 'storage/') ? substr($fields[$imageKey], 8) : $fields[$imageKey];
                $fields[$altKey] = CmsMediaAsset::query()->where('path', $path)->value('alt_text') ?? '';
            }
        }
        $fields['slug'] = $entry->slug;
        $fields['cmsId'] = $entry->id;
        return $fields;
    }
}