<?php

namespace App\Exports; 

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize; 
use DB; 

class InProgressHouseholdExport implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, WithStyles
{

    protected $request;
    protected $data;

    function __construct($request, $data) {

        $this->request = $request;
        $this->data = $data;
    } 

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {

        return $this->data->get();
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function headings(): array
    {
        return ["English Name", "Community", "Region", "Recommended Energy Type",
            "Profession", "# of Male", "# of Female", "# of Children", 
            "# of Adults"];
    }

    public function title(): string
    {
        return 'In-Progress Households';
    }

    /**
     * Styling
     *
     * @return response()
     */
    public function styles(Worksheet $sheet)
    {
        $sheet->setAutoFilter('A1:U1');

        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}