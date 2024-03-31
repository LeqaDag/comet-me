<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use DB;

class EnergyUsers implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, WithStyles
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
        $data = DB::table('energy_users')
            ->join('all_energy_meters', 'all_energy_meters.id', 'energy_users.all_energy_meter_id')
            ->join('communities', 'energy_users.community_id', 'communities.id')
            ->leftJoin('households', 'all_energy_meters.household_id', 'households.id')
            ->leftJoin('public_structures', 'all_energy_meters.public_structure_id', 
                'public_structures.id')
            ->select(
                'communities.english_name as community_name', 
                'communities.electricity_before',
                'all_energy_meters.installation_date',
                'energy_users.meter_number'
            )
            ->groupBy('communities.english_name', 'energy_users.meter_number')
            ->get();
        
        return $data;
    } 

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function headings(): array
    {
        return ["Community", "Electricity before Comet", "Installation Date", 
            "Account Number"];
    }

    public function title(): string
    {
        return 'Data for research paper';
    }

    /**
     * Styling
     *
     * @return response()
     */
    public function styles(Worksheet $sheet)
    {
        $sheet->setAutoFilter('A1:E1');

        // Merge cells for the community  
        $groupedData = collect($this->collection())->groupBy('community_name');
        $rowIndex = 2;

        foreach ($groupedData as $englishName => $rows) {
            $firstRow = $rowIndex;
            $lastRow = $firstRow + count($rows) - 1;

            if ($firstRow !== null && $lastRow !== null) {
                $sheet->mergeCells("A{$firstRow}:A{$lastRow}");
                $sheet->mergeCells("B{$firstRow}:B{$lastRow}");
            }

            $rowIndex = $lastRow + 1;
        }
        
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true, 'size' => 14]],
        ];
    }
}
