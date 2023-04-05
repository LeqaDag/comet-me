<?php

namespace App\Exports;

use App\Models\EnergyUser;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use DB;

class EnergyUsersExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $energyUsers = DB::table('energy_users')
            ->join('energy_systems', 'energy_users.energy_system_id', '=', 'energy_systems.id')
            ->join('households', 'energy_users.household_id', '=', 'households.id')
            ->join('energy_donors', 'energy_users.household_id', '=', 'energy_donors.household_id')
            ->where('energy_donors.donor_id', '=', 1)
            ->where(function ($query) {
                $query->where("energy_users.energy_system_id", 62)
                      ->orWhere("energy_users.energy_system_id", 61);
            })
            ->join('professions', 'households.profession_id', '=', 'professions.id')
            ->join('communities', 'energy_users.community_id', '=', 'communities.id')
            ->join('regions', 'communities.region_id', '=', 'regions.id')
            ->join('sub_regions', 'communities.sub_region_id', '=', 'sub_regions.id')
            ->join('donors', 'energy_donors.donor_id', '=', 'donors.id')
            ->select('households.english_name as english_name', 'households.arabic_name as arabic_name',
                'communities.english_name as name', 'regions.english_name as region',
                'sub_regions.english_name as sub_region',
                'professions.profession_name as profession_name',
                'households.phone_number', 'households.number_of_male', 
                'households.number_of_female', 'households.number_of_adults', 
                'households.number_of_children', 'households.size_of_herd', 
                'energy_users.meter_number', 'energy_users.daily_limit', 
                'energy_users.installation_date', 'energy_systems.name as energy_system',
                'donors.donor_name')
            ->get();

        $users = EnergyUser::all(); 
        return $energyUsers;
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function headings(): array
    {
        return ["English Name", "Arabic Name", "Community", "Region", "Sub Region",
            "Profession", "Phone Number", "Number of Male", "Number of Female",
            "Number of Adults", "Number of Children", "Size of herds",
            "Meter Number", "Daily Limit", "Installation Date", "Energy System", 
            "Donor"];
    }
}