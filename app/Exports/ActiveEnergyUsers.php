<?php

namespace App\Exports;

use App\Models\AllEnergyMeter;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB;

class ActiveEnergyUsers implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize
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
        $query = DB::table('households')
            ->join('communities', 'households.community_id', '=', 'communities.id')
            ->join('regions', 'communities.region_id', '=', 'regions.id')
            ->join('sub_regions', 'communities.sub_region_id', '=', 'sub_regions.id')
            ->leftJoin('all_energy_meters', 'all_energy_meters.household_id', '=', 'households.id')
            ->leftJoin('household_meters', 'all_energy_meters.id', 
                '=', 'household_meters.energy_user_id')
            ->leftJoin('meter_cases', 'all_energy_meters.meter_case_id', '=', 'meter_cases.id')
            ->leftJoin('energy_systems', 'all_energy_meters.energy_system_id', '=', 'energy_systems.id')
            ->leftJoin('energy_system_types', 'all_energy_meters.energy_system_type_id', 
                '=', 'energy_system_types.id')
            ->where('all_energy_meters.household_id', '!=', 0)
            ->where('all_energy_meters.meter_case_id', 1)
            ->where('households.energy_system_status', "Served")
            ->select('households.english_name as english_name', 
                'households.english_name as arabic_name', 
                'communities.english_name as community_name',
                'regions.english_name as region', 'sub_regions.english_name as sub_region',
                'energy_systems.name as energy_name', 'energy_system_types.name as energy_type_name',
                'households.number_of_male', 'households.number_of_female', 
                'households.number_of_adults', 'households.number_of_children', 'households.phone_number',
                'all_energy_meters.meter_number', 'all_energy_meters.daily_limit', 
                'all_energy_meters.installation_date', 'meter_cases.meter_case_name_english');

        if($this->request->region) {
            $query->where("regions.english_name", $this->request->region);
        } 

        if($this->request->community) {
            $query->where("communities.english_name", $this->request->community);
        } 

        return $query->get();
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function headings(): array
    {
        return ["User", "Arabic Name", "Community", "Region", "Sub Region", 
            "Energy System", "Energy System Type", "Number of male", "Number of Female", 
            "Number of adults", "Number of children", "Phone number", "Meter Number", 
            "Daily Limit",  "Installation Date", "Meter Case"];
    }

    public function title(): string
    {
        return 'Energy Users';
    }
}