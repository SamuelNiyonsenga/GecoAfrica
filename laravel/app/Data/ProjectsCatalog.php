<?php

namespace App\Data;

final class ProjectsCatalog
{
    public static function featured(): array
    {
        return [
            [
                'title' => 'Asphalt road supervision',
                'location' => 'Nyanza District, Southern Province',
                'category' => 'Roads & transport',
                'summary' => 'Supervision of asphalt road construction in Nyanza District, bringing construction oversight to a public road project.',
                'image' => 'images/projects/nyanza-asphalt-road.jpg',
                'imageAlt' => 'Asphalt road works in Nyanza District, Rwanda',
                'number' => '01',
            ],
            [
                'title' => 'Asphalt roads in Rusizi',
                'location' => 'Rusizi District, Western Province',
                'category' => 'Roads & transport',
                'summary' => 'Construction supervision for asphalt roads in Rusizi District. The reference schedule records a 4.94 km assignment.',
                'image' => 'images/projects/rusizi-asphalt-road.jpg',
                'imageAlt' => 'Completed asphalt road with pedestrian crossing in Rwanda',
                'number' => '02',
            ],
            [
                'title' => 'MINECOFIN archive library renovation',
                'location' => 'Kigali, Rwanda',
                'category' => 'Buildings & facilities',
                'summary' => 'Renovation of the MINECOFIN archive library into a transformer housing facility.',
                'image' => 'images/projects/minecofin-transformer-facility.jpg',
                'imageAlt' => 'Renovated transformer housing facility at the former MINECOFIN archive library',
                'number' => '03',
            ],
            [
                'title' => 'Land Husbandry & Hillside Irrigation',
                'location' => 'Nyanza and Gatsibo Districts, Rwanda',
                'category' => 'Geotechnical & land development',
                'summary' => 'Soil analysis for the Land Husbandry, Water Harvesting and Hillside Irrigation (LWH) project across sites in Nyanza and Gatsibo.',
                'image' => 'images/projects/lwh-soil-investigation.jpg',
                'imageAlt' => 'Soil sampling equipment used for a land husbandry investigation in Rwanda',
                'number' => '04',
                'areaNote' => 'The former project gallery lists 500 ha in Nyanza and 100 ha in Gatsibo. The references schedule separately records a 25 ha soil-analysis assignment for Nyanza and Gatsibo. The published source materials do not explain the difference, so both figures are retained as originally recorded.',
            ],
        ];
    }

