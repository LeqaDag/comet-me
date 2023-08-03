<?php

namespace App\Exports;

use App\Models\EnergyUser;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB;

class EnergyMaintenanceExport implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, 
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
        $data = DB::table('electricity_maintenance_calls')
            ->leftJoin('households', 'electricity_maintenance_calls.household_id', 
                'households.id')
            ->leftJoin('public_structures', 'electricity_maintenance_calls.public_structure_id', 
                'public_structures.id')
            ->join('communities', 'electricity_maintenance_calls.community_id', 'communities.id')
            ->join('regions', 'communities.region_id', '=', 'regions.id')
            ->join('sub_regions', 'communities.sub_region_id', '=', 'sub_regions.id')
            ->join('maintenance_types', 'electricity_maintenance_calls.maintenance_type_id', 
                '=', 'maintenance_types.id')
            ->join('maintenance_electricity_actions', 'electricity_maintenance_calls.maintenance_electricity_action_id', 
                '=', 'maintenance_electricity_actions.id')
            ->join('maintenance_statuses', 'electricity_maintenance_calls.maintenance_status_id', 
                '=', 'maintenance_statuses.id')
            // ->join('electricity_maintenance_call_users', 'electricity_maintenance_calls.id', 
            //     'electricity_maintenance_call_users.electricity_maintenance_call_id')
            ->join('users', 'electricity_maintenance_calls.user_id', '=', 'users.id')
            ->where('electricity_maintenance_calls.is_archived', 0)
            ->select('households.english_name as english_name', 
                'public_structures.english_name as public_name', 
                'communities.english_name as community_name',
                'regions.english_name as region', 'sub_regions.english_name as sub_region',
                'users.name as user_name',
                'maintenance_electricity_actions.maintenance_action_electricity', 
                'maintenance_electricity_actions.maintenance_action_electricity_english',
                'maintenance_statuses.name', 'maintenance_types.type',
                'date_of_call', 'date_completed', 'electricity_maintenance_calls.notes');

        if($this->request->public) {
            $data->where("public_structures.public_structure_category_id1", $this->request->public)
                ->orWhere("public_structures.public_structure_category_id2", $this->request->public)
                ->orWhere("public_structures.public_structure_category_id3", $this->request->public);
        }
        if($this->request->community_id) {
            $data->where("electricity_maintenance_calls.community_id", $this->request->community_id);
        }
        if($this->request->date) {
            $data->where("electricity_maintenance_calls.date_completed", ">=", $this->request->date);
        }

        return $data->get();
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function headings(): array
    {
        return ["Household Name", "Public Structure", "Community", "Region", "Sub Region", 
            "Recipient", "Action in Arabic", "Action in English", "Status", "Type", "Call Date",
            "Completed Date", "Notes"];
    }

    public function title(): string
    {
        return 'Energy Maintenance Logs';
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