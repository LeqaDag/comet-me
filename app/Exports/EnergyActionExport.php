<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB;

class EnergyActionExport implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, 
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
        $data = DB::table('energy_actions')
            ->join('energy_action_categories', 'energy_action_categories.id',
                'energy_actions.energy_action_category_id')
            ->where('energy_actions.is_archived', 0)
            ->select(
                'energy_actions.english_name as action_english',
                'energy_actions.arabic_name as action_arabic',
                'energy_action_categories.english_name as english_name', 
                'energy_action_categories.arabic_name as arabic_name', 
                'energy_actions.notes'
            ); 

        if($this->request->action_category) {

            $data->where("energy_maintenance_issues.id", $this->request->action_category);
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
        return 'Energy Actions';
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