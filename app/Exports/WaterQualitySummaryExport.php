<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents; 
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell; 
use DB;

class WaterQualitySummaryExport implements FromCollection, WithHeadings, WithStyles, 
    ShouldAutoSize, WithEvents, WithCustomStartCell
{
    private $phAverage = 0, $fciAverage = 0, $ecAverage = 0, $phMax = 0, $phMin = 0, $fciMax = 0,
        $fciMin = 0, $ecMax = 0, $ecMin = 0, $phMode = 0, $fciMode = 0, $ecMode = 0;
        
    protected $request;

    function __construct($request) {

        $this->request = $request;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $data = DB::table('water_quality_results')
            ->join('communities', 'water_quality_results.community_id', 'communities.id')
            ->join('regions', 'communities.region_id', '=', 'regions.id')
            ->join('sub_regions', 'communities.sub_region_id', '=', 'sub_regions.id')
            ->leftJoin('households', 'water_quality_results.household_id', 'households.id')
            ->leftJoin('public_structures', 'water_quality_results.public_structure_id', 
                '=', 'public_structures.id')
            ->where('water_quality_results.is_archived', 0)
            ->select('water_quality_results.date', 
                'communities.english_name as community_name',
                'households.english_name as english_name', 
                'water_quality_results.date', 
                'water_quality_results.cfu',
                'water_quality_results.fci', 'water_quality_results.ec',
                'water_quality_results.ph', 'water_quality_results.notes');

        //die($data->get()); DB::raw("MAX(fci) AS fci_max, MIN(fci) AS fci_min")

        $phAverage = DB::table('water_quality_results')
            ->join('communities', 'water_quality_results.community_id', 'communities.id')
            ->leftJoin('households', 'water_quality_results.household_id', 'households.id')
            ->where('ph', '!=', "")
            ->select('communities.english_name as community_name',
            'households.english_name as english_name', 
            'water_quality_results.date', 'water_quality_results.ph');

        $fciAverage = DB::table('water_quality_results')
            ->join('communities', 'water_quality_results.community_id', 'communities.id')
            ->leftJoin('households', 'water_quality_results.household_id', 'households.id')
            ->where('fci', '!=', "")
            ->select('communities.english_name as community_name',
            'households.english_name as english_name', 
            'water_quality_results.date', 'water_quality_results.fci');

        $ecAverage = DB::table('water_quality_results')
            ->join('communities', 'water_quality_results.community_id', 'communities.id')
            ->leftJoin('households', 'water_quality_results.household_id', 'households.id')
            ->where('ec', '!=', "")
            ->select('communities.english_name as community_name',
            'households.english_name as english_name', 
            'water_quality_results.date', 'water_quality_results.ec');

        $phMax = DB::table('water_quality_results')
            ->join('communities', 'water_quality_results.community_id', 'communities.id')
            ->leftJoin('households', 'water_quality_results.household_id', 'households.id')
            ->where('ph', '!=', "")
            ->select('communities.english_name as community_name',
            'households.english_name as english_name', 
            'water_quality_results.date', 'water_quality_results.ph');

        $phMin = DB::table('water_quality_results')
            ->join('communities', 'water_quality_results.community_id', 'communities.id')
            ->leftJoin('households', 'water_quality_results.household_id', 'households.id')
            ->where('ph', '!=', "")
            ->select('communities.english_name as community_name',
            'households.english_name as english_name', 
            'water_quality_results.date', 'water_quality_results.ph');

        $fciMax = DB::table('water_quality_results')
            ->join('communities', 'water_quality_results.community_id', 'communities.id')
            ->leftJoin('households', 'water_quality_results.household_id', 'households.id')
            ->where('fci', '!=', "")
            ->select('communities.english_name as community_name',
            'households.english_name as english_name', 
            'water_quality_results.date', 'water_quality_results.fci');

        $fciMin = DB::table('water_quality_results')
            ->join('communities', 'water_quality_results.community_id', 'communities.id')
            ->leftJoin('households', 'water_quality_results.household_id', 'households.id')
            ->where('fci', '!=', "")
            ->select('communities.english_name as community_name',
            'households.english_name as english_name', 
            'water_quality_results.date', 'water_quality_results.fci');

        $ecMax = DB::table('water_quality_results')
            ->join('communities', 'water_quality_results.community_id', 'communities.id')
            ->leftJoin('households', 'water_quality_results.household_id', 'households.id')
            ->where('ec', '!=', "")
            ->select('communities.english_name as community_name',
            'households.english_name as english_name', 
            'water_quality_results.date', 'water_quality_results.ec');

        $ecMin = DB::table('water_quality_results')
            ->join('communities', 'water_quality_results.community_id', 'communities.id')
            ->leftJoin('households', 'water_quality_results.household_id', 'households.id')
            ->where('ec', '!=', "")
            ->select('communities.english_name as community_name',
            'households.english_name as english_name', 
            'water_quality_results.date', 'water_quality_results.ec');

        if($this->request->community) {

            $data->where("communities.english_name", $this->request->community);
            $phAverage->where("communities.english_name", $this->request->community);
            $fciAverage->where("communities.english_name", $this->request->community);
            $ecAverage->where("communities.english_name", $this->request->community);
            $phMax->where("communities.english_name", $this->request->community);
            $phMin->where("communities.english_name", $this->request->community);
            $fciMax->where("communities.english_name", $this->request->community);
            $fciMin->where("communities.english_name", $this->request->community);
            $ecMax->where("communities.english_name", $this->request->community);
            $ecMin->where("communities.english_name", $this->request->community);
        } 
        if($this->request->household) {
            $data->where("households.english_name", $this->request->household);
            $phAverage->where("households.english_name", $this->request->household);
            $fciAverage->where("households.english_name", $this->request->household);
            $ecAverage->where("households.english_name", $this->request->household);
            $phMax->where("households.english_name", $this->request->household);
            $phMin->where("households.english_name", $this->request->household);
            $fciMax->where("households.english_name", $this->request->household);
            $fciMin->where("households.english_name", $this->request->household);
            $ecMax->where("households.english_name", $this->request->household);
            $ecMin->where("households.english_name", $this->request->household);
        }
        if($this->request->from_date) {
            $data->where("water_quality_results.date", ">=", $this->request->from_date);
            $phAverage->where("water_quality_results.date", ">=", $this->request->from_date);
            $fciAverage->where("water_quality_results.date", ">=", $this->request->from_date);
            $ecAverage->where("water_quality_results.date", ">=", $this->request->from_date);
            $phMax->where("water_quality_results.date", ">=", $this->request->from_date);
            $phMin->where("water_quality_results.date", ">=", $this->request->from_date);
            $fciMax->where("water_quality_results.date", ">=", $this->request->from_date);
            $fciMin->where("water_quality_results.date", ">=", $this->request->from_date);
            $ecMax->where("water_quality_results.date", ">=", $this->request->from_date);
            $ecMin->where("water_quality_results.date", ">=", $this->request->from_date);
        }
        if($this->request->to_date) {
            $data->where("water_quality_results.date", "<=", $this->request->to_date);
            $phAverage->where("water_quality_results.date", "<=", $this->request->to_date);
            $fciAverage->where("water_quality_results.date", "<=", $this->request->to_date);
            $ecAverage->where("water_quality_results.date", "<=", $this->request->to_date);
            $phMax->where("water_quality_results.date", "<=", $this->request->to_date);
            $phMin->where("water_quality_results.date", "<=", $this->request->to_date);
            $fciMax->where("water_quality_results.date", "<=", $this->request->to_date);
            $fciMin->where("water_quality_results.date", "<=", $this->request->to_date);
            $ecMax->where("water_quality_results.date", "<=", $this->request->to_date);
            $ecMin->where("water_quality_results.date", "<=", $this->request->to_date);
        } 

        $this->phAverage = $phAverage->avg('ph');
        $this->fciAverage = $fciAverage->avg('fci');
        $this->ecAverage = $ecAverage->avg('ec');
        $this->phMax = $phMax->max('ph');
        $this->phMin = $phMin->min('ph');
        $this->fciMax = $fciMax->max('fci');
        $this->fciMin = $fciMin->min('fci');
        $this->ecMax = $ecMax->max('ec');
        $this->ecMin = $ecMin->min('ec');

        return $data->get();
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function headings(): array
    {
        return ["Date", "Community", "Facility User", "bacterial contamination result  (cfu)", 
            "Free Chlorine (mg/l)", "pH", "Electrical Conductivity (ms/cm)"];
    }


    public function startCell(): string
    {
        return 'B7';
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
   
                $event->sheet->getDelegate()->getStyle('A1:C1')
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            },
        ];
    }

    /**
     * Styling
     *
     * @return response()
     */
    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:H1');
        $sheet->mergeCells('B2:E2');
        $sheet->mergeCells('B3:E3');
        $sheet->mergeCells('B4:E4');
        $sheet->mergeCells('B5:E5');

        $sheet->setCellValue('A1', 'COMET - ME Water Quality Programe ');
        $sheet->setCellValue('B3', 'Average');
        $sheet->setCellValue('B4', 'Max');
        $sheet->setCellValue('B5', 'Min');
        $sheet->setCellValue('F3', $this->phAverage);
        $sheet->setCellValue('G3', $this->fciAverage);
        $sheet->setCellValue('H3', $this->ecAverage);
        $sheet->setCellValue('G4', $this->phMax);
        $sheet->setCellValue('G5', $this->phMin);
        $sheet->setCellValue('F4', $this->fciMax);
        $sheet->setCellValue('F5', $this->fciMin);
        $sheet->setCellValue('H4', $this->ecMax);
        $sheet->setCellValue('H5', $this->ecMin);

        $sheet->setAutoFilter('B6:H6');
        $sheet->getStyle('B6:G6')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('B6:G6')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle('B3:H3')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle('B3:H3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('B3:H3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('B4:H4')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle('B4:H4')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('B4:H4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('B5:H5')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle('B5:H5')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('B5:H5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true, 'size' => 14]],
            6    => ['font' => ['bold' => true, 'size' => 12]],

            // Styling an entire column.
            'B2' => ['font' => ['italic' => true, 'size' => 13]],
            'B3' => ['font' => ['italic' => true, 'size' => 13]],
            'B4' => ['font' => ['italic' => true, 'size' => 13]],

            // Styling an entire column.
            //'C'  => ['font' => ['size' => 16]],
        ];
    }
}