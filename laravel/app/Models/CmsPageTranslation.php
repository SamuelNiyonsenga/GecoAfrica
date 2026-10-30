<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CmsPageTranslation extends Model
{
    protected $table = 'cms_page_translations';
    protected $fillable = ['page_id', 'locale_code', 'draft_fields', 'published_fields', 'published_at'];

    protected function casts(): array
    {
        return ['draft_fields' => 'array', 'published_fields' => 'array', 'published_at' => 'datetime'];
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(CmsPage::class, 'page_id');
    }
}