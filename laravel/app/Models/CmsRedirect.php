<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CmsRedirect extends Model
{
    protected $table = 'cms_redirects';
    protected $fillable = ['from_path', 'to_path', 'status_code', 'source', 'hits', 'last_hit_at'];

    protected function casts(): array
    {
        return ['last_hit_at' => 'datetime', 'status_code' => 'integer', 'hits' => 'integer'];
    }

    /** "/Blog/Old-Post/?x=1" and "https://gecoafrica.com/blog/old-post" both become "/blog/old-post". */
    public static function normalisePath(string $path): string
    {
        $path = (string) (parse_url(trim($path), PHP_URL_PATH) ?? '');
        $path = '/'.trim(rawurldecode($path), '/');

        return mb_strtolower($path);
    }

    /**
     * Record that an item's public address moved. Keeps chains short: anything that
     * pointed at the old address now points straight at the new one.
     */
    public static function remember(string $fromUrl, string $toUrl): void
    {
        $from = self::normalisePath($fromUrl);
        $to = self::normalisePath($toUrl);
        if ($from === $to || $from === '/') {
            return;
        }
        self::query()->where('to_path', $from)->update(['to_path' => $to]);
        self::query()->where('from_path', $to)->delete(); // the new address is live again
        self::query()->updateOrCreate(['from_path' => $from], ['to_path' => $to, 'status_code' => 301, 'source' => 'auto']);
    }

    public static function findFor(string $path): ?self
    {
        try {
            return self::query()->where('from_path', self::normalisePath($path))->first();
        } catch (\Throwable) {
            return null; // table not migrated yet
        }
    }
}
