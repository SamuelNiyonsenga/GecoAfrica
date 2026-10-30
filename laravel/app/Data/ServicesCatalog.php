<?php

namespace App\Data;

final class ServicesCatalog
{
    public static function all(): array
    {
        return [
            [
                'number' => '01',
                'slug' => 'geotechnical-engineering',
                'title' => 'Geotechnical engineering',
                'summary' => 'Understand the ground before you build. GECO carries out site investigations, core drilling and laboratory materials testing.',
                'intro' => 'Core drilling and material testing through the Soil Mechanics and Geotechnical Engineering Laboratory.',
                'image' => 'images/home/fieldwork-drilling.webp',
                'imageAlt' => 'GECO Africa field team conducting a geotechnical investigation in Rwanda',
                'imageCaption' => 'Ground investigation · Rwanda',
                'tags' => ['Site investigation', 'Soil mechanics', 'Materials testing'],
                'items' => ['Core drilling', 'Soil mechanics laboratory testing', 'Geotechnical material testing'],
                'priceList' => 'assets/laboratory-price-list.pdf',
            ],
            [
                'number' => '02',
                'slug' => 'road-design-and-supervision',
                'title' => 'Road design and supervision',
                'summary' => 'Move projects from early feasibility through survey, road design, tender preparation and construction supervision.',
                'intro' => 'Technical input for road and transport infrastructure from preliminary studies and survey to construction supervision and reporting.',
                'image' => 'images/home/road-survey.webp',
                'imageAlt' => 'GECO Africa surveyors carrying out a topographic survey for a road project',
                'imageCaption' => 'Survey · Design · Supervision',
                'tags' => ['Feasibility studies', 'Road design', 'Supervision'],
                'items' => ['Preliminary and feasibility studies', 'Topographic survey, GIS and mapping', 'Hydraulic studies', 'Geological and geotechnical studies', 'Traffic analysis', 'Geometric and pavement design', 'Bills of quantities and tender documents', 'Environmental impact assessment', 'Construction supervision', 'Quality control and verification of executed quantities', 'Project reporting'],
            ],
            [
                'number' => '03',
                'slug' => 'building-design-and-supervision',
                'title' => 'Building design and supervision',
                'summary' => 'Coordinate technical work for durable buildings, from site and structural studies to drawings and quality control.',
                'intro' => 'Building project services spanning preliminary studies, site assessment, architectural and structural design, documentation and supervision.',
                'image' => 'images/home/residential-project.webp',
                'imageAlt' => 'Residential building in Rwanda',
                'imageCaption' => 'Building design · Rwanda',
                'tags' => ['Structural design', 'Tender documents', 'Quality control'],
                'items' => ['Preliminary and feasibility studies', 'Topographic survey, GIS and mapping', 'Geological and geotechnical studies', 'Architectural design', 'Structural design', 'Drawing and drafting', 'Environmental impact assessment', 'Bills of quantities and tender documents', 'Construction supervision', 'Quality control and verification of executed quantities', 'Project reporting'],
            ],
            [
                'number' => '04',
                'slug' => 'civil-engineering-construction',
                'title' => 'Construction of civil engineering projects',
                'summary' => 'Practical engineering support for civil works, with a focus on safe delivery, clear reporting and lasting value.',
                'intro' => 'Construction of civil engineering projects, including infrastructure such as bridges, buildings and roads.',
                'image' => 'images/services/civil-works.jpg',
                'imageAlt' => 'Completed construction project with a service building and paved access in Rwanda',
                'imageCaption' => 'Civil works · Rwanda',
                'tags' => ['Bridges', 'Buildings', 'Roads'],
                'items' => ['Bridges', 'Buildings', 'Roads and related civil works'],
            ],
        ];
    }

    public static function homepageCards(): array
    {
        return array_map(static fn (array $service): array => [
            'number' => $service['number'],
            'slug' => $service['slug'],
            'title' => $service['title'],
            'description' => $service['summary'],
            'tags' => $service['tags'],
        ], self::all());
    }
}
