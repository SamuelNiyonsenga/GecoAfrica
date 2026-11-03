<?php

namespace App\Data;

final class TeamCatalog
{
    public static function core(): array
    {
        return [
            [
                'slug' => 'jean-kizito',
                'name' => 'MUNYARUGERERO Jean Kizito',
                'shortName' => 'Jean Kizito',
                'role' => 'Founder and Managing Director',
                'image' => 'images/team/jean-kizito.png',
                'cv' => 'assets/cv/jean-kizito.pdf',
                'alt' => 'Portrait of GECO Africa founder and Managing Director Jean Kizito Munyarugerero',
                'bio' => 'A qualified geotechnical engineer, Jean founded GECO Africa. Before establishing the company, he ran the geotechnical engineering laboratory at Kigali Institute of Science and Technology (KIST) for six years. He has led GECO’s work and contributed to geotechnical studies for major infrastructure and building projects in Rwanda.',
                'profile' => 'Professional focus: continual learning and strengthening his engineering skills.',
                'focus' => 'Technical leadership, soil mechanics and geotechnical engineering.',
                'expertise' => ['Geotechnical engineering', 'Soil mechanics', 'Engineering leadership'],
            ],
            [
                'slug' => 'aline-mukaneza',
                'name' => 'MUKANEZA RWABUTOGO Aline Aimée',
                'shortName' => 'Aline Aimée Mukaneza',
                'role' => 'Co-founder and Technical Manager',
                'image' => 'images/team/aline-mukaneza.png',
                'cv' => 'assets/cv/aline-mukaneza.pdf',
                'alt' => 'Portrait of GECO Africa co-founder and Technical Manager Aline Aimée Mukaneza',
                'bio' => 'Aline graduated in civil engineering from KIST in 2010. Her experience includes highway design and construction supervision, preparation of tender and contract documents, project management and contract supervision.',
                'profile' => 'Professional focus: applying management concepts and interpersonal skills while continuing to learn and grow.',
                'focus' => 'Technical coordination, design, project management and contract supervision.',
                'expertise' => ['Civil engineering', 'Highway design', 'Project and contract supervision'],
            ],
            [
                'slug' => 'angelique-muhawenimana',
                'name' => 'MUHAWENIMANA Angélique',
                'shortName' => 'Angélique Muhawenimana',
                'role' => 'Financial and Laboratory Manager',
                'image' => 'images/team/angelique-muhawenimana.png',
                'cv' => 'assets/cv/angelique-muhawenimana.pdf',
                'alt' => 'Portrait of GECO Africa Financial and Laboratory Manager Angélique Muhawenimana',
                'bio' => 'Angélique is responsible for finance and for coordinating the laboratory’s technical, management and financial activities.',
                'focus' => 'Finance and coordination of laboratory operations.',
                'expertise' => ['Financial management', 'Laboratory coordination', 'Technical operations'],
            ],
            [
                'slug' => 'marie-assoumpta',
                'name' => 'UWINEZA Marie Assoumpta',
                'shortName' => 'Marie Assoumpta Uwineza',
                'role' => 'Laboratory Technician',
                'image' => 'images/team/marie-assoumpta.png',
                'cv' => 'assets/cv/marie-assoumpta.pdf',
                'alt' => 'Portrait of GECO Africa Laboratory Technician Marie Assoumpta Uwineza',
                'bio' => 'Marie supports GECO’s soil mechanics and geotechnical testing work. Her published laboratory capabilities include consolidation, permeability, shear, hydrometer, in-situ density, penetrometer and modified Proctor tests.',
                'focus' => 'Laboratory testing for soil mechanics and geotechnical investigations.',
                'expertise' => ['Consolidation', 'Permeability and shear', 'Hydrometer and in-situ density', 'Penetrometer and modified Proctor'],
            ],
        ];
    }

    public static function supporting(): array
    {
        return [
            [
                'name' => 'RWAGASHAYIJA Timothée',
                'role' => 'Geology Expert',
                'qualification' => 'Geologist · MSc',
                'experience' => '20 years’ experience',
                'initials' => 'RT',
            ],
            [
                'name' => 'HABYARIMANA Juvénal',
                'role' => 'Logistics Officer',
                'qualification' => 'Electromechanical Engineer · BSc',
                'experience' => '20 years’ experience',
                'initials' => 'HJ',
            ],
            [
                'name' => 'NZABONIMPA Etienne',
                'role' => 'Accountant',
                'qualification' => 'Economist and Accountant · BSc',
                'experience' => '20 years’ experience',
                'initials' => 'NE',
            ],
            [
                'name' => 'Me MUSONERA Alexis',
                'role' => 'Legal Advisor',
                'qualification' => 'Lawyer · BBA',
                'experience' => '20 years’ experience',
                'initials' => 'MA',
            ],
            [
                'name' => 'RUGWIRO Bonny Francis',
                'role' => 'Surveyor Expert',
                'qualification' => 'Surveyor',
                'experience' => null,
                'initials' => 'RB',
            ],
        ];
    }
}
