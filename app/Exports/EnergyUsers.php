<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB;

class EnergyUsers implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, WithStyles
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
        $data = DB::table('all_energy_meter_donors')
            ->join('all_energy_meters', 'all_energy_meters.id', '=',
                'all_energy_meter_donors.all_energy_meter_id')
            ->join('communities', 'all_energy_meters.community_id', '=', 'communities.id')
            ->join('regions', 'communities.region_id', '=', 'regions.id')
            ->join('sub_regions', 'communities.sub_region_id', '=', 'sub_regions.id')
            ->join('households', 'all_energy_meters.household_id', '=', 'households.id')
            ->join('meter_cases', 'all_energy_meters.meter_case_id', '=', 'meter_cases.id')
            ->join('energy_systems', 'all_energy_meters.energy_system_id', '=', 'energy_systems.id')
            ->join('energy_system_types', 'all_energy_meters.energy_system_type_id', 
                '=', 'energy_system_types.id')
            ->join('donors', 'all_energy_meter_donors.donor_id', '=',
                'donors.id')
            ->where('all_energy_meters.household_id', "!=", 0)
            ->where('all_energy_meters.meter_case_id', 1)
            ->where('all_energy_meter_donors.donor_id', 1);

        // dd($data->count());
        // $sub = DB::table('household_meters')
        //     ->leftJoin('all_energy_meters', 'household_meters.energy_user_id', 
        //         '=', 'all_energy_meters.id')
        //     ->leftJoin('all_energy_meter_donors', 'all_energy_meters.id', 
        //         '=', 'all_energy_meter_donors.all_energy_meter_id')
        //     ->where('all_energy_meters.meter_case_id', 1)
        //     ->where('all_energy_meters.household_id', "!=", 0)
        //     ->where('all_energy_meter_donors.donor_id', 1)
        //     ->count();

        // // $total = $data->merge($sub);
        // dd($sub);

        $query = DB::table('all_energy_meters')
            ->join('communities', 'all_energy_meters.community_id', '=', 'communities.id')
            ->join('regions', 'communities.region_id', '=', 'regions.id')
            ->join('sub_regions', 'communities.sub_region_id', '=', 'sub_regions.id')
            ->join('households', 'all_energy_meters.household_id', '=', 'households.id')
            ->join('meter_cases', 'all_energy_meters.meter_case_id', '=', 'meter_cases.id')
            ->join('energy_systems', 'all_energy_meters.energy_system_id', '=', 'energy_systems.id')
            ->join('energy_system_types', 'all_energy_meters.energy_system_type_id', 
                '=', 'energy_system_types.id')
            ->leftJoin('all_energy_meter_donors', 'all_energy_meters.id', '=',
            'all_energy_meter_donors.all_energy_meter_id')
            ->leftJoin('donors', 'all_energy_meter_donors.donor_id', '=',
                'donors.id')
            ->where('all_energy_meters.meter_case_id', 1)
            ->select('households.english_name as english_name', 
                'communities.english_name as community_name',
                'regions.english_name as region', 'sub_regions.english_name as sub_region',
                'energy_systems.name as energy_name', 'energy_system_types.name as energy_type_name',
                'households.number_of_male', 'households.number_of_female', 
                'households.number_of_adults', 'households.number_of_children', 
                'households.phone_number', 'all_energy_meters.meter_number', 
                'all_energy_meters.daily_limit', 'all_energy_meters.installation_date', 
                'meter_cases.meter_case_name_english', 'donors.donor_name');

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
        return ["Active User", "Community", "Region", "Sub Region", 
            "Energy System", "Energy System Type", "Number of male", "Number of Female", 
            "Number of adults", "Number of children", "Phone number", "Meter Number", 
            "Daily Limit",  "Installation Date", "Meter Case", "Donor"];
    }

    public function title(): string
    {
        return 'Energy Users';
    }

    /**
     * Styling
     *
     * @return response()
     */
    public function styles(Worksheet $sheet)
    {
        $sheet->setAutoFilter('A1:P1');

        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true, 'size' => 14]],
        ];
    }
}