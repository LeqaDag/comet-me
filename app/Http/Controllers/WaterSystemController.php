<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\AllWaterHolder;
use App\Models\User;
use App\Models\BsfStatus;
use App\Models\Community;
use App\Models\CommunityWaterSource;
use App\Models\GridUser;
use App\Models\H2oSharedUser;
use App\Models\H2oStatus;
use App\Models\H2oUser;
use App\Models\Household;
use App\Models\WaterConnector;
use App\Models\WaterElectrical;
use App\Models\WaterFilter;
use App\Models\WaterPipe;
use App\Models\WaterPump;
use App\Models\WaterSystemConnector;
use App\Models\WaterSystemElectrical;
use App\Models\WaterSystemFilter;
use App\Models\WaterSystemPipe;
use App\Models\WaterSystemPump;
use App\Models\WaterSystemTank;
use App\Models\WaterTank;
use App\Models\WaterUser;
use App\Models\WaterSystem;
use App\Models\WaterSystemType;
use App\Models\H2oSystemIncident;
use App\Models\Incident;
use App\Models\IncidentStatus;
use App\Exports\Water\OldSystemHolders;
use App\Exports\Water\GridLargeHolders;
use App\Exports\Water\GridSmallHolders;
use App\Exports\Water\NetworkHolders;
use Auth;
use DB;
use Route;
use DataTables;
use Excel;

