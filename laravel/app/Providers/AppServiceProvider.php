<?php

namespace App\Providers;

use App\Models\CmsPage;
use App\Models\CmsSiteSetting;
use App\Support\CmsContentRepository;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        View::composer('layouts.site', function ($view): void {
            $view->with('cmsSiteSettings', CmsSiteSetting::query()->find(1)?->fields ?? []);
        });
        View::composer(['home', 'about', 'contact', 'services', 'projects', 'team', 'partners', 'gallery', 'blog', 'activities'], function ($view): void {
            $viewName = $view->getName();
            $page = CmsPage::query()->where('page_key', $viewName)->with('translations')->first();
            $data = $view->getData();
            if ($page) $data = array_replace($data, $page->publishedFields('en'));
            if ($viewName === 'home') {
                $data['services'] = CmsContentRepository::homepageServices();
                $data['featuredProjects'] = CmsContentRepository::featuredProjects();
                $data['projectReferenceCount'] = CmsContentRepository::referenceCount();
            } elseif ($viewName === 'about') {
                $data['serviceScopes'] = CmsContentRepository::services();
                $data['team'] = CmsContentRepository::coreTeam();
            }
            $data['cmsSiteSettings'] = CmsSiteSetting::query()->find(1)?->fields ?? [];
            $view->with($data);
        });
    }
}