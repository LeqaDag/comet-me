<?php

namespace App\Exports;

use App\Models\EnergyUser;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use DB;

class EnergyMaintenanceExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $data = DB::table('electricity_maintenance_calls')
            ->leftJoin('households', 'electricity_maintenance_calls.household_id', 
                'households.id')
            ->leftJoin('public_structures', 'electricity_maintenance_calls.public_structure_id', 
                'public_structures.id')
            ->join('communities', 'electricity_maintenance_calls.community_id', 'communities.id')
            ->join('regions', 'communities.region_id', '=', 'regions.id')
            ->join('sub_regions', 'communities.sub_region_id', '=', 'sub_regions.id')
            ->join('maintenance_types', 'electricity_maintenance_calls.maintenance_type_id', 
                '=', 'maintenance_types.id')
            ->join('maintenance_electricity_actions', 'electricity_maintenance_calls.maintenance_electricity_action_id', 
                '=', 'maintenance_electricity_actions.id')
            ->join('maintenance_statuses', 'electricity_maintenance_calls.maintenance_status_id', 
                '=', 'maintenance_statuses.id')
            ->join('users', 'electricity_maintenance_calls.user_id', '=', 'users.id')
            ->select('households.english_name as english_name', 
                'public_structures.english_name as public_name', 
                'communities.english_name as community_name',
                'regions.english_name as region', 'sub_regions.english_name as sub_region',
                'users.name as user_name',
                'maintenance_electricity_actions.maintenance_action_electricity', 
                'maintenance_electricity_actions.maintenance_action_electricity_english',
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