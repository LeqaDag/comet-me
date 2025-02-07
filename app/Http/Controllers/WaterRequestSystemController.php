<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use DB; 
use Route;
use App\Models\AllEnergyMeter;
use App\Models\AllWaterHolder;
use App\Models\AllEnergyMeterDonor;
use App\Models\User; 
use App\Models\Community;
use App\Models\EnergySystemType;
use App\Models\CommunityService;
use App\Models\WaterRequestStatus;
use App\Models\WaterSystemStatus;
use App\Models\WaterSystemType;
use App\Models\WaterRequestSystem;
use App\Models\WaterSystemCycle;
use App\Models\WaterHolderStatus;
use App\Models\Household;
use App\Models\PublicStructure;
use App\Models\InstallationType;
use App\Models\MeterCase;
use App\Models\Region;
use App\Exports\WaterRequestSystemExport;
use App\Exports\Water\WaterProgressExport;
use Carbon\Carbon;
use Image;
use Excel;
use DataTables;

class WaterRequestSystemController extends Controller
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

                $dataPublic = DB::table('water_request_systems')
                    ->leftJoin('households', 'water_request_systems.household_id', 'households.id')
                    ->leftJoin('public_structures', 'water_request_systems.public_structure_id', 'public_structures.id')
                    ->join('communities', 'water_request_systems.community_id', 'communities.id')
                    ->leftJoin('water_request_statuses', 'water_request_systems.water_request_status_id', 
                        'water_request_statuses.id')
                    ->leftJoin('all_energy_meters as users', 'users.household_id', 'households.id')
                    ->leftJoin('all_energy_meters as publics', 'publics.public_structure_id', 'public_structures.id')
                    ->where('water_request_systems.is_archived', 0) 
                    ->select(
                        'water_request_systems.date', 
                        'water_request_systems.id as id', 'water_request_systems.created_at as created_at', 
                        'water_request_systems.updated_at as updated_at', 
                        'communities.english_name as community_name', 'water_request_statuses.name',
                        DB::raw('IFNULL(users.meter_number, publics.meter_number) 
                            as meter_number'),  
                        DB::raw('IFNULL(users.is_main, publics.is_main) 
                            as is_main'),
                        DB::raw('IFNULL(households.english_name, public_structures.english_name) 
                            as holder'))
                    ->orderBy('water_request_systems.date', 'asc');
                 
                return Datatables::of($dataPublic)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
    
                        $moveButton = "<a type='button' title='Start Working' class='moveWaterRequest' data-id='".$row->id."'><i class='fa-solid fa-arrow-right text-warning'></i></a>";
                        $viewButton = "<a type='button' class='viewWaterRequest' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#viewWaterRequestModal' ><i class='fa-solid fa-eye text-info'></i></a>";
                        $updateButton = "<a type='button' class='updateWaterRequest' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#updateWaterUserModal' ><i class='fa-solid fa-pen-to-square text-success'></i></a>";
                        $deleteButton = "<a type='button' class='deleteWaterRequest' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";
    
                        if(Auth::guard('user')->user()->user_type_id == 1 || 
                            Auth::guard('user')->user()->user_type_id == 2 ||
                            Auth::guard('user')->user()->user_type_id == 5 ||
                            Auth::guard('user')->user()->user_type_id == 11) 
                        {
                                
                            return $moveButton." ". $viewButton." ". $updateButton." ".$deleteButton;
                        } else return $viewButton;
       
                    })
                    ->filter(function ($instance) use ($request) {
                        if (!empty($request->get('search'))) {
                                $instance->where(function($w) use($request) {
                                $search = $request->get('search');
                                $w->orWhere('communities.english_name', 'LIKE', "%$search%")
                                ->orWhere('communities.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('water_request_systems.date', 'LIKE', "%$search%")
                                ->orWhere('households.english_name', 'LIKE', "%$search%")
                                ->orWhere('households.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('water_request_statuses.name', 'LIKE', "%$search%");
                            });
                        }
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
    
            $communities = Community::where('is_archived', 0)
                ->orderBy('english_name', 'ASC')
                ->get();
            $households = Household::where('is_archived', 0)
                ->orderBy('english_name', 'ASC')
                ->get();
            $requestStatuses = WaterRequestStatus::where('is_archived', 0)
                ->orderBy('name', 'ASC')
                ->get();

            return view('request.water.index', compact('communities', 'households',
                'requestStatuses'));
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
        $communities = Community::where('is_archived', 0)
            ->orderBy('english_name', 'ASC')
            ->get();
        $requestStatuses = WaterRequestStatus::where('is_archived', 0)
            ->orderBy('name', 'ASC')
            ->get();
        $waterSystemTypes = WaterSystemType::orderBy('type', 'ASC')->get();
        $waterSystemStatuses = WaterSystemStatus::where('is_archived', 0)
            ->orderBy('status', 'ASC')
            ->get();
        $waterCycleYears = WaterSystemCycle::orderBy('name', 'ASC')
            ->get();

        return view('request.water.create', compact('communities', 'requestStatuses', 'waterSystemTypes',
            'waterSystemStatuses', 'waterCycleYears'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $waterRequestSystem = new WaterRequestSystem();
        $waterRequestSystem->community_id = $request->community_id;
        if($request->public_user == "user") $waterRequestSystem->household_id = $request->household_public_id;
        else if($request->public_user == "public") $waterRequestSystem->public_structure_id = $request->household_public_id;
        $waterRequestSystem->water_request_status_id = $request->water_request_status_id;
        $waterRequestSystem->date = $request->date;
        $waterRequestSystem->water_system_type_id = $request->water_system_type_id;
        $waterRequestSystem->water_system_status_id = $request->water_system_status_id;
        $waterRequestSystem->water_system_cycle_id = $request->water_system_cycle_id;
        $waterRequestSystem->referred_by = $request->referred_by;
        $waterRequestSystem->notes = $request->notes;
        $waterRequestSystem->save();

        return redirect('/water-request')
            ->with('message', 'New Water Requested System Added Successfully!');
    }

    /**
     * Show the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    { 
        $waterRequestSystem = WaterRequestSystem::findOrFail($id);
        $waterRequestSystemType = WaterSystemType::findOrFail($waterRequestSystem->water_system_type_id);
        $waterRequestStatus = WaterRequestStatus::findOrFail($waterRequestSystem->water_request_status_id);

        $energyMeter = null; 
        $household = null;
        $public = null;
        $meter = null;
        $systemType = null;
        $newReplacnment = null;
        $cycleYear = null;
        $holderStatus = null;

        if($waterRequestSystem->household_id) {

            $energyMeter = AllEnergyMeter::where("is_archived", 0)
                ->where("household_id", $waterRequestSystem->household_id)
                ->first();
            $household = Household::where('id', $waterRequestSystem->household_id)->first();
            if($household->water_holder_status_id) $holderStatus = WaterHolderStatus::findOrFail($household->water_holder_status_id);
        } else if($waterRequestSystem->public_structure_id) {

            $energyMeter = AllEnergyMeter::where("is_archived", 0)
                ->where("public_structure_id", $waterRequestSystem->public_structure_id)
                ->first();
            $public = PublicStructure::where('id', $waterRequestSystem->public_structure_id)->first();
            if($public->water_holder_status_id) $holderStatus = WaterHolderStatus::findOrFail($public->water_holder_status_id);
        } 

        $community = Community::where('id', $waterRequestSystem->community_id)->first();

        if($energyMeter) {

            $meter = MeterCase::where('id', $energyMeter->meter_case_id)->first();
            $systemType = EnergySystemType::where('id', $energyMeter->energy_system_type_id)->first();
        }

        if($waterRequestSystem->water_system_cycle_id) $cycleYear = WaterSystemCycle::findOrFail($waterRequestSystem->water_system_cycle_id);
        
        if($waterRequestSystem->water_system_status_id) $newReplacnment = WaterSystemStatus::findOrFail($waterRequestSystem->water_system_status_id);

        $response['energy'] = $energyMeter;
        $response['community'] = $community;
        $response['household'] = $household;
        $response['public'] = $public;
        $response['meter'] = $meter;
        $response['type'] = $systemType;
        $response['waterRequestSystem'] = $waterRequestSystem;
        $response['waterRequestSystemType'] = $waterRequestSystemType;
        $response['waterRequestStatus'] = $waterRequestStatus;
        $response['cycleYear'] = $cycleYear;
        $response['newReplacnment'] = $newReplacnment;
        $response['holderStatus'] = $holderStatus;

        return response()->json($response);
    }

    /**
     * View Edit page.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) 
    {
        $waterRequestSystem = WaterRequestSystem::findOrFail($id);
        $requestStatuses = WaterRequestStatus::where('is_archived', 0)
            ->orderBy('name', 'ASC')
            ->get();
        $waterSystemTypes = WaterSystemType::orderBy('type', 'ASC')->get();
        $waterSystemStatuses = WaterSystemStatus::where('is_archived', 0)
            ->orderBy('status', 'ASC')
            ->get();

        $households = null;
        $publics = null;
        if($waterRequestSystem->household_id) {

            $households = Household::where('is_archived', 0)
                ->where('community_id', $waterRequestSystem->community_id)
                ->get();
        } else if($waterRequestSystem->public_structure_id) {

            $publics = PublicStructure::where('is_archived', 0)
                ->where('community_id', $waterRequestSystem->community_id)
                ->get();
        } 

        $waterCycleYears = WaterSystemCycle::orderBy('name', 'ASC')
            ->get();

        $waterHolderStatues = WaterHolderStatus::where('is_archived', 0)->get();

        return view('request.water.edit', compact('waterRequestSystem', 'requestStatuses', 'waterSystemTypes',
            'households', 'publics', 'waterSystemStatuses', 'waterCycleYears', 'waterHolderStatues'));
    }
    
    /**
     * Update an existing resource in storage.
     *
     * @param  \Illuminate\Http\Request $request, int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $waterRequestSystem = WaterRequestSystem::findOrFail($id);
        if($request->water_request_status_id) $waterRequestSystem->water_request_status_id = $request->water_request_status_id;
        if($request->date) $waterRequestSystem->date = $request->date;
        if($request->water_system_type_id) $waterRequestSystem->water_system_type_id = $request->water_system_type_id;
        if($request->water_system_status_id) $waterRequestSystem->water_system_status_id = $request->water_system_status_id;
        if($request->water_system_cycle_id) $waterRequestSystem->water_system_cycle_id = $request->water_system_cycle_id;
        if($request->referred_by) $waterRequestSystem->referred_by = $request->referred_by;
        if($request->notes) $waterRequestSystem->notes = $request->notes;
        $waterRequestSystem->save();
        
        if($request->water_holder_status_id) {

            if($waterRequestSystem->household_id) {

                $household = Household::findOrFail($waterRequestSystem->household_id);
                $household->water_holder_status_id = $request->water_holder_status_id;
                $household->save();
            } else if($waterRequestSystem->public_structure_id) {

                $public = PublicStructure::findOrFail($waterRequestSystem->public_structure_id);
                $public->water_holder_status_id = $request->water_holder_status_id;
                $public->save();
            }
        }

        return redirect('/water-request')->with('message', 'Requested Water Holder Updated Successfully!');
    }

    /**
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteRequestedWaterSystem(Request $request)
    {
        $id = $request->id;

        $waterRequestSystem = WaterRequestSystem::find($id);

        if($waterRequestSystem) {

            $waterRequestSystem->is_archived = 1;
            $waterRequestSystem->save();

            $response['success'] = 1;
            $response['msg'] = 'Water Requested Household Deleted successfully'; 
        } else {

            $response['success'] = 0;
            $response['msg'] = 'Invalid ID.';
        }

        return response()->json($response); 
    }

    /**
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function moveRequestedWaterSystem(Request $request)
    {
        $id = $request->id;

        $waterRequestSystem = WaterRequestSystem::find($id);

        $existCommunityService = CommunityService::where("community_id", $waterRequestSystem->community_id)
            ->where("service_id", 2)
            ->first();
            
        if($existCommunityService) {

        } else {

            $communityService = new CommunityService();
            $communityService->service_id = 2;
            $communityService->community_id = $waterRequestSystem->community_id;
            $communityService->save();
        }

        if($waterRequestSystem->household_id) {

            $exist = AllWaterHolder::where("household_id", $waterRequestSystem->household_id)->first();
            if($exist) {

            } else {

                $newHolder = new AllWaterHolder();
                $newHolder->household_id = $waterRequestSystem->household_id;
                $newHolder->community_id = $waterRequestSystem->community_id;
                $newHolder->is_main = "Yes";
                $newHolder->request_date = $waterRequestSystem->date;
                $newHolder->save();

                $household = Household::findOrFail($waterRequestSystem->household_id);
                $household->water_service = "Yes";
                $household->water_system_status = "Served";
                $household->save();
            }
        }
        
        if($waterRequestSystem->public_structure_id) {

            $newHolder = new AllWaterHolder();
            $newHolder->public_structure_id = $waterRequestSystem->public_structure_id;
            $newHolder->community_id = $waterRequestSystem->community_id;
            $newHolder->is_main = "Yes";
            $newHolder->request_date = $waterRequestSystem->date;
            $newHolder->save();
        }

        $waterRequestSystem->is_archived = 1;
        $waterRequestSystem->save();

        $response['success'] = 1;
        $response['msg'] = 'Water Requested Holder moved to the water holders list successfully'; 

        return response()->json($response); 
    }

    /**
     * Get households by community_id.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getRequestedByCommunity(int $id)
    {
        if (!$id) {

            $html = '<option selected disabled>Choose One...</option>';
        } else {

            $html = '<option selected disabled>Choose One...</option>';
            $households = Household::where('community_id', $id)
                ->where('is_archived', 0)
                ->where('internet_holder_young', 0)
                ->orderBy('english_name', 'ASC')
                ->get();

            foreach ($households as $household) {
                $html .= '<option value="'.$household->id.'">'.$household->english_name.'</option>';
            }
        }

        return response()->json(['html' => $html]);
    }

    /**
     * Get households by community_id.
     *
     * @param  int $id, String $is_household
     * @return \Illuminate\Http\Response
     */
    public function getDetailsByHouseholdPublic(int $id, String $is_household)
    {
        if (!$id) {

            $energyUser = '';
            $waterDetails = '';
        } else {

            if($is_household == "user") {

                $energyUser = AllEnergyMeter::where('household_id', $id)
                    ->where('is_archived', 0)
                    ->select('is_main', 'meter_number')
                    ->first();

                $waterDetails = AllWaterHolder::where('household_id', $id)
                    ->where('is_archived', 0)
                    ->first();
            } else if($is_household == "public") {

                $energyUser = AllEnergyMeter::where('public_structure_id', $id)
                    ->where('is_archived', 0)
                    ->select('is_main', 'meter_number')
                    ->first();

                $waterDetails = AllWaterHolder::where('public_structure_id', $id)
                    ->where('is_archived', 0)
                    ->first();
            }
        }

        return response()->json([
            'energyUser' => $energyUser,
            'waterDetails' => $waterDetails
        ]);
    }

    /**
     * 
     * @return \Illuminate\Support\Collection
     */
    public function export(Request $request) 
    {
                
        return Excel::download(new WaterRequestSystemExport($request), 'Requested Water.xlsx');
    }

    /**
     * 
     * @return \Illuminate\Support\Collection
     */
    public function exportProgress(Request $request) 
    {
                
        return Excel::download(new WaterProgressExport($request), 'Water Progress Report.xlsx');
    }
}