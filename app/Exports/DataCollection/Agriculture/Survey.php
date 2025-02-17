<?php

namespace App\Exports\DataCollection\Agriculture;

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
                'label:English (en)' => 'Farmer details',
                'label:Arabic (ar)' => 'معلومات المزارع',
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
                'label:English (en)' => 'choose region',
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
                'name' => 'size_of_herd',
                'label:English (en)' => 'Number of sheep you own',
                'label:Arabic (ar)' => 'ادخل عدد الأغنام',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => 'pulldata("households", "size_of_herd", "name", ${select_household_name})',
                'required' => 'no',
                'relevant' => false,
            ],
            [
                'type' => 'integer', 
                'name' => 'size_of_goat',
                'label:English (en)' => 'Number of goats you own',
                'label:Arabic (ar)' => 'ادخل عدد الماعز',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => 'pulldata("households", "size_of_goat", "name", ${select_household_name})',
                'required' => 'no',
                'relevant' => false,
            ],
            [
                'type' => 'integer', 
                'name' => 'size_of_cow',
                'label:English (en)' => 'Number of cows you own',
                'label:Arabic (ar)' => 'ادخل عدد الأبقار',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => 'pulldata("households", "size_of_cow", "name", ${select_household_name})',
                'required' => 'no',
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

            // Income
            [
                'type' => 'begin group', 
                'name' => 'income_details',
                'label:English (en)' => 'Income details',
                'label:Arabic (ar)' => 'الدخل',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => false,
                'relevant' => false,
            ],
            [
                'type' => 'select_multiple source_income', 
                'name' => 'select_source_income',
                'label:English (en)' => 'What is your main source of income',
                'label:Arabic (ar)' => 'ما هي مصادر الدخل',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => false,
                'appearance' => 'long-text'
            ],
            [
                'type' => 'select_multiple dairy_products', 
                'name' => 'select_dairy_products',
                'label:English (en)' => 'Dairy products',
                'label:Arabic (ar)' => 'منتجات الألبان',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => 'selected(${select_source_income}, "dairy_products")',
            ],
            [
                'type' => 'integer', 
                'name' => 'herd_sold',
                'label:English (en)' => 'How many animals have you sold in the last 3 months?',
                'label:Arabic (ar)' => 'كم عدد الأغنام التي قمت ببيعها آخر 3 أشهر',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => 'selected(${select_source_income}, "livestock_sales")',
            ],
            [
                'type' => 'integer', 
                'name' => 'ils_sold',
                'label:English (en)' => 'What price do you get per animal? ILS',
                'label:Arabic (ar)' => 'كم عدد الأغنام التي قمت ببيعها آخر 3 أشهر',
                'hint' => 'ILS',
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => 'selected(${select_source_income}, "livestock_sales")',
            ],
            [
                'type' => 'integer', 
                'name' => 'jod_sold',
                'label:English (en)' => 'What price do you get per animal? JOD',
                'label:Arabic (ar)' => 'كم عدد الأغنام التي قمت ببيعها آخر 3 أشهر',
                'hint' => 'JOD',
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => 'selected(${select_source_income}, "livestock_sales")',
            ],
            [
                'type' => 'select_one herd_reduced', 
                'name' => 'select_herd_reduced',
                'label:English (en)' => 'Has your herd reduced in size over the last year?',
                'label:Arabic (ar)' => 'هل انخفض عدد الأغنام آخر سنة؟',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => 'selected(${select_source_income}, "livestock_sales")',
            ],
            [
                'type' => 'integer', 
                'name' => 'herd_reduced_number_from',
                'label:English (en)' => 'By how much (From)',
                'label:Arabic (ar)' => 'كم؟',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => '${select_herd_reduced} = "Yes"',
            ],
            [
                'type' => 'integer', 
                'name' => 'herd_reduced_number_to',
                'label:English (en)' => 'By how much (To)',
                'label:Arabic (ar)' => 'كم؟',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => '${select_herd_reduced} = "Yes"',
            ],
            [
                'type' => 'text', 
                'name' => 'other_income',
                'label:English (en)' => 'Please specify other sources',
                'label:Arabic (ar)' => 'اذكر المصادر الاخرى',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => 'selected(${select_source_income}, "other")',
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

            // Agriculture Details Group
            [
                'type' => 'begin group', 
                'name' => 'agriculture_details',
                'label:English (en)' => 'Agriculture details',
                'label:Arabic (ar)' => 'معلومات متعلقة بمشروع الزراعة',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => false,
                'relevant' => false,
            ],
            [
                'type' => 'select_multiple feed_types', 
                'name' => 'select_feed_types',
                'label:English (en)' => 'What types of feed do you primarily use?',
                'label:Arabic (ar)' => 'ماذا تُطعم أغنامك؟',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => false,
            ],
            [
                'type' => 'select_multiple herd_challenge', 
                'name' => 'select_herd_challenge',
                'label:English (en)' => 'What is the **biggest challenge** in obtaining feed?',
                'label:Arabic (ar)' => 'ما هي التحديات التي تواجههك بجلب طعام الحيوانات',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => false,
            ],
            [
                'type' => 'text', 
                'name' => 'other_challenges',
                'label:English (en)' => 'Please specify other challenges',
                'label:Arabic (ar)' => 'اذكر التحديات الاخرى',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => 'selected(${select_herd_challenge}, "other")',
            ],
            [
                'type' => 'text', 
                'name' => 'halavit',
                'label:English (en)' => 'How much Halavit formula do you use/buy? For how many months a year?',
                'label:Arabic (ar)' => 'كم كمية الحالافيت التي تستخدمها وكم شهر بالسنة',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => false,
                'appearance' => 'long-text'
            ],
            [
                'type' => 'select_multiple herd_diseases', 
                'name' => 'select_herd_diseases',
                'label:English (en)' => 'What diseases commonly affect your sheep?',
                'label:Arabic (ar)' => 'ما هي الأمراض التي تتعرض لها أغنامك؟',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => false,
            ],
            [
                'type' => 'text', 
                'name' => 'other_diseases',
                'label:English (en)' => 'Please specify other diseases',
                'label:Arabic (ar)' => 'اذكر الأمراض الاخرى',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => 'selected(${select_herd_diseases}, "other")',
                'appearance' => 'long-text'
            ],
            [
                'type' => 'select_multiple veterinary_services', 
                'name' => 'select_veterinary_services',
                'label:English (en)' => 'Do you have access to veterinary services?',
                'label:Arabic (ar)' => 'هل تستطيع الوصول الى خدمات البيطرة',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => false,
            ],
            [
                'type' => 'select_multiple water_sources', 
                'name' => 'select_water_sources',
                'label:English (en)' => 'What is your primary water source for livestock?',
                'label:Arabic (ar)' => 'ما هي مصادر الماء',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => false,
            ],
            [
                'type' => 'select_multiple sell_livestock', 
                'name' => 'select_sell_livestock',
                'label:English (en)' => 'Where do you currently sell your livestock?',
                'label:Arabic (ar)' => 'اين تبيع الحيوانات؟',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => false,
            ],
            [
                'type' => 'text', 
                'name' => 'livestock_local_markets',
                'label:English (en)' => 'Where is your local market?',
                'label:Arabic (ar)' => 'أين السوق الحلي؟',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'no',
                'relevant' => 'selected(${select_sell_livestock}, "local_markets")',
                'appearance' => 'long-text'
            ],
            [
                'type' => 'text', 
                'name' => 'livestock_customers',
                'label:English (en)' => 'How many such clients/customers do you have?',
                'label:Arabic (ar)' => 'كم عدد العملاء الذين تمتلكهم؟',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'no',
                'relevant' =>  'selected(${select_sell_livestock}, "direct_clients")',
                'appearance' => 'long-text'
            ],
            [
                'type' => 'text', 
                'name' => 'other_sell_livestock',
                'label:English (en)' => 'Please specify other market',
                'label:Arabic (ar)' => 'اذكر المصادر الاخرى',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => 'selected(${select_sell_livestock}, "other")',
                'appearance' => 'long-text'
            ],
            [
                'type' => 'select_multiple sell_dairy_products', 
                'name' => 'select_sell_dairy_products',
                'label:English (en)' => 'Where do you currently sell your dairy products?',
                'label:Arabic (ar)' => 'اين تبيع منتجاتك؟',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => false,
            ],
            [
                'type' => 'text', 
                'name' => 'dairy_products_local_markets',
                'label:English (en)' => 'Where is your local market?',
                'label:Arabic (ar)' => 'أين السوق الحلي؟',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'no',
                'relevant' => 'selected(${select_sell_livestock}, "local_markets")',
                'appearance' => 'long-text'
            ],
            [
                'type' => 'text', 
                'name' => 'dairy_products_customers',
                'label:English (en)' => 'How many such clients/customers do you have?',
                'label:Arabic (ar)' => 'كم عدد العملاء الذين تمتلكهم؟',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'no',
                'relevant' =>  'selected(${select_sell_livestock}, "direct_clients")',
                'appearance' => 'long-text'
            ],
            [
                'type' => 'text', 
                'name' => 'other_sell_dairy_products',
                'label:English (en)' => 'Please specify other market',
                'label:Arabic (ar)' => 'اذكر المصادر الاخرى',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => 'selected(${select_sell_dairy_products}, "other")',
                'appearance' => 'long-text'
            ],
            [
                'type' => 'select_multiple market_challenges', 
                'name' => 'select_market_challenges',
                'label:English (en)' => 'What are the main challenges in bringing your products to the Market?',
                'label:Arabic (ar)' => 'ما هي التحديات التي تواجههك لبيع المنتجات بالسوق؟',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => false,
            ],
            [
                'type' => 'text', 
                'name' => 'other_market_challenges',
                'label:English (en)' => 'Please specify other challenges',
                'label:Arabic (ar)' => 'اذكر التحديات الاخرى',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => 'selected(${select_market_challenges}, "other")',
                'appearance' => 'long-text'
            ],
            [
                'type' => 'select_multiple herd_limitations', 
                'name' => 'select_herd_limitations',
                'label:English (en)' => 'How do grazing limitations affect your livestock?',
                'label:Arabic (ar)' => 'كيف تؤثر محددات الرعي على الماشية؟',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => false,
            ],
            [
                'type' => 'text', 
                'name' => 'other_herd_limitations',
                'label:English (en)' => 'Please specify other limitations',
                'label:Arabic (ar)' => 'اذكر المحددات الاخرى',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => 'selected(${select_herd_limitations}, "other")',
                'appearance' => 'long-text'
            ],
            [
                'type' => 'select_one soil_fertility', 
                'name' => 'select_soil_fertility',
                'label:English (en)' => 'Soil fertility',
                'label:Arabic (ar)' => 'خصوبة التربة',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => false,
            ],
            [
                'type' => 'select_one grow', 
                'name' => 'select_grow',
                'label:English (en)' => 'Do you grow anything?',
                'label:Arabic (ar)' => 'هل تزرع؟',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => false,
            ],
            [
                'type' => 'select_multiple growing_products', 
                'name' => 'select_growing_products',
                'label:English (en)' => 'What is your purpose of growing?',
                'label:Arabic (ar)' => 'الغرض من الزراعة؟',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => '${select_grow} = "Yes"',
            ],
            [
                'type' => 'text', 
                'name' => 'family_land',
                'label:English (en)' => 'How much land is used for this? (Family)',
                'label:Arabic (ar)' => 'كم مساحة الارض؟',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'no', 
                'relevant' => 'selected(${select_growing_products}, "family_consumption")',
                'appearance' => 'long-text'
            ],
            [
                'type' => 'text', 
                'name' => 'animal_land',
                'label:English (en)' => 'How much land is used for this? (Animal)',
                'label:Arabic (ar)' => 'كم مساحة الارض؟',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'no',
                'relevant' => 'selected(${select_growing_products}, "animal_feed")',
                'appearance' => 'long-text'
            ],
            [
                'type' => 'text', 
                'name' => 'tools',
                'label:English (en)' => 'Are there any electrical tools or technologies being used to improve livestock management?',
                'label:Arabic (ar)' => 'هل هناك أي أدوات أو تقنيات كهربائية يتم استخدامها لتحسين إدارة الثروة الحيوانية؟',
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