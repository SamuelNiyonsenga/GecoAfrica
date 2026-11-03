<?php

namespace App\Http\Controllers;

use App\Support\CmsContentRepository;
use Illuminate\View\View;

class ProjectsController extends Controller
{
    public function index(): View
    {
        $groups = CmsContentRepository::projectGroups();
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
            'featuredProjects' => CmsContentRepository::featuredProjects(),
            'projectGroups' => $groups,
            'selectedExperience' => CmsContentRepository::selectedExperience(),
            'referenceCount' => CmsContentRepository::referenceCount(),
        ]);
    }
}
