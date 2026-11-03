<?php

namespace Tests\Feature;

use App\Models\CmsContentEntry;
use App\Support\CmsContentTypes;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * A client adding a new item through the CMS usually fills in only the required
 * field. Every public page must keep working when content is that sparse.
 */
class CmsMinimalContentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed explicitly: $seed only applies when the test database is first built.
        $this->seed();
    }

    private const PUBLIC_PAGES = ['/', '/about', '/services', '/projects', '/team', '/partners', '/gallery', '/blog', '/activities', '/contact'];

    public function test_public_pages_survive_a_minimal_item_of_every_content_type(): void
    {
        $parentGroupId = CmsContentEntry::query()->where('type', 'project_group')->value('id');

        foreach (CmsContentTypes::all() as $section => $definition) {
            $fields = [];
            foreach ($definition['fields'] as $key => $spec) {
                if (!empty($spec['required'])) {
                    $fields[$key] = 'Minimal '.$definition['singular'];
                }
            }
            $entry = CmsContentEntry::query()->create([
                'type' => $definition['type'],
                'slug' => 'minimal-'.str_replace('_', '-', $definition['type']),
                'parent_id' => !empty($definition['parent_type']) ? $parentGroupId : null,
                'group_key' => !empty($definition['group_options']) ? array_key_first($definition['group_options']) : null,
                'status' => 'published',
                'published_at' => now(),
                'sort_order' => 0,
                'is_featured' => true,
            ]);
            $entry->translations()->create(['locale_code' => 'en', 'draft_fields' => $fields, 'published_fields' => $fields]);
        }

        foreach (self::PUBLIC_PAGES as $url) {
            $response = $this->get($url);
            $this->assertSame(200, $response->status(), "{$url} returned {$response->status()} with minimal CMS content: ".($response->exception?->getMessage() ?? ''));
            $html = $response->getContent();
            $this->assertStringNotContainsString('src="'.rtrim(asset(''), '/').'/"', $html, "{$url} has an image with no file");
            $this->assertStringNotContainsString('src=""', $html, "{$url} has an image with no file");
        }

        $this->get('/blog/minimal-blog-post')->assertOk();
    }
}
