<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use DB;

class CommunityExport implements FromCollection, WithHeadings
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
        $communityDonors = DB::table('community_donors')
            ->join('communities', 'community_donors.community_id', '=', 'communities.id')
            ->join('donors', 'community_donors.donor_id', 'donors.id')
            ->join('service_types', 'community_donors.service_id', 'service_types.id')
            ->select(
                DB::raw('communities.english_name as english_name'),
                DB::raw('count(*) as number')
                )
            ->groupBy('communities.english_name')
            ->get();
        
        //die($communityDonors);
        
        $data = DB::table('communities')
            ->join('regions', 'communities.region_id', '=', 'regions.id')
            ->join('sub_regions', 'communities.sub_region_id', '=', 'sub_regions.id')
            ->leftJoin('sub_sub_regions', 'communities.sub_sub_region_id', '=', 'sub_sub_regions.id')
            ->leftJoin('public_structures', 'communities.id', '=', 'public_structures.community_id')
            ->join('community_statuses', 'communities.community_status_id', 
                '=', 'community_statuses.id')
            // ->leftJoin('energy_users', function ($join) {
            //     $join->on('energy_users.id', '=', 
            //     DB::raw('(SELECT id FROM energy_users WHERE energy_users.community_id = communities.id LIMIT 1)'));
            // })
            // // ->leftJoin('energy_system_types', 'energy_users.energy_system_type_id', 
            // //     '=', 'energy_system_types.id')
            // ->leftJoin('community_donors', 'community_donors.community_id', '=', 'communities.id')
            // ->leftJoin('donors', 'community_donors.donor_id', 'donors.id')
            // ->leftJoin('service_types', 'community_donors.service_id', 'service_types.id')
            
            // ->leftJoin('community_donors', function ($join) {
            //     $join->on('community_donors.community_id', '=', 
            //     DB::raw('(SELECT donor_id FROM community_donors WHERE community_donors.community_id = communities.id)'));
            // })
           // ->leftJoin('community_donors', 'communities.id', 'community_donors.community_id')
            //->join('donors', 'community_donors.donor_id', 'donors.id')
            // ->join('service_types', 'community_donors.service_id', 'service_types.id')
            ->select('communities.english_name as english_name', 
                'communities.arabic_name as arabic_name',
                'regions.english_name as name', 'sub_regions.english_name as subname',
                'sub_sub_regions.english_name as sub_sub_name',
                'number_of_household', 'communities.number_of_people as number_of_people',
                'number_of_compound', 'community_statuses.name as status_name',
                'communities.energy_service', 'communities.energy_service_beginning_year',
                'communities.water_service', 'communities.water_service_beginning_year',
                'communities.internet_service', 'communities.internet_service_beginning_year', 
                );
 
       // dd($data->count());

        if($this->request->public) {
            $data->where("public_structures.public_structure_category_id1", $this->request->public)
                ->orWhere("public_structures.public_structure_category_id2", $this->request->public)
                ->orWhere("public_structures.public_structure_category_id3", $this->request->public);
        }
        if($this->request->system_type) {
            $data->where("energy_users.energy_system_type_id", $this->request->system_type);
        }
        if($this->request->donor) {
            $data->where("community_donors.donor_id", $this->request->donor);
        }
        if($this->request->region) {
            $data->where("communities.region_id", $this->request->region);
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
        return ["English Name", "Arabic Name", "Region", "Sub Region", "Sub Sub Region",
            "Number of Households", "Number of People",  "Number of Compounds", 
            "Status", "Energy Service", "Energy Service Year", "Energy System Type", "Water Service", 
            "Water Service Year", "Internet Service", "Internet Service Year",
            "Donor", "Service"];
    }
}