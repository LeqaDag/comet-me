<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB;

class InternetActionExport implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, 
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
        $data = DB::table('internet_actions')
            ->join('internet_issues', 'internet_actions.internet_issue_id', 
                'internet_issues.id')
            ->join('internet_issue_types', 'internet_issues.internet_issue_type_id', 
                'internet_issue_types.id')
            ->select(
                'internet_actions.english_name as action_english',
                'internet_actions.arabic_name as action_arabic',
                'internet_issues.english_name as english_name', 
                'internet_issues.arabic_name as arabic_name', 
                'internet_issue_types.type',
                'internet_actions.notes'
            ); 

        if($this->request->internet_issue_id) {

            $data->where("internet_actions.internet_issue_id", $this->request->internet_issue_id);
        } 
        
        if($this->request->internet_issue_type_id) {

            $data->where("internet_issue_types.id", $this->request->internet_issue_type_id);
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
        return ["Action (English)", "Action (Arabic)", "Issue (English)", "Issue (Arabic)",
            "Type", "Notes"];
    }

    public function title(): string
    {
        return 'Internet Actions';
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