<?php

namespace App\Exports\Maintenance;

use App\Models\EnergyUser;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB;

class EnergyMaintenanceLog implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, 
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
            ->leftJoin('energy_systems', 'electricity_maintenance_calls.energy_system_id', 
                'energy_systems.id')
            ->leftJoin('energy_turbine_communities', 'electricity_maintenance_calls.energy_turbine_community_id', 
                'energy_turbine_communities.id')
            ->leftJoin('energy_generator_communities', 'electricity_maintenance_calls.energy_generator_community_id', 
                    'energy_generator_communities.id')
            ->join('communities', 'electricity_maintenance_calls.community_id', 'communities.id')
            ->join('regions', 'communities.region_id','regions.id')
            ->join('sub_regions', 'communities.sub_region_id','sub_regions.id')
            ->join('maintenance_types', 'electricity_maintenance_calls.maintenance_type_id', 
               'maintenance_types.id')
            ->join('electricity_maintenance_call_actions', 'electricity_maintenance_calls.id', 
                'electricity_maintenance_call_actions.electricity_maintenance_call_id')
            ->leftJoin('energy_maintenance_actions', 'energy_maintenance_actions.id',
                'electricity_maintenance_call_actions.energy_maintenance_action_id')
            ->leftJoin('energy_maintenance_issues', 'energy_maintenance_issues.id',
                'energy_maintenance_actions.energy_maintenance_issue_id')
            ->leftJoin('maintenance_statuses', 'electricity_maintenance_calls.maintenance_status_id', 
               'maintenance_statuses.id')
            ->leftJoin('electricity_maintenance_call_users', 'electricity_maintenance_calls.id', 
                'electricity_maintenance_call_users.electricity_maintenance_call_id')
            ->leftJoin('users as performed_users', 'electricity_maintenance_call_users.user_id', 
                'performed_users.id')
            ->leftJoin('users as recipients', 'electricity_maintenance_calls.user_id', 'recipients.id')
            ->where('electricity_maintenance_calls.is_archived', 0)
            ->select([
                DB::raw('COALESCE(households.english_name, public_structures.english_name, 
                    energy_systems.name, energy_turbine_communities.name, energy_generator_communities.name) 
                    as exported_value'),
                'communities.english_name as community_name',
                'regions.english_name as region', 'sub_regions.english_name as sub_region',
                'recipients.name as recipient_name',
                'maintenance_statuses.name', 'maintenance_types.type',
                DB::raw('group_concat(DISTINCT energy_maintenance_issues.english_name)'),
                DB::raw('group_concat(DISTINCT energy_maintenance_actions.english_name)'),
                DB::raw('group_concat(DISTINCT energy_maintenance_actions.arabic_name)'),
                'date_of_call', 'date_completed', 
                DB::raw('group_concat(DISTINCT performed_users.name)'),
                'electricity_maintenance_calls.last_hour', 'electricity_maintenance_calls.run_hour', 
                'electricity_maintenance_calls.run_performed_hour',
                'electricity_maintenance_calls.notes'
            ])
            ->groupBy('electricity_maintenance_calls.id');


        if($this->request->public) {
            $data->where("public_structures.public_structure_category_id1", $this->request->public)
                ->orWhere("public_structures.public_structure_category_id2", $this->request->public)
                ->orWhere("public_structures.public_structure_category_id3", $this->request->public);
        }
        if($this->request->community_id) {
            $data->where("electricity_maintenance_calls.community_id", $this->request->community_id);
        }
        if($this->request->issue) {
            $data->where("energy_maintenance_issues.id", $this->request->issue);
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
        return ["Agent", "Community", "Region", "Sub Region", "Recipient", "Status", "Type", 
            "Issue", "Action in English", "Action in Arabic", "Call Date", "Completed Date", 
            "Performed By", "Last Run hours", "Run hours", "Run hours to do maintenance", "Notes"];
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
        $sheet->setAutoFilter('A1:Q1');

        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}