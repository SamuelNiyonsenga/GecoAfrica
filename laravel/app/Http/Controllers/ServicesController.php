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
            'services' => $services,
        ]);
    }
}
