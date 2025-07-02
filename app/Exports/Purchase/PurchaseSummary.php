<?php

namespace App\Exports\Purchase;

use App\Models\AllEnergyPurchaseMeter;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents; 
use DB;
use Carbon\Carbon;

class PurchaseSummary implements FromCollection, WithTitle, ShouldAutoSize, 
    WithStyles, WithCustomStartCell, WithEvents
{
    private $totalMeters = 0;

    // Days
    private $group1To90 = 0, $group91To180 = 0, $group181To270 = 0, $group271To360 = 0, $group361To720 = 0, 
        $group721To1080 = 0, $group1081To1440 = 0, $group1441To1800 = 0, $group1801To2160 = 0, 
        $group2161To2420 = 0, $group2421To2780 = 0, $groupGraet2781 = 0;


    protected $request;

    function __construct($request) {

        $this->request = $request; 
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    { 
        
        $this->totalMeters = AllEnergyPurchaseMeter::count();

        $this->group1To90 = AllEnergyPurchaseMeter::where("days1", ">=", 1)
            ->where("days1", "<=", 90)
            ->count();

        $this->group91To180 = AllEnergyPurchaseMeter::where("days1", ">=", 91)
            ->where("days1", "<=", 180)
            ->count();

        $this->group181To270 = AllEnergyPurchaseMeter::where("days1", ">=", 181)
            ->where("days1", "<=", 270)
            ->count();

        $this->group271To360 = AllEnergyPurchaseMeter::where("days1", ">=", 271)
            ->where("days1", "<=", 360)
            ->count();

        $this->group361To720 = AllEnergyPurchaseMeter::where("days1", ">=", 361)
            ->where("days1", "<=", 720)
            ->count();

        $this->group721To1080 = AllEnergyPurchaseMeter::where("days1", ">=", 721)
            ->where("days1", "<=", 1080)
            ->count();

        $this->group1081To1440 = AllEnergyPurchaseMeter::where("days1", ">=", 1081)
            ->where("days1", "<=", 1440)
            ->count();

        $this->group1441To1800 = AllEnergyPurchaseMeter::where("days1", ">=", 1441)
            ->where("days1", "<=", 1800)
            ->count();

        $this->group1801To2160 = AllEnergyPurchaseMeter::where("days1", ">=", 1801)
            ->where("days1", "<=", 2160)
            ->count();

        $this->group2161To2420 = AllEnergyPurchaseMeter::where("days1", ">=", 2161)
            ->where("days1", "<=", 2420)
            ->count();

        $this->group2421To2780 = AllEnergyPurchaseMeter::where("days1", ">=", 2421)
            ->where("days1", "<=", 2780)
            ->count();

        $this->groupGraet2781 = AllEnergyPurchaseMeter::where("days1", ">=", 2781)
            ->count();

        $data = [
            [
                'Total Meters' => $this->totalMeters,
            ],
        ];
    
        return collect($data); 
    }


    public function title(): string
    {
        return 'Purchase Summary';
    }

    public function startCell(): string
    {
        return 'A5';
    }

     /**
     * Write code on Method
     *
     * @return response()
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
   
                $event->sheet->getDelegate()->getStyle('A1:J1')
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                $event->sheet->getDelegate()->getStyle('A8:C8')
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
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
        $sheet->mergeCells('A1:C1');
        $sheet->mergeCells('A2:C2');
        $sheet->mergeCells('D1:E1');
        $sheet->mergeCells('D2:E2');

        $sheet->setCellValue('A1', 'Date of Report');
        $sheet->setCellValue('A2', 'Total Meters in report');

        $sheet->setCellValue('D1', Date(('2025-05-01')));
        $sheet->setCellValue('D2', $this->totalMeters);


        return [
            // Style the first row as bold text.
            1  => ['font' => ['bold' => true, 'size' => 12]],
            2  => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}