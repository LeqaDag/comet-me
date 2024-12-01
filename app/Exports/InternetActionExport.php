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
            ->join('action_categories', 'action_categories.id',
                'internet_actions.action_category_id')
            ->where('internet_actions.is_archived', 0)
            ->select(
                'internet_actions.english_name as action_english',
                'internet_actions.arabic_name as action_arabic',
                'action_categories.english_name as english_name', 
                'action_categories.arabic_name as arabic_name', 
                'internet_actions.notes'
            ); 

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
        return ["Action (English)", "Action (Arabic)", "Action Category (English)", "Action Category (Arabic)", 
            "Notes"];
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
        $sheet->setAutoFilter('A1:E1');

        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}