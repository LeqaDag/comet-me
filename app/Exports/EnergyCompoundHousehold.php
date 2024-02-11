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
            ->join('community_statuses', 'communities.community_status_id', 
                'community_statuses.id')
            ->join('compound_households', 'compound_households.compound_id', 'compounds.id')
            ->join('households', 'compound_households.household_id', 'households.id')
            ->leftJoin('energy_system_types', 'households.energy_system_type_id', 
                'energy_system_types.id')
            ->where('communities.is_archived', 0)
            ->where('communities.community_status_id', 1)
            ->orWhere('communities.community_status_id', 2)
            ->select(
                'households.english_name as household',
                'communities.english_name as community_name', 
                'regions.english_name as region', 'sub_regions.english_name as sub_region',
                'energy_system_types.name', 'households.number_of_male', 
                'households.number_of_female', 'households.number_of_adults', 
                'households.number_of_children', 'households.phone_number'
            );

        $queryCommunities =  DB::table('communities')
            ->join('regions', 'communities.region_id', 'regions.id')
            ->join('sub_regions', 'communities.sub_region_id', 'sub_regions.id')
            ->join('community_statuses', 'communities.community_status_id', 
                'community_statuses.id')
            ->leftJoin('households', 'households.community_id','communities.id')
            ->leftJoin('energy_system_types', 'energy_system_types.id',
                'households.energy_system_type_id')
            ->where('communities.is_archived', 0)
            ->where('communities.community_status_id', 1)
            ->orWhere('communities.community_status_id', 2)
             ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('compounds')
                    ->whereRaw('compounds.community_id = communities.id');
            })
            ->select(
                'households.english_name as household',
                'communities.english_name as community_name', 
                'regions.english_name as region', 'sub_regions.english_name as sub_region',
                'energy_system_types.name', 'households.number_of_male', 
                'households.number_of_female', 'households.number_of_adults', 
                'households.number_of_children', 'households.phone_number'
            );

        $communitiesCollection = collect($queryCommunities->get());
        $compoundsCollection = collect($queryCompounds->get());

        $query =  DB::table('households')
            ->join('household_statuses', 'households.household_status_id', 
                'household_statuses.id')
            ->join('communities', 'communities.id', 'households.community_id')
            ->leftJoin('compound_households', 'compound_households.household_id', 'households.id')
            ->leftJoin('compounds', 'compound_households.compound_id', 'compounds.id')
            ->join('community_statuses', 'communities.community_status_id', 
                'community_statuses.id')
            ->leftJoin('energy_system_types', 'energy_system_types.id',
                'households.energy_system_type_id')
            ->where('communities.is_archived', 0)
            ->where('communities.community_status_id', 1)
            ->orWhere('communities.community_status_id', 2)
            ->select(
                'households.english_name as household',
                'communities.english_name as community_name',
                DB::raw("CASE 
                    WHEN households.household_status_id = 4 THEN 'DC Completed' 
                    WHEN households.household_status_id = 3 THEN 'AC Completed' 
                    ELSE household_statuses.status 
                END as status"),
                'compounds.english_name as compound_name',
                'energy_system_types.name', 'households.number_of_male', 
                'households.number_of_female', 'households.number_of_adults', 
                'households.number_of_children', 'households.phone_number'
            );

        return $query->get();
    } 

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function headings(): array
    {
        return ["Household", "Community", "Status", "Compound", "System Type",  
            "Number of male", "Number of Female", "Number of adults", 
            "Number of children", "Phone number"];
    }

    public function title(): string
    {
        return 'Households - New Communities';
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
        $sheet->setAutoFilter('A1:J1');

        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}