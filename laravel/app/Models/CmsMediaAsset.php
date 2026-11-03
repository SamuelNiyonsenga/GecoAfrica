<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class CmsMediaAsset extends Model
{
    use SoftDeletes;

    protected $table = 'cms_media_assets';
    protected $fillable = ['disk', 'path', 'original_name', 'mime_type', 'size_bytes', 'width', 'height', 'duration_seconds', 'uploaded_by'];
    protected function casts(): array
    {
        return ['size_bytes' => 'integer', 'width' => 'integer', 'height' => 'integer', 'duration_seconds' => 'integer'];
    }
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
    public function publicUrl(): string
    {
        return $this->disk === 'legacy-public' ? asset(ltrim($this->path, '/')) : Storage::disk($this->disk)->url($this->path);
    }
}