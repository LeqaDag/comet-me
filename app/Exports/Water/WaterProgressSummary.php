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

class EnergyRequestedSummary implements FromCollection, WithTitle, ShouldAutoSize, 
    WithStyles, WithEvents,WithCustomStartCell
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

    } 

    public function startCell(): string
    {
        return 'A4';
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
        $sheet->setCellValue('A1', 'Name');   
        $sheet->setCellValue('B1', 'Geographical Region'); 
        $sheet->setCellValue('C1', 'FBS # confirmed'); 
        $sheet->setCellValue('D1', 'MG # confirmed meters'); 
        $sheet->setCellValue('E1', 'SMG # confirmed meters'); 
        $sheet->setCellValue('F1', 'Electricity Room'); 
        $sheet->setCellValue('G1', 'Grid'); 
        $sheet->setCellValue('H1', 'Initial Households/Public'); 
        $sheet->setCellValue('I1', 'Completed AC'); // household_status is in-progress
        $sheet->setCellValue('J1', 'Activate Meter MG'); // household_status is served// MG
        $sheet->setCellValue('K1', 'Activate Meter FBS');
        $sheet->setCellValue('L1', 'Shared Households');
        $sheet->setCellValue('M1', 'Public Structures MG');
        $sheet->setCellValue('N1', 'Public Structures FBS');
        $sheet->setCellValue('O1', 'Served');
        $sheet->setCellValue('P1', 'Delta');
        $sheet->setCellValue('Q1', 'Refrigerator');

        $sheet->setCellValue('A2', 'MISC FBS');  
        $sheet->setCellValue('A3', 'Relocated Households');  
        //$sheet->setCellValue('A4', 'Requested Households');     
        $sheet->setCellValue('B2', ' ');       
        $sheet->setCellValue('B3', ' ');      
        $sheet->setCellValue('C2', $this->misc);
        $sheet->setCellValue('C3', $this->relocatedHouseholds);
        //$sheet->setCellValue('C4', $this->requestedHouseholds);
        
        $sheet->setCellValue('K2', $this->activateMisc);
        $sheet->setCellValue('K3', $this->activateRelocated);

        $sheet->setCellValue('P2', ($this->misc - $this->activateMisc));
        $sheet->setCellValue('P3', ($this->relocatedHouseholds -$this->activateRelocated));

        $sheet->setCellValue('O2', ($this->activateMisc));
        $sheet->setCellValue('O3', ($this->activateRelocated));

        $sheet->setCellValue('Q2', ($this->miscRefrigerator));
        $sheet->setCellValue('Q3', ($this->relocatedRefrigerator));

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
        $sheet->setCellValue('M'.$lastRow, '=SUM(M2:M'.($lastRow-1).')');
        $sheet->setCellValue('N'.$lastRow, '=SUM(N2:N'.($lastRow-1).')');
        $sheet->setCellValue('O'.$lastRow, '=SUM(O2:O'.($lastRow-1).')');
        $sheet->setCellValue('P'.$lastRow, '=SUM(P2:P'.($lastRow-1).')');

        // Confirmed 
        $sheet->getStyle('C1:C' . ($lastRow - 1))->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet->getStyle('C1:C' . ($lastRow - 1))->getFill()->setStartColor(new Color('ADD8E6'));
        $sheet->getStyle('C1:C' . ($lastRow - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle('C1:C' . ($lastRow - 1))->getBorders()->getAllBorders()->setColor(new Color('000000'));

        $sheet->getStyle('D1:D' . ($lastRow - 1))->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet->getStyle('D1:D' . ($lastRow - 1))->getFill()->setStartColor(new Color('ADD8E6'));
        $sheet->getStyle('D1:D' . ($lastRow - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle('D1:D' . ($lastRow - 1))->getBorders()->getAllBorders()->setColor(new Color('000000'));

        $sheet->getStyle('E1:E' . ($lastRow - 1))->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet->getStyle('E1:E' . ($lastRow - 1))->getFill()->setStartColor(new Color('ADD8E6'));
        $sheet->getStyle('E1:E' . ($lastRow - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle('E1:E' . ($lastRow - 1))->getBorders()->getAllBorders()->setColor(new Color('000000'));

        // Initial
        $sheet->getStyle('H1:H' . ($lastRow - 1))->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet->getStyle('H1:H' . ($lastRow - 1))->getFill()->setStartColor(new Color('e6e6ff'));
        $sheet->getStyle('H1:H' . ($lastRow - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle('H1:H' . ($lastRow - 1))->getBorders()->getAllBorders()->setColor(new Color('000000'));

        // AC Completed
        $sheet->getStyle('I1:I' . ($lastRow - 1))->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet->getStyle('I1:I' . ($lastRow - 1))->getFill()->setStartColor(new Color('e6e600'));
        $sheet->getStyle('I1:I' . ($lastRow - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle('I1:I' . ($lastRow - 1))->getBorders()->getAllBorders()->setColor(new Color('000000'));
        
        // Served
        $sheet->getStyle('O1:O' . ($lastRow - 1))->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet->getStyle('O1:O' . ($lastRow - 1))->getFill()->setStartColor(new Color('86af49'));
        $sheet->getStyle('O1:O' . ($lastRow - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle('O1:O' . ($lastRow - 1))->getBorders()->getAllBorders()->setColor(new Color('000000'));

        // Delta
        $sheet->getStyle('P1:P' . ($lastRow - 1))->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet->getStyle('P1:P' . ($lastRow - 1))->getFill()->setStartColor(new Color('e60000'));
        $sheet->getStyle('P1:P' . ($lastRow - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle('P1:P' . ($lastRow - 1))->getBorders()->getAllBorders()->setColor(new Color('000000'));

        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true, 'size' => 12]],
            // Optionally, you can style the total row as well
            $lastRow => ['font' => ['bold' => true, 'size' => 12]]
        ];
    }

}