<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\AllEnergyMeter;
use App\Models\User;
use App\Models\Community; 
use App\Models\CommunityDonor;
use App\Models\CommunityStatus;
use App\Models\CommunityRepresentative;
use App\Models\CommunityRole;
use App\Models\Compound;
use App\Models\Donor;
use App\Models\EnergySystem;
use App\Models\EnergyBattery; 
use App\Models\EnergyPv;
use App\Models\EnergyAirConditioner; 
use App\Models\EnergyBatteryStatusProcessor;
use App\Models\EnergyBatteryTemperatureSensor;
use App\Models\EnergyChargeController;
use App\Models\EnergyGenerator;
use App\Models\EnergyInverter;
use App\Models\EnergyLoadRelay;
use App\Models\EnergyMcbAc;
use App\Models\EnergyMcbPv;
use App\Models\EnergyMonitoring;
use App\Models\EnergyWindTurbine;
use App\Models\EnergyMcbChargeController;
use App\Models\EnergyMcbInverter;
use App\Models\EnergyRelayDriver;
use App\Models\EnergyRemoteControlCenter;
use App\Models\EnergySystemType;
use App\Models\EnergySystemRelayDriver;
use App\Models\EnergySystemBattery;
use App\Models\EnergyBatteryMount;
use App\Models\EnergySystemBatteryMount;
use App\Models\EnergySystemMonitoring;
use App\Models\EnergySystemPv;
use App\Models\EnergyPvMount;
use App\Models\EnergySystemCycle;
use App\Models\EnergySystemPvMount;
use App\Models\EnergySystemChargeController;
use App\Models\EnergySystemWindTurbine;
use App\Models\EnergySystemGenerator;
use App\Models\EnergySystemBatteryStatusProcessor;
use App\Models\EnergySystemBatteryTemperatureSensor;
use App\Models\EnergySystemInverter;
use App\Models\EnergySystemLoadRelay;
use App\Models\EnergySystemMcbPv;
use App\Models\EnergySystemMcbChargeController;
use App\Models\EnergySystemRemoteControlCenter;
use App\Models\EnergySystemMcbInverter;
use App\Models\EnergySystemAirConditioner;
use App\Models\Household;
use App\Models\Photo;
use App\Models\Region;
use App\Models\SubRegion;
use App\Models\SubCommunity;
use App\Models\Settlement;
use App\Models\ServiceType;
use App\Models\PublicStructure;
use App\Models\PublicStructureCategory;
use App\Models\ProductType;
use App\Models\CommunityWaterSource;
use App\Models\IncidentStatusSmallInfrastructure;
use App\Models\FbsSystem;
use App\Models\Town;
use App\Models\EnergySystemFbsCabinet;
use App\Models\EnergySystemFbsFan;
use App\Models\EnergySystemFbsLock;
use App\Models\EnergySystemFbsWiring;
use App\Models\EnergySystemWiringHouse;
use App\Models\EnergySystemElectricityRoom;
use App\Models\EnergySystemElectricityBosRoom;
use App\Models\EnergySystemRefrigeratorCost;
use App\Models\EnergySystemGrid;
use App\Exports\EnergySystemExport;
use Carbon\Carbon;
use Auth;
use DataTables;
use DB;
use Excel;
use Image;
use Route;

class EnergySystemController extends Controller
{

