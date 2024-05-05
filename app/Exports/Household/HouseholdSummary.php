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
 
    // Routine Maintenance & Management 
    private $missingMale = 0, $missingFemale = 0, $missingChildren = 0, $missingAdults = 0, 
        $missingAllInfo = 0, $discrepancy = 0, $missingPhoneNumber = 0, $missingSchoolStudent = 0;

    protected $request;

    function __construct($request) {

        $this->request = $request; 
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    { 
        $missingMaleHousehold =  DB::table('households')
            ->join('communities', 'communities.id', 'households.community_id')
            ->where('households.is_archived', 0)
            ->where('households.internet_holder_young', 0)
            ->whereNull('households.number_of_male');

        $missingFemaleHousehold =  DB::table('households')
            ->join('communities', 'communities.id', 'households.community_id')
            ->where('households.is_archived', 0)
            ->where('households.internet_holder_young', 0)
            ->whereNull('households.number_of_female');

        $missingChildrenHousehold =  DB::table('households')
            ->join('communities', 'communities.id', 'households.community_id')
            ->where('households.is_archived', 0)
            ->where('households.internet_holder_young', 0)
            ->whereNull('households.number_of_children');

        $missingAdultHousehold =  DB::table('households')
            ->join('communities', 'communities.id', 'households.community_id')
            ->where('households.is_archived', 0)
            ->where('households.internet_holder_young', 0)
            ->whereNull('households.number_of_adults');

        $missingAllInfoHousehold =  DB::table('households')
            ->join('communities', 'communities.id', 'households.community_id')
            ->where('households.is_archived', 0)
            ->where('households.internet_holder_young', 0)
            ->whereNull('households.number_of_male')
            ->whereNull('households.number_of_female')
            ->whereNull('households.number_of_children')
            ->whereNull('households.number_of_adults');

        $discrepancyHousehold = DB::table('households')
            ->join('communities', 'communities.id', 'households.community_id')
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

        $missingSchoolStudentHousehold = DB::table('households')
            ->join('communities', 'communities.id', 'households.community_id')
            ->where('households.is_archived', 0)
            ->where('households.internet_holder_young', 0)
            ->whereNull('households.school_students');
            
        $this->missingMale = $missingMaleHousehold->count();
        $this->missingFemale = $missingFemaleHousehold->count();
        $this->missingChildren = $missingChildrenHousehold->count();
        $this->missingAdults = $missingAdultHousehold->count();
        $this->missingAllInfo = $missingAllInfoHousehold->count();
        $this->discrepancy = $discrepancyHousehold->count();
        $this->missingPhoneNumber = $missingPhoneNumberHousehold->count();
        $this->missingSchoolStudent = $missingSchoolStudentHousehold->count();

        $data = [
            [
                '# of missing male' => $this->missingMale,
                '# of missing female' => $this->missingFemale
            ],
        ];
    
        return collect($data); 
    }


    public function title(): string
    {
        return 'Beneficiaries Info';
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
        $sheet->mergeCells('A7:I7');
        $sheet->mergeCells('A8:I8');
        $sheet->mergeCells('A9:I9');

        $sheet->setCellValue('A1', 'Missing Beneficiaries Informations');
        $sheet->setCellValue('A2', '# of Missing Male');
        $sheet->setCellValue('A3', '# of Missing Female');
        $sheet->setCellValue('A4', '# of Missing Children');
        $sheet->setCellValue('A5', '# of Missing Adults');
        $sheet->setCellValue('A6', '# of Households with no Information');
        $sheet->setCellValue('A7', '# of Households that have discrepancy between Male+Female and Adults+Children');
        $sheet->setCellValue('A8', '# of Missing Phone Numbers');
        $sheet->setCellValue('A9', '# of Missing School Students');

        $sheet->setCellValue('J2', $this->missingMale);
        $sheet->setCellValue('J3', $this->missingFemale);
        $sheet->setCellValue('J4', $this->missingChildren);
        $sheet->setCellValue('J5', $this->missingAdults);
        $sheet->setCellValue('J6', $this->missingAllInfo);
        $sheet->setCellValue('J7', $this->discrepancy);
        $sheet->setCellValue('J8', $this->missingPhoneNumber);
        $sheet->setCellValue('J9', $this->missingSchoolStudent);

        // $sheet->mergeCells('A10:J10');
        // $sheet->mergeCells('A11:I11');
        // $sheet->mergeCells('A12:I12');
        // $sheet->mergeCells('A13:I13');
        // $sheet->mergeCells('A14:I14');
        // $sheet->mergeCells('A15:J15');


        return [
            // Style the first row as bold text.
            1  => ['font' => ['bold' => true, 'size' => 12]],
            10  => ['font' => ['bold' => true, 'size' => 12]],
            16  => ['font' => ['bold' => true, 'size' => 12]],
            22  => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}