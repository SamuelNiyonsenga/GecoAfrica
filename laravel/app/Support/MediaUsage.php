<?php

namespace App\Support;

use App\Models\CmsContentTranslation;
use App\Models\CmsMediaAsset;
use App\Models\CmsPageTranslation;

class MediaUsage
{
    public static function count(CmsMediaAsset $asset): int
    {
        $paths = array_unique([$asset->path, 'storage/'.ltrim($asset->path, '/')]);
        $items = [];

        foreach (CmsContentTranslation::query()->get(['id', 'content_entry_id', 'draft_fields', 'published_fields']) as $translation) {
            if (self::containsAny($translation->draft_fields ?? [], $paths) || self::containsAny($translation->published_fields ?? [], $paths)) {
                $items['content:'.$translation->content_entry_id] = true;
            }
        }

        foreach (CmsPageTranslation::query()->get(['id', 'page_id', 'draft_fields', 'published_fields']) as $translation) {
            if (self::containsAny($translation->draft_fields ?? [], $paths) || self::containsAny($translation->published_fields ?? [], $paths)) {
                $items['page:'.$translation->page_id] = true;
            }
        }

        return count($items);
    }

    private static function containsAny(array $value, array $paths): bool
    {
        foreach ($value as $item) {
            if (is_string($item) && in_array(ltrim($item, '/'), $paths, true)) return true;
            if (is_array($item) && self::containsAny($item, $paths)) return true;
        }

        return false;
    }
}
