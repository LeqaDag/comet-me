<?php

namespace App\Exports\Household;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize; 
use DB; 

class MissingAllInfo implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, WithStyles
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
        $data = DB::table('households')
            ->join('communities', 'households.community_id', 'communities.id')
            ->leftJoin('energy_system_cycles', 'energy_system_cycles.id', 'communities.energy_system_cycle_id')
            ->leftJoin('all_energy_meters', 'households.id', 'all_energy_meters.household_id')
            ->where('households.is_archived', 0) 
            ->where('internet_holder_young', 0) 
            ->whereNull('households.number_of_male')
            ->whereNull('households.number_of_female')
            ->whereNull('households.number_of_children')
            ->whereNull('households.number_of_adults')
            ->select(
                'households.english_name as english_name', 
                'households.arabic_name as arabic_name', 
                'communities.english_name as community_name',
                'energy_system_cycles.name as cycle',
                'households.phone_number',  
                'water_system_status', 
                'internet_system_status'
            )
            ->groupBy('households.id');

        return $data->get();
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function headings(): array
    {
        return ["English Name", "Arabic Name", "Community", "Cycle Year", "Phone Number", 
            "Water System Status", "Internet System Status"];
    }

    public function title(): string
    {
        return 'Missing All Informations';
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