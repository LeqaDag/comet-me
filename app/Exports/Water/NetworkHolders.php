<?php

namespace App\Exports\Water;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents; 
use Maatwebsite\Excel\Events\AfterSheet;
use App\Models\WaterSystem;
use DB;  

class NetworkHolders implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize,
    WithStyles, WithEvents
{ 
    protected $id;

    function __construct($id) {

        $this->id = $id;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $community = WaterSystem::where('id', $this->id)
            ->where('community_id', '!=', NULL)
            ->first();

        if($community) {

            $data = DB::table('all_water_holders') 
                ->join('communities', 'all_water_holders.community_id', 'communities.id')
                ->join('regions', 'communities.region_id', 'regions.id')
                ->join('sub_regions', 'communities.sub_region_id', 'sub_regions.id')
                ->LeftJoin('public_structures', 'all_water_holders.public_structure_id', 
                    'public_structures.id')
                ->LeftJoin('households', 'all_water_holders.household_id', 'households.id')
                ->leftJoin('water_network_users', 'households.id', 'water_network_users.household_id')
                ->LeftJoin('all_water_holder_donors', 'all_water_holders.id', 
                    'all_water_holder_donors.all_water_holder_id')
                ->LeftJoin('donors', 'all_water_holder_donors.donor_id', 'donors.id')
                ->where('all_water_holders.is_archived', 0)
                ->where('communities.id', $community->community_id)
                ->select([ 
                    DB::raw('IFNULL(households.english_name, public_structures.english_name) 
                        as exported_value'),
                    'all_water_holders.is_main', 
                    'communities.english_name as community_name',
                    'regions.english_name as region', 'sub_regions.english_name as sub_region',
                    'households.number_of_male', 'households.number_of_female', 
                    'households.number_of_adults', 'households.number_of_children', 
                    'households.phone_number',
                    DB::raw('group_concat(donors.donor_name) as donors'),
                ])
                ->groupBy('all_water_holders.id'); 
        } else {

            $data = collect(); 
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
        return ["Water Holder", "Main Holder", "Community", "Region", 
            "Sub Region", "Number of male", "Number of Female", "Number of adults", 
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
        $sheet->setAutoFilter('A1:K1');

        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }

    public function title(): string
    {
        return 'Network Holders';
    }
}