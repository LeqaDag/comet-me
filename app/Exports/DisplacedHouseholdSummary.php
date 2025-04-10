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
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use App\Models\DisplacedHousehold;
use App\Models\DisplacedHouseholdStatus;
use \Carbon\Carbon;
use DB;

class DisplacedHouseholdSummary implements FromCollection, WithTitle, ShouldAutoSize, 
    WithStyles, WithEvents,WithCustomStartCell
{
    private $displacedServedNewLocation = 0, $displacedMovedtoAreaA = 0, $displacedLeftAndTookSystem = 0,
        $displacedResponseInProgress = 0;

    protected $request; 

    function __construct($request) {

        $this->request = $request; 
    }
 
    /**
    * @return \Illuminate\Support\Collection
    */ 
    public function collection()  
    { 
        $this->displacedServedNewLocation = DisplacedHousehold::where("is_archived", 0)
            ->where("displaced_households.new_community_id", "!=", NULL)
            ->count();
        $this->displacedMovedtoAreaA = DisplacedHousehold::where("is_archived", 0)
            ->where("displaced_households.area", "A")
            ->count();
        $this->displacedLeftAndTookSystem = DisplacedHousehold::where("is_archived", 0)
            ->where("displaced_households.system_retrieved", "No")
            ->count();
        $this->displacedResponseInProgress = DisplacedHousehold::where("is_archived", 0)
            ->where("displaced_households.displaced_household_status_id", 3)
            ->count();

        $data = [
            [
                '# of displaced households served by Comet-ME in new location' => $this->displacedServedNewLocation,
                '# of displaced households moved to Area A' => $this->displacedMovedtoAreaA,
                '# of displaced households that left and took their system' => $this->displacedLeftAndTookSystem,
                '# of displaced households where our response is pending' => $this->displacedResponseInProgress
            ],
        ];
    
        return collect($data); 
    } 

    public function startCell(): string
    {
        return 'A4';
    }

    public function title(): string 
    {
        return 'Displaced Households Summary';
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
        $sheet->mergeCells('A1:J1');
        $sheet->mergeCells('A2:I2');
        $sheet->mergeCells('A3:I3');
        $sheet->mergeCells('A4:I4');
        $sheet->mergeCells('A5:I5');

        $sheet->setCellValue('A1', '');
        $sheet->setCellValue('A2', '# of displaced households served by Comet-ME in new location');
        $sheet->setCellValue('A3', '# of displaced households moved to Area A');
        $sheet->setCellValue('A4', '# of displaced households that left and took their system');
        $sheet->setCellValue('A5', '# of displaced households where our response is pending');

        $sheet->setCellValue('J2', $this->displacedServedNewLocation);
        $sheet->setCellValue('J3', $this->displacedMovedtoAreaA);
        $sheet->setCellValue('J4', $this->displacedLeftAndTookSystem);
        $sheet->setCellValue('J5', $this->displacedResponseInProgress);

        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }

}