    /**
     * This functin for calculating the total cost for all systems
     *
     * @return \Illuminate\Http\Response
     */
    public function totalCost() {

        $energySystems = EnergySystem::where("is_archived", 0)->get();

        foreach($energySystems as $energySystem) {

            $totalCost = 0;
            $energySys = EnergySystem::findOrFail($energySystem->id);

            // Sum costs from related models
            $totalCost += EnergySystemBattery::where('energy_system_id', $energySystem->id)->sum('cost');
            $totalCost += EnergySystemBatteryMount::where('energy_system_id', $energySystem->id)->sum('cost');
            $totalCost += EnergySystemPv::where('energy_system_id', $energySystem->id)->sum('cost');
            $totalCost += EnergySystemPvMount::where('energy_system_id', $energySystem->id)->sum('cost');
            $totalCost += EnergySystemChargeController::where('energy_system_id', $energySystem->id)->sum('cost');
            $totalCost += EnergySystemInverter::where('energy_system_id', $energySystem->id)->sum('cost');
            $totalCost += EnergySystemRelayDriver::where('energy_system_id', $energySystem->id)->sum('cost');
            $totalCost += EnergySystemLoadRelay::where('energy_system_id', $energySystem->id)->sum('cost');
            $totalCost += EnergySystemBatteryStatusProcessor::where('energy_system_id', $energySystem->id)->sum('cost');
            $totalCost += EnergySystemMonitoring::where('energy_system_id', $energySystem->id)->sum('cost');
            $totalCost += EnergySystemBatteryTemperatureSensor::where('energy_system_id', $energySystem->id)->sum('cost');
            $totalCost += EnergySystemRemoteControlCenter::where('energy_system_id', $energySystem->id)->sum('cost');
            $totalCost += EnergySystemGenerator::where('energy_system_id', $energySystem->id)->sum('cost');
            $totalCost += EnergySystemWindTurbine::where('energy_system_id', $energySystem->id)->sum('cost');
            $totalCost += EnergySystemMcbPv::where('energy_system_id', $energySystem->id)->sum('cost');
            $totalCost += EnergySystemMcbChargeController::where('energy_system_id', $energySystem->id)->sum('cost');
            $totalCost += EnergySystemMcbInverter::where('energy_system_id', $energySystem->id)->sum('cost');
            $totalCost += EnergySystemAirConditioner::where('energy_system_id', $energySystem->id)->sum('cost');
            $totalCost += EnergySystemWiringHouse::where('energy_system_id', $energySystem->id)->sum('cost');
            $totalCost += EnergySystemElectricityRoom::where('energy_system_id', $energySystem->id)->sum('cost');
            $totalCost += EnergySystemElectricityBosRoom::where('energy_system_id', $energySystem->id)->sum('cost');
            $totalCost += EnergySystemGrid::where('energy_system_id', $energySystem->id)->sum('cost');
            $totalCost += EnergySystemRefrigeratorCost::where('energy_system_id', $energySystem->id)->sum('cost');

            // Assign total cost to the energy system and save again
            $energySys->total_costs = $totalCost;
            $energySys->save();
            
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {	
        if (Auth::guard('user')->user() != null) {

            $this->totalCost();
            
            $communityFilter = $request->input('community_filter');
            $typeFilter = $request->input('type_filter');
            $yearFilter = $request->input('year_filter');

            if ($request->ajax()) {

                $data = DB::table('energy_systems')
                    ->join('energy_system_types', 'energy_systems.energy_system_type_id', 
                        'energy_system_types.id')
                    ->where('energy_systems.is_archived', 0);

                if($communityFilter != null) {

                    $data->where('energy_systems.community_id', $communityFilter);
                }
                if ($typeFilter != null) {

                    $data->where('energy_system_types.id', $typeFilter);
                }
                if ($yearFilter != null) {

                    $data->where('energy_systems.installation_year', '>=', $yearFilter);
                }

                $data
                ->select('energy_systems.id as id', 'energy_systems.created_at',
                    'energy_systems.updated_at', 'energy_systems.name',
                    'energy_systems.installation_year', 'energy_systems.upgrade_year1',
                    'energy_system_types.name as type',
                    'energy_systems.total_rated_power')
                ->latest();

                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
    
                        $viewButton = "<a type='button' class='viewEnergySystem' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#viewEnergySystemModal' ><i class='fa-solid fa-eye text-info'></i></a>";
                        $updateButton = "<a type='button' class='updateEnergySystem' data-id='".$row->id."' ><i class='fa-solid fa-pen-to-square text-success'></i></a>";
                        $deleteButton = "<a type='button' class='deleteEnergySystem' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";
                        
                        if(Auth::guard('user')->user()->user_type_id == 1 || 
                            Auth::guard('user')->user()->user_type_id == 2 ||
                            Auth::guard('user')->user()->user_type_id == 3 ||
                            Auth::guard('user')->user()->user_type_id == 4) 
                        {
                                
                            return $viewButton." ". $updateButton." ".$deleteButton;
                        } else return $viewButton;
                    })
                   
                    ->filter(function ($instance) use ($request) {
                        if (!empty($request->get('search'))) {
                                $instance->where(function($w) use($request){
                                $search = $request->get('search');
                                $w->orWhere('energy_systems.name', 'LIKE', "%$search%")
                                ->orWhere('energy_systems.installation_year', 'LIKE', "%$search%")
                                ->orWhere('energy_systems.upgrade_year1', 'LIKE', "%$search%")
                                ->orWhere('energy_system_types.name', 'LIKE', "%$search%");
                            });
                        }
                    })
                ->rawColumns(['action'])
                ->make(true);
            }
    
            $communities = Community::where('is_archived', 0)
                ->orderBy('english_name', 'ASC')
                ->get();
            $donors = Donor::paginate();
            $services = ServiceType::all();
    
            $dataEnergySystem = DB::table('energy_systems')
                ->join('energy_system_types', 'energy_systems.energy_system_type_id', 
                    '=', 'energy_system_types.id')
                ->select(
                    DB::raw('energy_system_types.name as name'),
                    DB::raw('count(*) as number'))
                ->groupBy('energy_system_types.name')
                ->get();
            $arrayEnergySystem[] = ['System Type', 'Number'];
            
            foreach($dataEnergySystem as $key => $value) {
    
                $arrayEnergySystem[++$key] = 
                [$value->name, $value->number];
            }

            $energyTypes = EnergySystemType::where('is_archived', 0)
                ->orderBy('name', 'ASC')
                ->get();
        
            $energyCycles = EnergySystemCycle::orderBy('name', 'ASC')
                ->get();

            return view('system.energy.index', compact('communities', 'donors', 'services',
                'energyTypes', 'energyCycles'))
            ->with(
                'energySystemData', json_encode($arrayEnergySystem)
            );
            
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
        $batteries = EnergyBattery::where('is_archived', 0)
            ->orderBy('battery_model', 'ASC')
            ->get();
        $batteryMounts= EnergyBatteryMount::orderBy('model', 'ASC')
            ->get();
        $communities = Community::where('is_archived', 0)
            ->orderBy('english_name', 'ASC')
            ->get();
        $pvs = EnergyPv::where('is_archived', 0)
            ->orderBy('pv_model', 'ASC')
            ->get();
        $pvMounts= EnergyPvMount::orderBy('model', 'ASC')
            ->get();
        $controllers = EnergyChargeController::where('is_archived', 0)
            ->orderBy('charge_controller_model', 'ASC')
            ->get();
        $rccs = EnergyRemoteControlCenter::where('is_archived', 0)
            ->orderBy('model', 'ASC')
            ->get();
        $bsps = EnergyBatteryStatusProcessor::where('is_archived', 0)
            ->orderBy('model', 'ASC')
            ->get();
        $inverters = EnergyInverter::where('is_archived', 0)
            ->orderBy('inverter_model', 'ASC')
            ->get();
        $relayDrivers = EnergyRelayDriver::where('is_archived', 0)
            ->orderBy('model', 'ASC')
            ->get();
        $loadRelaies = EnergyLoadRelay::where('is_archived', 0)
            ->orderBy('load_relay_model', 'ASC')
            ->get();
        $loggers = EnergyMonitoring::where('is_archived', 0)
            ->orderBy('monitoring_model', 'ASC')
            ->get();
        $generators = EnergyGenerator::where('is_archived', 0)
            ->orderBy('generator_model', 'ASC')
            ->get();
        $turbines = EnergyWindTurbine::where('is_archived', 0)
            ->orderBy('wind_turbine_model', 'ASC')
            ->get();
        $mcbControllers = EnergyMcbChargeController::where('is_archived', 0)
            ->orderBy('model', 'ASC')
            ->get();
        $mcbInventors = EnergyMcbInverter::where('is_archived', 0)
            ->orderBy('inverter_MCB_model', 'ASC')
            ->get();
        $mcbPvs = EnergyMcbPv::where('is_archived', 0)
            ->orderBy('model', 'ASC')
            ->get();
        $airConditioners =  EnergyAirConditioner::where('is_archived', 0)
            ->orderBy('model', 'ASC') 
            ->get();
        $energyTypes = EnergySystemType::where('is_archived', 0)->get();
        $energyCycles = EnergySystemCycle::orderBy('name', 'ASC')
            ->get();

        return view('system.energy.create', compact('batteries', 'communities', 'controllers',
            'pvs', 'mcbPvs', 'mcbInventors', 'mcbControllers', 'turbines', 'generators',
            'loggers', 'loadRelaies', 'relayDrivers', 'inverters', 'bsps', 'rccs',
            'energyTypes', 'airConditioners', 'batteryMounts', 'pvMounts', 'energyCycles'));
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
        $last_comet_id = EnergySystem::latest('id')->value('comet_id');

        $energySystem = new EnergySystem();
 
        if($request->community_id) $energySystem->community_id = $request->community_id;
        $energySystem->name = $request->name;
        $energySystem->comet_id = ++$last_comet_id;
        $energySystem->fake_meter_number = 'ES' . ++$last_comet_id;
        $energySystem->installation_year = $request->installation_year;
        if($request->energy_system_cycle_id) $energySystem->energy_system_cycle_id = $request->energy_system_cycle_id;
        $energySystem->energy_system_type_id = $request->energy_system_type_id;
        $energySystem->notes = $request->notes;
        $energySystem->save();

        return redirect('/energy-system')
            ->with('message', 'New Energy System Added Successfully!');
    }

    /**
     * View Edit page.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function editPage($id)
    {
        $energySystem = EnergySystem::findOrFail($id);

        return response()->json($energySystem);
    }

    /**
     * View Edit page.
     *
     * @param  int $id 
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $energySystem = EnergySystem::findOrFail($id);

        $allEnergyMetersCount = AllEnergyMeter::where("is_archived", 0)
            ->where("energy_system_id", $id)
            ->count();

        if($energySystem->energy_system_type_id == 2) {

            if (!EnergySystemFbsCabinet::where("energy_system_id", $id)->exists()) {

                $fbsCabinet = new EnergySystemFbsCabinet();
                $fbsCabinet->energy_system_id = $id;
                $fbsCabinet->unit = $allEnergyMetersCount;
                $fbsCabinet->save();
            }
    
            if (!EnergySystemFbsFan::where("energy_system_id", $id)->exists()) {
    
                $fbsFan = new EnergySystemFbsFan();
                $fbsFan->energy_system_id = $id;
                $fbsFan->unit = $allEnergyMetersCount;
                $fbsFan->save();
            }
    
            if (!EnergySystemFbsLock::where("energy_system_id", $id)->exists()) {
    
                $fbsLock = new EnergySystemFbsLock();
                $fbsLock->energy_system_id = $id;
                $fbsLock->unit = $allEnergyMetersCount;
                $fbsLock->save();
            }
    
            if (!EnergySystemFbsWiring::where("energy_system_id", $id)->exists()) {
    
                $fbsWiring = new EnergySystemFbsWiring();
                $fbsWiring->energy_system_id = $id;
                $fbsWiring->unit = $allEnergyMetersCount;
                $fbsWiring->save();
            }
        }

        $batteries = EnergyBattery::where('is_archived', 0)
            ->orderBy('battery_model', 'ASC')
            ->get();  
        $batteryMounts= EnergyBatteryMount::orderBy('model', 'ASC')
            ->get();
        $communities = Community::where('is_archived', 0)
            ->orderBy('english_name', 'ASC')
            ->get();
        $pvs = EnergyPv::where('is_archived', 0)
            ->orderBy('pv_model', 'ASC')
            ->get();
        $pvMounts= EnergyPvMount::orderBy('model', 'ASC')
            ->get();
        $controllers = EnergyChargeController::where('is_archived', 0)
            ->orderBy('charge_controller_model', 'ASC')
            ->get();
        $rccs = EnergyRemoteControlCenter::where('is_archived', 0)
            ->orderBy('model', 'ASC')
            ->get();
        $bsps = EnergyBatteryStatusProcessor::where('is_archived', 0)
            ->orderBy('model', 'ASC')
            ->get();
        $btss = EnergyBatteryTemperatureSensor::where('is_archived', 0)
            ->orderBy('BTS_model', 'ASC')
            ->get();
        $inverters = EnergyInverter::where('is_archived', 0)
            ->orderBy('inverter_model', 'ASC')
            ->get();
        $relayDrivers = EnergyRelayDriver::where('is_archived', 0)
            ->orderBy('model', 'ASC')
            ->get();
        $loadRelaies = EnergyLoadRelay::where('is_archived', 0)
            ->orderBy('load_relay_model', 'ASC')
            ->get();
        $loggers = EnergyMonitoring::where('is_archived', 0)
            ->orderBy('monitoring_model', 'ASC')
            ->get();
        $generators = EnergyGenerator::where('is_archived', 0)
            ->orderBy('generator_model', 'ASC')
            ->get();
        $turbines = EnergyWindTurbine::where('is_archived', 0)
            ->orderBy('wind_turbine_model', 'ASC')
            ->get();
        $mcbControllers = EnergyMcbChargeController::where('is_archived', 0)
            ->orderBy('model', 'ASC')
            ->get();
        $mcbInventors = EnergyMcbInverter::where('is_archived', 0)
            ->orderBy('inverter_MCB_model', 'ASC')
            ->get();
        $mcbPvs = EnergyMcbPv::where('is_archived', 0)
            ->orderBy('model', 'ASC')
            ->get();
        $airConditioners =  EnergyAirConditioner::where('is_archived', 0)
            ->orderBy('model', 'ASC') 
            ->get();

        $energyTypes = EnergySystemType::where('is_archived', 0)->get();

        $battarySystems = DB::table('energy_system_batteries')
            ->join('energy_systems', 'energy_system_batteries.energy_system_id', 
                '=', 'energy_systems.id')
            ->join('energy_batteries', 'energy_system_batteries.battery_type_id', 
                '=', 'energy_batteries.id')
            ->where('energy_system_batteries.energy_system_id', '=', $id)
            ->select('energy_system_batteries.battery_units', 'energy_batteries.battery_model', 
                'energy_batteries.battery_brand', 'energy_systems.name', 
                'energy_system_batteries.id', 'energy_system_batteries.cost')
            ->get(); 

        $battaryMountSystems = DB::table('energy_system_battery_mounts')
            ->join('energy_systems', 'energy_system_battery_mounts.energy_system_id', 
                'energy_systems.id')
            ->join('energy_battery_mounts', 'energy_system_battery_mounts.energy_battery_mount_id', 
                'energy_battery_mounts.id')
            ->where('energy_system_battery_mounts.energy_system_id', $id)
            ->select('energy_system_battery_mounts.unit', 'energy_battery_mounts.model', 
                'energy_battery_mounts.brand', 'energy_systems.name', 
                'energy_system_battery_mounts.id', 'energy_system_battery_mounts.cost')
            ->get(); 

        $pvSystems = DB::table('energy_system_pvs')
            ->join('energy_systems', 'energy_system_pvs.energy_system_id', 
                '=', 'energy_systems.id')
            ->join('energy_pvs', 'energy_system_pvs.pv_type_id', 
                '=', 'energy_pvs.id')
            ->where('energy_system_pvs.energy_system_id', '=', $id)
            ->select('energy_system_pvs.pv_units', 'energy_pvs.pv_model', 
                'energy_pvs.pv_brand', 'energy_systems.name', 
                'energy_system_pvs.id', 'energy_system_pvs.cost')
            ->get(); 

        $pvMountSystems = DB::table('energy_system_pv_mounts')
            ->join('energy_systems', 'energy_system_pv_mounts.energy_system_id', 
                'energy_systems.id')
            ->join('energy_pv_mounts', 'energy_system_pv_mounts.energy_pv_mount_id', 
                'energy_pv_mounts.id')
            ->where('energy_system_pv_mounts.energy_system_id', $id)
            ->select('energy_system_pv_mounts.unit', 'energy_pv_mounts.model', 
                'energy_pv_mounts.brand', 'energy_systems.name', 
                'energy_system_pv_mounts.id', 'energy_system_pv_mounts.cost')
            ->get(); 

        $controllerSystems = DB::table('energy_system_charge_controllers')
            ->join('energy_systems', 'energy_system_charge_controllers.energy_system_id', 
                '=', 'energy_systems.id')
            ->join('energy_charge_controllers', 'energy_system_charge_controllers.energy_charge_controller_id', 
                '=', 'energy_charge_controllers.id') 
            ->where('energy_system_charge_controllers.energy_system_id', '=', $id)
            ->select('energy_system_charge_controllers.controller_units', 
                'energy_charge_controllers.charge_controller_model', 
                'energy_charge_controllers.charge_controller_brand', 'energy_systems.name', 
                'energy_system_charge_controllers.id', 'energy_system_charge_controllers.cost')
            ->get(); 

        $inverterSystems = DB::table('energy_system_inverters')
            ->join('energy_systems', 'energy_system_inverters.energy_system_id', 
                '=', 'energy_systems.id')
            ->join('energy_inverters', 'energy_system_inverters.energy_inverter_id', 
                '=', 'energy_inverters.id')
            ->where('energy_system_inverters.energy_system_id', '=', $id)
            ->select('energy_system_inverters.inverter_units', 'energy_inverters.inverter_model', 
                'energy_inverters.inverter_brand', 'energy_systems.name', 
                'energy_system_inverters.id', 'energy_system_inverters.cost')
            ->get(); 

        $relayDriverSystems = DB::table('energy_system_relay_drivers')
            ->join('energy_systems', 'energy_system_relay_drivers.energy_system_id', 
                '=', 'energy_systems.id')
            ->join('energy_relay_drivers', 'energy_system_relay_drivers.relay_driver_type_id', 
                '=', 'energy_relay_drivers.id')
            ->where('energy_system_relay_drivers.energy_system_id', '=', $id)
            ->select('energy_system_relay_drivers.relay_driver_units', 'energy_relay_drivers.model', 
                'energy_relay_drivers.brand', 'energy_systems.name', 
                'energy_system_relay_drivers.id', 'energy_system_relay_drivers.cost')
            ->get(); 

        $loadRelaySystems = DB::table('energy_system_load_relays')
            ->join('energy_systems', 'energy_system_load_relays.energy_system_id', 
                '=', 'energy_systems.id')
            ->join('energy_load_relays', 'energy_system_load_relays.energy_load_relay_id', 
                '=', 'energy_load_relays.id')
            ->where('energy_system_load_relays.energy_system_id', '=', $id)
            ->select('energy_system_load_relays.load_relay_units', 'energy_load_relays.load_relay_model', 
                'energy_load_relays.load_relay_brand', 'energy_systems.name', 
                'energy_system_load_relays.id', 'energy_system_load_relays.cost')
            ->get();

        $bspSystems = DB::table('energy_system_battery_status_processors')
            ->join('energy_systems', 'energy_system_battery_status_processors.energy_system_id', 
                '=', 'energy_systems.id')
            ->join('energy_battery_status_processors', 'energy_system_battery_status_processors.energy_battery_status_processor_id', 
                '=', 'energy_battery_status_processors.id')
            ->where('energy_system_battery_status_processors.energy_system_id', '=', $id)
            ->select('energy_system_battery_status_processors.bsp_units', 'energy_systems.name', 
                'energy_battery_status_processors.model', 'energy_battery_status_processors.brand', 
                'energy_system_battery_status_processors.id', 'energy_system_battery_status_processors.cost')
            ->get();

        $rccSystems = DB::table('energy_system_remote_control_centers')
            ->join('energy_systems', 'energy_system_remote_control_centers.energy_system_id', 
                '=', 'energy_systems.id')
            ->join('energy_remote_control_centers', 'energy_system_remote_control_centers.energy_remote_control_center_id', 
                '=', 'energy_remote_control_centers.id')
            ->where('energy_system_remote_control_centers.energy_system_id', '=', $id)
            ->select('energy_system_remote_control_centers.rcc_units', 
                'energy_remote_control_centers.model', 'energy_system_remote_control_centers.cost',
                'energy_remote_control_centers.brand', 'energy_systems.name', 
                'energy_system_remote_control_centers.id')
            ->get();

        $loggerSystems = DB::table('energy_system_monitorings')
            ->join('energy_systems', 'energy_system_monitorings.energy_system_id', 
                '=', 'energy_systems.id')
            ->join('energy_monitorings', 'energy_system_monitorings.energy_monitoring_id', 
                '=', 'energy_monitorings.id')
            ->where('energy_system_monitorings.energy_system_id', '=', $id)
            ->select('energy_system_monitorings.monitoring_units', 
                'energy_monitorings.monitoring_model', 
                'energy_monitorings.monitoring_brand', 'energy_systems.name', 
                'energy_system_monitorings.id', 'energy_system_monitorings.cost')
            ->get();

        $generatorSystems = DB::table('energy_system_generators')
            ->join('energy_systems', 'energy_system_generators.energy_system_id', 
                '=', 'energy_systems.id')
            ->join('energy_generators', 'energy_system_generators.energy_generator_id', 
                '=', 'energy_generators.id')
            ->where('energy_system_generators.energy_system_id', '=', $id)
            ->select('energy_system_generators.generator_units', 
                'energy_generators.generator_model', 
                'energy_generators.generator_brand', 'energy_systems.name', 
                'energy_system_generators.id', 'energy_system_generators.cost')
            ->get();

        $turbineSystems = DB::table('energy_system_wind_turbines')
            ->join('energy_systems', 'energy_system_wind_turbines.energy_system_id', 
                '=', 'energy_systems.id')
            ->join('energy_wind_turbines', 'energy_system_wind_turbines.energy_wind_turbine_id', 
                '=', 'energy_wind_turbines.id')
            ->where('energy_system_wind_turbines.energy_system_id', '=', $id)
            ->select('energy_system_wind_turbines.turbine_units', 
                'energy_wind_turbines.wind_turbine_model', 
                'energy_wind_turbines.wind_turbine_brand', 'energy_systems.name', 
                'energy_system_wind_turbines.id', 'energy_system_wind_turbines.cost')
            ->get();

        $pvMcbSystems = DB::table('energy_system_mcb_pvs')
            ->join('energy_systems', 'energy_system_mcb_pvs.energy_system_id', 
                '=', 'energy_systems.id')
            ->join('energy_mcb_pvs', 'energy_system_mcb_pvs.energy_mcb_pv_id', 
                '=', 'energy_mcb_pvs.id')
            ->where('energy_system_mcb_pvs.energy_system_id', '=', $id)
            ->select('energy_system_mcb_pvs.mcb_pv_units', 
                'energy_mcb_pvs.model', 
                'energy_mcb_pvs.brand', 'energy_systems.name', 
                'energy_system_mcb_pvs.id', 'energy_system_mcb_pvs.cost')
            ->get();

        $controllerMcbSystems = DB::table('energy_system_mcb_charge_controllers')
            ->join('energy_systems', 'energy_system_mcb_charge_controllers.energy_system_id', 
                '=', 'energy_systems.id')
            ->join('energy_mcb_charge_controllers', 
                'energy_system_mcb_charge_controllers.energy_mcb_charge_controller_id', 
                '=', 'energy_mcb_charge_controllers.id')
            ->where('energy_system_mcb_charge_controllers.energy_system_id', '=', $id)
            ->select('energy_system_mcb_charge_controllers.mcb_controller_units', 
                'energy_mcb_charge_controllers.model', 
                'energy_mcb_charge_controllers.brand', 'energy_systems.name', 
                'energy_system_mcb_charge_controllers.id', 'energy_system_mcb_charge_controllers.cost')
            ->get();

        $inventerMcbSystems = DB::table('energy_system_mcb_inverters')
            ->join('energy_systems', 'energy_system_mcb_inverters.energy_system_id', 
                '=', 'energy_systems.id')
            ->join('energy_mcb_inverters', 'energy_system_mcb_inverters.energy_mcb_inverter_id', 
                '=', 'energy_mcb_inverters.id')
            ->where('energy_system_mcb_inverters.energy_system_id', '=', $id)
            ->select('energy_system_mcb_inverters.mcb_inverter_units', 
                'energy_mcb_inverters.inverter_MCB_model', 
                'energy_mcb_inverters.inverter_MCB_brand', 'energy_systems.name', 
                'energy_system_mcb_inverters.id', 'energy_system_mcb_inverters.cost')
            ->get();

        $airConditionerSystems = DB::table('energy_system_air_conditioners')
            ->join('energy_systems', 'energy_system_air_conditioners.energy_system_id', 
                '=', 'energy_systems.id')
            ->join('energy_air_conditioners', 'energy_system_air_conditioners.energy_air_conditioner_id', 
                '=', 'energy_air_conditioners.id')
            ->where('energy_system_air_conditioners.energy_system_id', '=', $id)
            ->select('energy_system_air_conditioners.energy_air_conditioner_units', 
                'energy_air_conditioners.model', 
                'energy_air_conditioners.brand', 'energy_systems.name', 
                'energy_system_air_conditioners.id', 'energy_system_air_conditioners.cost')
            ->get();

        $btsSystems = DB::table('energy_system_battery_temperature_sensors')
            ->join('energy_systems', 'energy_system_battery_temperature_sensors.energy_system_id', 
                'energy_systems.id')
            ->join('energy_battery_temperature_sensors', 'energy_battery_temperature_sensors.id',
                'energy_system_battery_temperature_sensors.energy_battery_temperature_sensor_id')
            ->where('energy_system_battery_temperature_sensors.energy_system_id', '=', $id)
            ->select('energy_system_battery_temperature_sensors.bts_units', 
                'energy_battery_temperature_sensors.BTS_model', 
                'energy_battery_temperature_sensors.BTS_brand', 'energy_systems.name', 
                'energy_system_battery_temperature_sensors.id', 'energy_system_battery_temperature_sensors.cost')
            ->get();

        $energyCycles = EnergySystemCycle::orderBy('name', 'ASC')
            ->get();

        $fbsCabinets = DB::table('energy_system_fbs_cabinets')
            ->join('energy_systems', 'energy_system_fbs_cabinets.energy_system_id', 
                'energy_systems.id')
            ->where('energy_system_fbs_cabinets.energy_system_id', $id)
            ->select('energy_system_fbs_cabinets.unit', 'energy_systems.name', 
                'energy_system_fbs_cabinets.id', 'energy_system_fbs_cabinets.cost')
            ->get();

        $fbsFans = DB::table('energy_system_fbs_fans')
            ->join('energy_systems', 'energy_system_fbs_fans.energy_system_id', 
                'energy_systems.id')
            ->where('energy_system_fbs_fans.energy_system_id', $id)
            ->select('energy_system_fbs_fans.unit', 'energy_systems.name', 
                'energy_system_fbs_fans.id', 'energy_system_fbs_fans.cost')
            ->get();

        $fbsLocks = DB::table('energy_system_fbs_locks')
            ->join('energy_systems', 'energy_system_fbs_locks.energy_system_id', 
                'energy_systems.id')
            ->where('energy_system_fbs_locks.energy_system_id', $id)
            ->select('energy_system_fbs_locks.unit', 'energy_systems.name', 
                'energy_system_fbs_locks.id', 'energy_system_fbs_locks.cost')
            ->get();

        $fbsWirings = DB::table('energy_system_fbs_wirings')
            ->join('energy_systems', 'energy_system_fbs_wirings.energy_system_id', 
                'energy_systems.id')
            ->where('energy_system_fbs_wirings.energy_system_id', $id)
            ->select('energy_system_fbs_wirings.unit', 'energy_systems.name', 
                'energy_system_fbs_wirings.id', 'energy_system_fbs_wirings.cost')
            ->get();

        $houseWirings = DB::table('energy_system_wiring_houses')
            ->join('energy_systems', 'energy_system_wiring_houses.energy_system_id', 
                'energy_systems.id')
            ->where('energy_system_wiring_houses.energy_system_id', $id)
            ->select('energy_system_wiring_houses.unit', 'energy_systems.name', 
                'energy_system_wiring_houses.id', 'energy_system_wiring_houses.cost')
            ->get();

        $electricityRooms = DB::table('energy_system_electricity_rooms')
            ->join('energy_systems', 'energy_system_electricity_rooms.energy_system_id', 
                'energy_systems.id')
            ->where('energy_system_electricity_rooms.energy_system_id', $id)
            ->select('energy_system_electricity_rooms.unit', 'energy_systems.name', 
                'energy_system_electricity_rooms.id', 'energy_system_electricity_rooms.cost')
            ->get();

        $electricityBosRooms = DB::table('energy_system_electricity_bos_rooms')
            ->join('energy_systems', 'energy_system_electricity_bos_rooms.energy_system_id', 
                'energy_systems.id')
            ->where('energy_system_electricity_bos_rooms.energy_system_id', $id)
            ->select('energy_system_electricity_bos_rooms.unit', 'energy_systems.name', 
                'energy_system_electricity_bos_rooms.id', 'energy_system_electricity_bos_rooms.cost')
            ->get();

        $communityGrids = DB::table('energy_system_grids')
            ->join('energy_systems', 'energy_system_grids.energy_system_id', 
                'energy_systems.id')
            ->where('energy_system_grids.energy_system_id', $id)
            ->select('energy_system_grids.unit', 'energy_systems.name', 
                'energy_system_grids.id', 'energy_system_grids.cost')
            ->get();

        $refrigerators = DB::table('energy_system_refrigerator_costs')
            ->join('energy_systems', 'energy_system_refrigerator_costs.energy_system_id', 
                'energy_systems.id')
            ->where('energy_system_refrigerator_costs.energy_system_id', $id)
            ->select('energy_system_refrigerator_costs.unit', 'energy_systems.name', 
                'energy_system_refrigerator_costs.id', 'energy_system_refrigerator_costs.cost')
            ->get();

        return view('system.energy.edit', compact('batteries', 'communities', 'controllers',
            'pvs', 'mcbPvs', 'mcbInventors', 'mcbControllers', 'turbines', 'generators',
            'loggers', 'loadRelaies', 'relayDrivers', 'inverters', 'bsps', 'rccs', 'btss',
            'energyTypes', 'energySystem', 'battarySystems', 'pvSystems', 'controllerSystems',
            'inverterSystems', 'relayDriverSystems', 'loadRelaySystems', 'bspSystems',
            'rccSystems', 'loggerSystems', 'generatorSystems', 'turbineSystems', 'pvMcbSystems',
            'controllerMcbSystems', 'inventerMcbSystems', 'airConditioners', 
            'airConditionerSystems', 'batteryMounts', 'pvMounts', 'battaryMountSystems',
            'pvMountSystems', 'btsSystems', 'energyCycles', 'fbsWirings', 'fbsLocks',
            'fbsFans', 'fbsCabinets', 'houseWirings', 'electricityRooms', 'electricityBosRooms',
            'communityGrids', 'refrigerators'));
    }

    // This function is to update the battery unit & costs
    public function updateBattery($id, $units, $cost)
    {
        $battery = EnergySystemBattery::findOrFail($id);
        $battery->battery_units = $units;
        $battery->cost = $cost;
        $battery->save();

        return response()->json(['success' => 1, 'msg' => 'Battery updated successfully']);
    }

    // This function is to update the battery mount unit & costs
    public function updateBatteryMount($id, $units, $cost)
    {
        $batteryMount = EnergySystemBatteryMount::findOrFail($id);
        $batteryMount->unit = $units;
        $batteryMount->cost = $cost;
        $batteryMount->save();

        return response()->json(['success' => 1, 'msg' => 'Battery Mount updated successfully']);
    }

    // This function is to update the pv unit & costs
    public function updatePv($id, $units, $cost)
    {
        $pv = EnergySystemPv::findOrFail($id);
        $pv->pv_units = $units;
        $pv->cost = $cost;
        $pv->save();

        return response()->json(['success' => 1, 'msg' => 'PV updated successfully']);
    }

    // This function is to update the pv unit & costs
    public function updatePvMount($id, $units, $cost)
    {
        $pv = EnergySystemPvMount::findOrFail($id);
        $pv->unit = $units;
        $pv->cost = $cost;
        $pv->save();

        return response()->json(['success' => 1, 'msg' => 'PV Mount updated successfully']);
    }

    // This function is to update the controller unit & costs
    public function updateChargeController($id, $units, $cost)
    {
        $controller = EnergySystemChargeController::findOrFail($id);
        $controller->controller_units = $units;
        $controller->cost = $cost;
        $controller->save();

        return response()->json(['success' => 1, 'msg' => 'Controller updated successfully']);
    }

    // This function is to update the inverter unit & costs
    public function updateInverter($id, $units, $cost)
    {
        $inverter = EnergySystemInverter::findOrFail($id);
        $inverter->inverter_units = $units;
        $inverter->cost = $cost;
        $inverter->save();

        return response()->json(['success' => 1, 'msg' => 'Inverter updated successfully']);
    }

    // This function is to update the relay driver unit & costs
    public function updateRelayDriver($id, $units, $cost)
    {
        $relayDriver = EnergySystemRelayDriver::findOrFail($id);
        $relayDriver->relay_driver_units = $units;
        $relayDriver->cost = $cost;
        $relayDriver->save();

        return response()->json(['success' => 1, 'msg' => 'Relay Driver updated successfully']);
    }

    // This function is to update the Load Relay unit & costs
    public function updateLoadRelay($id, $units, $cost)
    {
        $loadRelay = EnergySystemLoadRelay::findOrFail($id);
        $loadRelay->load_relay_units = $units;
        $loadRelay->cost = $cost;
        $loadRelay->save();

        return response()->json(['success' => 1, 'msg' => 'Load Relay updated successfully']);
    }

    // This function is to update the Conditioner unit & costs
    public function updateConditioner($id, $units, $cost)
    {
        $airConditioner = EnergySystemAirConditioner::findOrFail($id);
        $airConditioner->energy_air_conditioner_units = $units;
        $airConditioner->cost = $cost;
        $airConditioner->save();

        return response()->json(['success' => 1, 'msg' => 'Air Conditioner updated successfully']);
    }

    // This function is to update the bsp
    public function updateBsp($id, $units, $cost)
    {
        $bsp = EnergySystemBatteryStatusProcessor::findOrFail($id);
        $bsp->bsp_units = $units;
        $bsp->cost = $cost;
        $bsp->save();

        return response()->json(['success' => 1, 'msg' => 'BSP updated successfully']);
    }

    // This function is to update the bts
    public function updateBts($id, $units, $cost)
    {
        $bts = EnergySystemBatteryTemperatureSensor::findOrFail($id);
        $bts->bts_units = $units;
        $bts->cost = $cost;
        $bts->save();

        return response()->json(['success' => 1, 'msg' => 'BTS updated successfully']);
    }

    // This function is to update the rcc
    public function updateRcc($id, $units, $cost)
    {
        $rcc = EnergySystemRemoteControlCenter::findOrFail($id);
        $rcc->rcc_units = $units;
        $rcc->cost = $cost;
        $rcc->save();

        return response()->json(['success' => 1, 'msg' => 'RCC updated successfully']);
    }

    // This function is to update the Generator
    public function updateGenerator($id, $units, $cost)
    {
        $generator = EnergySystemGenerator::findOrFail($id);
        $generator->generator_units = $units;
        $generator->cost = $cost;
        $generator->save();

        return response()->json(['success' => 1, 'msg' => 'Generator updated successfully']);
    }

    // This function is to update the Monitoring
    public function updateLogger($id, $units, $cost)
    {
        $logger = EnergySystemMonitoring::findOrFail($id);
        $logger->monitoring_units = $units;
        $logger->cost = $cost;
        $logger->save();

        return response()->json(['success' => 1, 'msg' => 'Logger updated successfully']);
    }

    // This function is to update the Turbine
    public function updateTurbine($id, $units, $cost)
    {
        $turbine = EnergySystemWindTurbine::findOrFail($id);
        $turbine->turbine_units = $units;
        $turbine->cost = $cost;
        $turbine->save();

        return response()->json(['success' => 1, 'msg' => 'Turbine updated successfully']);
    }

    // This function is to update the MCP PV
    public function updateMcbPv($id, $units, $cost)
    {
        $mcbPv = EnergySystemMcbPv::findOrFail($id);
        $mcbPv->mcb_pv_units = $units;
        $mcbPv->cost = $cost;
        $mcbPv->save();

        return response()->json(['success' => 1, 'msg' => 'MCP PV updated successfully']);
    }
    
    // This function is to update the MCP Controller
    public function updateMcbController($id, $units, $cost)
    {
        $mcbController = EnergySystemMcbChargeController::findOrFail($id);
        $mcbController->mcb_controller_units = $units;
        $mcbController->cost = $cost;
        $mcbController->save();

        return response()->json(['success' => 1, 'msg' => 'MCP Controller updated successfully']);
    }

    // This function is to update the MCP Inverter
    public function updateMcbInverter($id, $units, $cost)
    {
        $mcbInverter = EnergySystemMcbInverter::findOrFail($id);
        $mcbInverter->mcb_inverter_units = $units;
        $mcbInverter->cost = $cost;
        $mcbInverter->save();

        return response()->json(['success' => 1, 'msg' => 'MCP Inverter updated successfully']);
    }

    // This function is to update the FBS Cabinet
    public function updateCabinet($id, $units, $cost)
    {
        $fbsCabinet = EnergySystemFbsCabinet::findOrFail($id);
        $fbsCabinet->unit = $units;
        $fbsCabinet->cost = $cost;
        $fbsCabinet->save();

        return response()->json(['success' => 1, 'msg' => 'FBS Cabinet updated successfully']);
    }

    // This function is to update the FBS Fan
    public function updateFan($id, $units, $cost)
    {
        $fbsFan = EnergySystemFbsFan::findOrFail($id);
        $fbsFan->unit = $units;
        $fbsFan->cost = $cost;
        $fbsFan->save();

        return response()->json(['success' => 1, 'msg' => 'FBS Fan updated successfully']);
    }

    // This function is to update the FBS Lock
    public function updateLock($id, $units, $cost)
    {
        $fbsLock = EnergySystemFbsLock::findOrFail($id);
        $fbsLock->unit = $units;
        $fbsLock->cost = $cost;
        $fbsLock->save();

        return response()->json(['success' => 1, 'msg' => 'FBS Lock updated successfully']);
    }

    // This function is to update the FBS Wiring
    public function updateWiring($id, $units, $cost)
    {
        $fbsWiring = EnergySystemFbsWiring::findOrFail($id);
        $fbsWiring->unit = $units;
        $fbsWiring->cost = $cost;
        $fbsWiring->save();

        return response()->json(['success' => 1, 'msg' => 'FBS Wiring updated successfully']);
    }

    // This function is to update the House Wiring
    public function updateWiringHouse($id, $units, $cost)
    {
        $houseWiring = EnergySystemWiringHouse::findOrFail($id);
        $houseWiring->unit = $units;
        $houseWiring->cost = $cost;
        $houseWiring->save();

        return response()->json(['success' => 1, 'msg' => 'House Wiring updated successfully']);
    }

    // This function is to update the Electricity room
    public function updateElectricityRoom($id, $units, $cost)
    {
        $electricityRoom = EnergySystemElectricityRoom::findOrFail($id);
        $electricityRoom->unit = $units;
        $electricityRoom->cost = $cost;
        $electricityRoom->save();

        return response()->json(['success' => 1, 'msg' => 'Electricity Room updated successfully']);
    }

    // This function is to update the Electricity bos room
    public function updateElectricityBosRoom($id, $units, $cost)
    {
        $electricityBosRoom = EnergySystemElectricityBosRoom::findOrFail($id);
        $electricityBosRoom->unit = $units;
        $electricityBosRoom->cost = $cost;
        $electricityBosRoom->save();

        return response()->json(['success' => 1, 'msg' => 'Electricity Bos updated successfully']);
    }

    // This function is to update the Grid
    public function updateGrid($id, $units, $cost)
    {
        $communityGrid = EnergySystemGrid::findOrFail($id);
        $communityGrid->unit = $units;
        $communityGrid->cost = $cost;
        $communityGrid->save();

        return response()->json(['success' => 1, 'msg' => 'Community Grid updated successfully']);
    }

    // This function is to update the Refrigerator
    public function updateRefrigerator($id, $units, $cost)
    {
        $refrigerator = EnergySystemRefrigeratorCost::findOrFail($id);
        $refrigerator->unit = $units;
        $refrigerator->cost = $cost;
        $refrigerator->save();

        return response()->json(['success' => 1, 'msg' => 'Refrigerator updated successfully']);
    }
    

    /**
     * Update an existing resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request, int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //dd($request->all());
        $energySystem = EnergySystem::findOrFail($id);
 
        if($request->name) $energySystem->name = $request->name;
        if($request->installation_year) $energySystem->installation_year = $request->installation_year;
        if($request->energy_system_cycle_id) $energySystem->energy_system_cycle_id = $request->energy_system_cycle_id;
        if($request->upgrade_year1) $energySystem->upgrade_year1 = $request->upgrade_year1;
        if($request->upgrade_year2) $energySystem->upgrade_year2 = $request->upgrade_year2;
        if($request->total_rated_power == null) $energySystem->total_rated_power = null;
        if($request->total_rated_power) $energySystem->total_rated_power = $request->total_rated_power;
        if($request->generated_power == null) $energySystem->generated_power = null;
        if($request->generated_power) $energySystem->generated_power = $request->generated_power;
        if($request->turbine_power == null) $energySystem->turbine_power = null;
        if($request->turbine_power) $energySystem->turbine_power = $request->turbine_power;
        if($request->energy_system_type_id) $energySystem->energy_system_type_id = $request->energy_system_type_id;
        $energySystem->notes = $request->notes;
        $energySystem->save();

        // Battery  
        if ($request->battery_ids) {
            for ($cnq = 0; $cnq < count($request->battery_ids); $cnq++) {

                $internetBattery = new EnergySystemBattery();
                $internetBattery->battery_type_id = $request->battery_ids[$cnq];
                $internetBattery->energy_system_id = $id;
                $internetBattery->battery_units = $request->input("battery_units.$cnq.subject") ?? 0;
                $internetBattery->cost = $request->input("battery_costs.$cnq.subject") ?? 0;
        
                $internetBattery->save();
            }
        }

        // Battery Mount
        if ($request->battery_mount_ids) {
            for ($btm = 0; $btm < count($request->battery_mount_ids); $btm++) {

                $energyBatteryMount = new EnergySystemBatteryMount();
                $energyBatteryMount->energy_battery_mount_id = $request->battery_mount_ids[$btm];
                $energyBatteryMount->energy_system_id = $id;
                $energyBatteryMount->unit = $request->input("battery_mount_units.$btm.subject") ?? 0;
                $energyBatteryMount->cost = $request->input("battery_mount_costs.$btm.subject") ?? 0;
        
                $energyBatteryMount->save();
            }
        }

        // Solar Panel
        if ($request->pv_ids) {
            for ($pv = 0; $pv < count($request->pv_ids); $pv++) {

                $energyPv = new EnergySystemPv();
                $energyPv->pv_type_id = $request->pv_ids[$pv];
                $energyPv->energy_system_id = $id;
                $energyPv->pv_units = $request->input("pv_units.$pv.subject") ?? 0;
                $energyPv->cost = $request->input("pv_costs.$pv.subject") ?? 0;
        
                $energyPv->save();
            }
        }

        // Solar Panel Mount
        if ($request->pv_mount_ids) {
            for ($pvm = 0; $pvm < count($request->pv_mount_ids); $pvm++) {

                $energyPvMount = new EnergySystemPvMount();
                $energyPvMount->energy_pv_mount_id = $request->pv_mount_ids[$pvm];
                $energyPvMount->energy_system_id = $id;
                $energyPvMount->unit = $request->input("pv_mount_units.$pvm.subject") ?? 0;
                $energyPvMount->cost = $request->input("pv_mount_costs.$pvm.subject") ?? 0;
        
                $energyPvMount->save();
            }
        }

        // Controller
        if ($request->controller_ids) {
            for ($contr = 0; $contr < count($request->controller_ids); $contr++) {

                $energyController = new EnergySystemChargeController();
                $energyController->energy_charge_controller_id = $request->controller_ids[$contr];
                $energyController->energy_system_id = $id;
                $energyController->controller_units = $request->input("controller_units.$contr.subject") ?? 0;
                $energyController->cost = $request->input("controller_costs.$contr.subject") ?? 0;
        
                $energyController->save();
            }
        }

        // Inverter
        if ($request->inverter_ids) {
            for ($invr = 0; $invr < count($request->inverter_ids); $invr++) {

                $energyInverter = new EnergySystemInverter();
                $energyInverter->energy_inverter_id = $request->inverter_ids[$invr];
                $energyInverter->energy_system_id = $id;
                $energyInverter->inverter_units = $request->input("inverter_units.$invr.subject") ?? 0;
                $energyInverter->cost = $request->input("inverter_costs.$invr.subject") ?? 0;
        
                $energyInverter->save();
            }
        }

        // Relay Driver
        if ($request->relay_driver_ids) {
            for ($invr = 0; $invr < count($request->relay_driver_ids); $invr++) {

                $energyRelayDriver = new EnergySystemRelayDriver();
                $energyRelayDriver->relay_driver_type_id = $request->relay_driver_ids[$invr];
                $energyRelayDriver->energy_system_id = $id;
                $energyRelayDriver->relay_driver_units = $request->input("relay-driver_units.$invr.subject") ?? 0;
                $energyRelayDriver->cost = $request->input("relay-driver_costs.$invr.subject") ?? 0;
        
                $energyRelayDriver->save();
            }
        }

        // Load Relay
        if ($request->load_relay_ids) {
            for ($i = 0; $i < count($request->load_relay_ids); $i++) {

                $energyLoadRelay = new EnergySystemLoadRelay();
                $energyLoadRelay->energy_load_relay_id = $request->load_relay_ids[$i];
                $energyLoadRelay->energy_system_id = $id;
                $energyLoadRelay->load_relay_units = $request->input("load-relay-units.$i.subject") ?? 0;
                $energyLoadRelay->cost = $request->input("load-relay-costs.$i.subject") ?? 0;
                $energyLoadRelay->save();
            }
        }

        // BSP Status Processor
        if ($request->bsp_ids) {
            for ($bspr = 0; $bspr < count($request->bsp_ids); $bspr++) {

                $energyBsp = new EnergySystemBatteryStatusProcessor();
                $energyBsp->energy_battery_status_processor_id = $request->bsp_ids[$bspr];
                $energyBsp->energy_system_id = $id;
                $energyBsp->bsp_units = $request->input("bsp_units.$bspr.subject") ?? 0;
                $energyBsp->cost = $request->input("bsp_costs.$bspr.subject") ?? 0;
        
                $energyBsp->save();
            }
        }

        // Logger
        if ($request->logger_ids) {
            for ($logr = 0; $logr < count($request->logger_ids); $logr++) {

                $energyLogger = new EnergySystemMonitoring();
                $energyLogger->energy_monitoring_id = $request->logger_ids[$logr];
                $energyLogger->energy_system_id = $id;
                $energyLogger->monitoring_units = $request->input("logger_units.$logr.subject") ?? 0;
                $energyLogger->cost = $request->input("logger_costs.$logr.subject") ?? 0;
        
                $energyLogger->save();
            }
        }

        // BTS
        if ($request->bts_ids) {
            for ($bspr = 0; $bspr < count($request->bts_ids); $bspr++) {

                $energyBts = new EnergySystemBatteryTemperatureSensor();
                $energyBts->energy_battery_temperature_sensor_id = $request->bts_ids[$bspr];
                $energyBts->energy_system_id = $id;
                $energyBts->bts_units = $request->input("bts_units.$bspr.subject") ?? 0;
                $energyBts->cost = $request->input("bts_costs.$bspr.subject") ?? 0;
        
                $energyBts->save();
            }
        }

        // Remote Control Center
        if ($request->rcc_ids) {
            for ($rccc = 0; $rccc < count($request->rcc_ids); $rccc++) {

                $energyRcc = new EnergySystemRemoteControlCenter();
                $energyRcc->energy_remote_control_center_id = $request->rcc_ids[$rccc];
                $energyRcc->energy_system_id = $id;
                $energyRcc->rcc_units = $request->input("rcc_units.$rccc.subject") ?? 0;
                $energyRcc->cost = $request->input("rcc_costs.$rccc.subject") ?? 0;
        
                $energyRcc->save();
            }
        }

        // Generator
        if ($request->generator_ids) {
            for ($gnidx = 0; $gnidx < count($request->generator_ids); $gnidx++) {

                $energyGenerator = new EnergySystemGenerator();
                $energyGenerator->energy_generator_id = $request->generator_ids[$gnidx];
                $energyGenerator->energy_system_id = $id;
                $energyGenerator->generator_units = $request->input("generator_units.$gnidx.subject") ?? 0;
                $energyGenerator->cost = $request->input("generator_costs.$gnidx.subject") ?? 0;
        
                $energyGenerator->save();
            }
        }

        // Wind Turbine
        if ($request->turbine_ids) {
            for ($turr = 0; $turr < count($request->turbine_ids); $turr++) {

                $energyTurbine = new EnergySystemWindTurbine();
                $energyTurbine->energy_wind_turbine_id = $request->turbine_ids[$turr];
                $energyTurbine->energy_system_id = $id;
                $energyTurbine->turbine_units = $request->input("turbine_units.$turr.subject") ?? 0;
                $energyTurbine->cost = $request->input("turbine_costs.$turr.subject") ?? 0;
        
                $energyTurbine->save();
            }
        }

        // Solar Panel MCB
        if ($request->mcb_pv_ids) {
            for ($mcbpv = 0; $mcbpv < count($request->mcb_pv_ids); $mcbpv++) {

                $energyMcbPv = new EnergySystemMcbPv();
                $energyMcbPv->energy_mcb_pv_id = $request->mcb_pv_ids[$mcbpv];
                $energyMcbPv->energy_system_id = $id;
                $energyMcbPv->mcb_pv_units = $request->input("mcb_pv_units.$mcbpv.subject") ?? 0;
                $energyMcbPv->cost = $request->input("mcb_pv_costs.$mcbpv.subject") ?? 0;
        
                $energyMcbPv->save();
            }
        }

        // Charge Controllers MCB
        if ($request->mcb_controller_ids) {
            for ($mcbcon = 0; $mcbcon < count($request->mcb_controller_ids); $mcbcon++) {

                $energyMcbController = new EnergySystemMcbChargeController();
                $energyMcbController->energy_mcb_charge_controller_id = $request->mcb_controller_ids[$mcbcon];
                $energyMcbController->energy_system_id = $id;
                $energyMcbController->mcb_controller_units = $request->input("mcb_controller_units.$mcbcon.subject") ?? 0;
                $energyMcbController->cost = $request->input("mcb_controller_costs.$mcbcon.subject") ?? 0;
        
                $energyMcbController->save();
            }
        }

        // Inverter MCB
        if ($request->mcb_inverter_ids) {
            for ($mcbin = 0; $mcbin < count($request->mcb_inverter_ids); $mcbin++) {

                $energyMcbInverter = new EnergySystemMcbInverter();
                $energyMcbInverter->energy_mcb_inverter_id = $request->mcb_inverter_ids[$mcbin];
                $energyMcbInverter->energy_system_id = $id;
                $energyMcbInverter->mcb_inverter_units = $request->input("mcb_inverter_units.$mcbin.subject") ?? 0;
                $energyMcbInverter->cost = $request->input("mcb_inverter_costs.$mcbin.subject") ?? 0;
        
                $energyMcbInverter->save();
            }
        }

        // Air Conditioner
        if ($request->conditioner_ids) {
            for ($airc = 0; $airc < count($request->conditioner_ids); $airc++) {

                $internetConditioner = new EnergySystemAirConditioner();
                $internetConditioner->energy_air_conditioner_id = $request->conditioner_ids[$airc];
                $internetConditioner->energy_system_id = $id;
                $internetConditioner->energy_air_conditioner_units = $request->input("conditioner_units.$airc.subject") ?? 0;
                $internetConditioner->cost = $request->input("conditioner_costs.$airc.subject") ?? 0;
        
                $internetConditioner->save();
            }
        }

        // Wiring House
        if ($request->wiring_house_units) {

            $existing = EnergySystemWiringHouse::where('energy_system_id', $id)->exists();

            if(!$existing) {
                for ($airc = 0; $airc < count($request->wiring_house_units); $airc++) {
    
                    $wiringHouse = new EnergySystemWiringHouse();
                    $wiringHouse->energy_system_id = $id;
                    if($energySystem->energy_system_type_id == 2) {
    
                        $allEnergyMetersCount = AllEnergyMeter::where("is_archived", 0)
                            ->where("energy_system_id", $id)
                            ->where("energy_system_type_id", 2)
                            ->count();
                        $wiringHouse->unit = $allEnergyMetersCount;
                    } else {
    
                        $wiringHouse->unit = $request->input("wiring_house_units.$airc.subject") ?? 0;
                    }
                    $wiringHouse->cost = $request->input("wiring_house_costs.$airc.subject") ?? 0;
            
                    $wiringHouse->save();
                }
            }
        }

        // Electricity Room
        if ($request->electricity_room_units) {
            // Check if already inserted
            $existing = EnergySystemElectricityRoom::where('energy_system_id', $id)->exists();
        
            if (!$existing) {
                for ($elcro = 0; $elcro < count($request->electricity_room_units); $elcro++) {

                    $electricityRoom = new EnergySystemElectricityRoom();
                    $electricityRoom->energy_system_id = $id;
        
                    if ($energySystem->energy_system_type_id != 2) {
                        
                        $electricityRoom->unit = $request->input("electricity_room_units.$elcro.subject") ?? 0;
                    }
        
                    $electricityRoom->cost = $request->input("electricity_room_costs.$elcro.subject") ?? 0;
                    $electricityRoom->save();
                }
            }
        }
        

        // Electricity BOS Room
        if ($request->electricity_bos_room_units) {
            // Check if BOS room data already exists for this energy system
            $existingBos = EnergySystemElectricityBosRoom::where('energy_system_id', $id)->exists();

            if (!$existingBos) {
                for ($elcbr = 0; $elcbr < count($request->electricity_bos_room_units); $elcbr++) {
                    $electricityBosRoom = new EnergySystemElectricityBosRoom();
                    $electricityBosRoom->energy_system_id = $id;

                    if ($energySystem->energy_system_type_id != 2) {
                        
                        $electricityBosRoom->unit = $request->input("electricity_bos_room_units.$elcbr.subject") ?? 0;
                    }

                    $electricityBosRoom->cost = $request->input("electricity_bos_room_costs.$elcbr.subject") ?? 0;

                    $electricityBosRoom->save();
                }
            }
        }

        // Grid
        if ($request->grid_units) {

            $existingGrid = EnergySystemGrid::where('energy_system_id', $id)->exists();

            if (!$existingGrid) {

                for ($enrg = 0; $enrg < count($request->grid_units); $enrg++) {

                    $energyGrid = new EnergySystemGrid();
                    $energyGrid->energy_system_id = $id;

                    if ($energySystem->energy_system_type_id != 2) {

                        $energyGrid->unit = $request->input("grid_units.$enrg.subject") ?? 0;
                    }

                    $energyGrid->cost = $request->input("grid_costs.$enrg.subject") ?? 0;

                    $energyGrid->save();
                }
            }
        }

        // Refrigerator
        if ($request->refrigerator_units) {

            $existingRefrigerator = EnergySystemRefrigeratorCost::where('energy_system_id', $id)->exists();

            if (!$existingRefrigerator) {

                for ($enrg = 0; $enrg < count($request->refrigerator_units); $enrg++) {

                    $energyRefrigerator = new EnergySystemRefrigeratorCost();
                    $energyRefrigerator->energy_system_id = $id;

                    if ($energySystem->energy_system_type_id == 2) {

                        $allEnergyMetersCount = AllEnergyMeter::where("is_archived", 0)
                            ->where("energy_system_id", $id)
                            ->where("energy_system_type_id", 2)
                            ->count();
                        $energyRefrigerator->unit = $allEnergyMetersCount;
                    } else {

                        $energyRefrigerator->unit = $request->input("refrigerator_units.$enrg.subject") ?? 0;
                    }

                    $energyRefrigerator->cost = $request->input("refrigerator_costs.$enrg.subject") ?? 0;

                    $energyRefrigerator->save();
                }
            }
        }

        return redirect('/energy-system')->with('message', 'Energy System Updated Successfully!');
    }

    /**
     * Show the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function showPage($id)
    {
        $energySystem = EnergySystem::findOrFail($id);

        return response()->json($energySystem);
    }

    /**
     * View show page.
     *
     * @param  int $id 
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $energySystem = EnergySystem::findOrFail($id);

        $battarySystems = DB::table('energy_system_batteries')
            ->join('energy_systems', 'energy_system_batteries.energy_system_id', 
                '=', 'energy_systems.id')
            ->join('energy_batteries', 'energy_system_batteries.battery_type_id', 
                '=', 'energy_batteries.id')
            ->where('energy_system_batteries.energy_system_id', '=', $id)
            ->select('energy_system_batteries.battery_units', 'energy_batteries.battery_model', 
                'energy_batteries.battery_brand', 'energy_systems.name', 
                'energy_system_batteries.id', 'energy_system_batteries.cost')
            ->get(); 

        $battaryMountSystems = DB::table('energy_system_battery_mounts')
            ->join('energy_systems', 'energy_system_battery_mounts.energy_system_id', 
                'energy_systems.id')
            ->join('energy_battery_mounts', 'energy_system_battery_mounts.energy_battery_mount_id', 
                'energy_battery_mounts.id')
            ->where('energy_system_battery_mounts.energy_system_id', $id)
            ->select('energy_system_battery_mounts.unit', 'energy_battery_mounts.model', 
                'energy_battery_mounts.brand', 'energy_systems.name', 
                'energy_system_battery_mounts.id', 'energy_system_battery_mounts.cost')
            ->get(); 

        $pvMountSystems = DB::table('energy_system_pv_mounts')
            ->join('energy_systems', 'energy_system_pv_mounts.energy_system_id', 
                'energy_systems.id')
            ->join('energy_pv_mounts', 'energy_system_pv_mounts.energy_pv_mount_id', 
                'energy_pv_mounts.id')
            ->where('energy_system_pv_mounts.energy_system_id', $id)
            ->select('energy_system_pv_mounts.unit', 'energy_pv_mounts.model', 
                'energy_pv_mounts.brand', 'energy_systems.name', 
                'energy_system_pv_mounts.id', 'energy_system_pv_mounts.cost')
            ->get(); 

        $pvSystems = DB::table('energy_system_pvs')
            ->join('energy_systems', 'energy_system_pvs.energy_system_id', 
                '=', 'energy_systems.id')
            ->join('energy_pvs', 'energy_system_pvs.pv_type_id', 
                '=', 'energy_pvs.id')
            ->where('energy_system_pvs.energy_system_id', '=', $id)
            ->select('energy_system_pvs.pv_units', 'energy_pvs.pv_model', 
                'energy_pvs.pv_brand', 'energy_systems.name', 
                'energy_system_pvs.id', 'energy_system_pvs.cost')
            ->get(); 

        $controllerSystems = DB::table('energy_system_charge_controllers')
            ->join('energy_systems', 'energy_system_charge_controllers.energy_system_id', 
                '=', 'energy_systems.id')
            ->join('energy_charge_controllers', 'energy_system_charge_controllers.energy_charge_controller_id', 
                '=', 'energy_charge_controllers.id')
            ->where('energy_system_charge_controllers.energy_system_id', '=', $id)
            ->select('energy_system_charge_controllers.controller_units', 
                'energy_charge_controllers.charge_controller_model', 
                'energy_charge_controllers.charge_controller_brand', 'energy_systems.name', 
                'energy_system_charge_controllers.id', 'energy_system_charge_controllers.cost')
            ->get(); 

        $inverterSystems = DB::table('energy_system_inverters')
            ->join('energy_systems', 'energy_system_inverters.energy_system_id', 
                '=', 'energy_systems.id')
            ->join('energy_inverters', 'energy_system_inverters.energy_inverter_id', 
                '=', 'energy_inverters.id')
            ->where('energy_system_inverters.energy_system_id', '=', $id)
            ->select('energy_system_inverters.inverter_units', 'energy_inverters.inverter_model', 
                'energy_inverters.inverter_brand', 'energy_systems.name', 
                'energy_system_inverters.id', 'energy_system_inverters.cost')
            ->get(); 

        $relayDriverSystems = DB::table('energy_system_relay_drivers')
            ->join('energy_systems', 'energy_system_relay_drivers.energy_system_id', 
                '=', 'energy_systems.id')
            ->join('energy_relay_drivers', 'energy_system_relay_drivers.relay_driver_type_id', 
                '=', 'energy_relay_drivers.id')
            ->where('energy_system_relay_drivers.energy_system_id', '=', $id)
            ->select('energy_system_relay_drivers.relay_driver_units', 'energy_relay_drivers.model', 
                'energy_relay_drivers.brand', 'energy_systems.name', 
                'energy_system_relay_drivers.id', 'energy_system_relay_drivers.cost')
            ->get(); 

        $loadRelaySystems = DB::table('energy_system_load_relays')
            ->join('energy_systems', 'energy_system_load_relays.energy_system_id', 
                '=', 'energy_systems.id')
            ->join('energy_load_relays', 'energy_system_load_relays.energy_load_relay_id', 
                '=', 'energy_load_relays.id')
            ->where('energy_system_load_relays.energy_system_id', '=', $id)
            ->select('energy_system_load_relays.load_relay_units', 'energy_load_relays.load_relay_model', 
                'energy_load_relays.load_relay_brand', 'energy_systems.name', 
                'energy_system_load_relays.id', 'energy_system_load_relays.cost')
            ->get();

        $bspSystems = DB::table('energy_system_battery_status_processors')
            ->join('energy_systems', 'energy_system_battery_status_processors.energy_system_id', 
                '=', 'energy_systems.id')
            ->join('energy_battery_status_processors', 'energy_system_battery_status_processors.energy_battery_status_processor_id', 
                '=', 'energy_battery_status_processors.id')
            ->where('energy_system_battery_status_processors.energy_system_id', '=', $id)
            ->select('energy_system_battery_status_processors.bsp_units', 'energy_systems.name', 
                'energy_battery_status_processors.model', 'energy_battery_status_processors.brand', 
                'energy_system_battery_status_processors.id', 'energy_system_battery_status_processors.cost')
            ->get();

        $rccSystems = DB::table('energy_system_remote_control_centers')
            ->join('energy_systems', 'energy_system_remote_control_centers.energy_system_id', 
                '=', 'energy_systems.id')
            ->join('energy_remote_control_centers', 'energy_system_remote_control_centers.energy_remote_control_center_id', 
                '=', 'energy_remote_control_centers.id')
            ->where('energy_system_remote_control_centers.energy_system_id', '=', $id)
            ->select('energy_system_remote_control_centers.rcc_units', 
                'energy_remote_control_centers.model', 
                'energy_remote_control_centers.brand', 'energy_systems.name', 
                'energy_system_remote_control_centers.id', 'energy_system_remote_control_centers.cost')
            ->get();

        $loggerSystems = DB::table('energy_system_monitorings')
            ->join('energy_systems', 'energy_system_monitorings.energy_system_id', 
                '=', 'energy_systems.id')
            ->join('energy_monitorings', 'energy_system_monitorings.energy_monitoring_id', 
                '=', 'energy_monitorings.id')
            ->where('energy_system_monitorings.energy_system_id', '=', $id)
            ->select('energy_system_monitorings.monitoring_units', 
                'energy_monitorings.monitoring_model', 
                'energy_monitorings.monitoring_brand', 'energy_systems.name', 
                'energy_system_monitorings.id', 'energy_system_monitorings.cost')
            ->get();

        $generatorSystems = DB::table('energy_system_generators')
            ->join('energy_systems', 'energy_system_generators.energy_system_id', 
                '=', 'energy_systems.id')
            ->join('energy_generators', 'energy_system_generators.energy_generator_id', 
                '=', 'energy_generators.id')
            ->where('energy_system_generators.energy_system_id', '=', $id)
            ->select('energy_system_generators.generator_units', 
                'energy_generators.generator_model', 
                'energy_generators.generator_brand', 'energy_systems.name', 
                'energy_system_generators.id', 'energy_system_generators.cost')
            ->get();

        $turbineSystems = DB::table('energy_system_wind_turbines')
            ->join('energy_systems', 'energy_system_wind_turbines.energy_system_id', 
                '=', 'energy_systems.id')
            ->join('energy_wind_turbines', 'energy_system_wind_turbines.energy_wind_turbine_id', 
                '=', 'energy_wind_turbines.id')
            ->where('energy_system_wind_turbines.energy_system_id', '=', $id)
            ->select('energy_system_wind_turbines.turbine_units', 
                'energy_wind_turbines.wind_turbine_model', 
                'energy_wind_turbines.wind_turbine_brand', 'energy_systems.name', 
                'energy_system_wind_turbines.id', 'energy_system_wind_turbines.cost')
            ->get();

        $pvMcbSystems = DB::table('energy_system_mcb_pvs')
            ->join('energy_systems', 'energy_system_mcb_pvs.energy_system_id', 
                '=', 'energy_systems.id')
            ->join('energy_mcb_pvs', 'energy_system_mcb_pvs.energy_mcb_pv_id', 
                '=', 'energy_mcb_pvs.id')
            ->where('energy_system_mcb_pvs.energy_system_id', '=', $id)
            ->select('energy_system_mcb_pvs.mcb_pv_units', 
                'energy_mcb_pvs.model', 
                'energy_mcb_pvs.brand', 'energy_systems.name', 
                'energy_system_mcb_pvs.id', 'energy_system_mcb_pvs.cost')
            ->get();

        $controllerMcbSystems = DB::table('energy_system_mcb_charge_controllers')
            ->join('energy_systems', 'energy_system_mcb_charge_controllers.energy_system_id', 
                '=', 'energy_systems.id')
            ->join('energy_mcb_charge_controllers', 
                'energy_system_mcb_charge_controllers.energy_mcb_charge_controller_id', 
                '=', 'energy_mcb_charge_controllers.id')
            ->where('energy_system_mcb_charge_controllers.energy_system_id', '=', $id)
            ->select('energy_system_mcb_charge_controllers.mcb_controller_units', 
                'energy_mcb_charge_controllers.model', 
                'energy_mcb_charge_controllers.brand', 'energy_systems.name', 
                'energy_system_mcb_charge_controllers.id', 'energy_system_mcb_charge_controllers.cost')
            ->get();

        $inventerMcbSystems = DB::table('energy_system_mcb_inverters')
            ->join('energy_systems', 'energy_system_mcb_inverters.energy_system_id', 
                '=', 'energy_systems.id')
            ->join('energy_mcb_inverters', 'energy_system_mcb_inverters.energy_mcb_inverter_id', 
                '=', 'energy_mcb_inverters.id')
            ->where('energy_system_mcb_inverters.energy_system_id', '=', $id)
            ->select('energy_system_mcb_inverters.mcb_inverter_units', 
                'energy_mcb_inverters.inverter_MCB_model', 
                'energy_mcb_inverters.inverter_MCB_brand', 'energy_systems.name', 
                'energy_system_mcb_inverters.id', 'energy_system_mcb_inverters.cost')
            ->get();

        $airConditionerSystems = DB::table('energy_system_air_conditioners')
            ->join('energy_systems', 'energy_system_air_conditioners.energy_system_id', 
                '=', 'energy_systems.id')
            ->join('energy_air_conditioners', 'energy_system_air_conditioners.energy_air_conditioner_id', 
                '=', 'energy_air_conditioners.id')
            ->where('energy_system_air_conditioners.energy_system_id', '=', $id)
            ->select('energy_system_air_conditioners.energy_air_conditioner_units', 
                'energy_air_conditioners.model', 
                'energy_air_conditioners.brand', 'energy_systems.name', 
                'energy_system_air_conditioners.id', 'energy_system_air_conditioners.cost')
            ->get();

        $btsSystems = DB::table('energy_system_battery_temperature_sensors')
            ->join('energy_systems', 'energy_system_battery_temperature_sensors.energy_system_id', 
                'energy_systems.id')
            ->join('energy_battery_temperature_sensors', 'energy_battery_temperature_sensors.id',
                'energy_system_battery_temperature_sensors.energy_battery_temperature_sensor_id')
            ->where('energy_system_battery_temperature_sensors.energy_system_id', '=', $id)
            ->select('energy_system_battery_temperature_sensors.bts_units', 
                'energy_battery_temperature_sensors.BTS_model', 
                'energy_battery_temperature_sensors.BTS_brand', 'energy_systems.name', 
                'energy_system_battery_temperature_sensors.id', 'energy_system_battery_temperature_sensors.cost')
            ->get();

        // FBS
        $fbsCabinets = DB::table('energy_system_fbs_cabinets')
            ->join('energy_systems', 'energy_system_fbs_cabinets.energy_system_id', 
                'energy_systems.id')
            ->where('energy_system_fbs_cabinets.energy_system_id', $id)
            ->select('energy_system_fbs_cabinets.unit', 'energy_systems.name', 
                'energy_system_fbs_cabinets.id', 'energy_system_fbs_cabinets.cost')
            ->get();

        $fbsFans = DB::table('energy_system_fbs_fans')
            ->join('energy_systems', 'energy_system_fbs_fans.energy_system_id', 
                'energy_systems.id')
            ->where('energy_system_fbs_fans.energy_system_id', $id)
            ->select('energy_system_fbs_fans.unit', 'energy_systems.name', 
                'energy_system_fbs_fans.id', 'energy_system_fbs_fans.cost')
            ->get();

        $fbsLocks = DB::table('energy_system_fbs_locks')
            ->join('energy_systems', 'energy_system_fbs_locks.energy_system_id', 
                'energy_systems.id')
            ->where('energy_system_fbs_locks.energy_system_id', $id)
            ->select('energy_system_fbs_locks.unit', 'energy_systems.name', 
                'energy_system_fbs_locks.id', 'energy_system_fbs_locks.cost')
            ->get();

        $fbsWirings = DB::table('energy_system_fbs_wirings')
            ->join('energy_systems', 'energy_system_fbs_wirings.energy_system_id', 
                'energy_systems.id')
            ->where('energy_system_fbs_wirings.energy_system_id', $id)
            ->select('energy_system_fbs_wirings.unit', 'energy_systems.name', 
                'energy_system_fbs_wirings.id', 'energy_system_fbs_wirings.cost')
            ->get();


        $houseWirings = DB::table('energy_system_wiring_houses')
            ->join('energy_systems', 'energy_system_wiring_houses.energy_system_id', 
                'energy_systems.id')
            ->where('energy_system_wiring_houses.energy_system_id', $id)
            ->select('energy_system_wiring_houses.unit', 'energy_systems.name', 
                'energy_system_wiring_houses.id', 'energy_system_wiring_houses.cost')
            ->get();

        $electricityRooms = DB::table('energy_system_electricity_rooms')
            ->join('energy_systems', 'energy_system_electricity_rooms.energy_system_id', 
                'energy_systems.id')
            ->where('energy_system_electricity_rooms.energy_system_id', $id)
            ->select('energy_system_electricity_rooms.unit', 'energy_systems.name', 
                'energy_system_electricity_rooms.id', 'energy_system_electricity_rooms.cost')
            ->get();

        $electricityBosRooms = DB::table('energy_system_electricity_bos_rooms')
            ->join('energy_systems', 'energy_system_electricity_bos_rooms.energy_system_id', 
                'energy_systems.id')
            ->where('energy_system_electricity_bos_rooms.energy_system_id', $id)
            ->select('energy_system_electricity_bos_rooms.unit', 'energy_systems.name', 
                'energy_system_electricity_bos_rooms.id', 'energy_system_electricity_bos_rooms.cost')
            ->get();

        $communityGrids = DB::table('energy_system_grids')
            ->join('energy_systems', 'energy_system_grids.energy_system_id', 
                'energy_systems.id')
            ->where('energy_system_grids.energy_system_id', $id)
            ->select('energy_system_grids.unit', 'energy_systems.name', 
                'energy_system_grids.id', 'energy_system_grids.cost')
            ->get();

        $refrigerators = DB::table('energy_system_refrigerator_costs')
            ->join('energy_systems', 'energy_system_refrigerator_costs.energy_system_id', 
                'energy_systems.id')
            ->where('energy_system_refrigerator_costs.energy_system_id', $id)
            ->select('energy_system_refrigerator_costs.unit', 'energy_systems.name', 
                'energy_system_refrigerator_costs.id', 'energy_system_refrigerator_costs.cost')
            ->get();

        return view('system.energy.show', compact('energySystem', 'battarySystems', 'pvSystems', 
            'controllerSystems', 'inverterSystems', 'relayDriverSystems', 'loadRelaySystems', 
            'bspSystems', 'rccSystems', 'loggerSystems', 'generatorSystems', 'turbineSystems', 
            'pvMcbSystems', 'controllerMcbSystems', 'inventerMcbSystems', 'airConditionerSystems',
            'battaryMountSystems', 'pvMountSystems', 'btsSystems', 'fbsCabinets', 'fbsFans',
            'fbsLocks', 'fbsWirings', 'refrigerators', 'communityGrids', 'electricityBosRooms',
            'electricityRooms', 'houseWirings'));
    }

    /**
     * Change resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function incidentFbsDetails(Request $request)
    {
        $incidentStatus = $request->selected_data;

        $statusFbs = IncidentStatusSmallInfrastructure::where("name", $incidentStatus)->first();
        $status_id = $statusFbs->id;

        $dataIncidents = DB::table('fbs_user_incidents')
            ->join('energy_users', 'fbs_user_incidents.energy_user_id', '=', 'energy_users.id')
            ->join('households', 'energy_users.household_id', '=', 'households.id')
            ->join('communities', 'fbs_user_incidents.community_id', '=', 'communities.id')
            ->join('incidents', 'fbs_user_incidents.incident_id', '=', 'incidents.id')
            ->join('incident_status_small_infrastructures', 
                'fbs_user_incidents.incident_status_small_infrastructure_id', 
                '=', 'incident_status_small_infrastructures.id')
            ->where("fbs_user_incidents.incident_status_small_infrastructure_id", $status_id)
            ->select("communities.english_name as community", "fbs_user_incidents.date",
                "incidents.english_name as incident", "households.english_name as household",
                "fbs_user_incidents.equipment")
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
    public function deleteEnergySystem(Request $request)
    {
        $id = $request->id;

        $energySystem = EnergySystem::find($id);

        if($energySystem) {

            $energySystem->is_archived = 1;
            $energySystem->save();

            $response['success'] = 1;
            $response['msg'] = 'Energy System Deleted successfully'; 
        } else {

            $response['success'] = 0;
            $response['msg'] = 'Invalid ID.';
        }

        return response()->json($response); 
    }

    /**
     * 
     * @return \Illuminate\Support\Collection
     */
    public function export(Request $request) 
    {
                
        return Excel::download(new EnergySystemExport($request), 'energy_systems.xlsx');
    }
}