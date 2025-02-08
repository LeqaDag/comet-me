<?php

namespace App\Exports\DataCollection\AcSurvey;

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
            // [
            //     'type' => 'select_one form_type', 
            //     'name' => 'select_form_type',
            //     'label' => 'Select form type',
            //     'hint' => false,
            //     'choices' => false,
            //     'choice_filter' => false,
            //     'calculation' => false,
            //     'required' => 'yes',
            //     'relevant' => false,
            // ],

            // // Initial Survey
            // [
            //     'type' => 'begin group', 
            //     'name' => 'initial_survey',
            //     'label' => 'Initial Survey',
            //     'hint' => false,
            //     'choices' => false,
            //     'choice_filter' => false,
            //     'calculation' => false,
            //     'required' => false,
            //     'relevant' => '${select_form_type} = "Initial Survey"',
            // ],
            // // General Details
            // [
            //     'type' => 'select_one initial_community', 
            //     'name' => 'select_initial_community',
            //     'label' => 'Select community',
            //     'hint' => false,
            //     'choices' => false,
            //     'choice_filter' => false,
            //     'calculation' => false,
            //     'required' => 'yes',
            //     'relevant' => false,
            // ],
            // [
            //     'type' => 'text', 
            //     'name' => 'arabic_name',
            //     'label' => 'Enter the household Arabic name',
            //     'hint' => false,
            //     'choices' => false,
            //     'choice_filter' => false,
            //     'calculation' => false,
            //     'required' => 'yes',
            //     'relevant' => false,
            // ],
            // [
            //     'type' => 'select_one profession', 
            //     'name' => 'select_profession',
            //     'label' => 'Select profession',
            //     'hint' => false,
            //     'choices' => false,
            //     'choice_filter' => false,
            //     'calculation' => false,
            //     'required' => 'yes',
            //     'relevant' => false,
            // ],
            // [
            //     'type' => 'integer', 
            //     'name' => 'phone_number',
            //     'label' => 'Enter phone number',
            //     'hint' => false,
            //     'choices' => false,
            //     'choice_filter' => false,
            //     'calculation' => false,
            //     'required' => 'yes',
            //     'relevant' => false,
            // ],
            // [
            //     'type' => 'integer', 
            //     'name' => 'additional_phone_number',
            //     'label' => 'Enter additional phone number',
            //     'hint' => false,
            //     'choices' => false,
            //     'choice_filter' => false,
            //     'calculation' => false,
            //     'required' => 'no',
            //     'relevant' => false,
            // ],
            // [
            //     'type' => 'integer', 
            //     'name' => 'number_of_male',
            //     'label' => 'Enter number of male',
            //     'hint' => false,
            //     'choices' => false,
            //     'choice_filter' => false,
            //     'calculation' => false,
            //     'required' => 'yes',
            //     'relevant' => false,
            // ],
            // [
            //     'type' => 'integer', 
            //     'name' => 'number_of_female',
            //     'label' => 'Enter number of female',
            //     'hint' => false,
            //     'choices' => false,
            //     'choice_filter' => false,
            //     'calculation' => false,
            //     'required' => 'yes',
            //     'relevant' => false,
            // ],
            // [
            //     'type' => 'integer', 
            //     'name' => 'number_of_adults',
            //     'label' => 'Enter number of adults',
            //     'hint' => false,
            //     'choices' => false,
            //     'choice_filter' => false,
            //     'calculation' => false,
            //     'required' => 'yes',
            //     'relevant' => false,
            // ],
            // [
            //     'type' => 'integer', 
            //     'name' => 'number_of_children',
            //     'label' => 'Enter number of children',
            //     'hint' => false,
            //     'choices' => false,
            //     'choice_filter' => false,
            //     'calculation' => false,
            //     'required' => 'yes',
            //     'relevant' => false,
            // ],
            // [
            //     'type' => 'integer', 
            //     'name' => 'school_students',
            //     'label' => 'Enter number of school',
            //     'hint' => false,
            //     'choices' => false,
            //     'choice_filter' => false,
            //     'calculation' => false,
            //     'required' => 'no',
            //     'relevant' => false,
            // ],
            // [
            //     'type' => 'integer', 
            //     'name' => 'university_students',
            //     'label' => 'Enter number of university',
            //     'hint' => false,
            //     'choices' => false,
            //     'choice_filter' => false,
            //     'calculation' => false,
            //     'required' => 'no',
            //     'relevant' => false,
            // ],
            // [
            //     'type' => 'select_one demolition', 
            //     'name' => 'demolition_order',
            //     'label' => 'Select demolition order',
            //     'hint' => false,
            //     'choices' => false,
            //     'choice_filter' => false,
            //     'calculation' => false,
            //     'required' => 'yes',
            //     'relevant' => false,
            // ],
            // [
            //     'type' => 'select_one cycle_year', 
            //     'name' => 'select_cycle_year',
            //     'label' => 'Select cycle year',
            //     'hint' => false,
            //     'choices' => false,
            //     'choice_filter' => false,
            //     'calculation' => false,
            //     'required' => 'yes',
            //     'relevant' => false,
            // ],
            // [
            //     'type' => 'select_one system_type', 
            //     'name' => 'select_system_type',
            //     'label' => 'Select system type',
            //     'hint' => false,
            //     'choices' => false,
            //     'choice_filter' => false,
            //     'calculation' => false,
            //     'required' => 'yes',
            //     'relevant' => false,
            // ],
            // [
            //     'type' => 'end group', 
            //     'name' => false,
            //     'label' => false,
            //     'hint' => false,
            //     'choices' => false,
            //     'choice_filter' => false,
            //     'calculation' => false,
            //     'required' => false,
            //     'relevant' => false,
            // ],

            
            // AC Survey	
            [
                'type' => 'begin group', 
                'name' => 'survey_ac',
                'label:English (en)' => 'AC Survey',
                'label:Arabic (ar)' => 'استبيان الـ AC ',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => false,
                'relevant' => false
            ],
            [
                'type' => 'select_one ac_community', 
                'name' => 'select_ac_community',
                'label:English (en)' => 'Select community',
                'label:Arabic (ar)' => 'اختر التجمع',
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
                'label:English (en)' => 'Select compound',
                'label:Arabic (ar)' => 'اختر المنطقة داخل التجمع',
                'hint' => false,
                'choices' => 'compound',
                'choice_filter' => '${select_ac_community} = community',
                'calculation' => false,
                'required' => 'no',
                'relevant' => false,
            ],
            [
                'type' => 'select_one household', 
                'name' => 'select_household_name',
                'label:English (en)' => 'Select household',
                'label:Arabic (ar)' => 'اختر العائلة',
                'hint' => false,
                'choices' => 'household', 
                'choice_filter' => '${select_ac_community} = community',
                'calculation' => false,
                'required' => 'no',
                'relevant' => false,
            ],
            // First Group
            [
                'type' => 'text', 
                'name' => 'english_name',
                'label:English (en)' => 'Enter the household English name',
                'label:Arabic (ar)' => 'ادخل اسم العائلة باللغة الانجليزية',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => 'pulldata("households", "label_en", "name", ${select_household_name})',
                'required' => 'no',
                'relevant' => false,
            ],
            [
                'type' => 'text', 
                'name' => 'arabic_name',
                'label:English (en)' => 'Enter the household Arabic name',
                'label:Arabic (ar)' => 'ادخل اسم العائلة باللغة العربية',
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
                'label:English (en)' => 'Select profession',
                'label:Arabic (ar)' => 'اختر المهنة',
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
                'label:English (en)' => 'Enter phone number',
                'label:Arabic (ar)' => 'ادخل رقم الهاتف',
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
                'label:English (en)' => 'Enter additional phone number',
                'label:Arabic (ar)' => 'ادخل رقم هاتف احتياطي',
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
                'label:English (en)' => 'Enter number of male',
                'label:Arabic (ar)' => 'عدد الذكور',
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
                'label:English (en)' => 'Enter number of female',
                'label:Arabic (ar)' => 'عدد الاناث',
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
                'label:English (en)' => 'Enter number of adults',
                'label:Arabic (ar)' => 'عدد الكبار',
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
                'label:English (en)' => 'Enter number of children',
                'label:Arabic (ar)' => 'عدد الصغار',
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
                'label:English (en)' => 'Enter number of school',
                'label:Arabic (ar)' => 'عدد طلاب المدارس',
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
                'label:English (en)' => 'Enter number of university',
                'label:Arabic (ar)' => 'عدد طلاب الجامعات',
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
                'label:English (en)' => 'Select demolition order',
                'label:Arabic (ar)' => 'هل يوجد اخطار هدم',
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
                'label:English (en)' => 'Select house in the town',
                'label:Arabic (ar)' => 'هل يوجد بيت في البلدة/القرية',
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
                'label:English (en)' => 'Herds',
                'label:Arabic (ar)' => 'الأغنام',
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
                'label:English (en)' => 'Select herd',
                'label:Arabic (ar)' => 'هل يوجد عنده غنم',
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
                'label:English (en)' => 'Enter size of herds',
                'label:Arabic (ar)' => 'ادخل عدد الأغنام',
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
                'label:English (en)' => 'Enter number of animal shelter',
                'label:Arabic (ar)' => 'ادخل عدد بيوت الغنم (البركسات)',
                'hint' => false, 
                'choices' => false,
                'choice_filter' => false,
                'calculation' => 'pulldata("households", "number_of_animal_shelters", "name", ${select_household_name})',
                'required' => 'no',
                'relevant' => '${select_herd} = "Yes"',
            ],
            [
                'type' => 'end group', 
                'name' => false,
                'label:English (en)' => false,
                'label:Arabic (ar)' => false,
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
                'label:English (en)' => 'Cistern',
                'label:Arabic (ar)' => 'آبار المياه',
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
                'label:English (en)' => 'Select cistern',
                'label:Arabic (ar)' => 'هل يوجد بئر',
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
                'label:English (en)' => 'Enter how many cisterns',
                'label:Arabic (ar)' => 'كم عدد الآبار',
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
                'label:English (en)' => 'Enter the depth in Liter',
                'label:Arabic (ar)' => 'ادخل حجم البئر بالكوب',
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
                'label:English (en)' => 'Enter the distance in meter',
                'label:Arabic (ar)' => 'كم يبعد البئر عن المنزل (بالمتر)',
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
                'label:English (en)' => 'Select cistern shared',
                'label:Arabic (ar)' => 'هل البئر مشترك',
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
                'label:English (en)' => false,
                'label:Arabic (ar)' => false,
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
                'label:English (en)' => 'Izbih',
                'label:Arabic (ar)' => 'العزبة',
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
                'label:English (en)' => 'Select Izbih',
                'label:Arabic (ar)' => 'هل يعزب',
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
                'label:English (en)' => 'Enter how long',
                'label:Arabic (ar)' => 'كم يمكث بالعزبة (بالشهور)',
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
                'label:English (en)' => false,
                'label:Arabic (ar)' => false,
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
                'label:English (en)' => false,
                'label:Arabic (ar)' => false,
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
                'label:English (en)' => 'Enter notes',
                'label:Arabic (ar)' => 'ملاحظات',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'no',
                'relevant' => false,
                'appearance' => 'long-text'
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
        return ['type', 'name', 'label:English (en)', 'label:Arabic (ar)', 'hint', 'choices', 'choice_filter', 'calculation', 'required', 'relevant'];
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