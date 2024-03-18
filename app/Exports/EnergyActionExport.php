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
        $data = DB::table('energy_maintenance_actions')
        ->join('energy_maintenance_issues', 'energy_maintenance_issues.id',
            'energy_maintenance_actions.energy_maintenance_issue_id')
        ->join('energy_maintenance_issue_types', 'energy_maintenance_issue_types.id',
            'energy_maintenance_actions.energy_maintenance_issue_type_id')
            ->select(
                'energy_maintenance_actions.english_name as action_english',
                'energy_maintenance_actions.arabic_name as action_arabic',
                'energy_maintenance_issues.english_name as english_name', 
                'energy_maintenance_issues.arabic_name as arabic_name', 
                'energy_maintenance_issue_types.name',
                'energy_maintenance_actions.notes'
            ); 

        if($this->request->energy_maintenance_issue_id) {

            $data->where("energy_maintenance_issues.id", $this->request->energy_maintenance_issue_id);
        } 
        
        if($this->request->energy_maintenance_issue_type_id) {

            $data->where("energy_maintenance_issue_types.id", $this->request->energy_maintenance_issue_type_id);
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
        return 'Energy Actions';
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