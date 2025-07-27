<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use DB;
use Route;
use App\Models\User;
use App\Models\Community;
use App\Models\CommunityDonor;
use App\Models\Donor;
use App\Models\InternetUser; 
use App\Models\InternetUserDonor;
use App\Models\InternetSystemType;
use App\Models\InternetSystem;
use App\Models\InternetSystemCommunity;
use App\Models\Household;
use App\Models\Region;
use App\Models\Router;
use App\Models\Switche;
use App\Models\CameraShelve; 
use App\Models\NetworkCabinet; 
use App\Models\AirPatchPanel; 
use App\Models\PatchPanel; 
use App\Models\PatchCord; 
use App\Models\PowerDistributor; 
use App\Models\Keystone; 
use App\Models\SwitchInternetSystem;
use App\Models\RouterInternetSystem;
use App\Models\ApInternetSystem;
use App\Models\ApLiteInternetSystem;
use App\Models\InternetAp;
use App\Models\ControllerInternetSystem;
use App\Models\InternetController;
use App\Models\PtpInternetSystem;
use App\Models\InternetPtp;
use App\Models\InternetUisp;
use App\Models\UispInternetSystem;
use App\Models\InternetSystemCommunityType;
use App\Models\LineOfSight;
use App\Models\InternetElectrician;
use App\Models\ElectricianInternetSystem;
use App\Models\ConnectorInternetSystem;
use App\Models\InternetConnector;
use App\Models\NvrCamera;
use App\Models\NetworkCabinetInternetSystem;
use App\Models\NetworkCabinetComponent;
use Carbon\Carbon;
use Image;
use DataTables;

