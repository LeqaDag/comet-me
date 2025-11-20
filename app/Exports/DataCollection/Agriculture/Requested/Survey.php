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

            // Farmer details
            [
                'type' => 'begin group', 
                'name' => 'farmer_details',
                'label:English (en)' => 'Requested Farmer Details',
                'label:Arabic (ar)' => 'معلومات طالب النظام (المزارع)',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => false,
                'relevant' => false,
                'repeat_count' => false,
            ],
            [
                'type' => 'select_one region', 
                'name' => 'select_region',
                'label:English (en)' => 'choose region',
                'label:Arabic (ar)' => 'اختر المنطقة/المدينة',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => false,
                'repeat_count' => false,
            ],
            [
                'type' => 'select_one sub_region', 
                'name' => 'select_sub_region',
                'label:English (en)' => 'Choose sub region',
                'label:Arabic (ar)' => 'اختر البلدة/القرية',
                'hint' => false,
                'choices' => 'sub_region',
                'choice_filter' => '${select_region} = region',
                'calculation' => false,
                'required' => 'yes',
                'relevant' => false,
                'repeat_count' => false,
            ],
            [
                'type' => 'select_one community', 
                'name' => 'select_community',
                'label:English (en)' => 'Choose community',
                'label:Arabic (ar)' => 'اختر التجمع',
                'hint' => false,
                'choices' => false,
                'choice_filter' => '${select_sub_region} = sub_region',
                'calculation' => false,
                'required' => 'yes',
                'relevant' => false,
                'repeat_count' => false,
            ],
            [
                'type' => 'select_one household', 
                'name' => 'select_household_name',
                'label:English (en)' => 'Choose household',
                'label:Arabic (ar)' => 'اختر اسم العائلة',
                'hint' => false,
                'choices' => 'household', 
                'choice_filter' => '${select_community} = community',
                'calculation' => false,
                'required' => 'no',
                'relevant' => false,
                'repeat_count' => false,
            ],
            [
                'type' => 'integer', 
                'name' => 'phone_number',
                'label:English (en)' => 'Phone number',
                'label:Arabic (ar)' => 'رقم الهاتف',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => 'pulldata("households", "phone_number", "name", ${select_household_name})',
                'required' => 'yes',
                'relevant' => false,
                'repeat_count' => false,
            ],
            [
                'type' => 'integer', 
                'name' => 'size_of_herd',
                'label:English (en)' => 'Number of sheep you own',
                'label:Arabic (ar)' => 'عدد الأغنام التي تمتلكها',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => 'pulldata("households", "size_of_herd", "name", ${select_household_name})',
                'required' => 'no',
                'relevant' => false,
                'repeat_count' => false,
            ],
            [
                'type' => 'select_one is_shared', 
                'name' => 'select_is_shared',
                'label:English (en)' => 'Do you have shared users with you on herds?',
                'label:Arabic (ar)' => 'هل يشاركك أحد بالاغنام؟',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => false, 
                'repeat_count' => false,
            ],
            [
                'type' => 'integer',
                'name' => 'number_of_shared_users',
                'label:English (en)' => 'How many shared users?',
                'label:Arabic (ar)' => 'كم عدد المستخدمين المشاركين؟',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => '${select_is_shared} = "Yes"',
                'repeat_count' => false,
            ],
            [
                'type' => 'begin_repeat',
                'name' => 'shared_user_info',
                'label:English (en)' => 'Shared user details',
                'label:Arabic (ar)' => 'تفاصيل المستخدمين المشاركين',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => '${select_is_shared} = "Yes"',
                'repeat_count' => '${number_of_shared_users}',
            ],

            [
                'type' => 'select_one household_list',
                'name' => 'shared_user_name',
                'label:English (en)' => 'Select household name',
                'label:Arabic (ar)' => 'اختر اسم الأسرة',
                'hint' => false,
                'choices' => 'household_list', 
                'choice_filter' => '${select_community} = community',
                'calculation' => false,
                'required' => 'yes',
                'relevant' => false,
                'repeat_count' => false,
            ],
            [
                'type' => 'integer',
                'name' => 'shared_user_herds',
                'label:English (en)' => 'How many herds for this user?',
                'label:Arabic (ar)' => 'كم عدد الأغنام لهذا المستخدم؟',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => false,
                'repeat_count' => false,
            ],
            [
                'type' => 'end_repeat',
                'name' => 'shared_user_info',
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
                'repeat_count' => false,
            ],


            
            // System details
            [
                'type' => 'begin group', 
                'name' => 'farmer_details',
                'label:English (en)' => 'Requested System Details',
                'label:Arabic (ar)' => 'معلومات النظام',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => false,
                'relevant' => false,
                'repeat_count' => false,
            ],
            [
                'type' => 'select_one cycle_year', 
                'name' => 'select_cycle_year',
                'label:English (en)' => 'Choose cycle year',
                'label:Arabic (ar)' => 'اختر دورة المشروع',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => false,
                'repeat_count' => false,
            ],
            [
                'type' => 'select_one installtion_type', 
                'name' => 'select_installtion_type',
                'label:English (en)' => 'Choose installtion type',
                'label:Arabic (ar)' => 'اختر نوع تثبيت النظام',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => false,
                'repeat_count' => false,
            ],
            // [
            //     'type' => 'select_one system_type', 
            //     'name' => 'select_system_type',
            //     'label:English (en)' => 'Choose system type',
            //     'label:Arabic (ar)' => 'اختر نوع النظام',
            //     'hint' => false,
            //     'choices' => false,
            //     'choice_filter' => false,
            //     'calculation' => false,
            //     'required' => 'yes',
            //     'relevant' => false,
            //     'repeat_count' => false,
            // ],
            [
                'type' => 'select_one area_type', 
                'name' => 'select_area_type',
                'label:English (en)' => 'Which area will you build the system in?',
                'label:Arabic (ar)' => 'ما هي المنطقة التي سوف تبني فيها النظام',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => false,
                'repeat_count' => false,
            ],
            [
                'type' => 'select_one area', 
                'name' => 'select_area',
                'label:English (en)' => 'Do you have area for the installation?',
                'label:Arabic (ar)' => 'هل تتوفر مساحة لديك؟',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => false,
                'repeat_count' => false,
            ],
            [
                'type' => 'text', 
                'name' => 'area',
                'label:English (en)' => 'How much area is available?',
                'label:Arabic (ar)' => 'كم المساحة المتوفرة؟',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'no',
                'relevant' => '${select_area} = "Yes"',
                'repeat_count' => false,
                'appearance' => 'long-text'
            ],
            [
                'type' => 'text', 
                'name' => 'alternative_area',
                'label:English (en)' => 'Specify where the alternative location is?',
                'label:Arabic (ar)' => 'يرجى ذكر أين المكان البديل؟',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'no',
                'relevant' => '${select_area} = "No"',
                'repeat_count' => false,
                'appearance' => 'long-text',
            ],
            [
                'type' => 'select_one animals', 
                'name' => 'select_animal', 
                'label:English (en)' => 'Do you have other animals that will benefit from the project?',
                'label:Arabic (ar)' => 'هل يتوفر عندك حيوانات اخرى سوف تستفيد من المشروع؟',
                'hint' => false, 
                'choices' => false, 
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => false,
                'repeat_count' => false,
            ],
            [
                'type' => 'select_one goat', 
                'name' => 'select_goat', 
                'label:English (en)' => 'Do you have goats?',
                'label:Arabic (ar)' => 'هل يوجد لديك ماعز؟',
                'hint' => false, 
                'choices' => false, 
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'no',
                'relevant' => '${select_animal} = "Yes"',
                'repeat_count' => false,
            ],
            [
                'type' => 'integer', 
                'name' => 'size_of_goat',
                'label:English (en)' => 'Number of goats you own',
                'label:Arabic (ar)' => 'ادخل عدد الماعز',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => '${select_goat} = "Yes"',
                'repeat_count' => false,
            ],
            [
                'type' => 'select_one cow', 
                'name' => 'select_cow', 
                'label:English (en)' => 'Do you have cow?',
                'label:Arabic (ar)' => 'هل يوجد لديك بقر؟',
                'hint' => false, 
                'choices' => false, 
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'no',
                'relevant' => '${select_animal} = "Yes"',
                'repeat_count' => false,
            ],
            [
                'type' => 'integer', 
                'name' => 'size_of_cow',
                'label:English (en)' => 'Number of cows you own',
                'label:Arabic (ar)' => 'ادخل عدد الأبقار',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => '${select_cow} = "Yes"',
                'repeat_count' => false,
            ],
            [
                'type' => 'select_one chicken', 
                'name' => 'select_chicken', 
                'label:English (en)' => 'Do you have chicken?',
                'label:Arabic (ar)' => 'هل يوجد لديك دجاج؟',
                'hint' => false, 
                'choices' => false, 
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'no',
                'relevant' => '${select_animal} = "Yes"',
                'repeat_count' => false,
            ],
            [
                'type' => 'integer', 
                'name' => 'size_of_chicken',
                'label:English (en)' => 'Number of chickens you own',
                'label:Arabic (ar)' => 'ادخل عدد الدجاج',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => '${select_chicken} = "Yes"',
                'repeat_count' => false,
            ],
            [
                'type' => 'select_one camel', 
                'name' => 'select_camel', 
                'label:English (en)' => 'Do you have camels?',
                'label:Arabic (ar)' => 'هل يوجد لديك جمال؟',
                'hint' => false, 
                'choices' => false, 
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'no',
                'relevant' => '${select_animal} = "Yes"',
                'repeat_count' => false,
            ],
            [
                'type' => 'integer', 
                'name' => 'size_of_camel',
                'label:English (en)' => 'Number of camels you own',
                'label:Arabic (ar)' => 'ادخل عدد الجمال',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => '${select_camel} = "Yes"',
                'repeat_count' => false,
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
                'repeat_count' => false,
            ],


            // Agriculture Notes
            [
                'type' => 'text', 
                'name' => 'agriculture_notes',
                'label:English (en)' => 'Notes',
                'label:Arabic (ar)' => 'ملاحظات',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'no',
                'relevant' => false,
                'repeat_count' => false,
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
        return ['type', 'name', 'label:English (en)', 'label:Arabic (ar)', 'hint', 'choices', 'choice_filter', 'calculation', 
            'required', 'relevant', 'repeat_count', 'appearance'];
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