<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\User;
use App\Models\Community; 
use App\Models\AllEnergyMeter;
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
use App\Models\EnergySystemBatteryMount;
use App\Models\EnergySystemMonitoring;
use App\Models\EnergySystemPv;
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
use App\Models\EnergySystemWiringHouse;
use App\Models\EnergySystemFbsWiring;
use App\Models\EnergySystemFbsLock;
use App\Models\EnergySystemFbsFan;
use App\Models\EnergySystemFbsCabinet;
use App\Models\EnergySystemCycle;
use App\Models\EnergySystemRefrigeratorCost;
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
use App\Models\GridCommunityCompound;
use App\Models\IncidentStatusSmallInfrastructure;
use App\Models\FbsSystem;
use App\Models\Town;
use App\Models\CompoundHousehold;
use App\Exports\EnergyCostExport;
use Carbon\Carbon;
use Auth;
use DataTables;
use DB;
use Excel;
use Image;
use Route;

class EnergyCostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    { 

        $allEnergySystems = EnergySystem::where('is_archived', 0)->get();

        foreach($allEnergySystems as $allEnergySystem) {

            $number = 0;
            $gridCommunityCompound = GridCommunityCompound::where('energy_system_id', $allEnergySystem->id)->first();
            if($gridCommunityCompound) {

                $compoundHousehold = CompoundHousehold::where('compound_id', $gridCommunityCompound->compound_id)->count();

                $number = $compoundHousehold;
                $allEnergySystem->number_of_families = $number;
                $allEnergySystem->save();
            } else {

                $household = DB::table('households')
                    ->where('households.is_archived', 0)
                    ->where('households.community_id', $allEnergySystem->community_id)
                    ->whereNotExists(function ($query) {
                        $query->select(DB::raw(1))
                            ->from('compound_households') 
                            ->whereRaw('compound_households.household_id = households.id');
                    })
                    ->count();
                  
                $allEnergySystem->number_of_families = $household;
                $allEnergySystem->save();
            }
        }


        $energySystems = EnergySystem::where('is_archived', 0)->get();

        foreach($energySystems as $energySystem) {

            $number = 0;
            $gridCommunityCompound = GridCommunityCompound::where('energy_system_id', $energySystem->id)->first();

            if($gridCommunityCompound) {

                $compoundHousehold = CompoundHousehold::where('compound_id', $gridCommunityCompound->compound_id)->count();

                $number = $compoundHousehold;
 
                $exisitRefrigeratorCost = EnergySystemRefrigeratorCost::where('energy_system_id', $energySystem->id)->first();
                if($exisitRefrigeratorCost) {

                    $exisitRefrigeratorCost->unit = $number;
                    $exisitRefrigeratorCost->save();
                } else {

                    $refrigeratorCost = new EnergySystemRefrigeratorCost();
                    $refrigeratorCost->energy_system_id = $energySystem->id;
                    $refrigeratorCost->unit = $number;
                    $refrigeratorCost->save();
                }
            }
            
        }