class InternetSystemController extends Controller
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
 
                $data = DB::table('internet_system_communities')
                    ->where('internet_system_communities.is_archived', 0)
                    ->join('communities', 'internet_system_communities.community_id', 
                        'communities.id')
                    ->join('internet_systems', 'internet_system_communities.internet_system_id', 
                        'internet_systems.id')
                    ->join('internet_system_community_types', 'internet_system_community_types.internet_system_id', 
                        'internet_systems.id')
                    ->join('internet_system_types', 'internet_system_community_types.internet_system_type_id', 
                        'internet_system_types.id')
                    ->where('internet_system_community_types.is_archived', 0)
                    ->select(
                         DB::raw("GROUP_CONCAT(DISTINCT COALESCE(internet_system_types.name) 
                        SEPARATOR ', ') as name"),
                        'internet_systems.start_year', 
                        'internet_system_types.upgrade_year', 'internet_systems.system_name',
                        'internet_systems.id as id',
                        'internet_system_communities.created_at as created_at', 
                        'internet_system_communities.updated_at as updated_at', 
                        'communities.english_name as community_name')
                    ->latest()
                    ->groupBy('internet_system_communities.id'); 
    
                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
                        $viewButton = "<a type='button' class='viewInternetSystem' data-id='".$row->id."' ><i class='fa-solid fa-eye text-info'></i></a>";
                        $updateButton = "<a type='button' class='updateInternetSystem' data-id='".$row->id."' ><i class='fa-solid fa-pen-to-square text-success'></i></a>";
                        $cabinetButton = "<a type='button' class='cabinetInternetSystem' data-id='".$row->id."' ><i class='fa-solid fa-server text-primary'></i></a>";
                        $deleteButton = "<a type='button' class='deleteInternetSystem' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";
                        
                        if(Auth::guard('user')->user()->user_type_id == 1 || 
                            Auth::guard('user')->user()->user_type_id == 2 ||
                            Auth::guard('user')->user()->user_type_id == 6 ||
                            Auth::guard('user')->user()->user_type_id == 10||
                            Auth::guard('user')->user()->user_type_id == 13) 
                        {
                                
                            return $viewButton." ". $updateButton." ". $cabinetButton. " ". $deleteButton;
                        } else return $viewButton;
                        
                    })
                    ->filter(function ($instance) use ($request) {
                        if (!empty($request->get('search'))) {
                                $instance->where(function($w) use($request) {
                                $search = $request->get('search');
                                $w->orWhere('communities.english_name', 'LIKE', "%$search%")
                                ->orWhere('internet_system_types.name', 'LIKE', "%$search%")
                                ->orWhere('communities.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('internet_systems.system_name', 'LIKE', "%$search%")
                                ->orWhere('internet_system_types.start_year', 'LIKE', "%$search%")
                                ->orWhere('internet_system_types.upgrade_year', 'LIKE', "%$search%");
                            });
                        }
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }

            return view('system.internet.index');
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
        $aps = InternetAp::all();
        $communities = Community::where('is_archived', 0)
            ->orderBy('english_name', 'ASC')
            ->get();
        $controllers = InternetController::all();
        $internetSystemTypes = InternetSystemType::all();
        $routers = Router::all();
        $switches = Switche::all();
        $ptps = InternetPtp::all();
        $uisps = InternetUisp::all();
        $electricians = InternetElectrician::all();
        $connectors = InternetConnector::all();

        return view('system.internet.create', compact('aps', 'communities', 'controllers',
            'internetSystemTypes', 'routers', 'switches', 'ptps', 'uisps', 'electricians',
            'connectors'));
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
        $last_comet_id = InternetSystem::latest('id')->value('comet_id');

        $internetSystem = new InternetSystem();
        $internetSystem->comet_id = ++$last_comet_id;
        $internetSystem->fake_meter_number = 'IS' . ++$last_comet_id;
        $internetSystem->system_name = $request->system_name;
        $internetSystem->start_year = $request->start_year;
        $internetSystem->notes = $request->notes;
        $internetSystem->save();

        if($request->internet_system_type_id) {

            for($i=0; $i < count($request->internet_system_type_id); $i++) {

                $internetSystemType = new InternetSystemCommunityType();
                $internetSystemType->internet_system_type_id = $request->internet_system_type_id[$i];
                $internetSystemType->internet_system_id = $internetSystem->id;
                $internetSystemType->save();
            }
        }

        $internetSystemCommunity = new InternetSystemCommunity();
        $internetSystemCommunity->community_id = $request->community_id;
        if($request->compound_id) $internetSystemCommunity->compound_id = $request->compound_id; 
        $internetSystemCommunity->internet_system_id = $internetSystem->id;
        $internetSystemCommunity->save();

        $community = Community::findOrFail($request->community_id);
        $community->internet_service = "Yes";
        if($community->internet_service_beginning_year == Null) {

            $community->internet_service_beginning_year = $request->start_year;
        }
        $community->save();


        return redirect('/internet-system')
            ->with('message', 'New Internet System Added Successfully!');
    }

    /**
     * View Edit page.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function editPage($id)
    {
        $internetSystem = InternetSystem::findOrFail($id);

        return response()->json($internetSystem);
    }

    /**
     * View Edit page.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $internetSystem = InternetSystem::findOrFail($id);
        $internetSystemTypes = InternetSystemCommunityType::where('internet_system_id', $id)
            ->where("is_archived", 0)
            ->get();
        $internetCommunities = InternetSystemCommunity::where('internet_system_id', $id)->get();
     
        $internetTypes = InternetSystemType::all();

        // Router
        $routerSystems = DB::table('router_internet_systems')
            ->join('internet_systems', 'router_internet_systems.internet_system_id', 
                'internet_systems.id')
            ->join('routers', 'router_internet_systems.router_id', 
                'routers.id')
            ->where('router_internet_systems.internet_system_id', $id)
            ->select('router_internet_systems.router_units', 'routers.model', 
                'routers.brand_name', 'internet_systems.system_name', 
                'router_internet_systems.id', 'router_internet_systems.router_costs')
            ->get(); 

        // Switch
        $switchSystems = DB::table('switch_internet_systems')
            ->join('internet_systems', 'switch_internet_systems.internet_system_id', 
                '=', 'internet_systems.id')
            ->join('switches', 'switch_internet_systems.switch_id', 
                '=', 'switches.id')
            ->where('switch_internet_systems.internet_system_id', '=', $id)
            ->select('switch_internet_systems.switch_units', 'switches.model', 
                'switches.brand_name', 'internet_systems.system_name',
                'switch_internet_systems.id', 'switch_internet_systems.switch_costs')
            ->get(); 

        // Controller
        $controllerSystems = DB::table('controller_internet_systems')
            ->join('internet_systems', 'controller_internet_systems.internet_system_id', 
                '=', 'internet_systems.id')
            ->join('internet_controllers', 'controller_internet_systems.internet_controller_id', 
                '=', 'internet_controllers.id')
            ->where('controller_internet_systems.internet_system_id', '=', $id)
            ->select('controller_internet_systems.controller_units', 'internet_controllers.model', 
                'internet_controllers.brand', 'internet_systems.system_name',
                'controller_internet_systems.id', 'controller_internet_systems.controller_costs')
            ->get();

        // PTP 
        $ptpSystems = DB::table('ptp_internet_systems')
            ->join('internet_systems', 'ptp_internet_systems.internet_system_id', 
                '=', 'internet_systems.id')
            ->join('internet_ptps', 'ptp_internet_systems.internet_ptp_id', 
                '=', 'internet_ptps.id')
            ->where('ptp_internet_systems.internet_system_id', '=', $id)
            ->select('ptp_internet_systems.ptp_units', 'internet_ptps.model', 
                'internet_ptps.brand', 'internet_systems.system_name',
                'ptp_internet_systems.id', 'ptp_internet_systems.ptp_costs')
            ->get();

        // AP
        $apSystems = DB::table('ap_internet_systems')
            ->join('internet_systems', 'ap_internet_systems.internet_system_id', 
                '=', 'internet_systems.id')
            ->join('internet_aps', 'ap_internet_systems.internet_ap_id', 
                '=', 'internet_aps.id')
            ->where('ap_internet_systems.internet_system_id', '=', $id)
            ->select('ap_internet_systems.ap_units', 'internet_aps.model', 
                'internet_aps.brand', 'internet_systems.system_name',
                'ap_internet_systems.id', 'ap_internet_systems.ap_costs')
            ->get();

        // AP Lite
        $apLiteSystems = DB::table('ap_lite_internet_systems')
            ->join('internet_systems', 'ap_lite_internet_systems.internet_system_id', 
                '=', 'internet_systems.id')
            ->join('internet_aps', 'ap_lite_internet_systems.internet_ap_id', 
                '=', 'internet_aps.id')
            ->where('ap_lite_internet_systems.internet_system_id', '=', $id)
            ->select('ap_lite_internet_systems.ap_lite_units', 'internet_aps.model', 
                'internet_aps.brand', 'internet_systems.system_name',
                'ap_lite_internet_systems.id', 'ap_lite_internet_systems.ap_lite_costs')
            ->get();

        // UISP
        $uispSystems = DB::table('uisp_internet_systems')
            ->join('internet_systems', 'uisp_internet_systems.internet_system_id', 
                '=', 'internet_systems.id')
            ->join('internet_uisps', 'uisp_internet_systems.internet_uisp_id', 
                '=', 'internet_uisps.id')
            ->where('uisp_internet_systems.internet_system_id', '=', $id)
            ->select('uisp_internet_systems.uisp_units', 'internet_uisps.model', 
                'internet_uisps.brand', 'internet_systems.system_name',
                'uisp_internet_systems.id', 'uisp_internet_systems.uisp_costs')
            ->get();

        // Electrician
        $electricianSystems = DB::table('electrician_internet_systems')
            ->join('internet_systems', 'electrician_internet_systems.internet_system_id', 
                'internet_systems.id')
            ->join('internet_electricians', 'electrician_internet_systems.internet_electrician_id', 
                'internet_electricians.id')
            ->where('electrician_internet_systems.internet_system_id', $id)
            ->select('electrician_internet_systems.electrician_units', 'internet_electricians.model', 
                'internet_electricians.brand', 'internet_systems.system_name',
                'electrician_internet_systems.id', 'electrician_internet_systems.electrician_costs')
            ->get();

        // Connector
        $connectorSystems = DB::table('connector_internet_systems')
            ->join('internet_systems', 'connector_internet_systems.internet_system_id', 
                'internet_systems.id')
            ->join('internet_connectors', 'connector_internet_systems.internet_connector_id', 
                'internet_connectors.id')
            ->where('connector_internet_systems.internet_system_id', $id)
            ->select('connector_internet_systems.connector_units', 'internet_connectors.model', 
                'internet_connectors.brand', 'internet_systems.system_name',
                'connector_internet_systems.id', 'connector_internet_systems.connector_costs')
            ->get();

        $aps = InternetAp::all();
        $controllers = InternetController::all();
        $routers = Router::all();
        $switchs = Switche::all();
        $ptps = InternetPtp::all();
        $uisps = InternetUisp::all();
        $electricians = InternetElectrician::all();
        $connectors = InternetConnector::all();
        $cameraShelves = CameraShelve::all();
        $patchPaneles = PatchPanel::all();
        $airPatchPaneles = AirPatchPanel::all();
        $patchCords = PatchCord::all();
        $powerDistributors = PowerDistributor::all();
        $Keystones = Keystone::all();


        return view('system.internet.edit', compact('routers', 'switchs', 'controllers',
            'ptps', 'uisps', 'internetSystem', 'internetSystemTypes', 'aps', 'connectors',
            'internetTypes', 'routerSystems', 'ptpSystems', 'controllerSystems', 
            'switchSystems', 'apSystems', 'apLiteSystems', 'uispSystems', 'electricians',
            'electricianSystems', 'connectorSystems', 'Keystones', 'powerDistributors',
            'cameraShelves', 'patchPaneles', 'airPatchPaneles', 'patchCords'));
    }

    /**
     * View Edit page.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function cabinet($id)
    {
        $internetSystem = InternetSystem::findOrFail($id);
        $internetSystemTypes = InternetSystemCommunityType::where('internet_system_id', $id)
            ->where("is_archived", 0)
            ->get();
        $internetCommunities = InternetSystemCommunity::where('internet_system_id', $id)->get();

        $routers = Router::all();
        $switchs = Switche::all();
        $cameraShelves = CameraShelve::all();
        $patchPaneles = PatchPanel::all();
        $airPatchPaneles = AirPatchPanel::all();
        $patchCords = PatchCord::all();
        $powerDistributors = PowerDistributor::all();
        $Keystones = Keystone::all();
        $nvrs = NvrCamera::all();
        $cabinets = NetworkCabinet::all();

        return view('system.internet.cabinet.edit', compact('routers', 'switchs', 'internetSystem', 'internetSystemTypes', 
            'Keystones', 'powerDistributors', 'cameraShelves', 'patchPaneles', 'airPatchPaneles', 'patchCords', 'nvrs',
            'cabinets'));
    }

    // This function is to update the internet unit & costs
    public function updateRouter($id, $units, $cost)
    {
        $router = RouterInternetSystem::findOrFail($id);
        $router->router_units = $units;
        $router->router_costs = $cost;
        $router->save();

        return response()->json(['success' => 1, 'msg' => 'Router updated successfully']);
    }

    // This function is to update the switch unit & costs
    public function updateSwitch($id, $units, $cost)
    {
        $switch = SwitchInternetSystem::findOrFail($id);
        $switch->switch_units = $units;
        $switch->switch_costs = $cost;
        $switch->save();

        return response()->json(['success' => 1, 'msg' => 'Switch updated successfully']);
    }

    // This function is to update the controller unit & costs
    public function updateController($id, $units, $cost)
    {
        $controller = ControllerInternetSystem::findOrFail($id);
        $controller->controller_units = $units;
        $controller->controller_costs = $cost;
        $controller->save();

        return response()->json(['success' => 1, 'msg' => 'Controller updated successfully']);
    }

    // This function is to update the ap unit & costs
    public function updateAp($id, $units, $cost)
    {
        $ap = ApInternetSystem::findOrFail($id);
        $ap->ap_units = $units;
        $ap->ap_costs = $cost;
        $ap->save();

        return response()->json(['success' => 1, 'msg' => 'AP updated successfully']);
    }

    // This function is to update the apLite unit & costs
    public function updateApLite($id, $units, $cost)
    {
        $ap = ApLiteInternetSystem::findOrFail($id);
        $ap->ap_lite_units = $units;
        $ap->ap_lite_costs = $cost;
        $ap->save();

        return response()->json(['success' => 1, 'msg' => 'AP Lite updated successfully']);
    }

    // This function is to update the ptp unit & costs
    public function updatePtp($id, $units, $cost)
    {
        $ptp = PtpInternetSystem::findOrFail($id);
        $ptp->ptp_units = $units;
        $ptp->ptp_costs = $cost;
        $ptp->save();

        return response()->json(['success' => 1, 'msg' => 'PTP updated successfully']);
    }

    // This function is to update the UISP unit & costs
    public function updateUisp($id, $units, $cost)
    {
        $uisp = UispInternetSystem::findOrFail($id);
        $uisp->uisp_units = $units;
        $uisp->uisp_costs = $cost;
        $uisp->save();

        return response()->json(['success' => 1, 'msg' => 'UISP updated successfully']);
    }

    // This function is to update the Connector unit & costs
    public function updateInternetConnector($id, $units, $cost)
    {
        $connector = ConnectorInternetSystem::findOrFail($id);
        $connector->connector_units = $units;
        $connector->connector_costs = $cost;
        $connector->save();

        return response()->json(['success' => 1, 'msg' => 'Connector updated successfully']);
    }

    // This function is to update the Electrician unit & costs
    public function updateElectrician($id, $units, $cost)
    {
        $electrician = ElectricianInternetSystem::findOrFail($id);
        $electrician->electrician_units = $units;
        $electrician->electrician_costs = $cost;
        $electrician->save();

        return response()->json(['success' => 1, 'msg' => 'Electrician updated successfully']);
    }

    // This function is to update the Electrician unit & costs
    public function updateInternetSystemCabinetComponent(Request $request, $id)
    {
        $request->validate([
            'units' => 'required|numeric|min:0',
            'cost' => 'required|numeric|min:0',
        ]);

        $networkComponent = NetworkCabinetComponent::findOrFail($id);
        $networkComponent->unit = $request->units;
        $networkComponent->cost = $request->cost;
        $networkComponent->save();

        return response()->json(['success' => 1, 'msg' => 'Component updated successfully']);
    }

    /**
     * Update an existing resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request, int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $internetSystem = InternetSystem::findOrFail($id);

        $internetSystem->system_name = $request->system_name;
        $internetSystem->start_year = $request->start_year;
        $internetSystem->notes = $request->notes;
        $internetSystem->save();

        if($request->new_internet_types) {
            for($i=0; $i < count($request->new_internet_types); $i++) {

                $internetSystemType = new InternetSystemCommunityType();
                $internetSystemType->internet_system_type_id = $request->new_internet_types[$i];
                $internetSystemType->internet_system_id = $internetSystem->id;
                $internetSystemType->save();
            }
        }

        // Router 
        if ($request->router_ids) {
            for ($cnq = 0; $cnq < count($request->router_ids); $cnq++) {

                $internetRouter = new RouterInternetSystem();
                $internetRouter->router_id = $request->router_ids[$cnq];
                $internetRouter->internet_system_id = $id;
                $internetRouter->router_units = $request->input("router_units.$cnq.subject") ?? 0;
                $internetRouter->router_costs = $request->input("router_costs.$cnq.subject") ?? 0;
        
                $internetRouter->save();
            }
        }

        // Switch 
        if ($request->switch_ids) {
            for ($sw = 0; $sw < count($request->switch_ids); $sw++) {

                $internetSwitch = new SwitchInternetSystem();
                $internetSwitch->switch_id = $request->switch_ids[$sw];
                $internetSwitch->internet_system_id = $id;
                $internetSwitch->switch_units = $request->input("switch_units.$sw.subject") ?? 0;
                $internetSwitch->switch_costs = $request->input("switch_costs.$sw.subject") ?? 0;
        
                $internetSwitch->save();
            }
        }

        // Controller
        if ($request->controller_ids) {
            for ($contr = 0; $contr < count($request->controller_ids); $contr++) {

                $internetController = new ControllerInternetSystem();
                $internetController->internet_controller_id = $request->controller_ids[$contr];
                $internetController->internet_system_id = $id;
                $internetController->controller_units = $request->input("controller_units.$contr.subject") ?? 0;
                $internetController->controller_costs = $request->input("controller_costs.$contr.subject") ?? 0;
        
                $internetController->save();
            }
        }

        // AP
        if ($request->ap_ids) {
            for ($aps = 0; $aps < count($request->ap_ids); $aps++) {

                $internetAp = new ApInternetSystem();
                $internetAp->internet_ap_id = $request->ap_ids[$aps];
                $internetAp->internet_system_id = $id;
                $internetAp->ap_units = $request->input("ap_units.$aps.subject") ?? 0;
                $internetAp->ap_costs = $request->input("ap_costs.$aps.subject") ?? 0;
        
                $internetAp->save();
            }
        }

        // AP Lite
        if ($request->ap_lite_ids) {
            for ($apl = 0; $apl < count($request->ap_lite_ids); $apl++) {

                $internetApLite = new ApLiteInternetSystem();
                $internetApLite->internet_ap_id = $request->ap_lite_ids[$apl];
                $internetApLite->internet_system_id = $id;
                $internetApLite->ap_lite_units = $request->input("ap_lite_units.$apl.subject") ?? 0;
                $internetApLite->ap_lite_costs = $request->input("ap_lite_costs.$apl.subject") ?? 0;
        
                $internetApLite->save();
            }
        }

        // PTP
        if ($request->ptp_id) {
            for ($pt = 0; $pt < count($request->ptp_id); $pt++) {

                $internetPtp = new PtpInternetSystem();
                $internetPtp->internet_ptp_id = $request->ptp_id[$pt];
                $internetPtp->internet_system_id = $id;
                $internetPtp->ptp_units = $request->input("ptp_units.$pt.subject") ?? 0;
                $internetPtp->ptp_costs = $request->input("ptp_costs.$pt.subject") ?? 0;
        
                $internetPtp->save();
            }
        }

        // UISP
        if ($request->uisp_ids) {
            for ($iuis = 0; $iuis < count($request->uisp_ids); $iuis++) {

                $internetUisp = new UispInternetSystem();
                $internetUisp->internet_uisp_id = $request->uisp_ids[$iuis];
                $internetUisp->internet_system_id = $id;
                $internetUisp->uisp_units = $request->input("uisp_units.$iuis.subject") ?? 0;
                $internetUisp->uisp_costs = $request->input("uisp_costs.$iuis.subject") ?? 0;
        
                $internetUisp->save();
            }
        }

        // Connector
        if ($request->connector_ids) {
            for ($conntr = 0; $conntr < count($request->connector_ids); $conntr++) {

                $internetConnector = new ConnectorInternetSystem();
                $internetConnector->internet_connector_id = $request->connector_ids[$conntr];
                $internetConnector->internet_system_id = $id;
                $internetConnector->connector_units = $request->input("connector_units.$conntr.subject") ?? 0;
                $internetConnector->connector_costs = $request->input("connector_costs.$conntr.subject") ?? 0;
        
                $internetConnector->save();
            }
        }
        
        // Electrician
        if ($request->electrician_ids) {
            for ($elctra = 0; $elctra < count($request->electrician_ids); $elctra++) {

                $internetElectrician = new ElectricianInternetSystem();
                $internetElectrician->internet_electrician_id = $request->electrician_ids[$elctra];
                $internetElectrician->internet_system_id = $id;
                $internetElectrician->electrician_units = $request->input("electrician_units.$elctra.subject") ?? 0;
                $internetElectrician->electrician_costs = $request->input("electrician_costs.$elctra.subject") ?? 0;
        
                $internetElectrician->save();
            }
        }


        return redirect('/internet-system')->with('message', 'Internet System Updated Successfully!');
    }

    /**
     * Show the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function showPage($id)
    {
        $internetSystem = InternetSystem::findOrFail($id);

        return response()->json($internetSystem);
    }
    
    /**
     * Show the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $internetSystem = InternetSystem::findOrFail($id);
        $internetSystemTypes = InternetSystemCommunityType::where('internet_system_id', $id)
            ->where('is_archived', 0)
            ->get();
        $internetCommunities = InternetSystemCommunity::where('internet_system_id', $id)
            ->where('is_archived', 0)
            ->get();
     
        foreach($internetCommunities as $internetCommunity) {
            
            $lineOfSightMainCommunities = LineOfSight::where("main_community_id", $internetCommunity->community_id)->get();
            $lineOfSightSubCommunities = LineOfSight::where("sub_community_id", $internetCommunity->community_id)->get();
        }
 
        // Router
        $routers = DB::table('router_internet_systems')
            ->join('internet_systems', 'router_internet_systems.internet_system_id', 
                '=', 'internet_systems.id')
            ->join('routers', 'router_internet_systems.router_id', 
                '=', 'routers.id')
            ->where('router_internet_systems.internet_system_id', '=', $id)
            ->select('router_internet_systems.router_units', 'routers.model',  
                'routers.brand_name', 'internet_systems.system_name',
                'router_internet_systems.router_costs')
            ->get(); 

        // Switch
        $switches = DB::table('switch_internet_systems')
            ->join('internet_systems', 'switch_internet_systems.internet_system_id', 
                '=', 'internet_systems.id')
            ->join('switches', 'switch_internet_systems.switch_id', 
                '=', 'switches.id')
            ->where('switch_internet_systems.internet_system_id', '=', $id)
            ->select('switch_internet_systems.switch_units', 'switches.model', 
                'switches.brand_name', 'internet_systems.system_name',
                'switch_internet_systems.switch_costs')
            ->get(); 

        // Controller
        $controllers = DB::table('controller_internet_systems')
            ->join('internet_systems', 'controller_internet_systems.internet_system_id', 
                '=', 'internet_systems.id')
            ->join('internet_controllers', 'controller_internet_systems.internet_controller_id', 
                '=', 'internet_controllers.id')
            ->where('controller_internet_systems.internet_system_id', '=', $id)
            ->select('controller_internet_systems.controller_units', 'internet_controllers.model', 
                'internet_controllers.brand', 'internet_systems.system_name',
                'controller_internet_systems.controller_costs')
            ->get();

        // PTP 
        $ptps = DB::table('ptp_internet_systems')
            ->join('internet_systems', 'ptp_internet_systems.internet_system_id', 
                '=', 'internet_systems.id')
            ->join('internet_ptps', 'ptp_internet_systems.internet_ptp_id', 
                '=', 'internet_ptps.id')
            ->where('ptp_internet_systems.internet_system_id', '=', $id)
            ->select('ptp_internet_systems.ptp_units', 'internet_ptps.model', 
                'internet_ptps.brand', 'internet_systems.system_name',
                'ptp_internet_systems.ptp_costs')
            ->get();

        // AP
        $aps = DB::table('ap_internet_systems')
            ->join('internet_systems', 'ap_internet_systems.internet_system_id', 
                '=', 'internet_systems.id')
            ->join('internet_aps', 'ap_internet_systems.internet_ap_id', 
                '=', 'internet_aps.id')
            ->where('ap_internet_systems.internet_system_id', '=', $id)
            ->select('ap_internet_systems.ap_units', 'internet_aps.model', 
                'internet_aps.brand', 'internet_systems.system_name',
                'ap_internet_systems.ap_costs')
            ->get();

        // AP Lite
        $apLites = DB::table('ap_lite_internet_systems')
            ->join('internet_systems', 'ap_lite_internet_systems.internet_system_id', 
                '=', 'internet_systems.id')
            ->join('internet_aps', 'ap_lite_internet_systems.internet_ap_id', 
                '=', 'internet_aps.id')
            ->where('ap_lite_internet_systems.internet_system_id', '=', $id)
            ->select('ap_lite_internet_systems.ap_lite_units', 'internet_aps.model', 
                'internet_aps.brand', 'internet_systems.system_name',
                'ap_lite_internet_systems.ap_lite_costs')
            ->get();

        // UISP
        $uisps = DB::table('uisp_internet_systems')
            ->join('internet_systems', 'uisp_internet_systems.internet_system_id', 
                '=', 'internet_systems.id')
            ->join('internet_uisps', 'uisp_internet_systems.internet_uisp_id', 
                '=', 'internet_uisps.id')
            ->where('uisp_internet_systems.internet_system_id', '=', $id)
            ->select('uisp_internet_systems.uisp_units', 'internet_uisps.model', 
                'internet_uisps.brand', 'internet_systems.system_name',
                'uisp_internet_systems.uisp_costs')
            ->get();

        // Electrician
        $electricians = DB::table('electrician_internet_systems')
            ->join('internet_systems', 'electrician_internet_systems.internet_system_id', 
                'internet_systems.id')
            ->join('internet_electricians', 'electrician_internet_systems.internet_electrician_id', 
                'internet_electricians.id')
            ->where('electrician_internet_systems.internet_system_id', $id)
            ->select('electrician_internet_systems.electrician_units', 'internet_electricians.model', 
                'internet_electricians.brand', 'internet_systems.system_name',
                'electrician_internet_systems.id', 'electrician_internet_systems.electrician_costs')
            ->get();

        // Connector
        $connectors = DB::table('connector_internet_systems')
            ->join('internet_systems', 'connector_internet_systems.internet_system_id', 
                'internet_systems.id')
            ->join('internet_connectors', 'connector_internet_systems.internet_connector_id', 
                'internet_connectors.id')
            ->where('connector_internet_systems.internet_system_id', $id)
            ->select('connector_internet_systems.connector_units', 'internet_connectors.model', 
                'internet_connectors.brand', 'internet_systems.system_name',
                'connector_internet_systems.id', 'connector_internet_systems.connector_costs')
            ->get();

        return view('system.internet.show', compact('routers', 'switches', 'controllers',
            'ptps', 'aps', 'apLites', 'uisps', 'internetSystem', 'internetSystemTypes', 
            'lineOfSightMainCommunities', 'lineOfSightSubCommunities', 'electricians',
            'connectors'));
    }

    /**
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteInternetSystem(Request $request)
    {
        $id = $request->id;

        $internetSystem = InternetSystem::find($id);
       // $internetSystemCommunity = InternetSystemCommunity::where("", $id)->first();

        if($internetSystem->delete()) {

           // if($internetSystemCommunity) $internetSystemCommunity->delete();
            $response['success'] = 1;
            $response['msg'] = 'Internet System Deleted successfully'; 
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
    public function deleteInternetSystemType(Request $request)
    {
        $id = $request->id;

        $internetSystemType = InternetSystemCommunityType::findOrFail($id);

        if($internetSystemType) {

            $internetSystemType->is_archived = 1;
            $internetSystemType->save();

            $response['success'] = 1;
            $response['msg'] = 'Internet System Type Deleted successfully'; 
        } else {

            $response['success'] = 0;
            $response['msg'] = 'Invalid ID.';
        }

        return response()->json($response); 
    }

    /**
     * Delete internet system router.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteInternetSystemRouter(Request $request)
    {
        $id = $request->id;

        $internetSystemRouter = RouterInternetSystem::find($id);

        if($internetSystemRouter->delete()) {

            $response['success'] = 1;
            $response['msg'] = 'Internet System Router Deleted successfully'; 
        } else {

            $response['success'] = 0;
            $response['msg'] = 'Invalid ID.';
        }

        return response()->json($response); 
    }

    /**
     * Delete internet system switch.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteInternetSystemSwitch(Request $request)
    {
        $id = $request->id;

        $internetSystemSwitch = SwitchInternetSystem::find($id);

        if($internetSystemSwitch->delete()) {

            $response['success'] = 1;
            $response['msg'] = 'Internet System Switch Deleted successfully'; 
        } else {

            $response['success'] = 0;
            $response['msg'] = 'Invalid ID.';
        }

        return response()->json($response); 
    }

     /**
     * Delete internet system controller.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteInternetSystemController(Request $request)
    {
        $id = $request->id;

        $internetSystemController = ControllerInternetSystem::find($id);

        if($internetSystemController->delete()) {

            $response['success'] = 1;
            $response['msg'] = 'Internet System Controller Deleted successfully'; 
        } else {

            $response['success'] = 0;
            $response['msg'] = 'Invalid ID.';
        }

        return response()->json($response); 
    }

     /**
     * Delete internet system ap.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteInternetSystemAp(Request $request)
    {
        $id = $request->id;

        $internetSystemAp = ApInternetSystem::find($id);

        if($internetSystemAp->delete()) {

            $response['success'] = 1;
            $response['msg'] = 'Internet System Ap Deleted successfully'; 
        } else {

            $response['success'] = 0;
            $response['msg'] = 'Invalid ID.';
        }

        return response()->json($response); 
    }

     /**
     * Delete internet system ap lite.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteInternetSystemApLite(Request $request)
    {
        $id = $request->id;

        $internetSystemApLite = ApLiteInternetSystem::find($id);

        if($internetSystemApLite->delete()) {

            $response['success'] = 1;
            $response['msg'] = 'Internet System Ap Lite Deleted successfully'; 
        } else {

            $response['success'] = 0;
            $response['msg'] = 'Invalid ID.';
        }

        return response()->json($response); 
    }

    /**
     * Delete internet system ptp.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteInternetSystemPtp(Request $request)
    {
        $id = $request->id;

        $internetSystemPtp = PtpInternetSystem::find($id);

        if($internetSystemPtp->delete()) {

            $response['success'] = 1;
            $response['msg'] = 'Internet System PTP Deleted successfully'; 
        } else {

            $response['success'] = 0;
            $response['msg'] = 'Invalid ID.';
        }

        return response()->json($response); 
    }

    /**
     * Delete internet system UISP.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteInternetSystemUisp(Request $request)
    {
        $id = $request->id;

        $internetSystemUisp = UispInternetSystem::find($id);

        if($internetSystemUisp->delete()) {

            $response['success'] = 1;
            $response['msg'] = 'Internet System UISP Deleted successfully'; 
        } else {

            $response['success'] = 0;
            $response['msg'] = 'Invalid ID.';
        }

        return response()->json($response); 
    }

    /**
     * Delete internet system Connector.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteInternetSystemConnector(Request $request)
    {
        $id = $request->id;

        $internetSystemConnector = ConnectorInternetSystem::find($id);

        if($internetSystemConnector->delete()) {

            $response['success'] = 1;
            $response['msg'] = 'Internet System Connector Deleted successfully'; 
        } else {

            $response['success'] = 0;
            $response['msg'] = 'Invalid ID.';
        }

        return response()->json($response); 
    }

    /**
     * Delete internet system Electrician.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteInternetSystemElectrician(Request $request)
    {
        $id = $request->id;

        $internetSystemElectrician = ElectricianInternetSystem::find($id);

        if($internetSystemElectrician->delete()) {

            $response['success'] = 1;
            $response['msg'] = 'Internet System Electrician Deleted successfully'; 
        } else {

            $response['success'] = 0;
            $response['msg'] = 'Invalid ID.';
        }

        return response()->json($response); 
    }
}