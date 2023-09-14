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

class WaterUserHolder implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize,
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
            ->LeftJoin('public_structures', 'all_water_holders.public_structure_id', 
                'public_structures.id')
            ->LeftJoin('households', 'all_water_holders.household_id', 'households.id')
            ->LeftJoin('h2o_users', 'h2o_users.household_id', 'households.id')
            ->LeftJoin('grid_users', 'h2o_users.household_id', '=', 'grid_users.household_id')
            ->leftJoin('water_network_users', 'households.id', 
                '=', 'water_network_users.household_id')
            ->LeftJoin('h2o_statuses', 'h2o_users.h2o_status_id', '=', 'h2o_statuses.id')
            ->LeftJoin('all_water_holder_donors', 'all_water_holders.id', 
                '=', 'all_water_holder_donors.all_water_holder_id')
            ->LeftJoin('donors', 'all_water_holder_donors.donor_id', '=', 'donors.id')
            ->where('all_water_holders.is_archived', 0)
            ->select([
                'households.english_name as english_name', 
                'public_structures.english_name as public',
                'all_water_holders.is_main', 
                'communities.english_name as community_name',
                'regions.english_name as region', 'sub_regions.english_name as sub_region',
                'h2o_statuses.status', 'h2o_users.installation_year',  
                'h2o_users.h2o_installation_date',
                'h2o_users.number_of_h20', 'h2o_users.number_of_bsf', 
                'grid_users.grid_integration_large', 
                'grid_users.large_date', 'grid_users.grid_integration_small', 
                'grid_users.small_date',
                'households.number_of_male', 'households.number_of_female', 
                'households.number_of_adults', 'households.number_of_children', 
                'households.phone_number',
                DB::raw('group_concat(donors.donor_name) as donors'),
            ])
            ->groupBy('all_water_holders.id');

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
        return ["Water System Holder", "Public Structure", "Main Holder", "Community", "Region", 
            "Sub Region", "H2O Status", "H2O Installation Year", 
            "Installation date (H2O)", "H2O systems",  "Number of BSF", 
            "Grid Integration Large", "Installation date (Grid Large)", 
            "Grid Integration Small", "Installation date (Grid Small)", 
            "Number of male", "Number of Female", "Number of adults", 
            "Number of children", "Phone number", "Donors"];
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
        $sheet->setAutoFilter('A1:AA1');

        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }

    public function title(): string
    {
        return 'All Water Holders';
    }
}