<?php

namespace App\Exports\DataCollection\Displacement;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use DB;
 
class Choices implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, 
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
        $regions = DB::table('regions')
            ->where('regions.is_archived', 0)
            ->select(
                DB::raw('"region" as list_name'), 
                'regions.english_name as name',
                'regions.arabic_name as label',
                'regions.english_name as label_en',
                'regions.arabic_name as label_ar',
                DB::raw('false as region'),
                DB::raw('false as sub_region'),
                DB::raw('false as community')
            )
            ->get();

        $sub_regions = DB::table('sub_regions')
            ->join('regions', 'sub_regions.region_id', 'regions.id')
            ->where('sub_regions.is_archived', 0)
            ->select(
                DB::raw('"sub_region" as list_name'), 
                'sub_regions.english_name as name',
                'sub_regions.arabic_name as label',
                'sub_regions.english_name as label_en',
                'sub_regions.arabic_name as label_ar',
                'regions.english_name as region',
                DB::raw('false as sub_region'),
                DB::raw('false as community')
            )
            ->get();

        $communities = DB::table('communities')
            ->join('regions', 'communities.region_id', 'regions.id')
            ->join('sub_regions', 'communities.sub_region_id', 'sub_regions.id')
            ->where('communities.is_archived', 0)
            ->select(
                DB::raw('"community" as list_name'), 
                'communities.english_name as name',
                'communities.arabic_name as label',
                'communities.english_name as label_en',
                'communities.arabic_name as label_ar',
                'regions.english_name as region',
                'sub_regions.english_name as sub_region',
                DB::raw('false as community')
            )
            ->get();

        $households = DB::table('households')
            ->join('communities', 'communities.id', 'households.community_id')
            ->where('households.is_archived', 0)
            ->select(
                DB::raw('"household" as list_name'), 
                'households.comet_id as name',
                'households.arabic_name as label',
                'households.english_name as label_en',
                'households.arabic_name as label_ar',
                DB::raw('false as region'),
                DB::raw('false as sub_region'),
                'communities.english_name as community'
            )
            ->get(); 
        
        $new_regions = DB::table('sub_regions')
            ->where('sub_regions.is_archived', 0)
            ->select(
                DB::raw('"new_region" as list_name'), 
                'sub_regions.english_name as name',
                'sub_regions.arabic_name as label',
                'sub_regions.english_name as label_en',
                'sub_regions.arabic_name as label_ar',
                DB::raw('false as region'),
                DB::raw('false as sub_region'),
                DB::raw('false as community')
            )
            ->get();

        $household_status = DB::table('displaced_household_statuses')
            ->select(
                DB::raw('"household_status" as list_name'), 
                'displaced_household_statuses.name as name',
                'displaced_household_statuses.arabic_name as label',
                'displaced_household_statuses.name as label_en',
                'displaced_household_statuses.arabic_name as label_ar',
                DB::raw('false as region'),
                DB::raw('false as sub_region'),
                DB::raw('false as community')
            )
            ->get();

        $fixedList = [
            [
                'list_name' => 'area', 
                'name' => 'Area A',
                'label' => 'منطقة A',
                'label_en' => 'Area A',
                'community' => false,
            ],
            [
                'list_name' => 'area', 
                'name' => 'Area B',
                'label' => 'منطقة B',
                'label_en' => 'Area B',
                'community' => false,
            ],
            [
                'list_name' => 'area', 
                'name' => 'Area C',
                'label' => 'منطقة C',
                'label_en' => 'Area C',
                'community' => false,
            ],
            [
                'list_name' => 'system_retrieved', 
                'name' => 'System Retrieved',
                'label' => 'تم ارجاع النظام',
                'label_en' => 'System Retrieved',
                'community' => false,
            ],
            [
                'list_name' => 'system_retrieved', 
                'name' => 'System Not Retrieved',
                'label' => 'لم يتم ارجاع النظام',
                'label_en' => 'System Not Retrieved',
                'community' => false,
            ],
            [
                'list_name' => 'system_retrieved', 
                'name' => 'System Destroyed',
                'label' => 'تم تدمير النظام',
                'label_en' => 'System Destroyed',
                'community' => false,
            ],
        ];

        $query = collect($regions)
            ->merge($sub_regions)
            ->merge($communities)
            ->merge($households)
            ->merge($new_regions)
            ->merge($household_status)
            ->merge($fixedList); 
        
        return $query;
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function headings(): array
    {
        return ['list_name', 'name', 'label', 'label_en', 'label_ar', 'region', 'sub_region', 'community'];
    }


    public function title(): string
    {
        return 'choices';
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
        $sheet->setAutoFilter('A1:H1');

        return [
            // Style the first row as bold text.
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}