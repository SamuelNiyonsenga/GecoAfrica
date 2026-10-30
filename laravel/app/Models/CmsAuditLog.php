<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CmsAuditLog extends Model
{
    public $timestamps = false;
    protected $table = 'cms_audit_logs';
    protected $fillable = ['user_id', 'action', 'entity_type', 'entity_key', 'metadata', 'created_at'];
    protected function casts(): array
    {
        return ['metadata' => 'array', 'created_at' => 'datetime'];
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}