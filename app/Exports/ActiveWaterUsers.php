<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use DB;

class ActiveWaterUsers implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize
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
        $data = DB::table('households')
            ->join('communities', 'households.community_id', 'communities.id')
            ->join('regions', 'communities.region_id', 'regions.id')
            ->join('sub_regions', 'communities.sub_region_id', 'sub_regions.id')
            ->leftJoin('h2o_users', 'h2o_users.household_id', '=', 'households.id')
            ->leftJoin('grid_users', 'h2o_users.household_id', '=', 'grid_users.household_id')
            ->leftJoin('water_network_users', 'households.id', 'water_network_users.household_id')
            ->leftJoin('h2o_user_donors', 'h2o_users.id', 'h2o_user_donors.h2o_user_id')
            ->where('households.water_system_status', 'Served')
            ->where('households.is_archived', 0)
            ->select('households.english_name as english_name', 
                'households.english_name as arabic_name', 
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

        
        if($this->request->region) {
            $data->where("regions.english_name", $this->request->region);
        }
        if($this->request->community) {
            $data->where("communities.english_name", $this->request->community);
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
        return ["Water Holder", "Arabic Name", "Community", "Region", "Sub Region", "Grid Access",
            "H2O Request Date", "H2O Installation Year", "H2O Installation Date",
            "Number of H2O",  "Number of BSF", "Grid Request Date", 
            "Number of Grid Integration Large", 
            "Date (Grid Large)", "Number of Grid Integration Small", "Date (Grid Small)", 
            "Delivery", "Paid", "Complete"];
    }

    public function title(): string
    {
        return 'Water Users';
    }
}