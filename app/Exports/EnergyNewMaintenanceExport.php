<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB;

class EnergyNewMaintenanceExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $data = DB::table('new_electricity_maintenance_calls')
            ->leftJoin('households', 'new_electricity_maintenance_calls.household_id', 
                'households.id')
            ->leftJoin('public_structures', 'new_electricity_maintenance_calls.public_structure_id', 
                'public_structures.id')
            ->join('communities', 'new_electricity_maintenance_calls.community_id', 'communities.id')
            ->join('maintenance_types', 'new_electricity_maintenance_calls.maintenance_type_id', 
                '=', 'maintenance_types.id')
            ->join('maintenance_new_electricity_actions', 
                'new_electricity_maintenance_calls.maintenance_new_electricity_action_id', '=', 
                'maintenance_new_electricity_actions.id')
            ->join('maintenance_statuses', 'new_electricity_maintenance_calls.maintenance_status_id', 
                '=', 'maintenance_statuses.id')
            ->join('users', 'new_electricity_maintenance_calls.user_id', '=', 'users.id')
            ->join('regions', 'communities.region_id', '=', 'regions.id')
            ->join('sub_regions', 'communities.sub_region_id', '=', 'sub_regions.id')
            ->where('new_electricity_maintenance_calls.is_archived', 0)
            ->select('households.english_name as english_name', 
                'public_structures.english_name as public_name', 
                'communities.english_name as community_name',
                'regions.english_name as region', 'sub_regions.english_name as sub_region',
                'users.name as user_name',
                'maintenance_new_electricity_actions.maintenance_action_new_electricity', 
                'maintenance_new_electricity_actions.maintenance_action_new_electricity_english',
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
            "Recipient", "Action in Arabic", "Action in English", "Status", "Type", "Call Date",
            "Completed Date"];
    }
}