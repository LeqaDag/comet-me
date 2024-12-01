<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB;

class RefrigeratorIssuesExport implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, 
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
        $data = DB::table('refrigerator_issues')
            ->join('refrigerator_actions', 'refrigerator_issues.refrigerator_action_id', 'refrigerator_actions.id')
            ->join('action_categories', 'refrigerator_actions.action_category_id', 'action_categories.id')
            ->where('refrigerator_issues.is_archived', 0) 
            ->where('refrigerator_actions.is_archived', 0)
            ->select(
                'refrigerator_issues.english_name as english_name', 
                'refrigerator_issues.arabic_name as arabic_name', 
                'refrigerator_actions.english_name as action_english',
                'refrigerator_actions.arabic_name as action_arabic',
                'action_categories.english_name as category',
                'refrigerator_issues.notes'
            ); 

        if($this->request->action_name) {

            $data->where("refrigerator_actions.id", $this->request->action_name);
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
            "Notes"];
    }

    public function title(): string
    {
        return 'Refrigerator Issues';
    }

    /**
     * Styling
     *
     * @return response()
     */
    public function styles(Worksheet $sheet)
    {
        $sheet->setAutoFilter('A1:F1');

        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}