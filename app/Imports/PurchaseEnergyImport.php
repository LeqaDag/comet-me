<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Community;
use App\Models\PublicStructure;
use App\Models\AllEnergyMeter;
use App\Models\Household;
use Carbon\Carbon;
use Excel;
use DB;

class PurchaseEnergyImport implements ToModel, WithHeadingRow
{ 

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $query = DB::table('all_energy_meters')
            ->join('communities', 'all_energy_meters.community_id', 'communities.id')
            ->join('regions', 'communities.region_id', 'regions.id')
            ->LeftJoin('public_structures', 'all_energy_meters.public_structure_id', 
                'public_structures.id')
            ->leftJoin('households', 'all_energy_meters.household_id', 'households.id')
            ->leftJoin('meter_cases', 'all_energy_meters.meter_case_id', 'meter_cases.id')
            ->leftJoin('energy_systems', 'all_energy_meters.energy_system_id', 'energy_systems.id')
            ->leftJoin('energy_system_types', 'all_energy_meters.energy_system_type_id', 
                'energy_system_types.id')
            ->where('all_energy_meters.is_archived', 0)
            ->where('all_energy_meters.meter_number', $row['meter_no'])
            ->select([
                DB::raw('IFNULL(households.english_name, public_structures.english_name) 
                    as exported_value'),
                DB::raw('IFNULL(households.arabic_name, public_structures.arabic_name) 
                    as exported_value_arabic'), 
                'all_energy_meters.is_main',
                'communities.english_name as community_name',
                'regions.english_name as region',
                'meter_cases.meter_case_name_english', 
                'energy_systems.name as energy_name', 'energy_system_types.name as energy_type_name', 
                'all_energy_meters.meter_number', 'all_energy_meters.daily_limit', 'all_energy_meters.installation_date',
                ]
            );


        return $query;
    }
}
