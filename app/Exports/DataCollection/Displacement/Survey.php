<?php

namespace App\Exports\DataCollection\Displacement;

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
                'type' => 'begin group', 
                'name' => 'from_displacement',
                'label:Arabic (ar)' => 'العائلات الراحلة (هنا يتم اختيار التجمع والعائلات التي رحلت)',
                'label:English (en)' => 'Displaced families',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => false,
                'relevant' => false,
            ],
            [
                'type' => 'select_one region', 
                'name' => 'select_region',
                'label:Arabic (ar)' => 'اختر المنطقة/المدينة',
                'label:English (en)' => 'choose region',
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
                'label:Arabic (ar)' => 'اختر البلدة/القرية',
                'label:English (en)' => 'Choose sub region',
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
                'label:Arabic (ar)' => 'اختر التجمع',
                'label:English (en)' => 'Choose community',
                'hint' => false,
                'choices' => false,
                'choice_filter' => '${select_sub_region} = sub_region',
                'calculation' => false,
                'required' => 'yes',
                'relevant' => false,
            ],
            [
                'type' => 'select_multiple household', 
                'name' => 'select_household_name',
                'label:Arabic (ar)' => 'اختر اسم العائلة',
                'label:English (en)' => 'Choose household',
                'hint' => false,
                'choices' => 'household', 
                'choice_filter' => '${select_community} = community',
                'calculation' => false,
                'required' => 'no',
                'relevant' => false,
            ],
            [
                'type' => 'end group', 
                'name' => false,
                'label:Arabic (ar)' => false,
                'label:English (en)' => false,
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => false,
                'relevant' => false,
            ],

            // Displacement Details Group
            [
                'type' => 'begin group', 
                'name' => 'displacement_details',
                'label:Arabic (ar)' => 'تفاصيل الرحيل (التاريخ، المنطقة الجديدة، وضع نظام الكهرباء)',
                'label:English (en)' => 'Displacement details',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => false,
                'relevant' => false,
            ],
            [
                'type' => 'date', 
                'name' => 'displacement_date',
                'label:Arabic (ar)' => 'اختر تاريخ الترحيل',
                'label:English (en)' => 'Displacement date',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => false,
            ],
            [
                'type' => 'select_one area', 
                'name' => 'select_area',
                'label:Arabic (ar)' => 'منطقة a/b/c',
                'label:English (en)' => 'Area',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => false,
            ],
            [
                'type' => 'select_one new_region', 
                'name' => 'select_new_region',
                'label:Arabic (ar)' => 'اين رحلوا؟',
                'label:English (en)' => 'New region',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'no',
                'relevant' => false,
            ],
            [
                'type' => 'select_one system_retrieved', 
                'name' => 'select_system_retrieved',
                'label:Arabic (ar)' => 'ماذا حدث لنظام الكهرباء؟',
                'label:English (en)' => 'System retrieved',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => false,
            ],
            [
                'type' => 'select_one household_status', 
                'name' => 'select_household_status',
                'label:Arabic (ar)' => 'اختر واحد من التالي',
                'label:English (en)' => 'Household statues',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => false,
            ],
            [
                'type' => 'text', 
                'name' => 'notes',
                'label:Arabic (ar)' => 'ملاحظات اخرى',
                'label:English (en)' => 'Notes',
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
                'label:Arabic (ar)' => false,
                'label:English (en)' => false,
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => false,
                'relevant' => false,
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
        return ['type', 'name', 'label:Arabic (ar)', 'label:English (en)', 'hint', 'choices', 'choice_filter', 'calculation', 
            'required', 'relevant', 'appearance'];
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