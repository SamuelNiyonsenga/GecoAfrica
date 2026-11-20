<?php

namespace App\Http\Controllers;

use App\Support\CmsContentRepository;
use App\Support\RichText;
use App\Support\Seo;
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

    public function show(string $slug): View
    {
        $project = CmsContentRepository::findFeaturedProject($slug);
        abort_if($project === null, 404);

        $body = RichText::withHeadingAnchors((string) ($project['body'] ?? ''), 'project');
        $url = route('projects.show', $project['slug']);
        $seo = Seo::forItem($project, 'A GECO Africa engineering project in Rwanda.');
        $others = array_values(array_filter(CmsContentRepository::featuredProjects(), static fn (array $item): bool => ($item['slug'] ?? '') !== $project['slug']));

        return view('project-show', $seo + [
            'cmsPageKey' => 'project',
            'project' => $project,
            'body' => $body['html'],
            'toc' => $body['toc'],
            'otherProjects' => array_slice($others, 0, 3),
            'schemaExtra' => [
                array_filter([
                    '@type' => 'CreativeWork',
                    'name' => $project['title'],
                    'description' => $seo['pageDescription'],
                    'url' => $url,
                    'image' => $seo['ogImage'],
                    'locationCreated' => ($project['location'] ?? '') !== '' ? ['@type' => 'Place', 'name' => $project['location']] : null,
                    'creator' => ['@id' => rtrim((string) config('app.url'), '/').'/#organization'],
                ]),
                Seo::breadcrumbs([['Home', route('home')], ['Projects', route('projects')], [$project['title'], $url]]),
            ],
        ]);
    }
}
