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
use App\Models\AllMissingMeter;
use App\Models\AllEnergyPurchaseMeter;
use DB;
use Carbon\Carbon;

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
        //Meters with vending data
        // $withVending = DB::table('all_energy_purchase_meters')
        //     ->join('all_energy_meters', 'all_energy_purchase_meters.all_energy_meter_id', 'all_energy_meters.id')
        //     ->where('all_energy_meters.is_archived', 0)
        //     ->distinct()
        //     ->pluck('all_energy_meters.meter_number')
        //     ->toArray(); 


        // $missingMeters = DB::table('all_energy_meters')
        //     ->join('communities', 'all_energy_meters.community_id', 'communities.id')
        //     ->join('regions', 'communities.region_id', 'regions.id')
        //     ->join('energy_systems', 'all_energy_meters.energy_system_id', 'energy_systems.id')
        //     ->join('energy_system_types', 'all_energy_meters.energy_system_type_id', 'energy_system_types.id')
        //     ->leftJoin('meter_cases', 'all_energy_meters.meter_case_id', 'meter_cases.id')
        //     ->leftJoin('households', 'households.id', 'all_energy_meters.household_id')
        //     ->leftJoin('public_structures', 'public_structures.id', 'all_energy_meters.public_structure_id')
        //     ->where('all_energy_meters.is_archived', 0)
        //     ->where('all_energy_meters.meter_number', "!=", 0)
        //     ->whereNotIn('all_energy_meters.meter_number', $withVending)
        //     ->select(
        //         DB::raw('IFNULL(households.english_name, public_structures.english_name) as exported_value'),
        //         'all_energy_meters.id as all_energy_meter_id',
        //         'all_energy_meters.meter_number',
        //         'communities.id as community',
        //         'meter_cases.meter_case_name_english'
        //     )
        //     ->get();
        

        // foreach ($missingMeters as $meter) {

        //     $missingMeter = new AllMissingMeter();
        //     $missingMeter->meter_number =  $meter->meter_number;
        //     $missingMeter->all_energy_meter_id = $meter->all_energy_meter_id;
        //     $missingMeter->community_id = $meter->community;
        //     $missingMeter->save();
        // }
            

        $allEnergyPurchaseMeters = AllEnergyPurchaseMeter::get();

        foreach($allEnergyPurchaseMeters as $allEnergyPurchaseMeter) {

            $firstDate1 = Carbon::parse($allEnergyPurchaseMeter->purchase_date1);
            $todayDate =  Carbon::parse('2025-05-01'); 
            $differenceInDays1 = $todayDate->diffInDays($firstDate1);

            $firstDate2 = Carbon::parse($allEnergyPurchaseMeter->purchase_date2);
            $differenceInDays2 = $todayDate->diffInDays($firstDate2);

            $firstDate3 = Carbon::parse($allEnergyPurchaseMeter->purchase_date3);
            $differenceInDays3 = $todayDate->diffInDays($firstDate3);


            $allEnergyPurchaseMeter->days1 = $differenceInDays1;
            $allEnergyPurchaseMeter->days2 = $differenceInDays2;
            $allEnergyPurchaseMeter->days3 = $differenceInDays3;
            $allEnergyPurchaseMeter->save();
        }

        $query = DB::table('all_energy_purchase_meters')
            ->join('all_energy_meters', 'all_energy_purchase_meters.all_energy_meter_id', 'all_energy_meters.id')
            ->join('communities', 'all_energy_meters.community_id', 'communities.id')
            ->join('regions', 'communities.region_id', 'regions.id')
            ->join('energy_systems', 'all_energy_meters.energy_system_id', 'energy_systems.id')
            ->join('energy_system_types', 'all_energy_meters.energy_system_type_id', 'energy_system_types.id')
            ->leftJoin('meter_cases', 'all_energy_meters.meter_case_id', 'meter_cases.id')
            ->leftJoin('households', 'households.id', 'all_energy_meters.household_id')
            ->leftJoin('public_structures', 'public_structures.id', 'all_energy_meters.public_structure_id')
            ->select(
                DB::raw('IFNULL(households.english_name, public_structures.english_name) as exported_value'),
                'all_energy_meters.meter_number',
                'communities.english_name as community',
                'regions.english_name as region',
                'energy_system_types.name as energy_type_name',
                'all_energy_meters.daily_limit',
                'all_energy_meters.installation_date',
                'all_energy_purchase_meters.purchase_date1', 
                'all_energy_purchase_meters.purchase_date2', 
                'all_energy_purchase_meters.purchase_date3',
                'all_energy_purchase_meters.days1', 
                'all_energy_purchase_meters.days2', 
                'all_energy_purchase_meters.days3',
                DB::raw('CASE 
                    WHEN all_energy_purchase_meters.days1 > 30 AND all_energy_purchase_meters.days1 < 60 THEN "Over month"
                    WHEN all_energy_purchase_meters.days1 > 60 AND all_energy_purchase_meters.days1 < 120 THEN "Over 2 months"
                    WHEN all_energy_purchase_meters.days1 > 120 THEN "Over 3 months"
                    ELSE "Within a month" 
                END as days1_flag'),

                    DB::raw('CASE 
                    WHEN all_energy_purchase_meters.days2 > 30 AND all_energy_purchase_meters.days2 < 60 THEN "Over month"
                    WHEN all_energy_purchase_meters.days2 > 60 AND all_energy_purchase_meters.days2 < 120 THEN "Over 2 months"
                    WHEN all_energy_purchase_meters.days2 > 120 THEN "Over 3 months"
                    ELSE "Within a month" 
                END as days2_flag'),
                    DB::raw('CASE 
                    WHEN all_energy_purchase_meters.days3 > 30 AND all_energy_purchase_meters.days3 < 60 THEN "Over month"
                    WHEN all_energy_purchase_meters.days3 > 60 AND all_energy_purchase_meters.days3 < 120 THEN "Over 2 months"
                    WHEN all_energy_purchase_meters.days3 > 120 THEN "Over 3 months"
                    ELSE "Within a month" 
                END as days3_flag'),

                'meter_cases.meter_case_name_english',
            );


        // $subquery = DB::table('all_energy_vending_meters as aevm')
        //     ->select(DB::raw('MAX(aevm.last_purchase_date) as last_purchase_date'), 'aevm.all_energy_meter_id')
        //     ->groupBy('aevm.all_energy_meter_id');
    
        // $query = DB::table('all_energy_vending_meters')
        //     ->joinSub($subquery, 'latest', function ($join) {
        //         $join->on('all_energy_vending_meters.all_energy_meter_id', '=', 'latest.all_energy_meter_id')
        //             ->on('all_energy_vending_meters.last_purchase_date', '=', 'latest.last_purchase_date');
        //     })
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
        //         DB::raw('IFNULL(households.english_name, public_structures.english_name) as exported_value'),
        //         'all_energy_meters.meter_number',
        //         'communities.english_name as community',
        //         'regions.english_name as region',
        //         'energy_system_types.name as energy_type_name',
        //         'all_energy_meters.daily_limit',
        //         'all_energy_meters.installation_date',
        //         DB::raw('DATE(all_energy_vending_meters.last_purchase_date) as last_purchase_date'), 
        //         'all_energy_vending_meters.days',
        //         DB::raw('CASE 
        //             WHEN all_energy_vending_meters.days > 30 AND all_energy_vending_meters.days < 60 THEN "Over month"
        //             WHEN all_energy_vending_meters.days > 60 AND all_energy_vending_meters.days < 120 THEN "Over 2 months"
        //             WHEN all_energy_vending_meters.days > 120 THEN "Over 3 months"
        //             ELSE "Within a month" 
        //         END as days_flag'),
        //         'meter_cases.meter_case_name_english'
        //     );
        

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
            "Energy System Type", "Daily Limit", "Installation Date",  
            "Last Purchase Date 1", "Last Purchase Date 2", "Last Purchase Date 3", 
            "Days Purchase 1", "Days Purchase 2", "Days Purchase 3", "Days Purchase Flag 1", 
            "Days Purchase Flag 2", "Days Purchase Flag 3", "Meter Case"];
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