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
                'regions.english_name as label',
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
                'sub_regions.english_name as label',
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
                'communities.english_name as label',
                'communities.english_name as label_en',
                'communities.arabic_name as label_ar',
                'regions.english_name as region',
                'sub_regions.english_name as sub_region',
                DB::raw('false as community')
            )
            ->get();

        $compounds = DB::table('compounds')
            ->join('communities', 'compounds.community_id', 'communities.id')
            ->where('compounds.is_archived', 0)
            ->select(
                DB::raw('"compound" as list_name'), 
                'compounds.english_name as name',
                'compounds.english_name as label',
                'compounds.english_name as label_en',
                'compounds.arabic_name as label_ar',
                DB::raw('false as region'),
                DB::raw('false as sub_region'),
                'communities.english_name as community'
            )
            ->get();

        $households = DB::table('households')
            ->join('communities', 'households.community_id', 'communities.id')
            ->join('regions', 'communities.region_id', 'regions.id')
            ->join('sub_regions', 'communities.sub_region_id', 'sub_regions.id')
            ->where('households.is_archived', 0)
            ->select(
                DB::raw('"household" as list_name'), 
                'households.comet_id as name',
                'households.english_name as label',
                'households.english_name as label_en',
                'households.arabic_name as label_ar',
                'regions.english_name as region',
                'sub_regions.english_name as sub_region',
                'communities.english_name as community'
            )
            ->get();

        $mainUsers =  DB::table('all_energy_meters')
            ->join('households', 'all_energy_meters.household_id', 'households.id')
            ->join('communities', 'households.community_id', 'communities.id')
            ->join('regions', 'communities.region_id', 'regions.id')
            ->join('sub_regions', 'communities.sub_region_id', 'sub_regions.id')
            ->where('all_energy_meters.is_archived', 0)
            ->select(
                DB::raw('"main_users" as list_name'), 
                'all_energy_meters.id as name',
                'households.english_name as label',
                'households.english_name as label_en',
                'households.arabic_name as label_ar',
                'regions.english_name as region',
                'sub_regions.english_name as sub_region',
                'communities.english_name as community'
            )
            ->get();

        $professions = DB::table('professions')
            ->where('professions.is_archived', 0)
            ->select(
                DB::raw('"profession" as list_name'), 
                'professions.profession_name as name',
                'professions.profession_name as label',
                'professions.profession_name as label_en',
                'professions.profession_name as label_ar',
                DB::raw('false as region'),
                DB::raw('false as sub_region'),
                DB::raw('false as community')
            )
            ->get();

        $meterCaseDescriptions = DB::table('meter_case_descriptions')
            ->select(
                DB::raw('"meter_case_description" as list_name'), 
                'meter_case_descriptions.english_name as name',
                'meter_case_descriptions.english_name as label',
                'meter_case_descriptions.english_name as label_en',
                'meter_case_descriptions.arabic_name as label_ar',
                DB::raw('false as region'),
                DB::raw('false as sub_region'),
                DB::raw('false as community')
            )
            ->get();

        $fixedList = [
            [
                'list_name' => 'is_live', 
                'name' => 'Live',
                'label' => 'Live',
                'label_en' => 'Live',
                'label_ar' => 'ساكن',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'is_live', 
                'name' => 'Move',
                'label' => 'Move',
                'label_en' => 'Move',
                'label_ar' => 'انتقل',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'herds', 
                'name' => 'Yes',
                'label' => 'Yes',
                'label_en' => 'Yes',
                'label_ar' => 'نعم',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'herds', 
                'name' => 'No',
                'label' => 'No',
                'label_en' => 'No',
                'label_ar' => 'لا',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'demolition', 
                'name' => 'Yes',
                'label' => 'Yes',
                'label_en' => 'Yes',
                'label_ar' => 'نعم',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'demolition', 
                'name' => 'No',
                'label' => 'No',
                'label_en' => 'No',
                'label_ar' => 'لا',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'cistern', 
                'name' => 'Yes',
                'label' => 'Yes',
                'label_en' => 'Yes',
                'label_ar' => 'نعم',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'cistern', 
                'name' => 'No',
                'label' => 'No',
                'label_en' => 'No',
                'label_ar' => 'لا',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'shared_cistern', 
                'name' => 'Yes',
                'label' => 'Yes',
                'label_en' => 'Yes',
                'label_ar' => 'نعم',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'shared_cistern', 
                'name' => 'No',
                'label' => 'No',
                'label_en' => 'No',
                'label_ar' => 'لا',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'house', 
                'name' => 'Yes',
                'label' => 'Yes',
                'label_en' => 'Yes',
                'label_ar' => 'نعم',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'house', 
                'name' => 'No',
                'label' => 'No',
                'label_en' => 'No',
                'label_ar' => 'لا',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'izbih', 
                'name' => 'Yes',
                'label' => 'Yes',
                'label_en' => 'Yes',
                'label_ar' => 'نعم',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'izbih', 
                'name' => 'No',
                'label' => 'No',
                'label_en' => 'No',
                'label_ar' => 'لا',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'refrigerator', 
                'name' => 'Yes',
                'label' => 'Yes',
                'label_en' => 'Yes',
                'label_ar' => 'نعم',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'refrigerator', 
                'name' => 'No',
                'label' => 'No',
                'label_en' => 'No',
                'label_ar' => 'لا',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'meter_case', 
                'name' => 'High usage',
                'label' => 'High usage',
                'label_en' => 'High usage',
                'label_ar' => 'استخدام عال',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'meter_case', 
                'name' => 'Regular usage',
                'label' => 'Regular usage',
                'label_en' => 'Regular usage',
                'label_ar' => 'استخدام عادي',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'meter_case', 
                'name' => 'Low usage',
                'label' => 'Low usage',
                'label_en' => 'Low usage',
                'label_ar' => 'استخدام منخفض',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'meter_case', 
                'name' => 'Bypass meter',
                'label' => 'Bypass meter',
                'label_en' => 'Bypass meter',
                'label_ar' => 'لاغي الساعة',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'meter_case', 
                'name' => 'Left Comet',
                'label' => 'Left Comet',
                'label_en' => 'Left Comet',
                'label_ar' => 'ترك كوميت',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'meter_case', 
                'name' => 'Not activated',
                'label' => 'Not activated',
                'label_en' => 'Not activated',
                'label_ar' => 'غير مفعلة',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'household_status', 
                'name' => 'Served',
                'label' => 'Served',
                'label_en' => 'Served',
                'label_ar' => 'خُدم',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'household_status', 
                'name' => 'Shared',
                'label' => 'Shared',
                'label_en' => 'Shared',
                'label_ar' => 'مشترك',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'household_status', 
                'name' => 'Requested',
                'label' => 'Requested',
                'label_en' => 'Requested',
                'label_ar' => 'يطلب نظام',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'household_status', 
                'name' => 'Shared & Requested',
                'label' => 'Shared & Requested',
                'label_en' => 'Shared & Requested',
                'label_ar' => 'مشترك ويريد نظام',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'water', 
                'name' => 'Served',
                'label' => 'Served',
                'label_en' => 'Served',
                'label_ar' => 'خُدم',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'water', 
                'name' => 'Not Served',
                'label' => 'Not Served',
                'label_en' => 'Not Served',
                'label_ar' => 'لم يخدم',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'internet', 
                'name' => 'Served',
                'label' => 'Served',
                'label_en' => 'Served',
                'label_ar' => 'خُدم',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'internet', 
                'name' => 'Not Served',
                'label' => 'Not Served',
                'label_en' => 'Not Served',
                'label_ar' => 'لم يخدم',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
        ];



        if($this->request->region) {

            $regions->where("regions.id", $this->request->region);
            $sub_regions->where("regions.id", $this->request->region);
            $communities->where("communities.region_id", $this->request->region);
            $households->where("regions.id", $this->request->region);
        }

        if($this->request->sub_region) {

            $sub_regions->where("sub_regions.id", $this->request->region);
            $communities->where("communities.sub_region_id", $this->request->sub_region);
            $households->where("sub_regions.id", $this->request->sub_region);
        }

        $query = collect($regions)
            ->merge($sub_regions)
            ->merge($communities)
            ->merge($compounds)
            ->merge($households)
            ->merge($mainUsers)
            ->merge($professions)
            ->merge($meterCaseDescriptions)
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