<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CmsPage extends Model
{
    protected $table = 'cms_pages';
    protected $fillable = ['page_key', 'updated_by'];

    public function translations(): HasMany
    {
        return $this->hasMany(CmsPageTranslation::class, 'page_id');
    }

    public function editor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function publishedFields(?string $locale = null): array
    {
        $translation = $this->translationFor($locale);
        return $translation?->published_fields ?? [];
    }

    public function draftFields(?string $locale = null): array
    {
        $translation = $this->translationFor($locale);
        return $translation?->draft_fields ?? [];
    }

    private function translationFor(?string $locale): ?CmsPageTranslation
    {
        $locale ??= app()->getLocale() ?: 'en';
        return $this->translations->firstWhere('locale_code', $locale)
            ?? $this->translations->firstWhere('locale_code', 'en')
            ?? $this->translations->first();
    }
}