<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use DB;

class FbsIncidentExport implements FromCollection, WithHeadings
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
        $query = DB::table('fbs_user_incidents')
            ->join('communities', 'fbs_user_incidents.community_id', '=', 'communities.id')
            ->join('regions', 'communities.region_id', '=', 'regions.id')
            ->join('sub_regions', 'communities.sub_region_id', '=', 'sub_regions.id')
            ->join('all_energy_meters', 'fbs_user_incidents.energy_user_id', '=', 'all_energy_meters.id')
            ->join('households', 'all_energy_meters.household_id', '=', 'households.id')
            ->join('incidents', 'fbs_user_incidents.incident_id', '=', 'incidents.id')
            ->join('incident_status_small_infrastructures', 
                'fbs_user_incidents.incident_status_small_infrastructure_id', 
                '=', 'incident_status_small_infrastructures.id')
            ->leftJoin('community_donors', 'community_donors.community_id', '=', 'communities.id')
            ->leftJoin('donors', 'community_donors.donor_id', 'donors.id')
            ->where('all_energy_meters.energy_system_type_id', 2)
            ->where('community_donors.service_id', 1)
            ->select('households.english_name as household_name',
                'communities.english_name as community_name', 
                'regions.english_name as region', 'sub_regions.english_name as sub_region',
                'incidents.english_name as incident', 
                'fbs_user_incidents.year', 'fbs_user_incidents.date', 
                'incident_status_small_infrastructures.name as fbs_status',
                'donors.donor_name');

        if($this->request->community) {

            $query->where("communities.english_name", $this->request->community);
        } 
        if($this->request->donor) {

            $query->where("community_donors.donor_id", $this->request->donor);
        }
        if($this->request->date) {

            $query->where("fbs_user_incidents.date", ">=", $this->request->date);
        }

        return $query->get();
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function headings(): array
    {
        return ["Energy User", "Community", "Region", "Sub Region", 
            "Incident", "Incident Year", "Incident Date", "Status", "Donor"];
    }
}