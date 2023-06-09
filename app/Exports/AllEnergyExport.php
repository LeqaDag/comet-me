<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

use DB;

class AllEnergyExport implements WithMultipleSheets 
{
    use Exportable;

    protected $request;

    function __construct($request) {
        $this->request = $request;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [
            new EnergyUsers($this->request),
            new HouseholdMeters($this->request),
            new PublicMeters($this->request)
        ];

        return $sheets;
    }

    // /**
    // * @return \Illuminate\Support\Collection
    // */
    // public function collection() 
    // {
    //     $query = DB::table('all_energy_meters')
    //         ->join('communities', 'all_energy_meters.community_id', '=', 'communities.id')
    //         ->join('regions', 'communities.region_id', '=', 'regions.id')
    //         ->join('sub_regions', 'communities.sub_region_id', '=', 'sub_regions.id')
    //         ->leftJoin('household_meters', 'all_energy_meters.id', 
    //             '=', 'household_meters.energy_user_id')
    //         ->leftJoin('households', 'all_energy_meters.household_id', '=', 'households.id')
            
    //        // ->leftJoin('household_meters', 'household_meters.household_id', '=', 'households.id')
    //         ->leftJoin('public_structures', 'all_energy_meters.public_structure_id', 
    //             '=', 'public_structures.id')
    //         ->leftJoin('meter_cases', 'all_energy_meters.meter_case_id', '=', 'meter_cases.id')
    //         ->leftJoin('energy_systems', 'all_energy_meters.energy_system_id', '=', 'energy_systems.id')
    //         ->leftJoin('energy_system_types', 'all_energy_meters.energy_system_type_id', 
    //             '=', 'energy_system_types.id')
    //         ->select('households.english_name as english_name', 
    //             'household_meters.household_name as household_name', 
    //             'public_structures.english_name as public_name', 
    //             'communities.english_name as community_name',
    //             'regions.english_name as region', 'sub_regions.english_name as sub_region',
    //             'energy_systems.name as energy_name', 'energy_system_types.name as energy_type_name',
    //             'households.number_of_male', 'households.number_of_female', 
    //             'households.number_of_adults', 'households.number_of_children', 'households.phone_number',
    //             'all_energy_meters.meter_number', 'all_energy_meters.daily_limit', 
    //             'all_energy_meters.installation_date', 'meter_cases.meter_case_name_english');

    //            // DB::raw('YEAR(all_energy_meters.installation_date) year'));


    //     if($this->request->misc) {

    //         if($this->request->misc == "misc") {

    //             $query->where("all_energy_meters.misc", 1);
    //         } else if($this->request->misc == "new") {

    //             $query->where("all_energy_meters.misc", 0);
    //         } else if($this->request->misc == "maintenance") {

    //             $query->where("all_energy_meters.misc", 2);
    //         }
    //     }

    //     if($this->request->date_from) {
    //         $query->where("all_energy_meters.installation_date", ">=", $this->request->date_from);
    //     }

    //     if($this->request->date_to) {
    //         $query->where("all_energy_meters.installation_date", "<=", $this->request->date_to);
    //     }

    //     //dd($query->count());
    //     return $query->get();
    // }

    // /**
    //  * Write code on Method
    //  *
    //  * @return response()
    //  */
    // public function headings(): array
    // {
    //     return ["Meter User", "Shared User", "Meter Public", "Community", "Region", "Sub Region", 
    //         "Energy System", "Energy System Type", "Number of male", "Number of Female", 
    //         "Number of adults", "Number of children", "Phone number", "Meter Number", 
    //         "Daily Limit",  "Installation Date", "Meter Case"];
    // }
}