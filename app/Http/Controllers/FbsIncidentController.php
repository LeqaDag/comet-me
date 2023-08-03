<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use DB;
use Route;
use App\Models\AllEnergyMeter;
use App\Models\User;
use App\Models\Community;
use App\Models\Donor;
use App\Models\EnergyUser;
use App\Models\EnergySystem;
use App\Models\HouseholdMeter;
use App\Models\FbsUserIncident;
use App\Models\FbsIncidentEquipment;
use App\Models\IncidentEquipment;
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
        if (Auth::guard('user')->user() != null) {

            if ($request->ajax()) {

                $data = DB::table('fbs_user_incidents')
                    ->join('communities', 'fbs_user_incidents.community_id', '=', 'communities.id')
                    ->join('all_energy_meters', 'fbs_user_incidents.energy_user_id', '=', 'all_energy_meters.id')
                    ->join('households', 'all_energy_meters.household_id', '=', 'households.id')
                    ->join('incidents', 'fbs_user_incidents.incident_id', '=', 'incidents.id')
                    ->join('incident_status_small_infrastructures', 
                        'fbs_user_incidents.incident_status_small_infrastructure_id', 
                        '=', 'incident_status_small_infrastructures.id')
                    ->where('fbs_user_incidents.is_archived', 0)
                    ->select('fbs_user_incidents.date', 'fbs_user_incidents.year',
                        'fbs_user_incidents.id as id', 'fbs_user_incidents.created_at as created_at', 
                        'fbs_user_incidents.updated_at as updated_at', 
                        'communities.english_name as community_name', 
                        'households.english_name as household_name',
                        'incidents.english_name as incident', 
                        'incident_status_small_infrastructures.name as fbs_status',
                        'fbs_user_incidents.notes')
                    ->orderBy('households.english_name', 'ASC')
                    ->latest(); 
    
                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
    
                        $viewButton = "<a type='button' class='viewFbsIncident' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#viewFbsIncidentModal' ><i class='fa-solid fa-eye text-info'></i></a>";
                        $updateButton = "<a type='button' class='updateFbsIncident' data-id='".$row->id."'><i class='fa-solid fa-pen-to-square text-success'></i></a>";
                        $deleteButton = "<a type='button' class='deleteFbsIncident' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";
                        
                        if(Auth::guard('user')->user()->user_type_id == 1 || 
                            Auth::guard('user')->user()->user_type_id == 2 ||
                            Auth::guard('user')->user()->user_type_id == 3 ||
                            Auth::guard('user')->user()->user_type_id == 4) 
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
    
            $communities = Community::where('is_archived', 0)
                ->orderBy('english_name', 'ASC')
                ->get();
            $energyUsers = DB::table('all_energy_meters')
                ->join('households', 'all_energy_meters.household_id', '=', 'households.id')
                ->where('all_energy_meters.energy_system_type_id', 2)
                ->where('all_energy_meters.is_archived', 0)
                ->orderBy('households.english_name', 'ASC')
                ->select('households.english_name', 'all_energy_meters.id')
                ->get();
    
            $incidents = Incident::where('is_archived', 0)->get();
            $incidentEquipments = IncidentEquipment::where('is_archived', 0)
                ->orderBy('name', 'ASC')
                ->get(); 
            $fbsIncidents = IncidentStatusSmallInfrastructure::where('is_archived', 0)->get();
            $fbsIncidentsNumber = FbsUserIncident::where('energy_user_id', '!=', '0')
                ->where('is_archived', 0)
                ->count(); 
            $donors = Donor::where('is_archived', 0)
                ->orderBy('donor_name', 'ASC')
                ->get();
    
            $dataFbsIncidents = DB::table('fbs_user_incidents')
                ->join('all_energy_meters', 'fbs_user_incidents.energy_user_id', '=', 'all_energy_meters.id')
                ->join('households', 'all_energy_meters.household_id', '=', 'households.id')
                ->join('communities', 'fbs_user_incidents.community_id', '=', 'communities.id')
                ->join('incidents', 'fbs_user_incidents.incident_id', '=', 'incidents.id')
                ->join('incident_status_small_infrastructures', 
                    'fbs_user_incidents.incident_status_small_infrastructure_id', 
                    '=', 'incident_status_small_infrastructures.id')
                ->where('incident_status_small_infrastructures.incident_id', 3)
                ->where('fbs_user_incidents.is_archived', 0)
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
                'incidents', 'fbsIncidents', 'fbsIncidentsNumber', 'donors', 'incidentEquipments'))
                ->with('incidentsFbsData', json_encode($arrayFbsIncidents));
                
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
        $fbsIncident = new FbsUserIncident();

        if($request->date) {

            $fbsIncident->date = $request->date;
            $year = explode('-', $request->date);
            $fbsIncident->year = $year[0];
        }

        $fbsIncident->community_id = $request->community_id;
        $energyUser = AllEnergyMeter::where('household_id', $request->energy_user_id)->first();
        $fbsIncident->energy_user_id = $energyUser->id;
        $fbsIncident->incident_id = $request->incident_id;
        $fbsIncident->incident_status_small_infrastructure_id = $request->incident_status_small_infrastructure_id;
        $fbsIncident->notes = $request->notes;
        $fbsIncident->save();
        $id = $fbsIncident->id;

        if($request->incident_equipment_id) {
            for($i=0; $i < count($request->incident_equipment_id); $i++) {

                $fbsEquipment = new FbsIncidentEquipment();
                $fbsEquipment->incident_equipment_id = $request->incident_equipment_id[$i];
                $fbsEquipment->fbs_user_incident_id = $id;
                $fbsEquipment->save();
            }
        }

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
        $householdId = AllEnergyMeter::where('id', $fbsIncident->energy_user_id)->first();

        $energyUser = Household::findOrFail($householdId->household_id);
        $community = Community::where('id', $fbsIncident->community_id)->first();
        $incident = Incident::where('id', $fbsIncident->incident_id)->first();
        $fbsStatus = IncidentStatusSmallInfrastructure::where('id', 
            $fbsIncident->incident_status_small_infrastructure_id)->first();

        $fbsIncidentEquipments = DB::table('fbs_incident_equipment')
            ->join('incident_equipment', 'fbs_incident_equipment.incident_equipment_id', 
                '=', 'incident_equipment.id')
            ->join('fbs_user_incidents', 'fbs_incident_equipment.fbs_user_incident_id', 
                '=', 'fbs_user_incidents.id')
            ->where('fbs_incident_equipment.fbs_user_incident_id', $id)
            ->where('fbs_incident_equipment.is_archived', 0)
            ->get();

        $response['fbsIncident'] = $fbsIncident;
        $response['energyUser'] = $energyUser;
        $response['community'] = $community;
        $response['incident'] = $incident;
        $response['fbsStatus'] = $fbsStatus;
        $response['fbsIncidentEquipments'] = $fbsIncidentEquipments;

        return response()->json($response);
    }

    /**
     * View Edit page.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) 
    {
        $fbsIncident = FbsUserIncident::findOrFail($id);
        $communities = Community::where('is_archived', 0)
            ->orderBy('english_name', 'ASC')
            ->get();
        $energyUsers = AllEnergyMeter::where('household_id', '!=', 0)
            ->where('is_archived', 0)
            ->get();
        $incidents = Incident::where('is_archived', 0)->get();
        $fbsStatuses = IncidentStatusSmallInfrastructure::where('is_archived', 0)->get();
        $fbsIncidentEquipments = FbsIncidentEquipment::where('fbs_user_incident_id', $id)
            ->where('is_archived', 0)
            ->get();
        $incidentEquipments = IncidentEquipment::where('is_archived', 0)
            ->orderBy('name', 'ASC')
            ->get(); 

        return view('incidents.fbs.edit', compact('fbsIncident', 'communities', 'energyUsers', 
            'incidents', 'fbsStatuses', 'fbsIncidentEquipments', 'incidentEquipments'));
    }

    /**
     * Update an existing resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request, int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $fbsIncident = FbsUserIncident::findOrFail($id);

        if($request->date) {

            $fbsIncident->date = $request->date;
            $year = explode('-', $request->date);
            $fbsIncident->year = $year[0];
        }

        $fbsIncident->incident_id = $request->incident_id;
        $fbsIncident->incident_status_small_infrastructure_id = $request->incident_status_small_infrastructure_id;
        $fbsIncident->notes = $request->notes;
        $fbsIncident->save();

        if($request->new_equipment) {

            for($i=0; $i < count($request->new_equipment); $i++) {

                $fbsEquipment = new FbsIncidentEquipment();
                $fbsEquipment->incident_equipment_id = $request->new_equipment[$i];
                $fbsEquipment->fbs_user_incident_id = $fbsIncident->id;
                $fbsEquipment->save();
            }
        }

        if($request->more_equipment) {

            for($i=0; $i < count($request->more_equipment); $i++) {

                $fbsEquipment = new FbsIncidentEquipment();
                $fbsEquipment->incident_equipment_id = $request->more_equipment[$i];
                $fbsEquipment->fbs_user_incident_id = $fbsIncident->id;
                $fbsEquipment->save();
            }
        }

        return redirect('/fbs-incident')->with('message', 'FBS Incident Updated Successfully!');
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

        if($fbsIncident) {

            $fbsIncident->is_archived = 1;
            $fbsIncident->save();
            
            $response['success'] = 1;
            $response['msg'] = 'FBS Incident Deleted successfully'; 
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
    public function deleteIncidentEquipment(Request $request)
    {
        $id = $request->id;

        $fbsEquipment = FbsIncidentEquipment::find($id);

        if($fbsEquipment) {

            $fbsEquipment->is_archived = 1;
            $fbsEquipment->save();
            
            $response['success'] = 1;
            $response['msg'] = 'Equipment Deleted successfully'; 
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
