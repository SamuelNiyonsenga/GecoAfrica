<?php

namespace App\Http\Controllers;

use App\Data\PartnersCatalog;
use Illuminate\View\View;

class PartnersController extends Controller
{
    public function index(): View
    {
        $partners = PartnersCatalog::all();
        $items = array_map(static fn (array $partner, int $index): array => [
            '@type' => 'ListItem',
            'position' => $index + 1,
            'item' => [
                '@type' => 'Organization',
                'name' => $partner['name'],
                'url' => $partner['website'],
                'image' => 'https://gecoafrica.com/' . $partner['image'],
            ],
        ], $partners, array_keys($partners));

        return view('partners', [
            'pageTitle' => 'Partners & Professional Network | GECO Africa Rwanda',
            'pageDescription' => 'Explore the five organizations listed on GECO Africa’s previous partner page, with current organization details and verified official websites.',
            'canonicalUrl' => 'https://gecoafrica.com/partners/',
            'ogImage' => 'https://gecoafrica.com/images/partners/rwanda-standards-board.jpg',
            'ogImageAlt' => 'Rwanda Standards Board logo from GECO Africa’s former partner directory',
            'structuredData' => [
                '@context' => 'https://schema.org',
                '@type' => 'CollectionPage',
                'name' => 'Partners | GECO Africa',
                'url' => 'https://gecoafrica.com/partners/',
                'description' => 'Organizations listed on GECO Africa’s previous partner page.',
                'mainEntity' => [
                    '@type' => 'ItemList',
                    'numberOfItems' => count($partners),
                    'itemListElement' => $items,
                ],
            ],
            'partners' => $partners,
        ]);
    }
}
