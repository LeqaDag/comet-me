<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\User;
use App\Models\BsfStatus;
use App\Models\Community;
use App\Models\CommunityWaterSource;
use App\Models\GridUser;
use App\Models\H2oSharedUser;
use App\Models\H2oStatus;
use App\Models\H2oUser;
use App\Models\Household;
use App\Models\WaterUser;
use Auth;
use DB;
use Route;
use DataTables;

class WaterUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {	
        // $communitiesMasafers = Community::where("sub_sub_region_id", 1)->get();
        // $count =0;

        // foreach($communitiesMasafers as $communitiesMasafer) {
        //     $energyUsers = GridUser::where('community_id', $communitiesMasafer->id)
        //     ->where("is_complete", "Yes")
        //     ->count();

        //     $count+=$energyUsers;
        // }

        // dd($count);
        // H2O Users
        // $h2oUsers = H2oUser::all();
        // foreach($h2oUsers as $h2oUser) {
        //     $household = Household::where("english_name", $h2oUser->household_name)
        //     ->first();
        //     $community = Community::where("english_name", $h2oUser->community_name)
        //     ->first();
        //     $status = H2oStatus::where("status", $h2oUser->h2o_status)
        //     ->first();
        //     $bsf = BsfStatus::where("name", $h2oUser->bsf_status)
        //     ->first();

        //     $h2oUser->h2o_status_id = $status->id;
        //     $h2oUser->bsf_status_id = $bsf->id;
        //     $h2oUser->household_id = $household->id;
        //     $h2oUser->community_id = $community->id;
        //     $h2oUser->save();

        // }

        // Shared H2O Users
        // $sharedUsers = H2oSharedUser::all();
        // foreach($sharedUsers as $sharedUser) {
        //     $household = Household::where("english_name", $sharedUser->household_name)
        //     ->first();
        //     $hold = Household::where("english_name", $sharedUser->h2o_user)
        //     ->first();
        //     $user = H2oUser::where("household_id", $hold->id)->first();

        //     $sharedUser->household_id = $household->id;
        //     $sharedUser->h2o_user_id = $user->id;
        //     $sharedUser->save();
        // }

        // Grid Users
        // $gridUsers = GridUser::all();
        // foreach($gridUsers as $gridUser) {
        //     $household = Household::where("english_name", $gridUser->household_name)
        //     ->first();
        //     $community = Community::where("english_name", $gridUser->community_name)
        //     ->first();

        //     $gridUser->household_id = $household->id;
        //     $gridUser->community_id = $community->id;
        //     $gridUser->save();

        // }
    
        
      