    public static function groups(): array
    {
        return [
            [
                'id' => 'geotechnical',
                'number' => '01',
                'title' => 'Geotechnical investigations',
                'intro' => 'Site investigations, soil analysis, geophysical and hydrological surveys for buildings, transport, water and energy projects.',
                'projects' => [
                    ['title' => 'Geotechnical investigations for the 13-storey ACACIA Commercial Building', 'location' => 'Kigali', 'client' => 'Kigali Real Estates'],
                    ['title' => 'Geotechnical investigations for a five-storey building in Kimihurura', 'location' => 'Kigali', 'client' => 'FROJO Engineering'],
                    ['title' => 'Geotechnical investigations for 38 TIGO cellular sites', 'location' => 'Rwanda', 'client' => 'MER Telecom'],
                    ['title' => 'Geotechnical investigations for 44 TIGO cellular sites', 'location' => 'Rwanda', 'client' => 'Canopies International'],
                    ['title' => 'Soil analysis for the Land Husbandry and Hillside Irrigation Project (25 ha)', 'location' => 'Nyanza and Gatsibo Districts', 'client' => 'AZ'],
                    ['title' => 'Geophysical and hydrological surveys for the Rutsiro tea project', 'location' => 'Rutsiro, Rwanda', 'client' => 'Rwanda Mountain Tea'],
                    ['title' => 'Geotechnical investigations for Huye Stadium', 'location' => 'Huye, Rwanda', 'client' => 'Ngandu Consulting'],
                    ['title' => 'Geotechnical investigations for Gahanga Olympic Stadium', 'location' => 'Gahanga, Rwanda', 'client' => 'Ngandu Consulting'],
                    ['title' => 'Geotechnical investigations for a nine-storey building for Rwanda Union Mission Adventist Church', 'location' => 'Rwanda', 'client' => 'Rwanda Union Mission'],
                    ['title' => 'Geotechnical investigations for the Kayonza warehouse', 'location' => 'Kayonza, Rwanda', 'client' => 'RDF'],
                    ['title' => 'Geotechnical investigations for the Mwange hydropower plant', 'location' => 'Rwanda', 'client' => 'REGREPOWER'],
                    ['title' => 'Geotechnical investigations for an office building in Kacyiru (listed as “9 office building” in the original schedule)', 'location' => 'Kacyiru, Kigali', 'client' => 'CIECONS Ltd'],
                    ['title' => 'Geotechnical investigations for the 13-storey MIC Commercial Complex', 'location' => 'Muhima, Kigali', 'client' => 'CIECONS Ltd'],
                    ['title' => 'Geotechnical investigations for CHIC Commercial Complex (five storeys)', 'location' => 'Nyarugenge, Kigali', 'client' => 'CIECONS Ltd'],
                    ['title' => 'Geotechnical investigations for Everlasting commercial building, Phase I (five storeys)', 'location' => 'Nyarugenge, Kigali', 'client' => 'Everlasting Ltd'],
                    ['title' => 'Geotechnical investigations for Everlasting commercial building, Phase II (13 storeys)', 'location' => 'Nyarugenge, Kigali', 'client' => 'Everlasting Ltd'],
                    ['title' => 'Feasibility study geotechnical investigations for the Dar es Salaam–Isaka–Kigali–Musongati railway', 'location' => 'Regional railway corridor', 'client' => 'GIBB Africa & Africa Drilling'],
                    ['title' => 'Geotechnical investigations for three valley dams at Kageyo, Simbwa and Gacundezi', 'location' => 'Kayonza, Gatsibo and Nyagatare Districts', 'client' => 'Real Contractors; M&E Associates'],
                    ['title' => 'Geotechnical investigations for the Rusumo Falls project', 'location' => 'Rusumo, Rwanda', 'client' => 'SNC-Lavalin & BEICO'],
                    ['title' => 'Geotechnical investigations for nine valley dams', 'location' => 'Kayonza, Karongi, Rwamagana, Nyagatare and Gisagara Districts', 'client' => 'SOGIS SARL'],
                    ['title' => 'Geotechnical investigations for ENGEN Huye Petrol Station', 'location' => 'Huye, Rwanda', 'client' => 'ENGEN Rwanda'],
                ],
            ],
            [
                'id' => 'roads',
                'number' => '02',
                'title' => 'Road design & supervision',
                'intro' => 'Road studies, rehabilitation, maintenance, asphalt construction supervision and drainage works, with published route lengths where available.',
                'projects' => [
                    ['title' => 'Study and supervision of Tubungo–Shyira road rehabilitation (5.5 km)', 'location' => 'Nyabihu District, Western Province', 'client' => 'Nyabihu District'],
                    ['title' => 'Study for rehabilitation of Raba–Minazi earth road (7.5 km)', 'location' => 'Gakenke District, Northern Province', 'client' => 'Gakenke District'],
                    ['title' => 'Study for rehabilitation of Karambi–Murama road (4.5 km)', 'location' => 'Bugesera District, Eastern Province', 'client' => 'Bugesera District'],
                    ['title' => 'Study for maintenance of Rwandex–Kimihurura road (3 km)', 'location' => 'Gasabo District, Kigali', 'client' => 'City of Kigali'],
                    ['title' => 'Supervision of Kanombe–Kibaya asphalt concrete road (3.1 km)', 'location' => 'Kicukiro District, Kigali', 'client' => 'City of Kigali & RMF'],
                    ['title' => 'Supervision of 15–Ndera road (3.2 km)', 'location' => 'Gasabo District, Kigali', 'client' => 'Gasabo District'],
                    ['title' => 'Supervision of asphalt roads in Nyanza City (5.5 km)', 'location' => 'Nyanza, Southern Province', 'client' => 'Nyanza District & MININFRA'],
                    ['title' => 'Study and supervision of asphalt roads in Nyanza City (3 km)', 'location' => 'Nyanza, Southern Province', 'client' => 'Nyanza District'],
                    ['title' => 'Study for construction of Ndera–Jurwe–Gikomero road (11 km)', 'location' => 'Gasabo District, Kigali', 'client' => 'Gasabo District'],
                    ['title' => 'Study for construction of Karuruma–Bweramvura road (7 km)', 'location' => 'Gasabo District, Kigali', 'client' => 'City of Kigali & RMF'],
                    ['title' => 'Supervision of Ramiro–Mwendo–RAB/Karama road (11 km)', 'location' => 'Bugesera District', 'client' => 'Bugesera District'],
                    ['title' => 'Supervision of Nzangwa–Nyiragiseke–Mugina–Gasenyi road (12.67 km)', 'location' => 'Bugesera District', 'client' => 'Bugesera District'],
                    ['title' => 'Supervision of Rubangura–ETO Muhima and RDB–Nyirinkwaya asphalt roads', 'location' => 'Nyarugenge and Gasabo Districts, Kigali', 'client' => 'City of Kigali'],
                    ['title' => 'Supervision of Gatenga drainage (ravine) construction', 'location' => 'Kicukiro District, Kigali', 'client' => 'City of Kigali & RMF'],
                    ['title' => 'Supervision of Kicukiro drainage (ravine) construction', 'location' => 'Kicukiro District, Kigali', 'client' => 'City of Kigali & RMF'],
                    ['title' => 'Supervision of asphalt roads in Rusizi District (4.94 km)', 'location' => 'Rusizi District', 'client' => 'Rusizi District & RTDA'],
                    ['title' => 'Supervision of asphalt roads in Ruhango District', 'location' => 'Ruhango District', 'client' => 'Ruhango District & LODA'],
                    ['title' => 'Supervision of asphalt roads in Nyanza District (3.94 km)', 'location' => 'Nyanza District', 'client' => 'Nyanza District & LODA'],
                ],
            ],
            [
                'id' => 'buildings',
                'number' => '03',
                'title' => 'Building design & supervision',
                'intro' => 'Building studies, administrative and community facilities, residential construction, renovation and project feasibility work.',
                'projects' => [
                    ['title' => 'Study for construction of a training centre for people with disabilities', 'location' => 'Musanze City, Northern Province', 'client' => 'Musanze District'],
                    ['title' => 'Study for construction of Gasabo Administrative Building (five storeys)', 'location' => 'Gasabo District, Kigali', 'client' => 'Gasabo District'],
                    ['title' => 'Supervision of construction of Ngororero District Guest House', 'location' => 'Ngororero District', 'client' => 'Ngororero District'],
                    ['title' => 'Supervision of completion works for a three-storey villa for AFTS asbl', 'location' => 'Rwanda', 'client' => 'AFTS asbl'],
                    ['title' => 'Construction of a basketball court at Murehe Primary School', 'location' => 'Rulindo District', 'client' => 'Rhineland-Palatinate'],
                    ['title' => 'Renovation of archive library into a transformer housing facility', 'location' => 'Kigali, Rwanda', 'client' => 'MINECOFIN'],
                    ['title' => 'Construction of a residential house for Niyonsaba Therese Mukangango', 'location' => 'Rwanda', 'client' => 'Niyonsaba Therese Mukangango'],
                    ['title' => 'Construction of a residential house for Denervaud Concessa', 'location' => 'Rwanda', 'client' => 'Denervaud Concessa'],
                    ['title' => 'Study and supervision of a three-storey motel building', 'location' => 'Bujumbura, Burundi', 'client' => 'Habumugisha Yves Sosthène'],
                    ['title' => 'Feasibility study for Muyanza Ceramic Factory', 'location' => 'Rulindo District', 'client' => 'Rulindo District'],
                ],
            ],
        ];
    }

    public static function selectedExperience(): array
    {
        return [
            'Bugesera International Airport',
            'Kigali City Tower',
            'RAMA Building',
            'CHIC and MIC Buildings',
            'T200 Building',
            'Ubumwe Grande Hotel',
            'Eastern Province Administrative Building',
            'Kigali and Gisenyi Marine bases',
        ];
    }

    public static function referenceCount(): int
    {
        return array_sum(array_map(static fn (array $group): int => count($group['projects']), self::groups()));
    }
}
