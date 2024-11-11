<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB;

class EnergyIssuesExport implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, 
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
        $data = DB::table('energy_issues')
            ->join('energy_maintenance_issue_types', 'energy_issues.energy_maintenance_issue_type_id', 
                'energy_maintenance_issue_types.id')
            ->join('energy_actions', 'energy_issues.energy_action_id', 'energy_actions.id')
            ->join('energy_action_categories', 'energy_actions.energy_action_category_id', 'energy_action_categories.id')
            ->where('energy_issues.is_archived', 0) 
            ->where('energy_actions.is_archived', 0)
            ->select(
                'energy_issues.english_name as english_name', 
                'energy_issues.arabic_name as arabic_name', 
                'energy_actions.english_name as action_english',
                'energy_actions.arabic_name as action_arabic',
                'energy_action_categories.english_name as category',
                'energy_maintenance_issue_types.name as type',
                'energy_issues.notes'
            ); 

        if($this->request->action_name) {

            $data->where("energy_actions.id", $this->request->action_name);
        } 

        if($this->request->issue_type) {

            $data->where("energy_maintenance_issue_types.id", $this->request->issue_type);
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
        return ["Issue (English)", "Issue (Arabic)", "Action (English)", "Action (Arabic)", "Action Category", 
            "Issue Type", "Notes"];
    }

    public function title(): string
    {
        return 'Energy Issues';
    }

    /**
     * Styling
     *
     * @return response()
     */
    public function styles(Worksheet $sheet)
    {
        $sheet->setAutoFilter('A1:G1');

        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}