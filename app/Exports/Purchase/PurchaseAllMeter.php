<?php

namespace App\Exports\Purchase;

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

class PurchaseAllMeter implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, 
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
        $records = DB::table('all_energy_purchase_meters as aepm')
            ->leftJoin('all_energy_meters as aem', 'aepm.all_energy_meter_id', '=', 'aem.id')
            ->where(function ($query) {
                $query->whereNull('aem.id')              // No matching meter
                    ->orWhere('aem.is_archived', 1);   // Or archived meter
            })
            ->select('aepm.meter_number') // Optional: select fields you need
            ->get();

        //die($records );

        // $allEnergyPurchaseMeters = AllEnergyPurchaseMeter::get();

        // foreach($allEnergyPurchaseMeters as $allEnergyPurchaseMeter) {

        //     $firstDate1 = Carbon::parse($allEnergyPurchaseMeter->purchase_date1);
        //     $todayDate =  Carbon::parse('2025-05-01'); 
        //     $differenceInDays1 = $todayDate->diffInDays($firstDate1);

        //     $firstDate2 = Carbon::parse($allEnergyPurchaseMeter->purchase_date2);
        //     $differenceInDays2 = $todayDate->diffInDays($firstDate2);

        //     $firstDate3 = Carbon::parse($allEnergyPurchaseMeter->purchase_date3);
        //     $differenceInDays3 = $todayDate->diffInDays($firstDate3);


        //     $allEnergyPurchaseMeter->days1 = $differenceInDays1;
        //     // $allEnergyPurchaseMeter->days2 = $differenceInDays2;
        //     // $allEnergyPurchaseMeter->days3 = $differenceInDays3;
        //     $allEnergyPurchaseMeter->save();
        // }

        $query = DB::table('all_energy_purchase_meters')
            ->join('all_energy_meters', 'all_energy_purchase_meters.all_energy_meter_id', 'all_energy_meters.id')
            ->join('communities', 'all_energy_meters.community_id', 'communities.id')
            ->join('regions', 'communities.region_id', 'regions.id')
            ->join('energy_systems', 'all_energy_meters.energy_system_id', 'energy_systems.id')
            ->join('energy_system_types', 'all_energy_meters.energy_system_type_id', 'energy_system_types.id')
            ->leftJoin('meter_cases', 'all_energy_meters.meter_case_id', 'meter_cases.id')
            ->leftJoin('households', 'households.id', 'all_energy_meters.household_id')
            ->leftJoin('public_structures', 'public_structures.id', 'all_energy_meters.public_structure_id')
            ->leftJoin('compounds', 'compounds.community_id', 'communities.id')
            ->where('all_energy_meters.is_archived', 0)
            ->select(
                DB::raw("CASE 
                    WHEN households.english_name IS NOT NULL THEN 'Household'
                    WHEN public_structures.english_name IS NOT NULL AND public_structures.comet_meter = 0 
                        THEN 'Public Structure'
                    WHEN public_structures.english_name IS NOT NULL AND public_structures.comet_meter = 1 
                        THEN 'Comet Internal'
                    ELSE 'Unknown'
                END as agent_type"),
                DB::raw('IFNULL(households.english_name, public_structures.english_name) as exported_value'),
                'all_energy_meters.meter_number',
                'communities.english_name as community', 
                'compounds.english_name as compound',
                'regions.english_name as region',
                'energy_system_types.name as energy_type_name',
                'energy_systems.name as energy_name',
                'all_energy_meters.daily_limit',
                'all_energy_meters.installation_date',
                'all_energy_purchase_meters.purchase_date1', 
                'all_energy_purchase_meters.purchase_date2', 
                'all_energy_purchase_meters.purchase_date3',

                'all_energy_purchase_meters.days1', 

                DB::raw('CASE 
                    WHEN all_energy_purchase_meters.days1 <= 90 THEN "1 to 90 days"
                    WHEN all_energy_purchase_meters.days1 <= 180 THEN "91 to 180 days"
                    WHEN all_energy_purchase_meters.days1 <= 270 THEN "181 to 270 days"
                    WHEN all_energy_purchase_meters.days1 <= 360 THEN "271 to 360 days"
                    WHEN all_energy_purchase_meters.days1 <= 720 THEN "361 to 720 days"
                    WHEN all_energy_purchase_meters.days1 <= 1080 THEN "721 to 1080"
                    WHEN all_energy_purchase_meters.days1 <= 1440 THEN "1081 to 1440"
                    WHEN all_energy_purchase_meters.days1 <= 1800 THEN "1441 to 1800"
                    WHEN all_energy_purchase_meters.days1 <= 2160 THEN "1801 to 2160"
                    WHEN all_energy_purchase_meters.days1 <= 2420 THEN "2161 to 2420"
                    WHEN all_energy_purchase_meters.days1 <= 2780 THEN "2421 to 2780"
                    ELSE "more than 2780" 
                END as days1_range'),

                DB::raw('CASE 
                    WHEN all_energy_purchase_meters.days1 <= 90 THEN 1
                    WHEN all_energy_purchase_meters.days1 <= 180 THEN 2
                    WHEN all_energy_purchase_meters.days1 <= 270 THEN 3
                    WHEN all_energy_purchase_meters.days1 <= 360 THEN 4
                    WHEN all_energy_purchase_meters.days1 <= 720 THEN 5
                    WHEN all_energy_purchase_meters.days1 <= 1080 THEN 6
                    WHEN all_energy_purchase_meters.days1 <= 1440 THEN 7
                    WHEN all_energy_purchase_meters.days1 <= 1800 THEN 8
                    WHEN all_energy_purchase_meters.days1 <= 2160 THEN 9
                    WHEN all_energy_purchase_meters.days1 <= 2420 THEN 10
                    WHEN all_energy_purchase_meters.days1 <= 2780 THEN 11
                    ELSE 12 
                END as days1_range_order'),

                DB::raw('CASE 
                    WHEN all_energy_purchase_meters.days1 >360 THEN "Over 360"
                    ELSE "360 or Less" 
                END as days1_over_360_flag'),

                DB::raw('CASE 
                    WHEN all_energy_purchase_meters.days1 <= 180 THEN "Topped up in last 180 days"
                    ELSE "More than 180 days" 
                END as days1_category_flag'),

                'all_energy_purchase_meters.days2', 
                'all_energy_purchase_meters.days3',
                DB::raw('CASE 
                    WHEN all_energy_purchase_meters.days1 >= 0 AND all_energy_purchase_meters.days1 <= 89 THEN "Less than 3 months"
                    WHEN all_energy_purchase_meters.days1 >= 90 THEN "Over 3 months"
                    ELSE " " 
                END as days1_flag'),
                    DB::raw('CASE 
                    WHEN all_energy_purchase_meters.days2 > 0 AND all_energy_purchase_meters.days2 <= 179 THEN "Less than 6 months"
                    WHEN all_energy_purchase_meters.days2 >= 180 THEN "Over 6 months"
                    ELSE " " 
                END as days2_flag'),
                    DB::raw('CASE 
                    WHEN all_energy_purchase_meters.days3 > 0 AND all_energy_purchase_meters.days3 <= 269 THEN "Less than 9 months"
                    WHEN all_energy_purchase_meters.days3 >= 270 THEN "Over 9 months"
                    ELSE " " 
                END as days3_flag'),

                'meter_cases.meter_case_name_english',
                'all_energy_purchase_meters.payment1',
                'all_energy_purchase_meters.payment2',
                'all_energy_purchase_meters.payment3',


                DB::raw('
                    IF(all_energy_purchase_meters.purchase_date2 IS NOT NULL AND all_energy_purchase_meters.purchase_date3 IS NOT NULL, 
                        DATEDIFF(all_energy_purchase_meters.purchase_date2, all_energy_purchase_meters.purchase_date3), NULL) as diff1,
                    
                    IF(all_energy_purchase_meters.purchase_date1 IS NOT NULL AND all_energy_purchase_meters.purchase_date2 IS NOT NULL, 
                        DATEDIFF(all_energy_purchase_meters.purchase_date1, all_energy_purchase_meters.purchase_date2), NULL) as diff2,
                
                    ROUND((
                        IF(all_energy_purchase_meters.purchase_date2 IS NOT NULL AND all_energy_purchase_meters.purchase_date3 IS NOT NULL, 
                            DATEDIFF(all_energy_purchase_meters.purchase_date2, all_energy_purchase_meters.purchase_date3), 0) +
                        IF(all_energy_purchase_meters.purchase_date1 IS NOT NULL AND all_energy_purchase_meters.purchase_date2 IS NOT NULL, 
                            DATEDIFF(all_energy_purchase_meters.purchase_date1, all_energy_purchase_meters.purchase_date2), 0)
                    ) / 
                    (
                        IF(all_energy_purchase_meters.purchase_date2 IS NOT NULL AND all_energy_purchase_meters.purchase_date3 IS NOT NULL, 
                            1, 0) +
                        IF(all_energy_purchase_meters.purchase_date1 IS NOT NULL AND all_energy_purchase_meters.purchase_date2 IS NOT NULL, 1, 0)
                    ), 0) as avg_topup_days,

                CASE 
                    WHEN (
                        (
                            IF(all_energy_purchase_meters.purchase_date2 IS NOT NULL AND all_energy_purchase_meters.purchase_date3 IS NOT NULL, DATEDIFF(all_energy_purchase_meters.purchase_date2, all_energy_purchase_meters.purchase_date3), 0) +
                            IF(all_energy_purchase_meters.purchase_date1 IS NOT NULL AND all_energy_purchase_meters.purchase_date2 IS NOT NULL, DATEDIFF(all_energy_purchase_meters.purchase_date1, all_energy_purchase_meters.purchase_date2), 0)
                        ) / 
                        NULLIF(
                            (
                                IF(all_energy_purchase_meters.purchase_date2 IS NOT NULL AND all_energy_purchase_meters.purchase_date3 IS NOT NULL, 1, 0) +
                                IF(all_energy_purchase_meters.purchase_date1 IS NOT NULL AND all_energy_purchase_meters.purchase_date2 IS NOT NULL, 1, 0)
                            ), 0
                        )
                    ) < 90 THEN "<90 days"
                    WHEN (
                        (
                            IF(all_energy_purchase_meters.purchase_date2 IS NOT NULL AND all_energy_purchase_meters.purchase_date3 IS NOT NULL, DATEDIFF(all_energy_purchase_meters.purchase_date2, all_energy_purchase_meters.purchase_date3), 0) +
                            IF(all_energy_purchase_meters.purchase_date1 IS NOT NULL AND all_energy_purchase_meters.purchase_date2 IS NOT NULL, DATEDIFF(all_energy_purchase_meters.purchase_date1, all_energy_purchase_meters.purchase_date2), 0)
                        ) / 
                        NULLIF(
                            (
                                IF(all_energy_purchase_meters.purchase_date2 IS NOT NULL AND all_energy_purchase_meters.purchase_date3 IS NOT NULL, 1, 0) +
                                IF(all_energy_purchase_meters.purchase_date1 IS NOT NULL AND all_energy_purchase_meters.purchase_date2 IS NOT NULL, 1, 0)
                            ), 0
                        )
                    ) BETWEEN 90 AND 180 THEN "91-180 days"
                    WHEN (
                        (
                            IF(all_energy_purchase_meters.purchase_date2 IS NOT NULL AND all_energy_purchase_meters.purchase_date3 IS NOT NULL, DATEDIFF(all_energy_purchase_meters.purchase_date2, all_energy_purchase_meters.purchase_date3), 0) +
                            IF(all_energy_purchase_meters.purchase_date1 IS NOT NULL AND all_energy_purchase_meters.purchase_date2 IS NOT NULL, DATEDIFF(all_energy_purchase_meters.purchase_date1, all_energy_purchase_meters.purchase_date2), 0)
                        ) / 
                        NULLIF(
                            (
                                IF(all_energy_purchase_meters.purchase_date2 IS NOT NULL AND all_energy_purchase_meters.purchase_date3 IS NOT NULL, 1, 0) +
                                IF(all_energy_purchase_meters.purchase_date1 IS NOT NULL AND all_energy_purchase_meters.purchase_date2 IS NOT NULL, 1, 0)
                            ), 0
                        )
                    ) BETWEEN 181 AND 270 THEN "181-270 days"
                    WHEN (
                        (
                            IF(all_energy_purchase_meters.purchase_date2 IS NOT NULL AND all_energy_purchase_meters.purchase_date3 IS NOT NULL, DATEDIFF(all_energy_purchase_meters.purchase_date2, all_energy_purchase_meters.purchase_date3), 0) +
                            IF(all_energy_purchase_meters.purchase_date1 IS NOT NULL AND all_energy_purchase_meters.purchase_date2 IS NOT NULL, DATEDIFF(all_energy_purchase_meters.purchase_date1, all_energy_purchase_meters.purchase_date2), 0)
                        ) / 
                        NULLIF(
                            (
                                IF(all_energy_purchase_meters.purchase_date2 IS NOT NULL AND all_energy_purchase_meters.purchase_date3 IS NOT NULL, 1, 0) +
                                IF(all_energy_purchase_meters.purchase_date1 IS NOT NULL AND all_energy_purchase_meters.purchase_date2 IS NOT NULL, 1, 0)
                            ), 0
                        )
                    ) BETWEEN 271 AND 360 THEN "271-360 days"
                    ELSE "More than 360 days"
                END as avg_topup_frequency_category
            ')



            )->groupBy("all_energy_meters.id");


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
            "Meter Type", "Energy Holder (User/Public)", "Meter Number", "Community", "Compound",  
            "Region", "Energy System Type", "Energy System", "Daily Limit", "Installation Date",  
            "Last Purchase Date 1", "Last Purchase Date 2", "Last Purchase Date 3", 
            "Days Purchase 1", "Days 1 Range", "Days 1 Range Order",
            "Over 360 Days 1 Flag", "Purchase 1 Age Category",
            "Days Purchase 2", "Days Purchase 3", "Days Purchase Flag 1", 
            "Days Purchase Flag 2", "Days Purchase Flag 3", "Meter Case", 
            "Payment Amount 1", "Payment Amount 2", "Payment Amount 3",
            "Avg Topup Frequency Category",
            "Days Between Purchase 2 and 3",  // <-- diff1
            "Days Between Purchase 1 and 2",  // <-- diff2
            "Average Topup Days"    ];
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
        $sheet->setAutoFilter('A1:AE1');

        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}