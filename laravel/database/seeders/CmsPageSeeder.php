<?php

namespace Database\Seeders;

use App\Http\Controllers\AboutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\PartnersController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ActivitiesController;
use App\Models\CmsPage;
use App\Models\CmsSiteSetting;
use Illuminate\Database\Seeder;
use Illuminate\Http\Request;

class CmsPageSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->pageDefaults() as $key => $fields) {
            $this->page($key, $fields);
        }

        CmsSiteSetting::query()->firstOrCreate(['id' => 1], ['fields' => [
            'business_name' => 'GECO Africa Ltd',
            'phone_numbers' => ['+250 788 842 990', '+250 788 572 346'],
            'whatsapp_number' => '+250 788 842 990',
            'public_email' => 'info@gecoafrica.com',
            'notification_email' => 'info@gecoafrica.com',
            'address' => 'Avenue des Poids Lourds, Source Oil Petrol Station building',
            'po_box' => '3711',
            'city' => 'Kigali',
            'country' => 'Rwanda',
            'opening_hours' => [],
            'social_links' => [],
            'default_locale' => 'en',
            'enabled_locales' => ['en'],
        ]]);
    }

    /** @return array<string, array<string, mixed>> */
    public function pageDefaults(): array
    {
        $pages = [];
        $homeFields = app(HomeController::class)->index()->getData();
        unset($homeFields['services'], $homeFields['featuredProjects'], $homeFields['projectReferenceCount'], $homeFields['cmsSiteSettings'], $homeFields['structuredData']);
        $pages['home'] = $homeFields;

        $aboutFields = app(AboutController::class)->index()->getData();
        unset($aboutFields['serviceScopes'], $aboutFields['team'], $aboutFields['cmsSiteSettings'], $aboutFields['structuredData']);
        $pages['about'] = $aboutFields;

        $contactFields = app(ContactController::class)->index()->getData();
        unset($contactFields['cmsSiteSettings'], $contactFields['structuredData']);
        $pages['contact'] = $contactFields;

        $dynamicPages = [
            'services' => [ServicesController::class, ['services']],
            'projects' => [ProjectsController::class, ['featuredProjects', 'projectGroups', 'referenceCount']],
            'team' => [TeamController::class, ['coreTeam', 'supportingTeam', 'teamCount']],
            'partners' => [PartnersController::class, ['partners']],
            'gallery' => [GalleryController::class, ['photos', 'galleryFilters']],
            'blog' => [BlogController::class, ['articles', 'categories', 'categoryCounts', 'activeCategory']],
            'activities' => [ActivitiesController::class, ['activities', 'categories']],
        ];
        foreach ($dynamicPages as $key => [$controller, $dynamic]) {
            $instance = app($controller);
            $pageView = $controller === BlogController::class
                ? $instance->index(Request::create('/blog', 'GET'))
                : $instance->index();
            $fields = $pageView->getData();
            foreach ($dynamic as $field) unset($fields[$field]);
            unset($fields['cmsSiteSettings'], $fields['structuredData']);
            $pages[$key] = $fields;
        }

        return $pages;
    }

    private function page(string $key, array $fields): void
    {
        $page = CmsPage::query()->firstOrCreate(['page_key' => $key]);
        $page->translations()->firstOrCreate(['locale_code' => 'en'], [
            'draft_fields' => $fields,
            'published_fields' => $fields,
            'published_at' => now(),
        ]);
    }
}
