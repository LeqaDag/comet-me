<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\User;
use App\Models\BsfStatus;
use App\Models\Community;
use App\Models\CommunityWaterSource;
use App\Models\GridUser;
use App\Models\H2oSharedUser;
use App\Models\H2oStatus;
use App\Models\H2oUser;
use App\Models\H2oSystemIncident;
use App\Models\Incident;
use App\Models\IncidentStatus;
use App\Models\Household;
use App\Models\WaterUser;
use App\Models\WaterSystem;
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
        if ($request->ajax()) {

            $data = DB::table('water_systems')->latest();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row) {

                    $viewButton = "<a type='button' class='viewWaterSystem' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#viewWtaerSystemModal' ><i class='fa-solid fa-eye text-info'></i></a>";

                    return $viewButton;
                })
               
                ->filter(function ($instance) use ($request) {
                    if (!empty($request->get('search'))) {
                            $instance->where(function($w) use($request){
                            $search = $request->get('search');
                            $w->orWhere('water_systems.type', 'LIKE', "%$search%")
                            ->orWhere('water_systems.description', 'LIKE', "%$search%")
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
}
