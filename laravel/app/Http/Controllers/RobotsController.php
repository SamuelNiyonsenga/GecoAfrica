<?php

namespace App\Http\Controllers;

use App\Support\Seo;
use Illuminate\Http\Response;

/** robots.txt built from APP_URL and the CMS "hide from search engines" setting. */
class RobotsController extends Controller
{
    public function __invoke(): Response
    {
        $lines = ['User-agent: *'];
        if (Seo::siteHidden()) {
            $lines[] = 'Disallow: /';
        } else {
            $lines[] = 'Disallow: /admin';
            $lines[] = 'Disallow: /admin/';
            $lines[] = '';
            $lines[] = 'Sitemap: '.route('sitemap');
        }

        return response(implode("\n", $lines)."\n", 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }
}
