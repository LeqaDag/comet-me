<?php

namespace App\Exports\DataCollection\UserSupport;

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
        $services = DB::table('service_types')
            ->where('service_types.is_archived', 0)
            ->where('service_types.id', '!=', 4)
            ->select(
                DB::raw('"services" as list_name'), 
                'service_types.service_name as name',
                'service_types.name_arabic as label:Arabic (ar)',
                DB::raw('false as users'),
            )
            ->get();

        $users = DB::table('users')
            ->where('users.is_archived', 0)
            ->select(
                DB::raw('"users" as list_name'), 
                'users.email as name',
                'users.name_arabic as label:Arabic (ar)',
                DB::raw('false as services'),
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