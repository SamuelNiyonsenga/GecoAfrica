<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->boolean('is_active')->default(true)->after('password');
            $table->timestamp('last_login_at')->nullable()->after('is_active');
        });

        Schema::create('cms_locales', function (Blueprint $table): void {
            $table->string('code', 16)->primary();
            $table->string('name', 80);
            $table->boolean('is_enabled')->default(false);
            $table->boolean('is_default')->default(false);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('cms_content_entries', function (Blueprint $table): void {
            $table->id();
            $table->string('type', 48);
            $table->string('slug', 180)->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('cms_content_entries')->nullOnDelete();
            $table->string('group_key', 100)->nullable();
            $table->string('status', 20)->default('draft');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->unique(['type', 'slug']);
            $table->index(['type', 'status', 'deleted_at', 'sort_order']);
            $table->index(['parent_id', 'sort_order']);
        });

        Schema::create('cms_content_translations', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('content_entry_id')->constrained('cms_content_entries')->cascadeOnDelete();
            $table->string('locale_code', 16);
            $table->json('fields');
            $table->timestamps();
            $table->unique(['content_entry_id', 'locale_code']);
            $table->foreign('locale_code')->references('code')->on('cms_locales')->cascadeOnUpdate()->restrictOnDelete();
        });

        Schema::create('cms_pages', function (Blueprint $table): void {
            $table->id();
            $table->string('page_key', 100)->unique();
            $table->json('draft');
            $table->json('published')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('cms_content_versions', function (Blueprint $table): void {
            $table->id();
            $table->string('entity_type', 64);
            $table->string('entity_key', 180);
            $table->json('snapshot');
            $table->string('note', 255)->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('created_at')->useCurrent();
            $table->index(['entity_type', 'entity_key', 'created_at']);
        });

        Schema::create('cms_media_assets', function (Blueprint $table): void {
            $table->id();
            $table->string('disk', 40)->default('public');
            $table->string('path', 1024)->unique();
            $table->string('original_name', 255);
            $table->string('mime_type', 120);
            $table->unsignedBigInteger('size_bytes')->default(0);
            $table->unsignedInteger('width')->nullable();
            $table->unsignedInteger('height')->nullable();
            $table->unsignedInteger('duration_seconds')->nullable();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('cms_media_attachments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('media_asset_id')->constrained('cms_media_assets')->cascadeOnDelete();
            $table->morphs('attachable');
            $table->unsignedInteger('position')->default(0);
            $table->string('role', 32)->default('gallery');
            $table->timestamps();
            $table->unique(['media_asset_id', 'attachable_type', 'attachable_id'], 'cms_media_attach_unique');
            $table->index(['attachable_type', 'attachable_id', 'position'], 'cms_media_attachable_pos_idx');
        });

        Schema::create('cms_site_settings', function (Blueprint $table): void {
            $table->unsignedTinyInteger('id')->primary();
            $table->json('fields');
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('cms_audit_logs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action', 80);
            $table->string('entity_type', 64);
            $table->string('entity_key', 180);
            $table->json('metadata')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->index(['entity_type', 'entity_key', 'created_at']);
            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_audit_logs');
        Schema::dropIfExists('cms_site_settings');
        Schema::dropIfExists('cms_media_attachments');
        Schema::dropIfExists('cms_media_assets');
        Schema::dropIfExists('cms_content_versions');
        Schema::dropIfExists('cms_pages');
        Schema::dropIfExists('cms_content_translations');
        Schema::dropIfExists('cms_content_entries');
        Schema::dropIfExists('cms_locales');
        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn(['is_active', 'last_login_at']);
        });
    }
};