<?php

namespace App\Exports\Household;

use App\Models\EnergyUser;
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

class HouseholdSummary implements FromCollection, WithTitle, ShouldAutoSize, 
    WithStyles, WithCustomStartCell, WithEvents
{
 
    // Not including new communities
    private $missingChildren = 0, $missingAdults = 0, $missingAllInfo = 0, $discrepancy = 0, 
        $missingPhoneNumber = 0;
    
    // new communities
    private $missingChildrenNew = 0, $missingAdultsNew = 0, $missingAllInfoNew = 0, $discrepancyNew = 0, 
        $missingPhoneNumberNew = 0;

    protected $request;

    function __construct($request) {

        $this->request = $request; 
    }
 
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    { 
        $missingChildrenHousehold =  DB::table('households')
            ->join('communities', 'communities.id', 'households.community_id')
            ->where('households.is_archived', 0)
            ->where('households.internet_holder_young', 0)
            ->whereNull('communities.energy_system_cycle_id')
            ->whereNull('households.number_of_children')
            ->where('households.number_of_adults', '!=', null);

        $missingAdultHousehold =  DB::table('households')
            ->join('communities', 'communities.id', 'households.community_id')
            ->where('households.is_archived', 0)
            ->where('households.internet_holder_young', 0)
            ->whereNull('communities.energy_system_cycle_id')
            ->whereNull('households.number_of_adults')
            ->whereNotNull('households.number_of_children');

        $missingAllInfoHousehold =  DB::table('households')
            ->join('communities', 'communities.id', 'households.community_id')
            ->where('households.is_archived', 0)
            ->where('households.internet_holder_young', 0)
            ->whereNull('communities.energy_system_cycle_id')
            ->whereNull('households.number_of_male')
            ->whereNull('households.number_of_female')
            ->whereNull('households.number_of_children')
            ->whereNull('households.number_of_adults');

        $discrepancyHousehold = DB::table('households')
            ->join('communities', 'communities.id', 'households.community_id')
            ->whereNull('communities.energy_system_cycle_id')
            ->where('households.is_archived', 0)
            ->where('households.internet_holder_young', 0)
            ->groupBy('households.id', 'communities.id') 
            ->havingRaw('households.number_of_male + households.number_of_female != 
                households.number_of_children + households.number_of_adults')
            ->get();

        $missingPhoneNumberHousehold =  DB::table('households')
            ->join('communities', 'communities.id', 'households.community_id')
            ->where('households.is_archived', 0)
            ->where('households.internet_holder_young', 0)
            ->whereNull('households.phone_number');


        $this->missingChildren = $missingChildrenHousehold->count();
        $this->missingAdults = $missingAdultHousehold->count();
        $this->missingAllInfo = $missingAllInfoHousehold->count();
        $this->discrepancy = $discrepancyHousehold->count();
        $this->missingPhoneNumber = $missingPhoneNumberHousehold->count();

        $data = [
            [
                'no children' => $this->missingChildren,
                'no info' => $this->missingAllInfo
            ],
        ];
    
        return collect($data); 
    }


    public function title(): string
    {
        return 'Missing Beneficiaries Info (not including new communities)';
    }

    public function startCell(): string
    {
        return 'A2';
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
        $sheet->mergeCells('A1:J1');
        $sheet->mergeCells('A2:I2');
        $sheet->mergeCells('A3:I3');
        $sheet->mergeCells('A4:I4');
        $sheet->mergeCells('A5:I5');
        $sheet->mergeCells('A6:I6');

        $sheet->setCellValue('A1', 'Missing Beneficiaries Informations (not including new communities)');
        $sheet->setCellValue('A2', '# of Households with No Children Listed');
        $sheet->setCellValue('A3', '# of Households with No Adults Listed');
        $sheet->setCellValue('A4', '# of Households with no Information');
        $sheet->setCellValue('A5', '# of Households that have discrepancy between Male+Female and Adults+Children');
        $sheet->setCellValue('A6', '# of Households with No Phone Numbers Listed');

        $sheet->setCellValue('J2', $this->missingChildren);
        $sheet->setCellValue('J3', $this->missingAdults);
        $sheet->setCellValue('J4', $this->missingAllInfo);
        $sheet->setCellValue('J5', $this->discrepancy);
        $sheet->setCellValue('J6', $this->missingPhoneNumber);

        $sheet->mergeCells('A7:J7');
        $sheet->mergeCells('A8:I8');
        $sheet->mergeCells('A9:I9');
        $sheet->mergeCells('A10:I10');
        $sheet->mergeCells('A11:I11');
        $sheet->mergeCells('A12:I12');

        $sheet->setCellValue('A7', 'Missing Beneficiaries Informations (new communities)');
        $sheet->setCellValue('A8', '# of Households with No Children Listed');
        $sheet->setCellValue('A9', '# of Households with No Adults Listed');
        $sheet->setCellValue('A10', '# of Households with no Information');
        $sheet->setCellValue('A11', '# of Households that have discrepancy between Male+Female and Adults+Children');
        $sheet->setCellValue('A12', '# of Households with No Phone Numbers Listed');

        $sheet->setCellValue('J8', $this->missingChildren);
        $sheet->setCellValue('J9', $this->missingAdults);
        $sheet->setCellValue('J10', $this->missingAllInfo);
        $sheet->setCellValue('J11', $this->discrepancy);
        $sheet->setCellValue('J12', $this->missingPhoneNumber);

        return [
            // Style the first row as bold text.
            1  => ['font' => ['bold' => true, 'size' => 12]],
            7  => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}