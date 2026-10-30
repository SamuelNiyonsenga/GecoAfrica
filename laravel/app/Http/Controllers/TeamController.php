<?php

namespace App\Http\Controllers;

use App\Support\CmsContentRepository;
use Illuminate\View\View;

class TeamController extends Controller
{
    public function index(): View
    {
        $coreTeam = CmsContentRepository::coreTeam();
        $supportingTeam = CmsContentRepository::supportingTeam();
        $teamMembers = array_merge($coreTeam, $supportingTeam);

        return view('team', [
            'pageTitle' => 'Meet the GECO Africa Engineering Team | Rwanda',
            'pageDescription' => 'Meet GECO Africa’s civil engineering, geotechnical, laboratory and operations team in Rwanda. Read professional profiles and view published CVs.',
            'canonicalUrl' => 'https://gecoafrica.com/team/',
            'ogImage' => 'https://gecoafrica.com/images/team/jean-kizito.png',
            'ogImageAlt' => 'GECO Africa engineering team in Rwanda',
            'preloadImage' => 'https://gecoafrica.com/images/team/jean-kizito.png',
            'structuredData' => [
                '@context' => 'https://schema.org',
                '@type' => 'AboutPage',
                'name' => 'Our Team | GECO Africa',
                'url' => 'https://gecoafrica.com/team/',
                'description' => 'Meet the professional team and supporting specialists listed in GECO Africa’s company profile.',
                'mainEntity' => array_map(static fn (array $member): array => [
                    '@type' => 'Person',
                    'name' => $member['name'],
                    'jobTitle' => $member['role'],
                    'worksFor' => ['@id' => 'https://gecoafrica.com/#organization'],
                ], $teamMembers),
            ],
            'coreTeam' => $coreTeam,
            'supportingTeam' => $supportingTeam,
            'teamCount' => count($teamMembers),
        ]);
    }
}
