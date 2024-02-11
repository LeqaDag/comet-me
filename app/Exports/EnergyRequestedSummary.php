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
        $queryCommunities =  DB::table('communities')
            ->join('regions', 'communities.region_id', 'regions.id')
            ->join('community_statuses', 'communities.community_status_id', 
                'community_statuses.id')
            ->leftJoin('households as all_households', 'all_households.community_id',
                'communities.id')
            ->leftJoin('energy_system_types as all_energy_types', 'all_energy_types.id',
                'all_households.energy_system_type_id')
            ->where('communities.is_archived', 0)
            ->where('communities.community_status_id', 1)
            ->orWhere('communities.community_status_id', 2)
             ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('compounds')
                    ->whereRaw('compounds.community_id = communities.id');
            })
            ->select(
                'communities.english_name',
                'regions.english_name as region',
                DB::raw('COUNT(CASE WHEN all_energy_types.id = 2 THEN 1 END) as sum_FBS'),
                DB::raw('COUNT(CASE WHEN all_energy_types.id = 1 THEN 1 END) as sum_MG'),
                DB::raw('COUNT(CASE WHEN all_energy_types.id = 4 THEN 1 END) as sum_SMG'),
                DB::raw('COUNT(CASE WHEN all_households.household_status_id = 3 THEN 1 END) as sum_AC'),
                DB::raw('COUNT(CASE WHEN all_households.household_status_id = 4 THEN 1 END) as sum_DC')
                )
            ->groupBy('communities.english_name');

        $queryCompounds = DB::table('compounds')
            ->join('communities', 'communities.id', 'compounds.community_id')
            ->join('regions', 'communities.region_id', 'regions.id')
            ->join('community_statuses', 'communities.community_status_id', 
                'community_statuses.id')
            ->join('compound_households', 'compound_households.compound_id', 'compounds.id')
            ->join('households', 'compound_households.household_id', 'households.id')
            ->leftJoin('energy_system_types', 'households.energy_system_type_id', 'energy_system_types.id')
            ->where('communities.is_archived', 0)
            ->where('households.household_status_id', 2)
            ->where('communities.community_status_id', 1)
            ->orWhere('communities.community_status_id', 2)
            ->orWhere('communities.community_status_id', 3)
            ->select(
                'compounds.english_name',
                'regions.english_name as region',
                DB::raw('COUNT(CASE WHEN energy_system_types.id = 2 THEN 1 END) as sum_FBS'),
                DB::raw('COUNT(CASE WHEN energy_system_types.id = 1 THEN 1 END) as sum_MG'),
                DB::raw('COUNT(CASE WHEN energy_system_types.id = 4 THEN 1 END) as sum_SMG'),
                DB::raw('COUNT(CASE WHEN households.household_status_id = 3 THEN 1 END) as sum_AC'),
                DB::raw('COUNT(CASE WHEN households.household_status_id = 4 THEN 1 END) as sum_DC')
                )
            ->groupBy('compounds.english_name');

        $this->misc = DB::table('households')
            ->join('energy_request_systems', 'energy_request_systems.household_id', 'households.id')
            ->where('households.is_archived', 0)
            ->where('households.household_status_id', 5)
            ->where('energy_request_systems.recommendede_energy_system_id', 2)
            ->count();

        if($this->request->community_id) {

            $queryCompounds->where("communities.id", $this->request->community_id);
        }
        if($this->request->request_status) {

            $queryCompounds->where("energy_request_systems.energy_request_status_id", $this->request->request_status);
        }

        $communitiesCollection = collect($queryCommunities->get());
        $compoundsCollection = collect($queryCompounds->get());

        return $compoundsCollection->merge($communitiesCollection);
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
        $sheet->setAutoFilter('A1:G1');
        $sheet->setCellValue('A1', 'Name');   
        $sheet->setCellValue('B1', 'Geographical Region'); 
        $sheet->setCellValue('C1', '# confirmed FBS'); 
        $sheet->setCellValue('D1', '# confirmed households/meters (MG)'); 
        $sheet->setCellValue('E1', 'Small MG (no electricity room)'); 
        $sheet->setCellValue('F1', 'Completed AC'); // household_status is in-progress
        $sheet->setCellValue('G1', 'Completed DC'); // household_status is served

        $sheet->setCellValue('A2', 'MISC FBS -- "Requested Systems"');     
        $sheet->setCellValue('B2', ' ');     
        $sheet->setCellValue('C2', $this->misc);

        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true, 'size' => 12]]
        ];
    }
}