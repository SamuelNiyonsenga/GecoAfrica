<?php

namespace Tests\Feature;

use App\Models\CmsSiteSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FooterTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_footer_lists_cms_services_and_hides_social_links_until_set(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Our services')
            ->assertSee(route('services').'#geotechnical-engineering', false)
            ->assertDontSee('footer-social', false);
    }

    public function test_social_links_from_site_settings_appear_under_the_logo(): void
    {
        $settings = CmsSiteSetting::query()->find(1);
        $fields = $settings->fields;
        $fields['social_links'] = ['linkedin' => 'https://www.linkedin.com/company/test', 'facebook' => 'not-a-url'];
        $settings->fields = $fields;
        $settings->save();

        $this->get('/')
            ->assertOk()
            ->assertSee('href="https://www.linkedin.com/company/test"', false)
            ->assertDontSee('not-a-url');
    }
}