        foreach($energySystems as $energySystem) {
       
            $totalCost = 0.0;
            $batteryCost = EnergySystemBattery::where('energy_system_id', $energySystem->id)
                ->value('cost');
            $batteryMountCost = EnergySystemBatteryMount::where('energy_system_id', $energySystem->id)
                ->value('cost');
            $pvCost = EnergySystemPv::where('energy_system_id', $energySystem->id)
                ->value('cost');
            $pvMountCost = EnergySystemPvMount::where('energy_system_id', $energySystem->id)
                ->value('cost');
            $relayCost = EnergySystemRelayDriver::where('energy_system_id', $energySystem->id)
                ->value('cost');
            $loggerCosts = EnergySystemMonitoring::where('energy_system_id', $energySystem->id)
                ->pluck('cost');
            $ccCost = EnergySystemChargeController::where('energy_system_id', $energySystem->id)
                ->value('cost');
            $turbineCost = EnergySystemWindTurbine::where('energy_system_id', $energySystem->id)
                ->value('cost');
            $generatorCost = EnergySystemGenerator::where('energy_system_id', $energySystem->id)
                ->value('cost');
            $bspCost = EnergySystemBatteryStatusProcessor::where('energy_system_id', $energySystem->id)
                ->value('cost');
            $btsCost = EnergySystemBatteryTemperatureSensor::where('energy_system_id', $energySystem->id)
                ->value('cost');
            $inventerCost = EnergySystemInverter::where('energy_system_id', $energySystem->id)
                ->value('cost');
            $loadCost = EnergySystemLoadRelay::where('energy_system_id', $energySystem->id)
                ->value('cost');
            $pvMcbCost = EnergySystemMcbPv::where('energy_system_id', $energySystem->id)
                ->value('cost');
            $mcbCcCost = EnergySystemMcbChargeController::where('energy_system_id', $energySystem->id)
                ->value('cost');
            $rccCost = EnergySystemRemoteControlCenter::where('energy_system_id', $energySystem->id)
                ->value('cost');
            $mcbInventerCost = EnergySystemMcbInverter::where('energy_system_id', $energySystem->id)
                ->value('cost');
            $airCost = EnergySystemAirConditioner::where('energy_system_id', $energySystem->id)
                ->value('cost');
            $electricityRoomCost = GridCommunityCompound::where('energy_system_id', $energySystem->id)
                ->value('electricity_room_cost');
            $electricityRoomBosCost = GridCommunityCompound::where('energy_system_id', $energySystem->id)
                ->value('electricity_room_bos_cost');
            $gridCost = GridCommunityCompound::where('energy_system_id', $energySystem->id)
                ->value('grid_cost');
            $houseWiringCost = EnergySystemWiringHouse::where('energy_system_id', $energySystem->id)
                ->value('cost');
            $refrigeratorCost = EnergySystemRefrigeratorCost::where('energy_system_id', $energySystem->id)
                ->value('cost');  
            $houseFbsWiringCost = EnergySystemFbsWiring::where('energy_system_id', $energySystem->id)
                ->value('cost');
            $fbsLockCost = EnergySystemFbsLock::where('energy_system_id', $energySystem->id)
                ->value('cost');
            $fbsFanCost = EnergySystemFbsFan::where('energy_system_id', $energySystem->id)
                ->value('cost');
            $fbsCabinetCost = EnergySystemFbsCabinet::where('energy_system_id', $energySystem->id)
                ->value('cost');
                
            $totalCost += $batteryCost ?? 0.0;
            $totalCost += $batteryMountCost ?? 0.0;
            $totalCost += $pvCost ?? 0.0;
            $totalCost += $pvMountCost ?? 0.0;
            $totalCost += $relayCost ?? 0.0;
            foreach ($loggerCosts as $loggerCost) {
                $totalCost += $loggerCost;
            }
            $totalCost += $ccCost ?? 0.0;
            $totalCost += $turbineCost ?? 0.0;
            $totalCost += $generatorCost ?? 0.0;
            $totalCost += $bspCost ?? 0.0;
            $totalCost += $btsCost ?? 0.0;
            $totalCost += $inventerCost ?? 0.0;
            $totalCost += $loadCost ?? 0.0;
            $totalCost += $pvMcbCost ?? 0.0;
            $totalCost += $mcbCcCost ?? 0.0;
            $totalCost += $rccCost ?? 0.0;
            $totalCost += $mcbInventerCost ?? 0.0;
            $totalCost += $airCost ?? 0.0;
            $totalCost += $electricityRoomCost ?? 0.0;
            $totalCost += $electricityRoomBosCost ?? 0.0;
            $totalCost += $gridCost ?? 0.0;
            $totalCost += $houseWiringCost ?? 0.0;
            $totalCost += $refrigeratorCost ?? 0.0;
            $totalCost += $houseFbsWiringCost ?? 0.0;
            $totalCost += $fbsLockCost ?? 0.0;
            $totalCost += $fbsFanCost ?? 0.0;
            $totalCost += $fbsCabinetCost ?? 0.0;
            
            $energySystem->total_costs = $totalCost;
            $energySystem->save();
        }

      //  dd($totalCost);

        if (Auth::guard('user')->user() != null) {

            $communityFilter = $request->input('community_filter');
            $typeFilter = $request->input('type_filter');
            $yearFilter = $request->input('year_filter');

            if ($request->ajax()) {

                $data = DB::table('energy_systems')
                    ->join('energy_system_types', 'energy_systems.energy_system_type_id', 
                        'energy_system_types.id')
                    ->where('energy_systems.is_archived', 0)
                    ->where('energy_systems.energy_system_cycle_id', '!=', NULL);

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
                    'energy_system_types.name as type', 'energy_systems.total_costs',
                    'energy_systems.total_rated_power')
                ->latest();

                return Datatables::of($data)
                    ->addIndexColumn()
                    // ->addColumn('action', function($row) {
    
                    //     $viewButton = "<a type='button' class='viewEnergyCost' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#viewEnergyCostModal' ><i class='fa-solid fa-eye text-info'></i></a>";
                    //     $updateButton = "<a type='button' class='updateEnergyCost' data-id='".$row->id."' ><i class='fa-solid fa-pen-to-square text-success'></i></a>";
                    //     $deleteButton = "<a type='button' class='deleteEnergyCost' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";
                        
                    //     if(Auth::guard('user')->user()->user_type_id == 1 || 
                    //         Auth::guard('user')->user()->user_type_id == 2 ||
                    //         Auth::guard('user')->user()->user_type_id == 4) 
                    //     {
                                
                    //         return $viewButton." ". $updateButton." ".$deleteButton;
                    //     } else return $viewButton;
                    // })
                   
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

            return view('costs.energy.index', compact('communities', 'donors', 'services',
                'energyTypes', 'energyCycles'))
            ->with(
                'energySystemData', json_encode($arrayEnergySystem)
            );
            
        } else {

            return view('errors.not-found');
        }
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
        $gridRoom = null;

        if($energySystem->energy_system_type_id !=2) {

            $gridRoom = GridCommunityCompound::where('energy_system_id', $id)->first();
        }

        $houseWiringSystem = DB::table('energy_system_wiring_houses')
            ->join('energy_systems', 'energy_system_wiring_houses.energy_system_id', 
                'energy_systems.id')
            ->where('energy_system_wiring_houses.energy_system_id', $id)
            ->select('energy_system_wiring_houses.unit', 'energy_systems.name', 
                'energy_system_wiring_houses.id', 'energy_system_wiring_houses.cost')
            ->first(); 

        $fbsWiringSystem = DB::table('energy_system_fbs_wirings')
            ->join('energy_systems', 'energy_system_fbs_wirings.energy_system_id', 
                'energy_systems.id')
            ->where('energy_system_fbs_wirings.energy_system_id', $id)
            ->select('energy_system_fbs_wirings.unit', 'energy_systems.name', 
                'energy_system_fbs_wirings.id', 'energy_system_fbs_wirings.cost')
            ->first(); 

