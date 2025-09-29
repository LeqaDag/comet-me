<?php

namespace App\Exports\Household;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize; 
use DB; 

class NoChildrenListed implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, WithStyles
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
        $data =  DB::table('households')
            ->join('communities', 'households.community_id', 'communities.id')
            ->join('regions', 'communities.region_id', 'regions.id')
            ->join('sub_regions', 'communities.sub_region_id', 'sub_regions.id')
            ->leftJoin('energy_system_cycles', 'energy_system_cycles.id', 'communities.energy_system_cycle_id')
            ->leftJoin('household_statuses', 'households.household_status_id', 
                'household_statuses.id')
            ->leftJoin('professions', 'households.profession_id', 
                'professions.id')
            ->leftJoin('energy_request_systems', 'households.id', 
                'energy_request_systems.household_id')
            ->leftJoin('all_energy_meters', 'households.id', 
                'all_energy_meters.household_id')
            ->leftJoin('energy_system_types', 'energy_system_types.id', 
                'all_energy_meters.energy_system_type_id')
            ->leftJoin('all_energy_meter_donors', 'all_energy_meters.id', 
                'all_energy_meter_donors.all_energy_meter_id')
            ->leftJoin('donors as energy_donor', 'all_energy_meter_donors.donor_id', 'energy_donor.id')
            ->leftJoin('all_water_holders', 'households.id', 
                'all_water_holders.household_id')
            ->leftJoin('all_water_holder_donors', 'all_water_holders.id', 
                'all_water_holder_donors.all_water_holder_id')
            ->leftJoin('donors as water_donor', 'all_water_holder_donors.donor_id', 'water_donor.id')
            ->leftJoin('internet_users', 'households.id', 
                'internet_users.household_id')
            ->leftJoin('internet_user_donors', 'internet_users.id', 
                'internet_user_donors.internet_user_id')
            ->leftJoin('donors as internet_donor', 'internet_user_donors.donor_id', 'internet_donor.id')
            ->leftJoin('compound_households', 'households.id', 'compound_households.household_id')
            ->leftJoin('compounds', 'compound_households.compound_id', 'compounds.id')
            ->where('households.is_archived', 0) 
            ->where('internet_holder_young', 0) 
            ->where('households.number_of_adults', '!=', NULL)
            ->whereNull('households.number_of_children')
            ->orWhere('households.number_of_children', 0)
            ->where('households.is_surveyed', 'no')
            ->orWhereNull('households.is_surveyed')
            ->select(
                'households.english_name as english_name', 
                'communities.english_name as community_name',
                'regions.english_name as region', 'compounds.english_name as compound',
                'energy_system_cycles.name as cycle',
                'households.phone_number', 'professions.profession_name', 
                'number_of_male', 'number_of_female', 'number_of_children','number_of_adults', 
                'household_statuses.status', 
                'all_energy_meters.is_main', 'energy_system_types.name',
                'all_energy_meters.meter_number', 'all_energy_meters.installation_date',
                DB::raw('group_concat(DISTINCT energy_donor.donor_name) as meter_donor'),
                'energy_request_systems.date', 
                'water_system_status', 
                DB::raw('group_concat(DISTINCT water_donor.donor_name) as water_donor'),
                'internet_system_status',
                DB::raw('group_concat(DISTINCT internet_donor.donor_name) as internet_donor')
            )
            ->groupBy('households.id');
        
        return $data->get();
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function headings(): array
    {
        return ["English Name", "Community", "Region", "Compound", "Cycle Year",
            "Phone Number", "Profession", "# of Male", "# of Female", "# of Children", "# of Adults",
            "Energy System Status", "Main User", 
            "Energy System Type", "Meter Number", "Installation Date", "Energy Donors",
            "Requset Date", "Water System Status", "Water Donors",
            "Internet System Status", "Internet Donors"];
    }

    public function title(): string
    {
        return 'Households with no Children Listed';
    }

    /**
     * Styling
     *
     * @return response()
     */
    public function styles(Worksheet $sheet)
    {
        $sheet->setAutoFilter('A1:W1');

        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}