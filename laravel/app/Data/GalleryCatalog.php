<?php

namespace App\Data;

final class GalleryCatalog
{
    public static function categories(): array
    {
        return [
            ['id' => 'all', 'label' => 'All photographs'],
            ['id' => 'design', 'label' => 'Design & studies'],
            ['id' => 'investigation', 'label' => 'Site investigations'],
            ['id' => 'roads', 'label' => 'Roads & transport'],
            ['id' => 'laboratory', 'label' => 'Laboratory & testing'],
            ['id' => 'quarry', 'label' => 'Quarry & materials'],
            ['id' => 'buildings', 'label' => 'Buildings & facilities'],
            ['id' => 'office', 'label' => 'Office'],
        ];
    }

    public static function all(): array
    {
        $photos = [
            ['officeone.jpg', 'GECO Africa office', 'office', 'Our office entrance in Kigali, Rwanda.', 'OUR OFFICE', 'OUR OFFICE'],
            ['bg_7.jpg', 'Building project concept', 'design', 'Architectural rendering of a multi-storey building and its grounds.', 'Geotechnical engineering', 'Geotechnical engineering'],
            ['bg_8.jpg', 'Building concept and site layout', 'design', 'Rendered view of a multi-storey building within a landscaped project site.', 'Geotechnical engineering', 'Geotechnical engineering'],
            ['bg_9.jpg', 'Institutional building concept', 'design', 'Three-dimensional building rendering with a campus-style setting.', 'Geotechnical engineering', 'Geotechnical engineering'],
            ['bg_3.jpg', 'Musanze children’s centre study', 'design', 'Project rendering published for the proposed Centre de Formation et d’Encadrement des Enfants de la Rue in Musanze District.', 'STUDY FOR CONSTRUCTION OF « CENTRE DE FORMATION ET D’ENCADREMENT DES ENFANTS DE LA RUE » IN MUSANZE DISTRICT', 'STUDY'],
            ['bg_2.jpg', 'Muyanza ceramic factory study', 'design', 'Architectural rendering associated with the study for a ceramic factory in Rulindo District.', 'STUDY FOR CONSTRUCTION OF MUYANZA CERAMIC FACTORY, RULINDO DISTRICT', 'STUDY'],
            ['firstproject.jpg', 'Road project supervision', 'roads', 'Completed road frontage photographed during road project supervision in Rwanda.', 'GEOTECHNICAL INVESTIGATIONS', 'SUPERVISION'],
            ['bg_1.jpg', 'Construction site photograph', 'buildings', 'Building under construction at a project site.', 'Construction site', 'Construction'],
            ['bg_4.jpg', 'Ruhango asphalt road works', 'roads', 'Road construction supervision in Ruhango District.', 'SUPERVISION OF CONSTRUCTION WORKS OF ASPHALT ROADS IN RUHANGO DISTRICT', 'SUPERVISION'],
            ['bg_12.jpg', 'Field drilling project site', 'investigation', 'Drilling work at a geotechnical investigation site.', 'Drilling', 'Drilling'],
            ['bg_13.jpg', 'Drilling rig in the field', 'investigation', 'Drilling equipment and crew at a site investigation location.', 'Drilling', 'Drilling'],
            ['bg_14.jpg', 'Geotechnical drilling works', 'investigation', 'Drilling rig set up beside a building during field investigations.', 'Drilling', 'Drilling'],
            ['bg_15.jpg', 'Drilling crew at work', 'investigation', 'Field team and drilling equipment photographed during site work.', 'Drilling', 'Drilling'],
            ['bg_16.jpg', 'Piezometer installation', 'investigation', 'Installation work photographed for a piezometer project entry.', 'Piezometer', 'Piezometer'],
            ['bg_17.jpg', 'Soil sampling in the field', 'investigation', 'Soil sampling activity at an outdoor investigation site.', 'Sampling', 'Sampling'],
            ['bg_18.jpg', 'Laboratory testing in progress', 'laboratory', 'GECO Africa laboratory staff working with testing equipment.', 'GECO Africa Ltd', 'GECO Africa Ltd'],
            ['bg_19.jpg', 'Laboratory soil test equipment', 'laboratory', 'Close view of soil testing equipment in GECO Africa’s laboratory.', 'GECO Africa Ltd', 'GECO Africa Ltd'],
            ['bg_20.jpg', 'Technician preparing a test', 'laboratory', 'Laboratory technician preparing a sample using geotechnical testing equipment.', 'GECO Africa Ltd', 'GECO Africa Ltd'],
            ['bg_21.jpg', 'Soil testing apparatus', 'laboratory', 'Geotechnical laboratory apparatus prepared for material testing.', 'GECO Africa Ltd', 'GECO Africa Ltd'],
            ['bg_22.jpg', 'Laboratory equipment demonstration', 'laboratory', 'GECO Africa technician demonstrating laboratory equipment.', 'GECO Africa Ltd', 'GECO Africa Ltd'],
            ['bg_23.jpg', 'Geotechnical testing equipment', 'laboratory', 'Soil mechanics testing equipment in a laboratory setting.', 'Geotechnical Engineering', 'Geotechnical Engineering'],
            ['bg_24.jpg', 'Laboratory sample preparation', 'laboratory', 'Geotechnical laboratory equipment and prepared samples.', 'Geotechnical Engineering', 'Geotechnical Engineering'],
            ['bg_25.jpg', 'Laboratory testing', 'laboratory', 'Technician operating geotechnical testing equipment.', 'Geotechnical Engineering', 'Geotechnical Engineering'],
            ['bg_26.jpg', 'Road alignment survey', 'roads', 'Survey team working along a road alignment.', 'Study road', 'Study road'],
            ['bg_27.jpg', 'Road survey and setting out', 'roads', 'Road construction survey work on a hillside route.', 'Study road', 'Study road'],
            ['bg_28.jpg', 'Quarry and aggregate materials', 'quarry', 'Crushed stone and rock materials at a quarry site.', 'Crusher', 'Quarry'],
            ['bg_29.jpg', 'Crushed stone stockpile', 'quarry', 'Rock aggregate material photographed at a quarry.', 'Crusher', 'Quarry'],
            ['bg_30.jpg', 'Quarry materials inspection', 'quarry', 'Stone aggregate at a quarry and materials site.', 'Crusher', 'Quarry'],
            ['bg_31.jpg', 'Quarry site review', 'quarry', 'Quarry inspection in progress at a rocky hillside site.', 'Crusher', 'Quarry'],
            ['bg_32.jpg', 'Laboratory strength testing equipment', 'laboratory', 'Geotechnical laboratory testing equipment arranged for material tests.', 'Laboratory', 'Laboratory'],
            ['bg_33.jpg', 'Sieve analysis equipment', 'laboratory', 'Laboratory sieve equipment and soil sample preparation.', 'Laboratory', 'Laboratory'],
            ['bg_34.jpg', 'GECO laboratory workspace', 'laboratory', 'Laboratory workspace with soil mechanics testing equipment.', 'Laboratory', 'Laboratory'],
            ['bg_35.jpg', 'Soil testing laboratory', 'laboratory', 'Interior view of GECO Africa’s soil testing laboratory.', 'Laboratory', 'Laboratory'],
            ['bg_36.jpg', 'Rusizi asphalt road supervision', 'roads', 'Road construction and supervision views from Rusizi District.', 'SUPERVISION OF CONSTRUCTION WORKS OF ASPHALT ROADS IN RUSIZI DISTRICT', 'RUSIZI DISTRICT'],
            ['bg_37.jpg', 'Nyanza asphalt road supervision', 'roads', 'Road construction and completed surface views from Nyanza District.', 'SUPERVISION OF CONSTRUCTION OF ASPHALT ROADS IN NYANZA DISTRICT', 'NYANZA DISTRICT'],
            ['bg_38.jpg', 'Ruhango asphalt road supervision', 'roads', 'Road construction and completed road views from Ruhango District.', 'SUPERVISION OF CONSTRUCTION WORKS OF ASPHALT ROADS IN RUHANGO DISTRICT', 'RUHANGO DISTRICT'],
            ['bg_39.jpg', 'La Croix du Sud Hospital road', 'roads', 'Road supervision project at RDB–La Croix du Sud Hospital.', 'SUPERVISION OF RDB-LA CROIX DU SUD HOSPITAL ASPHALT ROAD', 'SUPERVISION OF RDB-LA CROIX DU SUD HOSPITAL ASPHALT ROAD'],
            ['bg_40.jpg', 'Camp Kanombe–Kibaya asphalt road', 'roads', 'Asphalt road construction supervision on the Camp Kanombe–Kibaya road.', 'SUPERVISION OF CAMP KANOMBE-KIBAYA ASPHALT ROAD', 'CAMP KANOMBE-KIBAYA ASPHALT ROAD'],
            ['bg_41.jpg', 'Muhima–Rubangura House road', 'roads', 'Road construction works between Eto Muhima and Rubangura House.', 'SUPERVISION OF CONSTRUCTION WORKS OF ASPHALT ROAD ETO MUHIMA-RUBANGURA HOUSE', 'ETO MUHIMA-RUBANGURA HOUSE'],
            ['bg_42.jpg', 'MINECOFIN archive library renovation', 'buildings', 'Renovation work for the MINECOFIN archive library into a transformer housing facility in Kigali.', 'RENOVATION WORKS OF MINECOFIN ARCHIVE LIBRARY INTO A TRANSFORMER HOUSING FACILITY', 'KIGALI'],
            ['bg_43.jpg', 'Land Husbandry soil analysis', 'investigation', 'Soil sampling for the Land Husbandry, Water Harvesting and Hillsides Irrigation project in Nyanza and Gatsibo.', 'SOIL ANALYSIS FOR LAND HUSBANDRY, WATER HARVESTING AND HILLSIDES IRRIGATION PROJECT-RWANDA (LWH PROJECT) FOR NYANZA (500 Hectares) AND GATSIBO SITES(100 Hectares)', 'RWANDA'],
            ['map.jpg', 'Nyanza Hospital–Nyamagana road map', 'roads', 'Overlay map for the Nyanza Hospital–Nyamagana road reference.', 'ROAD NYANZA HOSPITAL - NYAMAGANA OVERLAYED MAP', 'ROAD NYANZA HOSPITAL - NYAMAGANA OVERLAYED MAP'],
        ];

        $labels = collect(self::categories())->keyBy('id');

        return array_map(static function (array $photo, int $index) use ($labels): array {
            [$file, $title, $category, $alt, $legacyCategory, $legacyTitle] = $photo;

            return [
                'number' => str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT),
                'file' => $file,
                'image' => 'images/gallery/' . $file,
                'title' => $title,
                'category' => $category,
                'categoryLabel' => $labels->get($category)['label'],
                'alt' => $alt,
                'caption' => $alt,
                'legacyCategory' => $legacyCategory,
                'legacyTitle' => $legacyTitle,
            ];
        }, $photos, array_keys($photos));
    }
}
