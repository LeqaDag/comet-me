<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use DB;

class EnergyUserExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $data = DB::table('energy_users')
            ->join('communities', 'energy_users.community_id', '=', 'communities.id')
            ->join('regions', 'communities.region_id', '=', 'regions.id')
            ->join('sub_regions', 'communities.sub_region_id', '=', 'sub_regions.id')
            ->join('households', 'energy_users.household_id', '=', 'households.id')
            ->join('energy_systems', 'energy_users.energy_system_id', '=', 'energy_systems.id')
            ->join('energy_system_types', 'energy_users.energy_system_type_id', '=', 'energy_system_types.id')
            ->join('meter_cases', 'energy_users.meter_case_id', '=', 'meter_cases.id')
            ->join('vendors', 'energy_users.vendor_username_id', '=', 'vendors.id')
            ->where('energy_users.meter_active', 'Yes')
            ->select('households.english_name as english_name', 
                'communities.english_name as community_name',
                'regions.english_name as region', 'sub_regions.english_name as sub_region',
                'energy_users.meter_number', 'energy_users.meter_active',
                'meter_cases.meter_case_name_english as meter_case',
                'energy_systems.name as energy_name', 
                'energy_system_types.name as energy_type_name',
                'energy_users.daily_limit', 'energy_users.installation_date',
                'vendors.english_name as vendor_name')
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
        return ["Meter Holder", "Community", "Region", "Sub Region", 
            "Meter Number", "Meter Active", "Meter Case", "Energy System", 
            "Energy System Type", "Daily Limit", "Installation Date", "Vendor"];
    }
}