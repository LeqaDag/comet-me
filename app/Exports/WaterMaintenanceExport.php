<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use DB;

class WaterMaintenanceExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $data = DB::table('h2o_maintenance_calls')
            ->leftJoin('households', 'h2o_maintenance_calls.household_id', 'households.id')
            ->leftJoin('public_structures', 'h2o_maintenance_calls.public_structure_id', 
                'public_structures.id')
            ->join('communities', 'h2o_maintenance_calls.community_id', 'communities.id')
            ->join('maintenance_types', 'h2o_maintenance_calls.maintenance_type_id', 
                '=', 'maintenance_types.id')
            ->join('maintenance_h2o_actions', 'h2o_maintenance_calls.maintenance_h2o_action_id', 
                '=', 'maintenance_h2o_actions.id')
            ->join('maintenance_statuses', 'h2o_maintenance_calls.maintenance_status_id', 
                '=', 'maintenance_statuses.id')
            ->join('users', 'h2o_maintenance_calls.user_id', '=', 'users.id')
            ->join('regions', 'communities.region_id', '=', 'regions.id')
            ->join('sub_regions', 'communities.sub_region_id', '=', 'sub_regions.id')
            ->select('households.english_name as english_name', 
                'public_structures.english_name as public_name', 
                'communities.english_name as community_name',
                'regions.english_name as region', 'sub_regions.english_name as sub_region',
                'users.name as user_name', 
                'maintenance_h2o_actions.maintenance_action_h2o',
                'maintenance_h2o_actions.maintenance_action_h2o_english',
                'maintenance_statuses.name', 'maintenance_types.type', 
                'date_of_call', 'date_completed')
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
        return ["Household Name", "Public Structure", "Community", "Region", "Sub Region", 
            "Recipient", "Action in English", "Action in Arabic", "Status", "Type", "Call Date",
            "Completed Date"];
    }
}