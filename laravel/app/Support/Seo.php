<?php

namespace App\Support;

use App\Models\CmsSiteSetting;
use Illuminate\Support\Str;

/**
 * Search and sharing details for public pages: titles, descriptions, sharing
 * images and robots rules, using the CMS item's own SEO fields when filled in.
 */
class Seo
{
    private static ?array $settings = null;

    public static function settings(): array
    {
        if (self::$settings === null || app()->runningUnitTests()) {
            try {
                self::$settings = CmsSiteSetting::query()->find(1)?->fields ?? [];
            } catch (\Throwable) {
                self::$settings = [];
            }
        }

        return self::$settings;
    }

    public static function titleSuffix(): string
    {
        $suffix = trim((string) (self::settings()['seo_title_suffix'] ?? ''));

        return $suffix === '' ? ' | GECO Africa' : ' '.ltrim($suffix);
    }

    /** True when the client has asked search engines to stay away from the whole site. */
    public static function siteHidden(): bool
    {
        return (bool) config('app.noindex') || !empty(self::settings()['discourage_indexing']);
    }

    public static function absoluteUrl(?string $path): ?string
    {
        $path = trim((string) $path);
        if ($path === '') {
            return null;
        }
        if (preg_match('#^https?://#i', $path)) {
            return $path;
        }

        return rtrim((string) config('app.url'), '/').'/'.ltrim($path, '/');
    }

    public static function defaultImage(): string
    {
        return self::absoluteUrl(self::settings()['seo_default_image'] ?? null) ?? self::absoluteUrl('images/home/fieldwork-drilling.webp');
    }

    /**
     * View data for a CMS item that has its own page.
     *
     * @param  array  $item  Published fields of the item
     * @param  string  $fallbackDescription  Used when the item has no summary or SEO description
     */
    public static function forItem(array $item, string $fallbackDescription = ''): array
    {
        $title = trim((string) ($item['seoTitle'] ?? ''));
        if ($title === '') {
            $title = trim((string) ($item['title'] ?? $item['name'] ?? '')).self::titleSuffix();
        }
        $description = trim((string) ($item['seoDescription'] ?? ''));
        if ($description === '') {
            $description = trim((string) ($item['summary'] ?? '')) ?: trim((string) ($item['intro'] ?? ''));
        }
        if ($description === '' && !empty($item['body']) && is_string($item['body'])) {
            $description = RichText::plainText($item['body']);
        }
        $description = Str::limit($description !== '' ? $description : $fallbackDescription, 160, '…');

        $image = trim((string) ($item['seoImage'] ?? ''));
        if ($image === '' && !empty($item['hasImage'] ?? true)) {
            $image = (string) ($item['image'] ?? '');
        }
        if ($image === CmsContentRepository::PLACEHOLDER_IMAGE) {
            $image = '';
        }

        return [
            'pageTitle' => $title,
            'pageDescription' => $description,
            'ogImage' => self::absoluteUrl($image) ?? self::defaultImage(),
            'ogImageAlt' => (string) (($item['imageAlt'] ?? '') ?: ($item['alt'] ?? '') ?: ($item['title'] ?? '')),
            'robots' => !empty($item['noindex']) ? 'noindex, follow' : null,
        ];
    }

    /** schema.org BreadcrumbList for a detail page. */
    public static function breadcrumbs(array $trail): array
    {
        $items = [];
        foreach (array_values($trail) as $index => [$name, $url]) {
            $items[] = ['@type' => 'ListItem', 'position' => $index + 1, 'name' => $name, 'item' => $url];
        }

        return ['@type' => 'BreadcrumbList', 'itemListElement' => $items];
    }
}
