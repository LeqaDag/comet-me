<?php

namespace App\Exports\Household;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize; 
use DB; 

class DiscrepancyHousehold implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, WithStyles
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
        $data = DB::table('households')
            ->join('communities', 'households.community_id', 'communities.id')
            ->join('regions', 'communities.region_id', 'regions.id')
            ->join('sub_regions', 'communities.sub_region_id', 'sub_regions.id')
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
            ->havingRaw('households.number_of_male + households.number_of_female != 
                households.number_of_children + households.number_of_adults')
            ->select('households.english_name as english_name', 
                'households.arabic_name as arabic_name', 
                'communities.english_name as community_name',
                'regions.english_name as region', 'compounds.english_name as compound',
                'households.phone_number', 'professions.profession_name', 
                'number_of_male', 'number_of_female', 'number_of_children','number_of_adults', 
                'school_students', 'household_statuses.status', 
                'all_energy_meters.is_main', 'energy_system_types.name',
                'all_energy_meters.meter_number', 
                DB::raw('group_concat(DISTINCT energy_donor.donor_name) as meter_donor'),
                'energy_request_systems.date', 
                'water_system_status', 
                DB::raw('group_concat(DISTINCT water_donor.donor_name) as water_donor'),
                'internet_system_status',
                DB::raw('group_concat(DISTINCT internet_donor.donor_name) as internet_donor'),
            )
            ->groupBy('households.id');

        if($this->request->region) {

            $regionIds = $this->request->region;

            $data->where(function ($query) use ($regionIds) {
                foreach ($regionIds as $regionId) {
                    if (is_array($regionId)) {
                        $query->orWhereIn('households.id', function ($subQuery) use ($regionId) {
                            $subQuery->select('households.id')
                                ->from('regions')
                                ->whereIn('communities.region_id', $regionId);
                        });
                    } else {
                        $query->orWhereIn('households.id', function ($subQuery) use ($regionId) {
                            $subQuery->select('households.id')
                                ->from('regions')
                                ->where('communities.region_id', $regionId);
                        });
                    }
                }
            });
        }
        if($this->request->community) {

            $communityIds = $this->request->community;

            $data->where(function ($query) use ($communityIds) {
                foreach ($communityIds as $communityId) {
                    if (is_array($communityId)) {
                        $query->orWhereIn('households.id', function ($subQuery) use ($communityId) {
                            $subQuery->select('households.id')
                                ->from('communities')
                                ->whereIn('households.community_id', $communityId);
                        });
                    } else {
                        $query->orWhereIn('households.id', function ($subQuery) use ($communityId) {
                            $subQuery->select('households.id')
                                ->from('communities')
                                ->where('households.community_id', $communityId);
                        });
                    }
                }
            });
        }
        if($this->request->status) {

            $statusIds = $this->request->status;

            $data->where(function ($query) use ($statusIds) {
                foreach ($statusIds as $statusId) {
                    if (is_array($statusId)) {
                        $query->orWhereIn('households.id', function ($subQuery) use ($statusId) {
                            $subQuery->select('households.id')
                                ->from('households')
                                ->whereIn('households.household_status_id', $statusId);
                        });
                    } else {
                        $query->orWhereIn('households.id', function ($subQuery) use ($statusId) {
                            $subQuery->select('households.id')
                                ->from('households')
                                ->where('households.household_status_id', $statusId);
                        });
                    }
                }
            });
        }
        if($this->request->system_type) {

            $systemTypesIds = $this->request->system_type;

            $data->where(function ($query) use ($systemTypesIds) {
                foreach ($systemTypesIds as $systemTypesId) {
                    if (is_array($systemTypesId)) {
                        $query->orWhereIn('households.id', function ($subQuery) use ($systemTypesId) {
                            $subQuery->select('all_energy_meters.household_id')
                                ->from('all_energy_meters')
                                ->whereIn('all_energy_meters.energy_system_type_id', $systemTypesId);
                        });
                    } else {
                        $query->orWhereIn('households.id', function ($subQuery) use ($systemTypesId) {
                            $subQuery->select('all_energy_meters.household_id')
                                ->from('all_energy_meters')
                                ->where('all_energy_meters.energy_system_type_id', $systemTypesId);
                        });
                    }
                }
            });
        }
        if($this->request->donor) {

            $donorIds = $this->request->donor;

            $data->where(function ($query) use ($donorIds) {
                foreach ($donorIds as $donorId) {
                    if (is_array($donorId)) {
                        $query->orWhere(function ($subQuery) use ($donorId) {
                            $subQuery->whereIn('households.id', function ($joinQuery) use ($donorId) {
                                $joinQuery->select('all_energy_meters.household_id')
                                    ->from('all_energy_meter_donors')
                                    ->whereIn('all_energy_meter_donors.donor_id', $donorId)
                                    ->unionAll(
                                        // Similar union for water donors
                                        DB::table('all_water_holders')
                                        ->select('all_water_holders.household_id')
                                        ->from('all_water_holder_donors')
                                        ->whereIn('all_water_holder_donors.donor_id', $donorId)
                                    )
                                    ->unionAll(
                                        // Similar union for internet donors
                                        DB::table('internet_users')
                                        ->select('internet_users.household_id')
                                        ->from('internet_user_donors')
                                        ->whereIn('internet_user_donors.donor_id', $donorId)
                                    );
                            });
                        });
                    } else {
                        $query->orWhereIn('households.id', function ($subQuery) use ($donorId) {
                            $subQuery->select('all_energy_meters.household_id')
                                ->from('all_energy_meter_donors')
                                ->where('all_energy_meter_donors.donor_id', $donorId)
                                ->unionAll(
                                    // Similar union for water donors
                                    DB::table('all_water_holders')
                                    ->select('all_water_holders.household_id')
                                    ->from('all_water_holder_donors')
                                    ->where('all_water_holder_donors.donor_id', $donorId)
                                )
                                ->unionAll(
                                    // Similar union for internet donors
                                    DB::table('internet_users')
                                    ->select('internet_users.household_id')
                                    ->from('internet_user_donors')
                                    ->where('internet_user_donors.donor_id', $donorId)
                                );
                        });
                    }
                }
            });
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
        return ["English Name", "Arabic Name", "Community", "Region", "Compound", 
            "Phone Number", "Profession", "# of Male", "# of Female", "# of Children", "# of Adults",
            "# of School students", "Energy System Status", "Main User", 
            "Energy System Type", "Meter Number", "Energy Donors",
            "Requset Date", "Water System Status", "Water Donors",
            "Internet System Status", "Internet Donors"];
    }

    public function title(): string
    {
        return 'Discrepancy Households';
    }

    /**
     * Styling
     *
     * @return response()
     */
    public function styles(Worksheet $sheet)
    {
        $sheet->setAutoFilter('A1:V1');

        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}