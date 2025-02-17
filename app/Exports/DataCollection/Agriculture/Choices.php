<?php

namespace App\Exports\DataCollection\Agriculture;

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

        $productTypes = DB::table('product_types')
            ->where('product_types.is_archived', 0)
            ->select(
                DB::raw('"dairy_products" as list_name'), 
                'product_types.name as name',
                'product_types.name as label:Arabic (ar)',
                'product_types.name as label:English (en)',
                DB::raw('false as region'),
                DB::raw('false as sub_region'),
                DB::raw('false as community')
            )
            ->get(); 

        $incomeSources = DB::table('income_sources')
            ->where('income_sources.is_archived', 0)
            ->select(
                DB::raw('"source_income" as list_name'), 
                'income_sources.unique_name as name',
                'income_sources.name as label:Arabic (ar)',
                'income_sources.name as label:English (en)',
                DB::raw('false as region'),
                DB::raw('false as sub_region'),
                DB::raw('false as community')
            )
            ->get();

        $feedTypes = DB::table('herd_feed_types')
            ->where('herd_feed_types.is_archived', 0)
            ->select(
                DB::raw('"feed_types" as list_name'), 
                'herd_feed_types.unique_name as name',
                'herd_feed_types.name as label:Arabic (ar)',
                'herd_feed_types.name as label:English (en)',
                DB::raw('false as region'),
                DB::raw('false as sub_region'),
                DB::raw('false as community')
            )
            ->get(); 

        $herdChallenges = DB::table('herd_challenges')
            ->where('herd_challenges.is_archived', 0)
            ->select(
                DB::raw('"herd_challenge" as list_name'), 
                'herd_challenges.unique_name as name',
                'herd_challenges.name as label:Arabic (ar)',
                'herd_challenges.name as label:English (en)',
                DB::raw('false as region'),
                DB::raw('false as sub_region'),
                DB::raw('false as community')
            )
            ->get(); 

        $herdDiseases = DB::table('herd_diseases')
            ->where('herd_diseases.is_archived', 0)
            ->select(
                DB::raw('"herd_diseases" as list_name'), 
                'herd_diseases.unique_name as name',
                'herd_diseases.name as label:Arabic (ar)',
                'herd_diseases.name as label:English (en)',
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
                'water_sources.name as label:Arabic (ar)',
                'water_sources.name as label:English (en)',
                DB::raw('false as region'),
                DB::raw('false as sub_region'),
                DB::raw('false as community')
            )
            ->get(); 

        $salePointLivestocks = DB::table('sale_points')
            ->where('sale_points.is_archived', 0)
            ->select(
                DB::raw('"sell_livestock" as list_name'), 
                'sale_points.unique_name as name',
                'sale_points.name as label:Arabic (ar)',
                'sale_points.name as label:English (en)',
                DB::raw('false as region'),
                DB::raw('false as sub_region'),
                DB::raw('false as community')
            )
            ->get(); 

        $salePointDairyProducts = DB::table('sale_points')
            ->where('sale_points.is_archived', 0)
            ->select(
                DB::raw('"sell_dairy_products" as list_name'), 
                'sale_points.unique_name as name',
                'sale_points.name as label:Arabic (ar)',
                'sale_points.name as label:English (en)',
                DB::raw('false as region'),
                DB::raw('false as sub_region'),
                DB::raw('false as community')
            )
            ->get(); 

        $marketChallenges = DB::table('market_challenges')
            ->where('market_challenges.is_archived', 0)
            ->select(
                DB::raw('"market_challenges" as list_name'), 
                'market_challenges.unique_name as name',
                'market_challenges.name as label:Arabic (ar)',
                'market_challenges.name as label:English (en)',
                DB::raw('false as region'),
                DB::raw('false as sub_region'),
                DB::raw('false as community')
            )
            ->get();

        $herdLimitations = DB::table('herd_limitations')
            ->where('herd_limitations.is_archived', 0)
            ->select(
                DB::raw('"herd_limitations" as list_name'), 
                'herd_limitations.unique_name as name',
                'herd_limitations.name as label:Arabic (ar)',
                'herd_limitations.name as label:English (en)',
                DB::raw('false as region'),
                DB::raw('false as sub_region'),
                DB::raw('false as community')
            )
            ->get();

        $fixedList = [
            [
                'list_name' => 'herd_reduced', 
                'name' => 'Yes',
                'label:Arabic (ar)' => 'نعم',
                'label:English (en)' => 'Yes',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'herd_reduced', 
                'name' => 'No',
                'label:Arabic (ar)' => 'لا',
                'label:English (en)' => 'No',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'veterinary_services', 
                'name' => 'Yes',
                'label:Arabic (ar)' => 'نعم',
                'label:English (en)' => 'Yes',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'veterinary_services', 
                'name' => 'No',
                'label:Arabic (ar)' => 'لا',
                'label:English (en)' => 'No',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'veterinary_services', 
                'name' => 'Only_occasionally',
                'label:Arabic (ar)' => 'بعض الأحيان',
                'label:English (en)' => 'Only occasionally',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'soil_fertility', 
                'name' => 'Very_good',
                'label:Arabic (ar)' => 'جيدة جدا',
                'label:English (en)' => 'Very good',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'soil_fertility', 
                'name' => 'Good',
                'label:Arabic (ar)' => 'جيدة',
                'label:English (en)' => 'Good',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'soil_fertility', 
                'name' => 'Poor',
                'label:Arabic (ar)' => 'ضعيف',
                'label:English (en)' => 'Poor',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'grow', 
                'name' => 'Yes',
                'label:Arabic (ar)' => 'نعم',
                'label:English (en)' => 'Yes',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'grow', 
                'name' => 'No',
                'label:Arabic (ar)' => 'لا',
                'label:English (en)' => 'No',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'growing_products', 
                'name' => 'family_consumption',
                'label:Arabic (ar)' => 'الاستهلاك العائلي',
                'label:English (en)' => 'For your family consumption',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
            [
                'list_name' => 'growing_products', 
                'name' => 'animal_feed',
                'label:Arabic (ar)' => 'غذاء للحيوانات',
                'label:English (en)' => 'For animal feed',
                'region' => false,
                'sub_region' => false,
                'community' => false,
            ],
        ];

        $query = collect($regions)
            ->merge($sub_regions)
            ->merge($communities)
            ->merge($households)
            ->merge($productTypes)
            ->merge($incomeSources)
            ->merge($feedTypes)
            ->merge($herdChallenges)
            ->merge($herdDiseases)
            ->merge($waterSources)
            ->merge($salePointLivestocks)
            ->merge($salePointDairyProducts)
            ->merge($marketChallenges)
            ->merge($herdLimitations)
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