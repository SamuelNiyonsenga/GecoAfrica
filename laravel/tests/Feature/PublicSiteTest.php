<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicSiteTest extends TestCase
{
    use RefreshDatabase;

    public function test_every_public_page_renders_the_main_navigation_landmark(): void
    {
        foreach ([
            '/', '/about', '/services', '/projects', '/team', '/partners', '/gallery',
            '/blog', '/blog/why-ground-investigation-comes-first', '/activities', '/contact',
        ] as $path) {
            $this->get($path)
                ->assertOk()
                ->assertSee('aria-label="Main navigation"', false);
        }
    }

    public function test_unknown_page_uses_the_branded_not_found_view(): void
    {
        $this->get('/this-page-does-not-exist')
            ->assertNotFound()
            ->assertSee('Go to Home')
            ->assertSee('name="robots" content="noindex"', false);
    }

    public function test_standalone_server_error_view_renders_without_the_site_layout(): void
    {
        $this->view('errors.500')
            ->assertSee('We’re having trouble loading this page.')
            ->assertSee('href="/"', false)
            ->assertDontSee('primary-navigation');
    }
}
