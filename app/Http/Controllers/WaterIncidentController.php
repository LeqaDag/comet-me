<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use DB;
use Route;
use App\Models\AllWaterHolder;
use App\Models\GridUser;
use App\Models\Donor;
use App\Models\IncidentStatus;
use App\Models\H2oStatus;
use App\Models\H2oUser;
use App\Models\H2oSystemIncident;
use App\Models\Household;
use App\Models\User;
use App\Models\Community;
use App\Models\Incident;
use App\Exports\WaterIncidentExport;
use Carbon\Carbon;
use Image; 
use DataTables;
use Excel;

class WaterIncidentController extends Controller
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

                $data = DB::table('h2o_system_incidents')
                    ->join('communities', 'h2o_system_incidents.community_id', '=', 'communities.id')
                    ->join('all_water_holders', 'h2o_system_incidents.all_water_holder_id', 
                        '=', 'all_water_holders.id')
                    ->join('households', 'all_water_holders.household_id', '=', 'households.id')
                    ->join('incidents', 'h2o_system_incidents.incident_id', '=', 'incidents.id')
                    ->join('incident_statuses', 
                        'h2o_system_incidents.incident_status_id', 
                        '=', 'incident_statuses.id')
                    ->select('h2o_system_incidents.date', 'h2o_system_incidents.year',
                        'h2o_system_incidents.id as id', 'h2o_system_incidents.created_at as created_at', 
                        'h2o_system_incidents.updated_at as updated_at', 
                        'communities.english_name as community_name', 
                        'households.english_name as household_name',
                        'incidents.english_name as incident', 
                        'incident_statuses.name as incident_status',
                        'h2o_system_incidents.notes')
                    ->latest(); 
    
                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
    
                        $viewButton = "<a type='button' class='viewWaterIncident' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#viewWaterIncidentModal' ><i class='fa-solid fa-eye text-info'></i></a>";
                        $updateButton = "<a type='button' class='updateWaterIncident' data-id='".$row->id."'><i class='fa-solid fa-pen-to-square text-success'></i></a>";
                        $deleteButton = "<a type='button' class='deleteWaterIncident' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";
                        
                        if(Auth::guard('user')->user()->user_type_id == 1 || 
                            Auth::guard('user')->user()->user_type_id == 2 ||
                            Auth::guard('user')->user()->user_type_id == 5) 
                        {

                            return $viewButton." ". $updateButton." ". $deleteButton;
                        } else return $viewButton;
       
                    })
                    ->filter(function ($instance) use ($request) {
                        if (!empty($request->get('search'))) {
                                $instance->where(function($w) use($request) {
                                $search = $request->get('search');
                                $w->orWhere('communities.english_name', 'LIKE', "%$search%")
                                ->orWhere('communities.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('incident_statuses.name', 'LIKE', "%$search%")
                                ->orWhere('households.english_name', 'LIKE', "%$search%")
                                ->orWhere('households.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('h2o_system_incidents.date', 'LIKE', "%$search%")
                                ->orWhere('incidents.english_name', 'LIKE', "%$search%");
                            });
                        }
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
    
            $communities = Community::all();
            $h2oUsers = DB::table('all_water_holders')
                ->join('households', 'all_water_holders.household_id', '=', 'households.id')
                ->select('households.english_name', 'all_water_holders.id')
                ->get();
    
            $incidents = Incident::all();
            $incidentStatuses = IncidentStatus::all();
            $h2oIncidentsNumber = H2oSystemIncident::count();
            $donors = Donor::all();
    
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
    
            return view('incidents.water.index', compact('communities', 'h2oUsers',
                'incidents', 'incidentStatuses', 'h2oIncidentsNumber', 'donors'))
                ->with('h2oIncidents', json_encode($arrayIncidents));
                
        } else {

            return view('errors.not-found');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {      
        $allWaterHolder = AllWaterHolder::where("household_id", $request->all_water_holder_id)->first();
        $waterIncident = new H2oSystemIncident();

        if($request->date) {

            $waterIncident->date = $request->date;
            $year = explode('-', $request->date);
            $waterIncident->year = $year[0];
        }

        $waterIncident->community_id = $request->community_id[0];
        $waterIncident->all_water_holder_id = $allWaterHolder->id;
        $waterIncident->incident_id = $request->incident_id;
        $waterIncident->incident_status_id = $request->incident_status_id;
        $waterIncident->equipment = $request->equipment;
        $waterIncident->notes = $request->notes;
        $waterIncident->save();

        return redirect()->back()
            ->with('message', 'New Water Incident Added Successfully!');
    }

    /**
     * View Edit page.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) 
    {
        $waterIncident = H2oSystemIncident::findOrFail($id);
        $communities = Community::where('is_archived', 0)->get();
        $incidents = Incident::all();
        $statuses = IncidentStatus::all();

        return view('incidents.water.edit', compact('waterIncident', 'communities', 
            'incidents', 'statuses'));
    }

    /**
     * Update an existing resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request, int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $waterIncident = H2oSystemIncident::findOrFail($id);

        if($request->date) {

            $waterIncident->date = $request->date;
            $year = explode('-', $request->date);
            $waterIncident->year = $year[0];
        }

        $waterIncident->incident_id = $request->incident_id;
        $waterIncident->incident_status_id = $request->incident_status_id;
        $waterIncident->equipment = $request->equipment;
        $waterIncident->notes = $request->notes;
        $waterIncident->save();

        return redirect('/water-incident')->with('message', 'Water Incident Updated Successfully!');
    }

    /**
     * Show the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $waterIncident = H2oSystemIncident::findOrFail($id);
        $householdId = AllWaterHolder::where('id', $waterIncident->all_water_holder_id)->first();

        $h2oUser = Household::findOrFail($householdId->household_id);
        $community = Community::where('id', $waterIncident->community_id)->first();
        $incident = Incident::where('id', $waterIncident->incident_id)->first();
        $waterStatus = IncidentStatus::where('id', $waterIncident->incident_status_id)->first();

        $response['waterIncident'] = $waterIncident;
        $response['h2oUser'] = $h2oUser;
        $response['community'] = $community;
        $response['incident'] = $incident;
        $response['waterStatus'] = $waterStatus;

        return response()->json($response);
    }

    /**
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteWaterIncident(Request $request)
    {
        $id = $request->id;

        $waterIncident = H2oSystemIncident::find($id);

        if($waterIncident->delete()) {

            $response['success'] = 1;
            $response['msg'] = 'Water Incident Deleted successfully'; 
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
                
        return Excel::download(new WaterIncidentExport($request), 'water_incidents.xlsx');
    }
}
