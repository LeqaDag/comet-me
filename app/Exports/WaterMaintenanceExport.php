<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB; 

class WaterMaintenanceExport implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, 
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
        $data = DB::table('h2o_maintenance_calls')
            ->leftJoin('households', 'h2o_maintenance_calls.household_id', 'households.id')
            ->leftJoin('public_structures', 'h2o_maintenance_calls.public_structure_id', 
                'public_structures.id')
            ->join('communities', 'h2o_maintenance_calls.community_id', 'communities.id')
            ->join('maintenance_types', 'h2o_maintenance_calls.maintenance_type_id', 
                '=', 'maintenance_types.id')
            ->join('h2o_maintenance_call_actions', 'h2o_maintenance_calls.id', 
                'h2o_maintenance_call_actions.h2o_maintenance_call_id')
            ->join('maintenance_h2o_actions', 'h2o_maintenance_call_actions.maintenance_h2o_action_id', 
                'maintenance_h2o_actions.id')
            ->leftJoin('h2o_maintenance_call_users', 'h2o_maintenance_calls.id', 
                'h2o_maintenance_call_users.h2o_maintenance_call_id')
            ->leftJoin('users as performed_users', 'h2o_maintenance_call_users.user_id', 
                'performed_users.id')
            ->join('maintenance_statuses', 'h2o_maintenance_calls.maintenance_status_id', 
                '=', 'maintenance_statuses.id')
            ->join('users as recipients', 'h2o_maintenance_calls.user_id', 'recipients.id')
            ->join('regions', 'communities.region_id', '=', 'regions.id')
            ->join('sub_regions', 'communities.sub_region_id', '=', 'sub_regions.id')
            ->where('h2o_maintenance_calls.is_archived', 0)
            ->where('h2o_maintenance_call_actions.is_archived', 0)
            ->select([ 
                DB::raw('IFNULL(households.english_name, public_structures.english_name) 
                    as exported_value'), 
                'communities.english_name as community_name',
                'regions.english_name as region', 'sub_regions.english_name as sub_region',
                'recipients.name as user_name', 'maintenance_statuses.name', 'maintenance_types.type', 
                DB::raw('group_concat(DISTINCT maintenance_h2o_actions.maintenance_action_h2o_english)'),
                DB::raw('group_concat(DISTINCT maintenance_h2o_actions.maintenance_action_h2o)'),
                DB::raw('group_concat(DISTINCT performed_users.name)'),
                'date_of_call', 'date_completed', 'h2o_maintenance_calls.notes'
            ])
            ->groupBy('h2o_maintenance_calls.id');

        if($this->request->public) {
            $data->where("public_structures.public_structure_category_id1", $this->request->public)
                ->orWhere("public_structures.public_structure_category_id2", $this->request->public)
                ->orWhere("public_structures.public_structure_category_id3", $this->request->public);
        }
        if($this->request->community_id) {
            $data->where("h2o_maintenance_calls.community_id", $this->request->community_id);
        }
        if($this->request->date) {
            $data->where("h2o_maintenance_calls.date_completed", ">=", $this->request->date);
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
        return ["Water Holder", "Community", "Region", "Sub Region", "Recipient", 
            "Status", "Type", "Action in English", "Action in Arabic", 
            "Performed By", "Call Date", "Completed Date", "Notes"];
    }

    public function title(): string
    {
        return 'Water Maintenance Logs';
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