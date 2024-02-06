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
use App\Models\H2oUserDonor;
use App\Models\H2oMaintenanceCall;
use App\Models\H2oMaintenanceCallAction;
use App\Models\H2oMaintenanceCallUser;
use App\Models\Household;
use App\Models\WaterUser;
use App\Models\MaintenanceActionType;
use App\Models\MaintenanceH2oAction;
use App\Models\MaintenanceStatus;
use App\Models\MaintenanceType;
use App\Models\PublicStructure;
use App\Models\PublicStructureCategory;
use App\Exports\WaterMaintenanceExport;
use Auth;
use DB;
use Route;
use DataTables;
use Excel;

class H2oMaintenanceCallController extends Controller
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
                
                $data = DB::table('h2o_maintenance_calls')
                    ->leftJoin('households', 'h2o_maintenance_calls.household_id', 'households.id')
                    ->leftJoin('public_structures', 'h2o_maintenance_calls.public_structure_id', 
                        'public_structures.id')
                    ->join('communities', 'h2o_maintenance_calls.community_id', 'communities.id')
                    ->join('maintenance_types', 'h2o_maintenance_calls.maintenance_type_id', 
                        '=', 'maintenance_types.id')
                    ->join('maintenance_statuses', 'h2o_maintenance_calls.maintenance_status_id', 
                        '=', 'maintenance_statuses.id')
                    ->join('users', 'h2o_maintenance_calls.user_id', '=', 'users.id')
                    ->where('h2o_maintenance_calls.is_archived', 0)
                    ->select('h2o_maintenance_calls.id as id', 
                        'households.english_name as household_name', 
                        'date_of_call', 'date_completed', 'h2o_maintenance_calls.notes',
                        'maintenance_types.type', 'maintenance_statuses.name', 
                        'communities.english_name as community_name',
                        'h2o_maintenance_calls.created_at as created_at',
                        'h2o_maintenance_calls.updated_at as updated_at',
                        'users.name as user_name', 
                        'public_structures.english_name as public_name')
                    ->orderBy('h2o_maintenance_calls.date_of_call', 'DESC');

                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
                        $updateButton = "<a type='button' class='updateWaterMaintenance' data-id='".$row->id."' ><i class='fa-solid fa-pen-to-square text-success'></i></a>";
                        $deleteButton = "<a type='button' class='deleteWaterMaintenance' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";
                        $viewButton = "<a type='button' class='viewWaterMaintenance' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#viewWaterMaintenanceModal' ><i class='fa-solid fa-eye text-info'></i></a>";
    
                        if(Auth::guard('user')->user()->user_type_id == 1 || 
                            Auth::guard('user')->user()->user_type_id == 2 ||
                            Auth::guard('user')->user()->user_type_id == 5 ||
                            Auth::guard('user')->user()->user_type_id == 11) 
                        {
                                
                            return $viewButton. " ". $updateButton . " ". $deleteButton;
                        } else return $viewButton;
                    })
                    ->addColumn('holder', function($row) {

                        if($row->household_name != null) $holder = $row->household_name;
                        else if($row->public_name != null) $holder = $row->public_name;
                        else $holder = null;

                        return $holder;
                    })
                    ->filter(function ($instance) use ($request) {
                        if (!empty($request->get('search'))) {
                                $instance->where(function($w) use($request){
                                $search = $request->get('search');
                                $w->orWhere('communities.english_name', 'LIKE', "%$search%")
                                ->orWhere('communities.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('households.english_name', 'LIKE', "%$search%")
                                ->orWhere('public_structures.english_name', 'LIKE', "%$search%")
                                ->orWhere('public_structures.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('households.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('maintenance_statuses.name', 'LIKE', "%$search%")
                                ->orWhere('maintenance_types.type', 'LIKE', "%$search%")
                                ->orWhere('users.name', 'LIKE', "%$search%");
                            });
                        }
                    })
                ->rawColumns(['action', 'holder'])
                ->make(true);
            }
    
            $communities = Community::where('is_archived', 0)
                ->orderBy('english_name', 'ASC')
                ->get();
            $households = DB::table('h2o_users')
                ->LeftJoin('grid_users', 'h2o_users.household_id', '=', 'grid_users.household_id')
                ->join('households', 'h2o_users.household_id', 'households.id')
                ->where('h2o_users.is_archived', 0)
                ->orderBy('households.english_name', 'ASC')
                ->select('households.id as id', 'households.english_name')
                ->get();
    
            $maintenanceTypes = MaintenanceType::where('is_archived', 0)->get();
            $maintenanceStatuses = MaintenanceStatus::where('is_archived', 0)->get();
            $maintenanceH2oActions = MaintenanceH2oAction::where('is_archived', 0)->get();
            $publics = PublicStructure::where('is_archived', 0)->get();
            $users = User::where('is_archived', 0)->get();
            $publicCategories = PublicStructureCategory::where('is_archived', 0)->get();
    
            return view('users.water.maintenance.index', compact('maintenanceTypes', 'maintenanceStatuses',
                'maintenanceH2oActions', 'users', 'communities', 'households', 'publics',
                'publicCategories'));

        } else {

            return view('errors.not-found');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response 
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'community_id' => 'required',
            'maintenance_status_id' => 'required',
            'maintenance_type_id' => 'required',
            'maintenance_h2o_action_id' => 'required',
            'user_id' => 'required'
        ]);

        $maintenance = new H2oMaintenanceCall();
        if($request->household_id) {

            $maintenance->household_id = $request->household_id;
        }
        
        if($request->public_structure_id) {

            $maintenance->public_structure_id = $request->public_structure_id;
        }

        $maintenance->community_id = $request->community_id[0];
        $maintenance->date_of_call = $request->date_of_call;
        $maintenance->date_completed = $request->date_completed;
        $maintenance->maintenance_status_id = $request->maintenance_status_id;
        $maintenance->user_id = $request->user_id;
        $maintenance->maintenance_type_id = $request->maintenance_type_id;
        $maintenance->notes = $request->notes;
        $maintenance->save();

        $maintenanceId = $maintenance->id;

        if($request->maintenance_h2o_action_id) {
            for($i=0; $i < count($request->maintenance_h2o_action_id); $i++) {

                $h2oMaintenanceCallAction = new H2oMaintenanceCallAction();
                $h2oMaintenanceCallAction->maintenance_h2o_action_id = $request->maintenance_h2o_action_id[$i];
                $h2oMaintenanceCallAction->h2o_maintenance_call_id = $maintenanceId;
                $h2oMaintenanceCallAction->save();
            }
        } 

        if($request->performed_by) {
            for($i=0; $i < count($request->performed_by); $i++) {

                $h2oMaintenanceCallUser = new H2oMaintenanceCallUser();
                $h2oMaintenanceCallUser->user_id = $request->performed_by[$i];
                $h2oMaintenanceCallUser->h2o_maintenance_call_id = $maintenanceId;
                $h2oMaintenanceCallUser->save();
            }
        }

        return redirect()->back()
        ->with('message', 'New Maintenance Added Successfully!');
    }

    /**
     * View Edit page.
     *  
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) 
    {
        $waterMaintenance = H2oMaintenanceCall::findOrFail($id);

        $maintenanceTypes = MaintenanceType::where('is_archived', 0)->get();
        $maintenanceStatuses = MaintenanceStatus::where('is_archived', 0)->get();
        $maintenanceWaterActions = MaintenanceH2oAction::where('is_archived', 0)->get();
        $h2oActions = DB::table('h2o_maintenance_call_actions')
            ->join('h2o_maintenance_calls', 'h2o_maintenance_call_actions.h2o_maintenance_call_id', 
                'h2o_maintenance_calls.id')
            ->join('maintenance_h2o_actions', 'h2o_maintenance_call_actions.maintenance_h2o_action_id', 
                'maintenance_h2o_actions.id')
            ->where('h2o_maintenance_call_actions.h2o_maintenance_call_id', $waterMaintenance->id)
            ->where('h2o_maintenance_call_actions.is_archived', 0)
            ->select('h2o_maintenance_call_actions.id', 'maintenance_h2o_actions.maintenance_action_h2o')
            ->get();
        $users = User::where('is_archived', 0)->get(); 
        $performedUsers = DB::table('h2o_maintenance_call_users')
            ->join('h2o_maintenance_calls', 'h2o_maintenance_call_users.h2o_maintenance_call_id', 
                'h2o_maintenance_calls.id')
            ->join('users', 'h2o_maintenance_call_users.user_id', 'users.id')
            ->where('h2o_maintenance_call_users.h2o_maintenance_call_id', $waterMaintenance->id)
            ->where('h2o_maintenance_call_users.is_archived', 0)
            ->select('h2o_maintenance_call_users.id', 'users.name')
            ->get();

        return view('users.water.maintenance.edit', compact('waterMaintenance', 'users',
            'maintenanceTypes',  'maintenanceStatuses', 'maintenanceWaterActions', 'h2oActions',
            'performedUsers'));
    }

    /**
     * Update an existing resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request, int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $maintenance = H2oMaintenanceCall::findOrFail($id);

        $maintenance->date_of_call = $request->date_of_call;
        $maintenance->date_completed = $request->date_completed;
        $maintenance->maintenance_status_id = $request->maintenance_status_id;
        $maintenance->user_id = $request->user_id;
        $maintenance->maintenance_type_id = $request->maintenance_type_id;
        $maintenance->notes = $request->notes;
        $maintenance->save();

        $maintenanceId = $maintenance->id;

        if($request->actions) {
            if($request->actions) {
                for($i=0; $i < count($request->actions); $i++) {
    
                    $h2oMaintenanceCallAction = new H2oMaintenanceCallAction();
                    $h2oMaintenanceCallAction->maintenance_h2o_action_id = $request->actions[$i];
                    $h2oMaintenanceCallAction->h2o_maintenance_call_id = $maintenanceId;
                    $h2oMaintenanceCallAction->save();
                }
            }
        }
        
        if($request->new_actions) {
            if($request->new_actions) {
                for($i=0; $i < count($request->new_actions); $i++) {
    
                    $h2oMaintenanceCallAction = new H2oMaintenanceCallAction();
                    $h2oMaintenanceCallAction->maintenance_h2o_action_id = $request->new_actions[$i];
                    $h2oMaintenanceCallAction->h2o_maintenance_call_id = $maintenanceId;
                    $h2oMaintenanceCallAction->save();
                }
            }
        }

        if($request->users) {
            if($request->users) {
                for($i=0; $i < count($request->users); $i++) {
    
                    $h2oMaintenanceCallUser = new H2oMaintenanceCallUser();
                    $h2oMaintenanceCallUser->user_id = $request->users[$i];
                    $h2oMaintenanceCallUser->h2o_maintenance_call_id = $maintenanceId;
                    $h2oMaintenanceCallUser->save();
                }
            }
        }

        if($request->new_users) {
            if($request->new_users) {
                for($i=0; $i < count($request->new_users); $i++) {
    
                    $h2oMaintenanceCallUser = new H2oMaintenanceCallUser();
                    $h2oMaintenanceCallUser->user_id = $request->new_users[$i];
                    $h2oMaintenanceCallUser->h2o_maintenance_call_id = $maintenanceId;
                    $h2oMaintenanceCallUser->save();
                }
            }
        }
        
        return redirect('/water-maintenance')->with('message', 'Water Maintenance Updated Successfully!');
    }

    /**
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteMaintenanceWater(Request $request)
    {
        $id = $request->id;

        $h2oMaintenance = H2oMaintenanceCall::find($id);
        $h2oMaintenanceActions = H2oMaintenanceCallAction::where('h2o_maintenance_call_id', $h2oMaintenance->id)->get();

        if($h2oMaintenance) {

            if($h2oMaintenanceActions) {
                foreach($h2oMaintenanceActions as $h2oMaintenanceAction) {
                    $h2oMaintenanceAction->is_archived = 1;
                    $h2oMaintenanceAction->save();
                }
            }

            $h2oMaintenance->is_archived = 1;
            $h2oMaintenance->save();
            
            $response['success'] = 1;
            $response['msg'] = 'H2O Maintenance Deleted successfully'; 
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
    public function deleteH2oAction(Request $request)
    {
        $id = $request->id;

        $h2oMaintenance = H2oMaintenanceCallAction::find($id);

        if($h2oMaintenance) {

            $h2oMaintenance->is_archived = 1;
            $h2oMaintenance->save();
            
            $response['success'] = 1;
            $response['msg'] = 'H2O Maintenance Action Deleted successfully'; 
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
    public function deletePerformedUsers(Request $request)
    {
        $id = $request->id;

        $h2oPerformedBy = H2oMaintenanceCallUser::find($id);

        if($h2oPerformedBy) {

            $h2oPerformedBy->is_archived = 1;
            $h2oPerformedBy->save();
            
            $response['success'] = 1;
            $response['msg'] = 'H2O Maintenance User Deleted successfully'; 
        } else {

            $response['success'] = 0;
            $response['msg'] = 'Invalid ID.';
        }

        return response()->json($response); 
    }

    /**
     * Show the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $h2oMaintenance = H2oMaintenanceCall::findOrFail($id);
        
        if($h2oMaintenance->household_id != NULL) {
            $householdId = $h2oMaintenance->household_id;
            $household = Household::where('id', $householdId)->first();
            
            $response['household'] = $household;
        }

        if($h2oMaintenance->public_structure_id != NULL) {
            $publicId = $h2oMaintenance->public_structure_id;
            $public = PublicStructure::where('id', $publicId)->first();
            
            $response['public'] = $public;
        }
       
        $community = Community::where('id', $h2oMaintenance->community_id)->first();
        $h2oAction = DB::table('h2o_maintenance_call_actions')
            ->join('h2o_maintenance_calls', 'h2o_maintenance_call_actions.h2o_maintenance_call_id', 
                'h2o_maintenance_calls.id')
            ->join('maintenance_h2o_actions', 'h2o_maintenance_call_actions.maintenance_h2o_action_id', 
                'maintenance_h2o_actions.id')
            ->where('h2o_maintenance_call_actions.h2o_maintenance_call_id', $h2oMaintenance->id)
            ->where('h2o_maintenance_call_actions.is_archived', 0)
            ->get();
        $status = MaintenanceStatus::where('id', $h2oMaintenance->maintenance_status_id)->first();
        $type = MaintenanceType::where('id', $h2oMaintenance->maintenance_type_id)->first();
        $user = User::where('id', $h2oMaintenance->user_id)->first();
        $performedUsers = DB::table('h2o_maintenance_call_users')
            ->join('h2o_maintenance_calls', 'h2o_maintenance_call_users.h2o_maintenance_call_id', 
                'h2o_maintenance_calls.id')
            ->join('users', 'h2o_maintenance_call_users.user_id', 'users.id')
            ->where('h2o_maintenance_call_users.h2o_maintenance_call_id', $h2oMaintenance->id)
            ->where('h2o_maintenance_call_users.is_archived', 0)
            ->select('h2o_maintenance_call_users.id', 'users.name')
            ->get();

        $response['community'] = $community;
        $response['h2oMaintenance'] = $h2oMaintenance;
        $response['h2oAction'] = $h2oAction;
        $response['status'] = $status;
        $response['type'] = $type;
        $response['user'] = $user;
        $response['performedUsers'] = $performedUsers;

        return response()->json($response);
    }

    /**
     * 
     * @return \Illuminate\Support\Collection
     */
    public function export(Request $request)
    {
                
        return Excel::download(new WaterMaintenanceExport($request), 'water_maintenance.xlsx');
    }
}
