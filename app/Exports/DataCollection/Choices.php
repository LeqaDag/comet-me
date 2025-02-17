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
                'regions.english_name as label:English (en)',
                'regions.arabic_name as label:Arabic (ar)',
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
                'sub_regions.english_name as label:English (en)',
                'sub_regions.arabic_name as label:Arabic (ar)',
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
                'communities.english_name as label:English (en)',
                'communities.arabic_name as label:Arabic (ar)',
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
                'compounds.english_name as label:English (en)',
                'compounds.arabic_name as label:Arabic (ar)',
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
                'households.english_name as label:English (en)',
                'households.arabic_name as label:Arabic (ar)',
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
                DB::raw('"main_user" as list_name'), 
                'all_energy_meters.id as name',
                'households.english_name as label:English (en)',
                'households.arabic_name as label:Arabic (ar)',
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
                'professions.profession_name as label:English (en)',
                'professions.arabic_name as label:Arabic (ar)',
                DB::raw('false as region'),
                DB::raw('false as sub_region'),
                DB::raw('false as community')
            )
            ->get();

        $meterCaseDescriptions = DB::table('meter_case_descriptions')
            ->select(
                DB::raw('"meter_case_description" as list_name'), 
                'meter_case_descriptions.english_name as name',
                'meter_case_descriptions.english_name as label:English (en)',
                'meter_case_descriptions.arabic_name as label:Arabic (ar)',
                DB::raw('false as region'),
                DB::raw('false as sub_region'),
                DB::raw('false as community')
            )
            ->get();

        $systemTypes = DB::table('energy_system_types')
            ->where('energy_system_types.is_archived', 0)
            ->select(
                DB::raw('"system_type" as list_name'), 
                'energy_system_types.name as name',
                'energy_system_types.name as label:English (en)',
                'energy_system_types.name as label:Arabic (ar)',
                DB::raw('false as region'),
                DB::raw('false as sub_region'),
                DB::raw('false as community')
            )
            ->get();

        $fixedList = [
            [
                'list_name' => 'is_live', 
                'name' => 'Live',
                'label:English (en)' => 'Live',
                'label:Arabic (ar)' => 'ساكن',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'is_live', 
                'name' => 'Move',
                'label:English (en)' => 'Move',
                'label:Arabic (ar)' => 'انتقل',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'herds', 
                'name' => 'Yes',
                'label:English (en)' => 'Yes',
                'label:Arabic (ar)' => 'نعم',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'herds', 
                'name' => 'No',
                'label:English (en)' => 'No',
                'label:Arabic (ar)' => 'لا',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'demolition', 
                'name' => 'Yes',
                'label:English (en)' => 'Yes',
                'label:Arabic (ar)' => 'نعم',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'demolition', 
                'name' => 'No',
                'label:English (en)' => 'No',
                'label:Arabic (ar)' => 'لا',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'cistern', 
                'name' => 'Yes',
                'label:English (en)' => 'Yes',
                'label:Arabic (ar)' => 'نعم',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'cistern', 
                'name' => 'No',
                'label:English (en)' => 'No',
                'label:Arabic (ar)' => 'لا',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'shared_cistern', 
                'name' => 'Yes',
                'label:English (en)' => 'Yes',
                'label:Arabic (ar)' => 'نعم',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'shared_cistern', 
                'name' => 'No',
                'label:English (en)' => 'No',
                'label:Arabic (ar)' => 'لا',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'house', 
                'name' => 'Yes',
                'label:English (en)' => 'Yes',
                'label:Arabic (ar)' => 'نعم',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'house', 
                'name' => 'No',
                'label:English (en)' => 'No',
                'label:Arabic (ar)' => 'لا',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'izbih', 
                'name' => 'Yes',
                'label:English (en)' => 'Yes',
                'label:Arabic (ar)' => 'نعم',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'izbih', 
                'name' => 'No',
                'label:English (en)' => 'No',
                'label:Arabic (ar)' => 'لا',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'refrigerator', 
                'name' => 'Yes',
                'label:English (en)' => 'Yes',
                'label:Arabic (ar)' => 'نعم',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'refrigerator', 
                'name' => 'No',
                'label:English (en)' => 'No',
                'label:Arabic (ar)' => 'لا',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'meter_case', 
                'name' => 'High usage',
                'label:English (en)' => 'High usage',
                'label:Arabic (ar)' => 'استخدام عال',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'meter_case', 
                'name' => 'Regular usage',
                'label:English (en)' => 'Regular usage',
                'label:Arabic (ar)' => 'استخدام عادي',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'meter_case', 
                'name' => 'Low usage',
                'label:English (en)' => 'Low usage',
                'label:Arabic (ar)' => 'استخدام منخفض',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'meter_case', 
                'name' => 'Bypass meter',
                'label:English (en)' => 'Bypass meter',
                'label:Arabic (ar)' => 'لاغي الساعة',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'meter_case', 
                'name' => 'Left Comet',
                'label:English (en)' => 'Left Comet',
                'label:Arabic (ar)' => 'ترك كوميت',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'meter_case', 
                'name' => 'Not activated',
                'label:English (en)' => 'Not activated',
                'label:Arabic (ar)' => 'غير مفعلة',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'household_status', 
                'name' => 'Served',
                'label:English (en)' => 'Served',
                'label:Arabic (ar)' => 'ساعة باسمه',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'household_status', 
                'name' => 'Shared',
                'label:English (en)' => 'Shared',
                'label:Arabic (ar)' => 'مشترك',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'household_status', 
                'name' => 'Requested',
                'label:English (en)' => 'Requested',
                'label:Arabic (ar)' => 'يطلب نظام',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'household_status', 
                'name' => 'Shared & Requested',
                'label:English (en)' => 'Shared & Requested',
                'label:Arabic (ar)' => 'مشترك ويريد نظام',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'water', 
                'name' => 'Served',
                'label:English (en)' => 'Served',
                'label:Arabic (ar)' => 'نعم',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'water', 
                'name' => 'Not Served',
                'label:English (en)' => 'Not Served',
                'label:Arabic (ar)' => 'لا',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'internet', 
                'name' => 'Served',
                'label:English (en)' => 'Served',
                'label:Arabic (ar)' => 'نعم',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'internet', 
                'name' => 'Not Served',
                'label:English (en)' => 'Not Served',
                'label:Arabic (ar)' => 'لا',
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
            ->merge($systemTypes)
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
        return ['list_name', 'name', 'label:English (en)', 'label:Arabic (ar)', 'region', 'sub_region', 'community'];
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