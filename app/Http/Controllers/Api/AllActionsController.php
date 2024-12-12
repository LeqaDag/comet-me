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
        $energyActions = DB::table('energy_issues')
            ->join('energy_actions', 'energy_issues.energy_action_id', 'energy_actions.id')
            ->join('action_categories', 'energy_actions.action_category_id', 'action_categories.id')
            ->where('energy_issues.is_archived', 0)
            ->where('energy_actions.is_archived', 0)
            ->where('action_categories.is_archived', 0)
            ->select(
                'energy_issues.comet_id',
                DB::raw("CONCAT(action_categories.arabic_name, ' - ', energy_actions.arabic_name, ' - ', energy_issues.arabic_name) 
                    as category_action_issue")
            )
            ->distinct()
            ->get();

        $refrigeratorActions = DB::table('refrigerator_issues')
            ->join('refrigerator_actions', 'refrigerator_issues.refrigerator_action_id', 'refrigerator_actions.id')
            ->join('action_categories', 'refrigerator_actions.action_category_id', 'action_categories.id')
            ->where('refrigerator_issues.is_archived', 0)
            ->where('refrigerator_actions.is_archived', 0)
            ->where('action_categories.is_archived', 0)
            ->select(
                'refrigerator_issues.comet_id',
                DB::raw("CONCAT(action_categories.arabic_name, ' - ', refrigerator_actions.arabic_name, ' - ', refrigerator_issues.arabic_name) 
                    as category_action_issue")
            )
            ->distinct()
            ->get();
          

        $waterActions = DB::table('water_issues')
            ->join('water_actions', 'water_issues.water_action_id', 'water_actions.id')
            ->join('action_categories', 'water_actions.action_category_id', 'action_categories.id')
            ->where('water_issues.is_archived', 0)
            ->where('water_actions.is_archived', 0)
            ->where('action_categories.is_archived', 0)
            ->select(
                'water_issues.comet_id',
                DB::raw("CONCAT(action_categories.arabic_name, ' - ', water_actions.arabic_name, ' - ', water_issues.arabic_name) 
                    as category_action_issue")
            )
            ->distinct()
            ->get();
  

        $internetActions = DB::table('internet_issues')
            ->join('internet_actions', 'internet_issues.internet_action_id', 'internet_actions.id')
            ->join('action_categories', 'internet_actions.action_category_id', 'action_categories.id')
            ->where('internet_issues.is_archived', 0)
            ->where('internet_actions.is_archived', 0)
            ->where('action_categories.is_archived', 0)
            ->select(
                'internet_issues.comet_id',
                DB::raw("CONCAT(action_categories.arabic_name, ' - ', internet_actions.arabic_name, ' - ', internet_issues.arabic_name) 
                    as category_action_issue")
            )
            ->distinct()
            ->get();
  

        $data = collect([$energyActions, $refrigeratorActions, $waterActions, $internetActions])->flatten();

        return response()->json([
            'energy' => $energyActions->merge($refrigeratorActions) ?: 'No data found',
            'water' => $waterActions ?: 'No data found',
            'internet' => $internetActions ?: 'No data found'
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }
}