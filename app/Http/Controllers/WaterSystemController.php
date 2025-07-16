<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\AllWaterHolder;
use App\Models\User;
use App\Models\BsfStatus;
use App\Models\HouseholdStatus;
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
use App\Models\WaterTap;
use App\Models\WaterValve;
use App\Models\WaterSystemConnector;
use App\Models\WaterSystemTap;
use App\Models\WaterSystemValve;
use App\Models\WaterSystemElectrical;  
use App\Models\WaterSystemCycle;
use App\Models\WaterSystemFilter;
use App\Models\WaterSystemPipe;
use App\Models\WaterSystemPump;
use App\Models\WaterSystemTank;
use App\Models\WaterHolderStatus;
use App\Models\WaterNetworkUser;
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
    
                        $viewButton = "<a type='button' class='viewWaterSystem' data-id='".$row->id."' ><i class='fa-solid fa-eye text-info'></i></a>";
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
        $waterSystemCycles = WaterSystemCycle::orderBy('name', 'ASC')->get();

        return view('system.water.create', compact('waterSystemTypes', 'communities', 'waterTanks', 'waterPumps',
            'waterPipes', 'waterFilters', 'waterConnectors', 'waterSystemCycles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {       
        // Get Last comet_id
        $last_comet_id = WaterSystem::latest('id')->value('comet_id');

        $waterSystem = new WaterSystem();
        $waterSystem->comet_id = ++$last_comet_id;
        $waterSystem->fake_meter_number = 'WS' . ++$last_comet_id;
        $waterSystem->water_system_type_id = $request->water_system_type_id;
        $waterSystem->name = $request->name;
        $waterSystem->description = $request->description;
        if($request->water_system_cycle_id) $waterSystem->water_system_cycle_id = $request->water_system_cycle_id;
        $waterSystem->year = $request->year;
        $waterSystem->upgrade_year1 = $request->upgrade_year1;
        $waterSystem->upgrade_year2 = $request->upgrade_year2;
        $waterSystem->notes = $request->notes;
        $waterSystem->save();

        if($request->community_id && $request->water_system_type_id == 4) {

            $waterSystem->community_id = $request->community_id;
            $waterSystem->save();

            $community = Community::findOrFail($request->community_id);
            $community->water_service = "Yes";
            $community->save();

            $left = "Left";
            $leftStatus = HouseholdStatus::where('status', 'like', '%' . $left. '%')->first(); 

            $households = Household::where("is_archived", 0)
                ->where("community_id", $request->community_id)
                ->where("household_status_id", "!=", $leftStatus->id)
                ->orderBy('english_name', 'ASC')
                ->get();

            foreach($households as $household) {

                $confirmed = "Confirmed";
                $confirmedStatus = WaterHolderStatus::where('status', 'like', '%' . $confirmed. '%')->first(); 

                $household->water_service = "Yes";
                $household->water_system_status = "Served";
                $household->water_system_cycle_id = $request->water_system_cycle_id;
                $household->water_holder_status_id = $confirmedStatus->id;
                $household->save();

                $exist = AllWaterHolder::where("household_id", $household->id)->first();
                $existNetwork = WaterNetworkUser::where("household_id", $household->id)->first();
                if(!$exist) {

                    $allWaterHolder = new AllWaterHolder();
                    $allWaterHolder->is_main = "Yes";
                    $allWaterHolder->household_id = $household->id;
                    $allWaterHolder->community_id = $request->community_id;
                    $allWaterHolder->water_system_id = $waterSystem->id;
                    $allWaterHolder->save();
                }
                if(!$existNetwork) {

                    $networkUser = new WaterNetworkUser();
                    $networkUser->household_id = $household->id;
                    $networkUser->community_id = $request->community_id;
                    $networkUser->save();
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
    public function showPage($id)
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

        $waterSystemConnectors = DB::table('water_system_connectors')
            ->join('water_systems', 'water_system_connectors.water_system_id', 
                'water_systems.id')
            ->join('water_connectors', 'water_system_connectors.water_connector_id', 
                'water_connectors.id')
            ->where('water_system_connectors.water_system_id', $id)
            ->select('water_connectors.model', 'water_system_connectors.connector_units', 
                'water_system_connectors.connector_costs', 'water_connectors.brand')
            ->get();

        $waterSystemFilters = DB::table('water_system_filters')
            ->join('water_systems', 'water_system_filters.water_system_id', 
                'water_systems.id')
            ->join('water_filters', 'water_system_filters.water_filter_id', 
                'water_filters.id')
            ->where('water_system_filters.water_system_id', $id)
            ->select('water_filters.model', 'water_system_filters.filter_units', 
                'water_system_filters.filter_costs', 'water_filters.brand')
            ->get();
        
        $waterSystemPipes = DB::table('water_system_pipes')
            ->join('water_systems', 'water_system_pipes.water_system_id', 
                'water_systems.id')
            ->join('water_pipes', 'water_system_pipes.water_pipe_id', 
                'water_pipes.id')
            ->where('water_system_pipes.water_system_id', $id)
            ->select('water_pipes.model', 'water_system_pipes.pipe_units', 
                'water_system_pipes.pipe_costs', 'water_pipes.brand')
            ->get();

        $waterSystemPumps = DB::table('water_system_pumps')
            ->join('water_systems', 'water_system_pumps.water_system_id', 
                'water_systems.id')
            ->join('water_pumps', 'water_system_pumps.water_pump_id', 
                'water_pumps.id')
            ->where('water_system_pumps.water_system_id', $id)
            ->select('water_pumps.model', 'water_system_pumps.pump_units', 
                'water_system_pumps.pump_costs', 'water_pumps.brand')
            ->get();

        $waterSystemTanks = DB::table('water_system_tanks')
            ->join('water_systems', 'water_system_tanks.water_system_id', 
                'water_systems.id')
            ->join('water_tanks', 'water_system_tanks.water_tank_id', 
                'water_tanks.id')
            ->where('water_system_tanks.water_system_id', $id)
            ->select('water_tanks.model', 'water_system_tanks.tank_units', 
                'water_system_tanks.tank_costs', 'water_tanks.brand')
            ->get();

        $waterSystemTaps = DB::table('water_system_taps')
            ->join('water_systems', 'water_system_taps.water_system_id', 
                'water_systems.id')
            ->join('water_taps', 'water_system_taps.water_tap_id', 
                'water_taps.id')
            ->where('water_system_taps.water_system_id', $id)
            ->select('water_taps.model', 'water_system_taps.tap_units', 
                'water_system_taps.tap_costs', 'water_taps.brand')
            ->get();

        $waterSystemValves = DB::table('water_system_valves')
            ->join('water_systems', 'water_system_valves.water_system_id', 
                'water_systems.id')
            ->join('water_valves', 'water_system_valves.water_valve_id', 
                'water_valves.id')
            ->where('water_system_valves.water_system_id', $id)
            ->select('water_valves.model', 'water_system_valves.valve_units', 
                'water_system_valves.valve_costs', 'water_valves.brand')
            ->get();


        return view('system.water.show', compact('waterSystem', 'waterSystemConnectors', 'waterSystemFilters',
            'waterSystemPipes', 'waterSystemPumps', 'waterSystemTanks', 'waterSystemTaps', 
            'waterSystemValves'));
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
        $taps = WaterTap::orderBy('model', 'ASC')->get();
        $valves = WaterValve::orderBy('model', 'ASC')->get();

        $waterConnectors = DB::table('water_system_connectors')
            ->join('water_systems', 'water_system_connectors.water_system_id', 
                'water_systems.id')
            ->join('water_connectors', 'water_system_connectors.water_connector_id', 
                'water_connectors.id')
            ->where('water_system_connectors.water_system_id', $id)
            ->select('water_system_connectors.id', 'water_connectors.model', 'water_system_connectors.connector_units', 
                'water_system_connectors.connector_costs')
            ->get();

        $waterFilters = DB::table('water_system_filters')
            ->join('water_systems', 'water_system_filters.water_system_id', 
                'water_systems.id')
            ->join('water_filters', 'water_system_filters.water_filter_id', 
                'water_filters.id')
            ->where('water_system_filters.water_system_id', $id)
            ->select('water_system_filters.id', 'water_filters.model', 'water_system_filters.filter_units', 
                'water_system_filters.filter_costs')
            ->get();
        
        $waterPipes = DB::table('water_system_pipes')
            ->join('water_systems', 'water_system_pipes.water_system_id', 
                'water_systems.id')
            ->join('water_pipes', 'water_system_pipes.water_pipe_id', 
                'water_pipes.id')
            ->where('water_system_pipes.water_system_id', $id)
            ->select('water_system_pipes.id', 'water_pipes.model', 'water_system_pipes.pipe_units', 'water_system_pipes.pipe_costs')
            ->get();

        $waterPumps = DB::table('water_system_pumps')
            ->join('water_systems', 'water_system_pumps.water_system_id', 
                'water_systems.id')
            ->join('water_pumps', 'water_system_pumps.water_pump_id', 
                'water_pumps.id')
            ->where('water_system_pumps.water_system_id', $id)
            ->select('water_system_pumps.id', 'water_pumps.model', 'water_system_pumps.pump_units', 'water_system_pumps.pump_costs')
            ->get();

        $waterTanks = DB::table('water_system_tanks')
            ->join('water_systems', 'water_system_tanks.water_system_id', 
                'water_systems.id')
            ->join('water_tanks', 'water_system_tanks.water_tank_id', 
                'water_tanks.id')
            ->where('water_system_tanks.water_system_id', $id)
            ->select('water_system_tanks.id', 'water_tanks.model', 'water_system_tanks.tank_units', 'water_system_tanks.tank_costs')
            ->get();

        $waterTaps = DB::table('water_system_taps')
            ->join('water_systems', 'water_system_taps.water_system_id', 
                'water_systems.id')
            ->join('water_taps', 'water_system_taps.water_tap_id', 
                'water_taps.id')
            ->where('water_system_taps.water_system_id', $id)
            ->select('water_system_taps.id', 'water_taps.model', 'water_system_taps.tap_units', 'water_system_taps.tap_costs')
            ->get();

        $waterValves = DB::table('water_system_valves')
            ->join('water_systems', 'water_system_valves.water_system_id', 
                'water_systems.id')
            ->join('water_valves', 'water_system_valves.water_valve_id', 
                'water_valves.id')
            ->where('water_system_valves.water_system_id', $id)
            ->select('water_system_valves.id', 'water_valves.model', 'water_system_valves.valve_units', 'water_system_valves.valve_costs')
            ->get();

        $waterSystem = WaterSystem::findOrFail($id);
        
        $waterSystemCycles = WaterSystemCycle::orderBy('name', 'ASC')->get();

        return view('system.water.edit', compact('connectors', 'filters', 'waterSystemTypes',
            'pipes', 'pumps', 'tanks', 'waterConnectors', 'waterSystem', 'communities', 
            'waterFilters', 'waterPipes', 'waterPumps', 'waterTanks', 'waterSystemCycles',
            'waterTaps', 'taps', 'waterValves', 'valves'));
    }

    // This function is to update the water tank costs
    public function updateTank($id, $units, $cost)
    {
        $tank = WaterSystemTank::findOrFail($id);
        $tank->tank_units = $units;
        $tank->tank_costs = $cost;
        $tank->save();

        return response()->json(['success' => 1, 'msg' => 'Tank updated successfully']);
    }

    // This function is to update the water pipe costs
    public function updatePipe($id, $units, $cost)
    {
        $pipe = WaterSystemPipe::findOrFail($id);
        $pipe->pipe_units = $units;
        $pipe->pipe_costs = $cost;
        $pipe->save();

        return response()->json(['success' => 1, 'msg' => 'Pipe updated successfully']);
    }

    // This function is to update the water pump costs
    public function updatePump($id, $units, $cost)
    {
        $pump = WaterSystemPump::findOrFail($id);
        $pump->pump_units = $units;
        $pump->pump_costs = $cost;
        $pump->save();

        return response()->json(['success' => 1, 'msg' => 'Pump updated successfully']);
    }

    // This function is to update the water connector costs
    public function updateConnector($id, $units, $cost)
    {
        $connector = WaterSystemConnector::findOrFail($id);
        $connector->connector_units = $units;
        $connector->connector_costs = $cost;
        $connector->save();

        return response()->json(['success' => 1, 'msg' => 'Connector updated successfully']);
    }

    // This function is to update the water Filter costs
    public function updateFilter($id, $units, $cost)
    {
        $filter = WaterSystemFilter::findOrFail($id);
        $filter->filter_units = $units;
        $filter->filter_costs = $cost;
        $filter->save();

        return response()->json(['success' => 1, 'msg' => 'Filter updated successfully']);
    }

    // This function is to update the water Tap costs
    public function updateTap($id, $units, $cost)
    {
        $tap = WaterSystemTap::findOrFail($id);
        $tap->tap_units = $units;
        $tap->tap_costs = $cost;
        $tap->save();

        return response()->json(['success' => 1, 'msg' => 'Tap updated successfully']);
    }

    // This function is to update the water Valve costs
    public function updateValve($id, $units, $cost)
    {
        $valve = WaterSystemValve::findOrFail($id);
        $valve->valve_units = $units;
        $valve->valve_costs = $cost;
        $valve->save();

        return response()->json(['success' => 1, 'msg' => 'Valve updated successfully']);
    }

    /**
     * Update an existing resource in storage.
     *
     * @param  \Illuminate\Http\Request $request, int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $waterSystem = WaterSystem::findOrFail($id);
        if($request->water_system_type_id) $waterSystem->water_system_type_id = $request->water_system_type_id;
        if($request->name) $waterSystem->name = $request->name;
        if($request->description) $waterSystem->description = $request->description;
        if($request->water_system_cycle_id) $waterSystem->water_system_cycle_id = $request->water_system_cycle_id;
        if($request->year) $waterSystem->year = $request->year;
        if($request->upgrade_year1) $waterSystem->upgrade_year1 = $request->upgrade_year1;
        if($request->upgrade_year2) $waterSystem->upgrade_year2 = $request->upgrade_year2;
        if($request->notes) $waterSystem->notes = $request->notes;
        $waterSystem->save();

        if($request->community_id && $request->water_system_type_id == 4) {

            $waterSystem->community_id = $request->community_id;
            $waterSystem->save();

            $left = "Left";
            $leftStatus = HouseholdStatus::where('status', 'like', '%' . $left. '%')->first(); 

            $households = Household::where("is_archived", 0)
                ->where("community_id", $request->community_id)
                ->where("household_status_id", "!=", $leftStatus->id)
                ->orderBy('english_name', 'ASC')
                ->get();

            foreach($households as $household) {

                $complete = "Completed";
                $completedStatus = WaterHolderStatus::where('status', 'like', '%' . $complete. '%')->first(); 

                $household->water_service = "Yes";
                $household->water_system_status = "Served";
                $household->water_system_cycle_id = $request->water_system_cycle_id;
                $household->water_holder_status_id = $completedStatus->id;
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
        

        if ($request->tank_ids) {

            for ($cnq = 0; $cnq < count($request->tank_ids); $cnq++) {

                $waterTank = new WaterSystemTank();
                $waterTank->water_tank_id = $request->tank_ids[$cnq];
                $waterTank->water_system_id = $id;
                $waterTank->tank_units = $request->input("tank_units.$cnq.subject") ?? 0;
                $waterTank->tank_costs = $request->input("tank_costs.$cnq.subject") ?? 0;
        
                $waterTank->save();
            }
        }

        if ($request->pipe_ids) {

            for ($pip = 0; $pip < count($request->pipe_ids); $pip++) {

                $waterPipe = new WaterSystemPipe();
                $waterPipe->water_pipe_id = $request->pipe_ids[$pip];
                $waterPipe->water_system_id = $id;
                $waterPipe->pipe_units = $request->input("pipe_units.$pip.subject") ?? 0;
                $waterPipe->pipe_costs = $request->input("pipe_costs.$pip.subject") ?? 0;
        
                $waterPipe->save();
            }
        }

        if ($request->pump_ids) {

            for ($pum = 0; $pum < count($request->pump_ids); $pum++) {

                $waterPump = new WaterSystemPump();
                $waterPump->water_pump_id = $request->pump_ids[$pum];
                $waterPump->water_system_id = $id;
                $waterPump->pump_units = $request->input("pump_units.$pum.subject") ?? 0;
                $waterPump->pump_costs = $request->input("pump_costs.$pum.subject") ?? 0;
        
                $waterPump->save();
            }
        }

        if ($request->connector_ids) {

            for ($conn = 0; $conn < count($request->connector_ids); $conn++) {

                $waterConnector = new WaterSystemConnector();
                $waterConnector->water_connector_id = $request->connector_ids[$conn];
                $waterConnector->water_system_id = $id;
                $waterConnector->connector_units = $request->input("connector_units.$conn.subject") ?? 0;
                $waterConnector->connector_costs = $request->input("connector_costs.$conn.subject") ?? 0;
        
                $waterConnector->save();
            }
        }

        if ($request->filter_ids) {

            for ($conn = 0; $conn < count($request->filter_ids); $conn++) {

                $waterFilter = new WaterSystemFilter();
                $waterFilter->water_filter_id = $request->filter_ids[$conn];
                $waterFilter->water_system_id = $id;
                $waterFilter->filter_units = $request->input("filter_units.$conn.subject") ?? 0;
                $waterFilter->filter_costs = $request->input("filter_costs.$conn.subject") ?? 0;
        
                $waterFilter->save();
            }
        }

        if ($request->tap_ids) {

            for ($conn = 0; $conn < count($request->tap_ids); $conn++) {

                $waterTap = new WaterSystemTap();
                $waterTap->water_tap_id = $request->tap_ids[$conn];
                $waterTap->water_system_id = $id;
                $waterTap->tap_units = $request->input("tap_units.$conn.subject") ?? 0;
                $waterTap->tap_costs = $request->input("tap_costs.$conn.subject") ?? 0;
        
                $waterTap->save();
            }
        }

        if ($request->valve_ids) {

            for ($conn = 0; $conn < count($request->valve_ids); $conn++) {

                $waterValve = new WaterSystemValve();
                $waterValve->water_valve_id = $request->valve_ids[$conn];
                $waterValve->water_system_id = $id;
                $waterValve->valve_units = $request->input("valve_units.$conn.subject") ?? 0;
                $waterValve->valve_costs = $request->input("valve_costs.$conn.subject") ?? 0;
        
                $waterValve->save();
            }
        }

        return redirect('/water-system')->with('message', 'Water System Updated Successfully!');
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

        $households = Household::where("community_id", $waterSystem->community_id)->get();

        foreach($households as $household) {

            $household->water_service = "No";
            $household->water_system_status = "Not Served";
            $household->water_system_cycle_id = null;
            $household->water_holder_status_id = null;
            $household->save();
        }

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
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteTank(Request $request)
    {
        $id = $request->id;

        $waterSystemTank = WaterSystemTank::find($id);

        if($waterSystemTank->delete()) {

            $response['success'] = 1;
            $response['msg'] = 'Water Tank Deleted successfully'; 
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
    public function deletePipe(Request $request)
    {
        $id = $request->id;

        $waterSystemPipe = WaterSystemPipe::find($id);

        if($waterSystemPipe->delete()) {

            $response['success'] = 1;
            $response['msg'] = 'Water Pipe Deleted successfully'; 
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
    public function deletePump(Request $request)
    {
        $id = $request->id;

        $waterSystemPump = WaterSystemPump::find($id);

        if($waterSystemPump->delete()) {

            $response['success'] = 1;
            $response['msg'] = 'Water Pump Deleted successfully'; 
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
    public function deleteConnector(Request $request)
    {
        $id = $request->id;

        $waterSystemConnector = WaterSystemConnector::find($id);

        if($waterSystemConnector->delete()) {

            $response['success'] = 1;
            $response['msg'] = 'Water Connector Deleted successfully'; 
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
    public function deleteFilter(Request $request)
    {
        $id = $request->id;

        $waterSystemFilter = WaterSystemFilter::find($id);

        if($waterSystemFilter->delete()) {

            $response['success'] = 1;
            $response['msg'] = 'Water Filter Deleted successfully'; 
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
    public function deleteTap(Request $request)
    {
        $id = $request->id;

        $waterSystemTap = WaterSystemTap::find($id);

        if($waterSystemTap->delete()) {

            $response['success'] = 1;
            $response['msg'] = 'Water Tap Deleted successfully'; 
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
    public function deleteValve(Request $request)
    {
        $id = $request->id;

        $waterSystemValve = WaterSystemValve::find($id);

        if($waterSystemValve->delete()) {

            $response['success'] = 1;
            $response['msg'] = 'Water Valve Deleted successfully'; 
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
