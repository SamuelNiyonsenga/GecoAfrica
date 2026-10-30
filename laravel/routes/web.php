<?php

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
