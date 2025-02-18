<?php

namespace App\Exports\DataCollection;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use DB;
 
class RequestedHouseholds implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, 
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
        $households = DB::table('households') 
            ->join('communities', 'households.community_id', 'communities.id')
            ->join('regions', 'communities.region_id', 'regions.id')
            ->join('sub_regions', 'communities.sub_region_id', 'sub_regions.id')
            ->leftJoin('professions', 'households.profession_id', 'professions.id')
            ->leftJoin('energy_system_types', 'households.energy_system_type_id', 'energy_system_types.id')
            ->leftJoin('all_energy_meters', 'all_energy_meters.household_id', 'households.id')
            ->leftJoin('users', 'households.referred_by_id', 'users.id')
            ->where('households.is_archived', 0)  
            ->where('communities.is_archived', 0)
            ->where('internet_holder_young', 0) 
            ->where('households.household_status_id', 5) 
            ->select(
                DB::raw('"household" as list_name'), 
                'households.comet_id as name',
                'households.english_name as label',
                'households.english_name as label_en',
                'households.arabic_name as label_ar',
                'regions.english_name as region',
                'sub_regions.english_name as sub_region',
                'communities.english_name as community',
                DB::raw('CASE 
                        WHEN households.request_date IS NOT NULL THEN households.request_date 
                        ELSE DATE(households.created_at) 
                    END as request_date
                '),
                DB::raw("CASE WHEN all_energy_meters.is_main = 'No' THEN 'Served'
                    ELSE 'Service requested' END AS status"),
                'users.name as referred_by',
                'energy_system_types.name as energy_system_type', 'households.phone_number'
            )
            ->get();
        
        return $households;
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function headings(): array
    {
        return ['list_name', 'name', 'label', 'label_en', 'label_ar', 'region', 'sub_region', 'community', 'request_date',
            'status', 'referred_by', 'energy_system_type', 'phone_number'];
    }


    public function title(): string
    {
        return 'requested_households';
    }

    public function startCell(): string
    {
        return 'A1';
    } 


    /**
     * Styling
     *
     * @return response()
     */
    public function styles(Worksheet $sheet)
    {
        $sheet->setAutoFilter('A1:O1');

        return [
            // Style the first row as bold text.
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}