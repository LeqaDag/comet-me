<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\User;
use App\Models\BsfStatus;
use App\Models\Donor;
use App\Models\Community;
use App\Models\CommunityWaterSource;
use App\Models\GridUser;
use App\Models\GridUserDonor;
use App\Models\H2oSharedUser;
use App\Models\H2oStatus;
use App\Models\H2oUser;
use App\Models\H2oSystemIncident;
use App\Models\H2oUserDonor;
use App\Models\Household;
use App\Models\WaterUser;
use App\Exports\WaterUserExport;
use App\Models\EnergySystemType;
use Auth;
use DB;
use Route;
use DataTables;
use Excel;

class WaterUserController extends Controller
{ 
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {	
        if ($request->ajax()) {
            $data = DB::table('h2o_users')
                ->LeftJoin('grid_users', 'h2o_users.household_id', '=', 'grid_users.household_id')
                ->join('households', 'h2o_users.household_id', 'households.id')
                ->join('communities', 'h2o_users.community_id', 'communities.id')
                ->join('h2o_statuses', 'h2o_users.h2o_status_id', '=', 'h2o_statuses.id')
                ->where('h2o_statuses.status', 'Used')
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

                    $viewButton = "<a type='button' class='viewWaterUser' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#viewWaterUserModal' ><i class='fa-solid fa-eye text-info'></i></a>";

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
            //->where('h2o_statuses.status', '!=', "Used")
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

        $donors = Donor::all();
        $energySystemTypes = EnergySystemType::all();

		return view('users.water.index', compact('communities', 'bsfStatus', 'households', 
            'h2oStatus', 'totalWaterHouseholds', 'totalWaterMale', 'totalWaterFemale',
            'totalWaterChildren', 'totalWaterAdults', 'donors', 'energySystemTypes'))
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
     * Show the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $h2oUser = H2oUser::findOrFail($id);
        $householdId = $h2oUser->household_id;
        $gridUser = GridUser::where('household_id', $householdId)->first();
        $community = Community::where('id', $h2oUser->community_id)->first();
        $household = Household::where('id', $h2oUser->household_id)->first();
        $h2oStatus = H2oStatus::where('id', $h2oUser->h2o_status_id)->first();
        $bsfStatus = BsfStatus::where('id', $h2oUser->bsf_status_id)->first();

        $response['h2oUser'] = $h2oUser;
        $response['h2oStatus'] = $h2oStatus;
        $response['bsfStatus'] = $bsfStatus;
        $response['gridUser'] = $gridUser;
        $response['community'] = $community;
        $response['household'] = $household;

        return response()->json($response);
    }

    /** 
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $h2oUser = new H2oUser();
        $h2oUser->community_id = $request->community_id;
        $h2oUser->household_id = $request->household_id;
        $h2oUser->h2o_status_id = $request->h2o_status_id;
        $h2oUser->bsf_status_id = $request->bsf_status_id;
        $h2oUser->number_of_bsf = $request->number_of_bsf;
        $h2oUser->number_of_h20 = $request->number_of_h20; 
        $h2oUser->h2o_request_date = $request->h2o_request_date; 
        $h2oUser->installation_year = $request->installation_year;
        $h2oUser->h2o_installation_date = $request->h2o_installation_date;
        $h2oUser->save();

        $gridUser = new GridUser();
        $gridUser->community_id = $request->community_id;
        $gridUser->household_id = $request->household_id;
        $gridUser->request_date = $request->request_date;
        $gridUser->grid_access = $request->grid_access;
        $gridUser->grid_integration_large = $request->grid_integration_large;
        $gridUser->large_date = $request->large_date;
        $gridUser->grid_integration_small = $request->grid_integration_small;
        $gridUser->small_date = $request->small_date;
        $gridUser->is_delivery = $request->is_delivery;
        $gridUser->is_paid = $request->is_paid;
        $gridUser->is_complete = $request->is_complete;
        $gridUser->save();

        $household = Household::findOrFail($request->household_id);
        $household->water_service = "Yes";
        $household->water_system_status = "Served";
        $household->save();

        return redirect()->back()->with('message', 'New User Added Successfully!');
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
            $html = '<option value="Yes">Yes</option><option value="No">No</option>';
        }

        return response()->json([
            'html' => $html, 
            'val' => $val
        ]);
    }

    /**
     * Change resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function chartWater(Request $request)
    {
        $gridLarge = 0;
        $gridSmall = 0;
        
        $arrayGrid[] = ['Grid Integration', 'Total'];

        if($request->water_status == "0") {

            $gridLarge = GridUser::where("is_complete", "Yes")
                ->selectRaw('SUM(grid_integration_large) AS sumLarge')
                ->first();
            $gridSmall = GridUser::where("is_complete", "Yes")
                ->selectRaw('SUM(grid_integration_small) AS sumSmall')
                ->first();
        } else if($request->water_status == "1") {

            $gridLarge = GridUser::where("is_complete", "No")
                ->selectRaw('SUM(grid_integration_large) AS sumLarge')
                ->first();
            $gridSmall = GridUser::where("is_complete", "No")
                ->selectRaw('SUM(grid_integration_small) AS sumSmall')
                ->first();
        } else if($request->water_status == "2") {

            $gridLarge = GridUser::where("is_delivery", "Yes")
                ->selectRaw('SUM(grid_integration_large) AS sumLarge')
                ->first();
            $gridSmall = GridUser::where("is_delivery", "Yes")
                ->selectRaw('SUM(grid_integration_small) AS sumSmall')
                ->first();
        } else if($request->water_status == "3") {

            $gridLarge = GridUser::where("is_delivery", "No")
                ->selectRaw('SUM(grid_integration_large) AS sumLarge')
                ->first();
            $gridSmall = GridUser::where("is_delivery", "No")
                ->selectRaw('SUM(grid_integration_small) AS sumSmall')
                ->first();
        }

        for($key=0; $key <=2; $key++) {
            if($key == 1) $arrayGrid[$key] = ["Grid Large", $gridLarge->sumLarge];
            if($key == 2) $arrayGrid[$key] = ["Grid Small", $gridSmall->sumSmall];
        }
      
        return response()->json($arrayGrid); 
    }

     /**
     * Change resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function waterChartDetails(Request $request)
    {
        $h2oStatus = $request->selected_data;

        $users = DB::table('h2o_users')
            ->join('households', 'h2o_users.household_id', 'households.id')
            ->join('communities', 'h2o_users.community_id', 'communities.id')
            ->join('h2o_statuses', 'h2o_users.h2o_status_id', '=', 'h2o_statuses.id')
            ->where("h2o_statuses.status", $h2oStatus)
            ->select('h2o_users.id as id', 'households.english_name', 'number_of_h20',
                'number_of_bsf', 'communities.english_name as community_name',
                'h2o_users.created_at as created_at',
                'h2o_users.updated_at as updated_at', 'h2o_statuses.status')
            ->get();

        $response = $users;  
      
        return response()->json($response); 
    }

    /**
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteWaterUser(Request $request)
    {
        $id = $request->id;

        $h2oUser = H2oUser::find($id);
        $gridUser = GridUser::where('household_id', $h2oUser->household_id)->first();

        if($gridUser) $gridUser->delete();

        if($h2oUser->delete()) {


            $response['success'] = 1;
            $response['msg'] = 'User Deleted successfully'; 
        } else {

            $response['success'] = 0;
            $response['msg'] = 'Invalid ID.';
        }

        return response()->json($response); 
    }

    /**
     * Get resources from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getWaterUserByCommunity($community_id)
    {
        $households = DB::table('h2o_users')
            ->LeftJoin('grid_users', 'h2o_users.household_id', '=', 'grid_users.household_id')
            ->join('households', 'h2o_users.household_id', 'households.id')
            ->where("households.community_id", $community_id)
            ->select('households.id as id', 'households.english_name')
            ->get();
 
        if (!$community_id) {

            $html = '<option value="">Choose One...</option>';
        } else {

            $html = '<option value="">Select...</option>';
            $households = DB::table('h2o_users')
                ->LeftJoin('grid_users', 'h2o_users.household_id', '=', 'grid_users.household_id')
                ->join('households', 'h2o_users.household_id', 'households.id')
                ->where("households.community_id", $community_id)
                ->select('households.id as id', 'households.english_name')
                ->get();

            foreach ($households as $household) {
                $html .= '<option value="'.$household->id.'">'.$household->english_name.'</option>';
            }
        }

        return response()->json(['html' => $html]);
    }

    /**
     * Get resources from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getPublicByCommunity($community_id)
    {
        $publics = DB::table('h2o_public_structures')
            ->join('public_structures', 'h2o_public_structures.public_structure_id', '=', 'public_structures.id')
            ->where("h2o_public_structures.community_id", $community_id)
            ->select('public_structures.id', 'public_structures.english_name')
            ->get();
      
        if (!$community_id) {

            $html = '<option value="">Choose One...</option>';
        } else {

            $html = '<option value="">Select...</option>';
            $publics = DB::table('h2o_public_structures')
                ->join('public_structures', 'h2o_public_structures.public_structure_id', '=', 'public_structures.id')
                ->where("h2o_public_structures.community_id", $community_id)
                ->select('public_structures.id', 'public_structures.english_name')
                ->get();

            foreach ($publics as $public) {
                $html .= '<option value="'.$public->id.'">'.$public->english_name.'</option>';
            }
        }

        return response()->json(['html' => $html]);
    }

    /**
     * 
     * @return \Illuminate\Support\Collection
     */
    public function export(Request $request) 
    {
                
        return Excel::download(new WaterUserExport($request), 'used_water_users.xlsx');
    }
}
