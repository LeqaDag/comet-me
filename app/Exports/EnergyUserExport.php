<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB;

class EnergyUserExport implements FromCollection, WithHeadings, ShouldAutoSize
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
        $query = DB::table('all_energy_meters')
            ->join('communities', 'all_energy_meters.community_id', '=', 'communities.id')
            ->join('regions', 'communities.region_id', '=', 'regions.id')
            ->join('sub_regions', 'communities.sub_region_id', '=', 'sub_regions.id')
            ->leftJoin('households', 'all_energy_meters.household_id', '=', 'households.id')
            ->leftJoin('public_structures', 'all_energy_meters.public_structure_id', 
                '=', 'public_structures.id')
            ->join('energy_systems', 'all_energy_meters.energy_system_id', '=', 'energy_systems.id')
            ->join('energy_system_types', 'all_energy_meters.energy_system_type_id', '=', 'energy_system_types.id')
            ->join('meter_cases', 'all_energy_meters.meter_case_id', '=', 'meter_cases.id')
            ->leftJoin('vendors', 'all_energy_meters.vendor_username_id', '=', 'vendors.id')
            ->where('all_energy_meters.meter_active', 'Yes')
            ->where('all_energy_meters.is_archived', 0)
            ->select('households.english_name as english_name', 
                'public_structures.english_name as public_name', 
                'communities.english_name as community_name',
                'regions.english_name as region', 'sub_regions.english_name as sub_region',
                'all_energy_meters.meter_number', 'all_energy_meters.meter_active',
                'meter_cases.meter_case_name_english as meter_case',
                'energy_systems.name as energy_name', 
                'energy_system_types.name as energy_type_name',
                'all_energy_meters.daily_limit', 'all_energy_meters.installation_date',
                'vendors.english_name as vendor_name');

        if($this->request->community) {
            $query->where("communities.english_name", $this->request->community);
        } 
        if($this->request->donor) {
            $query->where("community_donors.donor_id", $this->request->donor);
        }
        if($this->request->system_type) {
            $query->where("energy_system_types.name", $this->request->system_type);
        }
        if($this->request->installation_date) {
            $query->where("all_energy_meters.installation_date", ">=", $this->request->installation_date);
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
        return ["Meter User", "Meter Public",  "Community", "Region", "Sub Region", 
            "Meter Number", "Meter Active", "Meter Case", "Energy System", 
            "Energy System Type", "Daily Limit", "Installation Date", "Vendor"];
    }
}