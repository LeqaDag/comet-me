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
use App\Models\Household;
use App\Models\WaterUser;
use App\Models\MaintenanceActionType;
use App\Models\MaintenanceH2oAction;
use App\Models\MaintenanceStatus;
use App\Models\MaintenanceType;
use App\Models\PublicStructure;
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
        if ($request->ajax()) {
            $data = DB::table('h2o_maintenance_calls')
                ->leftJoin('households', 'h2o_maintenance_calls.household_id', 'households.id')
                ->leftJoin('public_structures', 'h2o_maintenance_calls.public_structure_id', 
                    'public_structures.id')
                ->join('communities', 'h2o_maintenance_calls.community_id', 'communities.id')
                ->join('maintenance_types', 'h2o_maintenance_calls.maintenance_type_id', 
                    '=', 'maintenance_types.id')
                ->join('maintenance_h2o_actions', 'h2o_maintenance_calls.maintenance_h2o_action_id', 
                    '=', 'maintenance_h2o_actions.id')
                ->join('maintenance_statuses', 'h2o_maintenance_calls.maintenance_status_id', 
                    '=', 'maintenance_statuses.id')
                ->join('users', 'h2o_maintenance_calls.user_id', '=', 'users.id')
                ->select('h2o_maintenance_calls.id as id', 'households.english_name', 
                    'date_of_call', 'date_completed', 'h2o_maintenance_calls.notes',
                    'maintenance_types.type', 'maintenance_statuses.name', 
                    'communities.english_name as community_name',
                    'h2o_maintenance_calls.created_at as created_at',
                    'h2o_maintenance_calls.updated_at as updated_at',
                    'maintenance_h2o_actions.maintenance_action_h2o',
                    'maintenance_h2o_actions.maintenance_action_h2o_english',
                    'users.name as user_name', 'public_structures.english_name as public_name')
                ->latest();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row) {

                    $updateButton = "<a type='button' class='updateWaterMaintenance' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#updateMaintenanceModal' ><i class='fa-solid fa-pen-to-square text-success'></i></a>";
                    $deleteButton = "<a type='button' class='deleteWaterMaintenance' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";
                    $viewButton = "<a type='button' class='viewWaterMaintenance' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#viewWaterMaintenanceModal' ><i class='fa-solid fa-eye text-info'></i></a>";

                    return $updateButton." ".$deleteButton. " ". $viewButton;
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
                            ->orWhere('maintenance_h2o_actions.maintenance_action_h2o', 'LIKE', "%$search%")
                            ->orWhere('users.name', 'LIKE', "%$search%");
                        });
                    }
                })
            ->rawColumns(['action'])
            ->make(true);
        }

        $communities = Community::all();
        $households = DB::table('h2o_users')
            ->LeftJoin('grid_users', 'h2o_users.household_id', '=', 'grid_users.household_id')
            ->join('households', 'h2o_users.household_id', 'households.id')
            ->select('households.id as id', 'households.english_name')
            ->get();

        $maintenanceTypes = MaintenanceType::all();
        $maintenanceStatuses = MaintenanceStatus::all();
        $maintenanceH2oActions = MaintenanceH2oAction::all();
        $publics = PublicStructure::all();
        $users = User::all();

		return view('users.water.maintenance.index', compact('maintenanceTypes', 'maintenanceStatuses',
            'maintenanceH2oActions', 'users', 'communities', 'households', 'publics'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response 
     */
    public function store(Request $request)
    {
        $maintenance = new H2oMaintenanceCall();
        if($request->household_id) {

            $maintenance->household_id = $request->household_id[0];
        }
        
        if($request->public_structure_id) {

            $maintenance->public_structure_id = $request->public_structure_id[0];
        }

        $maintenance->community_id = $request->community_id[0];
        $maintenance->date_of_call = $request->date_of_call;
        $maintenance->date_completed = $request->date_completed;
        $maintenance->maintenance_status_id = $request->maintenance_status_id;
        $maintenance->user_id = $request->user_id;
        $maintenance->maintenance_h2o_action_id = $request->maintenance_h2o_action_id;
        $maintenance->maintenance_type_id = $request->maintenance_type_id;
        $maintenance->notes = $request->notes;
        $maintenance->save();

        return redirect()->back()
        ->with('message', 'New Maintenance Added Successfully!');
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

        if($h2oMaintenance->delete()) {

            $response['success'] = 1;
            $response['msg'] = 'H2O Maintenance Deleted successfully'; 
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
        $h2oAction = MaintenanceH2oAction::where('id', $h2oMaintenance->maintenance_h2o_action_id)->first();
        $status = MaintenanceStatus::where('id', $h2oMaintenance->maintenance_status_id)->first();
        $type = MaintenanceType::where('id', $h2oMaintenance->maintenance_type_id)->first();
        $user = User::where('id', $h2oMaintenance->user_id)->first();

        $response['community'] = $community;
        $response['h2oMaintenance'] = $h2oMaintenance;
        $response['h2oAction'] = $h2oAction;
        $response['status'] = $status;
        $response['type'] = $type;
        $response['user'] = $user;

        return response()->json($response);
    }

    /**
     * 
     * @return \Illuminate\Support\Collection
     */
    public function export() 
    {
                
        return Excel::download(new WaterMaintenanceExport, 'water_maintenance.xlsx');
    }
}
