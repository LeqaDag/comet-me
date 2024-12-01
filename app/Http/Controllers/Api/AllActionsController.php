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
use Illuminate\Support\Facades\Cache;
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
        // $energyActions =  DB::table('energy_maintenance_actions') 
        //     ->join('energy_maintenance_issues', 'energy_maintenance_actions.energy_maintenance_issue_id', 
        //         'energy_maintenance_issues.id')
        //     ->join('energy_maintenance_issue_types', 'energy_maintenance_actions.energy_maintenance_issue_type_id', 
        //         'energy_maintenance_issue_types.id')
        //     ->select(
        //         'energy_maintenance_actions.id as energy_action_id',
        //         'energy_maintenance_actions.english_name as energy_english',
        //         'energy_maintenance_actions.arabic_name as energy_arabic',
        //         'energy_maintenance_issues.english_name as energy_issue_english',
        //         'energy_maintenance_issues.arabic_name as energy_issue_arabic',
        //         'energy_maintenance_issue_types.name as energy_type'
        //     )
        //     ->get();

        // $refrigeratorActions = DB::table('maintenance_refrigerator_actions') 
        //     ->select(
        //         'maintenance_refrigerator_actions.id as refrigerator_action_id',
        //         'maintenance_refrigerator_actions.maintenance_action_refrigerator_english as refrigerator_english',
        //         'maintenance_refrigerator_actions.maintenance_action_refrigerator as refrigerator_arabic'
        //     )
        //     ->get();

        // $waterActions = DB::table('maintenance_h2o_actions') 
        //     ->select(
        //         'maintenance_h2o_actions.id as water_action_id',
        //         'maintenance_h2o_actions.maintenance_action_h2o as water_arabic',
        //         'maintenance_h2o_actions.maintenance_action_h2o_english as water_english'
        //     )
        //     ->get();

        // $internetActions = DB::table('internet_actions') 
        //     ->join('internet_issues', 'internet_actions.internet_issue_id', 
        //         'internet_issues.id')
        //     ->select(
        //         'internet_actions.id as internet_action_id',
        //         'internet_actions.english_name as internet_english',
        //         'internet_actions.arabic_name as internet_arabic',
        //         'internet_issues.english_name as internet_issue_english',
        //         'internet_issues.arabic_name as internet_issue_arabic'
        //     )
        //     ->get();

        // return response()->json([
        //     'energy_actions' => $energyActions,
        //     'refrigerator_actions' => $refrigeratorActions,
        //     'water_actions' => $waterActions,
        //     'internet_actions' => $internetActions,
        // ], 200, [], JSON_UNESCAPED_UNICODE);

        $energyActions = Cache::remember('energy_issues', 3600, function () {
            return DB::table('energy_issues')
                ->join('energy_actions', 'energy_issues.energy_action_id', 'energy_actions.id')
                ->join('action_categories', 'energy_actions.action_category_id', 'action_categories.id')
                ->where('energy_issues.is_archived', 0)
                ->where('energy_actions.is_archived', 0)
                ->where('action_categories.is_archived', 0)
                ->select(
                    'energy_issues.comet_id',
                    'energy_issues.arabic_name as energy_issue_arabic',
                    'energy_actions.arabic_name as energy_action_arabic',
                    'action_categories.arabic_name as category_arabic',
                )
                ->distinct()
                ->get();
            }
        );

        $refrigeratorActions = Cache::remember('refrigerator_issues', 3600, function () {
            return DB::table('refrigerator_issues')
                ->join('refrigerator_actions', 'refrigerator_issues.refrigerator_action_id', 'refrigerator_actions.id')
                ->join('action_categories', 'refrigerator_actions.action_category_id', 'action_categories.id')
                ->where('refrigerator_issues.is_archived', 0)
                ->where('refrigerator_actions.is_archived', 0)
                ->where('action_categories.is_archived', 0)
                ->select(
                    'refrigerator_issues.comet_id',
                    'refrigerator_issues.arabic_name as refrigerator_issue_arabic',
                    'refrigerator_actions.arabic_name as refrigerator_action_arabic',
                    'action_categories.arabic_name as category_arabic',
                )
                ->distinct()
                ->get();
            }
        );

        $waterActions = Cache::remember('water_issues', 3600, function () {
            return DB::table('water_issues')
                ->join('water_actions', 'water_issues.water_action_id', 'water_actions.id')
                ->join('action_categories', 'water_actions.action_category_id', 'action_categories.id')
                ->where('water_issues.is_archived', 0)
                ->where('water_actions.is_archived', 0)
                ->where('action_categories.is_archived', 0)
                ->select(
                    'water_issues.comet_id',
                    'water_issues.arabic_name as water_issue_arabic',
                    'water_actions.arabic_name as water_action_arabic',
                    'action_categories.arabic_name as category_arabic',
                )
                ->distinct()
                ->get();
            }
        );

        $internetActions = Cache::remember('internet_issues', 3600, function () {
            return DB::table('internet_issues')
                ->join('internet_actions', 'internet_issues.internet_action_id', 'internet_actions.id')
                ->join('action_categories', 'internet_actions.action_category_id', 'action_categories.id')
                ->where('internet_issues.is_archived', 0)
                ->where('internet_actions.is_archived', 0)
                ->where('action_categories.is_archived', 0)
                ->select(
                    'internet_issues.comet_id',
                    'internet_issues.arabic_name as internet_issue_arabic',
                    'internet_actions.arabic_name as internet_action_arabic',
                    'action_categories.arabic_name as category_arabic',
                )
                ->distinct()
                ->get();
            }
        );

        $data = collect([$energyActions, $refrigeratorActions, $waterActions, $internetActions])->flatten();

        return response()->json([
            'energy' => $energyActions,
            'refrigerators' => $refrigeratorActions,
            'water' => $waterActions,
            'internet' => $internetActions
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }
}