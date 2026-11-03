<?php

namespace Tests\Feature;

use App\Models\CmsContentEntry;
use App\Models\CmsLocale;
use App\Models\CmsMediaAsset;
use App\Models\CmsPage;
use App\Models\CmsSiteSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class CmsWiringTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        CmsLocale::query()->firstOrCreate(['code' => 'en'], ['name' => 'English', 'is_enabled' => true, 'is_default' => true]);
    }

    public function test_homepage_obeys_section_order_and_visibility(): void
    {
        $page = CmsPage::create(['page_key' => 'home']);
        $page->translations()->create(['locale_code' => 'en', 'draft_fields' => [], 'published_fields' => ['homeSections' => [
            'about' => ['order' => 1, 'visible' => true], 'projects' => ['order' => 2, 'visible' => true], 'facts' => ['order' => 3, 'visible' => false],
        ]]]);
        $html = $this->get('/')->assertOk()->getContent();
        $this->assertLessThan(strpos($html, 'id="home-projects"'), strpos($html, 'id="about"'));
        $this->assertStringNotContainsString('id="home-at-a-glance"', $html);
    }

    public function test_sync_adds_only_missing_fields_and_is_idempotent(): void
    {
        $page = CmsPage::create(['page_key' => 'home']);
        $translation = $page->translations()->create(['locale_code' => 'en', 'draft_fields' => ['pageTitle' => 'Edited draft'], 'published_fields' => ['pageTitle' => 'Edited published']]);
        Artisan::call('cms:sync-page-fields');
        $translation->refresh();
        $this->assertSame('Edited draft', $translation->draft_fields['pageTitle']);
        $this->assertSame('Edited published', $translation->published_fields['pageTitle']);
        $this->assertArrayHasKey('factsItems', $translation->draft_fields);
        $this->assertArrayNotHasKey('structuredData', $translation->draft_fields);
        $before = [$translation->draft_fields, $translation->published_fields];
        Artisan::call('cms:sync-page-fields');
        $this->assertSame($before, [$translation->fresh()->draft_fields, $translation->fresh()->published_fields]);
        $this->assertStringContainsString('0 draft field(s), 0 published field(s) added', Artisan::output());
    }

    public function test_site_settings_phone_is_used_in_contact_json_ld_and_footer(): void
    {
        CmsSiteSetting::create(['id' => 1, 'fields' => ['business_name' => 'GECO Africa Ltd', 'phone_numbers' => ['+250 788 111 222', '+250 788 333 444'], 'public_email' => 'contact@example.com', 'address' => 'New office address', 'po_box' => '999', 'city' => 'Musanze', 'country' => 'Rwanda', 'social_links' => []]]);
        $this->get('/contact')->assertOk()->assertSee('+250 788 111 222')->assertSee('contact@example.com')->assertSee('New office address')
            ->assertSee('"telephone":["+250 788 111 222","+250 788 333 444"]', false);
    }

    public function test_guests_are_redirected_from_page_and_content_preview_routes(): void
    {
        $page = CmsPage::create(['page_key' => 'home']);
        $page->translations()->create(['locale_code' => 'en', 'draft_fields' => [], 'published_fields' => []]);
        $entry = CmsContentEntry::create(['type' => 'partner', 'slug' => 'preview-partner', 'status' => 'draft', 'sort_order' => 0]);
        $this->get('/admin/pages/home/preview')->assertRedirect(route('admin.login'));
        $this->get('/admin/content/partners/'.$entry->id.'/preview')->assertRedirect(route('admin.login'));
    }

    public function test_page_preview_uses_draft_and_public_home_uses_published_copy(): void
    {
        $page = CmsPage::create(['page_key' => 'home']);
        $page->translations()->create(['locale_code' => 'en', 'draft_fields' => ['aboutHeading' => 'Draft preview heading'], 'published_fields' => ['aboutHeading' => 'Published homepage heading']]);
        $admin = User::create(['name' => 'Admin', 'email' => 'admin@example.com', 'password' => 'a-long-password-123', 'is_active' => true]);
        $this->actingAs($admin)->get('/admin/pages/home/preview')->assertOk()->assertHeader('X-Robots-Tag', 'noindex')->assertSee('Draft preview heading');
        $this->get('/')->assertOk()->assertSee('Published homepage heading')->assertDontSee('Draft preview heading');
    }

    public function test_content_preview_includes_a_draft_only_partner(): void
    {
        $entry = CmsContentEntry::create(['type' => 'partner', 'slug' => 'draft-partner-preview', 'status' => 'draft', 'sort_order' => 0]);
        $entry->translations()->create(['locale_code' => 'en', 'draft_fields' => [
            'name' => 'Draft-only partner preview', 'website' => 'https://example.com', 'image' => 'images/geco-logo.png',
            'alt' => 'Partner logo', 'category' => 'Engineering partner', 'legacyName' => null, 'summary' => 'Preview copy', 'websiteLabel' => 'example.com',
        ], 'published_fields' => []]);
        $admin = User::create(['name' => 'Admin', 'email' => 'admin@example.com', 'password' => 'a-long-password-123', 'is_active' => true]);
        $this->actingAs($admin)->get('/admin/content/partners/'.$entry->id.'/preview')->assertOk()->assertHeader('X-Robots-Tag', 'noindex')->assertSee('Draft-only partner preview');
        $this->get('/partners')->assertOk()->assertDontSee('Draft-only partner preview');
    }

    public function test_unchanged_trashed_media_reference_can_still_be_saved(): void
    {
        $admin = User::create(['name' => 'Admin', 'email' => 'admin@example.com', 'password' => 'a-long-password-123', 'is_active' => true]);
        $asset = CmsMediaAsset::create(['disk' => 'public', 'path' => 'cms/trashed-partner.jpg', 'original_name' => 'partner.jpg', 'mime_type' => 'image/jpeg', 'size_bytes' => 1000]);
        $entry = CmsContentEntry::create(['type' => 'partner', 'slug' => 'existing-partner', 'status' => 'draft', 'sort_order' => 0]);
        $entry->translations()->create(['locale_code' => 'en', 'draft_fields' => ['name' => 'Existing partner', 'image' => 'storage/cms/trashed-partner.jpg'], 'published_fields' => []]);
        $asset->delete();
        $this->actingAs($admin)->put('/admin/content/partners/'.$entry->id, ['slug' => 'existing-partner', 'sort_order' => 0, 'fields' => ['name' => 'Existing partner', 'image' => 'storage/cms/trashed-partner.jpg']])->assertRedirect()->assertSessionHas('success');
        $this->assertSame('storage/cms/trashed-partner.jpg', $entry->fresh()->draftFields('en')['image']);
        $this->actingAs($admin)->get('/admin/content/partners/'.$entry->id.'/edit')->assertSee('This image is in Trash. Restore it or choose another.');
    }
}
