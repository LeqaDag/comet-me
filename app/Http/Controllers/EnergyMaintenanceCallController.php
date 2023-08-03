<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\AllEnergyMeter;
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
use App\Models\EnergySystem;
use App\Models\ElectricityMaintenanceCall;
use App\Models\ElectricityMaintenanceCallUser;
use App\Models\Household;
use App\Models\WaterUser;
use App\Models\MaintenanceActionType;
use App\Models\MaintenanceElectricityAction;
use App\Models\MaintenanceStatus;
use App\Models\MaintenanceType;
use App\Models\PublicStructure;
use App\Models\PublicStructureCategory;
use App\Exports\EnergyMaintenanceExport;
use App\Imports\ImportEnergyMaintenance;
use Auth;
use DB;
use Route;
use DataTables;
use Excel;

class EnergyMaintenanceCallController extends Controller
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
                
                $data = DB::table('electricity_maintenance_calls')
                    ->leftJoin('energy_systems', 'electricity_maintenance_calls.energy_system_id', 
                        'energy_systems.id')
                    ->leftJoin('households', 'electricity_maintenance_calls.household_id', 
                        'households.id')
                    ->leftJoin('public_structures', 'electricity_maintenance_calls.public_structure_id', 
                        'public_structures.id')
                    ->join('communities', 'electricity_maintenance_calls.community_id', 'communities.id')
                    ->join('maintenance_types', 'electricity_maintenance_calls.maintenance_type_id', 
                        '=', 'maintenance_types.id')
                    ->join('maintenance_electricity_actions', 'electricity_maintenance_calls.maintenance_electricity_action_id', 
                        '=', 'maintenance_electricity_actions.id')
                    ->join('maintenance_statuses', 'electricity_maintenance_calls.maintenance_status_id', 
                        '=', 'maintenance_statuses.id')
                    ->join('users', 'electricity_maintenance_calls.user_id', '=', 'users.id')
                    ->where('electricity_maintenance_calls.is_archived', 0)
                    ->select('electricity_maintenance_calls.id as id', 
                        'households.english_name as english_name', 
                        'date_of_call', 'date_completed', 'electricity_maintenance_calls.notes',
                        'maintenance_types.type', 'maintenance_statuses.name', 
                        'communities.english_name as community_name',
                        'electricity_maintenance_calls.created_at as created_at',
                        'electricity_maintenance_calls.updated_at as updated_at',
                        'maintenance_electricity_actions.maintenance_action_electricity',
                        'maintenance_electricity_actions.maintenance_action_electricity_english',
                        'users.name as user_name', 'public_structures.english_name as public_name',
                        'energy_systems.name as energy_name')
                    ->latest();
                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
    
                        $deleteButton = "<a type='button' class='deleteEnergyMaintenance' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";
                        $viewButton = "<a type='button' class='viewEnergyMaintenance' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#viewEnergyMaintenanceModal' ><i class='fa-solid fa-eye text-info'></i></a>";
                        $updateButton = "<a type='button' class='updateEnergyMaintenance' data-id='".$row->id."'><i class='fa-solid fa-pen-to-square text-success'></i></a>";
    
                        if(Auth::guard('user')->user()->user_type_id == 1 || 
                            Auth::guard('user')->user()->user_type_id == 2 ||
                            Auth::guard('user')->user()->user_type_id == 4 ||
                            Auth::guard('user')->user()->user_type_id == 7) 
                        {
                                
                            return $viewButton. " ". $updateButton . " ".$deleteButton ;
                        } else return $viewButton;
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
    
            $communities = Community::where('is_archived', 0)
                ->orderBy('english_name', 'ASC')
                ->get();
            $households = DB::table('all_energy_meters')
                ->where('all_energy_meters.is_archived', 0)
                ->join('households', 'all_energy_meters.household_id', 'households.id')
                ->orderBy('households.english_name', 'ASC')
                ->select('households.id as id', 'households.english_name')
                ->get();
            $publics = PublicStructure::where('is_archived', 0)->get();
            $maintenanceTypes = MaintenanceType::where('is_archived', 0)->get();
            $maintenanceStatuses = MaintenanceStatus::where('is_archived', 0)->get();
            $maintenanceEnergyActions = MaintenanceElectricityAction::where('is_archived', 0)->get();
            $users = User::where('is_archived', 0)->get();
            $mgSystems = EnergySystem::where('is_archived', 0)
                ->get();
            $publicCategories = PublicStructureCategory::where('is_archived', 0)->get();
    
            return view('users.energy.maintenance.index', compact('maintenanceTypes', 
                'maintenanceStatuses', 'maintenanceEnergyActions', 'users', 'communities', 
                'households', 'publics', 'mgSystems', 'publicCategories'));
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
            'maintenance_electricity_action_id' => 'required',
            'user_id' => 'required'
        ]);

        $maintenance = new ElectricityMaintenanceCall();
        if($request->household_id) {

            $energyUserId = AllEnergyMeter::where('household_id', $request->household_id)
                ->select('id')
                ->get();
        
            $maintenance->household_id = $request->household_id;
            $maintenance->energy_user_id = $energyUserId[0]->id;
        }
    
        if($request->public_structure_id) {

            $maintenance->public_structure_id = $request->public_structure_id;
        }

        if($request->energy_system_id) {

            $maintenance->energy_system_id = $request->energy_system_id;
        }

        $maintenance->community_id = $request->community_id[0];
        $maintenance->date_of_call = $request->date_of_call;
        $maintenance->date_completed = $request->date_completed;
        $maintenance->maintenance_status_id = $request->maintenance_status_id;
        $maintenance->user_id = $request->user_id;
        $maintenance->maintenance_electricity_action_id = $request->maintenance_electricity_action_id;
        $maintenance->maintenance_type_id = $request->maintenance_type_id;
        $maintenance->notes = $request->notes;
        $maintenance->save();

        $maintenanceId = $maintenance->id;

        if($request->performed_by) {
            for($i=0; $i < count($request->performed_by); $i++) {

                $h2oMaintenanceCallUser = new ElectricityMaintenanceCallUser();
                $h2oMaintenanceCallUser->user_id = $request->performed_by[$i];
                $h2oMaintenanceCallUser->electricity_maintenance_call_id = $maintenanceId;
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
        $energyMaintenance = ElectricityMaintenanceCall::findOrFail($id);
        $actions = "";

        if($energyMaintenance->household_id || $energyMaintenance->public_structure_id) {

            $actions = MaintenanceElectricityAction::where('is_archived', 0)
                ->where("system_user", 1)
                ->orWhere("system_user", 3)
                ->get();
        } else if($energyMaintenance->energy_system_id) {
            $actions = MaintenanceElectricityAction::where('is_archived', 0)
                ->where("system_user", 2)
                ->orWhere("system_user", 3)
                ->get();
        } 

        $maintenanceTypes = MaintenanceType::where('is_archived', 0)->get();
        $maintenanceStatuses = MaintenanceStatus::where('is_archived', 0)->get();
        $maintenanceEnergyActions = MaintenanceElectricityAction::where('is_archived', 0)->get();
        $users = User::where('is_archived', 0)->get();
        $performedUsers = DB::table('electricity_maintenance_call_users')
            ->join('electricity_maintenance_calls', 'electricity_maintenance_call_users.electricity_maintenance_call_id', 
                'electricity_maintenance_calls.id')
            ->join('users', 'electricity_maintenance_call_users.user_id', 'users.id')
            ->where('electricity_maintenance_call_users.electricity_maintenance_call_id', $energyMaintenance->id)
            ->where('electricity_maintenance_call_users.is_archived', 0)
            ->select('electricity_maintenance_call_users.id', 'users.name')
            ->get();

        return view('users.energy.maintenance.edit', compact('energyMaintenance', 
            'maintenanceTypes', 'maintenanceStatuses', 'maintenanceEnergyActions', 
            'users', 'actions', 'performedUsers'));
    }

    /**
     * Update an existing resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request, int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $energyMaintenance = ElectricityMaintenanceCall::findOrFail($id);

        $energyMaintenance->date_of_call = $request->date_of_call;
        $energyMaintenance->date_completed = $request->date_completed;
        $energyMaintenance->maintenance_status_id = $request->maintenance_status_id;
        $energyMaintenance->user_id = $request->user_id;
        $energyMaintenance->maintenance_electricity_action_id = $request->maintenance_electricity_action_id;
        $energyMaintenance->maintenance_type_id = $request->maintenance_type_id;
        $energyMaintenance->notes = $request->notes;
        $energyMaintenance->save();
        $maintenanceId = $energyMaintenance->id;

        if($request->users) {
            if($request->users) {
                for($i=0; $i < count($request->users); $i++) {
    
                    $energyMaintenanceCallUser = new ElectricityMaintenanceCallUser();
                    $energyMaintenanceCallUser->user_id = $request->users[$i];
                    $energyMaintenanceCallUser->electricity_maintenance_call_id = $maintenanceId;
                    $energyMaintenanceCallUser->save();
                }
            }
        }

        if($request->new_users) {
            if($request->new_users) {
                for($i=0; $i < count($request->new_users); $i++) {
    
                    $energyMaintenanceCallUser = new ElectricityMaintenanceCallUser();
                    $energyMaintenanceCallUser->user_id = $request->new_users[$i];
                    $energyMaintenanceCallUser->electricity_maintenance_call_id = $maintenanceId;
                    $energyMaintenanceCallUser->save();
                }
            }
        }

        return redirect('/energy-maintenance')->with('message', 'Energy Maintenance Updated Successfully!');
    }

    /**
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteMaintenanceEnergy(Request $request)
    {
        $id = $request->id;

        $energyMaintenance = ElectricityMaintenanceCall::find($id);

        if($energyMaintenance) {

            $energyMaintenance->is_archived = 1;
            $energyMaintenance->save();

            $response['success'] = 1;
            $response['msg'] = 'Electricity Maintenance Deleted successfully'; 
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
    public function deletePerformedEnergyUsers(Request $request)
    {
        $id = $request->id;

        $energyPerformedBy = ElectricityMaintenanceCallUser::find($id);

        if($energyPerformedBy) {

            $energyPerformedBy->is_archived = 1;
            $energyPerformedBy->save();
            
            $response['success'] = 1;
            $response['msg'] = 'Energy Maintenance User Deleted successfully'; 
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
    public function getEnergySystem($community_id)
    {
        if($community_id == 0) {

            $energySystems = EnergySystem::where('energy_system_type_id', 2)->get();
        } else {

            $energySystems = EnergySystem::where('community_id', $community_id)->get();
        }

        if (!$community_id) {

            $html = '<option value="">Choose One...</option>';
        } else {
 
            $html = '';
            if($community_id == 0) {

                $energySystems = EnergySystem::where('energy_system_type_id', 2)->get();
            } else {
                
                $energySystems = EnergySystem::where('community_id', $community_id)->get();
            }

            foreach ($energySystems as $energyType) {
                $html .= '<option value="'.$energyType->id.'">'.$energyType->name.'</option>';
            }
        }

        return response()->json(['html' => $html]);
    }

    /**
     * Show the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $energyMaintenance = ElectricityMaintenanceCall::findOrFail($id);
        
        if($energyMaintenance->energy_system_id != NULL) {
            $energyId = $energyMaintenance->energy_system_id;
            $energySystem = EnergySystem::where('id', $energyId)->first();
            
            $response['energySystem'] = $energySystem;
        }

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
        $energyAction = MaintenanceElectricityAction::where('id', $energyMaintenance->maintenance_electricity_action_id)->first();
        $status = MaintenanceStatus::where('id', $energyMaintenance->maintenance_status_id)->first();
        $type = MaintenanceType::where('id', $energyMaintenance->maintenance_type_id)->first();
        $user = User::where('id', $energyMaintenance->user_id)->first();
        $performedUsers = DB::table('electricity_maintenance_call_users')
            ->join('electricity_maintenance_calls', 'electricity_maintenance_call_users.electricity_maintenance_call_id', 
                'electricity_maintenance_calls.id')
            ->join('users', 'electricity_maintenance_call_users.user_id', 'users.id')
            ->where('electricity_maintenance_call_users.electricity_maintenance_call_id', $energyMaintenance->id)
            ->where('electricity_maintenance_call_users.is_archived', 0)
            ->select('electricity_maintenance_call_users.id', 'users.name')
            ->get();

        $response['community'] = $community;
        $response['energyMaintenance'] = $energyMaintenance;
        $response['energyAction'] = $energyAction;
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
                
        return Excel::download(new EnergyMaintenanceExport($request), 'energy_maintenance.xlsx');
    }

    /**
     * 
     * @return \Illuminate\Support\Collection
     */
    public function import(Request $request)
    {
        Excel::import(new ImportEnergyMaintenance, $request->file('file')); 
            
        return back()->with('success', 'Excel Data Imported successfully.');
    }

    /**
     * Get resources from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getMaintenanceAction($system)
    {
        if($system == 1) {

            $actions = MaintenanceElectricityAction::where('is_archived', 0)
                ->where("system_user", 2)
                ->orWhere("system_user", 3)
                ->get();
        } else if($system == 2) {

            $actions = MaintenanceElectricityAction::where('is_archived', 0)
                ->where("system_user", 1)
                ->orWhere("system_user", 3)
                ->get();
        }
        
 
        if (!$system) {

            $html = '<option value="">Choose One...</option>';
        } else {

            $html = '<option disabled selected>Select ...</option>';
            if($system == 1) {

                $actions = MaintenanceElectricityAction::where('is_archived', 0)
                    ->where("system_user", 2)
                    ->orWhere("system_user", 3)
                    ->get();
            } else if($system == 2) {
    
                $actions = MaintenanceElectricityAction::where('is_archived', 0)
                    ->where("system_user", 1)
                    ->orWhere("system_user", 3)
                    ->get();
            }

            foreach ($actions as $action) {
                $html .= '<option value="'.$action->id.'">'.$action->maintenance_action_electricity.'</option>';
            }
        }

        return response()->json(['html' => $html]);
    }
}