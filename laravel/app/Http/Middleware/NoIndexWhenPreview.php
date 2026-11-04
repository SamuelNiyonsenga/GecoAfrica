<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/** With APP_NOINDEX=true, tell search engines not to index anything on this copy of the site. */
class NoIndexWhenPreview
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        if (config('app.noindex')) {
            $response->headers->set('X-Robots-Tag', 'noindex, nofollow');
        }
        return $response;
    }
}
