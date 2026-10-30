<?php

namespace App\Data;

final class PartnersCatalog
{
    public static function all(): array
    {
        return [
            [
                'name' => 'Africa Drilling & Exploration Ltd',
                'shortName' => 'Africa Drilling & Exploration',
                'category' => 'Water & field services',
                'image' => 'images/partners/africa-drilling-exploration.jpg',
                'alt' => 'Africa Drilling & Exploration Ltd logo published on GECO Africa’s former partner page',
                'website' => 'https://www.afridrillers.com/',
                'websiteLabel' => 'afridrillers.com',
                'summary' => 'The former GECO page identified Africa Drilling & Exploration Ltd as a partner. The company’s published services describe hydrogeological and geophysical surveys, borehole drilling, pump installation and water-related field work.',
                'legacyName' => null,
            ],
            [
                'name' => 'Rwanda Standards Board',
                'shortName' => 'Rwanda Standards Board',
                'category' => 'Standards & quality infrastructure',
                'image' => 'images/partners/rwanda-standards-board.jpg',
                'alt' => 'Rwanda Standards Board logo published on GECO Africa’s former partner page',
                'website' => 'https://www.rsb.gov.rw/about/overview',
                'websiteLabel' => 'rsb.gov.rw',
                'summary' => 'Rwanda Standards Board is a public institution responsible for standards, conformity assessment and metrology services. Its wider quality infrastructure work includes testing, certification and technical support.',
                'legacyName' => null,
            ],
            [
                'name' => 'Saint Joseph Integrated Technical College',
                'shortName' => 'Saint Joseph Integrated Technical College',
                'category' => 'Technical education',
                'image' => 'images/partners/saint-joseph-technical-college.jpg',
                'alt' => 'Saint Joseph Integrated Technical College logo published on GECO Africa’s former partner page',
                'website' => 'https://sjitc.ac.rw/',
                'websiteLabel' => 'sjitc.ac.rw',
                'summary' => 'Based in Nyamirambo, Kigali, SJITC is a technical college focused on practical learning and skills development. Its current website features technical programmes, college facilities and student resources.',
                'legacyName' => 'SJITC',
            ],
            [
                'name' => 'Intercontinental Consultants and Technocrats Pvt. Ltd.',
                'shortName' => 'Intercontinental Consultants & Technocrats',
                'category' => 'Infrastructure consultancy',
                'image' => 'images/partners/intercontinental-consultants-technocrats.jpg',
                'alt' => 'Intercontinental Consultants and Technocrats logo published on GECO Africa’s former partner page',
                'website' => 'https://www.ictonline.com/',
                'websiteLabel' => 'ictonline.com',
                'summary' => 'ICT is an international infrastructure consultancy whose published practice spans transport, roads, bridges, airports, urban development, water resources, construction supervision and related engineering services.',
                'legacyName' => 'ICT',
            ],
            [
                'name' => 'Institute of Engineering Rwanda',
                'shortName' => 'Institute of Engineering Rwanda',
                'category' => 'Engineering profession',
                'image' => 'images/partners/institute-engineering-rwanda.jpg',
                'alt' => 'Former Institution of Engineers Rwanda logo from GECO Africa’s partner page',
                'website' => 'https://engineersrwanda.rw/',
                'websiteLabel' => 'engineersrwanda.rw',
                'summary' => 'GECO’s former page listed the organization under its previous name, Institution of Engineers Rwanda. It is now the Institute of Engineering Rwanda, a professional body supporting standards, ethical practice and capacity development across the engineering sector.',
                'legacyName' => 'Formerly Institution of Engineers Rwanda',
            ],
        ];
    }
}
