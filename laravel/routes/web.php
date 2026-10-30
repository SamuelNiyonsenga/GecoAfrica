<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\CmsContentController;
use App\Http\Controllers\Admin\CmsPageController;
use App\Http\Controllers\Admin\CmsSystemController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\PartnersController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ActivitiesController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/services', [ServicesController::class, 'index'])->name('services');
Route::get('/projects', [ProjectsController::class, 'index'])->name('projects');
Route::get('/team', [TeamController::class, 'index'])->name('team');
Route::get('/partners', [PartnersController::class, 'index'])->name('partners');
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery');
Route::get('/blog', [BlogController::class, 'index'])->name('blog');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
Route::get('/activities', [ActivitiesController::class, 'index'])->name('activities');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->middleware('throttle:5,1')->name('contact.submit');

Route::prefix('admin')->name('admin.')->group(function (): void {
    Route::get('/login', [AdminAuthController::class, 'create'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'store'])->middleware('throttle:5,15')->name('login.store');

    Route::middleware(['auth', 'cms.active'])->group(function (): void {
        Route::post('/logout', [AdminAuthController::class, 'destroy'])->name('logout');
        Route::get('/', AdminDashboardController::class)->name('dashboard');
        Route::get('/media', [CmsSystemController::class, 'media'])->name('media.index');
        Route::post('/media', [CmsSystemController::class, 'uploadMedia'])->name('media.upload');
        Route::delete('/media/{asset}', [CmsSystemController::class, 'deleteMedia'])->name('media.delete');
        Route::get('/settings', [CmsSystemController::class, 'settings'])->name('settings.edit');
        Route::put('/settings', [CmsSystemController::class, 'saveSettings'])->name('settings.update');
        Route::get('/enquiries', [CmsSystemController::class, 'enquiries'])->name('enquiries.index');
        Route::put('/enquiries/{enquiry}', [CmsSystemController::class, 'updateEnquiry'])->name('enquiries.update');
        Route::get('/activity', [CmsSystemController::class, 'activity'])->name('activity.index');
        Route::get('/trash', [CmsSystemController::class, 'trash'])->name('trash.index');
        Route::post('/trash/content/{entry}/restore', [CmsSystemController::class, 'restoreContent'])->name('trash.content.restore');
        Route::post('/trash/media/{asset}/restore', [CmsSystemController::class, 'restoreMedia'])->name('trash.media.restore');
        Route::get('/pages', [CmsPageController::class, 'index'])->name('pages.index');
        Route::get('/pages/{pageKey}', [CmsPageController::class, 'edit'])->name('pages.edit');
        Route::post('/pages/{pageKey}/draft', [CmsPageController::class, 'saveDraft'])->name('pages.draft');
        Route::post('/pages/{pageKey}/publish', [CmsPageController::class, 'publish'])->name('pages.publish');
        Route::post('/pages/{pageKey}/versions/{version}/restore', [CmsPageController::class, 'restoreVersion'])->name('pages.restore-version');
        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
        Route::put('/users/{user}/password', [AdminUserController::class, 'resetPassword'])->name('users.password');
        Route::patch('/users/{user}/active', [AdminUserController::class, 'setActive'])->name('users.active');

        Route::get('/content/{section}', [CmsContentController::class, 'index'])->name('content.index');
        Route::get('/content/{section}/create', [CmsContentController::class, 'create'])->name('content.create');
        Route::post('/content/{section}', [CmsContentController::class, 'store'])->name('content.store');
        Route::get('/content/{section}/{entry}/edit', [CmsContentController::class, 'edit'])->name('content.edit');
        Route::put('/content/{section}/{entry}', [CmsContentController::class, 'update'])->name('content.update');
        Route::post('/content/{section}/{entry}/publish', [CmsContentController::class, 'publish'])->name('content.publish');
        Route::post('/content/{section}/{entry}/unpublish', [CmsContentController::class, 'unpublish'])->name('content.unpublish');
        Route::delete('/content/{section}/{entry}', [CmsContentController::class, 'destroy'])->name('content.destroy');
        Route::post('/content/{section}/{entry}/versions/{version}/restore', [CmsContentController::class, 'restoreVersion'])->name('content.restore-version');
    });
});

Route::get('/about.php', fn () => redirect('/about/', 301))->name('about.legacy');
Route::get('/services.php', fn () => redirect('/services/', 301))->name('services.legacy');
Route::get('/project.php', fn () => redirect('/projects/', 301))->name('projects.legacy');
Route::get('/team1.php', fn () => redirect('/team/', 301))->name('team.legacy');
Route::get('/Our_Partners.php', fn () => redirect('/partners/', 301))->name('partners.legacy');
Route::get('/gallery.php', fn () => redirect('/gallery/', 301))->name('gallery.legacy');
Route::get('/blog.php', fn () => redirect('/blog/', 301))->name('blog.legacy');
Route::get('/events.php', function () {
    $activityId = request()->query('id');
    $fragment = is_string($activityId) && ctype_digit($activityId) ? '#activity-'.$activityId : '';

    return redirect()->to(route('activities').$fragment, 301);
})->name('activities.legacy');
Route::get('/contact.php', fn () => redirect('/contact/', 301))->name('contact.legacy');
Route::get('/blog-single.php', fn () => redirect('/team/#aline-mukaneza', 301))->name('team.aline.legacy');
Route::get('/blog-single2.php', fn () => redirect('/team/#jean-kizito', 301))->name('team.jean.legacy');
Route::get('/blog-single3.php', fn () => redirect('/team/#marie-assoumpta', 301))->name('team.marie.legacy');
Route::get('/blog-single4.php', fn () => redirect('/team/#angelique-muhawenimana', 301))->name('team.angelique.legacy');