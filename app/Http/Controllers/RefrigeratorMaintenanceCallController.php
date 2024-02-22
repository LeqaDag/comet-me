<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\User;
use App\Models\Community;
use App\Models\RefrigeratorMaintenanceCall;
use App\Models\RefrigeratorMaintenanceCallAction;
use App\Models\RefrigeratorMaintenanceCallUser;
use App\Models\Household;
use App\Models\MaintenanceActionType;
use App\Models\MaintenanceRefrigeratorAction;
use App\Models\MaintenanceStatus;
use App\Models\MaintenanceType;
use App\Models\PublicStructure;
use App\Models\PublicStructureCategory;
use App\Exports\RefrigeratorMaintenanceExport;
use Auth;
use DB;
use Route;
use DataTables;
use Excel;

class RefrigeratorMaintenanceCallController extends Controller
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
                $data = DB::table('refrigerator_maintenance_calls')
                    ->leftJoin('households', 'refrigerator_maintenance_calls.household_id', 'households.id')
                    ->leftJoin('public_structures', 'refrigerator_maintenance_calls.public_structure_id', 
                        'public_structures.id')
                    ->join('communities', 'refrigerator_maintenance_calls.community_id', 'communities.id')
                    ->join('maintenance_types', 'refrigerator_maintenance_calls.maintenance_type_id', 
                        '=', 'maintenance_types.id')
                    ->join('maintenance_statuses', 'refrigerator_maintenance_calls.maintenance_status_id', 
                        '=', 'maintenance_statuses.id')
                    ->join('users', 'refrigerator_maintenance_calls.user_id', '=', 'users.id')
                    ->leftJoin('refrigerator_maintenance_call_actions', 'refrigerator_maintenance_calls.id', 
                        'refrigerator_maintenance_call_actions.refrigerator_maintenance_call_id')
                    ->leftJoin('maintenance_refrigerator_actions', 
                        'refrigerator_maintenance_call_actions.maintenance_refrigerator_action_id', 
                        '=', 'maintenance_refrigerator_actions.id')
                    ->where('refrigerator_maintenance_calls.is_archived', 0)
                    ->select('refrigerator_maintenance_calls.id as id', 
                        'households.english_name as household_name', 
                        'date_of_call', 'date_completed', 'refrigerator_maintenance_calls.notes',
                        'maintenance_types.type', 'maintenance_statuses.name', 
                        'communities.english_name as community_name',
                        'refrigerator_maintenance_calls.created_at as created_at',
                        'refrigerator_maintenance_calls.updated_at as updated_at',
                        'users.name as user_name', 'public_structures.english_name as public_name')
                    ->latest();
                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
    
                        $updateButton = "<a type='button' class='updateRefrigeratorMaintenance' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#updateRefrigeratorMaintenanceModal' ><i class='fa-solid fa-pen-to-square text-success'></i></a>";
                        $deleteButton = "<a type='button' class='deleteRefrigeratorMaintenance' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";
                        $viewButton = "<a type='button' class='viewRefrigeratorMaintenance' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#viewRefrigeratorMaintenanceModal' ><i class='fa-solid fa-eye text-info'></i></a>";
    
                        if(Auth::guard('user')->user()->user_type_id == 1 || 
                            Auth::guard('user')->user()->user_type_id == 2 ||
                            Auth::guard('user')->user()->user_type_id == 3 ||
                            Auth::guard('user')->user()->user_type_id == 4 ||
                            Auth::guard('user')->user()->user_type_id == 7) 
                        {
                                
                            return $viewButton. " ".$updateButton. " ". $deleteButton ;
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
                                ->orWhere('public_structures.english_name', 'LIKE', "%$search%")
                                ->orWhere('public_structures.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('households.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('maintenance_statuses.name', 'LIKE', "%$search%")
                                ->orWhere('maintenance_types.type', 'LIKE', "%$search%")
                                ->orWhere('users.name', 'LIKE', "%$search%")
                                ->orWhere('maintenance_refrigerator_actions.maintenance_action_refrigerator', 'LIKE', "%$search%")
                                ->orWhere('maintenance_refrigerator_actions.maintenance_action_refrigerator_english', 'LIKE', "%$search%");
                            });
                        }
                    })
                ->rawColumns(['action', 'holder'])
                ->make(true);
            }
    
            $communities = Community::where('is_archived', 0)
                ->orderBy('arabic_name', 'ASC')
                ->get();
            $households = DB::table('refrigerator_holders')
                ->where('refrigerator_holders.is_archived', 0)
                ->join('households', 'refrigerator_holders.household_id', 'households.id')
                ->orderBy('households.english_name', 'ASC')
                ->select('households.id as id', 'households.english_name')
                ->get();
    
            $maintenanceTypes = MaintenanceType::where('is_archived', 0)->get();
            $maintenanceStatuses = MaintenanceStatus::where('is_archived', 0)->get();
            $maintenanceRefrigeratorActions = MaintenanceRefrigeratorAction::where('is_archived', 0)->get();
            $publics = DB::table('refrigerator_holders')
                ->where('refrigerator_holders.is_archived', 0)
                ->join('public_structures', 'refrigerator_holders.public_structure_id', 'public_structures.id')
                ->select('public_structures.id as id', 'public_structures.english_name')
                ->get();
            $users = User::where('is_archived', 0)->get();
            $publicCategories = PublicStructureCategory::where('is_archived', 0)->get();
    
            return view('users.refrigerator.maintenance.index', compact('maintenanceTypes', 
                'maintenanceStatuses', 'maintenanceRefrigeratorActions', 'users', 'communities', 
                'households', 'publics', 'publicCategories'));
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
            'maintenance_refrigerator_action_id' => 'required',
            'user_id' => 'required'
        ]);

        $maintenance = new RefrigeratorMaintenanceCall();
        if($request->household_id) {

            $maintenance->household_id = $request->household_id;
            $household = Household::where("id", $request->household_id)->first();
            $household->phone_number = $request->phone_number;
            $household->save();
        }
        
        if($request->public_structure_id) {

            $maintenance->public_structure_id = $request->public_structure_id;
        }

        $maintenance->community_id = $request->community_id[0];
        $maintenance->date_of_call = $request->date_of_call;
        $maintenance->visit_date = $request->visit_date;
        if($request->date_completed) {

            $maintenance->date_completed = $request->date_completed;
            $maintenance->maintenance_status_id = 3;
        } else if($request->date_completed == null)  {

            $maintenance->date_completed = null;
            $maintenance->maintenance_status_id = $request->maintenance_status_id;
        } 
        $maintenance->user_id = $request->user_id;
        $maintenance->maintenance_type_id = $request->maintenance_type_id;
        $maintenance->notes = $request->notes;
        $maintenance->save();

        $maintenanceId = $maintenance->id;

        if($request->maintenance_refrigerator_action_id) {
            for($i=0; $i < count($request->maintenance_refrigerator_action_id); $i++) {

                $h2oMaintenanceCallAction = new RefrigeratorMaintenanceCallAction();
                $h2oMaintenanceCallAction->maintenance_refrigerator_action_id = $request->maintenance_refrigerator_action_id[$i];
                $h2oMaintenanceCallAction->refrigerator_maintenance_call_id = $maintenanceId;
                $h2oMaintenanceCallAction->save();
            }
        }

        if($request->performed_by) {
            for($i=0; $i < count($request->performed_by); $i++) {

                $h2oMaintenanceCallUser = new RefrigeratorMaintenanceCallUser();
                $h2oMaintenanceCallUser->user_id = $request->performed_by[$i];
                $h2oMaintenanceCallUser->refrigerator_maintenance_call_id = $maintenanceId;
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
        $refrigeratorMaintenance = RefrigeratorMaintenanceCall::findOrFail($id);
        $actions = "";

        $maintenanceTypes = MaintenanceType::where('is_archived', 0)->get();
        $maintenanceStatuses = MaintenanceStatus::where('is_archived', 0)->get();
        $maintenanceRefrigeratorActions = MaintenanceRefrigeratorAction::where('is_archived', 0)->get();

        $refrigeratorActions = DB::table('refrigerator_maintenance_call_actions')
            ->join('refrigerator_maintenance_calls', 'refrigerator_maintenance_call_actions.refrigerator_maintenance_call_id', 
                'refrigerator_maintenance_calls.id')
            ->join('maintenance_refrigerator_actions', 'refrigerator_maintenance_call_actions.maintenance_refrigerator_action_id', 
                'maintenance_refrigerator_actions.id')
            ->where('refrigerator_maintenance_call_actions.refrigerator_maintenance_call_id', $refrigeratorMaintenance->id)
            ->where('refrigerator_maintenance_call_actions.is_archived', 0)
            ->select('refrigerator_maintenance_call_actions.id', 'maintenance_refrigerator_actions.maintenance_action_refrigerator')
            ->get();

        $performedUsers = DB::table('refrigerator_maintenance_call_users')
            ->join('refrigerator_maintenance_calls', 'refrigerator_maintenance_call_users.refrigerator_maintenance_call_id', 
                'refrigerator_maintenance_calls.id')
            ->join('users', 'refrigerator_maintenance_call_users.user_id', 'users.id')
            ->where('refrigerator_maintenance_call_users.refrigerator_maintenance_call_id', $refrigeratorMaintenance->id)
            ->where('refrigerator_maintenance_call_users.is_archived', 0)
            ->select('refrigerator_maintenance_call_users.id', 'users.name')
            ->get();

        $users = User::where('is_archived', 0)->get();

        return view('users.refrigerator.maintenance.edit', compact('refrigeratorMaintenance',
            'maintenanceTypes',  'maintenanceStatuses', 'maintenanceRefrigeratorActions', 
            'refrigeratorActions', 'performedUsers', 'users'));
    }

    /**
     * Update an existing resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request, int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $maintenance = RefrigeratorMaintenanceCall::findOrFail($id);

        $maintenance->date_of_call = $request->date_of_call;
        $maintenance->visit_date = $request->visit_date;
        if($request->date_completed) {

            $maintenance->date_completed = $request->date_completed;
            $maintenance->maintenance_status_id = 3;
        } else if($request->date_completed == null)  {

            $maintenance->date_completed = null;
        } else {

            $maintenance->maintenance_status_id = $request->maintenance_status_id;
        }
        $maintenance->user_id = $request->user_id;
        $maintenance->maintenance_type_id = $request->maintenance_type_id;
        $maintenance->notes = $request->notes;
        $maintenance->save();

        if($maintenance->household_id) {

            $household = Household::where("id", $maintenance->household_id)->first();
            $household->phone_number = $request->phone_number;
            $household->save();
        }

        if($request->actions) {
            for($i=0; $i < count($request->actions); $i++) {

                $h2oMaintenanceCallAction = new RefrigeratorMaintenanceCallAction();
                $h2oMaintenanceCallAction->maintenance_refrigerator_action_id = $request->actions[$i];
                $h2oMaintenanceCallAction->refrigerator_maintenance_call_id = $id;
                $h2oMaintenanceCallAction->save();
            }
        }

        if($request->new_actions) {
            for($i=0; $i < count($request->new_actions); $i++) {

                $h2oMaintenanceCallAction = new RefrigeratorMaintenanceCallAction();
                $h2oMaintenanceCallAction->maintenance_refrigerator_action_id = $request->new_actions[$i];
                $h2oMaintenanceCallAction->refrigerator_maintenance_call_id = $id;
                $h2oMaintenanceCallAction->save();
            }
        }

        if($request->users) {
            if($request->users) {
                for($i=0; $i < count($request->users); $i++) {
    
                    $refrigeratorMaintenanceCallUser = new RefrigeratorMaintenanceCallUser();
                    $refrigeratorMaintenanceCallUser->user_id = $request->users[$i];
                    $refrigeratorMaintenanceCallUser->refrigerator_maintenance_call_id = $id;
                    $refrigeratorMaintenanceCallUser->save();
                }
            }
        }

        if($request->new_users) {
            if($request->new_users) {
                for($i=0; $i < count($request->new_users); $i++) {
    
                    $refrigeratorMaintenanceCallUser = new RefrigeratorMaintenanceCallUser();
                    $refrigeratorMaintenanceCallUser->user_id = $request->new_users[$i];
                    $refrigeratorMaintenanceCallUser->refrigerator_maintenance_call_id = $id;
                    $refrigeratorMaintenanceCallUser->save();
                }
            }
        }
        
        return redirect('/refrigerator-maintenance')->with('message', 'Refrigerator Maintenance Updated Successfully!');
    }

    /**
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteRefrigerator(Request $request)
    {
        $id = $request->id;

        $maintenance = RefrigeratorMaintenanceCall::find($id);
        $maintenanceActions = RefrigeratorMaintenanceCallAction::where('', $householdId)->get();

        if($maintenance) {

            $maintenance->is_archived = 1;
            $maintenance->save();

            $response['success'] = 1;
            $response['msg'] = 'Refrigerator Maintenance Deleted successfully'; 
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
    public function deleteRefrigeratorAction(Request $request)
    {
        $id = $request->id;

        $refrigeratorMaintenance = RefrigeratorMaintenanceCallAction::find($id);

        if($refrigeratorMaintenance) {

            $refrigeratorMaintenance->is_archived = 1;
            $refrigeratorMaintenance->save();
            
            $response['success'] = 1;
            $response['msg'] = 'Refrigerator Maintenance Action Deleted successfully'; 
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
    public function deletePerformedRefrigeratorUsers(Request $request)
    {
        $id = $request->id;

        $refrigeratorPerformedBy = RefrigeratorMaintenanceCallUser::find($id);

        if($refrigeratorPerformedBy) {

            $refrigeratorPerformedBy->is_archived = 1;
            $refrigeratorPerformedBy->save();
            
            $response['success'] = 1;
            $response['msg'] = 'Refrigerator Maintenance User Deleted successfully'; 
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
        $refrigeratorMaintenance = RefrigeratorMaintenanceCall::findOrFail($id);
        
        if($refrigeratorMaintenance->household_id != NULL) {
            $householdId = $refrigeratorMaintenance->household_id;
            $household = Household::where('id', $householdId)->first();
            
            $response['household'] = $household;
        }

        if($refrigeratorMaintenance->public_structure_id != NULL) {
            $publicId = $refrigeratorMaintenance->public_structure_id;
            $public = PublicStructure::where('id', $publicId)->first();
            
            $response['public'] = $public;
        }
       
        $community = Community::where('id', $refrigeratorMaintenance->community_id)->first();
        $refrigeratorAction = MaintenanceRefrigeratorAction::where('id', 
            $refrigeratorMaintenance->maintenance_refrigerator_action_id)->first();
        $status = MaintenanceStatus::where('id', $refrigeratorMaintenance->maintenance_status_id)
            ->first();
        $type = MaintenanceType::where('id', $refrigeratorMaintenance->maintenance_type_id)
            ->first();
        $user = User::where('id', $refrigeratorMaintenance->user_id)->first();

        $refrigeratorActions = DB::table('refrigerator_maintenance_call_actions')
            ->join('refrigerator_maintenance_calls', 'refrigerator_maintenance_call_actions.refrigerator_maintenance_call_id', 
                'refrigerator_maintenance_calls.id')
            ->join('maintenance_refrigerator_actions', 'refrigerator_maintenance_call_actions.maintenance_refrigerator_action_id', 
                'maintenance_refrigerator_actions.id')
            ->where('refrigerator_maintenance_call_actions.refrigerator_maintenance_call_id', $refrigeratorMaintenance->id)
            ->where('refrigerator_maintenance_call_actions.is_archived', 0)
            ->select('refrigerator_maintenance_call_actions.id', 'maintenance_refrigerator_actions.maintenance_action_refrigerator')
            ->get();

        $performedUsers = DB::table('refrigerator_maintenance_call_users')
            ->join('refrigerator_maintenance_calls', 'refrigerator_maintenance_call_users.refrigerator_maintenance_call_id', 
                'refrigerator_maintenance_calls.id')
            ->join('users', 'refrigerator_maintenance_call_users.user_id', 'users.id')
            ->where('refrigerator_maintenance_call_users.refrigerator_maintenance_call_id', $refrigeratorMaintenance->id)
            ->where('refrigerator_maintenance_call_users.is_archived', 0)
            ->select('refrigerator_maintenance_call_users.id', 'users.name')
            ->get();

        $response['community'] = $community;
        $response['refrigeratorMaintenance'] = $refrigeratorMaintenance;
        $response['refrigeratorAction'] = $refrigeratorAction;
        $response['status'] = $status;
        $response['type'] = $type;
        $response['user'] = $user;
        $response['refrigeratorActions'] = $refrigeratorActions;
        $response['performedUsers'] = $performedUsers;

        return response()->json($response);
    }

    /**
     * 
     * @return \Illuminate\Support\Collection
     */
    public function export(Request $request)
    {
                
        return Excel::download(new RefrigeratorMaintenanceExport($request), 'refrigerator_maintenance.xlsx');
    }
}
