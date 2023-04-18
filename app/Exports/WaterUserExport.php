<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use DB;

class WaterUserExport implements FromCollection, WithHeadings
{
    protected $request;

    function __construct($request) {

        $this->request = $request;
    }

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
            ->leftJoin('community_donors', 'community_donors.community_id', '=', 'communities.id')
            ->leftJoin('donors', 'community_donors.donor_id', 'donors.id')
            ->where('h2o_statuses.status', 'Used')
            ->where('community_donors.service_id', 2)
            ->select('households.english_name as english_name', 
                'communities.english_name as community_name',
                'regions.english_name as region', 'sub_regions.english_name as sub_region',
                'grid_users.grid_access',
                'h2o_users.h2o_request_date', 'h2o_users.installation_year',  
                'h2o_users.h2o_installation_date',
                'h2o_users.number_of_h20', 'h2o_users.number_of_bsf', 'grid_users.request_date', 
                'grid_users.grid_integration_large', 
                'grid_users.large_date', 'grid_users.grid_integration_small', 
                'grid_users.small_date', 'grid_users.is_delivery', 
                'grid_users.is_paid', 'grid_users.is_complete');

        if($this->request->community) {
            $data->where("communities.english_name", $this->request->community);
        } 
        if($this->request->donor) {
            $data->where("community_donors.donor_id", $this->request->donor);
        }
        if($this->request->h2o_request_date) {
            $data->where("h2o_users.h2o_request_date", ">=", $this->request->h2o_request_date);
        }
        if($this->request->h2o_installation_date) {
            $data->where("h2o_users.h2o_installation_date", ">=", $this->request->h2o_installation_date);
        }

        return $data->get();
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function headings(): array
    {
        return ["Water Holder", "Community", "Region", "Sub Region", "Grid Access",
            "H2O Request Date", "H2O Installation Year", "H2O Installation Date",
            "Number of H2O",  "Number of BSF", "Grid Request Date", 
            "Number of Grid Integration Large", 
            "Date (Grid Large)", "Number of Grid Integration Small", "Date (Grid Small)", 
            "Delivery", "Paid", "Complete"];
    }
}