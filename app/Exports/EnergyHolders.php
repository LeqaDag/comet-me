<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB;

class EnergyHolders implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, WithStyles
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
            ->LeftJoin('public_structures', 'all_energy_meters.public_structure_id', 
                'public_structures.id')
            ->leftJoin('households', 'all_energy_meters.household_id', '=', 'households.id')
            ->leftJoin('meter_cases', 'all_energy_meters.meter_case_id', '=', 'meter_cases.id')
            ->leftJoin('energy_systems', 'all_energy_meters.energy_system_id', '=', 'energy_systems.id')
            ->leftJoin('energy_system_types', 'all_energy_meters.energy_system_type_id', 
                '=', 'energy_system_types.id')
            ->leftJoin('all_energy_meter_donors', 'all_energy_meters.id', '=',
            'all_energy_meter_donors.all_energy_meter_id')
            ->leftJoin('donors', 'all_energy_meter_donors.donor_id', '=',
                'donors.id')
            ->select([
                'households.english_name as english_name', 
                'public_structures.english_name as public',
                'all_energy_meters.is_main',
                'communities.english_name as community_name',
                'regions.english_name as region', 'sub_regions.english_name as sub_region', 
                'meter_cases.meter_case_name_english', 
                'energy_systems.name as energy_name', 'energy_system_types.name as energy_type_name',
                'households.number_of_male', 'households.number_of_female', 
                'households.number_of_adults', 'households.number_of_children', 
                'households.phone_number', 'all_energy_meters.meter_number', 
                'all_energy_meters.daily_limit', 'all_energy_meters.installation_date',
                DB::raw('group_concat(donors.donor_name) as donors'),
            ])
            ->groupBy('all_energy_meters.id');


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

      //  $query
        // dd($query->count());

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
        return ["Energy User", "Public Structure", "Main Holder", "Community", "Region", "Sub Region", 
            "Meter Case", "Energy System", "Energy System Type", "Number of male", "Number of Female", 
            "Number of adults", "Number of children", "Phone number", "Meter Number", 
            "Daily Limit",  "Installation Date", "Donors"];
    }

    public function title(): string
    {
        return 'Energy Holders';
    }

    /**
     * Styling
     *
     * @return response()
     */
    public function styles(Worksheet $sheet)
    {
        $sheet->setAutoFilter('A1:R1');

        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}