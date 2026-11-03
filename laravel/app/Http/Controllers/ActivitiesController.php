<?php

namespace App\Http\Controllers;

use App\Support\CmsContentRepository;
use Illuminate\View\View;

class ActivitiesController extends Controller
{
    public function index(): View
    {
        $activities = CmsContentRepository::activities();
        $activityItems = array_map(static fn (array $activity, int $index): array => [
            '@type' => 'ListItem',
            'position' => $index + 1,
            'name' => $activity['title'],
            'url' => 'https://gecoafrica.com/activities/#activity-'.$activity['id'],
        ], $activities, array_keys($activities));

        return view('activities', [
            'pageTitle' => 'GECO Africa Activities | Engineering Assignments in Rwanda',
            'pageDescription' => 'Explore GECO Africa’s published activity record: geotechnical investigations, topographic surveys, water supply engineering, structural assessments and project services delivered across Rwanda.',
            'canonicalUrl' => 'https://gecoafrica.com/activities/',
            'ogImage' => 'https://gecoafrica.com/images/home/fieldwork-drilling.webp',
            'ogImageAlt' => 'GECO Africa engineering team working on a site investigation in Rwanda',
            'structuredData' => [
                '@context' => 'https://schema.org',
                '@type' => 'CollectionPage',
                'name' => 'GECO Africa activities and engineering assignments',
                'url' => 'https://gecoafrica.com/activities/',
                'description' => 'A record of GECO Africa engineering assignments published on its former activities page.',
                'mainEntity' => [
                    '@type' => 'ItemList',
                    'numberOfItems' => count($activities),
                    'itemListElement' => $activityItems,
                ],
            ],
            'activities' => $activities,
            'categories' => array_values(array_unique(array_column($activities, 'category'))),
        ]);
    }
}
