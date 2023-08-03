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

        return view('system.internet.component.create', compact('aps', 'communities', 'controllers',
            'internetSystemTypes', 'routers', 'switches', 'ptps', 'uisps'));
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
                $newRouter->brand_name = $request->router_brands[$i]["subject"];
                $newRouter->save();
            }
        }

        // Switch
        if($request->switch_brands[0]["subject"] != null) {
            for($i=0; $i < count($request->switch_brands); $i++) {

                $newSwitch = new Switche();
                $newSwitch->model = $request->switch_models[$i]["subject"];
                $newSwitch->brand_name = $request->switch_brands[$i]["subject"];
                $newSwitch->save();
            }
        }

        // Controller
        if($request->controller_models[0]["subject"] != null) {
            for($i=0; $i < count($request->controller_brands); $i++) {

                $newController = new InternetController();
                $newController->model = $request->controller_models[$i]["subject"];
                $newController->brand = $request->controller_brands[$i]["subject"];
                $newController->save();
            }
        }

        // AP
        if($request->ap_models[0]["subject"] != null) {
            for($i=0; $i < count($request->ap_models); $i++) {

                $newAp = new InternetAp();
                $newAp->model = $request->ap_models[$i]["subject"];
                $newAp->brand = $request->ap_brands[$i]["subject"];
                $newAp->save();
            }
        }

        // AP Lite


        // PTP
        if($request->ptp_models[0]["subject"] != null) {
            for($i=0; $i < count($request->ptp_models); $i++) {

                $newPtp = new InternetPtp();
                $newPtp->model = $request->ptp_models[$i]["subject"];
                $newPtp->brand = $request->ptp_brands[$i]["subject"];
                $newPtp->save();
            }
        }

        // UISP
        if($request->uisp_models[0]["subject"] != null) {
            for($i=0; $i < count($request->uisp_models); $i++) {

                $newUisp = new InternetUisp();
                $newUisp->model = $request->uisp_models[$i]["subject"];
                $newUisp->brand = $request->uisp_brands[$i]["subject"];
                $newUisp->save();
            }
        }

        return redirect('/internet-system')
            ->with('message', 'New Internet Components Added Successfully!');
    }
}
