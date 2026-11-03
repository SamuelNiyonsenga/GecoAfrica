<?php

namespace App\Http\Controllers;

use App\Models\CmsContentEntry;
use App\Support\CmsContentRepository;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    private const PAGES = ['home', 'about', 'services', 'projects', 'team', 'partners', 'gallery', 'blog', 'activities', 'contact'];

    public function __invoke(): Response
    {
        $urls = [];
        foreach (self::PAGES as $name) {
            $urls[] = ['loc' => route($name), 'lastmod' => null];
        }
        foreach (CmsContentRepository::blog() as $article) {
            $updated = isset($article['cmsId'])
                ? CmsContentEntry::query()->whereKey($article['cmsId'])->value('updated_at')
                : null;
            $urls[] = ['loc' => route('blog.show', $article['slug']), 'lastmod' => $updated ? \Illuminate\Support\Carbon::parse($updated)->toAtomString() : null];
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n".'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";
        foreach ($urls as $url) {
            $xml .= '  <url><loc>'.e($url['loc']).'</loc>'.($url['lastmod'] ? '<lastmod>'.$url['lastmod'].'</lastmod>' : '').'</url>'."\n";
        }
        $xml .= '</urlset>'."\n";

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }
}
