<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CmsContentVersion extends Model
{
    public $timestamps = false;
    protected $table = 'cms_content_versions';
    protected $fillable = ['entity_type', 'entity_key', 'snapshot', 'note', 'created_by', 'created_at'];
    protected function casts(): array
    {
        return ['snapshot' => 'array', 'created_at' => 'datetime'];
    }
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}