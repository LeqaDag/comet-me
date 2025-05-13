<?php

namespace App\Exports\DataCollection\CommunityCompound;

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
                'communities.comet_id as name',
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

        $productTypes = DB::table('product_types')
            ->where('product_types.is_archived', 0)
            ->select(
                DB::raw('"products" as list_name'), 
                'product_types.name as name',
                'product_types.name as label:English (en)',
                'product_types.name as label:Arabic (ar)',
                DB::raw('false as region'),
                DB::raw('false as sub_region'),
                DB::raw('false as community')
            )
            ->get(); 

        $waterSources = DB::table('water_sources')
            ->where('water_sources.is_archived', 0)
            ->select(
                DB::raw('"water_sources" as list_name'), 
                'water_sources.unique_name as name',
                'water_sources.name as label:English (en)',
                'water_sources.name as label:Arabic (ar)',
                DB::raw('false as region'),
                DB::raw('false as sub_region'),
                DB::raw('false as community')
            )
            ->get();

        $fixedList = [
            [
                'list_name' => 'fallah', 
                'name' => 'Yes',
                'label:English (en)' => 'Yes',
                'label:Arabic (ar)' => 'نعم',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'fallah', 
                'name' => 'No',
                'label:English (en)' => 'No',
                'label:Arabic (ar)' => 'لا',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'bedouin', 
                'name' => 'Yes',
                'label:English (en)' => 'Yes',
                'label:Arabic (ar)' => 'نعم',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'bedouin', 
                'name' => 'No',
                'label:English (en)' => 'No',
                'label:Arabic (ar)' => 'لا',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'reception', 
                'name' => 'Yes',
                'label:English (en)' => 'Yes',
                'label:Arabic (ar)' => 'نعم',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'reception', 
                'name' => 'No',
                'label:English (en)' => 'No',
                'label:Arabic (ar)' => 'لا',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'demolition_executed', 
                'name' => 'Yes',
                'label:English (en)' => 'Yes',
                'label:Arabic (ar)' => 'نعم',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'demolition_executed', 
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
        ];


        $query = collect($regions)
            ->merge($sub_regions)
            ->merge($communities)
            ->merge($compounds)
            ->merge($productTypes)
            ->merge($waterSources)
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
        $sheet->setAutoFilter('A1:G1');

        return [
            // Style the first row as bold text.
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}