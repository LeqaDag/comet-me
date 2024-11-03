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
use DB;

class EnergyServedUsersByCommunity implements FromCollection, WithTitle, ShouldAutoSize, 
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
            ->leftJoin('public_structures', 'public_structures.community_id', 'communities.id')
            ->leftJoin('community_donors', 'community_donors.community_id', 'communities.id')
            ->leftJoin('donors', 'community_donors.donor_id', 'donors.id')
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
                DB::raw('COUNT(CASE WHEN all_energy_meters.meter_case_id = 1 AND all_energy_meters.energy_system_type_id != 2 
                    THEN 1 END) as sum_DC_MG'),
                DB::raw('COUNT(CASE WHEN all_energy_meters.meter_case_id = 1 AND all_energy_meters.energy_system_type_id = 2 
                    THEN 1 END) as sum_DC_FBS'),
                DB::raw('COUNT(CASE WHEN all_energy_meters.is_main = "No" THEN 1 END) as sum_shared_household'),

                DB::raw('COUNT(CASE WHEN all_energy_meters.household_id = NULL AND all_energy_meters.meter_case_id = 1 AND 
                    all_energy_meters.energy_system_type_id != 2 THEN 1 END) as sum_public_MG'),
                DB::raw('COUNT(CASE WHEN all_energy_meters.household_id = NULL AND all_energy_meters.meter_case_id = 1 AND 
                    all_energy_meters.energy_system_type_id = 2 THEN 1 END) as sum_public_FBS'),

                DB::raw('COUNT(CASE WHEN all_energy_meters.meter_case_id = 1 AND all_energy_meters.energy_system_type_id != 2 
                    THEN 1 END) + 
                    COUNT(CASE WHEN all_energy_meters.meter_case_id = 1 AND all_energy_meters.energy_system_type_id = 2 
                    THEN 1 END) +
                    COUNT(CASE WHEN all_energy_meters.is_main = "No" THEN 1 END) as total_households'),
                
                DB::raw('COUNT(CASE WHEN all_energy_meters.household_id = NULL AND all_energy_meters.meter_case_id = 1 AND 
                    all_energy_meters.energy_system_type_id != 2 THEN 1 END) +
                    COUNT(CASE WHEN all_energy_meters.household_id = NULL AND all_energy_meters.meter_case_id = 1 AND 
                    all_energy_meters.energy_system_type_id = 2 THEN 1 END) as total_public'),

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

                DB::raw('group_concat(DISTINCT CASE WHEN community_donors.is_archived = 0 AND community_donors.service_id = 1
                    THEN donors.donor_name END) as donors')
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
            ->leftJoin('community_donors', 'community_donors.compound_id', 'compounds.id')
            ->leftJoin('donors', 'community_donors.donor_id', 'donors.id')
            ->where('communities.is_archived', 0)
            ->where('compounds.is_archived', 0)
            //->where('households.is_archived', 0)
            ->where('compound_households.is_archived', 0)
            ->where('all_energy_meters.is_archived', 0)
            ->whereNotNull('communities.energy_system_cycle_id')
            ->select(
                'compounds.english_name',    
                'regions.english_name as region',
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
                    COUNT(CASE WHEN households.is_archived = 0 AND all_energy_meters.is_main = "No" THEN 1 END)
                    as total_households'),
                    
                DB::raw('COUNT(DISTINCT CASE WHEN public_meters.meter_case_id = 1 AND public_meters.energy_system_type_id != 2 
                    THEN public_structures.id END) +
                    COUNT(DISTINCT CASE WHEN public_meters.meter_case_id = 1 AND public_meters.energy_system_type_id = 2 
                    THEN public_structures.id END) as total_public'),    

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
                
                DB::raw('GROUP_CONCAT(DISTINCT CASE WHEN community_donors.is_archived = 0 AND community_donors.service_id = 1
                    THEN donors.donor_name END) as donors')
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
            $this->relocatedHouseholds->where("communities.energy_system_cycle_id", $this->request->energy_cycle_id);
            $this->activateRelocated->where("communities.energy_system_cycle_id", $this->request->energy_cycle_id);
            $this->requestedHouseholds->where("communities.energy_system_cycle_id", $this->request->energy_cycle_id);
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
        $this->activateMisc = $this->activateMisc->count();
        $this->requestedHouseholds = $this->requestedHouseholds->count();
        $this->relocatedHouseholds = $this->relocatedHouseholds->count();
        $this->activateRelocated = $this->activateRelocated->count();

        return $compoundsCollection->merge($communitiesCollection);
    } 

    public function startCell(): string
    {
        return 'A4';
    }

    public function title(): string 
    {
        return 'Served Holders by community';
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
        $sheet->setAutoFilter('A1:K1');
        $sheet->setCellValue('A1', 'Name');   
        $sheet->setCellValue('B1', 'Geographical Region'); 
        $sheet->setCellValue('C1', 'Activated FBS meters'); 
        $sheet->setCellValue('D1', 'Activated MG meters');  
        $sheet->setCellValue('E1', 'Shared Households');
        $sheet->setCellValue('F1', 'Public Structures MG');
        $sheet->setCellValue('G1', 'Public Structures FBS');
        $sheet->setCellValue('H1', 'Total Households');
        $sheet->setCellValue('I1', 'Total Public Structures');
        $sheet->setCellValue('J1', 'Delta');
        $sheet->setCellValue('K1', 'Donors');
    
        $sheet->setCellValue('B2', ' ');        


        // Adding the summation row
        $lastRow = $sheet->getHighestRow() + 1;
        $sheet->setCellValue('A'.$lastRow, 'Total');
        $sheet->setCellValue('C'.$lastRow, '=SUM(C2:C'.($lastRow-1).')');
        $sheet->setCellValue('D'.$lastRow, '=SUM(D2:D'.($lastRow-1).')');
        $sheet->setCellValue('E'.$lastRow, '=SUM(E2:E'.($lastRow-1).')');
        $sheet->setCellValue('F'.$lastRow, '=SUM(F2:F'.($lastRow-1).')');
        $sheet->setCellValue('G'.$lastRow, '=SUM(G2:G'.($lastRow-1).')');
        $sheet->setCellValue('H'.$lastRow, '=SUM(H2:H'.($lastRow-1).')');
        $sheet->setCellValue('I'.$lastRow, '=SUM(I2:I'.($lastRow-1).')');
        $sheet->setCellValue('J'.$lastRow, '=SUM(J2:J'.($lastRow-1).')');

        // Confirmed 
        $sheet->getStyle('C1:C' . ($lastRow - 1))->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet->getStyle('C1:C' . ($lastRow - 1))->getFill()->setStartColor(new Color('ADD8E6'));
        $sheet->getStyle('C1:C' . ($lastRow - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle('C1:C' . ($lastRow - 1))->getBorders()->getAllBorders()->setColor(new Color('000000'));

        $sheet->getStyle('D1:D' . ($lastRow - 1))->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet->getStyle('D1:D' . ($lastRow - 1))->getFill()->setStartColor(new Color('ADD8E6'));
        $sheet->getStyle('D1:D' . ($lastRow - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle('D1:D' . ($lastRow - 1))->getBorders()->getAllBorders()->setColor(new Color('000000'));

        $sheet->getStyle('H1:H' . ($lastRow - 1))->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet->getStyle('H1:H' . ($lastRow - 1))->getFill()->setStartColor(new Color('e6e6ff'));
        $sheet->getStyle('H1:H' . ($lastRow - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle('H1:H' . ($lastRow - 1))->getBorders()->getAllBorders()->setColor(new Color('000000'));

        $sheet->getStyle('I1:I' . ($lastRow - 1))->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet->getStyle('I1:I' . ($lastRow - 1))->getFill()->setStartColor(new Color('e6e600'));
        $sheet->getStyle('I1:I' . ($lastRow - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle('I1:I' . ($lastRow - 1))->getBorders()->getAllBorders()->setColor(new Color('000000'));

        // Delta
        $sheet->getStyle('J1:J' . ($lastRow - 1))->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet->getStyle('J1:J' . ($lastRow - 1))->getFill()->setStartColor(new Color('e60000'));
        $sheet->getStyle('J1:J' . ($lastRow - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle('J1:J' . ($lastRow - 1))->getBorders()->getAllBorders()->setColor(new Color('000000'));

        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true, 'size' => 12]],
            // Optionally, you can style the total row as well
            $lastRow => ['font' => ['bold' => true, 'size' => 12]]
        ];
    }

}