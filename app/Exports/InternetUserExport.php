<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use DB;

class InternetUserExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $data = DB::table('internet_users')
            ->join('communities', 'internet_users.community_id', '=', 'communities.id')
            ->join('regions', 'communities.region_id', '=', 'regions.id')
            ->join('sub_regions', 'communities.sub_region_id', '=', 'sub_regions.id')
            ->join('households', 'internet_users.household_id', '=', 'households.id')
            ->join('internet_statuses', 'internet_users.internet_status_id', '=', 'internet_statuses.id')
            ->select('households.english_name as english_name', 
                'communities.english_name as community_name',
                'regions.english_name as region', 'sub_regions.english_name as sub_region',
                'internet_users.start_date', 'internet_statuses.name',
                'internet_users.number_of_people', 'internet_users.number_of_contract',)
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
        return ["Internet Holder", "Community", "Region", "Sub Region", 
            "Start Date", "Internet Status", "Number of people", "Number of Contracts"];
    }
}