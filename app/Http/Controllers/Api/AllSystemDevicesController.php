<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use DB;
use Route;

class AllSystemDevicesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {	
        $turbines = DB::table('energy_turbine_communities')
            ->join('communities', 'energy_turbine_communities.community_id', 'communities.id')
            ->select('energy_turbine_communities.name', 'communities.english_name as community')
            ->get(); 

        $energySystems = DB::table('energy_systems')
            ->leftJoin('communities', 'energy_systems.community_id', 'communities.id')
            ->where('energy_systems.is_archived', 0)
            ->select('energy_systems.name as energy_system', 'communities.english_name as community')
            ->get();

        $waterSystems =  DB::table('water_systems')
            ->leftJoin('water_system_types', 'water_systems.water_system_type_id', 'water_system_types.id')
            ->leftJoin('communities', 'water_systems.community_id', 'communities.id')
            ->select(
                'water_systems.name as water_system', 
                'water_system_types.type as water_type',
                'communities.english_name as community')
            ->get();

        $generators = DB::table('energy_generator_communities')
            ->join('communities', 'energy_generator_communities.community_id', 'communities.id')
            ->select('energy_generator_communities.name', 'communities.english_name as community')
            ->get(); 

        $users = DB::table('users')
            ->join('roles', 'users.role_id', 'roles.id')
            ->where("users.is_archived", 0)
            ->orderBy('users.name', 'ASC')
            ->select('users.name as employee_name', 'roles.name')
            ->distinct()
            ->get(); 


        return response()->json([
            'energy_turbines' => $turbines,
            'energy_generators' => $generators,
            'energy_systems' => $energySystems,
            'water_systems' => $waterSystems,
            'users' => $users,
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }
}