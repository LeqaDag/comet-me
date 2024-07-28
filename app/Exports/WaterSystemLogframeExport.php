<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB; 

class WaterSystemLogframeExport implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, 
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
        $query = DB::table('water_system_log_frames')
            ->join('water_systems', 'water_system_log_frames.water_system_id', 'water_systems.id')
            ->join('water_system_types', 'water_system_types.id', 'water_systems.water_system_type_id')
            ->where('water_system_log_frames.is_archived', 0)
            ->select(
                'water_systems.name', 'water_system_types.type',
                'water_system_log_frames.test_date', 'water_system_log_frames.leakage', 
                'water_system_log_frames.reachability', 'water_system_log_frames.free_chlorine', 
                'water_system_log_frames.ph', 'water_system_log_frames.ec', 
                'water_system_log_frames.meter_reading', 'water_system_log_frames.daily_avg_cluster_consumption', 
                'water_system_log_frames.daily_avg_capita_consumption', 'water_system_log_frames.notes'
            );

        if($this->request->water_system_id) { 

            $query->where("water_systems.name", $this->request->water_system_id);
        } 
        if($this->request->year_from) {

            $yearFrom = $this->request->year_from;

            // Extract the year from test_date and compare with $yearFrom
            $query->whereRaw('YEAR(water_system_log_frames.test_date) = ?', [$yearFrom]);
        }

        return $query->get();
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function headings(): array
    {
        return ["Water System", "Water System Type", "Test Date", "Leakage", "Reachability (%)", 
            "Free Chlorine (PPM)", "PH", "Electrical Conductivity EC (MC)", "Meter Reading", 
            "Daily Avg Cluster Consumption (m3/cluster)", "Daily Avg Capita Consumption (L/day)", 
            "Notes"];
    }

    public function title(): string
    {
        return 'Water System Logframe';
    }

    /**
     * Styling
     *
     * @return response()
     */
    public function styles(Worksheet $sheet)
    {
        $sheet->setAutoFilter('A1:L1');

        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}