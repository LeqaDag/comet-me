<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents; 
use Maatwebsite\Excel\Events\AfterSheet;
use DB;

class EnergyCompoundHousehold implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, 
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
        $queryCompounds = DB::table('compounds')
            ->join('communities', 'communities.id', 'compounds.community_id')
            ->join('community_statuses', 'communities.community_status_id', 
                'community_statuses.id')
            ->join('regions', 'communities.region_id', 'regions.id')
            ->join('sub_regions', 'communities.sub_region_id', 'sub_regions.id')
            ->leftJoin('compound_households', 'compound_households.compound_id', 'compounds.id')
            ->join('households', 'compound_households.household_id', 'households.id')
            ->leftJoin('household_statuses', 'households.household_status_id', 
                'household_statuses.id')
            ->leftJoin('energy_system_types', 'households.energy_system_type_id', 'energy_system_types.id')
            ->leftJoin('all_energy_meters', 'all_energy_meters.household_id', 'households.id')
            ->leftJoin('grid_community_compounds', 'compounds.id',
                'grid_community_compounds.compound_id')
            ->leftJoin('community_donors', 'community_donors.compound_id', 'compounds.id')
            ->leftJoin('donors', 'community_donors.donor_id', 'donors.id')
            ->where('communities.is_archived', 0)
            ->where('communities.energy_system_cycle_id', '!=', null)
            ->where('households.energy_system_cycle_id', '!=', null)
            ->select(
                'households.english_name as household',
                'household_statuses.status as status',
                'communities.english_name as community_name',
                'community_statuses.name as community_status',
                'compounds.english_name as compound_name',
                'energy_system_types.name', 
                DB::raw('CASE WHEN households.number_of_male IS NULL 
                        OR households.number_of_female IS NULL 
                        OR households.number_of_adults IS NULL 
                        OR households.number_of_children IS NULL 
                    THEN "Missing Details" 
                    ELSE "Complete" 
                    END as details_status'),
                'households.number_of_male', 
                'households.number_of_female', 'households.number_of_adults', 
                'households.number_of_children', 
                DB::raw('CASE 
                    WHEN (households.number_of_male IS NOT NULL AND households.number_of_female IS NOT NULL 
                        AND households.number_of_adults IS NOT NULL AND households.number_of_children IS NOT NULL 
                        AND (households.number_of_adults + households.number_of_children) <> (households.number_of_male + households.number_of_female))
                    THEN "Discrepancy" 
                    ELSE "No Discrepancy" 
                    END as discrepancies_status'),
                'households.phone_number',
                DB::raw('group_concat(DISTINCT CASE WHEN community_donors.is_archived = 0 THEN donors.donor_name END) as donors')
            ) 
            ->groupBy('households.english_name');
 
        $queryCommunities =  DB::table('communities')
            ->join('regions', 'communities.region_id', 'regions.id')
            ->join('sub_regions', 'communities.sub_region_id', 'sub_regions.id')
            ->join('community_statuses', 'communities.community_status_id', 
                'community_statuses.id')
            ->join('households', 'households.community_id','communities.id')
            ->leftJoin('household_statuses', 'households.household_status_id', 
                'household_statuses.id')
            ->leftJoin('energy_system_types', 'energy_system_types.id',
                'households.energy_system_type_id')
            ->leftJoin('community_donors', 'community_donors.community_id', 'communities.id')
            ->leftJoin('donors', 'community_donors.donor_id', 'donors.id')
            ->where('communities.is_archived', 0)
            ->whereNotNull('communities.energy_system_cycle_id')
            //->whereNotNull('households.energy_system_cycle_id')
             ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('compounds')
                    ->whereRaw('compounds.community_id = communities.id');
            })
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('displaced_households')
                    ->whereRaw('displaced_households.household_id = households.id');
            })
            ->select(
                'households.english_name as household',
                'household_statuses.status as status',
                'communities.english_name as community_name',
                'community_statuses.name as community_status',
                DB::raw('" " as space'),
                'energy_system_types.name', 
                DB::raw('CASE WHEN households.number_of_male IS NULL 
                        OR households.number_of_female IS NULL 
                        OR households.number_of_adults IS NULL 
                        OR households.number_of_children IS NULL 
                    THEN "Missing Details" 
                    ELSE "Complete" 
                    END as details_status'),
                'households.number_of_male', 
                'households.number_of_female', 'households.number_of_adults', 
                'households.number_of_children', 
                DB::raw('CASE 
                    WHEN (households.number_of_male IS NOT NULL AND households.number_of_female IS NOT NULL 
                        AND households.number_of_adults IS NOT NULL AND households.number_of_children IS NOT NULL 
                        AND (households.number_of_adults + households.number_of_children) <> (households.number_of_male + households.number_of_female))
                    THEN "Discrepancy" 
                    ELSE "No Discrepancy" 
                    END as discrepancies_status'),
                'households.phone_number',
                DB::raw('group_concat(DISTINCT CASE WHEN community_donors.is_archived = 0 THEN donors.donor_name END) as donors')
            )
            ->groupBy('households.english_name');

            
        if($this->request->energy_cycle_id) {

            $queryCommunities->where("communities.energy_system_cycle_id", $this->request->energy_cycle_id);
            $queryCompounds->where("communities.energy_system_cycle_id", $this->request->energy_cycle_id);
        }

        $communitiesCollection = collect($queryCommunities->get());
        $compoundsCollection = collect($queryCompounds->get());

        return $compoundsCollection->merge($communitiesCollection);
    } 

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function headings(): array
    {
        return ["Household", "Household Status", "Community", "Community Status", "Compound", "System Type",  
            "All Details", "Number of male", "Number of Female", "Number of adults", "Number of children", 
            "Discrepancy", "Phone number", "Donors"];
    }

    public function title(): string
    {
        return 'Households - New Community';
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
        $sheet->setAutoFilter('A1:M1');

        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}