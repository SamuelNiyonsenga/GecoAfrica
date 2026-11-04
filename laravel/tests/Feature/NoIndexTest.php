<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NoIndexTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_live_site_is_indexable_by_default(): void
    {
        $response = $this->get('/');
        $response->assertOk()->assertHeaderMissing('X-Robots-Tag');
        $this->assertStringNotContainsString('noindex', $response->getContent());
    }

    public function test_preview_copy_is_hidden_from_search_engines(): void
    {
        config(['app.noindex' => true]);
        $this->get('/')->assertOk()->assertHeader('X-Robots-Tag', 'noindex, nofollow')->assertSee('<meta name="robots" content="noindex, nofollow">', false);
        $this->get('/sitemap.xml')->assertHeader('X-Robots-Tag', 'noindex, nofollow');
    }
}