class WaterSystemController extends Controller
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

                $data = DB::table('water_systems')
                    ->join('water_system_types', 'water_systems.water_system_type_id', 
                        'water_system_types.id')
                    ->select('water_systems.id as id', 'water_systems.name as name',
                        'water_systems.description', 'water_systems.year', 'water_system_types.type',
                        'water_systems.created_at as created_at',
                        'water_systems.updated_at as updated_at',)
                    ->latest();

                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
    
                        $viewButton = "<a type='button' class='viewWaterSystem' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#waterSystemModal' ><i class='fa-solid fa-eye text-info'></i></a>";
                        $updateButton = "<a type='button' class='updateWaterSystem' data-id='".$row->id."' ><i class='fa-solid fa-pen-to-square text-success'></i></a>";
                        $deleteButton = "<a type='button' class='deleteWaterSystem' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";
                        
                        if(Auth::guard('user')->user()->user_type_id == 1 || 
                            Auth::guard('user')->user()->user_type_id == 2 ||
                            Auth::guard('user')->user()->user_type_id == 5 ||
                            Auth::guard('user')->user()->user_type_id == 11) 
                        {
                                
                            return $viewButton." ". $updateButton." ".$deleteButton;
                        } else return $viewButton;
                    })
                    ->addColumn('systemName', function($row) {

                        $systemName = "";
                        $systemName = "<a type='button' class='getWaterHolders text-info' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#waterSystemHolderModal' >". $row->name ."</a>";

                        return $systemName;
                    })
                   
                    ->filter(function ($instance) use ($request) {
                        if (!empty($request->get('search'))) {
                                $instance->where(function($w) use($request){
                                $search = $request->get('search');
                                $w->orWhere('water_systems.name', 'LIKE', "%$search%")
                                ->orWhere('water_systems.description', 'LIKE', "%$search%")
                                ->orWhere('water_system_types.type', 'LIKE', "%$search%")
                                ->orWhere('water_systems.year', 'LIKE', "%$search%");
                            });
                        }
                    })
                ->rawColumns(['action', 'systemName'])
                ->make(true);
            }
    
            $gridLarge = GridUser::selectRaw('SUM(grid_integration_large) AS sumLarge')
                ->first();
            $gridSmall = GridUser::selectRaw('SUM(grid_integration_small) AS sumSmall')
                ->first();
            $h2oSystem = H2oUser::selectRaw('SUM(number_of_h20) AS h2oSystem')
                ->first();
            
            $waterArray[] = ['System Type', 'Total'];
            
            for($key=0; $key <=3; $key++) {
                if($key == 1) $waterArray[$key] = ["Grid Large", $gridLarge->sumLarge];
                if($key == 2) $waterArray[$key] = ["Grid Small", $gridSmall->sumSmall];
                if($key == 3) $waterArray[$key] = ["H2O System", $h2oSystem->h2oSystem];
            }
    
            $h2oIncidentsNumber = H2oSystemIncident::count();
    
            // H2O incidents
            $dataIncidents = DB::table('h2o_system_incidents')
                ->join('communities', 'h2o_system_incidents.community_id', '=', 'communities.id')
                ->join('sub_regions', 'communities.sub_region_id', '=', 'sub_regions.id')
                ->join('incidents', 'h2o_system_incidents.incident_id', '=', 'incidents.id')
                ->join('incident_statuses', 'h2o_system_incidents.incident_status_id', 
                    '=', 'incident_statuses.id')
                ->select(
                    DB::raw('incident_statuses.name as name'),
                    DB::raw('count(*) as number'))
                ->groupBy('incident_statuses.name')
                ->get();
    
            $arrayIncidents[] = ['English Name', 'Number'];
            
            foreach($dataIncidents as $key => $value) {
    
                $arrayIncidents[++$key] = [$value->name, $value->number];
            }
    
            return view('system.water.index', compact('h2oIncidentsNumber'))
            ->with(
                'waterSystemTypeData', json_encode($waterArray))
            ->with('h2oIncidents', json_encode($arrayIncidents));
            
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
        $waterSystemTypes = WaterSystemType::all();
        $communities = Community::where('is_archived', 0)
            ->orderBy('english_name', 'ASC')
            ->get();

        $waterTanks = WaterTank::orderBy('model', 'ASC')->get();
        $waterPumps = WaterPump::orderBy('model', 'ASC')->get();
        $waterPipes = WaterPipe::orderBy('model', 'ASC')->get();
        $waterFilters = WaterFilter::orderBy('model', 'ASC')->get();
        $waterConnectors = WaterConnector::orderBy('model', 'ASC')->get();

        return view('system.water.create', compact('waterSystemTypes', 'communities', 'waterTanks', 'waterPumps',
            'waterPipes', 'waterFilters', 'waterConnectors'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {       
        $waterSystem = new WaterSystem();
        $waterSystem->water_system_type_id = $request->water_system_type_id;
        $waterSystem->name = $request->name;
        $waterSystem->description = $request->description;
        $waterSystem->year = $request->year;
        $waterSystem->upgrade_year1 = $request->upgrade_year1;
        $waterSystem->upgrade_year2 = $request->upgrade_year2;
        $waterSystem->notes = $request->notes;
        $waterSystem->save();

        if($request->community_id && $request->water_system_type_id == 4) {

            $waterSystem->community_id = $request->community_id;
            $waterSystem->save();

            $households = Household::where("community_id", $request->community_id)
                ->orderBy('english_name', 'ASC')
                ->get();

            foreach($households as $household) {

                $household->water_service = "Yes";
                $household->water_system_status = "Served";
                $household->save();

                $exist = AllWaterHolder::where("household_id", $household->id)->first();
                if($exist) {

                } else {

                    $allWaterHolder = new AllWaterHolder();
                    $allWaterHolder->is_main = "Yes";
                    $allWaterHolder->household_id = $household->id;
                    $allWaterHolder->community_id = $request->community_id;
                    $allWaterHolder->water_system_id = $waterSystem->id;
                    $allWaterHolder->save();
                }
            }
        }

        // Tanks
        if($request->tanks_id) {
            for($i=0; $i < count($request->tanks_id); $i++) {

                $waterSystemTank = new WaterSystemTank();
                $waterSystemTank->water_tank_id = $request->tanks_id[$i];
                $waterSystemTank->tank_units = $request->tank_units[$i]["subject"];
                $waterSystemTank->water_system_id = $waterSystem->id;
                $waterSystemTank->save();
            }
        }

        // Pumps
        if($request->pumps_id) {
            for($i=0; $i < count($request->pumps_id); $i++) {

                $waterSystemPump = new WaterSystemPump();
                $waterSystemPump->water_pump_id = $request->pumps_id[$i];
                $waterSystemPump->pump_units = $request->pump_units[$i]["subject"];
                $waterSystemPump->water_system_id = $waterSystem->id;
                $waterSystemPump->save();
            }
        }

        // Pipes
        if($request->pipes_id) {
            for($i=0; $i < count($request->pipes_id); $i++) {

                $waterSystemPipe = new WaterSystemPipe();
                $waterSystemPipe->water_pipe_id = $request->pipes_id[$i];
                $waterSystemPipe->pipe_units = $request->pipe_units[$i]["subject"];
                $waterSystemPipe->water_system_id = $waterSystem->id;
                $waterSystemPipe->save();
            }
        }


        // Filters
        if($request->filters_id) {
            for($i=0; $i < count($request->filters_id); $i++) {

                $waterSystemFilter = new WaterSystemFilter();
                $waterSystemFilter->water_filter_id = $request->filters_id[$i];
                $waterSystemFilter->filter_units = $request->filter_units[$i]["subject"];
                $waterSystemFilter->water_system_id = $waterSystem->id;
                $waterSystemFilter->save();
            }
        }


        // Connectors
        if($request->connectors_id) {
            for($i=0; $i < count($request->connectors_id); $i++) {

                $waterSystemConnector = new WaterSystemConnector();
                $waterSystemConnector->water_connector_id = $request->connectors_id[$i];
                $waterSystemConnector->connector_units = $request->connector_units[$i]["subject"];
                $waterSystemConnector->water_system_id = $waterSystem->id;
                $waterSystemConnector->save();
            }
        }

        return redirect('/water-system')
            ->with('message', 'New Water System Added Successfully!');
    }

    /**
     * View Edit page.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function editPage($id)
    {
        $waterSystem = WaterSystem::findOrFail($id);

        return response()->json($waterSystem);
    }

    /**
     * Show the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $waterSystem = WaterSystem::findOrFail($id);
        $waterSystemType = WaterSystemType::where('id', $waterSystem->water_system_type_id)->first();
        $community = Community::where('id', $waterSystem->community_id)->first();

        $waterSystemConnectors = DB::table('water_system_connectors')
            ->join('water_systems', 'water_system_connectors.water_system_id', 
                'water_systems.id')
            ->join('water_connectors', 'water_system_connectors.water_connector_id', 
                'water_connectors.id')
            ->where('water_system_connectors.water_system_id', $id)
            ->select('water_connectors.model', 'water_system_connectors.connector_units')
            ->get();

        $waterSystemFilters = DB::table('water_system_filters')
            ->join('water_systems', 'water_system_filters.water_system_id', 
                'water_systems.id')
            ->join('water_filters', 'water_system_filters.water_filter_id', 
                'water_filters.id')
            ->where('water_system_filters.water_system_id', $id)
            ->select('water_filters.model', 'water_system_filters.filter_units')
            ->get();
        
        $waterSystemPipes = DB::table('water_system_pipes')
            ->join('water_systems', 'water_system_pipes.water_system_id', 
                'water_systems.id')
            ->join('water_pipes', 'water_system_pipes.water_pipe_id', 
                'water_pipes.id')
            ->where('water_system_pipes.water_system_id', $id)
            ->select('water_pipes.model', 'water_system_pipes.pipe_units')
            ->get();

        $waterSystemPumps = DB::table('water_system_pumps')
            ->join('water_systems', 'water_system_pumps.water_system_id', 
                'water_systems.id')
            ->join('water_pumps', 'water_system_pumps.water_pump_id', 
                'water_pumps.id')
            ->where('water_system_pumps.water_system_id', $id)
            ->select('water_pumps.model', 'water_system_pumps.pump_units')
            ->get();

        $waterSystemTanks = DB::table('water_system_tanks')
            ->join('water_systems', 'water_system_tanks.water_system_id', 
                'water_systems.id')
            ->join('water_tanks', 'water_system_tanks.water_tank_id', 
                'water_tanks.id')
            ->where('water_system_tanks.water_system_id', $id)
            ->select('water_tanks.model', 'water_system_tanks.tank_units')
            ->get();

        $response['waterSystem'] = $waterSystem;
        $response['waterSystemType'] = $waterSystemType;
        $response['community'] = $community;
        $response['waterSystemConnectors'] = $waterSystemConnectors;
        $response['waterSystemFilters'] = $waterSystemFilters;
        $response['waterSystemPipes'] = $waterSystemPipes;
        $response['waterSystemPumps'] = $waterSystemPumps;
        $response['waterSystemTanks'] = $waterSystemTanks;

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
        $waterSystemTypes = WaterSystemType::all();
        $communities = Community::where('is_archived', 0)
            ->orderBy('english_name', 'ASC')
            ->get();

        $tanks = WaterTank::orderBy('model', 'ASC')->get();
        $pumps = WaterPump::orderBy('model', 'ASC')->get();
        $pipes = WaterPipe::orderBy('model', 'ASC')->get();
        $filters = WaterFilter::orderBy('model', 'ASC')->get();
        $connectors = WaterConnector::orderBy('model', 'ASC')->get();

        $waterConnectors = DB::table('water_system_connectors')
            ->join('water_systems', 'water_system_connectors.water_system_id', 
                'water_systems.id')
            ->join('water_connectors', 'water_system_connectors.water_connector_id', 
                'water_connectors.id')
            ->where('water_system_connectors.water_system_id', $id)
            ->select('water_connectors.id', 'water_connectors.model', 'water_system_connectors.connector_units')
            ->get();

        $waterFilters = DB::table('water_system_filters')
            ->join('water_systems', 'water_system_filters.water_system_id', 
                'water_systems.id')
            ->join('water_filters', 'water_system_filters.water_filter_id', 
                'water_filters.id')
            ->where('water_system_filters.water_system_id', $id)
            ->select('water_filters.id', 'water_filters.model', 'water_system_filters.filter_units')
            ->get();
        
        $waterPipes = DB::table('water_system_pipes')
            ->join('water_systems', 'water_system_pipes.water_system_id', 
                'water_systems.id')
            ->join('water_pipes', 'water_system_pipes.water_pipe_id', 
                'water_pipes.id')
            ->where('water_system_pipes.water_system_id', $id)
            ->select('water_pipes.id', 'water_pipes.model', 'water_system_pipes.pipe_units')
            ->get();

        $waterPumps = DB::table('water_system_pumps')
            ->join('water_systems', 'water_system_pumps.water_system_id', 
                'water_systems.id')
            ->join('water_pumps', 'water_system_pumps.water_pump_id', 
                'water_pumps.id')
            ->where('water_system_pumps.water_system_id', $id)
            ->select('water_pumps.id', 'water_pumps.model', 'water_system_pumps.pump_units')
            ->get();

        $waterTanks = DB::table('water_system_tanks')
            ->join('water_systems', 'water_system_tanks.water_system_id', 
                'water_systems.id')
            ->join('water_tanks', 'water_system_tanks.water_tank_id', 
                'water_tanks.id')
            ->where('water_system_tanks.water_system_id', $id)
            ->select('water_tanks.id', 'water_tanks.model', 'water_system_tanks.tank_units')
            ->get();

        $waterSystem = WaterSystem::findOrFail($id);
        
        return view('system.water.edit', compact('connectors', 'filters', 'waterSystemTypes',
            'pipes', 'pumps', 'tanks', 'waterConnectors', 'waterSystem', 'communities',
            'waterFilters', 'waterPipes', 'waterPumps', 'waterTanks'));
    }

    /**
     * Get resources.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function incidentH2oDetails(Request $request)
    {
        $incidentStatus = $request->selected_data;

        $status = IncidentStatus::where("name", $incidentStatus)->first();
        $status_id = $status->id;

        $dataIncidents = DB::table('h2o_system_incidents')
            ->join('communities', 'h2o_system_incidents.community_id', '=', 'communities.id')
            ->join('h2o_users', 'h2o_system_incidents.h2o_user_id', '=', 'h2o_users.id')
            ->join('households', 'h2o_users.household_id', '=', 'households.id')
            ->join('incidents', 'h2o_system_incidents.incident_id', '=', 'incidents.id')
            ->join('incident_statuses', 'h2o_system_incidents.incident_status_id', 
                '=', 'incident_statuses.id')
            ->where("h2o_system_incidents.incident_status_id", $status_id)
            ->select("communities.english_name as community_name", "h2o_system_incidents.date",
                "incidents.english_name as incident", "households.english_name as household",
                "h2o_system_incidents.equipment")
            ->get();

        $response = $dataIncidents; 
      
        return response()->json($response); 
    }

    /**
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteWaterSystem (Request $request)
    {
        $id = $request->id;

        $waterSystem = WaterSystem::find($id);

        if($waterSystem->delete()) {

            $response['success'] = 1;
            $response['msg'] = 'Water System Deleted successfully'; 
        } else {

            $response['success'] = 0;
            $response['msg'] = 'Invalid ID.';
        }

        return response()->json($response); 
    }

    /**
     * Get the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function getWaterHolders($id)
    {
        $waterSystem = WaterSystem::findOrFail($id);
        $data = null;

        if($id == 1) {

            $data = DB::table('all_water_holders')
                ->join('communities', 'all_water_holders.community_id', 'communities.id')
                ->leftJoin('h2o_public_structures', 'all_water_holders.public_structure_id', 
                    'h2o_public_structures.public_structure_id')
                ->leftJoin('h2o_users', 'h2o_users.household_id', 'all_water_holders.household_id')
                ->join('h2o_statuses', 'h2o_users.h2o_status_id', 'h2o_statuses.id')
                ->select(
                    'communities.id',
                    'communities.english_name as community',
                    DB::raw('COUNT(DISTINCT h2o_users.id) as number_of_users'),
                    DB::raw('COUNT(DISTINCT h2o_public_structures.public_structure_id) as number_of_structures'),
                    DB::raw('COUNT(DISTINCT h2o_users.id) + COUNT(DISTINCT h2o_public_structures.public_structure_id) 
                    as total_number_of_holders')
                )
                ->groupBy('communities.id')
                ->get();
        } else if($id == 2) {

            $data = DB::table('all_water_holders')
                ->join('communities', 'all_water_holders.community_id', 'communities.id')
                ->LeftJoin('grid_public_structures', 'all_water_holders.public_structure_id', 
                    'grid_public_structures.public_structure_id')
                ->LeftJoin('grid_users', 'all_water_holders.household_id', 'grid_users.household_id')
                ->where('all_water_holders.is_archived', 0)
                ->where('grid_users.grid_integration_large', '!=', 0)
                ->select(
                    'communities.id',
                    'communities.english_name as community',
                    DB::raw('COUNT(DISTINCT grid_users.id) as number_of_users'),
                    DB::raw('COUNT(DISTINCT grid_public_structures.public_structure_id) as number_of_structures'),
                    DB::raw('COUNT(DISTINCT grid_users.id) + COUNT(DISTINCT grid_public_structures.public_structure_id) 
                    as total_number_of_holders')
                )
                ->groupBy('communities.id')
                ->get();
        } else if($id == 3) {

            $data = DB::table('all_water_holders')
                ->join('communities', 'all_water_holders.community_id', 'communities.id')
                ->LeftJoin('grid_public_structures', 'all_water_holders.public_structure_id', 
                    'grid_public_structures.public_structure_id')
                ->LeftJoin('grid_users', 'all_water_holders.household_id', 'grid_users.household_id')
                ->where('all_water_holders.is_archived', 0)
                ->where('grid_users.grid_integration_small', '!=', 0)
                ->select(
                    'communities.id',
                    'communities.english_name as community',
                    DB::raw('COUNT(DISTINCT grid_users.id) as number_of_users'),
                    DB::raw('COUNT(DISTINCT grid_public_structures.public_structure_id) as number_of_structures'),
                    DB::raw('COUNT(DISTINCT grid_users.id) + COUNT(DISTINCT grid_public_structures.public_structure_id) 
                    as total_number_of_holders')
                )
                ->groupBy('communities.id')
                ->get();
        } else {
            
            if($waterSystem->community_id) {

                $data = DB::table('all_water_holders')
                    ->join('communities', 'all_water_holders.community_id', 'communities.id')
                    ->where('all_water_holders.is_archived', 0)
                    ->where('communities.id', $waterSystem->community_id)
                    ->select(
                        'communities.id',
                        'communities.english_name as community',
                        DB::raw('COUNT(DISTINCT all_water_holders.household_id) as total_number_of_holders')
                    )
                    ->groupBy('communities.id')
                    ->get();
            }
        }

        $response['waterSystem'] = $waterSystem;
        $response['data'] = $data;

        return response()->json($response);
    }

    /**
     * 
     * @return \Illuminate\Support\Collection
     */
    public function exportWaterHolders(Request $request) 
    {
        $id = $request->input('water_system_id');
        if($id == 1) return Excel::download(new OldSystemHolders($id), 'old_system_holders.xlsx');
        else if($id == 2)  return Excel::download(new GridLargeHolders($id), 'grid_large_holders.xlsx');
        else if($id == 3)  return Excel::download(new GridSmallHolders($id), 'grid_small_holders.xlsx');
        else return Excel::download(new NetworkHolders($id), 'network_holders.xlsx');
    }
}