        $fbsLockSystem = DB::table('energy_system_fbs_locks')
            ->join('energy_systems', 'energy_system_fbs_locks.energy_system_id', 
                'energy_systems.id')
            ->where('energy_system_fbs_locks.energy_system_id', $id)
            ->select('energy_system_fbs_locks.unit', 'energy_systems.name', 
                'energy_system_fbs_locks.id', 'energy_system_fbs_locks.cost')
            ->first(); 

        $fbsFanSystem = DB::table('energy_system_fbs_fans')
            ->join('energy_systems', 'energy_system_fbs_fans.energy_system_id', 
                'energy_systems.id')
            ->where('energy_system_fbs_fans.energy_system_id', $id)
            ->select('energy_system_fbs_fans.unit', 'energy_systems.name', 
                'energy_system_fbs_fans.id', 'energy_system_fbs_fans.cost')
            ->first(); 

        $fbsCabinetSystem = DB::table('energy_system_fbs_cabinets')
            ->join('energy_systems', 'energy_system_fbs_cabinets.energy_system_id', 
                'energy_systems.id')
            ->where('energy_system_fbs_cabinets.energy_system_id', $id)
            ->select('energy_system_fbs_cabinets.unit', 'energy_systems.name', 
                'energy_system_fbs_cabinets.id', 'energy_system_fbs_cabinets.cost')
            ->first(); 

        $refrigeratorCostSystem = DB::table('energy_system_refrigerator_costs')
            ->join('energy_systems', 'energy_system_refrigerator_costs.energy_system_id', 
                'energy_systems.id')
            ->where('energy_system_refrigerator_costs.energy_system_id', $id)
            ->select('energy_system_refrigerator_costs.unit', 'energy_systems.name', 
                'energy_system_refrigerator_costs.id', 'energy_system_refrigerator_costs.cost')
            ->first(); 

