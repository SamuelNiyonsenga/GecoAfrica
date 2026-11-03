<?php

namespace App\Http\Controllers;

use App\Support\CmsContentRepository;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function index(): View
    {
        $photos = CmsContentRepository::gallery();
        $filters = CmsContentRepository::galleryCategories();

        foreach ($filters as &$filter) {
            $filter['count'] = count(array_filter($photos, static fn (array $photo): bool => $photo['category'] === $filter['id']));
        }
        unset($filter);
        array_unshift($filters, ['id' => 'all', 'label' => 'All', 'count' => count($photos)]);

        $items = array_map(static fn (array $photo, int $index): array => [
            '@type' => 'ListItem',
            'position' => $index + 1,
            'item' => [
                '@type' => 'ImageObject',
                'name' => $photo['title'],
                'contentUrl' => 'https://gecoafrica.com/' . $photo['image'],
                'caption' => $photo['caption'],
                'description' => $photo['alt'],
            ],
        ], $photos, array_keys($photos));

        return view('gallery', [
            'pageTitle' => 'Photo Gallery | GECO Africa Engineering & Site Work',
            'pageDescription' => 'Explore GECO Africa’s photo gallery of engineering designs, geotechnical site investigations, roads, laboratory testing, quarry materials and project work in Rwanda.',
            'canonicalUrl' => 'https://gecoafrica.com/gallery/',
            'ogImage' => 'https://gecoafrica.com/images/gallery/bg_13.jpg',
            'ogImageAlt' => 'GECO Africa drilling and geotechnical fieldwork in Rwanda',
            'preloadImage' => 'https://gecoafrica.com/images/gallery/bg_13.jpg',
            'photos' => $photos,
            'galleryFilters' => $filters,
        ]);
    }
}
