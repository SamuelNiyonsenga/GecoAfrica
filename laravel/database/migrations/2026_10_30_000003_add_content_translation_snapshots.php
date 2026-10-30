<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cms_content_translations', function (Blueprint $table): void {
            $table->renameColumn('fields', 'published_fields');
            $table->json('draft_fields')->nullable()->after('published_fields');
        });
        DB::table('cms_content_translations')->whereNull('draft_fields')->update(['draft_fields' => DB::raw('published_fields')]);
    }

    public function down(): void
    {
        Schema::table('cms_content_translations', function (Blueprint $table): void {
            $table->dropColumn('draft_fields');
            $table->renameColumn('published_fields', 'fields');
        });
    }
};