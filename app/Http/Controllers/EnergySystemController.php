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
use App\Models\EnergySystemPv;
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
use App\Models\FbsSystem;
use App\Models\Town;
use Carbon\Carbon;
use Auth;
use DataTables;
use DB;
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
        if ($request->ajax()) {

            $data = DB::table('energy_systems')
                ->join('energy_system_types', 'energy_systems.energy_system_type_id', 
                    '=', 'energy_system_types.id')
                ->select('energy_systems.id as id', 'energy_systems.created_at',
                    'energy_systems.updated_at', 'energy_systems.name',
                    'energy_systems.installation_year', 'energy_systems.upgrade_year1',
                    'energy_system_types.name as type')
                ->latest();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row) {

                    $viewButton = "<a type='button' class='viewEnergySystem' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#viewEnergySystemModal' ><i class='fa-solid fa-eye text-info'></i></a>";
                    $updateButton = "<a type='button' class='updateEnergySystem' data-id='".$row->id."' ><i class='fa-solid fa-pen-to-square text-success'></i></a>";
                    $deleteButton = "<a type='button' class='deleteEnergySystem' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";
                    
                    return $viewButton." ". $updateButton." ".$deleteButton;
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

        $communities = Community::all();
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

		return view('system.energy.index', compact('communities', 'donors', 'services'))
        ->with(
            'energySystemData', json_encode($arrayEnergySystem));

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
        $batteries = EnergyBattery::all();
        $chargeControllers = EnergyChargeController::all();
        $mcbChargeControllers = EnergyMcbChargeController::all();
        $energySystem = EnergySystem::findOrFail($id);
        $solarPanles = EnergyPv::all();
        $inventers = EnergyInverter::all();
        $relayDrivers = EnergyRelayDriver::all();
        $loadRelaies = EnergyLoadRelay::all();
        $bsps = EnergyBatteryStatusProcessor::all();
        $mcbInventers = EnergyMcbInverter::all();
        $loggers = EnergyMonitoring::all();
        $mcbPvs = EnergyMcbPv::all();
        $fbsSystem = 0;

        if($energySystem->energy_system_type_id == 2) {

            $fbsSystem = FbsSystem::where('energy_system_id', $id)->get();
            $fbsSystem = $fbsSystem[0];
        }

        $energySystemBatteries = EnergySystemBattery::where('energy_system_id', $id)->get();
        $energySystemRelayDrivers = EnergySystemRelayDriver::where('energy_system_id', $id)->get();
        $energySystemPvs = EnergySystemPv::where('energy_system_id', $id)->get();

        return view('system.energy.edit', compact('batteries', 'chargeControllers', 'energySystem',
            'solarPanles', 'inventers', 'relayDrivers', 'mcbChargeControllers', 'loadRelaies', 
            'bsps', 'mcbInventers', 'loggers', 'mcbPvs', 'fbsSystem', 'energySystemBatteries',
            'energySystemRelayDrivers', 'energySystemPvs'));
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
        $energySystem = FbsSystem::where('energy_system_id', $id)->get();
        $energySystem = FbsSystem::findOrFail($energySystem[0]->id);
        $energySystemType = EnergySystem::findOrFail($id);

        if($energySystemType->energy_system_type_id == 2) {

            if($request->battery_type_id)
                $energySystem->battery_type_id = $request->battery_type_id;
            if($request->battery_units)
                $energySystem->battery_units = $request->battery_units;
            if($request->solar_panel_type_id)
                $energySystem->solar_panel_type_id = $request->solar_panel_type_id;
            if($request->solar_panel_units)
                $energySystem->solar_panel_units = $request->solar_panel_units;
            if($request->charge_controller_type_id)
                $energySystem->charge_controller_type_id = $request->charge_controller_type_id;
            if($request->charge_controller_units)
                $energySystem->charge_controller_units = $request->charge_controller_units;
            if($request->charge_controller_mcb_type_id)
                $energySystem->charge_controller_mcb_type_id = $request->charge_controller_mcb_type_id;
            if($request->charge_controller_mcb_units)
                $energySystem->charge_controller_mcb_units = $request->charge_controller_mcb_units;
            if($request->relay_driver_type_id)
                $energySystem->relay_driver_type_id = $request->relay_driver_type_id;
            if($request->relay_driver_units)
                $energySystem->relay_driver_units = $request->relay_driver_units;
            if($request->pv_mcb_type_id)
                $energySystem->pv_mcb_type_id = $request->pv_mcb_type_id;
            if($request->pv_mcb_units)
                $energySystem->pv_mcb_units = $request->pv_mcb_units;
            if($request->invertor_type_id)    
                $energySystem->invertor_type_id = $request->invertor_type_id;
            if($request->invertor_units)
                $energySystem->invertor_units = $request->invertor_units;
            if($request->invertor_mcb_type_id)
                $energySystem->invertor_mcb_type_id = $request->invertor_mcb_type_id;
            if($request->invertor_mcb_units)
                $energySystem->invertor_mcb_units = $request->invertor_mcb_units;
            if($request->bsp_type_id)
                $energySystem->bsp_type_id = $request->bsp_type_id;
            if($request->bsp_type_units)
                $energySystem->bsp_type_units = $request->bsp_type_units;
            if($request->load_relay_id)
                $energySystem->load_relay_id = $request->load_relay_id;
            if($request->load_relay_units)
                $energySystem->load_relay_units = $request->load_relay_units;
            if($request->logger_type_id)
                $energySystem->logger_type_id = $request->logger_type_id;
            if($request->logger_type_units)
                $energySystem->logger_type_units = $request->logger_type_units;
        }

        $energySystem->save();

        return redirect('/energy-system')->with('message', 'Energy System Updated Successfully!');
    }
}