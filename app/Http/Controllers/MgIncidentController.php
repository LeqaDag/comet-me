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
use App\Models\EnergyDonor;
use App\Models\EnergySystem;
use App\Models\HouseholdMeter;
use App\Models\MgIncident;
use App\Models\Incident;
use App\Models\IncidentStatusMgSystem;
use App\Models\Region;
use App\Exports\MgIncidentExport;
use Carbon\Carbon;
use Image;
use DataTables;
use Excel;

class MgIncidentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = DB::table('mg_incidents')
                ->join('communities', 'mg_incidents.community_id', '=', 'communities.id')
                ->join('energy_systems', 'mg_incidents.energy_system_id', '=', 'energy_systems.id')
                ->join('incidents', 'mg_incidents.incident_id', '=', 'incidents.id')
                ->join('incident_status_mg_systems', 'mg_incidents.incident_status_mg_system_id', 
                    '=', 'incident_status_mg_systems.id')
                ->select('mg_incidents.date', 'mg_incidents.year',
                    'mg_incidents.id as id', 'mg_incidents.created_at as created_at', 
                    'mg_incidents.updated_at as updated_at', 
                    'communities.english_name as community_name',
                    'incidents.english_name as incident',
                    'energy_systems.name as energy_name', 
                    'incident_status_mg_systems.name as mg_status',
                    'mg_incidents.notes')
                ->latest(); 

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row) {

                    $viewButton = "<a type='button' class='viewMgIncident' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#viewMgIncidentModal' ><i class='fa-solid fa-eye text-info'></i></a>";
                    $deleteButton = "<a type='button' class='deleteMgIncident' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";
                    
                    return $viewButton." ".$deleteButton;
   
                })
                ->filter(function ($instance) use ($request) {
                    if (!empty($request->get('search'))) {
                            $instance->where(function($w) use($request) {
                            $search = $request->get('search');
                            $w->orWhere('communities.english_name', 'LIKE', "%$search%")
                            ->orWhere('communities.arabic_name', 'LIKE', "%$search%")
                            ->orWhere('incident_status_mg_systems.name', 'LIKE', "%$search%")
                            ->orWhere('energy_systems.name', 'LIKE', "%$search%")
                            ->orWhere('mg_incidents.date', 'LIKE', "%$search%")
                            ->orWhere('incidents.english_name', 'LIKE', "%$search%");
                        });
                    }
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $communities = Community::all();
        $energySystems = EnergySystem::where('energy_system_type_id',1)->get();
        $incidents = Incident::all();
        $mgIncidents = IncidentStatusMgSystem::all();
        $mgIncidentsNumber = MgIncident::count();
        $donors = Donor::all();

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

        return view('incidents.mg.index', compact('communities', 'energySystems',
            'incidents', 'mgIncidents', 'mgIncidentsNumber', 'donors'))
            ->with('incidentsData', json_encode($arrayIncidents));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {       
        $mgIncident = new MgIncident();

        if($request->date) {

            $mgIncident->date = $request->date;
            $year = explode('-', $request->date);
            $mgIncident->year = $year[0];
        }

        $mgIncident->community_id = $request->community_id[0];
        $mgIncident->energy_system_id = $request->energy_system_id[0];
        $mgIncident->incident_id = $request->incident_id;
        $mgIncident->incident_status_mg_system_id = $request->incident_status_mg_system_id;
        $mgIncident->notes = $request->notes;
        $mgIncident->save();

        return redirect()->back()
        ->with('message', 'New MG Incident Added Successfully!');
    }

    /**
     * Show the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $mgIncident = MgIncident::findOrFail($id);
        $energySystem = EnergySystem::where('id', $mgIncident->energy_system_id)->first();
        $community = Community::where('id', $mgIncident->community_id)->first();
        $incident = Incident::where('id', $mgIncident->incident_id)->first();
        $mgStatus = IncidentStatusMgSystem::where('id', $mgIncident->incident_status_mg_system_id)->first();

        $response['mgIncident'] = $mgIncident;
        $response['energySystem'] = $energySystem;
        $response['community'] = $community;
        $response['incident'] = $incident;
        $response['mgStatus'] = $mgStatus;

        return response()->json($response);
    }

    /**
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteMgIncident(Request $request)
    {
        $id = $request->id;

        $mgIncident = MgIncident::find($id);

        if($mgIncident->delete()) {

            $response['success'] = 1;
            $response['msg'] = 'MG Incident Deleted successfully'; 
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
                
        return Excel::download(new MgIncidentExport($request), 'mg_incidents.xlsx');
    }
}
