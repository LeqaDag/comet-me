<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use DB;

class SubCommunityHouseholdExport implements FromCollection, WithHeadings, WithTitle
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
        $data = DB::table('sub_community_households')
            ->join('communities', 'sub_community_households.community_id', 
                '=', 'communities.id')
            ->join('regions', 'communities.region_id', '=', 'regions.id')
            ->join('sub_regions', 'communities.sub_region_id', '=', 'sub_regions.id')
            ->join('community_statuses', 'communities.community_status_id', 
                '=', 'community_statuses.id')
            ->join('households', 'sub_community_households.household_id', 
                '=', 'households.id')
            ->join('sub_communities', 'sub_community_households.sub_community_id', 
                '=', 'sub_communities.id')
            ->leftJoin('household_statuses', 'households.household_status_id', 
                '=', 'household_statuses.id')
            ->leftJoin('professions', 'households.profession_id', 
                '=', 'professions.id')
            ->where('communities.is_archived', 0)
            ->select('households.english_name as household',
                'communities.english_name as community_english_name',
                'sub_communities.english_name as english_name', 
                'sub_communities.arabic_name as arabic_name', 
                'regions.english_name as region', 'sub_regions.english_name as sub_region',
                'households.phone_number', 'professions.profession_name', 
                'number_of_male', 'number_of_female', 'number_of_children', 'school_students',
                'household_statuses.status', 'water_system_status', 'internet_system_status');

        if($this->request->community) {

            $data->where("communities.id", $this->request->community);
        }
        if($this->request->region) {

            $data->where("regions.id", $this->request->region);
        }
        if($this->request->system_type) {

            $data->leftJoin('energy_users', function ($join) {
                    $join->on('energy_users.id', '=', 
                    DB::raw('(SELECT id FROM energy_users WHERE energy_users.community_id = communities.id LIMIT 1)'));
                })
                ->where("energy_users.energy_system_type_id", $this->request->system_type);
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
        return ["Household", "Community", "Sub Community English Name", "Sub Community Arabic Name", 
            "Region", "Sub Region", "Phone Number", "Profession", "# of Male", "# of Female", 
            "# of Children", "# of School students", "Energy System Status", "Water System Status", 
            "Internet System Status"];
    }

    public function title(): string
    {
        return 'Households - Sub Communities';
    }
}