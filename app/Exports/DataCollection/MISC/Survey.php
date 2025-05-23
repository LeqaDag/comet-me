<?php

namespace App\Exports\DataCollection\MISC;

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
                'label:English (en)' => 'Choose region',
                'label:Arabic (ar)' => 'اختر المنطقة/المدينة',
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
                'label:English (en)' => 'Choose sub region',
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
                'label:English (en)' => 'Choose community',
                'label:Arabic (ar)' => 'اختر التجمع',
                'hint' => false,
                'choices' => false,
                'choice_filter' => '${select_sub_region} = sub_region',
                'calculation' => false,
                'required' => 'yes',
                'relevant' => false,
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
            ],
            [
                'type' => 'text', 
                'name' => 'english_name',
                'label:English (en)' => 'Enter the household English name',
                'label:Arabic (ar)' => 'الاسم باللغة الانجليزية',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => 'pulldata("requested_households", "label_en", "name", ${select_household_name})',
                'required' => 'no',
                'relevant' => false,
            ],
            [
                'type' => 'text', 
                'name' => 'arabic_name',
                'label:English (en)' => 'Enter the household Arabic name',
                'label:Arabic (ar)' => 'الاسم باللغة العربية',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => 'pulldata("requested_households", "label_ar", "name", ${select_household_name})',
                'required' => 'no',
                'relevant' => false,
            ],
            [
                'type' => 'date', 
                'name' => 'request_date',
                'label:English (en)' => 'Request date',
                'label:Arabic (ar)' => 'تاريخ الطلب',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => 'pulldata("requested_households", "request_date", "name", ${select_household_name})',
                'required' => 'yes',
                'relevant' => false,
            ],
            [
                'type' => 'text', 
                'name' => 'referred_by',
                'label:English (en)' => 'Referred by',
                'label:Arabic (ar)' => 'مسجل الطلب',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => 'pulldata("requested_households", "referred_by", "name", ${select_household_name})',
                'required' => 'yes',
                'relevant' => false,
            ],
            [
                'type' => 'select_one status', 
                'name' => 'select_status',
                'label:English (en)' => 'Status',
                'label:Arabic (ar)' => 'حالة الطلب',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => 'pulldata("requested_households", "status", "name", ${select_household_name})',
                'required' => 'yes',
                'relevant' => false
            ],
            [
                'type' => 'select_one energy_system_type', 
                'name' => 'energy_system_type',
                'label:English (en)' => 'Energy system type',
                'label:Arabic (ar)' => 'نوع النظام',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => 'pulldata("requested_households", "energy_system_type", "name", ${select_household_name})',
                'required' => 'yes',
                'relevant' => false
            ],
            [
                'type' => 'text', 
                'name' => 'misc_notes',
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
                'type' => 'select_one action_type', 
                'name' => 'select_action_type',
                'label:English (en)' => 'Action to do',
                'label:Arabic (ar)' => 'الاجراء التي سيتم اتخاذه',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => false
            ],
            [
                'type' => 'text', 
                'name' => 'postponed_reason',
                'label:English (en)' => 'Postponed reason',
                'label:Arabic (ar)' => 'سبب التأجيل',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => '${select_action_type} = "Postponed"',
                'appearance' => 'long-text'
            ],
            [
                'type' => 'text', 
                'name' => 'delete_reason',
                'label:English (en)' => 'Deletion reason',
                'label:Arabic (ar)' => 'سبب الحذف',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => '${select_action_type} = "Delete"',
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