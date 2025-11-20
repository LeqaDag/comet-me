<?php

namespace App\Exports\DataCollection\Agriculture\Requested;

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
                'regions.arabic_name as label:Arabic (ar)',
                'regions.english_name as label:English (en)',
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
                'sub_regions.arabic_name as label:Arabic (ar)',
                'sub_regions.english_name as label:English (en)',
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
                'communities.arabic_name as label:Arabic (ar)',
                'communities.english_name as label:English (en)',
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
                'households.arabic_name as label:Arabic (ar)',
                'households.english_name as label:English (en)',
                DB::raw('false as region'),
                DB::raw('false as sub_region'),
                'communities.english_name as community'
            )
            ->get(); 

        $sharedHouseholds = DB::table('households')
            ->join('communities', 'communities.id', 'households.community_id')
            ->where('households.is_archived', 0)
            ->select(
                DB::raw('"household_list" as list_name'), 
                'households.comet_id as name',
                'households.arabic_name as label:Arabic (ar)',
                'households.english_name as label:English (en)',
                DB::raw('false as region'),
                DB::raw('false as sub_region'),
                'communities.english_name as community'
            )
            ->get(); 

        // $systemTypes = DB::table('agriculture_systems')
        //     ->where('agriculture_systems.is_archived', 0)
        //     ->select(
        //         DB::raw('"system_type" as list_name'), 
        //         'agriculture_systems.comet_id as name',
        //         'agriculture_systems.name as label:Arabic (ar)',
        //         'agriculture_systems.name as label:English (en)',
        //         DB::raw('false as region'),
        //         DB::raw('false as sub_region'),
        //         DB::raw('false as community')
        //     )
        //     ->get(); 

        $agricultureCycles = DB::table('agriculture_system_cycles')
            ->select(
                DB::raw('"cycle_year" as list_name'), 
                'agriculture_system_cycles.name as name',
                'agriculture_system_cycles.name as label:Arabic (ar)',
                'agriculture_system_cycles.name as label:English (en)',
                DB::raw('false as region'),
                DB::raw('false as sub_region'),
                DB::raw('false as community')
            )
            ->get();


        $agricultureInstalltionTypes = DB::table('agriculture_installation_types')
            ->select(
                DB::raw('"installtion_type" as list_name'), 
                'agriculture_installation_types.english_name as name',
                'agriculture_installation_types.arabic_name as label:Arabic (ar)',
                'agriculture_installation_types.english_name as label:English (en)',
                DB::raw('false as region'),
                DB::raw('false as sub_region'),
                DB::raw('false as community')
            )
            ->get();

        $fixedList = [
            [
                'list_name' => 'is_shared', 
                'name' => 'Yes',
                'label:Arabic (ar)' => 'نعم',
                'label:English (en)' => 'Yes',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'is_shared', 
                'name' => 'No',
                'label:Arabic (ar)' => 'لا',
                'label:English (en)' => 'No',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],

            [
                'list_name' => 'cow', 
                'name' => 'Yes',
                'label:Arabic (ar)' => 'نعم',
                'label:English (en)' => 'Yes',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'cow', 
                'name' => 'No',
                'label:Arabic (ar)' => 'لا',
                'label:English (en)' => 'No',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'animals', 
                'name' => 'Yes',
                'label:Arabic (ar)' => 'نعم',
                'label:English (en)' => 'Yes',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'animals', 
                'name' => 'No',
                'label:Arabic (ar)' => 'لا',
                'label:English (en)' => 'No',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'chicken', 
                'name' => 'Yes',
                'label:Arabic (ar)' => 'نعم',
                'label:English (en)' => 'Yes',
                'region' => false,
                'sub_region' => false, 
                'community' => false, 
            ],
            [
                'list_name' => 'chicken', 
                'name' => 'No',
                'label:Arabic (ar)' => 'لا',
                'label:English (en)' => 'No',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'goat', 
                'name' => 'Yes',
                'label:Arabic (ar)' => 'نعم',
                'label:English (en)' => 'Yes',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'goat', 
                'name' => 'No',
                'label:Arabic (ar)' => 'لا',
                'label:English (en)' => 'No',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'camel', 
                'name' => 'Yes',
                'label:Arabic (ar)' => 'نعم',
                'label:English (en)' => 'Yes',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'camel', 
                'name' => 'No',
                'label:Arabic (ar)' => 'لا',
                'label:English (en)' => 'No',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'area', 
                'name' => 'Yes',
                'label:Arabic (ar)' => 'نعم',
                'label:English (en)' => 'Yes',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'area', 
                'name' => 'No',
                'label:Arabic (ar)' => 'لا',
                'label:English (en)' => 'No',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'area_type', 
                'name' => 'A',
                'label:Arabic (ar)' => 'A',
                'label:English (en)' => 'A',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'area_type', 
                'name' => 'B',
                'label:Arabic (ar)' => 'B',
                'label:English (en)' => 'B',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'area_type', 
                'name' => 'C',
                'label:Arabic (ar)' => 'C',
                'label:English (en)' => 'C',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
        ];

        $query = collect($regions)
            ->merge($sub_regions)
            ->merge($communities)
            ->merge($households)
            ->merge($sharedHouseholds)
            ->merge($agricultureCycles)
            ->merge($agricultureInstalltionTypes)
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
        return ['list_name', 'name', 'label:Arabic (ar)', 'label:English (en)', 'region', 'sub_region', 'community'];
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