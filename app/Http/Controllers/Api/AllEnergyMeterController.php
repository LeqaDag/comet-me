<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use DB;
use Route;

class AllEnergyMeterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) 
    {	
        $households = DB::table('households')
            ->join('communities', 'households.community_id', 'communities.id')
            ->leftJoin('household_statuses', 'households.household_status_id', 'household_statuses.id')
            ->leftJoin('all_energy_meters', 'all_energy_meters.household_id', 'households.id')
            ->leftJoin('energy_systems', 'all_energy_meters.energy_system_id', 'energy_systems.id')
            ->leftJoin('energy_system_types', 'all_energy_meters.energy_system_type_id', 'energy_system_types.id')
            ->leftJoin('meter_cases', 'all_energy_meters.meter_case_id', 'meter_cases.id')
            ->leftJoin('internet_users as internet_household', 'households.id', 'internet_household.household_id')
            ->leftJoin('all_water_holders', 'all_water_holders.household_id', 'households.id')
            ->leftJoin('household_meters', 'household_meters.household_id', 'households.id')
            ->leftJoin('all_energy_meters as main_energy', 'main_energy.id', 'household_meters.energy_user_id')
            ->leftJoin('households as main_users', 'main_energy.household_id', 'main_users.id')
            ->where('households.is_archived', 0)
            ->select(
                'communities.english_name as english_community_name',
                'communities.arabic_name as arabic_community_name',
                'households.english_name as holder_name_english', 
                'households.arabic_name as holder_name_arabic', 
                'households.phone_number', 'household_statuses.status as energy_system_status',
                DB::raw('IFNULL(all_energy_meters.meter_number, main_energy.meter_number) 
                    as meter_number'),
                'energy_system_types.name as energy_type',
                'meter_cases.meter_case_name_english as meter_case',
                'all_energy_meters.is_main', 'all_energy_meters.is_archived',
                DB::raw("CASE WHEN all_water_holders.household_id IS NOT NULL THEN 'Served'
                    ELSE 'Not Served' END AS water_system_status"),
                'households.internet_system_status',
                DB::raw('IFNULL(internet_household.is_ppp, 0) as is_ppp'),
                DB::raw('IFNULL(internet_household.is_hotspot, 0) as is_hotspot'),
                'main_users.english_name as main_holder'
            )
            ->distinct()
            ->get();

        $publics = DB::table('public_structures')
            ->join('communities', 'public_structures.community_id', 'communities.id')
            ->leftJoin('all_energy_meters', 'all_energy_meters.public_structure_id', 'public_structures.id')
            ->leftJoin('energy_systems', 'all_energy_meters.energy_system_id', 'energy_systems.id')
            ->leftJoin('energy_system_types', 'all_energy_meters.energy_system_type_id', 'energy_system_types.id')
            ->leftJoin('meter_cases', 'all_energy_meters.meter_case_id', 'meter_cases.id')
            ->leftJoin('internet_users as internet_public', 'public_structures.id', 'internet_public.public_structure_id')
            ->leftJoin('all_water_holders', 'all_water_holders.public_structure_id', 'public_structures.id')
            ->leftJoin('household_meters', 'household_meters.public_structure_id', 'public_structures.id')
            ->leftJoin('all_energy_meters as main_energy', 'main_energy.id', 'household_meters.energy_user_id')
            ->leftJoin('households as main_users', 'main_energy.household_id', 'main_users.id')
            ->leftJoin('public_structures as main_public', 'main_energy.public_structure_id', 'main_public.id')
            ->where('public_structures.is_archived', 0)
            ->select(
                'communities.english_name as english_community_name',
                'communities.arabic_name as arabic_community_name',
                'public_structures.english_name as holder_name_english', 
                'public_structures.arabic_name as holder_name_arabic', 
                'public_structures.phone_number',
                DB::raw('IFNULL(all_energy_meters.meter_number, main_energy.meter_number) 
                    as meter_number'),
                'energy_system_types.name as energy_type',
                'meter_cases.meter_case_name_english as meter_case',
                'all_energy_meters.is_main', 'all_energy_meters.is_archived',
                DB::raw("CASE WHEN all_water_holders.public_structure_id IS NOT NULL THEN 'Served'
                    ELSE 'Not Served' END AS water_system_status"),
                DB::raw("CASE WHEN internet_public.public_structure_id IS NOT NULL THEN 'Served'
                    ELSE 'Not Served' END AS internet_system_status"),
                DB::raw('IFNULL(internet_public.is_ppp, 0) as is_ppp'),
                DB::raw('IFNULL(internet_public.is_hotspot, 0) as is_hotspot'),
                DB::raw('IFNULL(main_users.english_name, main_public.english_name) 
                    as main_holder'),
            )
            ->distinct()
            ->get();

        $data = $households->merge($publics);

        return response()->json($data, 200, [], JSON_UNESCAPED_UNICODE);
    }
}