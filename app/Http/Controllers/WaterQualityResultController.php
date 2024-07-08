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
use App\Imports\ImportWaterQualityResult;
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
        //$this->middleware('permission:quality-list|quality-create|quality-edit|quality-delete', ['only' => ['index']]);
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

        if (Auth::guard('user')->user() != null) {

            if ($request->ajax()) {

                $data = DB::table('water_quality_results')
                    ->join('communities', 'water_quality_results.community_id', 'communities.id')
                    ->leftJoin('households', 'water_quality_results.household_id', 'households.id')
                    ->leftJoin('public_structures', 'water_quality_results.public_structure_id', 
                        '=', 'public_structures.id')
                    ->where('water_quality_results.is_archived', 0)
                    ->select('water_quality_results.id as id', 
                        'households.english_name as household_name', 
                        'communities.english_name as community_name',
                        'water_quality_results.created_at as created_at',
                        'water_quality_results.updated_at as updated_at',
                        'public_structures.english_name as public_name',
                        'water_quality_results.date')
                    ->latest();
                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
                        $updateButton = "<a type='button' class='updateWaterResult' data-id='".$row->id."' ><i class='fa-solid fa-pen-to-square text-success'></i></a>";
                        $viewButton = "<a type='button' class='viewWaterResult' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#viewWaterResultModal' ><i class='fa-solid fa-eye text-info'></i></a>";
                        $deleteButton = "<a type='button' class='deleteWaterResult' data-id='".$row->id."'  ><i class='fa-solid fa-trash text-danger'></i></a>";
    
                        if(Auth::guard('user')->user()->user_type_id == 1 || 
                            Auth::guard('user')->user()->user_type_id == 2 ||
                            Auth::guard('user')->user()->user_type_id == 5 ||
                            Auth::guard('user')->user()->user_type_id == 9) 
                        {
                                
                            return $updateButton." ". $viewButton." ". $deleteButton;
                        } else return $viewButton;
                        
                    })
                    ->addColumn('holder', function($row) {

                        if($row->household_name != null) $holder = $row->household_name;
                        else if($row->public_name != null) $holder = $row->public_name;

                        return $holder;
                    })
                    ->filter(function ($instance) use ($request) {
                        if (!empty($request->get('search'))) {
                                $instance->where(function($w) use($request){
                                $search = $request->get('search');
                                $w->orWhere('communities.english_name', 'LIKE', "%$search%")
                                ->orWhere('communities.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('households.english_name', 'LIKE', "%$search%")
                                ->orWhere('households.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('public_structures.english_name', 'LIKE', "%$search%")
                                ->orWhere('public_structures.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('water_quality_results.date', 'LIKE', "%$search%");
                            });
                        }
                    })
                ->rawColumns(['action', 'holder'])
                ->make(true);
            }
    
            $communities = Community::where("water_service", "Yes")
                ->where('is_archived', 0)
                ->orderBy('english_name', 'ASC')
                ->get();
            $households = Household::where("water_system_status", "Served")
                ->where('is_archived', 0)
                ->orderBy('english_name', 'ASC')
                ->get();
    
            return view('results.water.index', compact('communities', 'households'));
            
        } else { 

            return view('errors.not-found');
        }
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
        $public = PublicStructure::where('id', $result->public_structure_id)->first();

        $response['result'] = $result;
        $response['community'] = $community;
        $response['household'] = $household;
        $response['public'] = $public;

        return response()->json($response);
    }

    /**
     * Get resources from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getWaterHolderByCommunity($community_id, $flag)
    {
        $html = "<option disabled selected>Choose one...</option>";
        $systems = null;

        if($flag == "user") {

            $households = DB::table('all_water_holders')
                ->join('households', 'all_water_holders.household_id', 'households.id')
                ->where('all_water_holders.is_archived', 0)
                ->where('all_water_holders.is_main', "Yes")
                ->where("all_water_holders.community_id", $community_id)
                ->orderBy('households.english_name', 'ASC')
                ->select('households.id as id', 'households.english_name')
                ->get();
        } else if($flag == "shared") {

            $households =DB::table('all_water_holders')
                ->join('households', 'all_water_holders.household_id', 'households.id')
                ->where('all_water_holders.is_archived', 0)
                ->where('all_water_holders.is_main', "No")
                ->where("all_water_holders.community_id", $community_id)
                ->orderBy('households.english_name', 'ASC')
                ->select('households.id as id', 'households.english_name')
                ->get();
        } else if($flag == "public") {

            $households = DB::table('h2o_public_structures')
                ->join('public_structures', 'h2o_public_structures.public_structure_id', 'public_structures.id')
                ->where('h2o_public_structures.is_archived', 0)
                ->where("h2o_public_structures.community_id", $community_id)
                ->select('public_structures.id as id', 'public_structures.english_name')
                ->get();
        } else if($flag == "system") {

            $systems = DB::table('water_systems')
                ->join('communities', 'water_systems.community_id', 'communities.id')
                ->where("water_systems.community_id", $community_id)
                ->select('water_systems.id as id', 'water_systems.name')
                ->get();
        }

        if($systems) {

            foreach ($systems as $system) {

                $html .= '<option value="'.$system->id.'">'.$system->name.'</option>';
            }
        } else {

            foreach ($households as $household) {

                $html .= '<option value="'.$household->id.'">'.$household->english_name.'</option>';
            }    
        }

        return response()->json(['html' => $html]);
    }

    /** 
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $waterResult = new WaterQualityResult();
        $waterResult->community_id = $request->community_id;

        if($request->public_user == "user") { 

            $waterResult->household_id = $request->household_id;
            $h2o_user_id = H2oUser::where('household_id', $request->household_id)->select('id')->get();
            $waterResult->h2o_user_id = $h2o_user_id[0]->id;

        } else if($request->public_user == "shared") {

            $waterResult->household_id = $request->household_id;
            $h2oSharedUser = H2oSharedUser::where('household_id', $request->household_id)->select('id')->get();
            $waterResult->h2o_shared_user_id = $h2oSharedUser[0]->id;

        } else if($request->public_user == "public") {

            $public_structure_id = $request->household_id;
            $waterResult->public_structure_id = $public_structure_id;
        }
        
        if($request->date) {

            $waterResult->date = $request->date;
            $year = explode('-', $request->date);
            $waterResult->year = $year[0];
        }
     
        $waterResult->cfu = $request->cfu;
        $waterResult->fci = $request->fci;
        $waterResult->ec = $request->ec;
        $waterResult->ph = $request->ph;
        $waterResult->notes = $request->notes;
        $waterResult->save();

        return redirect()->back()->with('message', 'New Water Result Added Successfully!');
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function deleteQualityResult(Request $request)
    {
        $id = $request->id;

        $waterResult = WaterQualityResult::findOrFail($id);

        if($waterResult) {

            $waterResult->is_archived = 1;
            $waterResult->save();

            $response['success'] = 1;
            $response['msg'] = 'Water Result Deleted successfully'; 
        } else {

            $response['success'] = 0;
            $response['msg'] = 'Invalid ID.';
        }

        return response()->json($response); 
    }

    /**
     * View Edit page.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function editPage($id)
    {
        $waterResult = WaterQualityResult::findOrFail($id);

        return response()->json($waterResult);
    }

    /**
     * View Edit page.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $waterResult = WaterQualityResult::findOrFail($id);
        $community_id = Community::findOrFail($waterResult->community_id);
        $communities = Community::where('is_archived', 0)->get();
        $household  = 0;
        $public = 0;
        if($waterResult->household_id) $household = Household::findOrFail($waterResult->household_id);
        if($waterResult->public_structure_id) $public = PublicStructure::findOrFail($waterResult->public_structure_id);

        return view('results.water.edit', compact('household', 'communities',
            'public', 'waterResult'));
    }

    /**
     * Update an existing resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request, int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //dd($request->all());
        $waterResult = WaterQualityResult::find($id);

        if($request->date) {

            $waterResult->date = $request->date;
            $year = explode('-', $request->date);
            $waterResult->year = $year[0];
        }

        $waterResult->ph = $request->ph;
        $waterResult->ec = $request->ec;
        $waterResult->fci = $request->fci;
        $waterResult->cfu = $request->cfu;
        $waterResult->notes = $request->notes;

        $waterResult->save(); 
        
        return redirect('/quality-result')->with('message', 'Water Result Updated Successfully!');
    }

    /**
     * 
     * @return \Illuminate\Support\Collection
     */
    public function export(Request $request)
    {
                
        return Excel::download(new WaterQualityResultExport($request), 'water_quality_results.xlsx');
    }

    /**
     * 
     * @return \Illuminate\Support\Collection
     */
    public function import(Request $request)
    {
        Excel::import(new ImportWaterQualityResult, $request->file('file')); 
            
        return back()->with('success', 'Excel Data Imported successfully.');
    }

    /** 
     * Show the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function summary($year)
    {
        $results = WaterQualityResult::where("year", $year)->get();
       // die($results);

        $response['result'] = $result;
        $response['community'] = $community;
        $response['household'] = $household;
        $response['public'] = $public;

        return response()->json($response);
    }
}