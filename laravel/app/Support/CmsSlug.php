<?php

namespace App\Support;

use Illuminate\Support\Str;

/**
 * Web addresses for CMS items. Apostrophes separate words ("d’Ingénierie" becomes
 * "d-ingenierie"), matching the slug preview in public/js/admin-structured.js.
 */
class CmsSlug
{
    public static function make(string $title): string
    {
        $spaced = preg_replace("/['’‘`´]/u", ' ', $title) ?? $title;

        return Str::limit(Str::slug($spaced), 180, '');
    }
}
