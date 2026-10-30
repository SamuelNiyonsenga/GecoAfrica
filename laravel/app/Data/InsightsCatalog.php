<?php

namespace App\Data;

class InsightsCatalog
{
    /**
     * Practical, evergreen guidance for clients planning engineering work.
     * These are original editorial guides, not historic articles from the former site.
     */
    public static function all(): array
    {
        return [
            [
                'slug' => 'why-ground-investigation-comes-first',
                'title' => 'Why ground investigation comes first',
                'category' => 'Geotechnical engineering',
                'categorySlug' => 'geotechnical',
                'eyebrow' => 'Start with the site',
                'summary' => 'A clear picture of soil, rock and groundwater helps a project team make better early decisions about foundations, earthworks and cost.',
                'image' => 'images/home/fieldwork-drilling.webp',
                'imageAlt' => 'Field team undertaking geotechnical drilling and ground investigation',
                'readTime' => '5 min read',
                'serviceAnchor' => 'geotechnical-engineering',
                'serviceLabel' => 'Geotechnical engineering',
                'sections' => [
                    ['heading' => 'Know what the ground can tell you', 'paragraphs' => [
                        'Every structure transfers loads into the ground. Before a design is settled, the project team needs evidence about the materials below the site, how they vary, and whether groundwater or other conditions may affect the work.',
                        'A geotechnical investigation brings together available site information, field exploration and laboratory testing. The methods and depth of investigation should match the project, the ground conditions and the decisions the design team needs to make.',
                    ]],
                    ['heading' => 'Turn observations into design inputs', 'paragraphs' => [
                        'Field work can include trial pits, boreholes, sampling and in-situ tests. Laboratory tests then help describe selected engineering properties. Together, these results support a ground model and recommendations that the project’s qualified design professionals can review.',
                        'The value is practical: a better basis for selecting foundation concepts, planning excavations, anticipating ground risks and preparing realistic construction requirements. Investigation findings reduce uncertainty; they do not remove the need for engineering judgement as work progresses.',
                    ]],
                    ['heading' => 'Plan the investigation around the project', 'paragraphs' => [
                        'Share the proposed building or infrastructure layout, anticipated loads, levels and project stage with the geotechnical team. That context helps focus the investigation on the questions that matter and makes the resulting report more useful to the wider design team.',
                    ]],
                ],
            ],
            [
                'slug' => 'how-to-read-a-geotechnical-report',
                'title' => 'How to read a geotechnical report',
                'category' => 'Geotechnical engineering',
                'categorySlug' => 'geotechnical',
                'eyebrow' => 'Make the findings useful',
                'summary' => 'A short guide to the ground model, test results, design recommendations and limitations that help teams use an investigation report with confidence.',
                'image' => 'images/projects/lwh-soil-investigation.jpg',
                'imageAlt' => 'Geotechnical soil investigation work supporting engineering decisions',
                'readTime' => '6 min read',
                'serviceAnchor' => 'geotechnical-engineering',
                'serviceLabel' => 'Geotechnical engineering',
                'sections' => [
                    ['heading' => 'Begin with the project context', 'paragraphs' => [
                        'A geotechnical report records what was investigated, where the work took place and which methods were used. Read the scope and site description first so that the findings are understood in the context of the actual project.',
                        'Check the exploration plan and logs alongside the written conclusions. They show where samples and tests came from and help explain how ground conditions may change across a site.',
                    ]],
                    ['heading' => 'Look for the ground model and recommendations', 'paragraphs' => [
                        'The ground model brings observations and test results together into an engineering interpretation. Recommendations may address foundation options, allowable parameters, settlement, excavation, earthworks or groundwater management, depending on the brief.',
                        'Confirm that the recommendations match the design stage and proposed structure. If the layout, loading or levels change, the assumptions behind the report may need to be reviewed by the responsible engineer.',
                    ]],
                    ['heading' => 'Keep limitations in view', 'paragraphs' => [
                        'Investigations sample a site at selected locations and times. The report should explain its scope, assumptions and limitations. Unexpected conditions during construction should be raised promptly so the design team can compare them with the ground model and decide what action is appropriate.',
                    ]],
                ],
            ],
            [
                'slug' => 'what-good-road-supervision-looks-like',
                'title' => 'What good road supervision looks like',
                'category' => 'Roads & infrastructure',
                'categorySlug' => 'infrastructure',
                'eyebrow' => 'From drawings to delivery',
                'summary' => 'Road quality is shaped by a chain of decisions—from drainage and ground preparation through materials, workmanship and clear site records.',
                'image' => 'images/home/road-survey.webp',
                'imageAlt' => 'Road engineering survey work in the field',
                'readTime' => '5 min read',
                'serviceAnchor' => 'road-design-and-supervision',
                'serviceLabel' => 'Road design and supervision',
                'sections' => [
                    ['heading' => 'Quality starts before paving', 'paragraphs' => [
                        'A road’s performance depends on more than its finished surface. Ground preparation, earthworks, layer construction, drainage and the quality of materials all contribute to how the road performs over time.',
                        'Good supervision gives the client and contractor a shared way to check that work follows the approved drawings, specifications and agreed quality procedures. The exact checks depend on the contract and design.',
                    ]],
                    ['heading' => 'Make checks visible and timely', 'paragraphs' => [
                        'A practical supervision process plans inspections around construction stages, records observations, tracks tests and documents decisions. When a concern appears, it can be raised while the relevant work is accessible and the team can agree on a response.',
                        'Drainage details deserve close attention because water can affect road layers and adjoining land. Levels, outlets and connections need to work together as shown in the design and as verified on site.',
                    ]],
                    ['heading' => 'Close the loop with records', 'paragraphs' => [
                        'Clear site records help teams follow progress, resolve questions and understand what was completed. Inspection notes, test records, approved changes and handover information provide a traceable account of the work for the client and future operators.',
                    ]],
                ],
            ],
            [
                'slug' => 'planning-a-building-project-around-its-site',
                'title' => 'Plan a building project around its site',
                'category' => 'Project planning',
                'categorySlug' => 'planning',
                'eyebrow' => 'Bring disciplines together',
                'summary' => 'Topography, access, drainage and ground conditions influence building design. Bringing them into early planning helps teams coordinate a more buildable proposal.',
                'image' => 'images/home/residential-project.webp',
                'imageAlt' => 'Residential building project in an urban setting',
                'readTime' => '4 min read',
                'serviceAnchor' => 'building-design-and-supervision',
                'serviceLabel' => 'Building design',
                'sections' => [
                    ['heading' => 'Start with a shared understanding of the site', 'paragraphs' => [
                        'Before a building layout is fixed, the team should understand the shape of the land, access points, neighbouring conditions, drainage routes and available site information. A site survey and relevant ground investigation give designers a stronger basis for developing the proposal.',
                        'This early coordination can reveal constraints that affect building position, levels, circulation or the way services connect to the site.',
                    ]],
                    ['heading' => 'Coordinate design decisions', 'paragraphs' => [
                        'Architecture, structural engineering, civil works and geotechnical advice each answer different questions. Sharing drawings and assumptions across disciplines helps the team identify interfaces early—for example, where building levels meet site drainage or where retaining works affect access.',
                        'A coordinated design also gives the client clearer information to review and can support more consistent construction documents and tender discussions.',
                    ]],
                    ['heading' => 'Keep the brief and approvals aligned', 'paragraphs' => [
                        'Record the project requirements, design assumptions and decisions as the proposal develops. Confirm applicable approvals and technical requirements with the responsible professionals and relevant authorities for the specific project.',
                    ]],
                ],
            ],
        ];
    }

    public static function categories(): array
    {
        return [
            'all' => 'All insights',
            'geotechnical' => 'Geotechnical',
            'infrastructure' => 'Roads & infrastructure',
            'planning' => 'Project planning',
        ];
    }

    public static function find(string $slug): ?array
    {
        foreach (self::all() as $article) {
            if ($article['slug'] === $slug) {
                return $article;
            }
        }

        return null;
    }
}
