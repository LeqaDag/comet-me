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
        $query = DB::table('all_energy_meters')
            ->join('households', 'households.id', 'all_energy_meters.id')
            ->join('communities', 'households.community_id', 'communities.id')
            ->leftJoin('household_statuses', 'households.household_status_id', 
                'household_statuses.id')
            ->where('households.is_archived', 0)
            ->where('all_energy_meters.energy_system_type_id', 2)
            ->where('households.energy_system_cycle_id', '!=', null)
            ->select(
                'households.english_name as household',
                'communities.english_name as community_name', 
                'household_statuses.status as status', 
                'households.number_of_male', 'households.number_of_female', 
                'households.number_of_adults', 'households.number_of_children', 
                'households.phone_number');

 
        if($this->request->community_id) {

            $query->where("communities.id", $this->request->community_id);
        }

        if($this->request->energy_cycle_id) {

            $query->where("households.energy_system_cycle_id", $this->request->energy_cycle_id);
        }

        die($query->get());
        return $query->get();
    } 

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function headings(): array
    {
        return ["Household", "Community", "Status", "Number of male", "Number of Female", "Number of adults", 
            "Number of children", "Phone number"];
    }

    public function title(): string
    {
        return 'MISC (FBS) Households';
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