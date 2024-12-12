<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Http;
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
use App\Models\ElectricityMaintenanceCallAction;
use App\Models\EnergyMaintenanceAction;
use App\Models\EnergyMaintenanceIssue;
use App\Models\EnergyTurbineCommunity; 
use App\Models\EnergyGeneratorCommunity;
use App\Models\EnergyMaintenanceIssueType; 
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
        // 75 -> 16 / 24 -> 52 / 40 -> 42 / 14 -> 4
        // 11 -> 8 / 13 -> 1 / 17 -> 79 / 39 -> 17
        // 27 -> 41 / 12 -> 56 / 43 -> 19 / 57 -> 44
        // 19 -> 15 / 72 -> 22 / 18 -> 12 / 77 -> 7
        // 78 -> 48 / 20 -> 5 / 16 -> 5 / 30 -> 32
        // 79 -> 80 / 80 -> 39 / 8 -> 10 / 7 -> 42
        // 48 -> 24 / 36 -> 31 / 9 -> 81 / 76 -> 82
        // 15 -> 83 /  21 -> 84 / 1 -> 85 / 54 -> 86
        // 34 & 10 -> 8 / 45 -> 35 / 4 -> 88
        // 52 -> 89 / 38-> 11
        
        // $actions = ElectricityMaintenanceCallAction::where('maintenance_electricity_action_id', 
        //     52)->get();
        // foreach($actions as $action) {

        //     $action->energy_maintenance_action_id = 89;
        //     $action->save();
        // }

        $communityFilter = $request->input('community_filter');
        $publicFilter = $request->input('public_filter');
        $issueFilter = $request->input('issue_filter');
        $dateFilter = $request->input('date_filter');

        if (Auth::guard('user')->user() != null) {
  
            if ($request->ajax()) {
                
                $data = DB::table('electricity_maintenance_calls')
                    ->leftJoin('energy_systems', 'electricity_maintenance_calls.energy_system_id', 
                        'energy_systems.id')
                    ->leftJoin('households', 'electricity_maintenance_calls.household_id', 
                        'households.id')
                    ->leftJoin('public_structures', 'electricity_maintenance_calls.public_structure_id', 
                        'public_structures.id')
                    ->leftJoin('energy_turbine_communities', 'electricity_maintenance_calls.energy_turbine_community_id', 
                        'energy_turbine_communities.id')
                    ->leftJoin('energy_generator_communities', 'electricity_maintenance_calls.energy_generator_community_id', 
                        'energy_generator_communities.id')
                    ->join('communities', 'electricity_maintenance_calls.community_id', 'communities.id')
                    ->join('maintenance_types', 'electricity_maintenance_calls.maintenance_type_id', 
                        'maintenance_types.id')
                    ->join('maintenance_statuses', 'electricity_maintenance_calls.maintenance_status_id', 
                        'maintenance_statuses.id')
                    ->join('users', 'electricity_maintenance_calls.user_id', 'users.id')
                    ->where('electricity_maintenance_calls.is_archived', 0);


                    if($communityFilter != null) {

                        $data->where('communities.id', $communityFilter);
                    }
                    if ($publicFilter != null) {
     
                        $data->where("public_structures.public_structure_category_id1", $publicFilter)
                            ->orWhere("public_structures.public_structure_category_id2", $publicFilter)
                            ->orWhere("public_structures.public_structure_category_id3", $publicFilter);
                    } 
                    if($issueFilter != null) {

                        $data->join('electricity_maintenance_call_actions', 
                            'electricity_maintenance_calls.id', 
                            'electricity_maintenance_call_actions.electricity_maintenance_call_id')
                            ->join('energy_maintenance_actions', 'energy_maintenance_actions.id',
                                'electricity_maintenance_call_actions.energy_maintenance_action_id')
                            ->where('energy_maintenance_actions.energy_maintenance_issue_id', $issueFilter);
                    }
                    if ($dateFilter != null) {
    
                        $data->where('electricity_maintenance_calls.date_completed', '>=', $dateFilter);
                    }
 
                    $data->select(
                        'electricity_maintenance_calls.id as id', 
                        'households.english_name as household_name', 
                        'date_of_call', 'date_completed', 'electricity_maintenance_calls.notes',
                        'maintenance_types.type', 'maintenance_statuses.name', 
                        'communities.english_name as community_name',
                        'electricity_maintenance_calls.created_at as created_at',
                        'electricity_maintenance_calls.updated_at as updated_at',
                        'users.name as user_name', 'public_structures.english_name as public_name',
                        'energy_turbine_communities.name as turbine', 
                        'energy_generator_communities.name as generator',
                        'energy_systems.name as energy_name'
                    )
                    ->distinct()
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
                    ->addColumn('holder', function($row) {

                        if($row->household_name != null) $holder = $row->household_name;
                        else if($row->public_name != null) $holder = $row->public_name;
                        else if($row->energy_name !=null) $holder = $row->energy_name;
                        else if($row->turbine !=null) $holder = $row->turbine;
                        else if($row->generator !=null) $holder = $row->generator;

                        return $holder;
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
                ->rawColumns(['action', 'holder'])
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
    
            $userActions = EnergyMaintenanceAction::where("energy_maintenance_issue_type_id", 2)
                ->orWhere("energy_maintenance_issue_type_id", 3)
                ->get();

            $systemActions = EnergyMaintenanceAction::where("energy_maintenance_issue_type_id", 1)
                ->orWhere("energy_maintenance_issue_type_id", 3)
                ->get();

            $energyIssues = EnergyMaintenanceIssue::all();
            $energyIssueTypes = EnergyMaintenanceIssueType::all();

            // This code is to get the tickets from the API
            $ticketsData =  Http::get('https://cometme.org/api/tickets');
            $ticketMaintainances = json_decode($ticketsData, true);

           // dd($ticketMaintainances);

            return view('users.energy.maintenance.index', compact('maintenanceTypes', 
                'maintenanceStatuses', 'maintenanceEnergyActions', 'users', 'communities', 
                'households', 'publics', 'mgSystems', 'publicCategories', 'userActions',
                'systemActions', 'energyIssues', 'energyIssueTypes'));
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
            'user_id' => 'required'
        ]);
  
        $maintenance = new ElectricityMaintenanceCall();
        $maintenance->maintenance_status_id = $request->maintenance_status_id;

        if($request->flag == "system") {

            $maintenance->energy_system_id = $request->agent_id;
        } else if($request->flag == "mg_user" || $request->flag == "fbs_user") {

            $energyUserId = AllEnergyMeter::where('household_id', $request->agent_id)
                ->select('id')
                ->get();

            $maintenance->household_id = $request->agent_id;
            $maintenance->energy_user_id = $energyUserId[0]->id;
        } else if($request->flag == "mg_public" || $request->flag == "fbs_public") {
            
            $maintenance->public_structure_id = $request->agent_id;
        } else if($request->flag == "turbine") {
            
            $maintenance->energy_turbine_community_id = $request->agent_id;
        } else if($request->flag == "generator") {
            
            $maintenance->energy_generator_community_id = $request->agent_id;
        } 

        $maintenance->community_id = $request->community_id[0];
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
        $maintenance->last_hour = $request->last_hour;
        $maintenance->run_hour = $request->run_hour;
        $maintenance->run_performed_hour = $request->run_performed_hour;
        $maintenance->notes = $request->notes;
        $maintenance->save();

        $maintenanceId = $maintenance->id;

        if($request->action_ids) {
            for($i=0; $i < count($request->action_ids); $i++) {

                $electricityMaintenanceCallAction = new ElectricityMaintenanceCallAction();
                $electricityMaintenanceCallAction->energy_maintenance_action_id = $request->action_ids[$i];
                $electricityMaintenanceCallAction->electricity_maintenance_call_id = $maintenanceId;
                $electricityMaintenanceCallAction->save();
            }
        }

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
        $allEnergyActions = "";

        if($energyMaintenance->household_id || $energyMaintenance->public_structure_id) {

            $allEnergyActions = EnergyMaintenanceAction::where("energy_maintenance_issue_type_id", 2)
                ->orWhere("energy_maintenance_issue_type_id", 3)
                ->get();
        } else if($energyMaintenance->energy_system_id) {

            $allEnergyActions = EnergyMaintenanceAction::where("energy_maintenance_issue_type_id", 1)
                ->orWhere("energy_maintenance_issue_type_id", 3)
                ->get();
        } else if($energyMaintenance->energy_turbine_community_id) {

            $allEnergyActions = EnergyMaintenanceAction::where("energy_maintenance_issue_type_id", 4)
                ->get();
        } else if($energyMaintenance->energy_generator_community_id) {

            $allEnergyActions = EnergyMaintenanceAction::where("energy_maintenance_issue_type_id", 5)
                ->get();
        } 

        $maintenanceTypes = MaintenanceType::where('is_archived', 0)->get();
        $maintenanceStatuses = MaintenanceStatus::where('is_archived', 0)->get();
        $users = User::where('is_archived', 0)->get();
        
        $performedUsers = DB::table('electricity_maintenance_call_users')
            ->join('electricity_maintenance_calls', 'electricity_maintenance_call_users.electricity_maintenance_call_id', 
                'electricity_maintenance_calls.id')
            ->join('users', 'electricity_maintenance_call_users.user_id', 'users.id')
            ->where('electricity_maintenance_call_users.electricity_maintenance_call_id', $energyMaintenance->id)
            ->where('electricity_maintenance_call_users.is_archived', 0)
            ->select('electricity_maintenance_call_users.id', 'users.name')
            ->get();

        $energyActions = DB::table('electricity_maintenance_call_actions')
            ->join('electricity_maintenance_calls', 'electricity_maintenance_calls.id',
                'electricity_maintenance_call_actions.electricity_maintenance_call_id')
            ->join('maintenance_electricity_actions', 'maintenance_electricity_actions.id',
                'electricity_maintenance_call_actions.maintenance_electricity_action_id')
            ->where('electricity_maintenance_calls.id', 
                $energyMaintenance->id)
            ->where('electricity_maintenance_call_actions.is_archived', 0)
            ->select('electricity_maintenance_call_actions.id', 
                'maintenance_electricity_actions.maintenance_action_electricity')
            ->get();

        $energyMaintanceActions = DB::table('electricity_maintenance_call_actions')
            ->join('electricity_maintenance_calls', 'electricity_maintenance_calls.id',
                'electricity_maintenance_call_actions.electricity_maintenance_call_id')
            ->join('energy_maintenance_actions', 'energy_maintenance_actions.id',
                'electricity_maintenance_call_actions.energy_maintenance_action_id')
            ->where('electricity_maintenance_calls.id', 
                $energyMaintenance->id)
            ->where('electricity_maintenance_call_actions.is_archived', 0)
            ->select('electricity_maintenance_call_actions.id', 
                'energy_maintenance_actions.english_name')
            ->get();

        return view('users.energy.maintenance.edit', compact('energyMaintenance', 
            'maintenanceTypes', 'maintenanceStatuses', 'users', 'performedUsers', 
            'energyActions', 'energyMaintanceActions', 'allEnergyActions'));
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
        $energyMaintenance->visit_date = $request->visit_date;
        if($request->date_completed) {

            $energyMaintenance->date_completed = $request->date_completed;
            $energyMaintenance->maintenance_status_id = 3;
        } else if($request->date_completed == null)  {

            $energyMaintenance->date_completed = null;
            $energyMaintenance->maintenance_status_id = $request->maintenance_status_id;
        }
        
        $energyMaintenance->user_id = $request->user_id;
        $energyMaintenance->maintenance_type_id = $request->maintenance_type_id;
        $energyMaintenance->notes = $request->notes;
        
        if($request->last_hour) $energyMaintenance->last_hour = $request->last_hour;
        if($request->run_hour) $energyMaintenance->run_hour = $request->run_hour;
        if($request->run_performed_hour) $energyMaintenance->run_performed_hour = $request->run_performed_hour;

        $energyMaintenance->save();
        $maintenanceId = $energyMaintenance->id;

        if($request->actions) {
            if($request->actions) {
                for($i=0; $i < count($request->actions); $i++) {
    
                    $energyMaintenanceCallAction = new ElectricityMaintenanceCallAction();
                    $energyMaintenanceCallAction->energy_maintenance_action_id = $request->actions[$i];
                    $energyMaintenanceCallAction->electricity_maintenance_call_id = $maintenanceId;
                    $energyMaintenanceCallAction->save();
                }
            }
        }
         
        if($request->new_actions) {
            if($request->new_actions) {
                for($i=0; $i < count($request->new_actions); $i++) {
    
                    $energyMaintenanceCallAction = new ElectricityMaintenanceCallAction();
                    $energyMaintenanceCallAction->energy_maintenance_action_id = $request->new_actions[$i];
                    $energyMaintenanceCallAction->electricity_maintenance_call_id = $maintenanceId;
                    $energyMaintenanceCallAction->save();
                }
            }
        }

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
    public function deleteEnergyAction(Request $request)
    {
        $id = $request->id;

        $energyMaintenance = ElectricityMaintenanceCallAction::find($id);

        if($energyMaintenance) {

            $energyMaintenance->delete();
            
            $response['success'] = 1;
            $response['msg'] = 'Energy Maintenance Action Deleted successfully'; 
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
       
        if($energyMaintenance->energy_turbine_community_id != NULL) {
            $turbineId = $energyMaintenance->energy_turbine_community_id;
            $turbine = EnergyTurbineCommunity::where('id', $turbineId)->first();
            
            $response['turbine'] = $turbine;
        }

        if($energyMaintenance->energy_generator_community_id != NULL) {
            $generatorId = $energyMaintenance->energy_generator_community_id;
            $generator = EnergyGeneratorCommunity::where('id', $generatorId)->first();
            
            $response['generator'] = $generator;
        }

        $community = Community::where('id', $energyMaintenance->community_id)->first();
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

        $energyActions =  DB::table('electricity_maintenance_call_actions')
            ->join('electricity_maintenance_calls', 'electricity_maintenance_calls.id',
                'electricity_maintenance_call_actions.electricity_maintenance_call_id')
            ->join('energy_maintenance_actions', 'energy_maintenance_actions.id',
                'electricity_maintenance_call_actions.energy_maintenance_action_id')
            ->where('electricity_maintenance_calls.id', 
                $energyMaintenance->id)
            ->where('electricity_maintenance_call_actions.is_archived', 0)
            ->select('electricity_maintenance_call_actions.id', 
                'energy_maintenance_actions.arabic_name')
            ->get();

        $response['community'] = $community;
        $response['energyMaintenance'] = $energyMaintenance;
        $response['energyActions'] = $energyActions;
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
    public function getAgent($flag, $community_id)
    {
        $html = '<option value="">Choose One...</option>';

        if($flag == "system") {

            $energySystems = EnergySystem::where('is_archived', 0)
                ->where('community_id', $community_id)
                ->select('id', 'name')
                ->get();

            foreach ($energySystems as $energyType) {
                $html .= '<option value="'.$energyType->id.'">'.$energyType->name.'</option>';
            }
        } else if($flag == "fbs_user") {

            $users = DB::table('all_energy_meters')
                ->join("households", "all_energy_meters.household_id", "households.id")
                ->where('all_energy_meters.is_archived', 0)
                ->where("all_energy_meters.community_id", $community_id)
                ->where("all_energy_meters.energy_system_type_id", 2)
                ->select("households.english_name", "households.id")
                ->orderBy('households.english_name', 'ASC')
                ->get(); 
                
            foreach ($users as $user) {

                $html .= '<option value="'.$user->id.'">'.$user->english_name.'</option>';
            }
        } else if($flag == "fbs_public") {

            $publics = DB::table('all_energy_meters')
                ->join("public_structures", "all_energy_meters.public_structure_id", 
                    "public_structures.id")
                ->where('all_energy_meters.is_archived', 0)
                ->where("all_energy_meters.community_id", $community_id)
                ->where("all_energy_meters.energy_system_type_id", 2)
                ->select("public_structures.english_name", "public_structures.id")
                ->orderBy('public_structures.english_name', 'ASC')
                ->get();

            foreach ($publics as $public) {
                
                $html .= '<option value="'.$public->id.'">'.$public->english_name.'</option>';
            }
        } else if($flag == "mg_public") {

            $publics = DB::table('all_energy_meters')
                ->join("public_structures", "all_energy_meters.public_structure_id", 
                    "public_structures.id")
                ->where('all_energy_meters.is_archived', 0)
                ->where("all_energy_meters.community_id", $community_id)
                ->where("all_energy_meters.energy_system_type_id", '!=', 2)
                ->select("public_structures.english_name", "public_structures.id")
                ->orderBy('public_structures.english_name', 'ASC')
                ->get();
                
            foreach ($publics as $public) {

                $html .= '<option value="'.$public->id.'">'.$public->english_name.'</option>';
            }
        } else if($flag == "mg_user") {

            $users = DB::table('all_energy_meters')
                ->join("households", "all_energy_meters.household_id", "households.id")
                ->where('all_energy_meters.is_archived', 0)
                ->where("all_energy_meters.community_id", $community_id)
                ->where("all_energy_meters.energy_system_type_id", '!=', 2)
                ->select("households.english_name", "households.id")
                ->orderBy('households.english_name', 'ASC')
                ->get();
                
            foreach ($users as $user) {

                $html .= '<option value="'.$user->id.'">'.$user->english_name.'</option>';
            }
        } else if($flag == "turbine") {

            $energyTurbines = EnergyTurbineCommunity::where('community_id', $community_id)
                ->select('id', 'name')
                ->get();
                
            foreach ($energyTurbines as $energyTurbine) {

                $html .= '<option value="'.$energyTurbine->id.'">'.$energyTurbine->name.'</option>';
            }
        } else if($flag == "generator") {

            $energyGenerators = EnergyGeneratorCommunity::where('community_id', $community_id)
                ->select('id', 'name')
                ->get();
                
                
            foreach ($energyGenerators as $energyGenerator) {

                $html .= '<option value="'.$energyGenerator->id.'">'.$energyGenerator->name.'</option>';
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
    public function getActionsByIssue($issue_id)
    {
        if (!$issue_id) {

            $html = '<option value="">Choose One...</option>';
        } else {

            $html = '<option value="">Choose One...</option>';

            $actions = DB::table('energy_maintenance_actions')
                ->join('energy_maintenance_issues', 'energy_maintenance_actions.energy_maintenance_issue_id', 
                    'energy_maintenance_issues.id')
                ->where("energy_maintenance_actions.energy_maintenance_issue_id", $issue_id)
                ->orderBy('energy_maintenance_actions.arabic_name', 'ASC')
                ->select('energy_maintenance_actions.id', 'energy_maintenance_actions.arabic_name')
                ->get();

            foreach ($actions as $action) {
                $html .= '<option value="'.$action->id.'">'.$action->arabic_name.'</option>';
            }
        }

        return response()->json(['html' => $html]);
    }
}