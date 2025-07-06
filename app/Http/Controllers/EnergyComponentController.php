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
use App\Models\EnergyBatteryStatusProcessor;
use App\Models\EnergyBatteryTemperatureSensor;
use App\Models\EnergyChargeController;
use App\Models\EnergyGenerator;
use App\Models\EnergyInverter;
use App\Models\EnergyLoadRelay;
use App\Models\EnergyMcbAc;
use App\Models\EnergyMcbPv;
use App\Models\EnergyMonitoring;
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
use App\Models\EnergySystemRemoteControlCenter;
use App\Models\EnergySystemWindTurbine;
use App\Models\EnergySystemGenerator;
use App\Models\EnergySystemBatteryStatusProcessor;
use App\Models\EnergySystemBatteryTemperatureSensor;
use App\Models\EnergySystemInverter;
use App\Models\EnergySystemLoadRelay;
use App\Models\EnergySystemMcbPv;
use App\Models\EnergySystemMcbChargeController;
use App\Models\EnergySystemMcbInverter;
use App\Models\EnergyWindTurbine;
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
use App\Models\EnergySystemFbsCabinet;
use App\Models\EnergySystemFbsFan;
use App\Models\EnergySystemFbsLock;
use App\Models\EnergySystemFbsWiring;
use App\Models\EnergySystemWiringHouse;
use App\Models\EnergySystemElectricityRoom;
use App\Models\EnergySystemElectricityBosRoom;
use App\Models\EnergySystemGrid;
use App\Models\EnergySystemRefrigeratorCost;
use App\Models\FbsSystem;
use App\Models\Town;
use Carbon\Carbon;
use Auth;
use DataTables;
use DB;
use Image;
use Route;

