<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CmsContentTranslation extends Model
{
    protected $table = 'cms_content_translations';
    protected $fillable = ['content_entry_id', 'locale_code', 'published_fields', 'draft_fields'];
    protected function casts(): array
    {
        return ['published_fields' => 'array', 'draft_fields' => 'array'];
    }
    public function entry(): BelongsTo
    {
        return $this->belongsTo(CmsContentEntry::class, 'content_entry_id');
    }
}