<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\User;
use App\Models\Community;
use App\Models\CommunityWaterSource;
use App\Models\GridUser;
use App\Models\H2oSharedUser;
use App\Models\H2oStatus;
use App\Models\H2oUser;
use App\Models\H2oSystemIncident;
use App\Models\H2oPublicStructure;
use App\Models\PublicStructure;
use App\Models\Household;
use App\Models\WaterQualityResult;
use App\Models\WaterUser;
use App\Exports\WaterUserExport;
use App\Models\EnergySystemType;
use App\Exports\WaterQualityResultExport;
use Auth;
use DB;
use Route;
use DataTables;
use Excel;

class WaterQualitySummaryController extends Controller
{
    /** 
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {	
        $results = DB::table('water_quality_results')
            ->join('communities', 'water_quality_results.community_id', 'communities.id')
            ->groupBy('water_quality_results.year', 'communities.english_name')
            ->select('communities.english_name as community_name',
                'water_quality_results.date', 'water_quality_results.year',
                'water_quality_results.created_at','water_quality_results.id',
                'water_quality_results.cfu','water_quality_results.community_id',)
            ->selectRaw('COUNT("water_quality_results.community_id") as samples')
            ->get();

          //  die($results);
        $communities = Community::where("water_service", "Yes")->get();
        $households = Household::where("water_system_status", "Served")->get();

		return view('results.summary.index', compact('results', 'communities', 'households'));
    }

    /**
     * Show the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function cfuMax($id, $year)
    {
        $result = WaterQualityResult::where("year", $year)
            ->where("community_id", $id)
            ->where("cfu", "<", 10)
            ->count("cfu");


        return response()->json($result);
    }
}