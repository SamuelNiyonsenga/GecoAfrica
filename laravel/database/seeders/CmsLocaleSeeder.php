<?php

namespace Database\Seeders;

use App\Models\CmsLocale;
use Illuminate\Database\Seeder;

class CmsLocaleSeeder extends Seeder
{
    public function run(): void
    {
        CmsLocale::query()->firstOrCreate(['code' => 'en'], [
            'name' => 'English',
            'is_enabled' => true,
            'is_default' => true,
            'sort_order' => 0,
        ]);
    }
}