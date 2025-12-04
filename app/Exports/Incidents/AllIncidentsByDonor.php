<?php

namespace App\Exports\Incidents;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use DB;

class AllIncidentsByDonor implements FromCollection, WithHeadings, WithTitle, WithStyles
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
        $allIncidents = new AllIncidents($this->request);
        
        // Get the collection from AllIncidents
        $data = $allIncidents->collection();

  
        return $data->filter(function ($item) {

            return stripos($item['Donor (Energy)'], $this->donorName) !== false ||
                   stripos($item['Donor (Water)'], $this->donorName) !== false ||
                   stripos($item['Donor (Internet)'], $this->donorName) !== false ||
                   stripos($item['Donor (Camera)'], $this->donorName) !== false;
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
        $sheet->setAutoFilter('A1:AI1');

        $highestRow = $sheet->getHighestRow();           
        $highestColumn = $sheet->getHighestColumn();        
        $fullRange = "A1:{$highestColumn}{$highestRow}";

        // Wrap text and vertical top alignment for all cells
        $sheet->getStyle($fullRange)->getAlignment()
            ->setWrapText(true)
            ->setVertical(Alignment::VERTICAL_TOP);

        // Convert highest column letter to index
        $highestColumnIndex = Coordinate::columnIndexFromString($highestColumn);

        // Set fixed column width for all columns properly
        for ($col = 1; $col <= $highestColumnIndex; $col++) {
            $columnLetter = Coordinate::stringFromColumnIndex($col);
            $sheet->getColumnDimension($columnLetter)->setWidth(40);
        }

        // Auto row height for all rows
        for ($row = 1; $row <= $highestRow; $row++) {
            $sheet->getRowDimension($row)->setRowHeight(-1);
        }

        // Header font style
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}
