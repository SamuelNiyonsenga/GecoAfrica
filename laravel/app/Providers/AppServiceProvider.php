<?php

namespace App\Providers;

use App\Models\CmsPage;
use App\Models\CmsSiteSetting;
use App\Support\CmsContentRepository;
use App\Support\CmsPreview;
use App\Support\StructuredData;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->scoped(CmsPreview::class, fn () => new CmsPreview());
    }

    public function boot(): void
    {
        View::composer('layouts.admin', function ($view): void {
            try { $view->with('adminNewEnquiries', \App\Models\ContactMessage::query()->where('status', 'new')->count()); }
            catch (\Throwable) { $view->with('adminNewEnquiries', 0); }
        });
        View::composer('layouts.site', function ($view): void {
            try {
                $settings = CmsSiteSetting::query()->find(1)?->fields ?? [];
            } catch (\Throwable $exception) {
                $settings = [];
            }
            $view->with('cmsSiteSettings', $settings);
            $data = $view->getData();
            $pageKey = (string) ($data['cmsPageKey'] ?? 'home');
            $view->with('structuredData', StructuredData::forPage($data, $settings, $pageKey));
            $view->with('cmsPreview', app(CmsPreview::class));
        });
        View::composer(['home', 'about', 'contact', 'services', 'projects', 'team', 'partners', 'gallery', 'blog', 'activities'], function ($view): void {
            $viewName = $view->getName();
            $page = CmsPage::query()->where('page_key', $viewName)->with('translations')->first();
            $data = $view->getData();
            if ($page) $data = array_replace($data, $page->publishedFields('en'));
            unset($data['structuredData']);
            $data['cmsPageKey'] = $viewName;
            if ($viewName === 'home') {
                $data['services'] = CmsContentRepository::homepageServices();
                $data['featuredProjects'] = CmsContentRepository::featuredProjects();
                $data['projectReferenceCount'] = CmsContentRepository::referenceCount();
            } elseif ($viewName === 'about') {
                $data['serviceScopes'] = CmsContentRepository::services();
                $data['team'] = CmsContentRepository::coreTeam();
            }
            try {
                $data['cmsSiteSettings'] = CmsSiteSetting::query()->find(1)?->fields ?? [];
            } catch (\Throwable $exception) {
                $data['cmsSiteSettings'] = [];
            }
            $settings = $data['cmsSiteSettings'];
            $phones = is_array($settings['phone_numbers'] ?? null) ? array_values($settings['phone_numbers']) : [];
            $data['sitePrimaryPhone'] = $phones[0] ?? '+250 788 842 990';
            $data['siteSecondaryPhone'] = $phones[1] ?? '+250 788 572 346';
            $data['sitePublicEmail'] = $settings['public_email'] ?? 'info@gecoafrica.com';
            $data['siteAddress'] = $settings['address'] ?? 'Avenue des Poids Lourds, Source Oil Petrol Station building';
            $data['sitePoBox'] = $settings['po_box'] ?? '3711';
            $data['siteCity'] = $settings['city'] ?? 'Kigali';
            $data['siteCountry'] = $settings['country'] ?? 'Rwanda';
            $data['cmsPreview'] = app(CmsPreview::class);
            $view->with($data);
        });
    }
}
