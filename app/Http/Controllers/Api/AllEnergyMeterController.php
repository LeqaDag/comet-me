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
        $data = DB::table('all_energy_meters')
            ->join('communities', 'all_energy_meters.community_id', 'communities.id')
            ->leftJoin('households', 'all_energy_meters.household_id', 'households.id')
            ->leftJoin('public_structures', 'all_energy_meters.public_structure_id', 'public_structures.id')
            ->join('energy_systems', 'all_energy_meters.energy_system_id', 'energy_systems.id')
            ->join('energy_system_types', 'all_energy_meters.energy_system_type_id', 'energy_system_types.id')
            ->join('meter_cases', 'all_energy_meters.meter_case_id', 'meter_cases.id')
            ->leftJoin('household_meters', 'household_meters.energy_user_id', 
                'all_energy_meters.id')
            ->leftJoin('households as shared_households', 'shared_households.id', 
                'household_meters.household_id')
            ->where('all_energy_meters.is_archived', 0)
            ->select(
                'communities.english_name as english_community_name',
                'communities.arabic_name as arabic_community_name',
                DB::raw('IFNULL(households.english_name, public_structures.english_name) 
                    as holder_name_english'),
                DB::raw('IFNULL(households.arabic_name, public_structures.arabic_name) 
                    as holder_name_arabic'),
                'households.phone_number', 'households.energy_system_status',
                'all_energy_meters.meter_number', 'energy_system_types.name as energy_type',
                'meter_cases.meter_case_name_english as meter_case', 'all_energy_meters.is_main',
                'households.water_system_status', 'households.internet_system_status')
            ->get(); 

        return response()->json($data, 200, [], JSON_UNESCAPED_UNICODE);
    }
}