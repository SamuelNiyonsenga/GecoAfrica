<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AboutController;
use App\Http\Controllers\ActivitiesController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PartnersController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\TeamController;
use App\Models\CmsContentEntry;
use App\Models\CmsPage;
use App\Support\CmsContentTypes;
use App\Support\CmsPreview;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class CmsPreviewController
{
    public function page(string $pageKey, CmsPreview $preview, Request $request): Response
    {
        abort_unless(in_array($pageKey, ['home', 'about', 'contact', 'services', 'projects', 'team', 'partners', 'gallery', 'blog', 'activities'], true), 404);
        abort_unless(CmsPage::query()->where('page_key', $pageKey)->whereHas('translations', fn ($query) => $query->where('locale_code', 'en'))->exists(), 404);
        $preview->forPage($pageKey);

        $view = match ($pageKey) {
            'home' => app(HomeController::class)->index(),
            'about' => app(AboutController::class)->index(),
            'contact' => app(ContactController::class)->index(),
            'services' => app(ServicesController::class)->index(),
            'projects' => app(ProjectsController::class)->index(),
            'team' => app(TeamController::class)->index(),
            'partners' => app(PartnersController::class)->index(),
            'gallery' => app(GalleryController::class)->index(),
            'blog' => app(BlogController::class)->index($request),
            'activities' => app(ActivitiesController::class)->index(),
        };

        return $this->render($view, $preview);
    }

    public function content(string $section, CmsContentEntry $entry, CmsPreview $preview): Response
    {
        $definition = CmsContentTypes::get($section);
        abort_unless($definition && $entry->type === $definition['type'], 404);
        $preview->forEntry((int) $entry->id, $section);

        $view = match ($entry->type) {
            'blog_post' => app(BlogController::class)->show($entry->slug),
            'service' => app(ServicesController::class)->index(),
            'team_member' => app(TeamController::class)->index(),
            'partner' => app(PartnersController::class)->index(),
            'gallery_item', 'gallery_category' => app(GalleryController::class)->index(),
            'project_feature', 'project_group', 'project_reference' => app(ProjectsController::class)->index(),
            'activity' => app(ActivitiesController::class)->index(),
            'blog_category' => app(BlogController::class)->index(request()),
            default => abort(404),
        };

        return $this->render($view, $preview);
    }

    private function render(View $view, CmsPreview $preview): Response
    {
        try {
            $html = $view->render();
        } finally {
            $preview->clear();
        }

        return response($html)->header('X-Robots-Tag', 'noindex');
    }
}
