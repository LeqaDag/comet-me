<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\Household;
use App\Models\AllEnergyMeter;
use App\Models\InternetUser;
use App\Models\PublicStructure;
use Auth;
use DB;
use Route;

class AllActionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {	
        $energyActions =  DB::table('energy_maintenance_actions') 
            ->join('energy_maintenance_issues', 'energy_maintenance_actions.energy_maintenance_issue_id', 
                'energy_maintenance_issues.id')
            ->join('energy_maintenance_issue_types', 'energy_maintenance_actions.energy_maintenance_issue_type_id', 
                'energy_maintenance_issue_types.id')
            ->select(
                'energy_maintenance_actions.id as energy_action_id',
                'energy_maintenance_actions.english_name as energy_english',
                'energy_maintenance_actions.arabic_name as energy_arabic',
                'energy_maintenance_issues.english_name as energy_issue_english',
                'energy_maintenance_issues.arabic_name as energy_issue_arabic',
                'energy_maintenance_issue_types.name as energy_type'
            )
            ->get();

        $refrigeratorActions = DB::table('maintenance_refrigerator_actions') 
            ->select(
                'maintenance_refrigerator_actions.id as refrigerator_action_id',
                'maintenance_refrigerator_actions.maintenance_action_refrigerator_english as refrigerator_english',
                'maintenance_refrigerator_actions.maintenance_action_refrigerator as refrigerator_arabic'
            )
            ->get();

        $waterActions = DB::table('maintenance_h2o_actions') 
            ->select(
                'maintenance_h2o_actions.id as water_action_id',
                'maintenance_h2o_actions.maintenance_action_h2o as water_arabic',
                'maintenance_h2o_actions.maintenance_action_h2o_english as water_english'
            )
            ->get();

        $internetActions = DB::table('internet_actions') 
            ->join('internet_issues', 'internet_actions.internet_issue_id', 
                'internet_issues.id')
            ->select(
                'internet_actions.id as internet_action_id',
                'internet_actions.english_name as internet_english',
                'internet_actions.arabic_name as internet_arabic',
                'internet_issues.english_name as internet_issue_english',
                'internet_issues.arabic_name as internet_issue_arabic'
            )
            ->get();

        return response()->json([
            'energy_actions' => $energyActions,
            'refrigerator_actions' => $refrigeratorActions,
            'water_actions' => $waterActions,
            'internet_actions' => $internetActions,
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }
}