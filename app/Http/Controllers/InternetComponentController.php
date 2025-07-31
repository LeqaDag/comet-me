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
use App\Models\LineOfSight;
use App\Models\InternetElectrician;
use App\Models\InternetConnector;
use App\Models\CameraShelve; 
use App\Models\NetworkCabinet; 
use App\Models\AirPatchPanel; 
use App\Models\PatchPanel; 
use App\Models\PatchCord; 
use App\Models\PowerDistributor; 
use App\Models\Keystone; 
use App\Models\NetworkCabinetInternetSystem;
use App\Models\NetworkCabinetComponent;
use Carbon\Carbon;
use Image;
use DataTables;

class InternetComponentController extends Controller
{
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

        return view('system.internet.component.create', compact('aps', 'communities', 'controllers',
            'internetSystemTypes', 'routers', 'switches', 'ptps', 'uisps', 'electricians'));
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

        // Router
        if($request->router_brands[0]["subject"] != null) {
            for($i=0; $i < count($request->router_brands); $i++) {

                $newRouter = new Router();
                $newRouter->model = $request->router_models[$i]["subject"];
                $newRouter->brand_name = $request->router_brands[$i]["subject"] ?? null;
                $newRouter->save();
            }
        }

        // Switch
        if($request->switch_brands[0]["subject"] != null) {
            for($i=0; $i < count($request->switch_brands); $i++) {

                $newSwitch = new Switche();
                $newSwitch->model = $request->switch_models[$i]["subject"];
                $newSwitch->brand_name = $request->switch_brands[$i]["subject"] ?? null;
                $newSwitch->save();
            }
        }

        // Controller
        if($request->controller_models[0]["subject"] != null) {
            for($i=0; $i < count($request->controller_brands); $i++) {

                $newController = new InternetController();
                $newController->model = $request->controller_models[$i]["subject"];
                $newController->brand = $request->controller_brands[$i]["subject"] ?? null;
                $newController->save();
            }
        }

        // AP
        if($request->ap_models[0]["subject"] != null) {
            for($i=0; $i < count($request->ap_models); $i++) {

                $newAp = new InternetAp();
                $newAp->model = $request->ap_models[$i]["subject"];
                $newAp->brand = $request->ap_brands[$i]["subject"] ?? null;
                $newAp->save();
            }
        }

        // AP Lite


        // PTP
        if($request->ptp_models[0]["subject"] != null) {
            for($i=0; $i < count($request->ptp_models); $i++) {

                $newPtp = new InternetPtp();
                $newPtp->model = $request->ptp_models[$i]["subject"];
                $newPtp->brand = $request->ptp_brands[$i]["subject"] ?? null;
                $newPtp->save();
            }
        }

        // UISP
        if($request->uisp_models[0]["subject"] != null) {
            for($i=0; $i < count($request->uisp_models); $i++) {

                $newUisp = new InternetUisp();
                $newUisp->model = $request->uisp_models[$i]["subject"];
                $newUisp->brand = $request->uisp_brands[$i]["subject"] ?? null;
                $newUisp->save();
            }
        }

        // Electrician
        if($request->electrician_models[0]["subject"] != null) {
            for($i=0; $i < count($request->electrician_models); $i++) {

                $newElectrician = new InternetElectrician();
                $newElectrician->model = $request->electrician_models[$i]["subject"];
                $newElectrician->brand = $request->electrician_brands[$i]["subject"] ?? null;
                $newElectrician->save();
            }
        }

        // Connector
        if($request->connector_models[0]["subject"] != null) {
            for($i=0; $i < count($request->connector_models); $i++) {

                $newConnector = new InternetConnector();
                $newConnector->model = $request->connector_models[$i]["subject"];
                $newConnector->brand = $request->connector_brands[$i]["subject"] ?? null;
                $newConnector->save();
            }
        }

        // NetwrokCabinet
        if($request->cabinet_models[0]["subject"] != null) {
            for($i=0; $i < count($request->cabinet_models); $i++) {

                $newCabinet = new NetworkCabinet();
                $newCabinet->model = $request->cabinet_models[$i]["subject"];
                $newCabinet->brand = $request->cabinet_brands[$i]["subject"] ?? null;
                $newCabinet->save();
            }
        }

        // PatchPanel
        if($request->patchpanel_models[0]["subject"] != null) {
            for($i=0; $i < count($request->patchpanel_models); $i++) {

                $patchPanel = new PatchPanel();
                $patchPanel->model = $request->patchpanel_models[$i]["subject"];
                $patchPanel->brand = $request->patchpanel_brands[$i]["subject"] ?? null;
                $patchPanel->save();
            }
        }

        // AirPatchPanel
        if($request->airpatchpanel_models[0]["subject"] != null) {
            for($air=0; $air < count($request->airpatchpanel_models); $air++) {

                $airPatchPanel = new AirPatchPanel();
                $airPatchPanel->model = $request->airpatchpanel_models[$air]["subject"];
                $airPatchPanel->brand = $request->airpatchpanel_brands[$air]["subject"] ?? null;
                $airPatchPanel->save();
            }
        }

