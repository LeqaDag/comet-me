<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\User;
use App\Models\Community;
use App\Models\RefrigeratorMaintenanceCall;
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
                    ->join('maintenance_refrigerator_actions', 'refrigerator_maintenance_calls.maintenance_refrigerator_action_id', 
                        '=', 'maintenance_refrigerator_actions.id')
                    ->join('maintenance_statuses', 'refrigerator_maintenance_calls.maintenance_status_id', 
                        '=', 'maintenance_statuses.id')
                    ->join('users', 'refrigerator_maintenance_calls.user_id', '=', 'users.id')
                    ->select('refrigerator_maintenance_calls.id as id', 'households.english_name', 
                        'date_of_call', 'date_completed', 'refrigerator_maintenance_calls.notes',
                        'maintenance_types.type', 'maintenance_statuses.name', 
                        'communities.english_name as community_name',
                        'refrigerator_maintenance_calls.created_at as created_at',
                        'refrigerator_maintenance_calls.updated_at as updated_at',
                        'maintenance_refrigerator_actions.maintenance_action_refrigerator',
                        'maintenance_refrigerator_actions.maintenance_action_refrigerator_english',
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
                                ->orWhere('maintenance_refrigerator_actions.maintenance_action_refrigerator', 'LIKE', "%$search%")
                                ->orWhere('users.name', 'LIKE', "%$search%");
                            });
                        }
                    })
                ->rawColumns(['action'])
                ->make(true);
            }
    
            $communities = Community::all();
            $households = DB::table('refrigerator_holders')
                ->join('households', 'refrigerator_holders.household_id', 'households.id')
                ->select('households.id as id', 'households.english_name')
                ->get();
    
            $maintenanceTypes = MaintenanceType::all();
            $maintenanceStatuses = MaintenanceStatus::all();
            $maintenanceRefrigeratorActions = MaintenanceRefrigeratorAction::all();
            $publics = DB::table('refrigerator_holders')
                ->join('public_structures', 'refrigerator_holders.public_structure_id', 'public_structures.id')
                ->select('public_structures.id as id', 'public_structures.english_name')
                ->get();
            $users = User::all();
            $publicCategories = PublicStructureCategory::all();
    
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
        }
        
        if($request->public_structure_id) {

            $maintenance->public_structure_id = $request->public_structure_id;
        }

        $maintenance->community_id = $request->community_id[0];
        $maintenance->date_of_call = $request->date_of_call;
        $maintenance->date_completed = $request->date_completed;
        $maintenance->maintenance_status_id = $request->maintenance_status_id;
        $maintenance->user_id = $request->user_id;
        $maintenance->maintenance_refrigerator_action_id = $request->maintenance_refrigerator_action_id ;
        $maintenance->maintenance_type_id = $request->maintenance_type_id;
        $maintenance->notes = $request->notes;
        $maintenance->save();

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

        $maintenanceTypes = MaintenanceType::all();
        $maintenanceStatuses = MaintenanceStatus::all();
        $maintenanceRefrigeratorActions = MaintenanceRefrigeratorAction::all();

        $users = User::all();

        return view('users.refrigerator.maintenance.edit', compact('refrigeratorMaintenance', 'users',
            'maintenanceTypes',  'maintenanceStatuses', 'maintenanceRefrigeratorActions'));
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
        $maintenance->date_completed = $request->date_completed;
        $maintenance->maintenance_status_id = $request->maintenance_status_id;
        $maintenance->user_id = $request->user_id;
        $maintenance->maintenance_refrigerator_action_id = $request->maintenance_refrigerator_action_id ;
        $maintenance->maintenance_type_id = $request->maintenance_type_id;
        $maintenance->notes = $request->notes;
        $maintenance->save();

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

        if($maintenance->delete()) {

            $response['success'] = 1;
            $response['msg'] = 'Refrigerator Maintenance Deleted successfully'; 
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

        $response['community'] = $community;
        $response['refrigeratorMaintenance'] = $refrigeratorMaintenance;
        $response['refrigeratorAction'] = $refrigeratorAction;
        $response['status'] = $status;
        $response['type'] = $type;
        $response['user'] = $user;

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
