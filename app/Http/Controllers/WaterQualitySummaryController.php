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
use App\Exports\WaterQualitySummaryExport;
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
        if (Auth::guard('user')->user() != null) {

            $results = DB::table('water_quality_results')
                ->join('communities', 'water_quality_results.community_id', 'communities.id')
                ->groupBy('water_quality_results.year', 'communities.english_name')
                ->where('water_quality_results.is_archived', 0)
                ->select('communities.english_name as community_name',
                    'water_quality_results.date', 'water_quality_results.year',
                    'water_quality_results.created_at','water_quality_results.id',
                    'water_quality_results.cfu','water_quality_results.community_id',)
                ->selectRaw('COUNT("water_quality_results.household_id") as samples')
                ->get();
           // die($results);

            $communities = Community::where("water_service", "Yes")
                ->orderBy('english_name', 'ASC')
                ->where('is_archived', 0)
                ->get();
            $households = Household::where("water_system_status", "Served")
                ->where('is_archived', 0)
                ->orderBy('english_name', 'ASC')
                ->get();

            return view('results.summary.index', compact('results', 'communities', 'households'));

        } else {

            return view('errors.not-found');
        }
    }

    /**
     * Change resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function chartWaterResult(Request $request)
    {
        $dataResultCfu = DB::table('water_quality_results')
            ->join('communities', 'water_quality_results.community_id', '=', 'communities.id')
            ->where('water_quality_results.is_archived', 0)
            ->select(
                DB::raw('water_quality_results.cfu as cfu'),
                DB::raw('count(*) as number'),
                DB::raw('water_quality_results.year'),
                DB::raw('water_quality_results.date'))
            ->groupBy('water_quality_results.cfu', 'water_quality_results.year');

        if($request->year != 0) {

            $dataResultCfu->where('water_quality_results.year', $request->year);
        }

        if($request->month != 0) {

            $dataResultCfu->whereMonth('water_quality_results.date', $request->month);
        } 

        $dataResultCfu = $dataResultCfu->get();

        $arrayResultCfu[] = ['Value', 'Number'];
        
        foreach($dataResultCfu as $key => $value) {

            $arrayResultCfu[++$key] = [$value->cfu, $value->number];
        }
        
        return response()->json($arrayResultCfu); 
    }

    /**
     * 
     * @return \Illuminate\Support\Collection
     */
    public function export(Request $request)
    {     
        return Excel::download(new WaterQualitySummaryExport($request), 
            'water_quality_summary_results.xlsx');
    }
}