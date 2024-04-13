<?php

namespace App\Exports\Energy\Cost;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents; 
use Maatwebsite\Excel\Events\AfterSheet;
use DB;
 
class EnergyElectricityRoomBos implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, 
    WithStyles, WithEvents
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
        $electricityRoomBos = DB::table('grid_community_compounds')
            ->join('energy_systems', 'grid_community_compounds.energy_system_id', 
                'energy_systems.id')
            ->where('energy_systems.is_archived', 0)
            ->where('grid_community_compounds.electricity_room_bos_cost', '>', 0)
            ->select(
                'energy_systems.name',
                DB::raw("'Elecricity room BoS' as model"),
                'grid_community_compounds.electricity_room_bos_number',
                DB::raw('(grid_community_compounds.electricity_room_bos_cost / grid_community_compounds.electricity_room_bos_number) 
                    as cost_per_unit'),
                'grid_community_compounds.electricity_room_bos_cost as cost'
            )
            ->get();

        $totals = $electricityRoomBos->reduce(function ($carry, $item) {
            $carry['electricity_room_bos_number'] += $item->electricity_room_bos_number;
            $carry['cost_per_unit'] += $item->cost_per_unit;
            $carry['cost'] += $item->cost;

            return $carry;
        }, [
            'electricity_room_bos_number' => 0,
            'cost_per_unit' => 0,
            'cost' => 0,
        ]);

        // Append totals as a new row 
        $electricityRoomBos->push([
            'name' => 'Total',
            'model' => '',
            'electricity_room_bos_number' => $totals['electricity_room_bos_number'],
            'cost_per_unit' => $totals['cost_per_unit'],
            'cost' => $totals['cost'],
        ]);

        return $electricityRoomBos;
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function headings(): array
    {
        return ['System Name', 'Model', 'Units', 'Cost Per Unit', 'Cost'];
    }


    public function title(): string
    {
        return 'Electricity Room Bos Costs';
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
            AfterSheet::class => function (AfterSheet $event) {

                $highestRow = $event->sheet->getDelegate()->getHighestRow();
                $highestColumn = $event->sheet->getDelegate()->getHighestColumn();
                $sheet = $event->sheet->getDelegate();
        
                for ($row = 1; $row <= $highestRow; $row++) {
                    for ($col = 'D'; $col <= $highestColumn; $col++) {
                        $cell = $sheet->getCell($col . $row);
                        $value = $cell->getValue();

                        // Check if the cell value is numeric
                        if (is_numeric($value)) {

                            $sheet->getStyle($col . $row)->getNumberFormat()->setFormatCode('#,##0.00');
                        }
                    }
                }
                
                // Apply different formatting for the total row
                foreach ($sheet->getRowIterator() as $row) {
                    $cell = $sheet->getCell('A' . $row->getRowIndex());
                    $value = $cell->getValue();

                    if ($value === 'Total') {
                        $sheet->getStyle('A' . $row->getRowIndex() . ':' . $highestColumn . $row->getRowIndex())->applyFromArray([
                            'font' => ['bold' => true],
                            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFFF00']],
                        ]);
                    }
                }
                $event->sheet->getDelegate()->freezePane('A3');  
            }
        ];
    }

    /**
     * Styling
     *
     * @return response()
     */
    public function styles(Worksheet $sheet)
    {
        $sheet->setAutoFilter('A1:E1');

        // $sheet->setCellValue('A1', '# of Families');
        // $sheet->setCellValue('A2', 'Component');

        return [
            // Style the first row as bold text.
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}