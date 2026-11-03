<?php

namespace App\Support;

use Database\Seeders\CmsPageSeeder;

class CmsPageDefaults
{
    /** @return array<string, array<string, mixed>> */
    public function all(): array
    {
        return app(CmsPageSeeder::class)->pageDefaults();
    }
}
