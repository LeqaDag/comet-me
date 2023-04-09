<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use DB;

class WaterUserExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $data = DB::table('h2o_users')
            ->LeftJoin('grid_users', 'h2o_users.household_id', '=', 'grid_users.household_id')
            ->join('households', 'h2o_users.household_id', 'households.id')
            ->join('communities', 'h2o_users.community_id', 'communities.id')
            ->join('regions', 'communities.region_id', '=', 'regions.id')
            ->join('sub_regions', 'communities.sub_region_id', '=', 'sub_regions.id')
            ->join('h2o_statuses', 'h2o_users.h2o_status_id', '=', 'h2o_statuses.id')
            ->where('h2o_statuses.status', 'Used')
            ->select('households.english_name as english_name', 
                'communities.english_name as community_name',
                'regions.english_name as region', 'sub_regions.english_name as sub_region',
                'h2o_users.h2o_request_date', 'h2o_users.installation_year',  
                'h2o_users.number_of_h20', 'h2o_users.number_of_bsf', 
                'grid_users.grid_access', 'grid_users.grid_integration_large', 
                'grid_users.large_date', 'grid_users.grid_integration_small', 
                'grid_users.small_date', 'grid_users.is_delivery', 
                'grid_users.is_paid', 'grid_users.is_complete')
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
        return ["Water Holder", "Community", "Region", "Sub Region", 
            "H2O Request Date", "H2O Installation Year", "Number of H2O", 
            "Number of BSF", "Grid Access", "Grid Large", "Date (Grid Large)",
            "Grid Small", "Date (Grid Small)", "Delivery", "Paid", "Complete"];
    }
}