<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Creating a new draft in the CMS crashed because `published_fields` was NOT NULL
 * (it was renamed from the original `fields` column) while a new draft has no
 * published version yet. Every translation also needs the `en` locale row, so it
 * is guaranteed here instead of relying on the seeder having been run.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cms_content_translations', function (Blueprint $table): void {
            $table->json('published_fields')->nullable()->change();
        });

        if (!DB::table('cms_locales')->where('code', 'en')->exists()) {
            DB::table('cms_locales')->insert([
                'code' => 'en',
                'name' => 'English',
                'is_enabled' => true,
                'is_default' => true,
                'sort_order' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        DB::table('cms_content_translations')->whereNull('published_fields')->update(['published_fields' => '{}']);
        Schema::table('cms_content_translations', function (Blueprint $table): void {
            $table->json('published_fields')->nullable(false)->change();
        });
    }
};
