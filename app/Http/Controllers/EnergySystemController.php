<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {	
        if (Auth::guard('user')->user() != null) {

            $communityFilter = $request->input('community_filter');
            $typeFilter = $request->input('type_filter');
            $yearFilter = $request->input('year_filter');

            if ($request->ajax()) {

                $data = DB::table('energy_systems')
                    ->join('energy_system_types', 'energy_systems.energy_system_type_id', 
                        '=', 'energy_system_types.id')
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
        
            return view('system.energy.index', compact('communities', 'donors', 'services',
                'energyTypes'))
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

        return view('system.energy.create', compact('batteries', 'communities', 'controllers',
            'pvs', 'mcbPvs', 'mcbInventors', 'mcbControllers', 'turbines', 'generators',
            'loggers', 'loadRelaies', 'relayDrivers', 'inverters', 'bsps', 'rccs',
            'energyTypes', 'airConditioners', 'batteryMounts', 'pvMounts'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {       
        $energySystem = new EnergySystem();
 
        if($request->community_id) $energySystem->community_id = $request->community_id;
        $energySystem->name = $request->name;
        $energySystem->installation_year = $request->installation_year;
        $energySystem->cycle_year = $request->cycle_year;
        $energySystem->energy_system_type_id = $request->energy_system_type_id;
        $energySystem->notes = $request->notes;
        $energySystem->save();
  
        // Battery
        if($request->battery_id) {
            for($i=0; $i < count($request->battery_id); $i++) {

                $batterySystem = new EnergySystemBattery();
                $batterySystem->battery_type_id = $request->battery_id[$i];
                $batterySystem->battery_units = $request->battery_units[$i]["subject"];
                $batterySystem->energy_system_id = $energySystem->id;
                $batterySystem->save();
            }
        }

        // Battery Mount
        if($request->battery_mount_id) {
            for($i=0; $i < count($request->battery_mount_id); $i++) {

                $batterySystemMount = new EnergySystemBatteryMount();
                $batterySystemMount->energy_battery_mount_id = $request->battery_mount_id[$i];
                $batterySystemMount->unit = $request->units[$i]["subject"];
                $batterySystemMount->energy_system_id = $energySystem->id;
                $batterySystemMount->save();
            }
        }

        // Solar Panel
        if($request->pv_id) {
            for($i=0; $i < count($request->pv_id); $i++) {

                $pvSystem = new EnergySystemPv();
                $pvSystem->pv_type_id = $request->pv_id[$i];
                $pvSystem->pv_units = $request->pv_units[$i]["subject"];
                $pvSystem->energy_system_id = $energySystem->id;
                $pvSystem->save();
            }
        }

        // Solar Panel Mount
        if($request->pv_mount_id) {
            for($i=0; $i < count($request->pv_mount_id); $i++) {

                $pvSystemMount = new EnergySystemPvMount();
                $pvSystemMount->energy_pv_mount_id = $request->pv_mount_id[$i];
                $pvSystemMount->unit = $request->units[$i]["subject"];
                $pvSystemMount->energy_system_id = $energySystem->id;
                $pvSystemMount->save();
            }
        }

        // Controller
        if($request->controller_id) {
            for($i=0; $i < count($request->controller_id); $i++) {

                $controllerSystem = new EnergySystemChargeController();
                $controllerSystem->energy_charge_controller_id = $request->controller_id[$i];
                $controllerSystem->controller_units = $request->controller_units[$i]["subject"];
                $controllerSystem->energy_system_id = $energySystem->id;
                $controllerSystem->save();
            }
        }

        // Logger
        if($request->logger_id) {
            for($i=0; $i < count($request->logger_id); $i++) {

                $loggerSystem = new EnergySystemMonitoring();
                $loggerSystem->energy_monitoring_id = $request->logger_id[$i];
                $loggerSystem->monitoring_units = $request->logger_units[$i]["subject"];
                $loggerSystem->energy_system_id = $energySystem->id;
                $loggerSystem->save();
            }
        }

        // Inverter
        if($request->inverter_id) {
            for($i=0; $i < count($request->inverter_id); $i++) {

                $inverterSystem = new EnergySystemInverter();
                $inverterSystem->energy_inverter_id = $request->inverter_id[$i];
                $inverterSystem->inverter_units = $request->inverter_units[$i]["subject"];
                $inverterSystem->energy_system_id = $energySystem->id;
                $inverterSystem->save();
            }
        }

        // Relay Driver
        if($request->relay_driver_id) {
            for($i=0; $i < count($request->relay_driver_id); $i++) {

                $relayDriverSystem = new EnergySystemRelayDriver();
                $relayDriverSystem->relay_driver_type_id = $request->relay_driver_id[$i];
                $relayDriverSystem->relay_driver_units = $request->relay_driver_units[$i]["subject"];
                $relayDriverSystem->energy_system_id = $energySystem->id;
                $relayDriverSystem->save();
            }
        }

        // Load Relay
        if($request->load_relay_id) {
            for($i=0; $i < count($request->load_relay_id); $i++) {

                $loadRelaySystem = new EnergySystemLoadRelay();
                $loadRelaySystem->energy_load_relay_id = $request->load_relay_id[$i];
                $loadRelaySystem->load_relay_units = $request->load_relay_units[$i]["subject"];
                $loadRelaySystem->energy_system_id = $energySystem->id;
                $loadRelaySystem->save();
            }
        }

        // Battery Status Processor
        if($request->bsp_id) {
            for($i=0; $i < count($request->bsp_id); $i++) {

                $bspSystem = new EnergySystemBatteryStatusProcessor();
                $bspSystem->energy_battery_status_processor_id = $request->bsp_id[$i];
                $bspSystem->bsp_units = $request->bsp_units[$i]["subject"];
                $bspSystem->energy_system_id = $energySystem->id;
                $bspSystem->save();
            }
        }

        // Remote Control Center
        if($request->rcc_id) {
            for($i=0; $i < count($request->rcc_id); $i++) {

                $rccSystem = new EnergySystemRemoteControlCenter();
                $rccSystem->energy_remote_control_center_id = $request->rcc_id[$i];
                $rccSystem->rcc_units = $request->rcc_units[$i]["subject"];
                $rccSystem->energy_system_id = $energySystem->id;
                $rccSystem->save();
            }
        }

        // Generator
        if($request->generator_id) {
            for($i=0; $i < count($request->generator_id); $i++) {

                $generatorSystem = new EnergySystemGenerator();
                $generatorSystem->energy_generator_id = $request->generator_id[$i];
                $generatorSystem->generator_units = $request->generator_units[$i]["subject"];
                $generatorSystem->energy_system_id = $energySystem->id;
                $generatorSystem->save();
            }
        }

        // Wind Turbine
        if($request->turbine_id) {
            for($i=0; $i < count($request->turbine_id); $i++) {

                $turbineSystem = new EnergySystemWindTurbine();
                $turbineSystem->energy_wind_turbine_id = $request->turbine_id[$i];
                $turbineSystem->turbine_units = $request->turbine_units[$i]["subject"];
                $turbineSystem->energy_system_id = $energySystem->id;
                $turbineSystem->save();
            }
        }

        // Solar Panel MCB
        if($request->pv_mcb_id) {
            for($i=0; $i < count($request->pv_mcb_id); $i++) {

                $pvMcbSystem = new EnergySystemMcbPv();
                $pvMcbSystem->energy_mcb_pv_id = $request->pv_mcb_id[$i];
                $pvMcbSystem->mcb_pv_units = $request->pv_mcb_units[$i]["subject"];
                $pvMcbSystem->energy_system_id = $energySystem->id;
                $pvMcbSystem->save();
            }
        }

        // Charge Controllers MCB
        if($request->controller_mcb_id) {
            for($i=0; $i < count($request->controller_mcb_id); $i++) {

                $controllerMcbSystem = new EnergySystemMcbChargeController();
                $controllerMcbSystem->energy_mcb_charge_controller_id = $request->controller_mcb_id[$i];
                $controllerMcbSystem->mcb_controller_units = $request->controller_mcb_units[$i]["subject"];
                $controllerMcbSystem->energy_system_id = $energySystem->id;
                $controllerMcbSystem->save();
            }
        }

        // Inverter MCB
        if($request->inventer_mcb_id) {
            for($i=0; $i < count($request->inventer_mcb_id); $i++) {

                $inventerMcbSystem = new EnergySystemMcbInverter();
                $inventerMcbSystem->energy_mcb_inverter_id = $request->inventer_mcb_id[$i];
                $inventerMcbSystem->mcb_inverter_units = $request->inventer_mcb_units[$i]["subject"];
                $inventerMcbSystem->energy_system_id = $energySystem->id;
                $inventerMcbSystem->save();
            }
        }

        // Air Conditioner
        if($request->energy_air_conditioner_id) {
            for($i=0; $i < count($request->energy_air_conditioner_id); $i++) {

                $inventerMcbSystem = new EnergySystemAirConditioner();
                $inventerMcbSystem->energy_air_conditioner_id = $request->energy_air_conditioner_id[$i];
                $inventerMcbSystem->energy_air_conditioner_units = $request->energy_air_conditioner_units[$i]["subject"];
                $inventerMcbSystem->energy_system_id = $energySystem->id;
                $inventerMcbSystem->save();
            }
        }

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

        $battarySystems = DB::table('energy_system_batteries')
            ->join('energy_systems', 'energy_system_batteries.energy_system_id', 
                '=', 'energy_systems.id')
            ->join('energy_batteries', 'energy_system_batteries.battery_type_id', 
                '=', 'energy_batteries.id')
            ->where('energy_system_batteries.energy_system_id', '=', $id)
            ->select('energy_system_batteries.battery_units', 'energy_batteries.battery_model', 
                'energy_batteries.battery_brand', 'energy_systems.name', 
                'energy_system_batteries.id')
            ->get(); 

        $battaryMountSystems = DB::table('energy_system_battery_mounts')
            ->join('energy_systems', 'energy_system_battery_mounts.energy_system_id', 
                'energy_systems.id')
            ->join('energy_battery_mounts', 'energy_system_battery_mounts.energy_battery_mount_id', 
                'energy_battery_mounts.id')
            ->where('energy_system_battery_mounts.energy_system_id', $id)
            ->select('energy_system_battery_mounts.unit', 'energy_battery_mounts.model', 
                'energy_battery_mounts.brand', 'energy_systems.name', 
                'energy_system_battery_mounts.id')
            ->get(); 

        $pvSystems = DB::table('energy_system_pvs')
            ->join('energy_systems', 'energy_system_pvs.energy_system_id', 
                '=', 'energy_systems.id')
            ->join('energy_pvs', 'energy_system_pvs.pv_type_id', 
                '=', 'energy_pvs.id')
            ->where('energy_system_pvs.energy_system_id', '=', $id)
            ->select('energy_system_pvs.pv_units', 'energy_pvs.pv_model', 
                'energy_pvs.pv_brand', 'energy_systems.name', 
                'energy_system_pvs.id')
            ->get(); 

        $pvMountSystems = DB::table('energy_system_pv_mounts')
            ->join('energy_systems', 'energy_system_pv_mounts.energy_system_id', 
                'energy_systems.id')
            ->join('energy_pv_mounts', 'energy_system_pv_mounts.energy_pv_mount_id', 
                'energy_pv_mounts.id')
            ->where('energy_system_pv_mounts.energy_system_id', $id)
            ->select('energy_system_pv_mounts.unit', 'energy_pv_mounts.model', 
                'energy_pv_mounts.brand', 'energy_systems.name', 
                'energy_system_pv_mounts.id')
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
                'energy_system_charge_controllers.id')
            ->get(); 

        $inverterSystems = DB::table('energy_system_inverters')
            ->join('energy_systems', 'energy_system_inverters.energy_system_id', 
                '=', 'energy_systems.id')
            ->join('energy_inverters', 'energy_system_inverters.energy_inverter_id', 
                '=', 'energy_inverters.id')
            ->where('energy_system_inverters.energy_system_id', '=', $id)
            ->select('energy_system_inverters.inverter_units', 'energy_inverters.inverter_model', 
                'energy_inverters.inverter_brand', 'energy_systems.name', 
                'energy_system_inverters.id')
            ->get(); 

        $relayDriverSystems = DB::table('energy_system_relay_drivers')
            ->join('energy_systems', 'energy_system_relay_drivers.energy_system_id', 
                '=', 'energy_systems.id')
            ->join('energy_relay_drivers', 'energy_system_relay_drivers.relay_driver_type_id', 
                '=', 'energy_relay_drivers.id')
            ->where('energy_system_relay_drivers.energy_system_id', '=', $id)
            ->select('energy_system_relay_drivers.relay_driver_units', 'energy_relay_drivers.model', 
                'energy_relay_drivers.brand', 'energy_systems.name', 
                'energy_system_relay_drivers.id')
            ->get(); 

        $loadRelaySystems = DB::table('energy_system_load_relays')
            ->join('energy_systems', 'energy_system_load_relays.energy_system_id', 
                '=', 'energy_systems.id')
            ->join('energy_load_relays', 'energy_system_load_relays.energy_load_relay_id', 
                '=', 'energy_load_relays.id')
            ->where('energy_system_load_relays.energy_system_id', '=', $id)
            ->select('energy_system_load_relays.load_relay_units', 'energy_load_relays.load_relay_model', 
                'energy_load_relays.load_relay_brand', 'energy_systems.name', 
                'energy_system_load_relays.id')
            ->get();

        $bspSystems = DB::table('energy_system_battery_status_processors')
            ->join('energy_systems', 'energy_system_battery_status_processors.energy_system_id', 
                '=', 'energy_systems.id')
            ->join('energy_battery_status_processors', 'energy_system_battery_status_processors.energy_battery_status_processor_id', 
                '=', 'energy_battery_status_processors.id')
            ->where('energy_system_battery_status_processors.energy_system_id', '=', $id)
            ->select('energy_system_battery_status_processors.bsp_units', 'energy_systems.name', 
                'energy_battery_status_processors.model', 'energy_battery_status_processors.brand', 
                'energy_system_battery_status_processors.id')
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
                'energy_system_monitorings.id')
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
                'energy_system_generators.id')
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
                'energy_system_wind_turbines.id')
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
                'energy_system_mcb_pvs.id')
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
                'energy_system_mcb_charge_controllers.id')
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
                'energy_system_mcb_inverters.id')
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
                'energy_system_air_conditioners.id')
            ->get();

        return view('system.energy.edit', compact('batteries', 'communities', 'controllers',
            'pvs', 'mcbPvs', 'mcbInventors', 'mcbControllers', 'turbines', 'generators',
            'loggers', 'loadRelaies', 'relayDrivers', 'inverters', 'bsps', 'rccs',
            'energyTypes', 'energySystem', 'battarySystems', 'pvSystems', 'controllerSystems',
            'inverterSystems', 'relayDriverSystems', 'loadRelaySystems', 'bspSystems',
            'rccSystems', 'loggerSystems', 'generatorSystems', 'turbineSystems', 'pvMcbSystems',
            'controllerMcbSystems', 'inventerMcbSystems', 'airConditioners', 
            'airConditionerSystems', 'batteryMounts', 'pvMounts', 'battaryMountSystems',
            'pvMountSystems'));
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
        if($request->cycle_year) $energySystem->cycle_year = $request->cycle_year;
        if($request->upgrade_year1) $energySystem->upgrade_year1 = $request->upgrade_year1;
        if($request->upgrade_year2) $energySystem->upgrade_year2 = $request->upgrade_year2;
        if($request->total_rated_power == null) $energySystem->total_rated_power = null;
        if($request->total_rated_power) $energySystem->total_rated_power = $request->total_rated_power;
        if($request->generated_power == null) $energySystem->generated_power = null;
        if($request->generated_power) $energySystem->generated_power = $request->generated_power;
        if($request->energy_system_type_id) $energySystem->energy_system_type_id = $request->energy_system_type_id;
        $energySystem->notes = $request->notes;
        $energySystem->save();

        // Battery
        if($request->battery_id) {
            for($i=0; $i < count($request->battery_id); $i++) {

                $batterySystem = new EnergySystemBattery();
                $batterySystem->battery_type_id = $request->battery_id[$i];
                $batterySystem->battery_units = $request->battery_units[$i]["subject"];
                $batterySystem->energy_system_id = $energySystem->id;
                $batterySystem->save();
            }
        }

        // Battery Mount
        if($request->battery_mount_id) {
            for($i=0; $i < count($request->battery_mount_id); $i++) {

                $batterySystemMount = new EnergySystemBatteryMount();
                $batterySystemMount->energy_battery_mount_id = $request->battery_mount_id[$i];
                $batterySystemMount->unit = $request->units[$i]["subject"];
                $batterySystemMount->energy_system_id = $energySystem->id;
                $batterySystemMount->save();
            }
        }

        // Solar Panel
        if($request->pv_id) {
            for($i=0; $i < count($request->pv_id); $i++) {

                $pvSystem = new EnergySystemPv();
                $pvSystem->pv_type_id = $request->pv_id[$i];
                $pvSystem->pv_units = $request->pv_units[$i]["subject"];
                $pvSystem->energy_system_id = $energySystem->id;
                $pvSystem->save();
            }
        }

        // Solar Panel Mount
        if($request->pv_mount_id) {
            for($i=0; $i < count($request->pv_mount_id); $i++) {

                $pvSystemMount = new EnergySystemPvMount();
                $pvSystemMount->energy_pv_mount_id = $request->pv_mount_id[$i];
                $pvSystemMount->unit = $request->units[$i]["subject"];
                $pvSystemMount->energy_system_id = $energySystem->id;
                $pvSystemMount->save();
            }
        }

        // Controller
        if($request->controller_id) {
            for($i=0; $i < count($request->controller_id); $i++) {

                $controllerSystem = new EnergySystemChargeController();
                $controllerSystem->energy_charge_controller_id = $request->controller_id[$i];
                $controllerSystem->controller_units = $request->controller_units[$i]["subject"];
                $controllerSystem->energy_system_id = $energySystem->id;
                $controllerSystem->save();
            }
        }

        // Logger
        if($request->logger_id) {
            for($i=0; $i < count($request->logger_id); $i++) {

                $loggerSystem = new EnergySystemMonitoring();
                $loggerSystem->energy_monitoring_id = $request->logger_id[$i];
                $loggerSystem->monitoring_units = $request->logger_units[$i]["subject"];
                $loggerSystem->energy_system_id = $energySystem->id;
                $loggerSystem->save();
            }
        }

        // Inverter
        if($request->inverter_id) {
            for($i=0; $i < count($request->inverter_id); $i++) {

                $inverterSystem = new EnergySystemInverter();
                $inverterSystem->energy_inverter_id = $request->inverter_id[$i];
                $inverterSystem->inverter_units = $request->inverter_units[$i]["subject"];
                $inverterSystem->energy_system_id = $energySystem->id;
                $inverterSystem->save();
            }
        }

        // Relay Driver
        if($request->relay_driver_id) {
            for($i=0; $i < count($request->relay_driver_id); $i++) {

                $relayDriverSystem = new EnergySystemRelayDriver();
                $relayDriverSystem->relay_driver_type_id = $request->relay_driver_id[$i];
                $relayDriverSystem->relay_driver_units = $request->relay_driver_units[$i]["subject"];
                $relayDriverSystem->energy_system_id = $energySystem->id;
                $relayDriverSystem->save();
            }
        }

        // Load Relay
        if($request->load_relay_id) {
            for($i=0; $i < count($request->load_relay_id); $i++) {

                $loadRelaySystem = new EnergySystemLoadRelay();
                $loadRelaySystem->energy_load_relay_id = $request->load_relay_id[$i];
                $loadRelaySystem->load_relay_units = $request->load_relay_units[$i]["subject"];
                $loadRelaySystem->energy_system_id = $energySystem->id;
                $loadRelaySystem->save();
            }
        }

        // Battery Status Processor
        if($request->bsp_id) {
            for($i=0; $i < count($request->bsp_id); $i++) {

                $bspSystem = new EnergySystemBatteryStatusProcessor();
                $bspSystem->energy_battery_status_processor_id = $request->bsp_id[$i];
                $bspSystem->bsp_units = $request->bsp_units[$i]["subject"];
                $bspSystem->energy_system_id = $energySystem->id;
                $bspSystem->save();
            }
        }

        // Remote Control Center
        if($request->rcc_id) {
            for($i=0; $i < count($request->rcc_id); $i++) {

                $rccSystem = new EnergySystemRemoteControlCenter();
                $rccSystem->energy_remote_control_center_id = $request->rcc_id[$i];
                $rccSystem->rcc_units = $request->rcc_units[$i]["subject"];
                $rccSystem->energy_system_id = $energySystem->id;
                $rccSystem->save();
            }
        }

        // Generator
        if($request->generator_id) {
            for($i=0; $i < count($request->generator_id); $i++) {

                $generatorSystem = new EnergySystemGenerator();
                $generatorSystem->energy_generator_id = $request->generator_id[$i];
                $generatorSystem->generator_units = $request->generator_units[$i]["subject"];
                $generatorSystem->energy_system_id = $energySystem->id;
                $generatorSystem->save();
            }
        }

        // Wind Turbine
        if($request->turbine_id) {
            for($i=0; $i < count($request->turbine_id); $i++) {

                $turbineSystem = new EnergySystemWindTurbine();
                $turbineSystem->energy_wind_turbine_id = $request->turbine_id[$i];
                $turbineSystem->turbine_units = $request->turbine_units[$i]["subject"];
                $turbineSystem->energy_system_id = $energySystem->id;
                $turbineSystem->save();
            }
        }

        // Solar Panel MCB
        if($request->pv_mcb_id) {
            for($i=0; $i < count($request->pv_mcb_id); $i++) {

                $pvMcbSystem = new EnergySystemMcbPv();
                $pvMcbSystem->energy_mcb_pv_id = $request->pv_mcb_id[$i];
                $pvMcbSystem->mcb_pv_units = $request->pv_mcb_units[$i]["subject"];
                $pvMcbSystem->energy_system_id = $energySystem->id;
                $pvMcbSystem->save();
            }
        }

        // Charge Controllers MCB
        if($request->controller_mcb_id) {
            for($i=0; $i < count($request->controller_mcb_id); $i++) {

                $controllerMcbSystem = new EnergySystemMcbChargeController();
                $controllerMcbSystem->energy_mcb_charge_controller_id = $request->controller_mcb_id[$i];
                $controllerMcbSystem->mcb_controller_units = $request->controller_mcb_units[$i]["subject"];
                $controllerMcbSystem->energy_system_id = $energySystem->id;
                $controllerMcbSystem->save();
            }
        }

        // Inverter MCB
        if($request->inventer_mcb_id) {
            for($i=0; $i < count($request->inventer_mcb_id); $i++) {

                $inventerMcbSystem = new EnergySystemMcbInverter();
                $inventerMcbSystem->energy_mcb_inverter_id = $request->inventer_mcb_id[$i];
                $inventerMcbSystem->mcb_inverter_units = $request->inventer_mcb_units[$i]["subject"];
                $inventerMcbSystem->energy_system_id = $energySystem->id;
                $inventerMcbSystem->save();
            }
        }

        // Air Conditioner
        if($request->energy_air_conditioner_id) {
            for($i=0; $i < count($request->energy_air_conditioner_id); $i++) {

                $inventerMcbSystem = new EnergySystemAirConditioner();
                $inventerMcbSystem->energy_air_conditioner_id = $request->energy_air_conditioner_id[$i];
                $inventerMcbSystem->energy_air_conditioner_units = $request->energy_air_conditioner_units[$i]["subject"];
                $inventerMcbSystem->energy_system_id = $energySystem->id;
                $inventerMcbSystem->save();
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
                'energy_system_batteries.id')
            ->get(); 

        $pvSystems = DB::table('energy_system_pvs')
            ->join('energy_systems', 'energy_system_pvs.energy_system_id', 
                '=', 'energy_systems.id')
            ->join('energy_pvs', 'energy_system_pvs.pv_type_id', 
                '=', 'energy_pvs.id')
            ->where('energy_system_pvs.energy_system_id', '=', $id)
            ->select('energy_system_pvs.pv_units', 'energy_pvs.pv_model', 
                'energy_pvs.pv_brand', 'energy_systems.name', 
                'energy_system_pvs.id')
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
                'energy_system_charge_controllers.id')
            ->get(); 

        $inverterSystems = DB::table('energy_system_inverters')
            ->join('energy_systems', 'energy_system_inverters.energy_system_id', 
                '=', 'energy_systems.id')
            ->join('energy_inverters', 'energy_system_inverters.energy_inverter_id', 
                '=', 'energy_inverters.id')
            ->where('energy_system_inverters.energy_system_id', '=', $id)
            ->select('energy_system_inverters.inverter_units', 'energy_inverters.inverter_model', 
                'energy_inverters.inverter_brand', 'energy_systems.name', 
                'energy_system_inverters.id')
            ->get(); 

        $relayDriverSystems = DB::table('energy_system_relay_drivers')
            ->join('energy_systems', 'energy_system_relay_drivers.energy_system_id', 
                '=', 'energy_systems.id')
            ->join('energy_relay_drivers', 'energy_system_relay_drivers.relay_driver_type_id', 
                '=', 'energy_relay_drivers.id')
            ->where('energy_system_relay_drivers.energy_system_id', '=', $id)
            ->select('energy_system_relay_drivers.relay_driver_units', 'energy_relay_drivers.model', 
                'energy_relay_drivers.brand', 'energy_systems.name', 
                'energy_system_relay_drivers.id')
            ->get(); 

        $loadRelaySystems = DB::table('energy_system_load_relays')
            ->join('energy_systems', 'energy_system_load_relays.energy_system_id', 
                '=', 'energy_systems.id')
            ->join('energy_load_relays', 'energy_system_load_relays.energy_load_relay_id', 
                '=', 'energy_load_relays.id')
            ->where('energy_system_load_relays.energy_system_id', '=', $id)
            ->select('energy_system_load_relays.load_relay_units', 'energy_load_relays.load_relay_model', 
                'energy_load_relays.load_relay_brand', 'energy_systems.name', 
                'energy_system_load_relays.id')
            ->get();

        $bspSystems = DB::table('energy_system_battery_status_processors')
            ->join('energy_systems', 'energy_system_battery_status_processors.energy_system_id', 
                '=', 'energy_systems.id')
            ->join('energy_battery_status_processors', 'energy_system_battery_status_processors.energy_battery_status_processor_id', 
                '=', 'energy_battery_status_processors.id')
            ->where('energy_system_battery_status_processors.energy_system_id', '=', $id)
            ->select('energy_system_battery_status_processors.bsp_units', 'energy_systems.name', 
                'energy_battery_status_processors.model', 'energy_battery_status_processors.brand', 
                'energy_system_battery_status_processors.id')
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
                'energy_system_monitorings.id')
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
                'energy_system_generators.id')
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
                'energy_system_wind_turbines.id')
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
                'energy_system_mcb_pvs.id')
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
                'energy_system_mcb_charge_controllers.id')
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
                'energy_system_mcb_inverters.id')
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
                'energy_system_air_conditioners.id')
            ->get();

        return view('system.energy.show', compact('energySystem', 'battarySystems', 'pvSystems', 
            'controllerSystems', 'inverterSystems', 'relayDriverSystems', 'loadRelaySystems', 
            'bspSystems', 'rccSystems', 'loggerSystems', 'generatorSystems', 'turbineSystems', 
            'pvMcbSystems', 'controllerMcbSystems', 'inventerMcbSystems', 'airConditionerSystems'));
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