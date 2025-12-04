<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB;

class WaterRequestSystemExport implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, 
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
        $query = DB::table('water_request_systems')
            ->leftJoin('households', 'water_request_systems.household_id', 'households.id')
            ->leftJoin('public_structures', 'water_request_systems.public_structure_id', 'public_structures.id')
            ->join('communities', 'water_request_systems.community_id', 'communities.id')
            ->join('regions', 'communities.region_id', 'regions.id')
            ->join('sub_regions', 'communities.sub_region_id', 'sub_regions.id')
            ->join('water_holder_statuses', 'water_request_systems.water_holder_status_id', 
                'water_holder_statuses.id')
            ->leftJoin('water_request_statuses', 'water_request_systems.water_request_status_id', 
                'water_request_statuses.id')
            ->leftJoin('all_energy_meters as users', 'users.household_id', 'households.id')
            ->leftJoin('all_energy_meters as publics', 'publics.public_structure_id', 'public_structures.id')
            ->leftJoin('water_system_types', 'water_request_systems.water_system_type_id', 
                'water_system_types.id')
            ->where('water_request_systems.is_archived', 0)
            ->select(
                DB::raw('IFNULL(households.english_name, public_structures.english_name) 
                    as holder'),
                'communities.english_name as community_name', 'regions.english_name as region', 
                'sub_regions.english_name as sub_region', 'water_request_systems.date', 
                'water_holder_statuses.status', 
                'water_request_statuses.name', 'water_request_statuses.name',
                'water_system_types.type', 
                DB::raw('COALESCE(users.is_main, publics.is_main, "No") as is_main'),
                DB::raw('IFNULL(users.meter_number, publics.meter_number) 
                    as meter_number'), 
                'water_request_systems.referred_by', 'water_request_systems.notes'
                    )
            ->orderBy('water_request_systems.date', 'desc'); 

        if($this->request->community) {

            $query->where("communities.english_name", $this->request->community);
        } 
        if($this->request->request_status) {

            $query->where("water_request_systems.water_request_status_id", $this->request->request_status);
        }
        if($this->request->date) {

            $query->where("water_request_systems.date", ">=", $this->request->date);
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
        return ["Requested Holder", "Community", "Region", "Sub Region", "Requested Date", 
            "Holder Status", "Requested Status", "Recommended Water System Type", 
            "Main Energy Holder", "Meter Number", "Referred By", "Notes"];
    }

    public function title(): string
    {
        return 'Requested Water System';
    }

    /**
     * Styling
     *
     * @return response()
     */
    public function styles(Worksheet $sheet)
    {
        $sheet->setAutoFilter('A1:L1');
        $sheet->getStyle('w1')->getAlignment()->setWrapText(true);

        $sheet->getColumnDimension('w')->setAutoSize(false)->setWidth(40);

        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}