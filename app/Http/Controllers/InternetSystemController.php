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
                    ->join('communities', 'internet_system_communities.community_id', 
                        '=', 'communities.id')
                    ->join('internet_systems', 'internet_system_communities.internet_system_id', 
                        '=', 'internet_systems.id')
                    ->join('internet_system_types', 'internet_systems.internet_system_type_id', 
                        '=', 'internet_system_types.id')
                    ->select('internet_system_types.name', 'internet_systems.start_year', 
                        'internet_system_types.upgrade_year', 'internet_systems.system_name',
                        'internet_systems.id as id',
                        'internet_system_communities.created_at as created_at', 
                        'internet_system_communities.updated_at as updated_at', 
                        'communities.english_name as community_name')
                    ->latest(); 
    
                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
                        $viewButton = "<a type='button' class='viewInternetSystem' data-id='".$row->id."' ><i class='fa-solid fa-eye text-info'></i></a>";
                        $updateButton = "<a type='button' class='updateInternetSystem' data-id='".$row->id."' ><i class='fa-solid fa-pen-to-square text-success'></i></a>";
                        $deleteButton = "<a type='button' class='deleteInternetSystem' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";
                        
                        if(Auth::guard('user')->user()->user_type_id == 1 || 
                            Auth::guard('user')->user()->user_type_id == 2 ||
                            Auth::guard('user')->user()->user_type_id == 6 ||
                            Auth::guard('user')->user()->user_type_id == 10) 
                        {
                                
                            return $viewButton." ". $updateButton." ".$deleteButton;
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
        $communities = Community::where("is_archived", 0)->get();
        $controllers = InternetController::all();
        $internetSystemTypes = InternetSystemType::all();
        $routers = Router::all();
        $switches = Switche::all();
        $ptps = InternetPtp::all();
        $uisps = InternetUisp::all();

        return view('system.internet.create', compact('aps', 'communities', 'controllers',
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

        $internetSystem = new InternetSystem();
        $internetSystem->internet_system_type_id = $request->internet_system_type_id;
        $internetSystem->system_name = $request->system_name;
        $internetSystem->start_year = $request->start_year;
        $internetSystem->notes = $request->notes;
        $internetSystem->save();

        $internetSystemCommunity = new InternetSystemCommunity();
        $internetSystemCommunity->community_id = $request->community_id;
        $internetSystemCommunity->internet_system_id = $internetSystem->id;
        $internetSystemCommunity->save();

        // $community = Community::findOrFail($request->community_id);
        // $community->internet_service = "Yes";
        // if($community->internet_service_beginning_year == Null) {
        //     $community->internet_service_beginning_year = $request->start_year;
        // }
        // $community->save();


        // Router
        if($request->router_id) {
            for($i=0; $i < count($request->router_id); $i++) {

                $routerInternetSystem = new RouterInternetSystem();
                $routerInternetSystem->router_id = $request->router_id[$i];
                $routerInternetSystem->router_units = $request->router_units[$i]["subject"];
                $routerInternetSystem->internet_system_id = $internetSystem->id;
                $routerInternetSystem->save();
            }
        }

        // Switch
        if($request->switch_id) {
            for($i=0; $i < count($request->switch_id); $i++) {

                $switchInternetSystem = new SwitchInternetSystem();
                $switchInternetSystem->switch_id = $request->switch_id[$i];
                $switchInternetSystem->switch_units = $request->switch_units[$i]["subject"];
                $switchInternetSystem->internet_system_id = $internetSystem->id;
                $switchInternetSystem->save();
            }
        }

        // Controller
        if($request->controller_id) {
            for($i=0; $i < count($request->controller_id); $i++) {

                $switchInternetSystem = new ControllerInternetSystem();
                $switchInternetSystem->internet_controller_id = $request->controller_id[$i];
                $switchInternetSystem->controller_units = $request->controller_units[$i]["subject"];
                $switchInternetSystem->internet_system_id = $internetSystem->id;
                $switchInternetSystem->save();
            }
        }

        // AP
        if($request->ap_units) {
            if($request->ap_id) {
                for($i=0; $i < count($request->ap_id); $i++) {
    
                    $switchInternetSystem = new ApInternetSystem();
                    $switchInternetSystem->internet_ap_id = $request->ap_id[$i];
                    $switchInternetSystem->ap_units = $request->ap_units[$i]["subject"];
                    $switchInternetSystem->internet_system_id = $internetSystem->id;
                    $switchInternetSystem->save();
                }
            }
        }

        // AP Lite
        if($request->ap_lite_units) {
            if($request->ap_lite_id) {
                for($i=0; $i < count($request->ap_lite_id); $i++) {

                    $switchInternetSystem = new ApLiteInternetSystem();
                    $switchInternetSystem->internet_ap_id = $request->ap_lite_id[$i];
                    $switchInternetSystem->ap_lite_units = $request->ap_lite_units[$i]["subject"];
                    $switchInternetSystem->internet_system_id = $internetSystem->id;
                    $switchInternetSystem->save();
                }
            }
        }

        // PTP
        if($request->ptp_id) {
            for($i=0; $i < count($request->ptp_id); $i++) {

                $ptpInternetSystem = new PtpInternetSystem();
                $ptpInternetSystem->internet_ptp_id = $request->ptp_id[$i];
                $ptpInternetSystem->ptp_units = $request->ptp_units[$i]["subject"];
                $ptpInternetSystem->internet_system_id = $internetSystem->id;
                $ptpInternetSystem->save();
            }
        }

        // UISP
        if($request->uisp_id) {
            for($i=0; $i < count($request->uisp_id); $i++) {

                $uispInternetSystem = new UispInternetSystem();
                $uispInternetSystem->internet_uisp_id = $request->uisp_id[$i];
                $uispInternetSystem->uisp_units = $request->uisp_units[$i]["subject"];
                $uispInternetSystem->internet_system_id = $internetSystem->id;
                $uispInternetSystem->save();
            }
        }

        return redirect('/internet-system')
            ->with('message', 'New Internet System Added Successfully!');
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
        $internetSystemType = InternetSystemType::where('id', 
            $internetSystem->internet_system_type_id)
            ->first();

        $internetCommunities = InternetSystemCommunity::where('internet_system_id', $id)->get();
     
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
                'routers.brand_name', 'internet_systems.system_name')
            ->get(); 

        // Switch
        $switches = DB::table('switch_internet_systems')
            ->join('internet_systems', 'switch_internet_systems.internet_system_id', 
                '=', 'internet_systems.id')
            ->join('switches', 'switch_internet_systems.switch_id', 
                '=', 'switches.id')
            ->where('switch_internet_systems.internet_system_id', '=', $id)
            ->select('switch_internet_systems.switch_units', 'switches.model', 
                'switches.brand_name', 'internet_systems.system_name')
            ->get(); 

        // Controller
        $controllers = DB::table('controller_internet_systems')
            ->join('internet_systems', 'controller_internet_systems.internet_system_id', 
                '=', 'internet_systems.id')
            ->join('internet_controllers', 'controller_internet_systems.internet_controller_id', 
                '=', 'internet_controllers.id')
            ->where('controller_internet_systems.internet_system_id', '=', $id)
            ->select('controller_internet_systems.controller_units', 'internet_controllers.model', 
                'internet_controllers.brand', 'internet_systems.system_name')
            ->get();

        // PTP 
        $ptps = DB::table('ptp_internet_systems')
            ->join('internet_systems', 'ptp_internet_systems.internet_system_id', 
                '=', 'internet_systems.id')
            ->join('internet_ptps', 'ptp_internet_systems.internet_ptp_id', 
                '=', 'internet_ptps.id')
            ->where('ptp_internet_systems.internet_system_id', '=', $id)
            ->select('ptp_internet_systems.ptp_units', 'internet_ptps.model', 
                'internet_ptps.brand', 'internet_systems.system_name')
            ->get();

        // AP
        $aps = DB::table('ap_internet_systems')
            ->join('internet_systems', 'ap_internet_systems.internet_system_id', 
                '=', 'internet_systems.id')
            ->join('internet_aps', 'ap_internet_systems.internet_ap_id', 
                '=', 'internet_aps.id')
            ->where('ap_internet_systems.internet_system_id', '=', $id)
            ->select('ap_internet_systems.ap_units', 'internet_aps.model', 
                'internet_aps.brand', 'internet_systems.system_name')
            ->get();

        // AP Lite
        $apLites = DB::table('ap_lite_internet_systems')
            ->join('internet_systems', 'ap_lite_internet_systems.internet_system_id', 
                '=', 'internet_systems.id')
            ->join('internet_aps', 'ap_lite_internet_systems.internet_ap_id', 
                '=', 'internet_aps.id')
            ->where('ap_lite_internet_systems.internet_system_id', '=', $id)
            ->select('ap_lite_internet_systems.ap_lite_units', 'internet_aps.model', 
                'internet_aps.brand', 'internet_systems.system_name')
            ->get();

        // UISP
        $uisps = DB::table('uisp_internet_systems')
            ->join('internet_systems', 'uisp_internet_systems.internet_system_id', 
                '=', 'internet_systems.id')
            ->join('internet_uisps', 'uisp_internet_systems.internet_uisp_id', 
                '=', 'internet_uisps.id')
            ->where('uisp_internet_systems.internet_system_id', '=', $id)
            ->select('uisp_internet_systems.uisp_units', 'internet_uisps.model', 
                'internet_uisps.brand', 'internet_systems.system_name')
            ->get();

        return view('system.internet.show', compact('routers', 'switches', 'controllers',
            'ptps', 'aps', 'apLites', 'uisps', 'internetSystem', 'internetSystemType', 
            'lineOfSightMainCommunities', 'lineOfSightSubCommunities'));
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
}