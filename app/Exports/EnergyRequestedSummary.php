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
    private $misc = 0, $activateMisc = 0;

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
            ->leftJoin('all_energy_meters', 'all_energy_meters.household_id', 'all_households.id')
            ->leftJoin('grid_community_compounds', 'communities.id',
                'grid_community_compounds.community_id')
            ->where('communities.is_archived', 0)
            ->where('communities.energy_system_cycle_id', '!=', null)
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
                'grid_community_compounds.electricity_room',
                'grid_community_compounds.grid',
                DB::raw('COUNT(CASE WHEN all_households.household_status_id = 3 THEN 1 END) as sum_AC'),
                DB::raw('COUNT(CASE WHEN all_energy_meters.meter_case_id = 1 THEN 1 END) as sum_DC')
                )
            ->groupBy('communities.english_name');
 
        $queryCompounds = DB::table('compounds')
            ->join('communities', 'communities.id', 'compounds.community_id')
            ->join('regions', 'communities.region_id', 'regions.id')
            ->leftJoin('compound_households', 'compound_households.compound_id', 'compounds.id')
            ->leftJoin('households', 'compound_households.household_id', 'households.id')
            ->leftJoin('energy_system_types', 'households.energy_system_type_id', 'energy_system_types.id')
            ->leftJoin('all_energy_meters', 'all_energy_meters.household_id', 'households.id')
            ->leftJoin('grid_community_compounds', 'compounds.id',
                'grid_community_compounds.compound_id')
            ->where('communities.is_archived', 0)
            ->where('households.is_archived', 0)
            ->where('communities.energy_system_cycle_id', '!=', null)
            ->select(
                'compounds.english_name', 
                'regions.english_name as region',
                DB::raw('COUNT(DISTINCT CASE WHEN households.energy_system_type_id = 2 THEN households.id END) as sum_FBS'),
                DB::raw('COUNT(DISTINCT CASE WHEN households.energy_system_type_id = 1 THEN households.id END) as sum_MG'),
                DB::raw('COUNT(DISTINCT CASE WHEN households.energy_system_type_id = 4 THEN households.id END) as sum_SMG'),
                'grid_community_compounds.electricity_room',
                'grid_community_compounds.grid', 
                DB::raw('COUNT(DISTINCT CASE WHEN households.household_status_id = 3 THEN households.id END) as sum_AC'),
                DB::raw('COUNT(DISTINCT CASE WHEN households.household_status_id = 3 
                    AND all_energy_meters.meter_case_id = 1 THEN 1 END) as sum_DC')
            )
            ->groupBy('compounds.english_name');
    

        $this->misc = DB::table('all_energy_meters')
            ->join('communities', 'all_energy_meters.community_id', 'communities.id')
            ->join('households', 'households.id', 'all_energy_meters.household_id')
            ->where('all_energy_meters.is_archived', 0)
            ->where('all_energy_meters.energy_system_type_id', 2)
            ->where('all_energy_meters.energy_system_cycle_id', '!=', null);

        $this->activateMisc = DB::table('households')
            //->join('energy_request_systems', 'energy_request_systems.household_id', 'households.id')
            ->join('all_energy_meters', 'all_energy_meters.household_id', 'households.id')
            ->join('communities', 'communities.id', 'all_energy_meters.community_id')
            ->where('households.is_archived', 0)
            ->where('households.household_status_id', 4) 
            ->where('all_energy_meters.energy_system_type_id', 2)
            ->where('all_energy_meters.energy_system_cycle_id', '!=', null)
            ->where('all_energy_meters.meter_case_id', 1)
            ->select('communities.english_name', 'households.english_name as household');

        if($this->request->community_id) {
 
            $queryCompounds->where("communities.id", $this->request->community_id);
        }
        if($this->request->request_status) {

            $queryCompounds->where("energy_request_systems.energy_request_status_id", $this->request->request_status);
        }
        if($this->request->energy_cycle_id) {

            $queryCommunities->where("communities.energy_system_cycle_id", $this->request->energy_cycle_id);
            $queryCompounds->where("communities.energy_system_cycle_id", $this->request->energy_cycle_id);
            $this->misc->where("households.energy_system_cycle_id", $this->request->energy_cycle_id);
            $this->activateMisc->where("households.energy_system_cycle_id", $this->request->energy_cycle_id);
        }

        $communitiesCollection = collect($queryCommunities->get());
        $compoundsCollection = collect($queryCompounds->get());
        $this->misc = $this->misc->count();
        $this->activateMisc = $this->activateMisc->count();

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
        $sheet->setAutoFilter('A1:I1');
        $sheet->setCellValue('A1', 'Name');   
        $sheet->setCellValue('B1', 'Geographical Region'); 
        $sheet->setCellValue('C1', '# confirmed FBS'); 
        $sheet->setCellValue('D1', '# confirmed households/meters (MG)'); 
        $sheet->setCellValue('E1', 'Small MG (no electricity room)'); 
        $sheet->setCellValue('F1', 'Electricity Room)'); 
        $sheet->setCellValue('G1', 'Grid'); 
        $sheet->setCellValue('H1', 'Completed AC'); // household_status is in-progress
        $sheet->setCellValue('I1', 'Activate Meter'); // household_status is served

        $sheet->setCellValue('A2', 'MISC FBS -- "Requested Systems"');     
        $sheet->setCellValue('B2', ' ');     
        $sheet->setCellValue('C2', $this->misc);
        $sheet->setCellValue('I2', $this->activateMisc);

        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true, 'size' => 12]]
        ];
    }
}