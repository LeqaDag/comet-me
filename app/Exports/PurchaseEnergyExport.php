<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents; 
use Maatwebsite\Excel\Events\AfterSheet;
use DB;
 
class PurchaseEnergyExport implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, 
    WithStyles, WithEvents
{
    protected $data; 

    function __construct($data) {

        $this->data = $data;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()  
    {
        // $query = DB::table('all_energy_vending_meters')
        //     ->join('all_energy_meters', 'all_energy_vending_meters.all_energy_meter_id', 'all_energy_meters.id')
        //     ->join('communities', 'all_energy_meters.community_id', 'communities.id')
        //     ->join('regions', 'communities.region_id', 'regions.id')
        //     ->join('energy_systems', 'all_energy_meters.energy_system_id', 'energy_systems.id')
        //     ->join('energy_system_types', 'all_energy_meters.energy_system_type_id', 'energy_system_types.id')
        //     ->leftJoin('meter_cases', 'all_energy_meters.meter_case_id', 'meter_cases.id')
        //     ->leftJoin('households', 'households.id', 'all_energy_meters.household_id')
        //     ->leftJoin('public_structures', 'public_structures.id', 'all_energy_meters.public_structure_id')
        //     ->where('all_energy_meters.is_archived', 0)
        //     ->select(
        //         DB::raw('IFNULL(households.english_name, public_structures.english_name) 
        //             as exported_value'),
        //         'all_energy_meters.meter_number',
        //         'communities.english_name as community',
        //         'regions.english_name as region',
        //         'energy_system_types.name as energy_type_name',
        //         'all_energy_meters.daily_limit',
        //         'all_energy_meters.installation_date',
        //         DB::raw('DATE(all_energy_vending_meters.last_purchase_date) as last_purchase_date'), 
        //         'all_energy_vending_meters.days',
        //         DB::raw('CASE 
        //             WHEN all_energy_vending_meters.days > 30 && all_energy_vending_meters.days < 60 THEN "Over month"
        //             WHEN all_energy_vending_meters.days > 60  && all_energy_vending_meters.days < 120 THEN "Over 2 months"
        //             WHEN all_energy_vending_meters.days > 120 THEN "Over 3 months"
        //             ELSE "Within a month" 
        //         END as days_flag'),
        //         'meter_cases.meter_case_name_english'
        //     )
        //     ->groupBy("all_energy_meters.meter_number");
        
        $subquery = DB::table('all_energy_vending_meters as aevm')
            ->select(DB::raw('MAX(aevm.last_purchase_date) as last_purchase_date'), 'aevm.all_energy_meter_id')
            ->groupBy('aevm.all_energy_meter_id');
    
        $query = DB::table('all_energy_vending_meters')
            ->joinSub($subquery, 'latest', function ($join) {
                $join->on('all_energy_vending_meters.all_energy_meter_id', '=', 'latest.all_energy_meter_id')
                    ->on('all_energy_vending_meters.last_purchase_date', '=', 'latest.last_purchase_date');
            })
            ->join('all_energy_meters', 'all_energy_vending_meters.all_energy_meter_id', 'all_energy_meters.id')
            ->join('communities', 'all_energy_meters.community_id', 'communities.id')
            ->join('regions', 'communities.region_id', 'regions.id')
            ->join('energy_systems', 'all_energy_meters.energy_system_id', 'energy_systems.id')
            ->join('energy_system_types', 'all_energy_meters.energy_system_type_id', 'energy_system_types.id')
            ->leftJoin('meter_cases', 'all_energy_meters.meter_case_id', 'meter_cases.id')
            ->leftJoin('households', 'households.id', 'all_energy_meters.household_id')
            ->leftJoin('public_structures', 'public_structures.id', 'all_energy_meters.public_structure_id')
            ->where('all_energy_meters.is_archived', 0)
            ->select(
                DB::raw('IFNULL(households.english_name, public_structures.english_name) as exported_value'),
                'all_energy_meters.meter_number',
                'communities.english_name as community',
                'regions.english_name as region',
                'energy_system_types.name as energy_type_name',
                'all_energy_meters.daily_limit',
                'all_energy_meters.installation_date',
                DB::raw('DATE(all_energy_vending_meters.last_purchase_date) as last_purchase_date'), 
                'all_energy_vending_meters.days',
                DB::raw('CASE 
                    WHEN all_energy_vending_meters.days > 30 AND all_energy_vending_meters.days < 60 THEN "Over month"
                    WHEN all_energy_vending_meters.days > 60 AND all_energy_vending_meters.days < 120 THEN "Over 2 months"
                    WHEN all_energy_vending_meters.days > 120 THEN "Over 3 months"
                    ELSE "Within a month" 
                END as days_flag'),
                'meter_cases.meter_case_name_english'
            );
        

        return $query->get();
    } 

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function headings(): array
    {
        return [
            "Energy Holder (User/Public)", "Meter Number", "Community", "Region", 
            "Energy System Type", "Daily Limit", "Installation Date", "Last Purchase Date", 
            "Days", "Days Flag", "Meter Case"];
    }

    public function title(): string
    {
        return 'Purchase Report';
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
              
                $event->sheet->getDelegate()->freezePane('A2');  
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
        $sheet->setAutoFilter('A1:K1');

        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}