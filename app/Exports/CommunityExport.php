<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use DB;

class CommunityExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $data = DB::table('communities')
            ->join('regions', 'communities.region_id', '=', 'regions.id')
            ->join('sub_regions', 'communities.sub_region_id', '=', 'sub_regions.id')
            ->leftJoin('sub_sub_regions', 'communities.sub_sub_region_id', '=', 'sub_sub_regions.id')
            ->join('community_statuses', 'communities.community_status_id', 
                '=', 'community_statuses.id')
            ->select('communities.english_name as english_name', 
                'communities.arabic_name as arabic_name',
                'regions.english_name as name', 'sub_regions.english_name as subname',
                'sub_sub_regions.english_name as sub_sub_name',
                'communities.number_of_people as number_of_people',
                'number_of_households', 'number_of_compound',
                'community_statuses.name as status_name',
                'communities.energy_service', 'communities.energy_service_beginning_year',
                'communities.water_service', 'communities.water_service_beginning_year',
                'communities.internet_service', 'communities.internet_service_beginning_year')
            ->get();

        return $data;
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function headings(): array
    {
        return ["English Name", "Arabic Name", "Region", "Sub Region", "Sub Sub Region",
            "Number of People", "Number of Households", "Number of Compounds", 
            "Status", "Energy Service", "Energy Service Year",
            "Water Service", "Water Service Year", "Internet Service",
            "Internet Service Year"];
    }
}