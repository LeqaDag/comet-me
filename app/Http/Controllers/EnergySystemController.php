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
use App\Models\EnergySystemType;
use App\Models\FbsUserIncident;
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
use App\Models\Town;
use App\Models\IncidentStatusSmallInfrastructure;
use App\Models\MgIncident;
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

                    return $viewButton;
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
        $fbsIncidentsNumber = FbsUserIncident::where('energy_user_id', '!=', '0')->count();
        $mgIncidentsNumber = MgIncident::count();

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

        $dataIncidents = DB::table('mg_incidents')
            ->join('communities', 'mg_incidents.community_id', '=', 'communities.id')
            ->join('sub_regions', 'communities.sub_region_id', '=', 'sub_regions.id')
            ->join('incidents', 'mg_incidents.incident_id', '=', 'incidents.id')
            ->join('incident_status_mg_systems', 'mg_incidents.incident_status_mg_system_id', 
                '=', 'incident_status_mg_systems.id')
            ->select(
                    DB::raw('incident_status_mg_systems.name as name'),
                    DB::raw('count(*) as number'))
            ->groupBy('incident_status_mg_systems.name')
            ->get();
        $arrayIncidents[] = ['English Name', 'Number'];
        
        foreach($dataIncidents as $key => $value) {

            $arrayIncidents[++$key] = [$value->name, $value->number];
        }

        $dataFbsIncidents = DB::table('fbs_user_incidents')
            ->join('energy_users', 'fbs_user_incidents.energy_user_id', '=', 'energy_users.id')
            ->join('households', 'energy_users.household_id', '=', 'households.id')
            ->join('communities', 'fbs_user_incidents.community_id', '=', 'communities.id')
            ->join('incidents', 'fbs_user_incidents.incident_id', '=', 'incidents.id')
            ->join('incident_status_small_infrastructures', 
                'fbs_user_incidents.incident_status_small_infrastructure_id', 
                '=', 'incident_status_small_infrastructures.id')
            ->select(
                    DB::raw('incident_status_small_infrastructures.name as name'),
                    DB::raw('count(*) as number'))
            ->groupBy('incident_status_small_infrastructures.name')
            ->get();
        $arrayFbsIncidents[] = ['English Name', 'Number'];
        
        foreach($dataFbsIncidents as $key => $value) {

            $arrayFbsIncidents[++$key] = [$value->name, $value->number];
        }

		return view('system.energy.index', compact('communities', 'donors', 'services', 
            'fbsIncidentsNumber', 'mgIncidentsNumber'))
        ->with(
            'energySystemData', json_encode($arrayEnergySystem))
        ->with(
            'incidentsData', json_encode($arrayIncidents))
        ->with(
            'incidentsFbsData', json_encode($arrayFbsIncidents));

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
}
