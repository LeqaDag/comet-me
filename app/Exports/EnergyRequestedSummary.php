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
use Maatwebsite\Excel\Concerns\WithCustomStartCell; 
use DB;

class EnergyRequestedSummary implements FromCollection, WithTitle, ShouldAutoSize, 
    WithStyles, WithEvents,WithCustomStartCell
{
    private $misc = 0;

    protected $request; 

    function __construct($request) {
        $this->request = $request;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()  
    {
        $query = DB::table('communities')
            ->leftJoin('community_statuses', 'communities.community_status_id', 
                '=', 'community_statuses.id')
            ->leftJoin('recommended_community_energy_systems', 'communities.id', 
                '=', 'recommended_community_energy_systems.community_id')
            ->leftJoin('energy_system_types', 'energy_system_types.id', 
                '=', 'recommended_community_energy_systems.energy_system_type_id')
            //->leftJoin('compound_households', 'compound_households.household_id', 'households.id')
            ->leftJoin('compounds', 'communities.id', '=', 'compounds.community_id')
            ->join('regions', 'communities.region_id', '=', 'regions.id')
            ->join('sub_regions', 'communities.sub_region_id', '=', 'sub_regions.id')
            ->where('communities.is_archived', 0)
            ->where('communities.community_status_id', 1)
            ->select(
                DB::raw('IFNULL(compounds.english_name, communities.english_name)'),
                'regions.english_name as region',
                'recommended_community_energy_systems.numbers')
            ->groupBy(DB::raw('IFNULL(compounds.english_name, communities.english_name)'));

       // die($query->get());
        $this->misc = DB::table('energy_request_systems')
            ->where('energy_request_systems.is_archived', 0)
            ->where('energy_request_systems.recommendede_energy_system_id', 2)
            ->count();

        if($this->request->community_id) {

            $query->where("communities.id", $this->request->community_id);
        }
        if($this->request->request_status) {

            $query->where("energy_request_systems.energy_request_status_id", $this->request->request_status);
        }

        return $query->get();
    } 

    public function startCell(): string
    {
        return 'A3';
    }

    public function title(): string
    {
        return 'Energy Progress Summary';
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
              
                $event->sheet->getDelegate()->freezePane('A1');  
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
        $sheet->setAutoFilter('A1:E1');
        $sheet->setCellValue('A1', 'Name');   
        $sheet->setCellValue('B1', 'Geographical Region'); 
        $sheet->setCellValue('C1', '# confirmed FBS'); 
        $sheet->setCellValue('D1', '# confirmed households/meters (MG)'); 
        $sheet->setCellValue('E1', 'Small MG (no electricity room)'); 

        $sheet->setCellValue('A2', 'MISC FBS -- "Requested Systems"');     
        $sheet->setCellValue('B2', ' ');     
        $sheet->setCellValue('C2', $this->misc);

        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}