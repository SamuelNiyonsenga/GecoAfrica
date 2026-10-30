<?php

namespace App\Http\Controllers;

use App\Support\CmsContentRepository;
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
            'structuredData' => [
                '@context' => 'https://schema.org',
                '@type' => 'CollectionPage',
                'name' => 'Engineering Services | GECO Africa',
                'url' => 'https://gecoafrica.com/services/',
                'description' => 'Geotechnical, road, building and civil engineering services from GECO Africa Ltd in Rwanda.',
                'mainEntity' => [
                    '@type' => 'ItemList',
                    'numberOfItems' => count($services),
                    'itemListElement' => array_map(static function (array $service, int $index): array {
                        return [
                            '@type' => 'ListItem',
                            'position' => $index + 1,
                            'item' => [
                                '@type' => 'Service',
                                '@id' => 'https://gecoafrica.com/services/#'.$service['slug'],
                                'name' => $service['title'],
                                'description' => $service['summary'],
                                'provider' => ['@id' => 'https://gecoafrica.com/#organization'],
                                'areaServed' => 'Rwanda',
                            ],
                        ];
                    }, $services, array_keys($services)),
                ],
            ],
            'services' => $services,
        ]);
    }
}
