<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use DB;

class EnergyUsers implements FromCollection, WithHeadings, WithTitle
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
        $query = DB::table('all_energy_meters')
            ->join('communities', 'all_energy_meters.community_id', '=', 'communities.id')
            ->join('regions', 'communities.region_id', '=', 'regions.id')
            ->join('sub_regions', 'communities.sub_region_id', '=', 'sub_regions.id')
            ->join('households', 'all_energy_meters.household_id', '=', 'households.id')
            ->join('meter_cases', 'all_energy_meters.meter_case_id', '=', 'meter_cases.id')
            ->join('energy_systems', 'all_energy_meters.energy_system_id', '=', 'energy_systems.id')
            ->join('energy_system_types', 'all_energy_meters.energy_system_type_id', 
                '=', 'energy_system_types.id')
            ->select('households.english_name as english_name', 
                'communities.english_name as community_name',
                'regions.english_name as region', 'sub_regions.english_name as sub_region',
                'energy_systems.name as energy_name', 'energy_system_types.name as energy_type_name',
                'households.number_of_male', 'households.number_of_female', 
                'households.number_of_adults', 'households.number_of_children', 'households.phone_number',
                'all_energy_meters.meter_number', 'all_energy_meters.daily_limit', 
                'all_energy_meters.installation_date', 'meter_cases.meter_case_name_english');

               // DB::raw('YEAR(all_energy_meters.installation_date) year'));


        if($this->request->misc) {

            if($this->request->misc == "misc") {

                $query->where("all_energy_meters.misc", 1);
            } else if($this->request->misc == "new") {

                $query->where("all_energy_meters.misc", 0);
            } else if($this->request->misc == "maintenance") {

                $query->where("all_energy_meters.misc", 2);
            }
        }

        if($this->request->date_from) {
            $query->where("all_energy_meters.installation_date", ">=", $this->request->date_from);
        }

        if($this->request->date_to) {
            $query->where("all_energy_meters.installation_date", "<=", $this->request->date_to);
        }

        //dd($query->count());
        return $query->get();
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function headings(): array
    {
        return ["Meter User", "Community", "Region", "Sub Region", 
            "Energy System", "Energy System Type", "Number of male", "Number of Female", 
            "Number of adults", "Number of children", "Phone number", "Meter Number", 
            "Daily Limit",  "Installation Date", "Meter Case"];
    }

    public function title(): string
    {
        return 'Energy Users';
    }
}