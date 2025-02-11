<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents; 
use Maatwebsite\Excel\Events\AfterSheet;
use DB; 

class WaterCommunityNetwork implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize,
    WithStyles, WithEvents
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
        $data = DB::table('all_water_holders') 
            ->join('communities', 'all_water_holders.community_id', 'communities.id')
            ->join('regions', 'communities.region_id', '=', 'regions.id')
            ->join('sub_regions', 'communities.sub_region_id', '=', 'sub_regions.id')
            ->LeftJoin('households', 'all_water_holders.household_id', 'households.id')
            ->join('water_network_users', 'households.id', 
                '=', 'water_network_users.household_id')
            ->select([
                'communities.english_name as community_name',
                'regions.english_name as region', 'sub_regions.english_name as sub_region',
                'communities.water_service_beginning_year',
                DB::raw('SUM(households.number_of_people) as number_of_people'),
                DB::raw('SUM(households.number_of_male) as number_of_male'),
                DB::raw('SUM(households.number_of_female) as number_of_female'),
                DB::raw('SUM(households.number_of_adults) as number_of_adults'),
                DB::raw('SUM(households.number_of_children) as number_of_children')
            ])
            ->groupBy('all_water_holders.community_id');

        if($this->request->community) {
            $data->where("communities.english_name", $this->request->community);
        } 
        if($this->request->h2o_installation_date_from) {
            $data->where("all_water_holders.installation_date", ">=", $this->request->h2o_installation_date_from);
        }
        if($this->request->h2o_installation_date) {
            $data->where("all_water_holders.installation_date", "<=", $this->request->h2o_installation_date);
        }

        return $data->get();
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function headings(): array
    {
        return ["Community", "Region", "Sub Region", "Service Beginning Year",
            "People", "Male", "Female", "Adults", "Children"];
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
              
                $event->sheet->getDelegate()->freezePane('A2');  
            },
        ];
    }

    /**
     * Styling
     *
     * @return response()
     */
    public function styles(Worksheet $sheet)
    {
        $sheet->setAutoFilter('A1:H1');

        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }

    public function title(): string
    {
        return 'Water-Network Communities';
    }
}