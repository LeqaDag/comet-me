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
 
class Households implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, 
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
        $households = DB::table('households') 
            ->join('communities', 'households.community_id', 'communities.id')
            ->join('regions', 'communities.region_id', 'regions.id')
            ->join('sub_regions', 'communities.sub_region_id', 'sub_regions.id')
            ->leftJoin('professions', 'households.profession_id', 'professions.id')
            ->leftJoin('all_energy_meters', 'households.id', 'all_energy_meters.household_id')
            ->leftJoin('structures', 'households.id', 'structures.household_id')
            ->leftJoin('cisterns', 'households.id', 'cisterns.household_id')
            ->leftJoin('community_households', 'community_households.household_id', 'households.id')
            ->where('households.is_archived', 0)  
            ->where('communities.is_archived', 0)
            ->where('internet_holder_young', 0) 
            ->select(
                DB::raw('"household" as list_name'), 
                'households.comet_id as name',
                'households.english_name as label',
                'households.english_name as label_en',
                'households.arabic_name as label_ar',
                'regions.english_name as region',
                'sub_regions.english_name as sub_region',
                'communities.english_name as community',
                'all_energy_meters.meter_number as meter_number', 
                'households.phone_number', 'professions.profession_name', 
                'households.number_of_male', 'households.number_of_female', 
                'households.number_of_children', 'households.number_of_adults', 
                'households.school_students', 'households.university_students', 
                'households.demolition_order', 'cisterns.shared_cisterns', 
                'structures.number_of_structures', 'structures.number_of_kitchens', 
                'households.size_of_herd', 'community_households.is_there_house_in_town',
                'structures.number_of_animal_shelters', 'cisterns.number_of_cisterns',
                'cisterns.distance_from_house', 'cisterns.volume_of_cisterns'
            )
            ->get();
        
        return $households;
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function headings(): array
    {
        return ['list_name', 'name', 'label', 'label_en', 'label_ar', 'region', 'sub_region', 'community', 'meter_number',
            'phone_number', 'profession_name', 'number_of_male', 'number_of_female', 'number_of_children', 'number_of_adults', 
            'school_students', 'university_students', 'demolition_order', 'shared_cisterns', 'number_of_structures', 
            'number_of_kitchens', 'size_of_herd', 'is_there_house_in_town', 'number_of_animal_shelters', 'number_of_cisterns', 
            'distance_from_house', 'volume_of_cisterns'];
    }


    public function title(): string
    {
        return 'households';
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
        $sheet->setAutoFilter('A1:I1');

        return [
            // Style the first row as bold text.
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}