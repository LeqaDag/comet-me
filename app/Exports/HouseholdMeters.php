<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents; 
use Maatwebsite\Excel\Events\AfterSheet;
use DB;

class HouseholdMeters implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize,
    WithStyles,WithEvents
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
        $query = DB::table('household_meters') 
            ->join('all_energy_meters', 'all_energy_meters.id', 
                '=', 'household_meters.energy_user_id')
            ->LeftJoin('installation_types', 'all_energy_meters.installation_type_id', '=', 
                'installation_types.id')
            ->leftJoin('meter_cases', 'all_energy_meters.meter_case_id', '=', 'meter_cases.id')
            ->join('communities', 'all_energy_meters.community_id', '=', 'communities.id')
            ->join('regions', 'communities.region_id', '=', 'regions.id')
            ->join('sub_regions', 'communities.sub_region_id', '=', 'sub_regions.id')
            ->join('households', 'household_meters.household_id', '=', 'households.id')
            ->leftJoin('all_energy_meter_donors', 'all_energy_meters.id', '=',
                'all_energy_meter_donors.all_energy_meter_id')
            ->leftJoin('donors', 'all_energy_meter_donors.donor_id', '=',
                'donors.id')
            ->where('household_meters.is_archived', 0)
            ->select([
                'households.english_name as english_name',
                'household_meters.user_name', 'installation_types.type',
                'meter_cases.meter_case_name_english',  
                'communities.english_name as community_name',
                'regions.english_name as region', 'sub_regions.english_name as sub_region',
                'households.number_of_male', 'households.number_of_female', 
                'households.number_of_adults', 'households.number_of_children', 
                'households.phone_number',    
                DB::raw('group_concat(donors.donor_name) as donors'),
            ])->groupBy('household_meters.id');


        if($this->request->community_id) {

            $query->where("all_energy_meters.community_id", $this->request->community_id);
        }
        if($this->request->type) {

            $query->where("all_energy_meters.installation_type_id", $this->request->type);
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
        return ["Shared User", "Main User", "Installation Type", "Meter Case",
            "Community", "Region", "Sub Region", "Number of male", "Number of Female", 
            "Number of adults", "Number of children", "Phone number", "Donors"];
    }

    public function title(): string
    {
        return 'Household Meters';
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
              
                $event->sheet->getDelegate()->freezePane('A2');  
            },
        ];
    }

    /**
     * Styling
     *
     * @return response()
     */
    public function styles(Worksheet $sheet)
    {
        $sheet->setAutoFilter('A1:M1');

        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true, 'size' => 14]],
        ];
    }
}