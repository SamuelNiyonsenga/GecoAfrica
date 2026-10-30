<?php

namespace App\Http\Controllers;

use App\Data\ProjectsCatalog;
use Illuminate\View\View;

class ProjectsController extends Controller
{
    public function index(): View
    {
        $groups = ProjectsCatalog::groups();
        $items = [];

        foreach ($groups as $group) {
            foreach ($group['projects'] as $project) {
                $items[] = [
                    '@type' => 'ListItem',
                    'position' => count($items) + 1,
                    'item' => [
                        '@type' => 'CreativeWork',
                        'name' => $project['title'],
                        'locationCreated' => $project['location'],
                        'provider' => ['@id' => 'https://gecoafrica.com/#organization'],
                    ],
                ];
            }
        }

        return view('projects', [
            'pageTitle' => 'Engineering Projects & References in Rwanda | GECO Africa',
            'pageDescription' => 'Explore GECO Africa’s project references in geotechnical investigations, road design and supervision, drainage, building design and construction across Rwanda and the region.',
            'canonicalUrl' => 'https://gecoafrica.com/projects/',
            'ogImage' => 'https://gecoafrica.com/images/projects/nyanza-asphalt-road.jpg',
            'ogImageAlt' => 'Asphalt road project in Rwanda',
            'preloadImage' => 'https://gecoafrica.com/images/projects/nyanza-asphalt-road.jpg',
            'structuredData' => [
                '@context' => 'https://schema.org',
                '@type' => 'CollectionPage',
                'name' => 'Project References | GECO Africa',
                'url' => 'https://gecoafrica.com/projects/',
                'description' => 'A selection of GECO Africa’s engineering project references in Rwanda and the region.',
                'mainEntity' => [
                    '@type' => 'ItemList',
                    'numberOfItems' => ProjectsCatalog::referenceCount(),
                    'itemListElement' => $items,
                ],
            ],
            'featuredProjects' => ProjectsCatalog::featured(),
            'projectGroups' => $groups,
            'selectedExperience' => ProjectsCatalog::selectedExperience(),
            'referenceCount' => ProjectsCatalog::referenceCount(),
        ]);
    }
}
