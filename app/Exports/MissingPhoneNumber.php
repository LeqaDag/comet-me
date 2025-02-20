<?php

namespace App\Exports; 

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize; 
use DB; 

class MissingPhoneNumber implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, WithStyles
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
        $this->data->whereNull('households.phone_number');

        return $this->data->get();
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function headings(): array
    {
        return ["English Name", "Arabic Name", "Community", "Region", "Sub Region", 
            "Profession", "# of Male", "# of Female", "# of Children", "# of Adults",
            "# of School students", "Energy System Status", "Main User", 
            "Energy System Type", "Meter Number", "Energy Donors",
            "Requset Date", "Water System Status", "Water Donors",
            "Internet System Status", "Internet Donors"];
    }

    public function title(): string
    {
        return 'Missing Phone Numbers';
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