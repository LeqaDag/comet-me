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
            ->join('regions', 'communities.region_id', 'regions.id')
            ->join('sub_regions', 'communities.sub_region_id', 'sub_regions.id')
            ->leftJoin('compound_households', 'compound_households.compound_id', 'compounds.id')
            ->join('households', 'compound_households.household_id', 'households.id')
            ->leftJoin('household_statuses', 'households.household_status_id', 
                'household_statuses.id')
            ->leftJoin('energy_system_types', 'households.energy_system_type_id', 'energy_system_types.id')
            ->leftJoin('all_energy_meters', 'all_energy_meters.household_id', 'households.id')
            ->leftJoin('meter_cases', 'all_energy_meters.meter_case_id', 'meter_cases.id')
            ->leftJoin('grid_community_compounds', 'compounds.id',
                'grid_community_compounds.compound_id')
            ->leftJoin('community_donors', 'community_donors.compound_id', 'compounds.id')
            ->leftJoin('donors', 'community_donors.donor_id', 'donors.id')
            ->where('communities.is_archived', 0)
            ->where('compounds.is_archived', 0)
            //->where('households.is_archived', 0)
            ->where('compound_households.is_archived', 0)
            //->where('all_energy_meters.is_archived', 0)
            ->whereNotNull('communities.energy_system_cycle_id')
            ->select(
                'households.english_name as household',
                'household_statuses.status as status',
                'communities.english_name as community_name',
                'compounds.english_name as compound_name',
                'energy_system_types.name', 
                'all_energy_meters.meter_number', 
                'meter_cases.meter_case_name_english', 'all_energy_meters.meter_active', 
                'all_energy_meters.installation_date', 'all_energy_meters.daily_limit',
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
 
        $queryCommunities = DB::table('communities')
            ->join('regions', 'communities.region_id', 'regions.id')
            ->join('sub_regions', 'communities.sub_region_id', 'sub_regions.id')
            ->join('community_statuses', 'communities.community_status_id', 
                'community_statuses.id')
            ->join('households', 'households.community_id','communities.id')
            ->leftJoin('household_statuses', 'households.household_status_id', 
                'household_statuses.id')
            ->leftJoin('all_energy_meters', 'all_energy_meters.household_id', 'households.id')
            ->leftJoin('meter_cases', 'all_energy_meters.meter_case_id', 'meter_cases.id')
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
                DB::raw('" " as space'),
                'energy_system_types.name', 
                'all_energy_meters.meter_number', 
                'meter_cases.meter_case_name_english', 'all_energy_meters.meter_active', 
                'all_energy_meters.installation_date', 'all_energy_meters.daily_limit',
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

            
        $queryPublics = DB::table('public_structures')
            ->join('communities', 'public_structures.community_id', 'communities.id')
            ->join('regions', 'communities.region_id', 'regions.id')
            ->join('sub_regions', 'communities.sub_region_id', 'sub_regions.id')                
            ->leftJoin('public_structure_statuses', 'public_structures.public_structure_status_id', 'public_structure_statuses.id')
            ->leftJoin('compounds', 'public_structures.compound_id', 'compounds.id')
            ->join('all_energy_meters', 'public_structures.id', 'all_energy_meters.public_structure_id')
            ->leftJoin('energy_system_types', 'all_energy_meters.energy_system_type_id', 'energy_system_types.id')
            ->leftJoin('meter_cases', 'all_energy_meters.meter_case_id', 'meter_cases.id')
            ->leftJoin('community_donors', 'community_donors.community_id', 'communities.id')
            ->leftJoin('donors', 'community_donors.donor_id', 'donors.id')
            ->where('communities.is_archived', 0)
            ->where('communities.energy_system_cycle_id', '!=', null)
            ->select(
                'public_structures.english_name as household',
                'public_structure_statuses.status as status',
                'communities.english_name as community_name',
                'compounds.english_name as compound_name',
                'energy_system_types.name', 
                'all_energy_meters.meter_number', 
                'meter_cases.meter_case_name_english', 'all_energy_meters.meter_active', 
                'all_energy_meters.installation_date', 'all_energy_meters.daily_limit',
                DB::raw('false as details_status'), 
                DB::raw('false as number_of_male'), 
                DB::raw('false as number_of_female'), 
                DB::raw('false as number_of_adults'), 
                DB::raw('false as number_of_children'), 
                DB::raw('false as discrepancies_status'), 
                'public_structures.phone_number',
                DB::raw('group_concat(DISTINCT CASE WHEN community_donors.is_archived = 0 THEN donors.donor_name END) as donors')
            ) 
            ->groupBy('public_structures.english_name');


        if($this->request->energy_cycle_id) {

            $queryCommunities->where("communities.energy_system_cycle_id", $this->request->energy_cycle_id);
            $queryCompounds->where("communities.energy_system_cycle_id", $this->request->energy_cycle_id);
            $queryPublics->where("communities.energy_system_cycle_id", $this->request->energy_cycle_id);
        }

        $communitiesCollection = collect($queryCommunities->get());
        $compoundsCollection = collect($queryCompounds->get());
        $publicsCollection = collect($queryPublics->get());

        return $compoundsCollection->merge($communitiesCollection)->merge($publicsCollection);
    } 

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function headings(): array
    {
        return ["Holder (Household/Public)", "Status", "Community", "Compound", "System Type",  
            "Meter Number", "Meter Case", "Meter Active", "Installation Date", "Daily Limit", "All Details", 
            "Number of male", "Number of Female", "Number of adults", "Number of children", "Discrepancy", 
            "Phone number", "Donors"];
    }

    public function title(): string
    {
        return '(Households Publics) - New Community';
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