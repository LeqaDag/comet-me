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

class RefrigeratorMaintenanceExport implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, 
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
        $data = DB::table('refrigerator_maintenance_calls')
            ->leftJoin('households', 'refrigerator_maintenance_calls.household_id', 'households.id')
            ->leftJoin('public_structures', 'refrigerator_maintenance_calls.public_structure_id', 
                'public_structures.id')
            ->join('communities', 'refrigerator_maintenance_calls.community_id', 'communities.id')
            ->join('regions', 'communities.region_id', '=', 'regions.id')
            ->join('sub_regions', 'communities.sub_region_id', '=', 'sub_regions.id')
            ->join('maintenance_types', 'refrigerator_maintenance_calls.maintenance_type_id', 
                '=', 'maintenance_types.id') 
            ->leftJoin('refrigerator_maintenance_call_actions', 'refrigerator_maintenance_calls.id', 
                'refrigerator_maintenance_call_actions.refrigerator_maintenance_call_id')
            ->leftJoin('maintenance_refrigerator_actions', 
                'refrigerator_maintenance_call_actions.maintenance_refrigerator_action_id', 
                '=', 'maintenance_refrigerator_actions.id')
            ->leftJoin('refrigerator_maintenance_call_users', 'refrigerator_maintenance_calls.id', 
                'refrigerator_maintenance_call_users.refrigerator_maintenance_call_id')
            ->leftJoin('users as performed_users', 'refrigerator_maintenance_call_users.user_id', 
                'performed_users.id')
            ->join('maintenance_statuses', 'refrigerator_maintenance_calls.maintenance_status_id', 
                '=', 'maintenance_statuses.id')
            ->join('users as recipients', 'refrigerator_maintenance_calls.user_id', 'recipients.id')
            ->where('refrigerator_maintenance_calls.is_archived', 0)
            ->select([
                DB::raw('IFNULL(households.english_name, public_structures.english_name) 
                    as exported_value'),
                'communities.english_name as community_name',
                'regions.english_name as region', 'sub_regions.english_name as sub_region',
                'recipients.name as user_name',
                DB::raw('group_concat(DISTINCT maintenance_refrigerator_actions.maintenance_action_refrigerator_english)'),
                DB::raw('group_concat(DISTINCT maintenance_refrigerator_actions.maintenance_action_refrigerator)'),
                'maintenance_statuses.name', 'maintenance_types.type',
                'date_of_call', 'visit_date','date_completed', 
                DB::raw('group_concat(DISTINCT performed_users.name)'),
                'refrigerator_maintenance_calls.notes'
            ])
            ->groupBy('refrigerator_maintenance_calls.id');

        if($this->request->public) {
            $data->where("public_structures.public_structure_category_id1", $this->request->public)
                ->orWhere("public_structures.public_structure_category_id2", $this->request->public)
                ->orWhere("public_structures.public_structure_category_id3", $this->request->public);
        }
        if($this->request->community_id) {
            $data->where("refrigerator_maintenance_calls.community_id", $this->request->community_id);
        }
        if($this->request->date) {
            $data->where("refrigerator_maintenance_calls.date_completed", ">=", $this->request->date);
        }
        if($this->request->call_date) {
            $data->where("refrigerator_maintenance_calls.date_of_call", ">=", $this->request->call_date);
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
        return ["Agent", "Community", "Region", "Sub Region", "Recipient", "Action in English", 
            "Action in Arabic", "Status", "Type", "Call Date", "Visit Date", "Completed Date", "Performed By", 
            "Notes"];
    }

    public function title(): string
    {
        return 'Refrigerator Maintenance Logs';
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