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
use App\Models\EnergyUser;
use App\Models\H2oUser;
use App\Models\H2oUserDonor;
use App\Models\NewElectricityMaintenanceCall;
use App\Models\Household;
use App\Models\WaterUser;
use App\Models\MaintenanceActionType;
use App\Models\MaintenanceNewElectricityAction;
use App\Models\MaintenanceStatus;
use App\Models\MaintenanceType;
use App\Models\PublicStructure;
use App\Exports\EnergyNewMaintenanceExport;
use Auth;
use DB;
use Route;
use DataTables;
use Excel;

class NewEnergyMaintenanceCallController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {	
        if ($request->ajax()) {
            $data = DB::table('new_electricity_maintenance_calls')
                ->leftJoin('households', 'new_electricity_maintenance_calls.household_id', 
                    'households.id')
                ->leftJoin('public_structures', 'new_electricity_maintenance_calls.public_structure_id', 
                    'public_structures.id')
                ->join('communities', 'new_electricity_maintenance_calls.community_id', 'communities.id')
                ->join('maintenance_types', 'new_electricity_maintenance_calls.maintenance_type_id', 
                    '=', 'maintenance_types.id')
                ->join('maintenance_new_electricity_actions', 
                    'new_electricity_maintenance_calls.maintenance_new_electricity_action_id', '=', 
                    'maintenance_new_electricity_actions.id')
                ->join('maintenance_statuses', 'new_electricity_maintenance_calls.maintenance_status_id', 
                    '=', 'maintenance_statuses.id')
                ->join('users', 'new_electricity_maintenance_calls.user_id', '=', 'users.id')
                ->select('new_electricity_maintenance_calls.id as id', 'households.english_name', 
                    'date_of_call', 'date_completed', 'new_electricity_maintenance_calls.notes',
                    'maintenance_types.type', 'maintenance_statuses.name', 
                    'communities.english_name as community_name',
                    'new_electricity_maintenance_calls.created_at as created_at',
                    'new_electricity_maintenance_calls.updated_at as updated_at',
                    'maintenance_new_electricity_actions.maintenance_action_new_electricity',
                    'maintenance_new_electricity_actions.maintenance_action_new_electricity_english',
                    'users.name as user_name', 'public_structures.english_name as public_name')
                ->latest();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row) {

                    $deleteButton = "<a type='button' class='deleteNewEnergyMaintenance' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";
                    $viewButton = "<a type='button' class='viewNewEnergyMaintenance' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#viewNewEnergyMaintenanceModal' ><i class='fa-solid fa-eye text-info'></i></a>";

                    return $deleteButton. " ". $viewButton;
                })
               
                ->filter(function ($instance) use ($request) {
                    if (!empty($request->get('search'))) {
                            $instance->where(function($w) use($request){
                            $search = $request->get('search');
                            $w->orWhere('communities.english_name', 'LIKE', "%$search%")
                            ->orWhere('communities.arabic_name', 'LIKE', "%$search%")
                            ->orWhere('households.english_name', 'LIKE', "%$search%")
                            ->orWhere('households.arabic_name', 'LIKE', "%$search%")
                            ->orWhere('maintenance_statuses.name', 'LIKE', "%$search%")
                            ->orWhere('maintenance_types.type', 'LIKE', "%$search%")
                            ->orWhere('users.name', 'LIKE', "%$search%");
                        });
                    }
                })
            ->rawColumns(['action'])
            ->make(true);
        }

        $communities = Community::all();
        $households = DB::table('energy_users')
            ->join('households', 'energy_users.household_id', 'households.id')
            ->select('households.id as id', 'households.english_name')
            ->get();

        $maintenanceTypes = MaintenanceType::all();
        $maintenanceStatuses = MaintenanceStatus::all();
        $maintenanceEnergyActions = MaintenanceNewElectricityAction::all();
        $publics = PublicStructure::all();
        $users = User::all();

		return view('users.energy.maintenance.new.index', compact('maintenanceTypes', 
            'maintenanceStatuses', 'maintenanceEnergyActions', 'users', 'communities', 
            'households', 'publics'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request->all());
        $maintenance = new NewElectricityMaintenanceCall();
        if($request->household_id) {

            $energyUserId = EnergyUser::where('household_id', $request->household_id[0])
                ->select('id')->get();
            $maintenance->household_id = $request->household_id[0];
            $maintenance->energy_user_id = $energyUserId[0]->id;
        }
        
        if($request->public_structure_id) {

            $maintenance->public_structure_id = $request->public_structure_id[0];
        }

        $maintenance->community_id = $request->community_id[0];
        $maintenance->date_of_call = $request->date_of_call;
        $maintenance->date_completed = $request->date_completed;
        $maintenance->maintenance_status_id = $request->maintenance_status_id;
        $maintenance->user_id = $request->user_id;
        $maintenance->maintenance_new_electricity_action_id = $request->maintenance_new_electricity_action_id;
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
    public function deleteNewMaintenanceEnergy(Request $request)
    {
        $id = $request->id;

        $energyMaintenance = NewElectricityMaintenanceCall::find($id);

        if($energyMaintenance->delete()) {

            $response['success'] = 1;
            $response['msg'] = 'Electricity Maintenance Deleted successfully'; 
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
        $energyMaintenance = NewElectricityMaintenanceCall::findOrFail($id);
        
        if($energyMaintenance->household_id != NULL) {
            $householdId = $energyMaintenance->household_id;
            $household = Household::where('id', $householdId)->first();
            
            $response['household'] = $household;
        }

        if($energyMaintenance->public_structure_id != NULL) {
            $publicId = $energyMaintenance->public_structure_id;
            $public = PublicStructure::where('id', $publicId)->first();
            
            $response['public'] = $public;
        }
       
        $community = Community::where('id', $energyMaintenance->community_id)->first();
        $energyAction = MaintenanceNewElectricityAction::where('id', $energyMaintenance->maintenance_new_electricity_action_id)->first();
        $status = MaintenanceStatus::where('id', $energyMaintenance->maintenance_status_id)->first();
        $type = MaintenanceType::where('id', $energyMaintenance->maintenance_type_id)->first();
        $user = User::where('id', $energyMaintenance->user_id)->first();

        $response['community'] = $community;
        $response['energyMaintenance'] = $energyMaintenance;
        $response['energyAction'] = $energyAction;
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
                
        return Excel::download(new EnergyNewMaintenanceExport, 'new_energy_maintenance.xlsx');
    }
}
