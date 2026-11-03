<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CmsLocale extends Model
{
    protected $table = 'cms_locales';
    protected $primaryKey = 'code';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['code', 'name', 'is_enabled', 'is_default', 'sort_order'];
    protected function casts(): array
    {
        return ['is_enabled' => 'boolean', 'is_default' => 'boolean'];
    }
}