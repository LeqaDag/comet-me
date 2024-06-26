<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB;

class RefrigeratorEnergyPublic implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, 
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
            ->join('public_structures', 'public_structures.id', 'all_energy_meters.public_structure_id')
            ->where('public_structures.comet_meter', 0)
            ->leftJoin('refrigerator_holders', 'public_structures.id', 'refrigerator_holders.public_structure_id')
            ->whereNull('refrigerator_holders.public_structure_id')
            ->select(
                'public_structures.english_name',
                'communities.english_name as community',
                'regions.english_name as region',
                'all_energy_meters.installation_date',
                'all_energy_meters.is_main',
                'all_energy_meters.meter_number',
                'energy_systems.name as energy_name',
                'energy_system_types.name as energy_type_name'
            );

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
        return ["Energy Holder", "Community", "Region", "Main Holder",
            "Installation Date", "Meter Number", "Energy System", "Energy System Type"];
    }

    public function title(): string
    {
        return 'Energy Public / No Refrigerator';
    }

    /**
     * Styling
     *
     * @return response()
     */
    public function styles(Worksheet $sheet)
    {
        $sheet->setAutoFilter('A1:H1');

        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}