class EnergyComponentController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('system.energy.component.create');
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

        // Battery 
        if($request->battery_models[0]["subject"] != null) {
            for($i=0; $i < count($request->battery_models); $i++) {

                $newBattery = new EnergyBattery();
                $newBattery->battery_model = $request->battery_models[$i]["subject"];
                $newBattery->battery_brand = $request->battery_brands[$i]["subject"];
                $newBattery->save();
            }
        }

        // Solar Panel
        if($request->pv_brands[0]["subject"] != null) {
            for($i=0; $i < count($request->pv_brands); $i++) {

                $newPv = new EnergyPv();
                $newPv->pv_model = $request->pv_models[$i]["subject"];
                $newPv->pv_brand = $request->pv_brands[$i]["subject"];
                $newPv->save();
            }
        }

        // Controller
        if($request->charge_controller_brands[0]["subject"] != null) {
            for($i=0; $i < count($request->charge_controller_brands); $i++) {

                $newController = new EnergyChargeController();
                $newController->charge_controller_model = $request->charge_controller_models[$i]["subject"];
                $newController->charge_controller_brand = $request->charge_controller_brands[$i]["subject"];
                $newController->save();
            }
        }

        // Inverter
        if($request->inverter_models[0]["subject"] != null) {
            for($i=0; $i < count($request->inverter_models); $i++) {

                $newInverter = new EnergyInverter();
                $newInverter->inverter_model = $request->inverter_models[$i]["subject"];
                $newInverter->inverter_brand = $request->inverter_brands[$i]["subject"];
                $newInverter->save();
            }
        }

        // Relay Driver
        if($request->relay_driver_models[0]["subject"] != null) {
            for($i=0; $i < count($request->relay_driver_models); $i++) {

                $newRelayDriver = new EnergyRelayDriver();
                $newRelayDriver->model = $request->relay_driver_models[$i]["subject"];
                $newRelayDriver->brand = $request->relay_driver_brands[$i]["subject"];
                $newRelayDriver->save();
            }
        }

        // Load Relay
        if($request->load_relay_models[0]["subject"] != null) {
            for($i=0; $i < count($request->load_relay_models); $i++) {

                $newLoadRelay = new EnergyLoadRelay();
                $newLoadRelay->load_relay_model = $request->load_relay_models[$i]["subject"];
                $newLoadRelay->load_relay_brand = $request->load_relay_brands[$i]["subject"];
                $newLoadRelay->save();
            }
        }

        // BSP
        if($request->bsp_models[0]["subject"] != null) {
            for($i=0; $i < count($request->bsp_models); $i++) {

                $newBsp = new EnergyBatteryStatusProcessor();
                $newBsp->model = $request->bsp_models[$i]["subject"];
                $newBsp->brand = $request->bsp_brands[$i]["subject"];
                $newBsp->save();
            }
        }

        // RCC
        if($request->rcc_models[0]["subject"] != null) {
            for($i=0; $i < count($request->rcc_models); $i++) {

                $newRcc = new EnergyRemoteControlCenter();
                $newRcc->model = $request->rcc_models[$i]["subject"];
                $newRcc->brand = $request->rcc_brands[$i]["subject"];
                $newRcc->save();
            }
        }

        // Logger
        if($request->logger_models[0]["subject"] != null) {
            for($i=0; $i < count($request->logger_models); $i++) {

                $newLogger = new EnergyMonitoring();
                $newLogger->monitoring_model = $request->logger_models[$i]["subject"];
                $newLogger->monitoring_brand = $request->logger_brands[$i]["subject"];
                $newLogger->save();
            }
        }

        // Generator
        if($request->generator_models[0]["subject"] != null) {
            for($i=0; $i < count($request->generator_models); $i++) {

                $newGenerator = new EnergyGenerator();
                $newGenerator->generator_model = $request->generator_models[$i]["subject"];
                $newGenerator->generator_brand = $request->generator_brands[$i]["subject"];
                $newGenerator->save();
            }
        }

        // Wind Turbine
        if($request->turbine_models[0]["subject"] != null) {
            for($i=0; $i < count($request->turbine_models); $i++) {

                $newWindTurbine = new EnergyWindTurbine();
                $newWindTurbine->wind_turbine_model = $request->turbine_models[$i]["subject"];
                $newWindTurbine->wind_turbine_brand = $request->turbine_brands[$i]["subject"];
                $newWindTurbine->save();
            }
        }

        // Controllers MCB
        if($request->charge_controller_mcb_models[0]["subject"] != null) {
            for($i=0; $i < count($request->charge_controller_mcb_models); $i++) {

                $newMcbController = new EnergyMcbChargeController();
                $newMcbController->model = $request->charge_controller_mcb_models[$i]["subject"];
                $newMcbController->brand = $request->charge_controller_mcb_brands[$i]["subject"];
                $newMcbController->save();
            }
        }

        // Inverter MCB
        if($request->inverter_mcb_models[0]["subject"] != null) {
            for($i=0; $i < count($request->inverter_mcb_models); $i++) {

                $newMcbInventor = new EnergyMcbInverter();
                $newMcbInventor->inverter_MCB_model = $request->inverter_mcb_models[$i]["subject"];
                $newMcbInventor->inverter_MCB_brand = $request->inverter_mcb_brands[$i]["subject"];
                $newMcbInventor->save();
            }
        }

        // PV MCB
        if($request->pv_mcb_models[0]["subject"] != null) {
            for($i=0; $i < count($request->pv_mcb_models); $i++) {

                $newMcbPv = new EnergyMcbPv();
                $newMcbPv->model = $request->pv_mcb_models[$i]["subject"];
                $newMcbPv->brand = $request->pv_mcb_brands[$i]["subject"];
                $newMcbPv->save();
            }
        }

        return redirect('/energy-system')
            ->with('message', 'New Energy Components Added Successfully!');
    }

    /**
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteEnergySystemBattery(Request $request)
    {
        $id = $request->id;

        $energyBattery = EnergySystemBattery::find($request->id);

        if($energyBattery->delete()) {

            $response['success'] = 1;
            $response['msg'] = 'Battery System Deleted successfully'; 
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
    public function deleteEnergySystemBatteryMount(Request $request)
    {
        $id = $request->id;

        $energyBattery = EnergySystemBatteryMount::find($request->id);

        if($energyBattery->delete()) {

            $response['success'] = 1;
            $response['msg'] = 'Battery Mount System Deleted successfully'; 
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
    public function deleteEnergySystemPv(Request $request)
    {
        $id = $request->id;

        $energyPv = EnergySystemPv::find($request->id);

        if($energyPv->delete()) {

            $response['success'] = 1;
            $response['msg'] = 'Pv System Deleted successfully'; 
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
    public function deleteEnergySystemPvMount(Request $request)
    {
        $id = $request->id;

        $energyPv = EnergySystemPvMount::find($request->id);

        if($energyPv->delete()) {

            $response['success'] = 1;
            $response['msg'] = 'Pv Mount System Deleted successfully'; 
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
    public function deleteEnergySystemController(Request $request)
    {
        $id = $request->id;

        $energyController = EnergySystemChargeController::find($request->id);

        if($energyController->delete()) {

            $response['success'] = 1;
            $response['msg'] = 'Controller System Deleted successfully'; 
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
    public function deleteEnergySystemRelayDriver(Request $request)
    {
        $id = $request->id;

        $energyRelayDriver = EnergySystemRelayDriver::find($request->id);

        if($energyRelayDriver->delete()) {

            $response['success'] = 1;
            $response['msg'] = 'Relay Driver System Deleted successfully'; 
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
    public function deleteEnergySystemGenerator(Request $request)
    {
        $id = $request->id;

        $energyGenerator = EnergySystemGenerator::find($request->id);

        if($energyGenerator->delete()) {

            $response['success'] = 1;
            $response['msg'] = 'Generator System Deleted successfully'; 
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
    public function deleteEnergySystemRcc(Request $request)
    {
        $id = $request->id;

        $energyRcc = EnergySystemRemoteControlCenter::find($request->id);

        if($energyRcc->delete()) {

            $response['success'] = 1;
            $response['msg'] = 'Rcc System Deleted successfully'; 
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
    public function deleteEnergySystemInverter(Request $request)
    {
        $id = $request->id;

        $energyInverter = EnergySystemInverter::find($request->id);

        if($energyInverter->delete()) {

            $response['success'] = 1;
            $response['msg'] = 'Inverter System Deleted successfully'; 
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
    public function deleteEnergySystemLoadRelay(Request $request)
    {
        $id = $request->id;

        $energyLoadRelay = EnergySystemLoadRelay::find($request->id);

        if($energyLoadRelay->delete()) {

            $response['success'] = 1;
            $response['msg'] = 'Load Relay System Deleted successfully'; 
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
    public function deleteEnergySystemMonitoring(Request $request)
    {
        $id = $request->id;

        $energyMonitoring = EnergySystemMonitoring::find($request->id);

        if($energyMonitoring->delete()) {

            $response['success'] = 1;
            $response['msg'] = 'Monitoring System Deleted successfully'; 
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
    public function deleteEnergySystemBsp(Request $request)
    {
        $id = $request->id;

        $energyBsp = EnergySystemBatteryStatusProcessor::find($request->id);

        if($energyBsp->delete()) {

            $response['success'] = 1;
            $response['msg'] = 'BSP System Deleted successfully'; 
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
    public function deleteEnergySystemMcbPv(Request $request)
    {
        $id = $request->id;

        $energyPv = EnergySystemMcbPv::find($request->id);

        if($energyPv->delete()) {

            $response['success'] = 1;
            $response['msg'] = 'MCB PV System Deleted successfully'; 
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
    public function deleteEnergySystemMcbController(Request $request)
    {
        $id = $request->id;

        $energyController = EnergySystemMcbChargeController::find($request->id);

        if($energyController->delete()) {

            $response['success'] = 1;
            $response['msg'] = 'MCB Controller System Deleted successfully'; 
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
    public function deleteEnergySystemMcbInverter(Request $request)
    {
        $id = $request->id;

        $energyInverter = EnergySystemMcbInverter::find($request->id);

        if($energyInverter->delete()) {

            $response['success'] = 1;
            $response['msg'] = 'MCB Inverter System Deleted successfully'; 
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
    public function deleteEnergySystemTurbine(Request $request)
    {
        $id = $request->id;

        $energyTurbine = EnergySystemWindTurbine::find($request->id);

        if($energyTurbine->delete()) {

            $response['success'] = 1;
            $response['msg'] = 'Turbine System Deleted successfully'; 
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
    public function deleteEnergySystemAirConditioner(Request $request)
    {
        $id = $request->id;

        $energyAir = EnergySystemAirConditioner::find($request->id);

        if($energyAir->delete()) {

            $response['success'] = 1;
            $response['msg'] = 'Air Conditioner Deleted successfully'; 
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
    public function deleteEnergySystemBts(Request $request)
    {
        $id = $request->id;

        $energyBts = EnergySystemBatteryTemperatureSensor::find($request->id);

        if($energyBts->delete()) {

            $response['success'] = 1;
            $response['msg'] = 'BTS Deleted successfully'; 
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
    public function deleteEnergySystemCabinet(Request $request)
    {
        $id = $request->id;

        $fbsCabinet = EnergySystemFbsCabinet::find($request->id);

        if($fbsCabinet->delete()) {

            $response['success'] = 1;
            $response['msg'] = 'FBS Cabinet Deleted successfully'; 
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
    public function deleteEnergySystemFan(Request $request)
    {
        $id = $request->id;

        $fbsFan = EnergySystemFbsFan::find($request->id);

        if($fbsFan->delete()) {

            $response['success'] = 1;
            $response['msg'] = 'FBS Fan Deleted successfully'; 
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
    public function deleteEnergySystemLock(Request $request)
    {
        $id = $request->id;

        $fbsLock = EnergySystemFbsLock::find($request->id);

        if($fbsLock->delete()) {

            $response['success'] = 1;
            $response['msg'] = 'FBS Lock Deleted successfully'; 
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
    public function deleteEnergySystemWiring(Request $request)
    {
        $id = $request->id;

        $fbsWiring = EnergySystemFbsWiring::find($request->id);

        if($fbsWiring->delete()) {

            $response['success'] = 1;
            $response['msg'] = 'FBS Wiring Deleted successfully'; 
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
    public function deleteEnergySystemWiringHouse(Request $request)
    {
        $id = $request->id;

        $houseWiring = EnergySystemWiringHouse::find($request->id);

        if($houseWiring->delete()) {

            $response['success'] = 1;
            $response['msg'] = 'House Wiring Deleted successfully'; 
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
    public function deleteEnergySystemElectricityRoom(Request $request)
    {
        $id = $request->id;

        $electricityRoom = EnergySystemElectricityRoom::find($request->id);

        if($electricityRoom->delete()) {

            $response['success'] = 1;
            $response['msg'] = 'Electricity Room Deleted successfully'; 
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
    public function deleteEnergySystemElectricityBosRoom(Request $request)
    {
        $id = $request->id;

        $electricityRoomBos = EnergySystemElectricityBosRoom::find($request->id);

        if($electricityRoomBos->delete()) {

            $response['success'] = 1;
            $response['msg'] = 'Electricity Bos Deleted successfully'; 
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
    public function deleteEnergySystemGrid(Request $request)
    {
        $id = $request->id;

        $energyGrid = EnergySystemGrid::find($request->id);

        if($energyGrid->delete()) {

            $response['success'] = 1;
            $response['msg'] = 'Community Grid Deleted successfully'; 
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
    public function deleteEnergySystemRefrigerator(Request $request)
    {
        $id = $request->id;

        $energyRefrigerator = EnergySystemRefrigeratorCost::find($request->id);

        if($energyRefrigerator->delete()) {

            $response['success'] = 1;
            $response['msg'] = 'Refrigerator Deleted successfully'; 
        } else {

            $response['success'] = 0;
            $response['msg'] = 'Invalid ID.';
        }

        return response()->json($response); 
    }
    
}
