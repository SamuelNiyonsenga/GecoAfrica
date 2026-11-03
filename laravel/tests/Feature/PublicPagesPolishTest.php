<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPagesPolishTest extends TestCase
{
    use RefreshDatabase;

    public function test_gallery_starts_with_all_photographs_selected(): void
    {
        $this->get('/gallery')
            ->assertOk()
            ->assertSee('data-gallery-filter="all" aria-pressed="true"', false)
            ->assertSee('<span>All</span>', false);
    }

    public function test_public_pages_do_not_expose_migration_language(): void
    {
        $phrases = [
            'original site', 'former website', 'former site', 'original schedule',
            'original gallery caption', 'listed as', 'original reference pdf',
            'recorded on', 'named on the former site', 'published reference schedule',
        ];

        foreach ([
            '/', '/about', '/services', '/projects', '/team', '/partners', '/gallery',
            '/blog', '/blog/why-ground-investigation-comes-first', '/activities', '/contact',
        ] as $path) {
            $response = $this->get($path)->assertOk();
            $html = mb_strtolower($response->getContent());
            foreach ($phrases as $phrase) {
                $this->assertStringNotContainsString($phrase, $html, "Migration wording found on {$path}: {$phrase}");
            }
        }
    }

    public function test_invalid_contact_submission_shows_errors_without_success_or_saving(): void
    {
        $this->from('/contact')->post('/contact', [])->assertRedirect('/contact')->assertSessionHasErrors();

        $this->assertDatabaseCount('contact_messages', 0);
        $this->get('/contact')->assertDontSee('contact_success-panel')->assertSee('contact-alert-error', false);
    }

    public function test_valid_contact_submission_saves_before_showing_success_panel(): void
    {
        $this->from('/contact')->post('/contact', [
            'name' => 'Test Visitor',
            'email' => 'visitor@example.com',
            'phone' => '+250 788 000 000',
            'service' => 'general',
            'message' => 'Please contact me about an engineering project.',
        ])->assertRedirect('/contact')->assertSessionHas('contact_success');

        $this->assertDatabaseCount('contact_messages', 1);
        $this->assertDatabaseHas('contact_messages', [
            'email' => 'visitor@example.com',
            'message' => 'Please contact me about an engineering project.',
        ]);
        $this->get('/contact')->assertSee('contact-success-panel', false)->assertDontSee('class="contact-form"', false);
    }

    public function test_contact_honeypot_does_not_show_success_or_store_an_enquiry(): void
    {
        $this->from('/contact')->post('/contact', ['company_website' => 'spam.example'])
            ->assertRedirect('/contact')->assertSessionHasErrors('contact')->assertSessionMissing('contact_success');

        $this->assertSame(0, ContactMessage::query()->count());
    }
}
