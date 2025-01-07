<?php

namespace App\Exports\DataCollection\AllForm;

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
                'type' => 'select_one form_type', 
                'name' => 'select_form_type',
                'label' => 'Select form type',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => false,
            ],

            // Initial Survey
            [
                'type' => 'begin group', 
                'name' => 'initial_survey',
                'label' => 'Initial Survey',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => false,
                'relevant' => '${select_form_type} = "Initial Survey"',
            ],
            // General Details
            [
                'type' => 'select_one initial_community', 
                'name' => 'select_initial_community',
                'label' => 'Select community',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
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
                'calculation' => false,
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
                'required' => 'no',
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
                'required' => 'no',
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
                'type' => 'select_one cycle_year', 
                'name' => 'select_cycle_year',
                'label' => 'Select cycle year',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => false,
            ],
            [
                'type' => 'select_one system_type', 
                'name' => 'select_system_type',
                'label' => 'Select system type',
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


            
            // AC Survey	
            [
                'type' => 'begin group', 
                'name' => 'ac_details',
                'label' => 'AC Survey',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => false,
                'relevant' => false,
            ],
            [
                'type' => 'select_one ac_community', 
                'name' => 'select_ac_community',
                'label' => 'Select community',
                'hint' => false,
                'choices' => 'community',
                'choice_filter' => false,
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
            // First Group
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
                'calculation' => 'pulldata("households", "phone_number", "name", ${select_household_name})',
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

            // Second Group
            [
                'type' => 'integer', 
                'name' => 'number_of_male',
                'label' => 'Enter number of male',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => 'pulldata("households", "number_of_male", "name", ${select_household_name})',
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
                'calculation' => 'pulldata("households", "number_of_female", "name", ${select_household_name})',
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
                'calculation' => 'pulldata("households", "number_of_adults", "name", ${select_household_name})',
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
                'calculation' => 'pulldata("households", "number_of_children", "name", ${select_household_name})',
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
                'calculation' => 'pulldata("households", "school_students", "name", ${select_household_name})',
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
                'calculation' => 'pulldata("households", "university_students", "name", ${select_household_name})',
                'required' => 'yes',
                'relevant' => false,
            ],	

            // Third Group
            [
                'type' => 'select_one demolition', 
                'name' => 'demolition_order',
                'label' => 'Select demolition order',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => 'pulldata("households", "demolition_order", "name", ${select_household_name})',
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
                'calculation' => 'pulldata("households", "is_there_house_in_town", "name", ${select_household_name})',
                'required' => 'yes',
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
                'calculation' => 'pulldata("households", "size_of_herd", "name", ${select_household_name})',
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
                'calculation' => 'pulldata("households", "number_of_animal_shelters", "name", ${select_household_name})',
                'required' => 'no',
                'relevant' => '${select_herd} = "Yes"',
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
                'calculation' => 'pulldata("households", "number_of_cisterns", "name", ${select_household_name})',
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
                'calculation' => 'pulldata("households", "volume_of_cisterns", "name", ${select_household_name})',
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
                'calculation' => 'pulldata("households", "distance_from_house", "name", ${select_household_name})',
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
                'calculation' => 'pulldata("households", "shared_cisterns", "name", ${select_household_name})',
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
                'calculation' => 'pulldata("households", "is_there_izbih", "name", ${select_household_name})',
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
                'calculation' => 'pulldata("households", "how_long", "name", ${select_household_name})',
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
            
            
           	


            // Incidents

							

            // Fourth Group




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