<?php

namespace App\Data;

class ActivitiesCatalog
{
    /** @return array<int, array{id: int, title: string, client: string, date: string, duration: string, category: string, location?: string}> */
    public static function all(): array
    {
        return [
            ['id' => 24, 'title' => 'Geotechnical investigations for the proposed construction of a residential complex (4 apartments)', 'client' => 'Harerimana Jean Bosco', 'date' => 'Feb 2022', 'duration' => 'February 2022', 'category' => 'Geotechnical investigation', 'location' => 'Murambi Village, Ruhango Cell, Gisozi Sector, Gasabo District'],
            ['id' => 25, 'title' => 'Geotechnical investigations for the construction project of a warehouse', 'client' => 'YDH AGRO Ltd', 'date' => 'Feb 2022', 'duration' => 'February 2022', 'category' => 'Geotechnical investigation'],
            ['id' => 26, 'title' => 'Geotechnical investigations for the proposed construction of a warehouse and G+2 showroom building', 'client' => 'JMG Construction and General Trading Co Ltd', 'date' => 'Feb 2022', 'duration' => 'February 2022', 'category' => 'Geotechnical investigation'],
            ['id' => 27, 'title' => 'Geotechnical investigations for the proposed construction of a petrol station at Gisozi', 'client' => 'COOPERATIVE ADARWA', 'date' => 'Feb 2022', 'duration' => 'February 2022', 'category' => 'Geotechnical investigation'],
            ['id' => 22, 'title' => 'Topographic survey of abattoir, feed mill, feed lot and amenities (Phase II: 200 hectares)', 'client' => 'GAKO MEAT COMPANY Ltd', 'date' => 'Jan 2022', 'duration' => 'January 2022', 'category' => 'Topographic survey'],
            ['id' => 23, 'title' => 'Structural audit of a mixed-use building', 'client' => 'WITE FORT PLAZA Ltd', 'date' => 'Jan 2022', 'duration' => 'January 2022', 'category' => 'Building assessment'],
            ['id' => 21, 'title' => 'Geotechnical investigations for the project of construction of residential buildings', 'client' => 'Nyandwi Jean Claude', 'date' => 'Dec 2021', 'duration' => 'December 2021', 'category' => 'Geotechnical investigation'],
            ['id' => 28, 'title' => 'Geotechnical investigations for the construction of Rukopfo–Cyarubariro–Rwisi–Simbi–Karambo road in Karongi District', 'client' => 'DEC Company Ltd', 'date' => 'Dec 2021', 'duration' => 'December 2021', 'category' => 'Road investigation'],
            ['id' => 29, 'title' => 'Geotechnical investigations for the proposed construction of Africa New Life Bible Church', 'client' => 'ANLM', 'date' => 'Dec 2021', 'duration' => 'December 2021', 'category' => 'Geotechnical investigation'],
            ['id' => 20, 'title' => 'Geotechnical investigations for housing upgrading in Mpazi unplanned settlement', 'client' => 'Skat Consulting Rwanda Ltd', 'date' => 'Nov 2021', 'duration' => 'November 2021', 'category' => 'Geotechnical investigation'],
            ['id' => 30, 'title' => 'Construction and extension of Kansi Drinking Water Supply System', 'client' => 'World Vision Rwanda / Gisagara District', 'date' => 'Nov 2021', 'duration' => 'November 2021', 'category' => 'Water supply'],
            ['id' => 19, 'title' => 'Geotechnical investigations for the feasibility study, detailed design and supervision of Sake Water Supply System (Phase I)', 'client' => 'WASAC Ltd', 'date' => 'Oct 2021', 'duration' => 'October 2021', 'category' => 'Water supply'],
            ['id' => 17, 'title' => 'Geotechnical investigations for the proposed construction of an apartment building', 'client' => 'MWEZE TCHIZA Claude', 'date' => 'Sep 2021', 'duration' => 'September 2021', 'category' => 'Geotechnical investigation'],
            ['id' => 13, 'title' => 'Geotechnical investigations for the feasibility study, detailed design and preparation of ESIA for upgrading the disability playground project in Gicumbi District', 'client' => 'Gicumbi District', 'date' => 'Aug 2021', 'duration' => 'August 2021', 'category' => 'Geotechnical investigation'],
            ['id' => 14, 'title' => 'Geotechnical investigations for the proposed construction of a meeting hall at Kayenzi, Rwamagana District', 'client' => 'CHANOINESSES St AUGUSTIN', 'date' => 'Aug 2021', 'duration' => 'August 2021', 'category' => 'Geotechnical investigation'],
            ['id' => 15, 'title' => 'Geotechnical investigations for the project of construction of Minago drinking water supply system (120.8 km) in Gatsibo District', 'client' => 'Gatsibo District', 'date' => 'Aug 2021', 'duration' => 'August 2021', 'category' => 'Water supply'],
            ['id' => 16, 'title' => 'Geotechnical investigations for the feasibility and technical studies of Mukarange Sector administrative office building construction project', 'client' => 'Kayonza District', 'date' => 'Aug 2021', 'duration' => 'August 2021', 'category' => 'Geotechnical investigation'],
            ['id' => 18, 'title' => 'Valuation of executed works for the project of construction of Don Bosco Complex in Nyarutarama, Gasabo District, City of Kigali', 'client' => 'Association des Anciens de Don Bosco', 'date' => 'Aug 2021', 'duration' => 'August 2021', 'category' => 'Project valuation'],
            ['id' => 12, 'title' => 'Geotechnical investigations for the proposed construction of the Center of Excellence in Neurology at CARAES Ndera Hospital', 'client' => 'Rwanda Housing Authority', 'date' => 'Jul 2021', 'duration' => 'July 2021', 'category' => 'Geotechnical investigation'],
            ['id' => 1, 'title' => 'Core drilling at reservoir sites in Ngororero District', 'client' => 'WASAC Ltd', 'date' => 'Jun 2021', 'duration' => 'June to July 2021', 'category' => 'Core drilling'],
            ['id' => 11, 'title' => 'Detailed topographic survey for the proposed construction of abattoir, feed mill, feed lot and amenities', 'client' => 'GAKO MEAT COMPANY Ltd', 'date' => 'Jun 2021', 'duration' => 'May to June 2021', 'category' => 'Topographic survey'],
        ];
    }
}
