<?php

namespace App\Http\Controllers;

use App\Support\CmsContentRepository;
use App\Support\RichText;
use App\Support\Seo;
use Illuminate\View\View;

class ServicesController extends Controller
{
    public function index(): View
    {
        $services = CmsContentRepository::services();

        return view('services', [
            'pageTitle' => 'Civil & Geotechnical Engineering Services in Rwanda | GECO Africa',
            'pageDescription' => 'Explore GECO Africa’s geotechnical engineering, road design, building design and civil construction services in Rwanda.',
            'canonicalUrl' => 'https://gecoafrica.com/services/',
            'ogImage' => 'https://gecoafrica.com/images/home/fieldwork-drilling.webp',
            'ogImageAlt' => 'GECO Africa team working on a geotechnical site investigation in Rwanda',
            'preloadImage' => 'https://gecoafrica.com/images/home/fieldwork-drilling.webp',
            'services' => $services,
        ]);
    }

    public function show(string $slug): View
    {
        $service = CmsContentRepository::findService($slug);
        abort_if($service === null, 404);

        $body = RichText::withHeadingAnchors((string) ($service['body'] ?? ''), 'service');
        $url = route('services.show', $service['slug']);
        $seo = Seo::forItem($service, 'GECO Africa engineering services in Rwanda.');
        $others = array_values(array_filter(CmsContentRepository::services(), static fn (array $item): bool => $item['slug'] !== $service['slug']));

        return view('service-show', $seo + [
            'cmsPageKey' => 'service',
            'service' => $service,
            'body' => $body['html'],
            'toc' => $body['toc'],
            'otherServices' => $others,
            'relatedArticles' => array_slice(array_values(array_filter(CmsContentRepository::blog(), static fn (array $article): bool => ($article['serviceAnchor'] ?? '') === $service['slug'])), 0, 3),
            'schemaExtra' => [
                [
                    '@type' => 'Service',
                    'name' => $service['title'],
                    'description' => $seo['pageDescription'],
                    'url' => $url,
                    'image' => $seo['ogImage'],
                    'provider' => ['@id' => rtrim((string) config('app.url'), '/').'/#organization'],
                    'areaServed' => 'Rwanda',
                ],
                Seo::breadcrumbs([['Home', route('home')], ['Services', route('services')], [$service['title'], $url]]),
            ],
        ]);
    }
}