        // CameraShelve
        if($request->camerashelve_models[0]["subject"] != null) {
            for($cmrsh=0; $cmrsh < count($request->camerashelve_models); $cmrsh++) {

                $cameraShelve = new CameraShelve();
                $cameraShelve->model = $request->camerashelve_models[$cmrsh]["subject"];
                $cameraShelve->brand = $request->camerashelve_brands[$cmrsh]["subject"] ?? null;
                $cameraShelve->save();
            }
        }

        // PatchCord
        if($request->patchcord_models[0]["subject"] != null) {
            for($ptchc=0; $ptchc < count($request->patchcord_models); $ptchc++) {

                $patchCord = new PatchCord();
                $patchCord->model = $request->patchcord_models[$ptchc]["subject"];
                $patchCord->brand = $request->patchcord_brands[$ptchc]["subject"] ?? null;
                $patchCord->save();
            }
        }

        // Keystone
        if($request->keystone_models[0]["subject"] != null) {
            for($kst=0; $kst < count($request->keystone_models); $kst++) {

                $keystone = new Keystone();
                $keystone->model = $request->keystone_models[$kst]["subject"];
                $keystone->brand = $request->keystone_brands[$kst]["subject"] ?? null;
                $keystone->save();
            }
        }

        // PowerDistributor
        if($request->powerdistributor_models[0]["subject"] != null) {
            for($pwod=0; $pwod < count($request->powerdistributor_models); $pwod++) {

                $powerDistributor = new PowerDistributor();
                $powerDistributor->model = $request->powerdistributor_models[$pwod]["subject"];
                $powerDistributor->brand = $request->powerdistributor_brands[$pwod]["subject"] ?? null;
                $powerDistributor->save();
            }
        }

        return redirect('/internet-system')
            ->with('message', 'New Internet Components Added Successfully!');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeComponents(Request $request)
    {
        $cabinetInternetSystem = NetworkCabinetInternetSystem::where("internet_system_id", $request->internet_system_id)
            ->first();
            
        if ($request->has('components')) {

            foreach ($request->input('components') as $cabinetId => $componentTypes) {

                foreach ($componentTypes as $componentType => $components) {

                    foreach ($components as $index => $newComponent) {

                        $component = new NetworkCabinetComponent();

                        $component->network_cabinet_internet_system_id = $cabinetInternetSystem->id;
                        $component->component_type = $componentType;
                        $component->component_id = $newComponent['component_id'] ?? null;
                        $component->unit = $newComponent['unit'] ?? 0;
                        $component->cost = $newComponent['cost'] ?? 0;
                        $component->save();
                    }
                }
            }
        }

        return redirect('/internet-system')->with('message', 'Components updated and/or added successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request, $id
     * @return \Illuminate\Http\Response
     */
    public function storeNetworkCabinet(Request $request, $internetSystemId)
    {
        $internetSystem = InternetSystem::findOrFail($internetSystemId);

        if($request->new_cabinets) {

            foreach ($request->new_cabinets as $cabinetData) {

                $cabinetId = $cabinetData['cabinet_id'];

                // Prevent duplicate insert
                $alreadyLinked = DB::table('network_cabinet_internet_systems')
                    ->where('internet_system_id', $internetSystemId)
                    ->where('network_cabinet_id', $cabinetId)
                    ->exists();

                if (!$alreadyLinked) {
                    DB::table('network_cabinet_internet_systems')->insert([
                        'internet_system_id' => $internetSystemId,
                        'network_cabinet_id' => $cabinetId,
                        'cost' => $cabinetData['cost']
                    ]);
                }
            }

            return response()->json([
                'success' => 1,
                'msg' => 'Cabinets added successfully.',
            ]);
        }
    }

    // This function is to update cabinet cost
    public function updateNetworkCabinetCost(Request $request,)
    {
        $networkCabinet = NetworkCabinetInternetSystem::where("internet_system_id", $request->internet_system_id)
            ->where("network_cabinet_id", $request->cabinet_id)
            ->first();
            
        if($networkCabinet) {

            $networkCabinet->cost = $request->cost;
            $networkCabinet->save();

            return response()->json(['success' => true]);
        } else {

            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Delete internet system Electrician.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $component = NetworkCabinetComponent::findOrFail($id);

        if (!$component) {

            return response()->json(['success' => false, 'msg' => 'Component not found.']);
        }

        $component->delete();

        return response()->json(['success' => true, 'msg' => 'Component deleted successfully.']);
    }

    /**
     * Delete internet system Cabinet.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteInternetSystemCabinet($internetSystemId, $cabinetId)
    {
        $networkCabinetInternetSystem = NetworkCabinetInternetSystem::where("internet_system_id", $internetSystemId)
            ->where("network_cabinet_id", $cabinetId)
            ->first();
 
        if($networkCabinetInternetSystem->delete()) {

            $response['success'] = 1;
            $response['msg'] = 'Internet Cabinet Deleted successfully'; 
        } else {

            $response['success'] = 0;
            $response['msg'] = 'Invalid ID.';
        }

        return response()->json($response); 
    }

}
