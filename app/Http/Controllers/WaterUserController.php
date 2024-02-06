<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\User;
use App\Models\AllEnergyMeter;
use App\Models\AllWaterHolder;
use App\Models\AllWaterHolderDonor;
use App\Models\BsfStatus;
use App\Models\Donor;
use App\Models\Community;
use App\Models\CommunityService;
use App\Models\CommunityWaterSource;
use App\Models\GridUser;
use App\Models\GridUserDonor;
use App\Models\GridPublicStructure;
use App\Models\H2oSharedUser;
use App\Models\H2oStatus;
use App\Models\H2oUser;
use App\Models\H2oSystemIncident;
use App\Models\H2oPublicStructure;
use App\Models\H2oSharedPublicStructure;
use App\Models\H2oUserDonor;
use App\Models\Household; 
use App\Models\WaterUser;
use App\Models\PublicStructure;
use App\Exports\WaterUserExport;
use App\Models\EnergySystemType;
use App\Models\WaterNetworkUser;
use App\Models\WaterSystem;
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
        if (Auth::guard('user')->user() != null) {

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
    
            $communities = Community::where('is_archived', 0)
                ->orderBy('english_name', 'ASC')
                ->get();
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
    
            $gridLarge = GridUser::where('grid_integration_large', '!=', 0)
                ->selectRaw('SUM(grid_integration_large) AS sumLarge')
                ->first();
            $gridSmall = GridUser::where('grid_integration_small', '!=', 0)
                ->selectRaw('SUM(grid_integration_small) AS sumSmall')
                ->first();
            
            $arrayGrid[] = ['Grid Integration', 'Total']; 
            
            for($key=0; $key <=2; $key++) {
                if($key == 1) $arrayGrid[$key] = ["Grid Large", $gridLarge->sumLarge];
                if($key == 2) $arrayGrid[$key] = ["Grid Small", $gridSmall->sumSmall];
            }
    
            $totalWaterHouseholds = Household::where("water_system_status", "Served")
                ->selectRaw('SUM(number_of_people) AS number_of_people')
                ->first();
            $totalWaterMale = Household::where("water_system_status", "Served")
                ->selectRaw('SUM(number_of_male) AS number_of_male')
                ->first(); 
            $totalWaterFemale = Household::where("water_system_status", "Served")
                ->selectRaw('SUM(number_of_female) AS number_of_female')
                ->first(); 
            $totalWaterAdults = Household::where("water_system_status", "Served")
                ->selectRaw('SUM(number_of_adults) AS number_of_adults')
                ->first();
            $totalWaterChildren = Household::where("water_system_status", "Served")
                ->selectRaw('SUM(number_of_children) AS number_of_children')
                ->first(); 
    
            $donors = Donor::all();
            $energySystemTypes = EnergySystemType::all();
    
            return view('users.water.index', compact('communities', 'bsfStatus', 'households', 
                'h2oStatus', 'totalWaterHouseholds', 'totalWaterMale', 'totalWaterFemale',
                'totalWaterChildren', 'totalWaterAdults', 'donors', 'energySystemTypes'))
            ->with('h2oChartStatus', json_encode($array))
            ->with('gridChartStatus', json_encode($arrayGrid));
        } else {

            return view('errors.not-found');
        }
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
        $existCommunityService = CommunityService::where("community_id", $request->community_id[0])
            ->where("service_id", 2)
            ->first();
            
        if($existCommunityService) {

        } else {

            $communityService = new CommunityService();
            $communityService->service_id = 2;
            $communityService->community_id = $request->community_id[0];
            $communityService->save();
        }

        $exist = AllWaterHolder::where("household_id", $request->household_id)->first();

        if($request->public_user == "user") {

            if($request->number_of_h20) {

                $existH2oUser = H2oUser::where("household_id", $request->household_id)->first();

                if($existH2oUser) {

                    $existH2oUser->h2o_status_id = $request->h2o_status_id;
                    $existH2oUser->bsf_status_id = $request->bsf_status_id;
                    $existH2oUser->number_of_bsf = $request->number_of_bsf;
                    $existH2oUser->number_of_h20 = $request->number_of_h20; 
                    $existH2oUser->h2o_request_date = $request->h2o_request_date; 
                    $existH2oUser->installation_year = $request->installation_year;
                    $existH2oUser->h2o_installation_date = $request->h2o_installation_date;
                    $existH2oUser->save();
                } else {

                    $h2oUser = new H2oUser();
                    $h2oUser->community_id = $request->community_id[0];
                    $h2oUser->household_id = $request->household_id;
                    $h2oUser->h2o_status_id = $request->h2o_status_id;
                    $h2oUser->bsf_status_id = $request->bsf_status_id;
                    $h2oUser->number_of_bsf = $request->number_of_bsf;
                    $h2oUser->number_of_h20 = $request->number_of_h20; 
                    $h2oUser->h2o_request_date = $request->h2o_request_date; 
                    $h2oUser->installation_year = $request->installation_year;
                    $h2oUser->h2o_installation_date = $request->h2o_installation_date;
                    $h2oUser->save();
                }
            }
          
            $existGridUser = GridUser::where("household_id", $request->household_id)->first();

            if($existGridUser) {

            } else {

                $gridUser = new GridUser();
                $gridUser->community_id = $request->community_id[0];
                $gridUser->household_id = $request->household_id;
                $gridUser->request_date = $request->request_date; 
                $gridUser->grid_integration_large = $request->grid_integration_large;
                $gridUser->large_date = $request->large_date;
                $gridUser->grid_integration_small = $request->grid_integration_small;
                $gridUser->small_date = $request->small_date;
                $gridUser->grid_access = $request->grid_access;
                $gridUser->is_delivery = $request->is_delivery;
                $gridUser->is_paid = $request->is_paid;
                $gridUser->is_complete = $request->is_complete;
                $gridUser->save();
            }
            
            if($request->request_date) {
     
                if($request->grid_integration_large) {

                    if($existGridUser) {

                        $existGridUser->grid_integration_large = $request->grid_integration_large;
                        $existGridUser->large_date = $request->large_date;
                        $existGridUser->request_date = $request->request_date;
                        $existGridUser->grid_access = $request->grid_access;
                        $existGridUser->is_delivery = $request->is_delivery;
                        $existGridUser->is_paid = $request->is_paid;
                        $existGridUser->is_complete = $request->is_complete;
                        $existGridUser->save();
                    }
                } 

                if($request->grid_integration_small) {

                    if($existGridUser) {

                        $existGridUser->grid_integration_small = $request->grid_integration_small;
                        $existGridUser->small_date = $request->small_date;
                        $existGridUser->request_date = $request->request_date;
                        $existGridUser->grid_access = $request->grid_access;
                        $existGridUser->is_delivery = $request->is_delivery;
                        $existGridUser->is_paid = $request->is_paid;
                        $existGridUser->is_complete = $request->is_complete;
                        $existGridUser->save();
                    }
                } 
            }
    
            if($exist) {
  
            } else {

                $allWaterHolder = new AllWaterHolder();
                $allWaterHolder->is_main = "Yes";
                $allWaterHolder->household_id = $request->household_id;
                $allWaterHolder->community_id = $request->community_id[0];
                $allWaterHolder->notes = $request->notes;
                $allWaterHolder->save();
            }

            $household = Household::findOrFail($request->household_id);
            $household->water_service = "Yes";
            $household->water_system_status = "Served";
            $household->save();

        }  else if($request->public_user == "public") {

            $allWaterHolder = new AllWaterHolder();
            $allWaterHolder->is_main = "Yes";
            $allWaterHolder->public_structure_id = $request->household_id;
            $allWaterHolder->community_id = $request->community_id[0];
            $allWaterHolder->notes = $request->notes;
            $allWaterHolder->save();

            if($request->number_of_h20) {
      
                $h2oPublicStructure = new H2oPublicStructure();
                $h2oPublicStructure->community_id = $request->community_id[0];
                $h2oPublicStructure->public_structure_id = $request->household_id;
                $h2oPublicStructure->h2o_status_id = $request->h2o_status_id;
                $h2oPublicStructure->bsf_status_id = $request->bsf_status_id;
                $h2oPublicStructure->number_of_bsf = $request->number_of_bsf;
                $h2oPublicStructure->number_of_h20 = $request->number_of_h20; 
                $h2oPublicStructure->h2o_request_date = $request->h2o_request_date; 
                $h2oPublicStructure->installation_year = $request->h2o_installation_date;
                $h2oPublicStructure->save();
    
                $exist = AllWaterHolder::where("public_structure_id", $request->household_id)->first();
    
                if($exist) {
    
                    $exist->water_system_id = 1;
                    $exist->request_date = $request->h2o_request_date; 
                    $exist->installation_year = $request->installation_year;
                    $exist->installation_date = $request->h2o_installation_date;
                    $exist->save();
                } else {
    
                    $allWaterHolder = new AllWaterHolder();
                    $allWaterHolder->is_main = "Yes";
                    $allWaterHolder->public_structure_id = $request->household_id;
                    $allWaterHolder->community_id = $request->community_id[0];
                    $allWaterHolder->water_system_id = 1;
                    $allWaterHolder->request_date = $request->h2o_request_date; 
                    $allWaterHolder->installation_year = $request->installation_year;
                    $allWaterHolder->installation_date = $request->h2o_installation_date;
                    $allWaterHolder->notes = $request->notes;
                    $allWaterHolder->save();
                }
            }

            $gridPublicStructure = new GridPublicStructure();
            $gridPublicStructure->community_id = $request->community_id[0];
            $gridPublicStructure->public_structure_id = $request->household_id;
            $gridPublicStructure->request_date = $request->request_date;
            $gridPublicStructure->grid_access = $request->grid_access;

    
            if($request->grid_integration_large) {
    
                $gridPublicStructure->grid_integration_large = $request->grid_integration_large;
                $gridPublicStructure->large_date = $request->large_date;
    
                $exist = AllWaterHolder::where("public_structure_id", $request->household_id)->first();
    
                if($exist) {
    
                    $exist->water_system_id1 = 2;
                    $exist->save();
                } else {
    
                    $allWaterHolder = new AllWaterHolder();
                    $allWaterHolder->is_main = "Yes";
                    $allWaterHolder->public_structure_id = $request->household_id;
                    $allWaterHolder->community_id = $request->community_id[0];
                    $allWaterHolder->water_system_id1 = 2;
                    $allWaterHolder->request_date = $request->request_date; 
                    $allWaterHolder->installation_date = $request->large_date;
                    $allWaterHolder->notes = $request->notes;
                    $allWaterHolder->save();
                }
    
                $gridPublicStructure->save();
            }
    
            if($request->grid_integration_small) {
    
                $gridPublicStructure->grid_integration_small = $request->grid_integration_small;
                $gridPublicStructure->small_date = $request->small_date;
    
                $exist = AllWaterHolder::where("public_structure_id", $request->household_id)->first();
    
                if($exist) {
    
                    $exist->water_system_id2 = 3;
                    $exist->save();
                } else {
    
                    $allWaterHolder = new AllWaterHolder();
                    $allWaterHolder->is_main = "Yes";
                    $allWaterHolder->public_structure_id = $request->household_id;
                    $allWaterHolder->community_id = $request->community_id[0];
                    $allWaterHolder->water_system_id2 = 3;
                    $allWaterHolder->request_date = $request->request_date; 
                    $allWaterHolder->installation_date = $request->large_date;
                    $allWaterHolder->notes = $request->notes;
                    $allWaterHolder->save();
                }
            }
            
            $gridPublicStructure->is_delivery = $request->is_delivery;
            $gridPublicStructure->is_paid = $request->is_paid;
            $gridPublicStructure->is_complete = $request->is_complete;
            $gridPublicStructure->save();
        } 
        
        return redirect()->back()->with('message', 'New User Added Successfully!');
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
        $allWaterHolder = AllWaterHolder::findOrFail($id);
        $h2oUser = H2oUser::where("household_id", $allWaterHolder->household_id)->first();
        $gridUser = GridUser::where('household_id', $allWaterHolder->household_id)->first();
        $h2oPublic = H2oPublicStructure::where("public_structure_id", $allWaterHolder->public_structure_id)->first();
        $gridPublic = GridPublicStructure::where('public_structure_id', $allWaterHolder->public_structure_id)->first();
        $networkUser = WaterNetworkUser::where('household_id', $allWaterHolder->household_id)->first();

        $h2oSharedPublic = H2oSharedPublicStructure::where("public_structure_id", $allWaterHolder->public_structure_id)->first();
        $h2oSharedUser = H2oSharedUser::where("household_id", $allWaterHolder->household_id)->first();;
        
        if($allWaterHolder->delete()) {

            if($h2oUser) $h2oUser->delete();
            if($gridUser) $gridUser->delete();
            if($h2oPublic) $h2oPublic->delete();
            if($gridPublic) $gridPublic->delete();
            if($networkUser) $networkUser->delete();
            if($h2oSharedUser) $h2oSharedUser->delete();
            if($h2oSharedPublic) $h2oSharedPublic->delete();

            $response['success'] = 1;
            $response['msg'] = 'Water Holder Deleted successfully'; 
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
        $households = DB::table('all_water_holders')
            ->join('households', 'all_water_holders.household_id', 'households.id')
            ->where("households.community_id", $community_id)
            ->select('households.id as id', 'households.english_name')
            ->get();
 
        if (!$community_id) {

            $html = '<option value="">Choose One...</option>';
        } else {

            $html = '<option value="">Select...</option>';
            $households = DB::table('all_water_holders')
                ->join('households', 'all_water_holders.household_id', 'households.id')
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
    public function getAllPublic($community_id)
    {
        $publics = DB::table('public_structures')
            ->where("public_structures.community_id", $community_id)
            ->select('public_structures.id', 'public_structures.english_name')
            ->get();
      
        if (!$community_id) {

            $html = '<option value="">Choose One...</option>';
        } else {

            $html = '<option value="">Select...</option>';
            $publics = DB::table('public_structures')
                ->where("public_structures.community_id", $community_id)
                ->select('public_structures.id', 'public_structures.english_name')
                ->get();

            foreach ($publics as $public) {
                $html .= '<option value="'.$public->id.'">'.$public->english_name.'</option>';
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
        $publics = DB::table('all_water_holders')
            ->join('public_structures', 'all_water_holders.public_structure_id', 'public_structures.id')
            ->where("all_water_holders.community_id", $community_id)
            ->select('public_structures.id', 'public_structures.english_name')
            ->get();
      
        if (!$community_id) {

            $html = '<option value="">Choose One...</option>';
        } else {

            $html = '<option value="">Select...</option>';
            $publics = DB::table('all_water_holders')
                ->join('public_structures', 'all_water_holders.public_structure_id', 'public_structures.id')
                ->where("all_water_holders.community_id", $community_id)
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
                
        return Excel::download(new WaterUserExport($request), 'Water Holder Report.xlsx');
    }
}