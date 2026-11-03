<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cms_page_translations', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('page_id')->constrained('cms_pages')->cascadeOnDelete();
            $table->string('locale_code', 16);
            $table->json('draft_fields');
            $table->json('published_fields')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->unique(['page_id', 'locale_code']);
            $table->foreign('locale_code')->references('code')->on('cms_locales')->cascadeOnUpdate()->restrictOnDelete();
        });

        foreach (DB::table('cms_pages')->get() as $page) {
            DB::table('cms_page_translations')->insert([
                'page_id' => $page->id,
                'locale_code' => 'en',
                'draft_fields' => $page->draft,
                'published_fields' => $page->published,
                'published_at' => $page->published_at,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        Schema::table('cms_pages', function (Blueprint $table): void {
            $table->dropColumn(['draft', 'published', 'published_at']);
        });
    }

    public function down(): void
    {
        Schema::table('cms_pages', function (Blueprint $table): void {
            $table->json('draft')->nullable();
            $table->json('published')->nullable();
            $table->timestamp('published_at')->nullable();
        });
        foreach (DB::table('cms_pages')->get() as $page) {
            $translation = DB::table('cms_page_translations')->where('page_id', $page->id)->where('locale_code', 'en')->first();
            if ($translation) {
                DB::table('cms_pages')->where('id', $page->id)->update([
                    'draft' => $translation->draft_fields,
                    'published' => $translation->published_fields,
                    'published_at' => $translation->published_at,
                ]);
            }
        }
        Schema::dropIfExists('cms_page_translations');
    }
};