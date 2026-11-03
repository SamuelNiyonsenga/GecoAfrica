<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CmsContentEntry extends Model
{
    use SoftDeletes;

    protected $table = 'cms_content_entries';
    protected $fillable = ['type', 'slug', 'parent_id', 'group_key', 'status', 'sort_order', 'is_featured', 'published_at'];

    protected function casts(): array
    {
        return ['is_featured' => 'boolean', 'published_at' => 'datetime'];
    }

    public function translations(): HasMany
    {
        return $this->hasMany(CmsContentTranslation::class, 'content_entry_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('sort_order');
    }

    public function media(): MorphToMany
    {
        return $this->morphToMany(CmsMediaAsset::class, 'attachable', 'cms_media_attachments', 'attachable_id', 'media_asset_id')
            ->withPivot(['position', 'role'])->withTimestamps()->orderByPivot('position');
    }

    public function translatedFields(?string $locale = null): array
    {
        $translation = $this->translationFor($locale);
        if (app(\App\Support\CmsPreview::class)->entryId() === $this->id) {
            return $translation?->draft_fields ?? [];
        }
        return $translation?->published_fields ?? [];
    }

    public function draftFields(?string $locale = null): array
    {
        $translation = $this->translationFor($locale);
        return $translation?->draft_fields ?? [];
    }

    private function translationFor(?string $locale): ?CmsContentTranslation
    {
        $locale ??= app()->getLocale() ?: 'en';
        return $this->translations->firstWhere('locale_code', $locale)
            ?? $this->translations->firstWhere('locale_code', 'en')
            ?? $this->translations->first();
    }

    public function scopePublished(Builder $query): Builder
    {
        $entryId = app(\App\Support\CmsPreview::class)->entryId();
        return $query->where(function (Builder $published) use ($entryId): void {
            $published->where(fn (Builder $normal) => $normal->where('status', 'published')->whereNotNull('published_at'));
            if ($entryId !== null) $published->orWhere('id', $entryId);
        });
    }
}
