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
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use App\Models\HouseholdStatus;
use App\Models\Household;
use DB;

class EnergyRequestedSummary implements FromCollection, WithTitle, ShouldAutoSize, 
    WithStyles, WithEvents,WithCustomStartCell
{
    private $misc = 0, $activateMisc = 0, $requestedHouseholds = 0, $relocatedHouseholds = 0,
        $activateRelocated = 0, $miscRefrigerator = 0, $relocatedRefrigerator, $miscHouseholds = 0,
        $miscHouseholdsFBS = 0, $miscHouseholdsMGSMG = 0;

    protected $request; 

    function __construct($request) {

        $this->request = $request; 
    }
 
    /**
    * @return \Illuminate\Support\Collection
    */ 
    public function collection()  
    { 
        // $households = Household::where("is_archived", 0)
        //     ->where("household_status_id", 11)
        //     ->get();

        // foreach($households as $household) {

        //     $household->energy_system_cycle_id = 2;
        //     $household->save();
        // }

        $this->miscHouseholds = DB::table('households')
            ->join('communities', 'households.community_id', 'communities.id')
            ->where('communities.energy_system_cycle_id', NULL)
            ->where('households.is_archived', 0)
            ->where('households.household_status_id', 11);

        // MISC FBS 
        $this->misc = DB::table('all_energy_meters')
            ->join('communities', 'all_energy_meters.community_id', 'communities.id')
            ->join('households', 'households.id', 'all_energy_meters.household_id')
            ->where('communities.energy_system_cycle_id', NULL)
            ->where('all_energy_meters.is_archived', 0)
            ->where('all_energy_meters.energy_system_type_id', 2);

        $this->activateMisc = DB::table('households')
            ->join('all_energy_meters', 'all_energy_meters.household_id', 'households.id')
            ->join('communities', 'communities.id', 'all_energy_meters.community_id')
            ->where('communities.energy_system_cycle_id', NULL)
            ->where('households.is_archived', 0)
            ->where('households.household_status_id', 4) 
            ->where('all_energy_meters.energy_system_type_id', 2)
            ->where('all_energy_meters.meter_active', 'Yes'); 

        // Requested
        $this->requestedHouseholds = DB::table('households')
            ->join('energy_request_systems', 'energy_request_systems.household_id', 'households.id')
            ->join('communities', 'households.community_id', 'communities.id')
            //->where('households.energy_system_cycle_id',  $cycleYear)
            ->where('households.is_archived', 0)
            ->where('households.household_status_id', 5);

        // Relocated Households
        $this->relocatedHouseholds = DB::table('displaced_households')
            ->join('all_energy_meters', 'all_energy_meters.household_id', 'displaced_households.household_id')
            ->join('households', 'all_energy_meters.household_id', 'households.id')
            ->join('household_statuses', 'households.household_status_id', 'household_statuses.id')
            ->join('communities', 'all_energy_meters.community_id', 'communities.id')
            ->join('meter_cases', 'all_energy_meters.meter_case_id', 'meter_cases.id')
            ->where('all_energy_meters.is_archived', 0);

        // MISC Refrigerator
        $this->miscRefrigerator = DB::table('refrigerator_holders')
            ->join('communities', 'refrigerator_holders.community_id', 'communities.id')
            ->join('households', 'households.id', 'refrigerator_holders.household_id')
            ->join('all_energy_meters', 'all_energy_meters.household_id', 'households.id')
            ->where('communities.energy_system_cycle_id', NULL)
            ->where('refrigerator_holders.is_archived', 0)
            ->where('all_energy_meters.energy_system_type_id', 2);

        // Relocated Refrigerator
        $this->relocatedRefrigerator = DB::table('refrigerator_holders')
            ->join('households', 'refrigerator_holders.household_id', 'households.id')
            ->join('displaced_households', 'households.id', 'displaced_households.household_id')
            ->join('communities', 'households.community_id', 'communities.id')
            ->join('all_energy_meters', 'all_energy_meters.household_id', 'households.id')
            ->join('meter_cases', 'all_energy_meters.meter_case_id', 'meter_cases.id')
            ->where('all_energy_meters.is_archived', 0)
            ->where('refrigerator_holders.is_archived', 0)
            ->whereNotNull('communities.energy_system_cycle_id'); 

        $this->activateRelocated = DB::table('displaced_households')
            ->join('all_energy_meters', 'all_energy_meters.household_id', 'displaced_households.household_id')
            ->join('households', 'all_energy_meters.household_id', 'households.id')
            ->join('household_statuses', 'households.household_status_id', 'household_statuses.id')
            ->join('communities', 'all_energy_meters.community_id', 'communities.id')
            ->join('meter_cases', 'all_energy_meters.meter_case_id', 'meter_cases.id')
            ->where('all_energy_meters.is_archived', 0)
            //->where('all_energy_meters.energy_system_cycle_id', $cycleYear)
            ->where('all_energy_meters.meter_active', "Yes");
        
        $householdStatus = HouseholdStatus::where('status', "On Hold")->first();

        $queryCommunities = DB::table('communities')
            ->join('regions', 'communities.region_id', 'regions.id')
            ->join('community_statuses', 'communities.community_status_id', 'community_statuses.id')
            ->leftJoin('households as all_households', 'all_households.community_id', 'communities.id')
            ->leftJoin('energy_system_types as all_energy_types', 'all_energy_types.id', 'all_households.energy_system_type_id') 
            ->leftJoin('all_energy_meters', 'all_energy_meters.household_id', 'all_households.id')
            ->leftJoin('grid_community_compounds', 'communities.id', 'grid_community_compounds.community_id')
            ->leftJoin('public_structures', 'public_structures.community_id', 'communities.id')

            ->leftJoin('refrigerator_holders', 'all_households.id', 'refrigerator_holders.household_id') 

            ->where('communities.is_archived', 0)
            //->where('communities.energy_system_cycle_id', $cycleYear)
            ->where('all_households.household_status_id', '!=', $householdStatus->id)
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
                DB::raw('false as unneeded'),
                DB::raw('COUNT(CASE WHEN all_energy_types.id = 2 THEN 1 END) + 
                    COUNT(CASE WHEN public_structures.is_archived = 0 AND public_structures.energy_system_type_id = 2 THEN 1 END)
                    as sum_FBS'),
                DB::raw('COUNT(CASE WHEN all_energy_types.id = 1 THEN 1 END)  + 
                    COUNT(CASE WHEN public_structures.is_archived = 0 AND public_structures.energy_system_type_id = 1 THEN 1 END)
                    as sum_MG'),
                DB::raw('COUNT(CASE WHEN all_energy_types.id = 4 THEN 1 END)  + 
                    COUNT(CASE WHEN public_structures.is_archived = 0 AND public_structures.energy_system_type_id = 4 THEN 1 END)
                    as sum_SMG'),
                'grid_community_compounds.electricity_room',
                'grid_community_compounds.grid',
                DB::raw('COUNT(CASE WHEN all_households.is_archived = 0 AND all_households.household_status_id = 1 THEN 1 END) as sum_inital'),
                DB::raw('COUNT(CASE WHEN all_households.household_status_id = 3 THEN 1 END) as sum_AC'),
                DB::raw('COUNT(CASE WHEN all_energy_meters.meter_case_id = 1 AND all_energy_meters.energy_system_type_id != 2 
                    THEN 1 END) as sum_DC_MG'),
                DB::raw('COUNT(CASE WHEN all_energy_meters.meter_case_id = 1 AND all_energy_meters.energy_system_type_id = 2 
                    THEN 1 END) as sum_DC_FBS'),
                DB::raw('COUNT(CASE WHEN all_energy_meters.is_main = "No" THEN 1 END) as sum_shared_household'),

                DB::raw('COUNT(CASE WHEN all_energy_meters.household_id = NULL AND all_energy_meters.meter_case_id = 1 AND 
                    all_energy_meters.energy_system_type_id != 2 THEN 1 END) as sum_public_MG'),
                DB::raw('COUNT(CASE WHEN all_energy_meters.household_id = NULL AND all_energy_meters.meter_case_id = 1 AND 
                    all_energy_meters.energy_system_type_id = 2 THEN 1 END) as sum_public_FBS'),

                DB::raw('COUNT(CASE WHEN all_energy_meters.meter_case_id = 1 AND all_energy_meters.energy_system_type_id != 2 THEN 1 END) +
                    COUNT(CASE WHEN all_energy_meters.meter_case_id = 1 AND all_energy_meters.energy_system_type_id = 2 
                    THEN 1 END) + 
                    COUNT(CASE WHEN all_energy_meters.is_main = "No" THEN 1 END) +
                    COUNT(CASE WHEN all_energy_meters.household_id = NULL AND all_energy_meters.meter_case_id = 1 AND 
                    all_energy_meters.energy_system_type_id != 2 THEN 1 END) +
                    COUNT(CASE WHEN all_energy_meters.household_id = NULL AND all_energy_meters.meter_case_id = 1 AND 
                    all_energy_meters.energy_system_type_id = 2 THEN 1 END)
                '),

                DB::raw('COALESCE(
                COUNT(CASE WHEN all_energy_meters.meter_case_id = 1 AND all_energy_meters.energy_system_type_id != 2 
                THEN 1 END) + 
                COUNT(CASE WHEN all_energy_meters.meter_case_id = 1 AND all_energy_meters.energy_system_type_id = 2 
                THEN 1 END) +
                COUNT(CASE WHEN all_energy_meters.is_main = "No" THEN 1 END) +
                COUNT(CASE WHEN all_energy_meters.household_id IS NULL AND all_energy_meters.meter_case_id = 1 AND 
                all_energy_meters.energy_system_type_id != 2 THEN 1 END) +
                COUNT(CASE WHEN all_energy_meters.household_id IS NULL AND all_energy_meters.meter_case_id = 1 AND 
                all_energy_meters.energy_system_type_id = 2 THEN 1 END) -
                (
                    COUNT(CASE WHEN all_energy_types.id = 2 THEN 1 END) + 
                    COUNT(CASE WHEN public_structures.is_archived = 0 AND public_structures.energy_system_type_id = 2 THEN 1 END) +
                    COUNT(CASE WHEN all_energy_types.id = 1 THEN 1 END)  + 
                    COUNT(CASE WHEN public_structures.is_archived = 0 AND public_structures.energy_system_type_id = 1 THEN 1 END) +
                    COUNT(CASE WHEN all_energy_types.id = 4 THEN 1 END)  + 
                    COUNT(CASE WHEN public_structures.is_archived = 0 AND public_structures.energy_system_type_id = 4 THEN 1 END)
                ), 0) AS delta'), 

                DB::raw('COUNT(DISTINCT CASE 
                    WHEN refrigerator_holders.id IS NOT NULL AND all_households.is_archived = 0 
                    THEN all_households.id  END) as total_refrigerators'
                ),

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
            ->leftJoin('public_structures', 'public_structures.compound_id', 'compounds.id')
            ->leftJoin('all_energy_meters as public_meters', 'public_meters.public_structure_id', 'public_structures.id')
            ->leftJoin('refrigerator_holders', 'households.id', 'refrigerator_holders.household_id') 
            ->where('communities.is_archived', 0)
            ->where('compounds.is_archived', 0)
            //->where('households.is_archived', 0)
            //->where('compound_households.is_archived', 0)
            //->where('communities.energy_system_cycle_id', $cycleYear)
            ->where('households.household_status_id', '!=', $householdStatus->id)
            ->select(
                'compounds.english_name',    
                'regions.english_name as region',
                DB::raw('false as unneeded'),
                DB::raw('COUNT(DISTINCT CASE WHEN households.is_archived = 0 AND households.energy_system_type_id = 2 
                    THEN households.id END) + 
                    COUNT(DISTINCT CASE WHEN public_structures.is_archived = 0 AND public_structures.energy_system_type_id = 2 
                    THEN public_structures.id END) as sum_FBS'),
                DB::raw('COUNT(DISTINCT CASE WHEN households.is_archived = 0 AND households.energy_system_type_id = 1 
                    THEN households.id END) + 
                    COUNT(DISTINCT CASE WHEN public_structures.is_archived = 0 AND public_structures.energy_system_type_id = 1 
                    THEN public_structures.id END) as sum_MG'),
                DB::raw('COUNT(DISTINCT CASE WHEN households.is_archived = 0 AND households.energy_system_type_id = 4 THEN 
                    households.id END) +
                    COUNT(DISTINCT CASE WHEN public_structures.is_archived = 0 AND public_structures.energy_system_type_id = 4 
                    THEN public_structures.id END) as sum_SMG'),
                'grid_community_compounds.electricity_room',
                'grid_community_compounds.grid', 
                DB::raw('COUNT(CASE WHEN households.is_archived = 0 AND households.household_status_id = 1 THEN 1 END) as sum_inital'),
                DB::raw('COUNT(CASE WHEN households.is_archived = 0 AND households.household_status_id = 3 THEN households.id END) as sum_AC'),
                DB::raw('COUNT(DISTINCT CASE WHEN all_energy_meters.is_archived = 0 AND all_energy_meters.meter_case_id = 1 AND all_energy_meters.energy_system_type_id != 2 
                    THEN households.id END) as sum_DC_MG'),
                DB::raw('COUNT(DISTINCT CASE WHEN all_energy_meters.is_archived = 0 AND all_energy_meters.meter_case_id = 1 AND all_energy_meters.energy_system_type_id = 2 
                    THEN households.id END) as sum_DC_FBS'),
                DB::raw('COUNT(CASE WHEN households.is_archived = 0 AND all_energy_meters.is_main = "No" THEN 1 END) as sum_shared_household'),
                
                DB::raw('COUNT(DISTINCT CASE WHEN public_meters.meter_case_id = 1 AND public_meters.energy_system_type_id != 2 
                    THEN public_structures.id END) as sum_public_MG'),
                DB::raw('COUNT(DISTINCT CASE WHEN public_meters.meter_case_id = 1 AND public_meters.energy_system_type_id = 2 
                    THEN public_structures.id END) as sum_public_FBS'),
                    
                DB::raw('COUNT(DISTINCT CASE WHEN all_energy_meters.is_archived = 0 AND all_energy_meters.meter_case_id = 1 AND all_energy_meters.energy_system_type_id != 2 
                    THEN households.id END) + 
                    COUNT(DISTINCT CASE WHEN all_energy_meters.is_archived = 0 AND all_energy_meters.meter_case_id = 1 AND all_energy_meters.energy_system_type_id = 2 
                    THEN households.id END) + 
                    COUNT(CASE WHEN households.is_archived = 0 AND all_energy_meters.is_main = "No" THEN 1 END) +
                    COUNT(DISTINCT CASE WHEN public_meters.meter_case_id = 1 AND public_meters.energy_system_type_id != 2 
                    THEN public_structures.id END) +
                    COUNT(DISTINCT CASE WHEN public_meters.meter_case_id = 1 AND public_meters.energy_system_type_id = 2 
                    THEN public_structures.id END)
                    '),
                DB::raw('(
                    COUNT(DISTINCT CASE WHEN all_energy_meters.is_archived = 0 AND all_energy_meters.meter_case_id = 1 AND all_energy_meters.energy_system_type_id != 2 
                    THEN households.id END) +
                    COUNT(DISTINCT CASE WHEN all_energy_meters.is_archived = 0 AND all_energy_meters.meter_case_id = 1 AND all_energy_meters.energy_system_type_id = 2 
                    THEN households.id END) +
                    COUNT(CASE WHEN households.is_archived = 0 AND all_energy_meters.is_main = "No" THEN 1 END) +
                    COUNT(DISTINCT CASE WHEN public_meters.meter_case_id = 1 AND public_meters.energy_system_type_id != 2 
                    THEN public_structures.id END) +
                    COUNT(DISTINCT CASE WHEN public_meters.meter_case_id = 1 AND public_meters.energy_system_type_id = 2 
                    THEN public_structures.id END) -
                    (
                        COUNT(DISTINCT CASE WHEN households.is_archived = 0 AND households.energy_system_type_id = 2 
                        THEN households.id END) + 
                        COUNT(DISTINCT CASE WHEN public_structures.is_archived = 0 AND public_structures.energy_system_type_id = 2 
                        THEN public_structures.id END) +
                        COUNT(DISTINCT CASE WHEN households.is_archived = 0 AND households.energy_system_type_id = 1 
                        THEN households.id END) + 
                        COUNT(DISTINCT CASE WHEN public_structures.is_archived = 0 AND public_structures.energy_system_type_id = 1 
                        THEN public_structures.id END) +
                        COUNT(DISTINCT CASE WHEN households.is_archived = 0 AND households.energy_system_type_id = 4 
                        THEN households.id END) +
                        COUNT(DISTINCT CASE WHEN public_structures.is_archived = 0 AND public_structures.energy_system_type_id = 4 
                        THEN public_structures.id END)
                    ) + 0 
                ) AS delta'),

                DB::raw('COUNT(DISTINCT CASE 
                    WHEN refrigerator_holders.id IS NOT NULL AND households.is_archived = 0 
                    THEN households.id  END) as total_refrigerators'
                ),
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
            $queryCompounds->where("compounds.energy_system_cycle_id", $this->request->energy_cycle_id);
            $this->misc->where("households.energy_system_cycle_id", $this->request->energy_cycle_id);
            $this->miscHouseholds->where("households.energy_system_cycle_id", $this->request->energy_cycle_id);
            $this->activateMisc->where("households.energy_system_cycle_id", $this->request->energy_cycle_id);
            $this->relocatedHouseholds->where("all_energy_meters.energy_system_cycle_id", $this->request->energy_cycle_id);
            $this->activateRelocated->where("all_energy_meters.energy_system_cycle_id", $this->request->energy_cycle_id);
            //$this->requestedHouseholds->where("communities.energy_system_cycle_id", $this->request->energy_cycle_id);
            
            $cycleId = (int) $this->request->energy_cycle_id;

            if ($cycleId > 0) {

                $baseYear = 2023;
                $startYear = $baseYear + ($cycleId - 1);
                $endYear = $startYear + 1;

                $startDate = "$startYear-04-01"; // April 1st
                $endDate = "$endYear-03-31"; // March 31st of next year

                $this->relocatedRefrigerator->where("households.energy_system_cycle_id", $this->request->energy_cycle_id)
                    ->whereBetween('refrigerator_holders.date', [$startDate, $endDate]);

                $this->miscRefrigerator->where("households.energy_system_cycle_id", $this->request->energy_cycle_id)
                    ->whereBetween('refrigerator_holders.date', [$startDate, $endDate]);
            }
        }
        
        $communitiesCollection = $queryCommunities->get()->map(function($item) {
        
            $item->delta = $item->delta == 0 ? "0" : $item->delta;

            return $item;
        });

        $compoundsCollection = $queryCompounds->get()->map(function($item) {
          
            $item->delta = $item->delta == 0 ? "0" : $item->delta;

            return $item;
        });

        
        $this->misc = $this->misc->count();
        $this->miscHouseholds = $this->miscHouseholds->count();
        $this->activateMisc = $this->activateMisc->count();
        $this->requestedHouseholds = $this->requestedHouseholds->count();
        $this->relocatedHouseholds = $this->relocatedHouseholds->count();
        $this->activateRelocated = $this->activateRelocated->count();
        $this->miscRefrigerator = $this->miscRefrigerator->count();
        $this->relocatedRefrigerator = $this->relocatedRefrigerator->count();

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
        $sheet->setAutoFilter('A1:R1');
        $sheet->setCellValue('A1', 'Name');   
        $sheet->setCellValue('B1', 'Geographical Region'); 
        $sheet->setCellValue('C1', 'Total Confirmed / Relecoated');  
        $sheet->setCellValue('D1', 'FBS # confirmed meters'); 
        $sheet->setCellValue('E1', 'MG # confirmed meters');  
        $sheet->setCellValue('F1', 'SMG # confirmed meters'); 
        $sheet->setCellValue('G1', 'Electricity Room'); 
        $sheet->setCellValue('H1', 'Grid'); 
        $sheet->setCellValue('I1', 'Initial Households/Public'); 
        $sheet->setCellValue('J1', 'Completed AC'); 
        $sheet->setCellValue('K1', 'Activate Meter MG'); // household_status is in-progress
        $sheet->setCellValue('L1', 'Activate Meter FBS'); // household_status is served// MG
        $sheet->setCellValue('M1', 'Shared Households');
        $sheet->setCellValue('N1', 'Public Structures MG');
        $sheet->setCellValue('O1', 'Public Structures FBS');
        $sheet->setCellValue('P1', 'Served');
        $sheet->setCellValue('Q1', 'Delta');
        $sheet->setCellValue('R1', 'Refrigerator');

        $sheet->setCellValue('A2', 'MISC Households');
        $sheet->setCellValue('A3', 'MISC FBS Users');
        $sheet->setCellValue('A4', 'Relocated Households');  
        //$sheet->setCellValue('A4', 'Requested Households');     
        $sheet->setCellValue('B2', ' '); 
        $sheet->setCellValue('B3', ' ');
        $sheet->setCellValue('B4', ' ');
        $sheet->setCellValue('C2', $this->miscHouseholds);
        $sheet->setCellValue('C3', $this->misc);
        $sheet->setCellValue('C4', $this->relocatedHouseholds);
        //$sheet->setCellValue('C4', $this->requestedHouseholds);
        
        $sheet->setCellValue('L2', '');
        $sheet->setCellValue('L3', $this->activateMisc);
        $sheet->setCellValue('L4', $this->activateRelocated);

        $sheet->setCellValue('Q2', '');
        $sheet->setCellValue('Q3', ($this->misc - $this->activateMisc));
        $sheet->setCellValue('Q4', ($this->relocatedHouseholds -$this->activateRelocated));

        $sheet->setCellValue('P2', '');
        $sheet->setCellValue('P3', ($this->activateMisc));
        $sheet->setCellValue('P4', ($this->activateRelocated));

        $sheet->setCellValue('R2', '');
        $sheet->setCellValue('R3', ($this->miscRefrigerator));
        $sheet->setCellValue('R4', ($this->relocatedRefrigerator));

        // Adding the summation row
        $lastRow = $sheet->getHighestRow() + 1;
        $sheet->setCellValue('A'.$lastRow, 'Total');
        $sheet->setCellValue('C'.$lastRow, '=SUM(C2:C'.($lastRow-1).')');
        $sheet->setCellValue('D'.$lastRow, '=SUM(D2:D'.($lastRow-1).')');
        $sheet->setCellValue('E'.$lastRow, '=SUM(E2:E'.($lastRow-1).')');
        $sheet->setCellValue('F'.$lastRow, '=SUM(F2:F'.($lastRow-1).')');
        $sheet->setCellValue('I'.$lastRow, '=SUM(I2:I'.($lastRow-1).')');
        $sheet->setCellValue('J'.$lastRow, '=SUM(J2:J'.($lastRow-1).')');
        $sheet->setCellValue('K'.$lastRow, '=SUM(K2:K'.($lastRow-1).')');
        $sheet->setCellValue('L'.$lastRow, '=SUM(L2:L'.($lastRow-1).')');
        $sheet->setCellValue('M'.$lastRow, '=SUM(M2:M'.($lastRow-1).')');
        $sheet->setCellValue('N'.$lastRow, '=SUM(N2:N'.($lastRow-1).')');
        $sheet->setCellValue('O'.$lastRow, '=SUM(O2:O'.($lastRow-1).')');
        $sheet->setCellValue('P'.$lastRow, '=SUM(P2:P'.($lastRow-1).')');
        $sheet->setCellValue('Q'.$lastRow, '=SUM(Q2:Q'.($lastRow-1).')');
        $sheet->setCellValue('R'.$lastRow, '=SUM(R2:R'.($lastRow-1).')');

        // Confirmed 
        $sheet->getStyle('D1:D' . ($lastRow - 1))->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet->getStyle('D1:D' . ($lastRow - 1))->getFill()->setStartColor(new Color('ADD8E6'));
        $sheet->getStyle('D1:D' . ($lastRow - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle('D1:D' . ($lastRow - 1))->getBorders()->getAllBorders()->setColor(new Color('000000'));

        $sheet->getStyle('E1:E' . ($lastRow - 1))->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet->getStyle('E1:E' . ($lastRow - 1))->getFill()->setStartColor(new Color('ADD8E6'));
        $sheet->getStyle('E1:E' . ($lastRow - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle('E1:E' . ($lastRow - 1))->getBorders()->getAllBorders()->setColor(new Color('000000'));

        $sheet->getStyle('F1:F' . ($lastRow - 1))->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet->getStyle('F1:F' . ($lastRow - 1))->getFill()->setStartColor(new Color('ADD8E6'));
        $sheet->getStyle('F1:F' . ($lastRow - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle('F1:F' . ($lastRow - 1))->getBorders()->getAllBorders()->setColor(new Color('000000'));

        // Initial
        $sheet->getStyle('I1:I' . ($lastRow - 1))->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet->getStyle('I1:I' . ($lastRow - 1))->getFill()->setStartColor(new Color('e6e6ff'));
        $sheet->getStyle('I1:I' . ($lastRow - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle('I1:I' . ($lastRow - 1))->getBorders()->getAllBorders()->setColor(new Color('000000'));

        // AC Completed
        $sheet->getStyle('J1:J' . ($lastRow - 1))->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet->getStyle('J1:J' . ($lastRow - 1))->getFill()->setStartColor(new Color('e6e600'));
        $sheet->getStyle('J1:J' . ($lastRow - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle('J1:J' . ($lastRow - 1))->getBorders()->getAllBorders()->setColor(new Color('000000'));
        
        // Served
        $sheet->getStyle('P1:P' . ($lastRow - 1))->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet->getStyle('P1:P' . ($lastRow - 1))->getFill()->setStartColor(new Color('86af49'));
        $sheet->getStyle('P1:P' . ($lastRow - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle('P1:P' . ($lastRow - 1))->getBorders()->getAllBorders()->setColor(new Color('000000'));

        // Delta
        $sheet->getStyle('Q1:Q' . ($lastRow - 1))->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet->getStyle('Q1:Q' . ($lastRow - 1))->getFill()->setStartColor(new Color('e60000'));
        $sheet->getStyle('Q1:Q' . ($lastRow - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle('Q1:Q' . ($lastRow - 1))->getBorders()->getAllBorders()->setColor(new Color('000000'));

        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true, 'size' => 12]],
            // Optionally, you can style the total row as well
            $lastRow => ['font' => ['bold' => true, 'size' => 12]]
        ];
    }

}