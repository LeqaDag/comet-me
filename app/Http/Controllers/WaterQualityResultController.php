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

class WaterQualityResultController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        // $this->middleware('permission:quality-list|quality-create|quality-edit|quality-delete', ['only' => ['index']]);
        // $this->middleware('permission:quality-create', ['only' => ['create','store']]);
        // $this->middleware('permission:quality-edit', ['only' => ['edit','update']]);
        // $this->middleware('permission:quality-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {	
        // $results = WaterQualityResult::all();

        // foreach($results as $result) {

        //     if($result->h2o_user_name != NULL) {

        //         $household = Household::where('english_name', $result->h2o_user_name)->first();

        //         if($household != NULL) {
        //             $h2oUser = H2oUser::where('household_id', $household->id)->first();

        //             if($h2oUser != NULL) {
        //                 $result->h2o_user_id = $h2oUser->id;
        //                 $result->household_id = $household->id;
    
        //             } else {
    
        //                 $sharedH2oUser = H2oSharedUser::where('user_english_name', $result->h2o_user_name)->first();
                        
        //                 if($sharedH2oUser != NULL) 
        //                 {
        //                     $result->h2o_shared_user_id = $sharedH2oUser->id;
        //                     $result->household_id = $sharedH2oUser->household_id;
        //                 }
        //             }
        //         }
        //     }

        //    // $h2oPublic = H2oPublicStructure::where()->first();
        //    // $result->public_structure_id = 0;

        //     $result->save();
        // }


        if ($request->ajax()) {

            $data = DB::table('water_quality_results')
                ->join('communities', 'water_quality_results.community_id', 'communities.id')
                ->leftJoin('h2o_users', 'water_quality_results.h2o_user_id', 'h2o_users.id')
                ->leftJoin('households', 'h2o_users.household_id', 'households.id')
                ->leftJoin('public_structures', 'water_quality_results.public_structure_id', 
                    '=', 'public_structures.id')
                ->select('water_quality_results.id as id', 'households.english_name as household', 
                    'communities.english_name as community_name',
                    'water_quality_results.created_at as created_at',
                    'water_quality_results.updated_at as updated_at',
                    'public_structures.english_name as public_name',
                    'water_quality_results.date')
                ->latest();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row) {

                    $viewButton = "<a type='button' class='viewWaterResult' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#viewWaterResultModal' ><i class='fa-solid fa-eye text-info'></i></a>";

                    return $viewButton;
                })
               
                ->filter(function ($instance) use ($request) {
                    if (!empty($request->get('search'))) {
                            $instance->where(function($w) use($request){
                            $search = $request->get('search');
                            $w->orWhere('communities.english_name', 'LIKE', "%$search%")
                            ->orWhere('communities.arabic_name', 'LIKE', "%$search%")
                            ->orWhere('households.english_name', 'LIKE', "%$search%")
                            ->orWhere('households.arabic_name', 'LIKE', "%$search%")
                            ->orWhere('water_quality_results.date', 'LIKE', "%$search%");
                        });
                    }
                })
            ->rawColumns(['action'])
            ->make(true);
        }

        $communities = Community::where("water_service", "Yes")->get();
        $households = Household::where("water_system_status", "Served")->get();

		return view('results.water.index', compact('communities', 'households'));
    }

    /**
     * Show the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $result = WaterQualityResult::findOrFail($id);
        $community = Community::where('id', $result->community_id)->first();
        $household = Household::where('id', $result->household_id)->first();

        $response['result'] = $result;
        $response['community'] = $community;
        $response['household'] = $household;

        return response()->json($response);
    }

    /**
     * 
     * @return \Illuminate\Support\Collection
     */
    public function export(Request $request)
    {
                
        return Excel::download(new WaterQualityResultExport($request), 'water_quality_results.xlsx');
    }
}