<?php

namespace Tests\Feature;

use App\Mail\CmsPasswordResetMail;
use App\Mail\CmsTestMail;
use App\Mail\ContactEnquiryAcknowledgement;
use App\Mail\ContactEnquiryReceived;
use App\Models\CmsContentEntry;
use App\Models\CmsLocale;
use App\Models\CmsRedirect;
use App\Models\CmsSiteSetting;
use App\Models\ContactMessage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Tests\TestCase;

/** Rich text, detail pages, SEO, redirects, ordering, bulk gallery and every email path. */
class CmsCompletenessTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        CmsLocale::query()->firstOrCreate(['code' => 'en'], ['name' => 'English', 'is_enabled' => true, 'is_default' => true]);
        $this->admin = User::query()->create(['name' => 'Aline Admin', 'email' => 'admin@example.com', 'password' => Hash::make('a-long-password-123'), 'is_active' => true]);
    }

    private function publish(string $type, string $slug, array $fields, int $order = 10): CmsContentEntry
    {
        $entry = CmsContentEntry::query()->create(['type' => $type, 'slug' => $slug, 'status' => 'published', 'published_at' => now(), 'sort_order' => $order]);
        $entry->translations()->create(['locale_code' => 'en', 'draft_fields' => $fields, 'published_fields' => $fields]);

        return $entry;
    }

    public function test_blog_rich_text_is_cleaned_and_rendered_with_contents_and_article_schema(): void
    {
        $this->actingAs($this->admin)->post(route('admin.content.store', 'blog'), [
            'intent' => 'publish',
            'fields' => [
                'title' => 'Soil testing before you build',
                'summary' => 'Why ground investigation matters for every building project in Rwanda.',
                'body' => '<h2>Why test the soil</h2><p onclick="alert(1)">Ground <strong>matters</strong>.<script>alert(2)</script></p><p><a href="javascript:alert(3)">bad</a> <a href="https://example.org">good</a></p><h2>What we do</h2><p>Drilling.</p>',
                'publishedDate' => '2026-11-01',
                'seoTitle' => 'Soil testing in Rwanda: a practical guide',
            ],
        ])->assertRedirect();

        $entry = CmsContentEntry::query()->where('type', 'blog_post')->where('slug', 'soil-testing-before-you-build')->firstOrFail();
        $body = $entry->translatedFields('en')['body'];
        $this->assertStringNotContainsString('script', $body);
        $this->assertStringNotContainsString('onclick', $body);
        $this->assertStringNotContainsString('javascript:', $body);
        $this->assertStringContainsString('rel="noopener"', $body);

        $html = $this->get(route('blog.show', 'soil-testing-before-you-build'))->assertOk()->getContent();
        $this->assertStringContainsString('<title>Soil testing in Rwanda: a practical guide</title>', $html);
        $this->assertStringContainsString('href="#guide-section-why-test-the-soil"', $html);
        $this->assertStringContainsString('<h2 id="guide-section-why-test-the-soil">', $html);
        $this->assertStringContainsString('"@type":"BlogPosting"', $html);
        $this->assertStringContainsString('"datePublished":"2026-11-01', $html);
        $this->assertStringContainsString('<meta property="og:type" content="article">', $html);
        $this->assertStringContainsString('1 min read', $html);
    }

    public function test_older_section_articles_open_in_the_rich_text_editor_and_still_render(): void
    {
        $entry = $this->publish('blog_post', 'old-guide', ['title' => 'Old guide', 'sections' => [['heading' => 'First part', 'paragraphs' => ['Hello world.']]]]);
        $this->actingAs($this->admin)->get(route('admin.content.edit', ['blog', $entry]))->assertOk()->assertSee('&lt;h2&gt;First part&lt;/h2&gt;', false);
        $this->get(route('blog.show', 'old-guide'))->assertOk()->assertSee('First part')->assertSee('Hello world.');
    }

    public function test_service_and_featured_project_have_their_own_pages_and_sitemap_entries(): void
    {
        $this->publish('service', 'geotechnical-engineering', ['title' => 'Geotechnical engineering', 'summary' => 'Ground investigation and lab testing.', 'body' => '<h2>Scope</h2><p>Core drilling.</p>', 'items' => ['Core drilling'], 'image' => '', 'gallery' => []]);
        $this->publish('project_feature', 'nyanza-road', ['title' => 'Nyanza road', 'location' => 'Nyanza District', 'client' => 'RTDA', 'summary' => 'Asphalt road supervision.', 'image' => '']);
        $this->publish('project_feature', 'hidden-project', ['title' => 'Hidden project', 'noindex' => '1', 'image' => ''], 20);

        $this->get(route('services'))->assertOk()->assertSee(route('services.show', 'geotechnical-engineering'), false);
        $this->get(route('services.show', 'geotechnical-engineering'))->assertOk()->assertSee('Core drilling')->assertSee('"@type":"Service"', false)->assertSee('"@type":"BreadcrumbList"', false);
        $this->get(route('projects.show', 'nyanza-road'))->assertOk()->assertSee('RTDA')->assertSee('Nyanza District');
        $this->get(route('projects.show', 'hidden-project'))->assertOk()->assertSee('<meta name="robots" content="noindex, follow">', false);
        $this->get(route('services.show', 'does-not-exist'))->assertNotFound();

        $sitemap = $this->get(route('sitemap'))->assertOk()->getContent();
        $this->assertStringContainsString(route('services.show', 'geotechnical-engineering'), $sitemap);
        $this->assertStringContainsString(route('projects.show', 'nyanza-road'), $sitemap);
        $this->assertStringNotContainsString('hidden-project', $sitemap);
    }

    public function test_changing_a_published_address_redirects_the_old_one(): void
    {
        $entry = $this->publish('blog_post', 'first-name', ['title' => 'An article']);
        $this->actingAs($this->admin)->put(route('admin.content.update', ['blog', $entry]), ['slug' => 'better-name', 'fields' => ['title' => 'An article']])->assertRedirect();

        $this->assertDatabaseHas('cms_redirects', ['from_path' => '/blog/first-name', 'to_path' => '/blog/better-name', 'source' => 'auto']);
        $this->get('/blog/first-name')->assertStatus(301)->assertRedirect(url('/blog/better-name'));
        $this->assertSame(1, CmsRedirect::query()->first()->hits);
    }

    public function test_manual_redirects_and_robots_follow_the_settings(): void
    {
        $this->actingAs($this->admin)->post(route('admin.redirects.store'), ['from_path' => 'https://gecoafrica.com/old-page.php?x=1', 'to_path' => '/services'])->assertSessionHasNoErrors();
        $this->get('/old-page.php')->assertStatus(301)->assertRedirect(url('/services'));
        $this->post(route('admin.redirects.store'), ['from_path' => '/admin/users', 'to_path' => '/'])->assertSessionHasErrors('from_path');

        $this->get('/robots.txt')->assertOk()->assertSee('Sitemap: '.route('sitemap'))->assertSee('Disallow: /admin');
        CmsSiteSetting::query()->updateOrCreate(['id' => 1], ['fields' => ['discourage_indexing' => true, 'google_site_verification' => 'abc123', 'ga4_measurement_id' => 'G-TEST1234']]);
        $this->get('/robots.txt')->assertSee("Disallow: /\n", false);
        $this->get('/')->assertSee('<meta name="robots" content="noindex, nofollow">', false)
            ->assertSee('<meta name="google-site-verification" content="abc123">', false)
            ->assertSee('gtag/js?id=G-TEST1234', false);
    }

    public function test_settings_store_seo_and_email_options(): void
    {
        $this->actingAs($this->admin)->put(route('admin.settings.update'), [
            'business_name' => 'GECO Africa Ltd', 'public_email' => 'info@gecoafrica.com', 'phone_numbers_text' => '+250 788 842 990',
            'address' => 'Kigali', 'city' => 'Kigali', 'country' => 'Rwanda',
            'notification_email' => 'one@example.com, two@example.com',
            'auto_reply_enabled' => '0',
            'google_site_verification' => '<meta name="google-site-verification" content="XyZ_123-abc" />',
            'ga4_measurement_id' => 'g-ab12cd34',
        ])->assertSessionHasNoErrors();
        $fields = CmsSiteSetting::query()->find(1)->fields;
        $this->assertSame('one@example.com, two@example.com', $fields['notification_email']);
        $this->assertSame('XyZ_123-abc', $fields['google_site_verification']);
        $this->assertSame('G-AB12CD34', $fields['ga4_measurement_id']);
        $this->assertFalse($fields['auto_reply_enabled']);

        $this->put(route('admin.settings.update'), ['business_name' => 'x', 'public_email' => 'info@gecoafrica.com', 'phone_numbers_text' => '1', 'address' => 'a', 'city' => 'c', 'country' => 'r', 'notification_email' => 'one@example.com, not-an-email'])
            ->assertSessionHasErrors('notification_email');
    }

    public function test_duplicate_and_move_change_items_without_touching_the_original(): void
    {
        $first = $this->publish('activity', 'first', ['title' => 'First'], 10);
        $second = $this->publish('activity', 'second', ['title' => 'Second'], 20);
        $this->actingAs($this->admin)->post(route('admin.content.move', ['activities', $second]), ['direction' => 'up'])->assertRedirect();
        $this->assertLessThan($first->fresh()->sort_order, $second->fresh()->sort_order);

        $this->post(route('admin.content.duplicate', ['activities', $first]))->assertRedirect();
        $copy = CmsContentEntry::query()->where('slug', 'first-copy')->firstOrFail();
        $this->assertSame('draft', $copy->status);
        $this->assertSame('First (copy)', $copy->draftFields('en')['title']);
        $this->assertGreaterThan($first->fresh()->sort_order, $copy->sort_order);
    }

    public function test_new_items_go_to_the_end_and_project_references_can_use_a_draft_group(): void
    {
        $this->publish('activity', 'existing', ['title' => 'Existing'], 50);
        $this->actingAs($this->admin)->post(route('admin.content.store', 'activities'), ['intent' => 'draft', 'fields' => ['title' => 'Newest']])->assertRedirect();
        $this->assertSame(60, CmsContentEntry::query()->where('slug', 'newest')->value('sort_order'));

        $group = CmsContentEntry::query()->create(['type' => 'project_group', 'slug' => 'water', 'status' => 'draft', 'sort_order' => 10]);
        $group->translations()->create(['locale_code' => 'en', 'draft_fields' => ['title' => 'Water works'], 'published_fields' => null]);
        $this->get(route('admin.content.create', 'projects'))->assertOk()->assertSee('Water works (draft, hidden until published)');
        $this->post(route('admin.content.store', 'projects'), ['intent' => 'publish', 'parent_id' => $group->id, 'fields' => ['title' => 'Borehole study']])->assertSessionHasNoErrors();
    }

    public function test_bulk_gallery_upload_creates_one_item_per_photo(): void
    {
        Storage::fake('public');
        $category = $this->publish('gallery_category', 'roads', ['name' => 'Roads']);
        $this->actingAs($this->admin)->post(route('admin.content.gallery-bulk'), [
            'files' => [UploadedFile::fake()->image('ruhango_road-works.jpg'), UploadedFile::fake()->image('site-visit.png')],
            'category' => $category->slug,
            'publish' => '1',
        ])->assertRedirect(route('admin.content.index', 'gallery'));

        $items = CmsContentEntry::query()->where('type', 'gallery_item')->get();
        $this->assertCount(2, $items);
        $this->assertSame('Ruhango road works', $items->first()->translatedFields('en')['title']);
        $this->assertSame('roads', $items->first()->translatedFields('en')['category']);
    }

    public function test_contact_enquiry_sends_notification_and_auto_reply_and_records_both(): void
    {
        Mail::fake();
        CmsSiteSetting::query()->updateOrCreate(['id' => 1], ['fields' => ['notification_email' => 'team@gecoafrica.com, boss@gecoafrica.com', 'public_email' => 'info@gecoafrica.com']]);
        $this->publish('service', 'geotechnical-engineering', ['title' => 'Geotechnical engineering']);

        $this->get(route('contact', ['service' => 'geotechnical-engineering']))->assertOk()->assertSee('<option value="geotechnical-engineering" selected>', false);
        $this->post(route('contact.submit'), ['name' => 'Jean Client', 'email' => 'jean@example.com', 'phone' => '+250 700 000 000', 'service' => 'geotechnical-engineering', 'message' => 'We need soil tests.'])
            ->assertRedirect(route('contact'));

        Mail::assertSent(ContactEnquiryReceived::class, fn ($mail) => $mail->hasTo('team@gecoafrica.com') && $mail->hasTo('boss@gecoafrica.com') && $mail->hasReplyTo('jean@example.com'));
        Mail::assertSent(ContactEnquiryAcknowledgement::class, fn ($mail) => $mail->hasTo('jean@example.com') && $mail->hasReplyTo('info@gecoafrica.com'));
        $enquiry = ContactMessage::query()->firstOrFail();
        $this->assertSame('Geotechnical engineering', $enquiry->service);
        $this->assertNotNull($enquiry->notification_sent_at);
        $this->assertNotNull($enquiry->acknowledgement_sent_at);
    }

    public function test_email_templates_render_with_the_enquiry_details(): void
    {
        $enquiry = ContactMessage::query()->create(['name' => 'Jean & Co', 'email' => 'jean@example.com', 'phone' => '+250 700', 'service' => 'General enquiry', 'message' => "Line one\nLine two"]);
        $html = (new ContactEnquiryReceived($enquiry))->render();
        $this->assertStringContainsString('Jean &amp; Co', $html);
        $this->assertStringContainsString('Line one<br />', $html);
        $ack = (new ContactEnquiryAcknowledgement($enquiry))->render();
        $this->assertStringContainsString('We have received your enquiry', $ack);
    }

    public function test_failed_email_is_recorded_shown_and_can_be_resent(): void
    {
        Mail::shouldReceive('to')->andThrow(new RuntimeException('Connection could not be established with host "mail.gecoafrica.com:465"'));
        $this->post(route('contact.submit'), ['name' => 'Jean', 'email' => 'jean@example.com', 'phone' => '+250 700', 'message' => 'Hello'])
            ->assertRedirect(route('contact'));
        $enquiry = ContactMessage::query()->firstOrFail();
        $this->assertNull($enquiry->notification_sent_at);
        $this->assertStringContainsString('Connection could not be established', $enquiry->notification_error);

        $this->actingAs($this->admin)->get(route('admin.enquiries.index'))->assertOk()->assertSee('Failed')->assertSee('Try again');
        $this->get(route('admin.email.index'))->assertOk()->assertSee('Connection could not be established');

        Mail::swap(new \Illuminate\Mail\MailManager($this->app));
        Mail::fake();
        $this->post(route('admin.enquiries.resend', $enquiry), ['email' => 'notification'])->assertSessionHasNoErrors();
        Mail::assertSent(ContactEnquiryReceived::class);
        $this->assertNotNull($enquiry->fresh()->notification_sent_at);
        $this->assertNull($enquiry->fresh()->notification_error);
    }

    public function test_test_email_page_reports_real_results(): void
    {
        Mail::fake();
        config(['mail.default' => 'smtp']);
        $this->actingAs($this->admin)->post(route('admin.email.test'), ['to' => 'me@example.com'])->assertSessionHas('success');
        Mail::assertSent(CmsTestMail::class, fn ($mail) => $mail->hasTo('me@example.com'));

        config(['mail.default' => 'log']);
        $this->post(route('admin.email.test'), ['to' => 'me@example.com'])->assertSessionHasErrors('to');
        $this->get(route('admin.email.index'))->assertSee('Emails are not being sent');
    }

    public function test_forgot_password_emails_a_working_link(): void
    {
        Mail::fake();
        $this->get(route('admin.login'))->assertSee('Forgot your password?');
        $this->post(route('admin.password.email'), ['email' => 'nobody@example.com'])->assertSessionHas('status');
        Mail::assertNothingSent();

        $this->post(route('admin.password.email'), ['email' => 'ADMIN@example.com'])->assertSessionHas('status');
        $token = null;
        Mail::assertSent(CmsPasswordResetMail::class, function ($mail) use (&$token) {
            $token = $mail->token;
            return $mail->hasTo('admin@example.com') && !$mail->invitation;
        });

        $this->get(route('admin.password.reset', ['token' => $token, 'email' => 'admin@example.com']))->assertOk();
        $this->post(route('admin.password.update'), ['token' => $token, 'email' => 'admin@example.com', 'password' => 'brand-new-password-1', 'password_confirmation' => 'brand-new-password-1'])
            ->assertRedirect(route('admin.login'));
        $this->assertTrue(Hash::check('brand-new-password-1', $this->admin->fresh()->password));
        $this->post(route('admin.password.update'), ['token' => $token, 'email' => 'admin@example.com', 'password' => 'another-password-12', 'password_confirmation' => 'another-password-12'])
            ->assertSessionHasErrors('email');
    }

    public function test_new_users_can_be_invited_by_email(): void
    {
        Mail::fake();
        $this->actingAs($this->admin)->post(route('admin.users.store'), ['name' => 'Marie', 'email' => 'marie@example.com', 'send_invite' => '1'])->assertSessionHasNoErrors();
        $marie = User::query()->where('email', 'marie@example.com')->firstOrFail();
        Mail::assertSent(CmsPasswordResetMail::class, fn ($mail) => $mail->invitation && $mail->hasTo('marie@example.com') && Password::broker()->tokenExists($marie, $mail->token));

        $this->post(route('admin.users.store'), ['name' => 'No password', 'email' => 'np@example.com'])->assertSessionHasErrors('password');
    }

    public function test_seo_health_page_lists_problems_in_plain_language(): void
    {
        $this->publish('blog_post', 'short', ['title' => 'Hi', 'body' => '<p>Short.</p>', 'image' => 'images/a.jpg', 'imageAlt' => '']);
        $this->actingAs($this->admin)->get(route('admin.seo.index'))->assertOk()
            ->assertSee('The article is short')
            ->assertSee('The main photo has no description');
        $this->get(route('admin.redirects.index'))->assertOk();
    }
}
