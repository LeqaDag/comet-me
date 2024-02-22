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

class EnergyRequestedHousehold implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, 
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
            ->join('energy_request_systems', 'energy_request_systems.household_id', 'households.id')
            ->join('communities', 'households.community_id', 'communities.id')
            ->leftJoin('compound_households', 'compound_households.household_id', 'households.id')
            ->leftJoin('compounds', 'compound_households.compound_id', 'compounds.id')
            ->leftJoin('energy_request_statuses', 'energy_request_systems.energy_request_status_id', 
                'energy_request_statuses.id')
            ->leftJoin('energy_system_types', 'energy_request_systems.recommendede_energy_system_id', 
                'energy_system_types.id')
            ->where('households.is_archived', 0)
            ->where('households.household_status_id', 5) 
            ->where('energy_request_systems.recommendede_energy_system_id', 2)
            ->select('households.english_name as household',
                'communities.english_name as community_name', 
                'compounds.english_name as compound_name',
                'energy_system_types.name', "energy_request_systems.date", 
                'households.number_of_male', 'households.number_of_female', 
                'households.number_of_adults', 'households.number_of_children', 
                'households.phone_number');

       // die($query->get());
 
        if($this->request->community_id) {

            $query->where("communities.id", $this->request->community_id);
        }
        if($this->request->request_status) {

            $query->where("energy_request_systems.energy_request_status_id", $this->request->request_status);
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
        return ["Household", "Community", "Compound", "System Type",  
            "Request Date", "Number of male", "Number of Female", "Number of adults", 
            "Number of children", "Phone number"];
    }

    public function title(): string
    {
        return 'Requested Households MISC';
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