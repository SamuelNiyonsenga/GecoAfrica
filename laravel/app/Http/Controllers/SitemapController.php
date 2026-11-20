<?php

namespace App\Http\Controllers;

use App\Models\CmsContentEntry;
use App\Models\CmsPage;
use App\Support\CmsContentRepository;
use App\Support\Seo;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;

/** sitemap.xml: every public page, article, service and featured project, with the date it last changed. */
class SitemapController extends Controller
{
    private const PAGES = ['home', 'about', 'services', 'projects', 'team', 'partners', 'gallery', 'blog', 'activities', 'contact'];

    public function __invoke(): Response
    {
        $urls = [];
        $pageDates = CmsPage::query()->pluck('updated_at', 'page_key');
        foreach (self::PAGES as $name) {
            $urls[] = ['loc' => route($name), 'lastmod' => $pageDates[$name] ?? null, 'image' => null];
        }

        $items = [
            ['route' => 'services.show', 'items' => CmsContentRepository::services()],
            ['route' => 'projects.show', 'items' => CmsContentRepository::featuredProjects()],
            ['route' => 'blog.show', 'items' => CmsContentRepository::blog()],
        ];
        $updated = CmsContentEntry::query()->published()->pluck('updated_at', 'id');
        foreach ($items as $group) {
            foreach ($group['items'] as $item) {
                if (empty($item['slug']) || !empty($item['noindex'])) {
                    continue;
                }
                $urls[] = [
                    'loc' => route($group['route'], $item['slug']),
                    'lastmod' => isset($item['cmsId']) ? ($updated[$item['cmsId']] ?? null) : null,
                    'image' => !empty($item['hasImage'] ?? true) && !empty($item['image']) && $item['image'] !== CmsContentRepository::PLACEHOLDER_IMAGE ? Seo::absoluteUrl($item['image']) : null,
                ];
            }
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n"
            .'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">'."\n";
        foreach ($urls as $url) {
            $xml .= '  <url><loc>'.e($url['loc']).'</loc>'
                .($url['lastmod'] ? '<lastmod>'.Carbon::parse($url['lastmod'])->toAtomString().'</lastmod>' : '')
                .($url['image'] ? '<image:image><image:loc>'.e($url['image']).'</image:loc></image:image>' : '')
                .'</url>'."\n";
        }
        $xml .= '</urlset>'."\n";

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }
}
