<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell; 
use DB;

class EnergySafetyExport implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, 
    WithStyles, WithCustomStartCell
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
        $query = DB::table('all_energy_meter_safety_checks')
            ->join('all_energy_meters', 'all_energy_meters.id', 
                '=', 'all_energy_meter_safety_checks.all_energy_meter_id')
            ->join('communities', 'all_energy_meters.community_id', '=', 'communities.id')
            ->leftJoin('households', 'all_energy_meters.household_id', '=', 'households.id')
            ->LeftJoin('public_structures', 'all_energy_meters.public_structure_id', 
                'public_structures.id')
            ->join('energy_systems', 'all_energy_meters.energy_system_id', '=', 'energy_systems.id')
            ->join('energy_system_types', 'all_energy_meters.energy_system_type_id', '=', 'energy_system_types.id')
            ->join('meter_cases', 'all_energy_meters.meter_case_id', '=', 'meter_cases.id')
            ->where('all_energy_meter_safety_checks.is_archived', 0)
            ->select('communities.english_name as community', 
                'households.english_name as household', 'public_structures.english_name as public', 
                'energy_system_types.name as energy_type_name',
                'all_energy_meters.meter_number', 'meter_cases.meter_case_name_english',
                'all_energy_meter_safety_checks.rcd_x_phase0', 
                'all_energy_meter_safety_checks.rcd_x_phase1', 
                'all_energy_meter_safety_checks.rcd_x1_phase0', 
                'all_energy_meter_safety_checks.rcd_x1_phase1', 
                'all_energy_meter_safety_checks.rcd_x5_phase0', 
                'all_energy_meter_safety_checks.rcd_x5_phase1', 
                'all_energy_meter_safety_checks.ph_loop', 
                'all_energy_meter_safety_checks.n_loop', 
                'all_energy_meter_safety_checks.visit_date',
                'all_energy_meter_safety_checks.notes',
                );

        if($this->request->community) {

            $query->where("communities.english_name", $this->request->community);
        } 
        if($this->request->system_type) {

            $query->where("energy_system_types.name", $this->request->system_type);
        }
        if($this->request->date_from) {

            $query->where("all_energy_meter_safety_checks.visit_date", ">=", $this->request->date_from);
        }
        if($this->request->date_to) {

            $query->where("all_energy_meter_safety_checks.visit_date", "<=", $this->request->date_to);
        }

        return $query->get();
    }

    public function startCell(): string
    {
        return 'A3';
    } 

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function headings(): array
    {
        return ["Community", "User User", "Public Holder", "System Type", "Meter Number", 
            "Meter Case", "Phase=0", "Phase=180", "Phase=0", "Phase=180", "Phase=0", "Phase=180", 
            "<5", "<5", "Visit Date", "Notes"];
    }

    public function title(): string
    {
        return 'Safety Checks';
    }

    /**
     * Styling
     *
     * @return response()
     */
    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('G1:L1');
        $sheet->mergeCells('M1:N1');
        $sheet->mergeCells('G2:H2');
        $sheet->mergeCells('K2:L2');
        $sheet->mergeCells('I2:J2');

        $sheet->setAutoFilter('A3:P3');

        $sheet->setCellValue('G1', 'RCD');
        $sheet->setCellValue('G2', 'X 0.5');
        $sheet->setCellValue('I2', 'X 1');
        $sheet->setCellValue('K2', 'X 5');
        $sheet->setCellValue('M1', 'Loop');
        $sheet->setCellValue('M2', 'PH-Loop');
        $sheet->setCellValue('N2', 'N-Loop');

        $sheet->getStyle('G1:N1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('G1:N1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        $sheet->getStyle('G2:N2')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('G2:N2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle('G1:N1')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle('G2:N2')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

   
        $sheet->getStyle('G1')->getFill()->applyFromArray
        ([
            'fillType' => 'solid',
            'rotation' => 0, 
            'color' => ['rgb' => '0066ff'],
        ]);

        $sheet->getStyle('G2:L2')->getFill()->applyFromArray
        ([
            'fillType' => 'solid',
            'color' => ['rgb' => 'b3d1ff'],
        ]);

        return [
            // Style the first row as bold text.
            3    => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}