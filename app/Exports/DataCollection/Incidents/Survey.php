<?php

namespace App\Exports\DataCollection\Incidents;

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
                'choices' => false,
                'choice_filter' => '${select_sub_region} = sub_region',
                'calculation' => false,
                'required' => 'yes',
                'relevant' => false,
            ],
            [
                'type' => 'select_one service_type', 
                'name' => 'select_service_type',
                'label' => 'Select service',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => false,
            ],
            [
                'type' => 'select_one energy_incident_type', 
                'name' => 'select_energy_incident_type',
                'label' => 'Select energy type',
                'hint' => false,
                'choices' => false,
                'choice_filter' => '${select_service_type} = Energy',
                'calculation' => false,
                'required' => 'yes',
                'relevant' => false,
            ],
            [
                'type' => 'select_one mg_system', 
                'name' => 'select_mg_system',
                'label' => 'Select MG system',
                'hint' => false,
                'choices' => 'mg_system',
                'choice_filter' => '${select_community} = community',
                'calculation' => false,
                'required' => 'no',
                'relevant' => false,
            ],
            [
                'type' => 'select_one water_system', 
                'name' => 'select_water_system',
                'label' => 'Select water system',
                'hint' => false,
                'choices' => 'water_system',
                'choice_filter' => '${select_community} = community',
                'calculation' => false,
                'required' => 'no',
                'relevant' => false,
            ],

            [
                'type' => 'select_one water_incident_type', 
                'name' => 'select_water_incident_type',
                'label' => 'Select water type',
                'hint' => false,
                'choices' => false,
                'choice_filter' => '${select_service_type} = Water',
                'calculation' => false,
                'required' => 'yes',
                'relevant' => false,
            ],
            [
                'type' => 'select_one internet_incident_type', 
                'name' => 'select_internet_incident_type',
                'label' => 'Select internet incident type',
                'hint' => false,
                'choices' => false,
                'choice_filter' => '${select_service_type} = Internet',
                'calculation' => false,
                'required' => 'yes',
                'relevant' => false,
            ],
            

            // Incident Details Group
            [
                'type' => 'begin group', 
                'name' => 'incident_details',
                'label' => 'Incident Details',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => false,
                'relevant' => false,
            ],
            [
                'type' => 'date', 
                'name' => 'incident_date',
                'label' => 'Pick the incident date',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => false,
            ],
            [
                'type' => 'select_one incident_type', 
                'name' => 'select_incident_type',
                'label' => 'Select incident type',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => false,
            ],
            [
                'type' => 'date', 
                'name' => 'response_date',
                'label' => 'Pick the response date',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => false
            ],
            [
                'type' => 'select_multiple incident_status', 
                'name' => 'select_incident_status',
                'label' => 'Select incident status',
                'hint' => false,
                'choices' => false,
                'choice_filter' => false,
                'calculation' => false,
                'required' => 'yes',
                'relevant' => false
            ],
            [
                'type' => 'select_multiple equipments_damaged', 
                'name' => 'select_equipments_damaged',
                'label' => 'Select equipments damaged',
                'hint' => false,
                'choices' => false,
                'choice_filter' => '${select_service_type} = service',
                'calculation' => false,
                'required' => 'yes',
                'relevant' => false
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
                'relevant' => false
            ],
            // Incident Details Group

			
            


            [
                'type' => 'text', 
                'name' => 'notes',
                'label' => 'Enter notes',
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
        return ['type', 'name', 'label', 'hint', 'choices', 'choice_filter', 'calculation', 'required', 
            'relevant', 'appearance'];
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