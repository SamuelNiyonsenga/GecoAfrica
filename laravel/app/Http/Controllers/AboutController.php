<?php

namespace App\Http\Controllers;

use App\Support\CmsContentRepository;

use Illuminate\View\View;

class AboutController extends Controller
{
    public function index(): View
    {
        return view('about', [
            'pageTitle' => 'About GECO Africa Ltd | Rwanda Engineering Consultancy',
            'pageDescription' => 'Meet GECO Africa Ltd, a Rwandan-owned civil engineering consultancy founded in 2007. Learn about our people, story, vision and services.',
            'canonicalUrl' => 'https://gecoafrica.com/about/',
            'ogImage' => 'https://gecoafrica.com/images/home/fieldwork-drilling.webp',
            'serviceScopes' => CmsContentRepository::services(),
            'projects' => [
                'Bugesera International Airport',
                'Kigali City Tower',
                'RAMA Building',
                'CHIC and MIC Buildings',
                'T200 Building',
                'Ubumwe Grande Hotel',
                'Eastern Province Administrative Building',
                'Kigali and Gisenyi Marine bases',
            ],
            'team' => CmsContentRepository::coreTeam(),
        ]);
    }
}
