<?php

namespace App\Exports\DataCollection\Workshops;

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
                'type' => 'select_one workshop_type', 
                'name' => 'select_workshop_type',
                'label:English (en)' => 'Choose workshop type',
                'label:Arabic (ar)' => 'اختر نوع الورشة',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => false,
            ],
            [
                'type' => 'date', 
                'name' => 'workshop_date',
                'label:English (en)' => 'Workshop date',
                'label:Arabic (ar)' => 'تاريخ الطلب',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => false,
            ],
            [
                'type' => 'text', 
                'name' => 'workshop_hours',
                'label:English (en)' => 'Workshop hours',
                'label:Arabic (ar)' => 'مدة الورشة',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => false,
            ],
            [
                'type' => 'select_one lead_by', 
                'name' => 'select_lead_by',
                'label:English (en)' => 'Lead by',
                'label:Arabic (ar)' => 'المسؤول',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => false,
            ],
            [
                'type' => 'integer', 
                'name' => 'attendance_male',
                'label:English (en)' => 'Attendance male',
                'label:Arabic (ar)' => 'الحضور الذكور',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => false,
            ],
            [
                'type' => 'integer', 
                'name' => 'attendance_female',
                'label:English (en)' => 'Attendance female',
                'label:Arabic (ar)' => 'الحضور الاناث',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => false,
            ],
            [
                'type' => 'integer', 
                'name' => 'attendance_youth',
                'label:English (en)' => 'Attendance youth',
                'label:Arabic (ar)' => 'الحضور الشباب',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => false,
            ],
            [
                'type' => 'text', 
                'name' => 'feedback',
                'label:English (en)' => 'Focus group feedback/stories',
                'label:Arabic (ar)' => 'ملاحظات المجموعات البؤرية / قصص',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'no',
                'relevant' => false,
                'appearance' => 'long-text'
            ],
            [
                'type' => 'text', 
                'name' => 'notes',
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