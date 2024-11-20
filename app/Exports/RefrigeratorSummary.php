<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB;

class RefrigeratorSummary implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, 
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
        $query = DB::table('refrigerator_holders')
            ->join('communities', 'refrigerator_holders.community_id', 'communities.id')
            ->join('regions', 'communities.region_id', 'regions.id')
            ->join('sub_regions', 'communities.sub_region_id', 'sub_regions.id')
            ->leftJoin('households', 'refrigerator_holders.household_id', 'households.id')
            ->leftJoin('public_structures', 'refrigerator_holders.public_structure_id', 
                'public_structures.id')
            ->leftJoin('refrigerator_holder_receive_numbers', 'refrigerator_holders.id', 
                'refrigerator_holder_receive_numbers.refrigerator_holder_id')
            ->leftJoin('energy_system_cycles as cycle_households', 'cycle_households.id', 'households.energy_system_cycle_id')
            ->leftJoin('energy_system_cycles as cycle_publics', 'cycle_publics.id', 'public_structures.energy_system_cycle_id')
            ->leftJoin('all_energy_meters as energy_users', 'energy_users.household_id', 'households.id')
            ->leftJoin('energy_systems', 'energy_users.energy_system_id', 'energy_systems.id')
            ->leftJoin('energy_system_types', 'energy_users.energy_system_type_id', 'energy_system_types.id')
            ->leftJoin('all_energy_meters as energy_publics', 'energy_publics.public_structure_id', 'public_structures.id')
            ->leftJoin('energy_systems as energy_system_publics', 'energy_publics.energy_system_id', 'energy_system_publics.id')
            ->leftJoin('energy_system_types as type_publics', 'energy_publics.energy_system_type_id', 'type_publics.id')
            ->where('refrigerator_holders.is_archived', 0)
            ->select(
                DB::raw('IFNULL(households.english_name, public_structures.english_name) 
                    as exported_value'),
                'communities.english_name as community_name', 
                'regions.english_name as region', 'sub_regions.english_name as sub_region',
                DB::raw('IFNULL(cycle_households.name, cycle_publics.name) as cycle_year'),
                DB::raw('IFNULL(energy_systems.name, energy_system_publics.name) as energy_name'),
                DB::raw('IFNULL(energy_system_types.name, type_publics.name) as energy_type'),
                'refrigerator_holders.year', 'refrigerator_holders.date', 
                'refrigerator_holders.is_paid', 'refrigerator_holders.payment',  
                'refrigerator_holder_receive_numbers.receive_number', 'refrigerator_holders.status');

        if($this->request->community) {

            $query->where("communities.english_name", $this->request->community);
        } 
        if($this->request->public) {
            $query->where("public_structures.public_structure_category_id1", $this->request->public)
                ->orWhere("public_structures.public_structure_category_id2", $this->request->public)
                ->orWhere("public_structures.public_structure_category_id3", $this->request->public);
        } 
        if($this->request->date_from) {

            $query->where("refrigerator_holders.date", ">=", $this->request->date_from);
        }
        if($this->request->date_to) {

            $query->where("refrigerator_holders.date", "<=", $this->request->date_to);
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
        return ["Energy Holder", "Community", "Region", "Sub Region", "Energy Cycle Year",
            "Energy System", "Energy Type", "Year", "Date", "Is Paid", "Payment", "Receive Number", 
            "Status"];
    }

    public function title(): string
    {
        return 'Refrigerator Holders';
    }

    /**
     * Styling
     *
     * @return response()
     */
    public function styles(Worksheet $sheet)
    {
        $sheet->setAutoFilter('A1:M1');

        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}