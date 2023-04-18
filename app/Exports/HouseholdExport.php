<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use DB;

class HouseholdExport implements FromCollection, WithHeadings
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
            ->join('communities', 'households.community_id', '=', 'communities.id')
            ->join('regions', 'communities.region_id', '=', 'regions.id')
            ->join('sub_regions', 'communities.sub_region_id', '=', 'sub_regions.id')
            ->join('household_statuses', 'households.household_status_id', 
                '=', 'household_statuses.id')
            ->join('professions', 'households.profession_id', 
                '=', 'professions.id')
            ->leftJoin('public_structures', 'communities.id', '=', 'public_structures.community_id')
            ->leftJoin('energy_users', function ($join) {
                $join->on('energy_users.id', '=', 
                DB::raw('(SELECT id FROM energy_users WHERE energy_users.community_id = communities.id LIMIT 1)'));
            })
            ->leftJoin('community_donors', 'community_donors.community_id', '=', 'communities.id')
            ->leftJoin('donors', 'community_donors.donor_id', 'donors.id')
            ->select('households.english_name as english_name', 
                'households.arabic_name as arabic_name', 
                'communities.english_name as community_name',
                'regions.english_name as region', 'sub_regions.english_name as sub_region',
                'households.phone_number', 'professions.profession_name', 
                'number_of_male', 'number_of_female', 'number_of_children', 'school_students',
                'household_statuses.status', 'water_system_status', 'internet_system_status');

        if($this->request->region) {

            $data->where("regions.id", $this->request->region);
        }
        if($this->request->system_type) {

            $data->where("energy_users.energy_system_type_id", $this->request->system_type);
        }
        if($this->request->donor) {

            $data->where("community_donors.donor_id", $this->request->donor);
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
            "Phone Number", "Profession", "# of Male", "# of Female", "# of Children",
            "# of School students", "Energy System Status", "Water System Status", 
            "Internet System Status"];
    }
}