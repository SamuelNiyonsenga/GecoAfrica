<?php

namespace Tests\Feature;

use App\Models\CmsContentEntry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CmsSlugConflictTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        DB::table('cms_locales')->insert([
            'code' => 'en',
            'name' => 'English',
            'is_enabled' => true,
            'is_default' => true,
            'sort_order' => 0,
        ]);
    }

    public function test_creating_partner_with_slug_used_by_trashed_partner_returns_validation_error_without_insert(): void
    {
        $this->actingAs($this->createAdmin());
        $entry = $this->createPartner();
        $entry->delete();
        $countBefore = DB::table('cms_content_entries')->count();

        $this->post('/admin/content/partners', [
            'slug' => 'test-partner',
            'intent' => 'draft',
            'fields' => ['name' => 'New Partner'],
        ])->assertSessionHasErrors('slug');

        $this->assertSame($countBefore, DB::table('cms_content_entries')->count());
    }

    public function test_creating_partner_with_slug_used_by_active_partner_returns_validation_error(): void
    {
        $this->actingAs($this->createAdmin());
        $this->createPartner();

        $this->post('/admin/content/partners', [
            'slug' => 'test-partner',
            'intent' => 'draft',
            'fields' => ['name' => 'New Partner'],
        ])->assertSessionHasErrors('slug');
    }

    public function test_editing_partner_while_keeping_its_slug_saves_successfully(): void
    {
        $this->actingAs($this->createAdmin());
        $entry = $this->createPartner();

        $this->put("/admin/content/partners/{$entry->id}", [
            'slug' => 'test-partner',
            'intent' => 'draft',
            'fields' => ['name' => 'Updated Partner'],
        ])->assertSessionDoesntHaveErrors('slug');

        $this->assertDatabaseHas('cms_content_entries', [
            'id' => $entry->id,
            'slug' => 'test-partner',
        ]);
        $this->assertSame('Updated Partner', $entry->fresh()->draftFields('en')['name']);
    }

    private function createAdmin(): User
    {
        return User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'a-long-password-123',
            'is_active' => true,
        ]);
    }

    private function createPartner(): CmsContentEntry
    {
        $entry = CmsContentEntry::create([
            'type' => 'partner',
            'slug' => 'test-partner',
            'status' => 'draft',
            'sort_order' => 0,
        ]);

        $entry->translations()->create([
            'locale_code' => 'en',
            'draft_fields' => ['name' => 'Test Partner'],
            'published_fields' => ['name' => 'Test Partner'],
        ]);

        return $entry;
    }
}
