<?php

namespace App\Exports;

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
 
class EnergyCostSystem implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, 
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
        $energySystems = DB::table('energy_systems')
            ->join('energy_system_types', 'energy_systems.energy_system_type_id', 'energy_system_types.id')
            ->where('energy_systems.is_archived', 0)
            ->where('energy_systems.total_costs', '>', 0)
            ->where('energy_systems.energy_system_type_id', '!=', 2)
            ->select(
                'energy_systems.name', 'energy_system_types.name as type', 
                'energy_systems.number_of_families',
                'energy_systems.installation_year', 'energy_systems.total_costs', 
                DB::raw('energy_systems.total_costs * 0.16 as vat_cost'),
                DB::raw('(energy_systems.total_costs * 0.16 + energy_systems.total_costs) * 0.10 as contingency_cost'),
                DB::raw('((energy_systems.total_costs * 0.16 + energy_systems.total_costs) * 0.10 ) + 
                    energy_systems.total_costs as system_cost'),
                DB::raw('(((energy_systems.total_costs * 0.16 + energy_systems.total_costs) * 0.10 ) + 
                    energy_systems.total_costs) / energy_systems.number_of_families as household_cost')
            )->groupBy('energy_systems.id');


        $energyFbsSystems = DB::table('energy_systems')
            ->join('energy_system_types', 'energy_systems.energy_system_type_id', 'energy_system_types.id')
            ->where('energy_systems.is_archived', 0)
            ->where('energy_systems.total_costs', '>', 0)
            ->where('energy_systems.energy_system_type_id', 2)
            ->select(
                'energy_systems.name', 'energy_system_types.name as type', 
                'energy_systems.number_of_families',
                'energy_systems.installation_year', 'energy_systems.total_costs', 
                DB::raw('energy_systems.total_costs * 0.16 as vat_cost'),
                DB::raw('((energy_systems.total_costs * 0.16) + energy_systems.total_costs) * 0.08 as contingency_cost'),
                DB::raw('(((energy_systems.total_costs * 0.16) + energy_systems.total_costs) * 0.08 ) + 
                    (energy_systems.total_costs * 0.16 + energy_systems.total_costs) as system_cost'),
                DB::raw('((((energy_systems.total_costs * 0.16) + energy_systems.total_costs) * 0.08 ) + 
                (energy_systems.total_costs * 0.16 + energy_systems.total_costs)) / energy_systems.number_of_families as household_cost')
            )->groupBy('energy_systems.id');


        if($this->request->energy_cycle_id) {

            $energySystems->where("energy_systems.energy_system_cycle_id", $this->request->energy_cycle_id);
            $energyFbsSystems->where("energy_systems.energy_system_cycle_id", $this->request->energy_cycle_id);
        }

        $energySystems = $energySystems->get();
        
        $energyFbsSystems = $energyFbsSystems->get();
        
        return $energySystems->merge($energyFbsSystems);
    }
 
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function headings(): array
    {
        return ['Energy System', 'Energy System Type', '# of Families', 'Installation Year', 'Total Cost', 
            'VAT', 'Contingency', 'System Cost', 'Cost by Household'];
    }

    // public function collection() {

    //     $energySystemNames = DB::table('energy_systems')
    //         ->where('is_archived', 0)
    //         ->where('installation_year', '>=', 2023)
    //         ->where('total_costs', '>', 0)
    //         ->pluck('name')
    //         ->toArray();

    //     $results = [];

    //     $batteryModels = DB::table('energy_systems')
    //         ->join('energy_system_batteries', 'energy_systems.id', 
    //             'energy_system_batteries.energy_system_id')
    //         ->leftJoin('energy_batteries', 'energy_batteries.id', 
    //             'energy_system_batteries.battery_type_id')
    //         ->where('energy_systems.is_archived', 0)
    //         ->where('energy_systems.installation_year', '>=', 2023)
    //         ->where('energy_systems.total_costs', '>', 0)
    //         ->distinct()
    //         ->pluck('energy_batteries.battery_model')
    //         ->toArray();
    //     $pvModels = DB::table('energy_systems')
    //         ->join('energy_system_pvs', 'energy_systems.id', 'energy_system_pvs.energy_system_id')
    //         ->leftJoin('energy_pvs', 'energy_pvs.id', 'energy_system_pvs.pv_type_id')
    //         ->where('energy_systems.is_archived', 0)
    //         ->where('energy_systems.installation_year', '>=', 2023)
    //         ->where('energy_systems.total_costs', '>', 0)
    //         ->distinct()
    //         ->pluck('energy_pvs.pv_model')
    //         ->toArray();

    //     $componentModels = array_merge($batteryModels, $pvModels);

    //     $results[1][] = ' '; $results[][1] = ' '; 
    //     $columnIndex = 2; 
    //     foreach ($energySystemNames as $name) {
            
    //         $results[2][] = $name;
    //         $results[2][] = $name . ' Cost'; 
    //         $columnIndex++; 
    //     }

    //     $rowIndex = 4;
    //     foreach ($componentModels as $model) {

    //         $results[$rowIndex][] = $model;
    //         $rowIndex++; 
    //     }

    //     // Convert the array back to a collection
    //     return new Collection($results);
    // }

    public function title(): string
    {
        return 'Price Lists';
    }

    public function startCell(): string
    {
        return 'B3';
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
                    for ($col = 'E'; $col <= $highestColumn; $col++) {
                        $cell = $sheet->getCell($col . $row);
                        $value = $cell->getValue();

                        // Check if the cell value is numeric
                        if (is_numeric($value)) {

                            $sheet->getStyle($col . $row)->getNumberFormat()->setFormatCode('#,##0.00');
                        }
                    }
                }

                $event->sheet->getDelegate()->freezePane('B3'); 
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
        $sheet->setAutoFilter('A1:I1');

        // $sheet->setCellValue('A1', '# of Families');
        // $sheet->setCellValue('A2', 'Component');

        return [
            // Style the first row as bold text.
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}