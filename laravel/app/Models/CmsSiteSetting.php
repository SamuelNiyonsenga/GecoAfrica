<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CmsSiteSetting extends Model
{
    protected $table = 'cms_site_settings';
    protected $fillable = ['id', 'fields', 'updated_by'];
    protected function casts(): array
    {
        return ['fields' => 'array'];
    }
    public function editor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}