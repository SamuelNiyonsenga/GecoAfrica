<?php

namespace Tests\Feature;

use App\Models\CmsPage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CmsPageEditorTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed explicitly: $seed only applies when the test database is first built.
        $this->seed();
    }

    private function admin(): User
    {
        return User::create(['name' => 'Editor', 'email' => 'editor@example.com', 'password' => 'a-long-password-123', 'is_active' => true]);
    }

    /** Submit the page form the way the browser does: only the fields the editor shows. */
    private function editorPayload(string $pageKey, array $overrides = []): array
    {
        $fields = CmsPage::query()->where('page_key', $pageKey)->with('translations')->first()->draftFields('en');
        $payload = [];
        foreach ($fields as $key => $value) {
            if (in_array($key, ['canonicalUrl', 'preloadImage', 'homeSections', 'structuredData'], true)) {
                continue;
            }
            $payload[$key] = is_array($value) ? json_encode($value) : $value;
        }
        return array_replace($payload, $overrides);
    }

    public function test_publishing_a_page_keeps_hidden_canonical_url_and_preload_image(): void
    {
        $before = CmsPage::query()->where('page_key', 'services')->with('translations')->first()->draftFields('en');
        $this->assertNotEmpty($before['canonicalUrl'] ?? null);

        $this->actingAs($this->admin())
            ->post('/admin/pages/services/publish', ['fields' => $this->editorPayload('services', ['pageTitle' => 'Services title edited'])])
            ->assertRedirect();

        $after = CmsPage::query()->where('page_key', 'services')->with('translations')->first();
        $this->assertSame($before['canonicalUrl'], $after->publishedFields('en')['canonicalUrl']);
        $this->assertSame($before['preloadImage'] ?? null, $after->publishedFields('en')['preloadImage'] ?? null);
        $this->assertSame('Services title edited', $after->publishedFields('en')['pageTitle']);
    }

    public function test_page_editor_uses_plain_labels_and_hides_technical_fields(): void
    {
        $this->actingAs($this->admin())->get('/admin/pages/home')
            ->assertOk()
            ->assertSee('Browser tab and Google title')
            ->assertSee('Search &amp; sharing', false)
            ->assertDontSee('name="fields[canonicalUrl]"', false)
            ->assertDontSee('name="fields[preloadImage]"', false)
            ->assertDontSee('Og Image');
    }
}
