<?php

use App\Http\Middleware\EnsureCmsUserIsActive;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withCommands([__DIR__.'/../app/Console/Commands'])
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectGuestsTo(fn () => route('admin.login'));
        $middleware->append(\App\Http\Middleware\NoIndexWhenPreview::class);
        $middleware->alias(['cms.active' => EnsureCmsUserIsActive::class]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Before showing "page not found", check CMS → Redirects for an old address that moved.
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $exception, \Illuminate\Http\Request $request) {
            if (!$request->isMethod('GET') || $request->is('admin', 'admin/*')) {
                return null;
            }
            $redirect = \App\Models\CmsRedirect::findFor($request->path());
            if (!$redirect) {
                return null;
            }
            $redirect->forceFill(['hits' => $redirect->hits + 1, 'last_hit_at' => now()])->saveQuietly();
            $target = preg_match('#^https?://#i', $redirect->to_path) ? $redirect->to_path : url($redirect->to_path);

            return redirect()->to($target, $redirect->status_code);
        });
    })->create();