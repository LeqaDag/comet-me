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
                'label:English (en)' => 'Select region',
                'label:Arabic (ar)' => 'اختر المدينة',
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
                'label:English (en)' => 'Select sub region',
                'label:Arabic (ar)' => 'اختر البلدة/القرية',
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
                'label:English (en)' => 'Select community',
                'label:Arabic (ar)' => 'اختر التجمع',
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
                'label:English (en)' => 'Select compound',
                'label:Arabic (ar)' => 'اختر المنطقة داخل التجمع',
                'hint' => false,
                'choices' => 'compound',
                'choice_filter' => '${select_community} = community',
                'calculation' => false,
                'required' => 'no',
                'relevant' => false,
            ],

            // Updating Community form
            [
                'type' => 'begin group', 
                'name' => 'community_form',
                'label:English (en)' => 'Community/Compound Form',
                'label:Arabic (ar)' => 'نموذج التجمع',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => false,
                'relevant' => false
            ],
            [
                'type' => 'geopoint', 
                'name' => 'community_location',
                'label:English (en)' => 'Select the community location',
                'label:Arabic (ar)' => 'اختر موقع التجمع',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'no',
                'relevant' => false
            ],
            [
                'type' => 'text', 
                'name' => 'english_name',
                'label:English (en)' => 'Enter the community English name',
                'label:Arabic (ar)' => 'اسم التجمع باللغة الانجليزية',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => 'pulldata("communities", "label_en", "name", ${select_community})',
                'required' => 'yes',
                'relevant' => false,
            ],
            [
                'type' => 'text', 
                'name' => 'arabic_name',
                'label:English (en)' => 'Enter the community Arabic name',
                'label:Arabic (ar)' => 'اسم التجمع باللغة العربية',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => 'pulldata("communities", "label_ar", "name", ${select_community})',
                'required' => 'yes',
                'relevant' => false,
            ],
            [
                'type' => 'select_one fallah', 
                'name' => 'select_fallah',
                'label:English (en)' => 'Select Fallah',
                'label:Arabic (ar)' => 'فلاحين؟',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => false,
            ],
            [
                'type' => 'select_one bedouin', 
                'name' => 'select_bedouin',
                'label:English (en)' => 'Select bedouin',
                'label:Arabic (ar)' => 'بدو؟',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => false,
            ],
            [
                'type' => 'select_one reception', 
                'name' => 'select_reception',
                'label:English (en)' => 'Select cellular reception',
                'label:Arabic (ar)' => 'هل يوجد تغطية (ارسال)',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => false,
            ],
            [
                'type' => 'select_multiple products', 
                'name' => 'select_product',
                'label:English (en)' => 'Select products',
                'label:Arabic (ar)' => 'المنتجات',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'no',
                'relevant' => false,
            ],
            [
                'type' => 'select_multiple water_sources', 
                'name' => 'select_water_sources',
                'label:English (en)' => 'Select water sources',
                'label:Arabic (ar)' => 'مصادر المياه',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => false,
            ],
            [
                'type' => 'begin group', 
                'name' => 'legal_details',
                'label:English (en)' => 'Legal Details',
                'label:Arabic (ar)' => 'المعلومات القانونية',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => false,
                'relevant' => false,
            ],
            [
                'type' => 'select_one demolition', 
                'name' => 'select_demolition',
                'label:English (en)' => 'Demolition orders/demolitions',
                'label:Arabic (ar)' => 'هل يوجد أوامر هدم؟',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => false,
            ],
            [
                'type' => 'integer', 
                'name' => 'demolition_number',
                'label:English (en)' => 'How many Demolitions?',
                'label:Arabic (ar)' => 'كم عددهم؟',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'no',
                'relevant' => '${select_demolition} = "Yes"',
            ],
            [
                'type' => 'text', 
                'name' => 'demolition_legal',
                'label:English (en)' => 'Demolition Legal Status',
                'label:Arabic (ar)' => 'الحالة القانونية للهدم',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'no',
                'relevant' => '${select_demolition} = "Yes"',
            ],
            [
                'type' => 'text', 
                'name' => 'lawyer',
                'label:English (en)' => 'Lawyer',
                'label:Arabic (ar)' => 'المحامي',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'no',
                'relevant' => '${select_demolition} = "Yes"',
            ],
            [
                'type' => 'text', 
                'name' => 'land_status',
                'label:English (en)' => 'Land Status',
                'label:Arabic (ar)' => 'ملكية الارض',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'no',
                'relevant' => '${select_demolition} = "Yes"',
            ],
            [
                'type' => 'select_one demolition_executed', 
                'name' => 'select_demolition_executed',
                'label:English (en)' => 'Have demolition orders been executed?',
                'label:Arabic (ar)' => 'هل تم تنفيذ أوامر هدم؟',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => false,
            ],
            [
                'type' => 'date', 
                'name' => 'demolition_date',
                'label:English (en)' => 'When was the last demolition?',
                'label:Arabic (ar)' => 'تاريخ آخر هدم؟',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'no',
                'relevant' => '${select_demolition_executed} = "Yes"',
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
                'name' => 'community_notes',
                'label:English (en)' => 'Notes',
                'label:Arabic (ar)' => 'ملاحظات اخرى',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'no',
                'relevant' => false,
                'appearance' => 'long-text'
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
            // End of community form

            // [
            //     'type' => 'begin group', 
            //     'name' => 'educational_form',
            //     'label' => 'Educational Form',
            //     'hint' => false,
            //     'choices' => false,
            //     'choice_filter' => false,
            //     'calculation' => false,
            //     'required' => false,
            //     'relevant' => false,
            // ],
            // [
            //     'type' => 'select_one neighboring_communities', 
            //     'name' => 'select_neighboring_communities',
            //     'label' => 'Do students attend schools in neighboring communities? How many',
            //     'hint' => false,
            //     'choices' => false,
            //     'choice_filter' => false,
            //     'calculation' => false,
            //     'required' => 'yes',
            //     'relevant' => false,
            // ],
            // [
            //     'type' => 'select_one neighboring_school1', 
            //     'name' => 'select_neighboring_school1',
            //     'label' => 'Neighboring school 1',
            //     'hint' => false,
            //     'choices' => false,
            //     'choice_filter' => false,
            //     'calculation' => false,
            //     'required' => 'yes',
            //     'relevant' => '${select_neighboring_communities} = "1" OR ${select_neighboring_communities} = "2"',
            // ],
            // [
            //     'type' => 'integer', 
            //     'name' => 'male1',
            //     'label' => 'How Many Male Students?',
            //     'hint' => false,
            //     'choices' => false,
            //     'choice_filter' => false,
            //     'calculation' => false,
            //     'required' => 'no',
            //     'relevant' => '${select_neighboring_communities} = "1" OR ${select_neighboring_communities} = "2"',
            // ],
            // [
            //     'type' => 'integer', 
            //     'name' => 'female1',
            //     'label' => 'How Many Female Students?',
            //     'hint' => false,
            //     'choices' => false,
            //     'choice_filter' => false,
            //     'calculation' => false,
            //     'required' => 'no',
            //     'relevant' => '${select_neighboring_communities} = "1" OR ${select_neighboring_communities} = "2"',
            // ],
            // [
            //     'type' => 'integer', 
            //     'name' => 'grade_from1',
            //     'label' => 'From Grade?',
            //     'hint' => false,
            //     'choices' => false,
            //     'choice_filter' => false,
            //     'calculation' => false,
            //     'required' => 'no',
            //     'relevant' => '${select_neighboring_communities} = "1" OR ${select_neighboring_communities} = "2"',
            // ],
            // [
            //     'type' => 'integer', 
            //     'name' => 'to_grade1',
            //     'label' => 'To Grade?',
            //     'hint' => false,
            //     'choices' => false,
            //     'choice_filter' => false,
            //     'calculation' => false,
            //     'required' => 'no',
            //     'relevant' => '${select_neighboring_communities} = "1" OR ${select_neighboring_communities} = "2"',
            // ],
            // [
            //     'type' => 'select_one neighboring_school2', 
            //     'name' => 'select_neighboring_school2',
            //     'label' => 'Neighboring school 2',
            //     'hint' => false,
            //     'choices' => false,
            //     'choice_filter' => false,
            //     'calculation' => false,
            //     'required' => 'yes',
            //     'relevant' => '${select_neighboring_communities} = "2"',
            // ],
            // [
            //     'type' => 'integer', 
            //     'name' => 'male2',
            //     'label' => 'How Many Male Students?',
            //     'hint' => false,
            //     'choices' => false,
            //     'choice_filter' => false,
            //     'calculation' => false,
            //     'required' => 'no',
            //     'relevant' => '${select_neighboring_communities} = "2"',
            // ],
            // [
            //     'type' => 'integer', 
            //     'name' => 'female2',
            //     'label' => 'How Many Female Students?',
            //     'hint' => false,
            //     'choices' => false,
            //     'choice_filter' => false,
            //     'calculation' => false,
            //     'required' => 'no',
            //     'relevant' => '${select_neighboring_communities} = "2"',
            // ],
            // [
            //     'type' => 'integer', 
            //     'name' => 'grade_from2',
            //     'label' => 'From Grade?',
            //     'hint' => false,
            //     'choices' => false,
            //     'choice_filter' => false,
            //     'calculation' => false,
            //     'required' => 'no',
            //     'relevant' => '${select_neighboring_communities} = "2"',
            // ],
            // [
            //     'type' => 'integer', 
            //     'name' => 'to_grade2',
            //     'label' => 'To Grade?',
            //     'hint' => false,
            //     'choices' => false,
            //     'choice_filter' => false,
            //     'calculation' => false,
            //     'required' => 'no',
            //     'relevant' => '${select_neighboring_communities} = "2"',
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
            'required', 'relevant'];
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