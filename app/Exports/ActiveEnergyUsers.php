<?php

namespace App\Exports;

use App\Models\AllEnergyMeter;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB;

class ActiveEnergyUsers implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, 
    WithStyles
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
        $query =  DB::table('all_energy_meters')
            ->join('communities', 'all_energy_meters.community_id', 'communities.id')
            ->join('regions', 'communities.region_id', 'regions.id')
            ->join('sub_regions', 'communities.sub_region_id', 'sub_regions.id')
            ->LeftJoin('installation_types', 'all_energy_meters.installation_type_id', 
                'installation_types.id')
            ->LeftJoin('public_structures', 'all_energy_meters.public_structure_id', 
                'public_structures.id')
            ->leftJoin('households', 'all_energy_meters.household_id', 'households.id')
            ->leftJoin('meter_cases', 'all_energy_meters.meter_case_id', 'meter_cases.id')
            ->leftJoin('energy_systems', 'all_energy_meters.energy_system_id', 'energy_systems.id')
            ->leftJoin('energy_system_types', 'all_energy_meters.energy_system_type_id', 
                'energy_system_types.id')
            ->leftJoin('all_energy_meter_donors', 'all_energy_meters.id',
            'all_energy_meter_donors.all_energy_meter_id')
            ->leftJoin('donors', 'all_energy_meter_donors.donor_id',
                'donors.id')
            ->where('all_energy_meters.is_archived', 0)
            ->where('all_energy_meters.meter_number', '!=', 0) 
            ->where('all_energy_meter_donors.is_archived', 0) 
            ->select([
                DB::raw('IFNULL(households.english_name, public_structures.english_name) 
                    as exported_value'),
                DB::raw('IFNULL(households.arabic_name, public_structures.arabic_name) 
                    as exported_value_arabic'),
                'all_energy_meters.is_main',
                'communities.english_name as community_name',
                'regions.english_name as region', 'sub_regions.english_name as sub_region', 
                'meter_cases.meter_case_name_english', 
                'energy_systems.name as energy_name', 'energy_system_types.name as energy_type_name',
                'all_energy_meters.ground_connected',
                'households.number_of_male', 'households.number_of_female', 
                'households.number_of_adults', 'households.number_of_children', 
                'households.phone_number', 'all_energy_meters.meter_number', 
                'all_energy_meters.daily_limit', 'all_energy_meters.installation_date',
                    DB::raw('group_concat(donors.donor_name) as donors'),
                ]) 
            ->groupBy('all_energy_meters.id');

        if($this->request->region_id) {

            $query->where("regions.id", $this->request->region_id);
        }
        if($this->request->community_id) {

            $query->where("all_energy_meters.community_id", $this->request->community_id);
        }
        if($this->request->type) {

            $query->where("all_energy_meters.installation_type_id", $this->request->type);
        }
        if($this->request->energy_system_type_id) {

            $query->where("energy_system_types.id", $this->request->energy_system_type_id);
        }
        if($this->request->meter_case_id) {

            $query->where("meter_cases.id", $this->request->meter_case_id);
        }
        if($this->request->service_year) {

            $query->whereYear("all_energy_meters.installation_date", $this->request->service_year);
        }
        if($this->request->date_from) {

            $query->where("all_energy_meters.installation_date", ">=", $this->request->date_from);
        }
        if($this->request->date_to) {

            $query->where("all_energy_meters.installation_date", "<=", $this->request->date_to);
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
        return ["Energy Holder (User/Public)", "Energy Holder (User/Public) Arabic",
            "Main Holder", "Community", "Region", "Sub Region", "Meter Case", "Energy System", 
            "Energy System Type", "Connected Ground", "Number of male", "Number of Female", 
            "Number of adults",  "Number of children", "Phone number", "Meter Number", 
            "Daily Limit", "Installation Date", "Donors"];
    }

    public function title(): string
    {
        return 'Active Energy Users';
    }

    /**
     * Styling
     *
     * @return response()
     */
    public function styles(Worksheet $sheet)
    {
        $sheet->setAutoFilter('A1:S1');

        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}