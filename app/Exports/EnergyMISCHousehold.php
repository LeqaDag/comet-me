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

class EnergyMISCHousehold implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, 
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

        $query = DB::table('households')
            ->join('communities', 'households.community_id', 'communities.id')
            ->where('communities.energy_system_cycle_id', NULL)
            ->where('households.is_archived', 0)
            ->where('households.household_status_id', 11)
            ->select( 
                'households.english_name as household',
                'communities.english_name as community_name', 

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
                'households.phone_number',
                'households.confirmation_notes'
            )
            ->groupBy('households.id');

 
        if($this->request->community_id) {

            $query->where("communities.id", $this->request->community_id);
        }

        if($this->request->energy_cycle_id) {

            $query->where("households.energy_system_cycle_id", $this->request->energy_cycle_id);
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
        return ["Household", "Community", "All Details", "Number of male", "Number of Female", "Number of adults", 
            "Number of children", "Discrepancy", "Phone number", "Notes"];
    }
 
    public function title(): string
    {
        return 'MISC/Confirmed Households';
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
        $sheet->setAutoFilter('A1:J1');

        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}