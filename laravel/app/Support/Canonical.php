<?php

namespace App\Support;

/**
 * The canonical address of the current page, built from APP_URL and the request path
 * with no trailing slash (Apache's .htaccess 301-redirects "/about/" to "/about").
 */
class Canonical
{
    public static function current(): string
    {
        $base = rtrim((string) config('app.url', 'https://gecoafrica.com'), '/');
        $path = trim(request()->path(), '/');

        return $path === '' ? $base.'/' : $base.'/'.$path;
    }
}
