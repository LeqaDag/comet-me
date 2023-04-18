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
use App\Models\Donor;
use App\Models\EnergyUser;
use App\Models\EnergySystem;
use App\Models\HouseholdMeter;
use App\Models\FbsUserIncident;
use App\Models\Incident;
use App\Models\Household;
use App\Models\IncidentStatusSmallInfrastructure;
use App\Models\Region;
use App\Exports\FbsIncidentExport;
use Carbon\Carbon;
use Image; 
use DataTables;
use Excel;

class FbsIncidentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = DB::table('fbs_user_incidents')
                ->join('communities', 'fbs_user_incidents.community_id', '=', 'communities.id')
                ->join('energy_users', 'fbs_user_incidents.energy_user_id', '=', 'energy_users.id')
                ->join('households', 'energy_users.household_id', '=', 'households.id')
                ->join('incidents', 'fbs_user_incidents.incident_id', '=', 'incidents.id')
                ->join('incident_status_small_infrastructures', 
                    'fbs_user_incidents.incident_status_small_infrastructure_id', 
                    '=', 'incident_status_small_infrastructures.id')
                ->select('fbs_user_incidents.date', 'fbs_user_incidents.year',
                    'fbs_user_incidents.id as id', 'fbs_user_incidents.created_at as created_at', 
                    'fbs_user_incidents.updated_at as updated_at', 
                    'communities.english_name as community_name', 
                    'households.english_name as household_name',
                    'incidents.english_name as incident', 
                    'incident_status_small_infrastructures.name as fbs_status',
                    'fbs_user_incidents.notes')
                ->latest(); 

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row) {

                    $viewButton = "<a type='button' class='viewFbsIncident' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#viewFbsIncidentModal' ><i class='fa-solid fa-eye text-info'></i></a>";
                    $deleteButton = "<a type='button' class='deleteFbsIncident' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";
                    
                    return $viewButton." ".$deleteButton;
   
                })
                ->filter(function ($instance) use ($request) {
                    if (!empty($request->get('search'))) {
                            $instance->where(function($w) use($request) {
                            $search = $request->get('search');
                            $w->orWhere('communities.english_name', 'LIKE', "%$search%")
                            ->orWhere('communities.arabic_name', 'LIKE', "%$search%")
                            ->orWhere('incident_status_small_infrastructures.name', 'LIKE', "%$search%")
                            ->orWhere('households.english_name', 'LIKE', "%$search%")
                            ->orWhere('households.arabic_name', 'LIKE', "%$search%")
                            ->orWhere('fbs_user_incidents.date', 'LIKE', "%$search%")
                            ->orWhere('incidents.english_name', 'LIKE', "%$search%");
                        });
                    }
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $communities = Community::all();
        $energyUsers = DB::table('energy_users')
            ->join('households', 'energy_users.household_id', '=', 'households.id')
            ->where('energy_users.energy_system_type_id', 2)
            ->select('households.english_name', 'energy_users.id')
            ->get();

        $incidents = Incident::all();
        $fbsIncidents = IncidentStatusSmallInfrastructure::all();
        $fbsIncidentsNumber = FbsUserIncident::where('energy_user_id', '!=', '0')->count();
        $donors = Donor::all();

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

        return view('incidents.fbs.index', compact('communities', 'energyUsers',
            'incidents', 'fbsIncidents', 'fbsIncidentsNumber', 'donors'))
            ->with('incidentsFbsData', json_encode($arrayFbsIncidents));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {      
       // dd($request->all()); 
        $fbsIncident = new FbsUserIncident();

        if($request->date) {

            $fbsIncident->date = $request->date;
            $year = explode('-', $request->date);
            $fbsIncident->year = $year[0];
        }

        $fbsIncident->community_id = $request->community_id[0];
        $fbsIncident->energy_user_id = $request->energy_user_id[0];
        $fbsIncident->incident_id = $request->incident_id;
        $fbsIncident->incident_status_small_infrastructure_id = $request->incident_status_small_infrastructure_id;
        $fbsIncident->equipment = $request->equipment;
        $fbsIncident->notes = $request->notes;
        $fbsIncident->save();

        return redirect()->back()
        ->with('message', 'New FBS Incident Added Successfully!');
    }

    /**
     * Show the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $fbsIncident = FbsUserIncident::findOrFail($id);
        $householdId = EnergyUser::where('id', $fbsIncident->energy_user_id)->first();

        $energyUser = Household::findOrFail($householdId->household_id);
        $community = Community::where('id', $fbsIncident->community_id)->first();
        $incident = Incident::where('id', $fbsIncident->incident_id)->first();
        $fbsStatus = IncidentStatusSmallInfrastructure::where('id', 
            $fbsIncident->incident_status_small_infrastructure_id)->first();

        $response['fbsIncident'] = $fbsIncident;
        $response['energyUser'] = $energyUser;
        $response['community'] = $community;
        $response['incident'] = $incident;
        $response['fbsStatus'] = $fbsStatus;

        return response()->json($response);
    }

    /**
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteFbsIncident(Request $request)
    {
        $id = $request->id;

        $fbsIncident = FbsUserIncident::find($id);

        if($fbsIncident->delete()) {

            $response['success'] = 1;
            $response['msg'] = 'FBS Incident Deleted successfully'; 
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
                
        return Excel::download(new FbsIncidentExport($request), 'fbs_incidents.xlsx');
    }
}
