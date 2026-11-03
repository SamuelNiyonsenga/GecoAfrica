<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('cms_media_assets', function (Blueprint $table): void {
            $table->text('alt_text')->nullable()->after('original_name');
        });
    }

    public function down(): void
    {
        Schema::table('cms_media_assets', function (Blueprint $table): void {
            $table->dropColumn('alt_text');
        });
    }
};
