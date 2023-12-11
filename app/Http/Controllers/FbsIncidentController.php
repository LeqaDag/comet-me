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
use App\Models\FbsIncidentStatus;
use App\Models\FbsIncidentPhoto;
use App\Models\IncidentEquipment;
use App\Models\Incident;
use App\Models\Household;
use App\Models\IncidentStatusSmallInfrastructure;
use App\Models\Region;
use App\Exports\FbsIncidentExport;
use App\Exports\AllIncidentExport;
use Carbon\Carbon;
use Intervention\Image\Facades\Image;
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
                    ->leftJoin('households', 'all_energy_meters.household_id', '=', 'households.id')
                    ->leftJoin('public_structures', 'all_energy_meters.public_structure_id', 
                        'public_structures.id')
                    ->join('incidents', 'fbs_user_incidents.incident_id', '=', 'incidents.id')
                    ->leftJoin('fbs_incident_statuses', 
                        'fbs_user_incidents.id', 
                        '=', 'fbs_incident_statuses.fbs_user_incident_id')
                    ->leftJoin('incident_status_small_infrastructures', 
                        'fbs_incident_statuses.incident_status_small_infrastructure_id', 
                        '=', 'incident_status_small_infrastructures.id')
                    ->where('fbs_user_incidents.is_archived', 0)
                    ->select('fbs_user_incidents.date', 'fbs_user_incidents.year',
                        'fbs_user_incidents.id as id', 'fbs_user_incidents.created_at as created_at', 
                        'fbs_user_incidents.updated_at as updated_at', 
                        'communities.english_name as community_name', 
                        'households.english_name as household_name',
                        'public_structures.english_name as public_name',
                        'incidents.english_name as incident', 
                        DB::raw('group_concat(DISTINCT incident_status_small_infrastructures.name) as fbs_status'),
                        'fbs_user_incidents.notes')
                    ->orderBy('fbs_user_incidents.date', 'desc')
                    ->groupBy('fbs_user_incidents.id'); 
    
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
                    ->addColumn('holder', function($row) {

                        if($row->household_name != null) $holder = $row->household_name;
                        else if($row->public_name != null) $holder = $row->public_name;

                        return $holder;
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
                                ->orWhere('public_structures.english_name', 'LIKE', "%$search%")
                                ->orWhere('public_structures.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('fbs_user_incidents.date', 'LIKE', "%$search%")
                                ->orWhere('incidents.english_name', 'LIKE', "%$search%");
                            });
                        }
                    })
                    ->rawColumns(['action', 'holder'])
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
                ->where("incident_equipment_type_id", 2)
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
        if($request->public_user == "user") {

            $energyUser = AllEnergyMeter::where('household_id', $request->energy_user_id)->first();
            $fbsIncident->energy_user_id = $energyUser->id;
        }

        if($request->public_user == "public") {

            $energyUser = AllEnergyMeter::where('public_structure_id', $request->energy_user_id)->first();
            $fbsIncident->energy_user_id = $energyUser->id;
        }

        $fbsIncident->incident_id = $request->incident_id;
        $fbsIncident->response_date = $request->response_date;
        $fbsIncident->losses_energy = $request->losses_energy;
        $fbsIncident->losses_water = $request->losses_water;
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

        if($request->incident_status_small_infrastructure_id) {
            for($i=0; $i < count($request->incident_status_small_infrastructure_id); $i++) {

                $fbsStatus = new FbsIncidentStatus();
                $fbsStatus->incident_status_small_infrastructure_id = 
                    $request->incident_status_small_infrastructure_id[$i];
                $fbsStatus->fbs_user_incident_id = $id;
                $fbsStatus->save();
            }
        }
        
        if ($request->file('photos')) {

            foreach($request->photos as $photo) {

                $original_name = $photo->getClientOriginalName();
                $extra_name  = uniqid().'_'.time().'_'.uniqid().'.'.$photo->extension();
                $encoded_base64_image = substr($photo, strpos($photo, ',') + 1); 
                $destinationPath = public_path().'/incidents/energy/' ;
                $photo->move($destinationPath, $extra_name);
    
                $fbsIncidentPhoto = new FbsIncidentPhoto();
                $fbsIncidentPhoto->slug = $extra_name;
                $fbsIncidentPhoto->fbs_user_incident_id = $id;
                $fbsIncidentPhoto->save();
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
        $energyMeter = AllEnergyMeter::findOrFail($fbsIncident->energy_user_id);
        $community = Community::where('id', $fbsIncident->community_id)->first();
        $incident = Incident::where('id', $fbsIncident->incident_id)->first();
        $fbsStatuses = DB::table('fbs_incident_statuses')
            ->join('fbs_user_incidents', 'fbs_user_incidents.id', 
                'fbs_incident_statuses.fbs_user_incident_id')
            ->join('incident_status_small_infrastructures', 
                'fbs_incident_statuses.incident_status_small_infrastructure_id', 
                'incident_status_small_infrastructures.id')
            ->where('fbs_incident_statuses.fbs_user_incident_id', $id)
            ->get();
            
        $fbsIncidentEquipments = DB::table('fbs_incident_equipment')
            ->join('incident_equipment', 'fbs_incident_equipment.incident_equipment_id', 
                '=', 'incident_equipment.id')
            ->join('fbs_user_incidents', 'fbs_incident_equipment.fbs_user_incident_id', 
                '=', 'fbs_user_incidents.id')
            ->where('fbs_incident_equipment.fbs_user_incident_id', $id)
            ->get();

        $fbsIncidentPhotos = FbsIncidentPhoto::where('fbs_user_incident_id', $id)
            ->get();

        return view('incidents.fbs.show', compact('fbsIncident', 'community', 
            'incident', 'fbsStatuses', 'fbsIncidentEquipments', 'energyMeter',
            'fbsIncidentPhotos'));
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
        $energyMeter = AllEnergyMeter::findOrFail($fbsIncident->energy_user_id);

        $incidents = Incident::where('is_archived', 0)->get();
        $fbsIncidentEquipments = FbsIncidentEquipment::where('fbs_user_incident_id', $id)
            ->where('is_archived', 0)
            ->get();
        $incidentEquipments = IncidentEquipment::where("incident_equipment_type_id", 2)
            ->orderBy('name', 'ASC')
            ->get(); 
        $fbsStatuses = DB::table('fbs_incident_statuses')
            ->join('fbs_user_incidents', 'fbs_user_incidents.id', 
                'fbs_incident_statuses.fbs_user_incident_id')
            ->join('incident_status_small_infrastructures', 
                'fbs_incident_statuses.incident_status_small_infrastructure_id', 
                'incident_status_small_infrastructures.id')
            ->where('fbs_incident_statuses.fbs_user_incident_id', $id)
            ->select('fbs_incident_statuses.id', 'incident_status_small_infrastructures.name')
            ->get();
        $fbsIncidentStatues = IncidentStatusSmallInfrastructure::where('is_archived', 0)->get();
        $fbsIncidentPhotos = FbsIncidentPhoto::where('fbs_user_incident_id', $id)
            ->get();

        return view('incidents.fbs.edit', compact('fbsIncident', 'communities', 'energyMeter', 
            'incidents', 'fbsStatuses', 'fbsIncidentEquipments', 'incidentEquipments',
            'fbsIncidentStatues', 'fbsIncidentPhotos'));
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
        $fbsIncident->notes = $request->notes;
        $fbsIncident->response_date = $request->response_date;
        $fbsIncident->losses_energy = $request->losses_energy;
        $fbsIncident->losses_water = $request->losses_water;
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

        if($request->new_statuses) {
            for($i=0; $i < count($request->new_statuses); $i++) {

                $fbsStatus = new FbsIncidentStatus();
                $fbsStatus->incident_status_small_infrastructure_id = 
                    $request->new_statuses[$i];
                $fbsStatus->fbs_user_incident_id = $id;
                $fbsStatus->save();
            }
        }

        if($request->more_statuses) {
            for($i=0; $i < count($request->more_statuses); $i++) {

                $fbsStatus = new FbsIncidentStatus();
                $fbsStatus->incident_status_small_infrastructure_id = 
                    $request->more_statuses[$i];
                $fbsStatus->fbs_user_incident_id = $id;
                $fbsStatus->save();
            }
        }

        if ($request->file('new_photos')) {

            foreach($request->new_photos as $photo) {

                $original_name = $photo->getClientOriginalName();
                $extra_name  = uniqid().'_'.time().'_'.uniqid().'.'.$photo->extension();
                $encoded_base64_image = substr($photo, strpos($photo, ',') + 1); 
                $destinationPath = public_path().'/incidents/energy/' ;
                $photo->move($destinationPath, $extra_name);
    
                $fbsIncidentPhoto = new FbsIncidentPhoto();
                $fbsIncidentPhoto->slug = $extra_name;
                $fbsIncidentPhoto->fbs_user_incident_id = $id;
                $fbsIncidentPhoto->save();
            }
        }

        if ($request->file('more_photos')) {

            foreach($request->more_photos as $photo) {

                $original_name = $photo->getClientOriginalName();
                $extra_name  = uniqid().'_'.time().'_'.uniqid().'.'.$photo->extension();
                $encoded_base64_image = substr($photo, strpos($photo, ',') + 1); 
                $destinationPath = public_path().'/incidents/energy/' ;
                $photo->move($destinationPath, $extra_name);
    
                $fbsIncidentPhoto = new FbsIncidentPhoto();
                $fbsIncidentPhoto->slug = $extra_name;
                $fbsIncidentPhoto->fbs_user_incident_id = $id;
                $fbsIncidentPhoto->save();
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
    public function deleteIncidentPhoto(Request $request)
    {
        $id = $request->id;

        $fbsPhoto = FbsIncidentPhoto::find($id);

        if($fbsPhoto) {

            $fbsPhoto->delete();
            
            $response['success'] = 1;
            $response['msg'] = 'Photo Deleted successfully'; 
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
    public function deleteIncidentStatus(Request $request)
    {
        $id = $request->id;

        $fbsStatus = FbsIncidentStatus::find($id);

        if($fbsStatus) {

            $fbsStatus->delete();
            
            $response['success'] = 1;
            $response['msg'] = 'Status Deleted successfully'; 
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

            $fbsEquipment->delete();
            
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
        if($request->type == "all") {

            return Excel::download(new AllIncidentExport($request), 'all_incidents.xlsx');
        }  else {

            return Excel::download(new FbsIncidentExport($request), 'fbs_incidents.xlsx');
        }    
    }
}
