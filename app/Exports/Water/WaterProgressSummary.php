<?php

namespace App\Exports\Water;

use App\Models\WaterRequestSystem;
use App\Models\WaterSystemStatus;
use App\Models\WaterSystemCycle;
use App\Models\GridIntegrationType;
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

class WaterProgressSummary implements FromCollection, WithTitle, ShouldAutoSize, 
    WithStyles, WithEvents,WithCustomStartCell
{
    private $newH2oConfirmed = 0, $replaceH2oConfirmed = 0, $gridIntegrationLargeConfirmed = 0, $gridIntegrationSmallConfirmed = 0, 
        $gridIntegrationLargeReplaced = 0, $gridIntegrationSmallReplaced = 0,

        $newH2oPaid = 0, $replaceH2oPaid = 0, $gridIntegrationLargePaid = 0, $gridIntegrationSmallPaid = 0, 
        $newH2oPickedUp = 0, $replaceH2oPickedUp = 0, $gridIntegrationLargePickedUp = 0, $gridIntegrationSmallPickedUp = 0,
        $newH2oInstalled = 0, $replaceH2oInstalled = 0, $gridIntegrationLargeInstalled = 0, $gridIntegrationSmallInstalled = 0,
        $newH2oSharedHousehold = 0, $replaceH2oSharedHousehold = 0, $gridIntegrationLargeSharedHousehold = 0, 
        $gridIntegrationSmallSharedHousehold = 0, $newH2oSharedPublic = 0, $replaceH2oSharedPublic = 0, 
        $gridIntegrationLargeSharedPublic = 0, $gridIntegrationSmallSharedPublic = 0, 
        $newH2oServed = 0, $replaceH2oServed = 0, $gridIntegrationLargeServed = 0, $gridIntegrationSmallServed = 0;

    protected $request; 

    function __construct($request) {

        $this->request = $request; 
    }
 
    /**
    * @return \Illuminate\Support\Collection
    */ 
    public function collection()  
    { 
        $new = "New"; $replacement = "Replacement"; 
        $large = "Grid Integration Large"; $small = "Grid Integration Small";

        $newSystem = WaterSystemStatus::where('status', 'like', '%' . $new. '%')->first(); 
        $replacementSystem = WaterSystemStatus::where('status', 'like', '%' . $replacement. '%')->first(); 

        $gridLarge = GridIntegrationType::where('name', 'like', '%' . $large. '%')->first(); 
        $smallLarge = GridIntegrationType::where('name', 'like', '%' . $small. '%')->first(); 
        
        // New & Integration Large
        $gridIntegrationLargeConfirmed = WaterRequestSystem::where("is_archived", 0)
            ->where("grid_integration_type_id", $gridLarge->id)
            ->where("water_system_status_id", $newSystem->id)
            ->count();

        // New & Integration Small
        $gridIntegrationSmallConfirmed = WaterRequestSystem::where("is_archived", 0)
            ->where("grid_integration_type_id", $smallLarge->id)
            ->where("water_system_status_id", $newSystem->id)
            ->count();

        // Replaced & Integration Large
        $gridIntegrationLargeReplaced = WaterRequestSystem::where("is_archived", 0)
            ->where("grid_integration_type_id", $gridLarge->id)
            ->where("water_system_status_id", $replacementSystem->id)
            ->count();

        // Replaced & Integration Large
        $gridIntegrationSmallReplaced = WaterRequestSystem::where("is_archived", 0)
            ->where("grid_integration_type_id", $smallLarge->id)
            ->where("water_system_status_id", $replacementSystem->id)
            ->count();
        
        // Network systems
        $data = DB::table('water_systems')
            ->join('water_system_types', 'water_system_types.id', 'water_systems.water_system_type_id')
            ->join('communities', 'communities.id', 'water_systems.community_id')
            ->join('households', 'communities.id', 'households.community_id')
            ->whereNotNull('water_systems.water_system_cycle_id')
            ->select(
                'water_systems.name',    
                'water_system_types.type',
                DB::raw('COUNT(CASE WHEN households.is_archived = 0 AND households.water_holder_status_id = 2 THEN 1 END) 
                    as sum_confirmed'), 
                DB::raw('COUNT(CASE WHEN households.is_archived = 0 AND households.water_holder_status_id = 3 THEN 1 END) 
                    as sum_delivered'), 
                DB::raw('COUNT(CASE WHEN households.is_archived = 0 AND households.water_holder_status_id = 4 THEN 1 END) 
                    as sum_in_progress'), 
                DB::raw('COUNT(CASE WHEN households.is_archived = 0 AND households.water_holder_status_id = 5 THEN 1 END) 
                    as sum_completed'), 
                DB::raw('COUNT(CASE WHEN households.is_archived = 0 AND households.water_holder_status_id = 6 THEN 1 END) 
                    as sum_paid'), 
            )->groupBy("water_systems.id");

        die($data->get());
        return $data->get();
    } 

    public function startCell(): string
    {
        return 'A6';
    }

    public function title(): string 
    {
        return 'Water Progress Summary by Cycle';
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
        $sheet->setAutoFilter('A1:P1');
        $sheet->setCellValue('A1', 'System name');   
        $sheet->setCellValue('B1', 'System type'); 
        $sheet->setCellValue('C1', 'New'); 
        $sheet->setCellValue('D1', 'Replaced'); 
        $sheet->setCellValue('E1', 'Total confirmed'); 
        $sheet->setCellValue('F1', 'Paid'); 
        $sheet->setCellValue('G1', 'Picked up'); 
        $sheet->setCellValue('H1', 'Installed'); 
        $sheet->setCellValue('I1', 'Shared Households - household systems');
        $sheet->setCellValue('J1', 'Public Structures Household systems'); 
        $sheet->setCellValue('K1', 'Served');
        $sheet->setCellValue('L1', 'Delta');

        $sheet->setCellValue('A2', 'H2O systems new users');  
        $sheet->setCellValue('A3', 'H2O systems replacement');  
        $sheet->setCellValue('A4', 'GI Large');   
        $sheet->setCellValue('A5', 'GI Small');   
        
        $sheet->setCellValue('B2', 'H2O');  
        $sheet->setCellValue('B3', 'H2O');  
        $sheet->setCellValue('B4', 'Grid Integration');   
        $sheet->setCellValue('B5', 'Grid Integration');  

        $sheet->setCellValue('B2', ' ');       
        $sheet->setCellValue('B3', ' ');      
        $sheet->setCellValue('C4', $this->gridIntegrationLargeConfirmed);
        $sheet->setCellValue('C5', $this->gridIntegrationSmallConfirmed);
        $sheet->setCellValue('D4', $this->gridIntegrationLargeReplaced);
        $sheet->setCellValue('D5', $this->gridIntegrationSmallReplaced);
        $sheet->setCellValue('E4', $this->gridIntegrationLargeReplaced + $this->gridIntegrationLargeConfirmed);
        $sheet->setCellValue('E5', $this->gridIntegrationSmallReplaced + $this->gridIntegrationSmallConfirmed);


        // Adding the summation row
        $lastRow = $sheet->getHighestRow() + 1;
        $sheet->setCellValue('A'.$lastRow, 'Total');
        $sheet->setCellValue('C'.$lastRow, '=SUM(C2:C'.($lastRow-1).')');
        $sheet->setCellValue('D'.$lastRow, '=SUM(D2:D'.($lastRow-1).')');
        $sheet->setCellValue('E'.$lastRow, '=SUM(E2:E'.($lastRow-1).')');
        $sheet->setCellValue('H'.$lastRow, '=SUM(H2:H'.($lastRow-1).')');
        $sheet->setCellValue('I'.$lastRow, '=SUM(I2:I'.($lastRow-1).')');
        $sheet->setCellValue('J'.$lastRow, '=SUM(J2:J'.($lastRow-1).')');
        $sheet->setCellValue('K'.$lastRow, '=SUM(K2:K'.($lastRow-1).')');
        $sheet->setCellValue('L'.$lastRow, '=SUM(L2:L'.($lastRow-1).')');

        // Confirmed 
        $sheet->getStyle('E1:E' . ($lastRow - 1))->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet->getStyle('E1:E' . ($lastRow - 1))->getFill()->setStartColor(new Color('ADD8E6'));
        $sheet->getStyle('E1:E' . ($lastRow - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle('E1:E' . ($lastRow - 1))->getBorders()->getAllBorders()->setColor(new Color('000000'));

        // Paid
        $sheet->getStyle('F1:F' . ($lastRow - 1))->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet->getStyle('F1:F' . ($lastRow - 1))->getFill()->setStartColor(new Color('ADD8E6'));
        $sheet->getStyle('F1:F' . ($lastRow - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle('F1:F' . ($lastRow - 1))->getBorders()->getAllBorders()->setColor(new Color('000000'));

        // Picked up
        $sheet->getStyle('G1:G' . ($lastRow - 1))->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet->getStyle('G1:G' . ($lastRow - 1))->getFill()->setStartColor(new Color('ADD8E6'));
        $sheet->getStyle('G1:G' . ($lastRow - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle('G1:G' . ($lastRow - 1))->getBorders()->getAllBorders()->setColor(new Color('000000'));

        // Installed
        $sheet->getStyle('H1:H' . ($lastRow - 1))->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet->getStyle('H1:H' . ($lastRow - 1))->getFill()->setStartColor(new Color('e6e6ff'));
        $sheet->getStyle('H1:H' . ($lastRow - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle('H1:H' . ($lastRow - 1))->getBorders()->getAllBorders()->setColor(new Color('000000'));

        // Served
        $sheet->getStyle('K1:K' . ($lastRow - 1))->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet->getStyle('K1:K' . ($lastRow - 1))->getFill()->setStartColor(new Color('86af49'));
        $sheet->getStyle('K1:K' . ($lastRow - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle('K1:K' . ($lastRow - 1))->getBorders()->getAllBorders()->setColor(new Color('000000'));

        // Delta
        $sheet->getStyle('L1:L' . ($lastRow - 1))->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet->getStyle('L1:L' . ($lastRow - 1))->getFill()->setStartColor(new Color('e60000'));
        $sheet->getStyle('L1:L' . ($lastRow - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle('L1:L' . ($lastRow - 1))->getBorders()->getAllBorders()->setColor(new Color('000000'));

        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true, 'size' => 12]],
            // Optionally, you can style the total row as well
            $lastRow => ['font' => ['bold' => true, 'size' => 12]]
        ];
    }

}