        $battarySystems = DB::table('energy_system_batteries')
            ->join('energy_systems', 'energy_system_batteries.energy_system_id', 
                'energy_systems.id')
            ->join('energy_batteries', 'energy_system_batteries.battery_type_id', 
                'energy_batteries.id')
            ->where('energy_system_batteries.energy_system_id', $id)
            ->select('energy_system_batteries.battery_units', 'energy_batteries.battery_model', 
                'energy_batteries.battery_brand', 'energy_systems.name', 
                'energy_system_batteries.id', 'energy_system_batteries.cost',)
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
                'energy_systems.id')
            ->join('energy_pvs', 'energy_system_pvs.pv_type_id', 
                'energy_pvs.id')
            ->where('energy_system_pvs.energy_system_id', $id)
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
                'energy_systems.id')
            ->join('energy_charge_controllers', 'energy_system_charge_controllers.energy_charge_controller_id', 
                'energy_charge_controllers.id')
            ->where('energy_system_charge_controllers.energy_system_id', $id)
            ->select('energy_system_charge_controllers.controller_units', 
                'energy_charge_controllers.charge_controller_model', 
                'energy_charge_controllers.charge_controller_brand', 'energy_systems.name', 
                'energy_system_charge_controllers.id', 'energy_system_charge_controllers.cost')
            ->get(); 

        $inverterSystems = DB::table('energy_system_inverters')
            ->join('energy_systems', 'energy_system_inverters.energy_system_id', 
                'energy_systems.id')
            ->join('energy_inverters', 'energy_system_inverters.energy_inverter_id', 
                'energy_inverters.id')
            ->where('energy_system_inverters.energy_system_id', $id)
            ->select('energy_system_inverters.inverter_units', 'energy_inverters.inverter_model', 
                'energy_inverters.inverter_brand', 'energy_systems.name', 
                'energy_system_inverters.id', 'energy_system_inverters.cost')
            ->get(); 

        $relayDriverSystems = DB::table('energy_system_relay_drivers')
            ->join('energy_systems', 'energy_system_relay_drivers.energy_system_id', 
                'energy_systems.id')
            ->join('energy_relay_drivers', 'energy_system_relay_drivers.relay_driver_type_id', 
                'energy_relay_drivers.id')
            ->where('energy_system_relay_drivers.energy_system_id', $id)
            ->select('energy_system_relay_drivers.relay_driver_units', 'energy_relay_drivers.model', 
                'energy_relay_drivers.brand', 'energy_systems.name', 
                'energy_system_relay_drivers.id', 'energy_system_relay_drivers.cost')
            ->get(); 

        $loadRelaySystems = DB::table('energy_system_load_relays')
            ->join('energy_systems', 'energy_system_load_relays.energy_system_id', 
                'energy_systems.id')
            ->join('energy_load_relays', 'energy_system_load_relays.energy_load_relay_id', 
                'energy_load_relays.id')
            ->where('energy_system_load_relays.energy_system_id', $id)
            ->select('energy_system_load_relays.load_relay_units', 'energy_load_relays.load_relay_model', 
                'energy_load_relays.load_relay_brand', 'energy_systems.name', 
                'energy_system_load_relays.id', 'energy_system_load_relays.cost')
            ->get();

        $bspSystems = DB::table('energy_system_battery_status_processors')
            ->join('energy_systems', 'energy_system_battery_status_processors.energy_system_id', 
                'energy_systems.id')
            ->join('energy_battery_status_processors', 'energy_system_battery_status_processors.energy_battery_status_processor_id', 
                'energy_battery_status_processors.id')
            ->where('energy_system_battery_status_processors.energy_system_id', $id)
            ->select('energy_system_battery_status_processors.bsp_units', 'energy_systems.name', 
                'energy_battery_status_processors.model', 'energy_battery_status_processors.brand', 
                'energy_system_battery_status_processors.id', 
                'energy_system_battery_status_processors.cost')
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
                'energy_system_battery_temperature_sensors.id', 
                'energy_system_battery_temperature_sensors.cost')
            ->get();

        $rccSystems = DB::table('energy_system_remote_control_centers')
            ->join('energy_systems', 'energy_system_remote_control_centers.energy_system_id', 
                'energy_systems.id')
            ->join('energy_remote_control_centers', 'energy_system_remote_control_centers.energy_remote_control_center_id', 
                'energy_remote_control_centers.id')
            ->where('energy_system_remote_control_centers.energy_system_id', $id)
            ->select('energy_system_remote_control_centers.rcc_units', 
                'energy_remote_control_centers.model', 'energy_system_remote_control_centers.cost',
                'energy_remote_control_centers.brand', 'energy_systems.name', 
                'energy_system_remote_control_centers.id')
            ->get();

        $loggerSystems = DB::table('energy_system_monitorings')
            ->join('energy_systems', 'energy_system_monitorings.energy_system_id', 
                'energy_systems.id')
            ->join('energy_monitorings', 'energy_system_monitorings.energy_monitoring_id', 
                'energy_monitorings.id')
            ->where('energy_system_monitorings.energy_system_id', $id)
            ->select('energy_system_monitorings.monitoring_units', 
                'energy_monitorings.monitoring_model', 'energy_system_monitorings.cost',
                'energy_monitorings.monitoring_brand', 'energy_systems.name', 
                'energy_system_monitorings.id')
            ->get();

        $generatorSystems = DB::table('energy_system_generators')
            ->join('energy_systems', 'energy_system_generators.energy_system_id', 
                'energy_systems.id')
            ->join('energy_generators', 'energy_system_generators.energy_generator_id', 
                'energy_generators.id')
            ->where('energy_system_generators.energy_system_id', $id)
            ->select('energy_system_generators.generator_units', 
                'energy_generators.generator_model', 'energy_system_generators.cost',
                'energy_generators.generator_brand', 'energy_systems.name', 
                'energy_system_generators.id')
            ->get();

        $turbineSystems = DB::table('energy_system_wind_turbines')
            ->join('energy_systems', 'energy_system_wind_turbines.energy_system_id', 
                'energy_systems.id')
            ->join('energy_wind_turbines', 'energy_system_wind_turbines.energy_wind_turbine_id', 
                'energy_wind_turbines.id')
            ->where('energy_system_wind_turbines.energy_system_id', $id)
            ->select('energy_system_wind_turbines.turbine_units', 
                'energy_wind_turbines.wind_turbine_model', 'energy_system_wind_turbines.cost',
                'energy_wind_turbines.wind_turbine_brand', 'energy_systems.name', 
                'energy_system_wind_turbines.id')
            ->get();

        $pvMcbSystems = DB::table('energy_system_mcb_pvs')
            ->join('energy_systems', 'energy_system_mcb_pvs.energy_system_id', 
                'energy_systems.id')
            ->join('energy_mcb_pvs', 'energy_system_mcb_pvs.energy_mcb_pv_id', 
                'energy_mcb_pvs.id')
            ->where('energy_system_mcb_pvs.energy_system_id', $id)
            ->select('energy_system_mcb_pvs.mcb_pv_units', 
                'energy_mcb_pvs.model', 'energy_system_mcb_pvs.cost',
                'energy_mcb_pvs.brand', 'energy_systems.name', 
                'energy_system_mcb_pvs.id')
            ->get();

        $controllerMcbSystems = DB::table('energy_system_mcb_charge_controllers')
            ->join('energy_systems', 'energy_system_mcb_charge_controllers.energy_system_id', 
                'energy_systems.id')
            ->join('energy_mcb_charge_controllers', 
                'energy_system_mcb_charge_controllers.energy_mcb_charge_controller_id', 
                'energy_mcb_charge_controllers.id')
            ->where('energy_system_mcb_charge_controllers.energy_system_id', $id)
            ->select('energy_system_mcb_charge_controllers.mcb_controller_units', 
                'energy_mcb_charge_controllers.model', 
                'energy_mcb_charge_controllers.brand', 'energy_systems.name', 
                'energy_system_mcb_charge_controllers.id',
                'energy_system_mcb_charge_controllers.cost')
            ->get();

        $inventerMcbSystems = DB::table('energy_system_mcb_inverters')
            ->join('energy_systems', 'energy_system_mcb_inverters.energy_system_id', 
                'energy_systems.id')
            ->join('energy_mcb_inverters', 'energy_system_mcb_inverters.energy_mcb_inverter_id', 
                'energy_mcb_inverters.id')
            ->where('energy_system_mcb_inverters.energy_system_id', $id)
            ->select('energy_system_mcb_inverters.mcb_inverter_units', 
                'energy_mcb_inverters.inverter_MCB_model', 
                'energy_mcb_inverters.inverter_MCB_brand', 'energy_systems.name', 
                'energy_system_mcb_inverters.id', 'energy_system_mcb_inverters.cost')
            ->get();

        $airConditionerSystems = DB::table('energy_system_air_conditioners')
            ->join('energy_systems', 'energy_system_air_conditioners.energy_system_id', 
                'energy_systems.id')
            ->join('energy_air_conditioners', 'energy_system_air_conditioners.energy_air_conditioner_id', 
                'energy_air_conditioners.id')
            ->where('energy_system_air_conditioners.energy_system_id', $id)
            ->select('energy_system_air_conditioners.energy_air_conditioner_units', 
                'energy_air_conditioners.model', 'energy_system_air_conditioners.cost',
                'energy_air_conditioners.brand', 'energy_systems.name', 
                'energy_system_air_conditioners.id')
            ->get();

        return view('costs.energy.edit', compact('energySystem', 'battarySystems', 'pvSystems', 
            'controllerSystems', 'inverterSystems', 'relayDriverSystems', 'loadRelaySystems', 
            'bspSystems', 'rccSystems', 'loggerSystems', 'generatorSystems', 'turbineSystems', 
            'pvMcbSystems', 'controllerMcbSystems', 'inventerMcbSystems', 'airConditionerSystems',
            'gridRoom', 'battaryMountSystems', 'pvMountSystems', 'houseWiringSystem', 'refrigeratorCostSystem',
            'fbsWiringSystem', 'fbsLockSystem', 'fbsFanSystem', 'fbsCabinetSystem', 'btsSystems'));
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
        
        // grid & electricity cost
        $gridRoom = GridCommunityCompound::where('energy_system_id', $id)->first();
        $electricityRoomNumber = $request->input('electricity_room_number');
        $electricityRoomBosNumber = $request->input('electricity_room_bos_number');
        $electricityGridNumber = $request->input('grid_number');
        $electricityRoomCost = $request->input('electricity_room_cost');
        $electricityRoomBosCost = $request->input('electricity_room_bos_cost');
        $electricityGridCost = $request->input('grid_cost');

        if($gridRoom) {

            if($electricityRoomNumber) $gridRoom->electricity_room_number = $electricityRoomNumber;
            if($electricityRoomBosNumber) $gridRoom->electricity_room_bos_number = $electricityRoomBosNumber;
            if($electricityGridNumber) $gridRoom->grid_number = $electricityGridNumber;
    
            if($electricityRoomCost) $gridRoom->electricity_room_cost = $electricityRoomCost;
            if($electricityRoomBosCost) $gridRoom->electricity_room_bos_cost = $electricityRoomBosCost;
            if($electricityGridCost) $gridRoom->grid_cost = $electricityGridCost;
            $gridRoom->save();
        }

        // House wiring
        $existWiringSystem = EnergySystemWiringHouse::where('energy_system_id', $id)->first();
        if($existWiringSystem) {

            $existWiringSystem->cost = $request->wiring_cost;
            $existWiringSystem->unit = $request->wiring_unit;
            $existWiringSystem->save();
        } else {

            $newWiringSystem = new EnergySystemWiringHouse();
            $newWiringSystem->energy_system_id = $id;
            $newWiringSystem->cost = $request->wiring_cost;
            $newWiringSystem->unit = $request->wiring_unit;
            $newWiringSystem->save();
        }

        // FBS Wiring
        $existFbsWiringSystem = EnergySystemFbsWiring::where('energy_system_id', $id)->first();
        if($existFbsWiringSystem) {

            $existFbsWiringSystem->cost = $request->fbs_wiring_cost;
            $existFbsWiringSystem->unit = $request->fbs_wiring_unit;
            $existFbsWiringSystem->save();
        } else {

            $newFbsWiringSystem = new EnergySystemFbsWiring();
            $newFbsWiringSystem->energy_system_id = $id;
            $newFbsWiringSystem->cost = $request->fbs_wiring_cost;
            $newFbsWiringSystem->unit = $request->fbs_wiring_unit;
            $newFbsWiringSystem->save();
        }

        // FBS Lock
        $existFbsLockSystem = EnergySystemFbsLock::where('energy_system_id', $id)->first();
        if($existFbsLockSystem) {

            $existFbsLockSystem->cost = $request->fbs_lock_cost;
            $existFbsLockSystem->unit = $request->fbs_lock_unit;
            $existFbsLockSystem->save();
        } else {

            $newFbsLockSystem = new EnergySystemFbsLock();
            $newFbsLockSystem->energy_system_id = $id;
            $newFbsLockSystem->cost = $request->fbs_lock_cost;
            $newFbsLockSystem->unit = $request->fbs_lock_unit;
            $newFbsLockSystem->save();
        }

        // FBS Fan
        $existFbsFanSystem = EnergySystemFbsFan::where('energy_system_id', $id)->first();
        if($existFbsFanSystem) {

            $existFbsFanSystem->cost = $request->fbs_fan_cost;
            $existFbsFanSystem->unit = $request->fbs_fan_unit;
            $existFbsFanSystem->save();
        } else {

            $newFbsFanSystem = new EnergySystemFbsFan();
            $newFbsFanSystem->energy_system_id = $id;
            $newFbsFanSystem->cost = $request->fbs_fan_cost;
            $newFbsFanSystem->unit = $request->fbs_fan_unit;
            $newFbsFanSystem->save();
        }

        // FBS Cabinet
        $existFbsCabinetSystem = EnergySystemFbsCabinet::where('energy_system_id', $id)->first();
        if($existFbsCabinetSystem) {

            $existFbsCabinetSystem->cost = $request->fbs_cabinet_cost;
            $existFbsCabinetSystem->unit = $request->fbs_cabinet_unit;
            $existFbsCabinetSystem->save();
        } else {

            $newFbsCabinetSystem = new EnergySystemFbsCabinet();
            $newFbsCabinetSystem->energy_system_id = $id;
            $newFbsCabinetSystem->cost = $request->fbs_cabinet_cost;
            $newFbsCabinetSystem->unit = $request->fbs_cabinet_unit;
            $newFbsCabinetSystem->save();
        }

        // Refrigerator Cost
        $existRefrigeratorSystem = EnergySystemRefrigeratorCost::where('energy_system_id', $id)->first();
        if($existRefrigeratorSystem) {

            $existRefrigeratorSystem->cost = $request->refrigerator_cost;
            $existRefrigeratorSystem->unit = $request->refrigerator_unit;
            $existRefrigeratorSystem->save();
        } else {

            $newRefrigeratorSystem = new EnergySystemRefrigeratorCost();
            $newRefrigeratorSystem->energy_system_id = $id;
            $newRefrigeratorSystem->cost = $request->refrigerator_cost;
            $newRefrigeratorSystem->unit = $request->refrigerator_unit;
            $newRefrigeratorSystem->save();
        }

        // Battery
        $batteryCosts = $request->input('battery_costs');
        $batterySystemIds = $request->input('battery_ids');
    
        if($batterySystemIds) {

            foreach ($batterySystemIds as $index => $batterySystemId) {

                $batterySystem = EnergySystemBattery::findOrFail($batterySystemId);
                $batterySystem->cost = $batteryCosts[$index];
                $batterySystem->save();
            }
        }

        // Battery Mount
        $batteryMountCosts = $request->input('battery_mount_costs');
        $batteryMountSystemIds = $request->input('battery_mount_ids');
    
        if($batteryMountSystemIds) {

            foreach ($batteryMountSystemIds as $index => $batteryMountSystemId) {

                $batteryMountSystem = EnergySystemBatteryMount::findOrFail($batteryMountSystemId);
                $batteryMountSystem->cost = $batteryMountCosts[$index];
                $batteryMountSystem->save();
            }
        }

        // Solar Panel
        $pvCosts = $request->input('pv_costs');
        $pvSystemIds = $request->input('pv_ids');
    
        if($pvSystemIds) {

            foreach ($pvSystemIds as $index => $pvSystemId) {

                $pvSystem = EnergySystemPv::findOrFail($pvSystemId);
                $pvSystem->cost = $pvCosts[$index];
                $pvSystem->save();
            }
        }

        // Solar Panel Mount
        $pvMountCosts = $request->input('pv_mount_costs');
        $pvMountSystemIds = $request->input('pv_mount_ids');
    
        if($pvMountSystemIds) {

            foreach ($pvMountSystemIds as $index => $pvMountSystemId) {

                $pvSystem = EnergySystemPvMount::findOrFail($pvMountSystemId);
                $pvSystem->cost = $pvMountCosts[$index];
                $pvSystem->save();
            }
        }

        // Controller
        $controllerCosts = $request->input('controller_costs');
        $controllerSystemIds = $request->input('controller_ids');
    
        if($controllerSystemIds) {

            foreach ($controllerSystemIds as $index => $controllerSystemId) {

                $controllerSystem = EnergySystemChargeController::findOrFail($controllerSystemId);
                $controllerSystem->cost = $controllerCosts[$index];
                $controllerSystem->save();
            }
        }

        // Logger
        $loggerCosts = $request->input('logger_costs');
        $loggerSystemIds = $request->input('logger_ids');
    
        if($loggerSystemIds) {

            foreach ($loggerSystemIds as $index => $loggerSystemId) {

                $loggerSystem = EnergySystemMonitoring::findOrFail($loggerSystemId);
                $loggerSystem->cost = $loggerCosts[$index];
                $loggerSystem->save();
            }
        }

        // Inverter
        $inverterCosts = $request->input('inverter_costs');
        $inverterSystemIds = $request->input('inverter_ids');
    
        if($inverterSystemIds) {

            foreach ($inverterSystemIds as $index => $inverterSystemId) {

                $inverterSystem = EnergySystemInverter::findOrFail($inverterSystemId);
                $inverterSystem->cost = $inverterCosts[$index];
                $inverterSystem->save();
            }
        }

        // Relay Driver
        $relayCosts = $request->input('relay_costs');
        $relaySystemIds = $request->input('relay_ids');
    
        if($relaySystemIds) {
            foreach ($relaySystemIds as $index => $relaySystemId) {

                $relaySystem = EnergySystemRelayDriver::findOrFail($relaySystemId);
                $relaySystem->cost = $relayCosts[$index];
                $relaySystem->save();
            }
        }

        // Load Relay
        $loadCosts = $request->input('load_costs');
        $loadSystemIds = $request->input('load_ids');
    
        if($loadSystemIds) {

            foreach ($loadSystemIds as $index => $loadSystemId) {
    
                $loadSystem = EnergySystemLoadRelay::findOrFail($loadSystemId);
                $loadSystem->cost = $loadCosts[$index];
                $loadSystem->save();
            }
        }

        // Battery Status Processor
        $bspCosts = $request->input('bsp_costs');
        $bspSystemIds = $request->input('bsp_ids');
    
        if($bspSystemIds) {
            foreach ($bspSystemIds as $index => $bspSystemId) {

                $bspSystem = EnergySystemBatteryStatusProcessor::findOrFail($bspSystemId);
                $bspSystem->cost = $bspCosts[$index];
                $bspSystem->save();
            }
        }

        // BTS
        $btsCosts = $request->input('bts_costs');
        $btsSystemIds = $request->input('bts_ids');
    
        if($btsSystemIds) {
            foreach ($btsSystemIds as $index => $btsSystemId) {

                $btsSystem = EnergySystemBatteryTemperatureSensor::findOrFail($btsSystemId);
                $btsSystem->cost = $btsCosts[$index];
                $btsSystem->save();
            }
        }

        // Remote Control Center
        $rccCosts = $request->input('rcc_costs');
        $rccSystemIds = $request->input('rcc_ids');
    
        if($rccSystemIds) {

            foreach ($rccSystemIds as $index => $rccSystemId) {

                $rccSystem = EnergySystemRemoteControlCenter::findOrFail($rccSystemId);
                $rccSystem->cost = $rccCosts[$index];
                $rccSystem->save();
            }
        }

        // Generator
        $generatorCosts = $request->input('generator_costs');
        $generatorSystemIds = $request->input('generator_ids');
    
        if($generatorSystemIds) {

            foreach ($generatorSystemIds as $index => $generatorSystemId) {

                $generatorSystem = EnergySystemGenerator::findOrFail($generatorSystemId);
                $generatorSystem->cost = $generatorCosts[$index];
                $generatorSystem->save();
            }
        }

        // Wind Turbine
        $turbineCosts = $request->input('turbine_costs');
        $turbineSystemIds = $request->input('turbine_ids');
    
        if($turbineSystemIds) {

            foreach ($turbineSystemIds as $index => $turbineSystemId) {

                $turbineSystem = EnergySystemWindTurbine::findOrFail($turbineSystemId);
                $turbineSystem->cost = $turbineCosts[$index];
                $turbineSystem->save();
            }
        }

        // Solar Panel MCB
        $pvMcbCosts = $request->input('pvMcb_costs');
        $pvMcbSystemIds = $request->input('pvMcb_ids');
    
        if($pvMcbSystemIds) {

            foreach ($pvMcbSystemIds as $index => $pvMcbSystemId) {

                $pvMcbSystem = EnergySystemMcbPv::findOrFail($pvMcbSystemId);
                $pvMcbSystem->cost = $pvMcbCosts[$index];
                $pvMcbSystem->save();
            }
        }

        // Charge Controllers MCB
        $controllerMcbCosts = $request->input('controllerMcb_costs');
        $controllerMcbSystemIds = $request->input('controllerMcb_ids');
    
        if($controllerMcbSystemIds) {

            foreach ($controllerMcbSystemIds as $index => $controllerMcbSystemId) {

                $controllerMcbSystem = EnergySystemMcbChargeController::findOrFail($controllerMcbSystemId);
                $controllerMcbSystem->cost = $controllerMcbCosts[$index];
                $controllerMcbSystem->save();
            }
        }

        // Inverter MCB
        $inventerMcbCosts = $request->input('inventerMcb_costs');
        $inventerMcbSystemIds = $request->input('inventerMcb_ids');
    
        if($inventerMcbSystemIds) {

            foreach ($inventerMcbSystemIds as $index => $inventerMcbSystemId) {

                $inventerMcbSystem = EnergySystemMcbInverter::findOrFail($inventerMcbSystemId);
                $inventerMcbSystem->cost = $inventerMcbCosts[$index];
                $inventerMcbSystem->save();
            }
        }

        // Air Conditioner
        $airConditionerCosts = $request->input('airConditioner_costs');
        $airConditionerSystemIds = $request->input('airConditioner_ids');
    
        if($airConditionerSystemIds) {

            foreach ($airConditionerSystemIds as $index => $airConditionerSystemId) {

                $airConditionerSystem = EnergySystemAirConditioner::findOrFail($airConditionerSystemId);
                $airConditionerSystem->cost = $airConditionerCosts[$index];
                $airConditionerSystem->save();
            }
        }
        
        return redirect('/energy-cost')->with('message', 'Energy Cost Updated Successfully!');
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
                'energy_systems.id')
            ->join('energy_batteries', 'energy_system_batteries.battery_type_id', 
                'energy_batteries.id')
            ->where('energy_system_batteries.energy_system_id', $id)
            ->select('energy_system_batteries.battery_units', 'energy_batteries.battery_model', 
                'energy_batteries.battery_brand', 'energy_systems.name', 
                'energy_system_batteries.id')
            ->get(); 

        $pvSystems = DB::table('energy_system_pvs')
            ->join('energy_systems', 'energy_system_pvs.energy_system_id', 
                'energy_systems.id')
            ->join('energy_pvs', 'energy_system_pvs.pv_type_id', 
                'energy_pvs.id')
            ->where('energy_system_pvs.energy_system_id', $id)
            ->select('energy_system_pvs.pv_units', 'energy_pvs.pv_model', 
                'energy_pvs.pv_brand', 'energy_systems.name', 
                'energy_system_pvs.id')
            ->get(); 

        $controllerSystems = DB::table('energy_system_charge_controllers')
            ->join('energy_systems', 'energy_system_charge_controllers.energy_system_id', 
                'energy_systems.id')
            ->join('energy_charge_controllers', 'energy_system_charge_controllers.energy_charge_controller_id', 
                'energy_charge_controllers.id')
            ->where('energy_system_charge_controllers.energy_system_id', $id)
            ->select('energy_system_charge_controllers.controller_units', 
                'energy_charge_controllers.charge_controller_model', 
                'energy_charge_controllers.charge_controller_brand', 'energy_systems.name', 
                'energy_system_charge_controllers.id')
            ->get(); 

        $inverterSystems = DB::table('energy_system_inverters')
            ->join('energy_systems', 'energy_system_inverters.energy_system_id', 
                'energy_systems.id')
            ->join('energy_inverters', 'energy_system_inverters.energy_inverter_id', 
                'energy_inverters.id')
            ->where('energy_system_inverters.energy_system_id', $id)
            ->select('energy_system_inverters.inverter_units', 'energy_inverters.inverter_model', 
                'energy_inverters.inverter_brand', 'energy_systems.name', 
                'energy_system_inverters.id')
            ->get(); 

        $relayDriverSystems = DB::table('energy_system_relay_drivers')
            ->join('energy_systems', 'energy_system_relay_drivers.energy_system_id', 
                'energy_systems.id')
            ->join('energy_relay_drivers', 'energy_system_relay_drivers.relay_driver_type_id', 
                'energy_relay_drivers.id')
            ->where('energy_system_relay_drivers.energy_system_id', $id)
            ->select('energy_system_relay_drivers.relay_driver_units', 'energy_relay_drivers.model', 
                'energy_relay_drivers.brand', 'energy_systems.name', 
                'energy_system_relay_drivers.id')
            ->get(); 

        $loadRelaySystems = DB::table('energy_system_load_relays')
            ->join('energy_systems', 'energy_system_load_relays.energy_system_id', 
                'energy_systems.id')
            ->join('energy_load_relays', 'energy_system_load_relays.energy_load_relay_id', 
                'energy_load_relays.id')
            ->where('energy_system_load_relays.energy_system_id', $id)
            ->select('energy_system_load_relays.load_relay_units', 'energy_load_relays.load_relay_model', 
                'energy_load_relays.load_relay_brand', 'energy_systems.name', 
                'energy_system_load_relays.id')
            ->get();

        $bspSystems = DB::table('energy_system_battery_status_processors')
            ->join('energy_systems', 'energy_system_battery_status_processors.energy_system_id', 
                'energy_systems.id')
            ->join('energy_battery_status_processors', 'energy_system_battery_status_processors.energy_battery_status_processor_id', 
                'energy_battery_status_processors.id')
            ->where('energy_system_battery_status_processors.energy_system_id', $id)
            ->select('energy_system_battery_status_processors.bsp_units', 'energy_systems.name', 
                'energy_battery_status_processors.model', 'energy_battery_status_processors.brand', 
                'energy_system_battery_status_processors.id')
            ->get();

        $rccSystems = DB::table('energy_system_remote_control_centers')
            ->join('energy_systems', 'energy_system_remote_control_centers.energy_system_id', 
                'energy_systems.id')
            ->join('energy_remote_control_centers', 'energy_system_remote_control_centers.energy_remote_control_center_id', 
                'energy_remote_control_centers.id')
            ->where('energy_system_remote_control_centers.energy_system_id', $id)
            ->select('energy_system_remote_control_centers.rcc_units', 
                'energy_remote_control_centers.model', 
                'energy_remote_control_centers.brand', 'energy_systems.name', 
                'energy_system_remote_control_centers.id')
            ->get();

        $loggerSystems = DB::table('energy_system_monitorings')
            ->join('energy_systems', 'energy_system_monitorings.energy_system_id', 
                'energy_systems.id')
            ->join('energy_monitorings', 'energy_system_monitorings.energy_monitoring_id', 
                'energy_monitorings.id')
            ->where('energy_system_monitorings.energy_system_id', $id)
            ->select('energy_system_monitorings.monitoring_units', 
                'energy_monitorings.monitoring_model', 
                'energy_monitorings.monitoring_brand', 'energy_systems.name', 
                'energy_system_monitorings.id')
            ->get();

        $generatorSystems = DB::table('energy_system_generators')
            ->join('energy_systems', 'energy_system_generators.energy_system_id', 
                'energy_systems.id')
            ->join('energy_generators', 'energy_system_generators.energy_generator_id', 
                'energy_generators.id')
            ->where('energy_system_generators.energy_system_id', $id)
            ->select('energy_system_generators.generator_units', 
                'energy_generators.generator_model', 
                'energy_generators.generator_brand', 'energy_systems.name', 
                'energy_system_generators.id')
            ->get();

        $turbineSystems = DB::table('energy_system_wind_turbines')
            ->join('energy_systems', 'energy_system_wind_turbines.energy_system_id', 
                'energy_systems.id')
            ->join('energy_wind_turbines', 'energy_system_wind_turbines.energy_wind_turbine_id', 
                'energy_wind_turbines.id')
            ->where('energy_system_wind_turbines.energy_system_id', $id)
            ->select('energy_system_wind_turbines.turbine_units', 
                'energy_wind_turbines.wind_turbine_model', 
                'energy_wind_turbines.wind_turbine_brand', 'energy_systems.name', 
                'energy_system_wind_turbines.id')
            ->get();

        $pvMcbSystems = DB::table('energy_system_mcb_pvs')
            ->join('energy_systems', 'energy_system_mcb_pvs.energy_system_id', 
                'energy_systems.id')
            ->join('energy_mcb_pvs', 'energy_system_mcb_pvs.energy_mcb_pv_id', 
                'energy_mcb_pvs.id')
            ->where('energy_system_mcb_pvs.energy_system_id', $id)
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
                
        return Excel::download(new EnergyCostExport($request), 'energy_costs.xlsx');
    }
}