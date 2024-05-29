<?php

namespace App\Exports\Compound;

use App\Models\EnergyUser;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents; 
use DB;
use Carbon\Carbon;

class Compound implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, WithStyles
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
        $data = DB::table('compounds')
            ->join('communities', 'compounds.community_id', 'communities.id')
            ->join('regions', 'communities.region_id', 'regions.id')
            ->join('sub_regions', 'communities.sub_region_id', 'sub_regions.id')
            ->leftJoin('community_representatives', 'community_representatives.compound_id', 
                'compounds.id')
            ->leftJoin('households as representatives', 'community_representatives.household_id', 
                'representatives.id')
            ->leftJoin('community_roles', 'community_representatives.community_role_id', 
                'community_roles.id')
            
            ->leftJoin('community_products', 'community_products.compound_id', 'compounds.id')
            ->leftJoin('product_types', 'community_products.product_type_id', 'product_types.id')

            ->leftJoin('nearby_towns', 'nearby_towns.compound_id', 'compounds.id')
            ->leftJoin('towns', 'nearby_towns.town_id', 'towns.id')

            ->leftJoin('nearby_settlements', 'nearby_settlements.compound_id', 'compounds.id')
            ->leftJoin('settlements', 'nearby_settlements.settlement_id', 'settlements.id')

            ->where('compounds.is_archived', 0)
            ->select(
                'compounds.english_name as english_name', 
                'compounds.arabic_name as arabic_name', 'communities.english_name as community',
                'regions.english_name as name', 'sub_regions.english_name as subname',
                'compounds.number_of_household', 'compounds.number_of_people as number_of_people',
                DB::raw('group_concat(DISTINCT representatives.english_name) as representatives'),
                DB::raw('group_concat(DISTINCT representatives.phone_number) as phone_numbers'),
                DB::raw('group_concat(DISTINCT community_roles.role) as community_roles'),
                DB::raw('group_concat(DISTINCT product_types.name) as product_types'),
                DB::raw('group_concat(DISTINCT towns.english_name) as towns'),
                DB::raw('group_concat(DISTINCT settlements.english_name) as settlements'),
                'compounds.demolition', 'compounds.demolition_number', 
                'compounds.demolition_executed', 'compounds.last_demolition', 
                'compounds.lawyer', 'compounds.notes'
            )
            ->groupBy('compounds.id');

        if($this->request->community) {

            $data->where("communities.id", $this->request->community);
        }
        if($this->request->region) {

            $data->where("regions.id", $this->request->region);
        }
        if($this->request->compound) {

            $data->where("compounds.id", $this->request->compound);
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
        return ["English Name", "Arabic Name", "Community", "Region", "Sub Region",
            "Number of Households", "Number of People", "Community Representatives", 
            "Phone Numbers", "Role", "Product Types", "Nearby Towns", "Nearby Settlements", 
            "Demolition Orders", "# of Demolitions", "# of Execution Demolitions",
            "Last Demolition", "Lawyers", "Notes"];
    }


    public function title(): string
    {
        return 'All Compounds';
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
        $sheet->setAutoFilter('A1:S1');
        
        return [ 
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}