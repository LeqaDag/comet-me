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
use \Carbon\Carbon;
use DB;

class EnergyMISCFbs implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, 
    WithStyles, WithEvents
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
        $oneYearAgo = Carbon::now()->subYear();

        $query = DB::table('all_energy_meters')
            ->join('communities', 'all_energy_meters.community_id', 'communities.id')
            ->join('households', 'households.id', 'all_energy_meters.household_id')
            ->leftJoin('household_statuses', 'households.household_status_id', 
                'household_statuses.id')
            ->leftJoin('meter_cases', 'all_energy_meters.meter_case_id', 'meter_cases.id')
            ->where('communities.created_at', '<=', $oneYearAgo)
            ->where('all_energy_meters.is_archived', 0)
            ->where('all_energy_meters.energy_system_type_id', 2)
            ->where('all_energy_meters.energy_system_cycle_id', '!=', null)
            ->select(
                'households.english_name as household',
                'communities.english_name as community_name', 
                'household_statuses.status as status', 
                'all_energy_meters.meter_number', 
                'meter_cases.meter_case_name_english', 'all_energy_meters.meter_active', 
                'all_energy_meters.installation_date', 'all_energy_meters.daily_limit',
                DB::raw('CASE WHEN households.number_of_male IS NULL 
                        OR households.number_of_female IS NULL 
                        OR households.number_of_adults IS NULL 
                        OR households.number_of_children IS NULL 
                    THEN "Missing Details" 
                    ELSE "Complete" 
                    END as details_status'),
                'households.number_of_male', 'households.number_of_female', 
                'households.number_of_adults', 'households.number_of_children', 
                DB::raw('CASE 
                    WHEN (households.number_of_male IS NOT NULL AND households.number_of_female IS NOT NULL 
                        AND households.number_of_adults IS NOT NULL AND households.number_of_children IS NOT NULL 
                        AND (households.number_of_adults + households.number_of_children) <> (households.number_of_male + households.number_of_female))
                    THEN "Discrepancy" 
                    ELSE "No Discrepancy" 
                    END as discrepancies_status'),
                'households.phone_number');

 
        if($this->request->community_id) {

            $query->where("communities.id", $this->request->community_id);
        }

        if($this->request->energy_cycle_id) {

            $query->where("all_energy_meters.energy_system_cycle_id", $this->request->energy_cycle_id);
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
        return ["Household", "Community", "Household Status", "Meter Number", "Meter Case", "Meter Active", 
            "Installation Date", "Daily Limit", "All Details", "Number of male", "Number of Female", "Number of adults", 
            "Number of children", "Discrepancy", "Phone number"];
    }

    public function title(): string
    {
        return 'MISC FBS';
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
        $sheet->setAutoFilter('A1:O1');

        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}