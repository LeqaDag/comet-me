<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\AllWaterHolder;
use App\Models\User;
use App\Models\BsfStatus;
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
use App\Models\WaterSystemConnector;
use App\Models\WaterSystemElectrical;
use App\Models\WaterSystemFilter;
use App\Models\WaterSystemPipe;
use App\Models\WaterSystemPump;
use App\Models\WaterSystemTank;
use App\Models\WaterTank;
use App\Models\WaterUser;
use App\Models\WaterSystem;
use App\Models\WaterSystemType;
use App\Models\H2oSystemIncident;
use App\Models\Incident;
use App\Models\IncidentStatus;
use Auth;
use DB;
use Route;
use DataTables;

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
    
                        $viewButton = "<a type='button' class='viewWaterSystem' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#viewWaterSystemModal' ><i class='fa-solid fa-eye text-info'></i></a>";
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
                ->rawColumns(['action'])
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

        return view('system.water.create', compact('waterSystemTypes', 'communities'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {       
        $waterSystem = new WaterSystem();
        $waterSystem->water_system_type_id = $request->water_system_type_id;
        $waterSystem->name = $request->name;
        $waterSystem->description = $request->description;
        $waterSystem->year = $request->year;
        $waterSystem->notes = $request->notes;
        $waterSystem->save();

        if($request->community_id && $request->water_system_type_id == 4) {

            $waterSystem->community_id = $request->community_id;
            $waterSystem->save();

            $households = Household::where("community_id", $request->community_id)
                ->orderBy('english_name', 'ASC')
                ->get();

            foreach($households as $household) {

                $household->water_service = "Yes";
                $household->water_system_status = "Served";
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
     * View Edit page.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $connectors = WaterConnector::all();
        $electricals = WaterElectrical::all(); 
        $filters = WaterFilter::all();
        $pipes = WaterPipe::all();
        $pumps = WaterPump::all();
        $tanks = WaterTank::all();

        $waterConnectors = WaterSystemConnector::where('water_system_id', $id)->get();
       // $waterElectricals= WaterSystemElectrical::where('water_system_id', $id)->get();
        $waterFilters = WaterSystemFilter::where('water_system_id', $id)->get();
        $waterPipes = WaterSystemPipe::where('water_system_id', $id)->get();
        $waterPumps = WaterSystemPump::where('water_system_id', $id)->get();
        $waterTanks = WaterSystemTank::where('water_system_id', $id)->get();
        $waterSystem = WaterSystem::findOrFail($id);
        
        return view('system.water.edit', compact('connectors', 'electricals', 'filters',
            'pipes', 'pumps', 'tanks', 'waterConnectors', 'waterSystem',
            'waterFilters', 'waterPipes', 'waterPumps', 'waterTanks'));
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

        if($waterSystem->delete()) {

            $response['success'] = 1;
            $response['msg'] = 'Water System Deleted successfully'; 
        } else {

            $response['success'] = 0;
            $response['msg'] = 'Invalid ID.';
        }

        return response()->json($response); 
    }
}
