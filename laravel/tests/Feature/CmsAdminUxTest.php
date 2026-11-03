<?php

namespace Tests\Feature;

use App\Models\CmsContentEntry;
use App\Models\CmsMediaAsset;
use App\Models\ContactMessage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CmsAdminUxTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::create(['name' => 'Admin User', 'email' => 'admin@example.com', 'password' => 'a-long-password-123', 'is_active' => true]);
    }

    public function test_dashboard_shows_new_enquiry_count_without_developer_note(): void
    {
        ContactMessage::create(['name' => 'Aline', 'email' => 'aline@example.com', 'phone' => '+250788000000', 'service' => 'Road supervision', 'message' => 'Please contact us.']);
        $this->actingAs($this->admin())->get('/admin')->assertOk()->assertSee('New enquiries')->assertSee('Aline')->assertDontSee('is being connected');
    }

    public function test_media_picker_is_authenticated_and_returns_paginated_assets(): void
    {
        CmsMediaAsset::create(['disk' => 'public', 'path' => 'cms/picker/photo.jpg', 'original_name' => 'photo.jpg', 'mime_type' => 'image/jpeg', 'size_bytes' => 1000]);
        $this->get('/admin/media/picker')->assertRedirect(route('admin.login'));
        $this->actingAs($this->admin())->getJson('/admin/media/picker')->assertOk()->assertJsonPath('items.0.name', 'photo.jpg')->assertJsonStructure(['items', 'next_page_url']);
    }

    public function test_creating_partner_without_slug_generates_one_from_name(): void
    {
        $this->actingAs($this->admin())->post('/admin/content/partners', ['intent' => 'draft', 'fields' => ['name' => 'École d’Ingénierie du Rwanda']])->assertRedirect();
        $this->assertDatabaseHas('cms_content_entries', ['type' => 'partner', 'slug' => 'ecole-d-ingenierie-du-rwanda']);
    }

    public function test_media_alt_text_column_and_public_fallback_are_available(): void
    {
        $asset = CmsMediaAsset::create(['disk' => 'public', 'path' => 'cms/test-alt.jpg', 'original_name' => 'alt.jpg', 'alt_text' => 'Field team at a test site', 'mime_type' => 'image/jpeg', 'size_bytes' => 1000]);
        $this->assertDatabaseHas('cms_media_assets', ['id' => $asset->id, 'alt_text' => 'Field team at a test site']);
        $entry = CmsContentEntry::create(['type' => 'partner', 'slug' => 'alt-fallback-partner', 'status' => 'published', 'published_at' => now(), 'sort_order' => 0]);
        $entry->translations()->create(['locale_code' => 'en', 'draft_fields' => ['name' => 'Alt fallback partner', 'category' => 'Engineering', 'image' => 'storage/cms/test-alt.jpg', 'alt' => '', 'website' => 'https://example.com', 'websiteLabel' => 'example.com', 'summary' => 'Partner profile'], 'published_fields' => ['name' => 'Alt fallback partner', 'category' => 'Engineering', 'image' => 'storage/cms/test-alt.jpg', 'alt' => '', 'website' => 'https://example.com', 'websiteLabel' => 'example.com', 'summary' => 'Partner profile']]);
        $this->get('/partners')->assertOk()->assertSee('Field team at a test site');
    }
}
