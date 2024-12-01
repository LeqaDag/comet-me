<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB;

class WaterIssuesExport implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, 
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
        $data = DB::table('water_issues')
            ->join('water_actions', 'water_issues.water_action_id', 'water_actions.id')
            ->join('action_categories', 'water_actions.action_category_id', 'action_categories.id')
            ->where('water_issues.is_archived', 0) 
            ->where('water_actions.is_archived', 0)
            ->select(
                'water_issues.english_name as english_name', 
                'water_issues.arabic_name as arabic_name', 
                'water_actions.english_name as action_english',
                'water_actions.arabic_name as action_arabic',
                'action_categories.english_name as category',
                'water_issues.notes'
            ); 

        if($this->request->action_name) {

            $data->where("water_actions.id", $this->request->action_name);
        } 

        if($this->request->action_category) {

            $data->where("action_categories.id", $this->request->action_category);
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
        return 'Water Issues';
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