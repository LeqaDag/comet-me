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
use \Carbon\Carbon;
use DB;

class EnergyRequestedSummary implements FromCollection, WithTitle, ShouldAutoSize, 
    WithStyles, WithEvents,WithCustomStartCell
{
    private $misc = 0, $activateMisc = 0, $requestedHouseholds = 0, $relocatedHouseholds = 0,
        $activateRelocated = 0;

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

        // MISC FBS 
        $this->misc = DB::table('all_energy_meters')
            ->join('communities', 'all_energy_meters.community_id', 'communities.id')
            ->join('households', 'households.id', 'all_energy_meters.household_id')
            ->where('communities.created_at', '<=', $oneYearAgo)
            ->where('all_energy_meters.is_archived', 0)
            ->where('all_energy_meters.energy_system_type_id', 2)
            ->where('all_energy_meters.energy_system_cycle_id', '!=', null);

        $this->activateMisc = DB::table('households')
            ->join('all_energy_meters', 'all_energy_meters.household_id', 'households.id')
            ->join('communities', 'communities.id', 'all_energy_meters.community_id')
            ->where('communities.created_at', '<=', $oneYearAgo)
            ->where('households.is_archived', 0)
            ->where('households.household_status_id', 4) 
            ->where('all_energy_meters.energy_system_type_id', 2)
            ->where('all_energy_meters.energy_system_cycle_id', '!=', null)
            ->where('all_energy_meters.meter_active', 'Yes');

        // Requested
        $this->requestedHouseholds = DB::table('households')
            ->join('energy_request_systems', 'energy_request_systems.household_id', 'households.id')
            ->join('communities', 'households.community_id', 'communities.id')
            ->where('communities.created_at', '<=', $oneYearAgo)
            ->where('households.is_archived', 0)
            ->where('households.household_status_id', 5);

        // Relocated Households
        $this->relocatedHouseholds =  DB::table('all_energy_meters')
            ->join('displaced_households', 'all_energy_meters.household_id', 'displaced_households.household_id')
            ->join('households', 'all_energy_meters.household_id', 'households.id')
            ->join('household_statuses', 'households.household_status_id', 'household_statuses.id')
            ->join('communities', 'households.community_id', 'communities.id')
            ->join('meter_cases', 'all_energy_meters.meter_case_id', 'meter_cases.id')
            ->where('all_energy_meters.is_archived', 0)
            ->whereNotNull('communities.energy_system_cycle_id')
            ->where('all_energy_meters.energy_system_cycle_id', '!=', null);

        $this->activateRelocated =  DB::table('all_energy_meters')
            ->join('displaced_households', 'all_energy_meters.household_id', 'displaced_households.household_id')
            ->join('communities', 'all_energy_meters.community_id', 'communities.id')
            ->where('all_energy_meters.is_archived', 0)
            ->whereNotNull('communities.energy_system_cycle_id')
            ->where('all_energy_meters.energy_system_cycle_id', '!=', null)
            ->where('all_energy_meters.meter_active', 'Yes');

        $queryCommunities = DB::table('communities')
            ->join('regions', 'communities.region_id', 'regions.id')
            ->join('community_statuses', 'communities.community_status_id', 'community_statuses.id')
            ->leftJoin('households as all_households', 'all_households.community_id', 'communities.id')
            ->leftJoin('energy_system_types as all_energy_types', 'all_energy_types.id', 'all_households.energy_system_type_id') 
            ->leftJoin('all_energy_meters', 'all_energy_meters.household_id', 'all_households.id')
            ->leftJoin('grid_community_compounds', 'communities.id', 'grid_community_compounds.community_id')
            ->where('communities.is_archived', 0)
            ->whereNotNull('communities.energy_system_cycle_id')
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('compounds')
                    ->whereRaw('compounds.community_id = communities.id');
            }) 
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('displaced_households')
                    ->whereRaw('displaced_households.household_id = all_households.id');
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
            ->leftJoin('grid_community_compounds', 'compounds.id', 'grid_community_compounds.compound_id')
            ->where('communities.is_archived', 0)
            ->where('households.is_archived', 0)
            ->where('compound_households.is_archived', 0)
            ->where('all_energy_meters.is_archived', 0)
            ->whereNotNull('communities.energy_system_cycle_id')
            ->select(
                'compounds.english_name',  
                'regions.english_name as region',
                DB::raw('COUNT(DISTINCT CASE WHEN households.energy_system_type_id = 2 THEN households.id END) as sum_FBS'),
                DB::raw('COUNT(DISTINCT CASE WHEN households.energy_system_type_id = 1 THEN households.id END) as sum_MG'),
                DB::raw('COUNT(DISTINCT CASE WHEN households.energy_system_type_id = 4 THEN households.id END) as sum_SMG'),
                'grid_community_compounds.electricity_room',
                'grid_community_compounds.grid', 
                DB::raw('COUNT(CASE WHEN households.household_status_id = 3 THEN households.id END) as sum_AC'),
                DB::raw('COUNT(DISTINCT CASE WHEN all_energy_meters.meter_case_id = 1 THEN households.id END) as sum_DC')
            )
            ->groupBy('compounds.english_name');
 
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
        $this->requestedHouseholds = $this->requestedHouseholds->count();
        $this->relocatedHouseholds = $this->relocatedHouseholds->count();
        $this->activateRelocated = $this->activateRelocated->count();

        return $compoundsCollection->merge($communitiesCollection);
    } 

    public function startCell(): string
    {
        return 'A5';
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

        $sheet->setCellValue('A2', 'MISC FBS');  
        $sheet->setCellValue('A3', 'Relocated Households');  
        $sheet->setCellValue('A4', 'Requested Households');     
        $sheet->setCellValue('B2', ' ');       
        $sheet->setCellValue('B3', ' ');       
        $sheet->setCellValue('B4', ' ');      
        $sheet->setCellValue('C2', $this->misc);
        $sheet->setCellValue('C3', $this->relocatedHouseholds);
        $sheet->setCellValue('C4', $this->requestedHouseholds);
        
        $sheet->setCellValue('I2', $this->activateMisc);
        $sheet->setCellValue('I3', $this->activateRelocated);

        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true, 'size' => 12]]
        ];
    }
}