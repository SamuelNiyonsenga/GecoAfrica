<?php

namespace App\Http\Controllers;

use App\Support\CmsContentRepository;
use Illuminate\View\View;

class PartnersController extends Controller
{
    public function index(): View
    {
        $partners = CmsContentRepository::partners();
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
            'ogImageAlt' => 'Rwanda Standards Board logo',
            'partners' => $partners,
        ]);
    }
}
