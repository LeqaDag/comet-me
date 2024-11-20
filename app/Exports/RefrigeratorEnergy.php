<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB;

class RefrigeratorEnergy implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, 
    WithStyles
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
        $query = DB::table('all_energy_meters')
            ->join('communities', 'all_energy_meters.community_id', 'communities.id')
            ->join('regions', 'communities.region_id', 'regions.id')
            ->join('sub_regions', 'communities.sub_region_id', 'sub_regions.id')
            ->join('energy_systems', 'all_energy_meters.energy_system_id', 'energy_systems.id')
            ->join('energy_system_types', 'all_energy_meters.energy_system_type_id', 'energy_system_types.id')
            ->leftJoin('households', 'households.id', 'all_energy_meters.household_id')
            ->leftJoin('refrigerator_holders', 'households.id', 'refrigerator_holders.household_id')
            ->leftJoin('public_structures', 'public_structures.id', 'all_energy_meters.public_structure_id')
            ->leftJoin('refrigerator_holders as public_refrigerators', 'public_structures.id', 
                'public_refrigerators.public_structure_id')
            ->leftJoin('energy_system_cycles as cycle_households', 'cycle_households.id', 'households.energy_system_cycle_id')
            ->leftJoin('energy_system_cycles as cycle_publics', 'cycle_publics.id', 'public_structures.energy_system_cycle_id')
            ->where(function ($query) {
                $query->whereNull('refrigerator_holders.household_id') 
                    ->orWhere(function ($subQuery) {
                        $subQuery->where('public_structures.comet_meter', 0) 
                                ->whereNull('public_refrigerators.public_structure_id'); 
                    });
            })
            ->where('all_energy_meters.is_archived', 0)
            ->select(
                DB::raw('COALESCE(households.english_name, public_structures.english_name) as english_name'),
                'communities.english_name as community',
                'regions.english_name as region',
                DB::raw('IFNULL(cycle_households.name, cycle_publics.name) as cycle_year'),
                'all_energy_meters.is_main',
                'all_energy_meters.installation_date',
                'all_energy_meters.meter_number',
                'energy_systems.name as energy_name',
                'energy_system_types.name as energy_type_name'
            )
            ->distinct();

        if($this->request->community) {

            $query->where("communities.english_name", $this->request->community);
        } 
        if($this->request->date_from) {

            $query->where("all_energy_meters.installation_date", ">=", $this->request->date_from);
        }
        if($this->request->date_to) {

            $query->where("all_energy_meters.installation_date", "<=", $this->request->date_to);
        }

        return $query->get();
    }

    /**
     * Write code on Method
     * 
     * @return response()
     */
    public function headings(): array
    {
        return ["Energy Holder", "Community", "Region", "Energy Cycle Year", "Main Holder",
            "Installation Date", "Meter Number", "Energy System", "Energy System Type"];
    }

    public function title(): string
    {
        return 'Energy Holders / No Refrigerator';
    }

    /**
     * Styling
     *
     * @return response()
     */
    public function styles(Worksheet $sheet)
    {
        $sheet->setAutoFilter('A1:I1');

        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}