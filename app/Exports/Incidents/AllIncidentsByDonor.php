<?php

namespace App\Exports\Incidents;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use DB;

class AllIncidentsByDonor implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, WithStyles
{
    protected $request;
    protected $donorName;

    function __construct($request, $donorName)
    {
        $this->request = $request;
        $this->donorName = $donorName;
    }

    public function collection()
    {
        $query = (new AllIncidents($this->request))->collection();

 
        return $query->filter(function ($row) {

            return str_contains($row->donor_name, $this->donorName);
        });
    }

    public function headings(): array
    {
        return (new AllIncidents($this->request))->headings();
    }

    public function title(): string
    {
        return 'Incidents log ' . $this->donorName;
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->setAutoFilter('A1:T1');

        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}