        if ($request->ajax()) {
            $data = DB::table('h2o_users')
                ->LeftJoin('grid_users', 'h2o_users.household_id', '=', 'grid_users.household_id')
                ->join('households', 'h2o_users.household_id', 'households.id')
                ->join('communities', 'h2o_users.community_id', 'communities.id')
                ->join('h2o_statuses', 'h2o_users.h2o_status_id', '=', 'h2o_statuses.id')
                ->select('h2o_users.id as id', 'households.english_name', 'number_of_h20',
                    'grid_integration_large', 'large_date', 'grid_integration_small', 
                    'small_date', 'is_delivery', 'number_of_bsf', 'is_paid', 
                    'is_complete', 'communities.english_name as community_name',
                    'installation_year', 'h2o_users.created_at as created_at',
                    'h2o_users.updated_at as updated_at', 'h2o_statuses.status')
                ->latest();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row) {

                    $updateButton = "<a type='button' class='updateWaterUser' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#updateWaterUserModal' ><i class='fa-solid fa-pen-to-square text-success'></i></a>";
                    $deleteButton = "<a type='button' class='deleteWaterUser' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";
                    $viewButton = "<a type='button' class='viewWaterUser' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#viewWaterUserModal' ><i class='fa-solid fa-eye text-info'></i></a>";

                    return $updateButton." ".$deleteButton. " ". $viewButton;
                })
               
                ->filter(function ($instance) use ($request) {
                    if (!empty($request->get('search'))) {
                            $instance->where(function($w) use($request){
                            $search = $request->get('search');
                            $w->orWhere('communities.english_name', 'LIKE', "%$search%")
                            ->orWhere('communities.arabic_name', 'LIKE', "%$search%")
                            ->orWhere('households.english_name', 'LIKE', "%$search%")
                            ->orWhere('households.arabic_name', 'LIKE', "%$search%")
                            ->orWhere('h2o_statuses.status', 'LIKE', "%$search%")
                            ->orWhere('grid_integration_large', 'LIKE', "%$search%")
                            ->orWhere('grid_integration_small', 'LIKE', "%$search%");
                        });
                    }
                })
            ->rawColumns(['action'])
            ->make(true);
        }

        $communities = Community::all();
        $bsfStatus = BsfStatus::all();
        $households = Household::all();
        $h2oStatus = H2oStatus::all();

        $data = DB::table('h2o_users')
            ->join('h2o_statuses', 'h2o_users.h2o_status_id', '=', 'h2o_statuses.id')
            ->where('h2o_statuses.status', '!=', "Used")
            ->select(
                    DB::raw('h2o_statuses.status as name'),
                    DB::raw('count(*) as number'))
            ->groupBy('h2o_statuses.status')
            ->get();

        
        $array[] = ['H2O Status', 'Total'];
        
        foreach($data as $key => $value) {

            $array[++$key] = [$value->name, $value->number];
        }

        $gridLarge = GridUser::selectRaw('SUM(grid_integration_large) AS sumLarge')
            ->first();
        $gridSmall = GridUser::selectRaw('SUM(grid_integration_small) AS sumSmall')
            ->first();
        
        $arrayGrid[] = ['Grid Integration', 'Total'];
        
        for($key=0; $key <=2; $key++) {
            if($key == 1) $arrayGrid[$key] = ["Grid Large", $gridLarge->sumLarge];
            if($key == 2) $arrayGrid[$key] = ["Grid Small", $gridSmall->sumSmall];
        }

        $totalWaterHouseholds = Household::where("water_service", "Yes")
            ->selectRaw('SUM(number_of_people) AS number_of_people')
            ->first();
        $totalWaterMale = Household::where("water_service", "Yes")
            ->selectRaw('SUM(number_of_male) AS number_of_male')
            ->first(); 
        $totalWaterFemale = Household::where("water_service", "Yes")
            ->selectRaw('SUM(number_of_female) AS number_of_female')
            ->first(); 
        $totalWaterAdults = Household::where("water_service", "Yes")
            ->selectRaw('SUM(number_of_adults) AS number_of_adults')
            ->first();
        $totalWaterChildren = Household::where("water_service", "Yes")
            ->selectRaw('SUM(number_of_children) AS number_of_children')
            ->first();
  
		return view('users.water.index', compact('communities', 'bsfStatus', 'households', 
            'h2oStatus', 'totalWaterHouseholds', 'totalWaterMale', 'totalWaterFemale',
            'totalWaterChildren', 'totalWaterAdults'))
        ->with('h2oChartStatus', json_encode($array))
        ->with('gridChartStatus', json_encode($arrayGrid));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $h2oUsers = H2oUser::all();
        // foreach($h2oUsers as $h2oUser) {
        //     $household = Household::where("id", $h2oUser->household_id)->first();
        //     $household->water_service = "Yes";
        //     $household->save();
        // } 

        dd($request->all());
        // $waterUser = WaterUser::create($request->all());
        // $waterUser->save();

        return redirect()->back();
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $community = Community::findOrFail($id);
        $community->is_archived = 1;
        $community->save();

        return redirect()->back();
    }

    /**
     * Get grid access by community_id.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getGridSource(Request $request)
    {
        $community = CommunityWaterSource::where('community_id', $request->community_id)
            ->where('water_source_id', 1)
            ->get();
 
        if (!$request->community_id || $community->count() ==0) {
            $val = "New";
            $html = '<option disabled selected>Choose One...</option> <option value="Yes">Yes</option><option value="No">No</option>';
        } else {
            $val = "Yes";
            $html = '<option value="Yes">Yes</option>';
        }

        return response()->json([
            'html' => $html, 
            'val' => $val
        ]);
    }
}
