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
 
class Survey implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, 
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

        $fixedList = [
            [
                'type' => 'select_one region', 
                'name' => 'select_region',
                'label' => 'Select region',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => false,
            ],
            [
                'type' => 'select_one sub_region', 
                'name' => 'select_sub_region',
                'label' => 'Select sub region',
                'hint' => false,
                'choices' => 'sub_region',
                'choice_filter' => '${select_region} = region',
                'calculation' => false,
                'required' => 'yes',
                'relevant' => false,
            ],
            [
                'type' => 'select_one community', 
                'name' => 'select_community',
                'label' => 'Select community',
                'hint' => false,
                'choices' => 'community',
                'choice_filter' => '${select_sub_region} = sub_region',
                'calculation' => false,
                'required' => 'yes',
                'relevant' => false,
            ],
            [
                'type' => 'select_one compound', 
                'name' => 'select_compound',
                'label' => 'Select compound',
                'hint' => false,
                'choices' => 'compound',
                'choice_filter' => '${select_community} = community',
                'calculation' => false,
                'required' => 'no',
                'relevant' => false,
            ],
            [
                'type' => 'select_one household', 
                'name' => 'select_household_name',
                'label' => 'Select household',
                'hint' => false,
                'choices' => 'household', 
                'choice_filter' => '${select_community} = community',
                'calculation' => false,
                'required' => 'no',
                'relevant' => false,
            ],
            [
                'type' => 'select_one is_live', 
                'name' => 'select_is_live',
                'label' => 'Do the household still live in the community, or move?',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => false,
            ],

            // Main Group
            [
                'type' => 'begin group', 
                'name' => 'live_left',
                'label' => 'Household Details',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => false,
                'relevant' => '${select_is_live} = "Live"',
            ],

            // First Group
            [
                'type' => 'begin group', 
                'name' => 'personal_details',
                'label' => 'Personal Details',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => false,
                'relevant' => false,
            ],
            [
                'type' => 'text', 
                'name' => 'english_name',
                'label' => 'Enter the household English name',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => 'pulldata("households", "label_en", "name", ${select_household_name})',
                'required' => 'yes',
                'relevant' => false,
            ],
            [
                'type' => 'text', 
                'name' => 'arabic_name',
                'label' => 'Enter the household Arabic name',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => 'pulldata("households", "label_ar", "name", ${select_household_name})',
                'required' => 'yes',
                'relevant' => false,
            ],
            [
                'type' => 'select_one profession', 
                'name' => 'select_profession',
                'label' => 'Select profession',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => false,
            ],
            [
                'type' => 'integer', 
                'name' => 'phone_number',
                'label' => 'Enter phone number',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => false,
            ],
            [
                'type' => 'integer', 
                'name' => 'additional_phone_number',
                'label' => 'Enter additional phone number',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'no',
                'relevant' => false,
            ],
            [
                'type' => 'end group', 
                'name' => false,
                'label' => false,
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => false,
                'relevant' => false,
            ],
            

            // Second Group
            [
                'type' => 'begin group', 
                'name' => 'family_members',
                'label' => 'Family Members',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => false,
                'relevant' => false,
            ],
            [
                'type' => 'integer', 
                'name' => 'number_of_male',
                'label' => 'Enter number of male',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => false,
            ],
            [
                'type' => 'integer', 
                'name' => 'number_of_female',
                'label' => 'Enter number of female',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => false,
            ],
            [
                'type' => 'integer', 
                'name' => 'number_of_adults',
                'label' => 'Enter number of adults',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => false,
            ],
            [
                'type' => 'integer', 
                'name' => 'number_of_children',
                'label' => 'Enter number of children',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => false,
            ],
            [
                'type' => 'integer', 
                'name' => 'school_students',
                'label' => 'Enter number of school',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => false,
            ],
            [
                'type' => 'integer', 
                'name' => 'university_students',
                'label' => 'Enter number of university',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => false,
            ],
            [
                'type' => 'end group', 
                'name' => false,
                'label' => false,
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => false,
                'relevant' => false,
            ],		


            // Third Group
            [
                'type' => 'begin group', 
                'name' => 'services_status',
                'label' => 'Services Status',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => false,
                'relevant' => false,
            ],
            [
                'type' => 'select_one household_status', 
                'name' => 'select_household_status',
                'label' => 'Select energy status',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => false,
            ],
            [
                'type' => 'select_one meter_case', 
                'name' => 'select_meter_case',
                'label' => 'Select meter case',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'no',
                'relevant' => '${select_household_status} = "Served"',
            ],
            [
                'type' => 'text', 
                'name' => 'meter_number',
                'label' => 'Meter number',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => 'pulldata("households", "meter_number", "name", ${select_household_name})',
                'required' => 'no',
                'relevant' => false,
            ],
            [
                'type' => 'select_one meter_case_description', 
                'name' => 'select_meter_case_description',
                'label' => 'Select meter case description',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'no',
                'relevant' => '${select_meter_case} = "Low usage" or ${select_meter_case} = "Not used"  or ${select_meter_case} = "Bypass meter" or ${select_meter_case} = "Left Comet"',
            ],
            [
                'type' => 'select_one main_user', 
                'name' => 'select_main_user',
                'label' => 'Select main user',
                'hint' => false,
                'choices' => 'main_user',
                'choice_filter' => '${select_community} = community',
                'calculation' => false,
                'required' => 'no',
                'relevant' => '${select_household_status} = "Shared" or ${select_household_status} = "Shared & Requested"',
            ],
            [
                'type' => 'select_one water', 
                'name' => 'select_water_system_status',
                'label' => 'Select water status',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => false,
            ],
            [
                'type' => 'select_one internet', 
                'name' => 'select_internet_system_status',
                'label' => 'Select internet status',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => false,
            ],
            [
                'type' => 'select_one refrigerator', 
                'name' => 'select_refrigerator',
                'label' => 'Select refrigerator',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => false,
            ],
            [
                'type' => 'end group', 
                'name' => false,
                'label' => false,
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => false,
                'relevant' => false,
            ],
							

            // Fourth Group
            [
                'type' => 'begin group', 
                'name' => 'house_details',
                'label' => 'House Details',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => false,
                'relevant' => false,
            ],
            [
                'type' => 'select_one demolition', 
                'name' => 'demolition_order',
                'label' => 'Select demolition order',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => false,
            ],
            [
                'type' => 'select_one house', 
                'name' => 'select_is_there_house_in_town',
                'label' => 'Select house in the town',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => false,
            ],
            [
                'type' => 'end group', 
                'name' => false,
                'label' => false,
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => false,
                'relevant' => false,
            ],

            // Fifth Group
            [
                'type' => 'begin group', 
                'name' => 'herds',
                'label' => 'Herds',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => false,
                'relevant' => false,
            ],
            [
                'type' => 'select_one herds', 
                'name' => 'select_herd',
                'label' => 'Select herd',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => false,
            ],
            [
                'type' => 'integer', 
                'name' => 'size_of_herd',
                'label' => 'Enter size of herds',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'no',
                'relevant' => '${select_herd} = "Yes"',
            ],
            [
                'type' => 'integer', 
                'name' => 'number_of_animal_shelters',
                'label' => 'Enter number of animal shelter',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'no',
                'relevant' => '${select_herd} = "Yes"',
            ],
            [
                'type' => 'end group', 
                'name' => false,
                'label' => false,
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => false,
                'relevant' => false,
            ],

            // Sixth Group
            [
                'type' => 'begin group', 
                'name' => 'cistern',
                'label' => 'Cistern',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => false,
                'relevant' => false,
            ],
            [
                'type' => 'select_one cistern', 
                'name' => 'select_cistern',
                'label' => 'Select cistern',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => false,
            ],
            [
                'type' => 'integer', 
                'name' => 'number_of_cisterns',
                'label' => 'Enter how many cisterns',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'no',
                'relevant' => '${select_cistern} = "Yes"',
            ],
            [
                'type' => 'integer', 
                'name' => 'cistern_depth',
                'label' => 'Enter the depth in Liter',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'no',
                'relevant' => '${select_cistern} = "Yes"',
            ],
            [
                'type' => 'integer', 
                'name' => 'distance_from_house',
                'label' => 'Enter the distance in meter',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'no',
                'relevant' => '${select_cistern} = "Yes"',
            ],
            [
                'type' => 'select_one shared_cistern', 
                'name' => 'select_shared_cisterns',
                'label' => 'Select cistern shared',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'no',
                'relevant' => '${select_cistern} = "Yes"',
            ],
            [
                'type' => 'end group', 
                'name' => false,
                'label' => false,
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => false,
                'relevant' => false,
            ],

							

							

            // Seventh Group
            [
                'type' => 'begin group', 
                'name' => 'izbih',
                'label' => 'Izbih',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => false,
                'relevant' => false,
            ],
            [
                'type' => 'select_one izbih', 
                'name' => 'select_is_there_izbih',
                'label' => 'Select Izbih',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => false,
            ],
            [
                'type' => 'integer', 
                'name' => 'how_long',
                'label' => 'Enter how long',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'no',
                'relevant' => '${select_is_there_izbih} = "Yes"',
            ],
            [
                'type' => 'end group', 
                'name' => false,
                'label' => false,
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => false,
                'relevant' => false,
            ],

            
            [
                'type' => 'end group', 
                'name' => false,
                'label' => false,
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => false,
                'relevant' => false,
            ],

						
            [
                'type' => 'text', 
                'name' => 'notes',
                'label' => 'Enter notes',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'no',
                'relevant' => false
            ],		

        ];
        
        $fixedListCollection = collect($fixedList);

        return $fixedListCollection;
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function headings(): array
    {
        return ['type', 'name', 'label', 'hint', 'choices', 'choice_filter', 'calculation', 'required', 'relevant'];
    }


    public function title(): string
    {
        return 'survey';
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