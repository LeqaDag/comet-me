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
use App\Models\Household;
use App\Models\HouseholdMeter;
use App\Models\MgIncident;
use App\Models\Incident;
use App\Models\IncidentEquipment;
use App\Models\IncidentStatusMgSystem;
use App\Models\Region;
use App\Models\MgIncidentEquipment;
use App\Models\MgAffectedHousehold;
use App\Models\MgIncidentPhoto;
use App\Exports\MgIncidentExport;
use Carbon\Carbon;
use Intervention\Image\Facades\Image;
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
        if (Auth::guard('user')->user() != null) {

            if ($request->ajax()) {

                $data = DB::table('mg_incidents')
                    ->join('communities', 'mg_incidents.community_id', '=', 'communities.id')
                    ->join('energy_systems', 'mg_incidents.energy_system_id', '=', 'energy_systems.id')
                    ->join('incidents', 'mg_incidents.incident_id', '=', 'incidents.id')
                    ->join('incident_status_mg_systems', 'mg_incidents.incident_status_mg_system_id', 
                        '=', 'incident_status_mg_systems.id')
                    ->where('mg_incidents.is_archived', 0)
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
                        $updateButton = "<a type='button' class='updateMgIncident' data-id='".$row->id."'><i class='fa-solid fa-pen-to-square text-success'></i></a>";
                        $deleteButton = "<a type='button' class='deleteMgIncident' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";
                        
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
    
            $communities = Community::where('is_archived', 0)
                ->orderBy('english_name', 'ASC')
                ->get();
            $energySystems = EnergySystem::where('energy_system_type_id',1)
                ->where('is_archived', 0)
                ->get();
            $incidents = Incident::where('is_archived', 0)->get();
            $mgIncidents = IncidentStatusMgSystem::where('is_archived', 0)->get();
            $mgIncidentsNumber = MgIncident::where('is_archived', 0)->count();
            $donors = Donor::where('is_archived', 0)
                ->orderBy('donor_name', 'ASC') 
                ->get();
    
            $dataIncidents = DB::table('mg_incidents')
                ->join('communities', 'mg_incidents.community_id', '=', 'communities.id')
                ->join('sub_regions', 'communities.sub_region_id', '=', 'sub_regions.id')
                ->join('incidents', 'mg_incidents.incident_id', '=', 'incidents.id')
                ->join('incident_status_mg_systems', 'mg_incidents.incident_status_mg_system_id', 
                    '=', 'incident_status_mg_systems.id')
                ->where('incident_status_mg_systems.incident_id', "=",  4)
                ->where('mg_incidents.is_archived', 0)
                ->select(
                    DB::raw('incident_status_mg_systems.name as name'),
                    DB::raw('count(*) as number'))
                ->groupBy('incident_status_mg_systems.name')
                ->get();
                
            $arrayIncidents[] = ['English Name', 'Number'];
            
            foreach($dataIncidents as $key => $value) {
    
                $arrayIncidents[++$key] = [$value->name, $value->number];
            }
    
            $incidentEquipments = IncidentEquipment::where('is_archived', 0)
                ->where("incident_equipment_type_id", 3)
                ->orderBy('name', 'ASC')
                ->get(); 

            $households = DB::table('all_energy_meters')
                ->join("households", "all_energy_meters.household_id", "households.id")
                ->select("households.id", "households.english_name")
                ->get();

            return view('incidents.mg.index', compact('communities', 'energySystems',
                'incidents', 'mgIncidents', 'mgIncidentsNumber', 'donors',
                'incidentEquipments', 'households'))
                ->with('incidentsData', json_encode($arrayIncidents));
                
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
        $mgIncident = new MgIncident();

        if($request->date) {

            $mgIncident->date = $request->date;
            $year = explode('-', $request->date);
            $mgIncident->year = $year[0];
        }

        $mgIncident->community_id = $request->community_id;
        $mgIncident->energy_system_id = $request->energy_system_id;
        $mgIncident->incident_id = $request->incident_id;
        $mgIncident->incident_status_mg_system_id = $request->incident_status_mg_system_id;
        $mgIncident->response_date = $request->response_date;
        $mgIncident->notes = $request->notes;
        $mgIncident->save();

        if($request->incident_equipment_id) {
            for($i=0; $i < count($request->incident_equipment_id); $i++) {

                $mgEquipment = new MgIncidentEquipment();
                $mgEquipment->incident_equipment_id = $request->incident_equipment_id[$i];
                $mgEquipment->mg_incident_id = $mgIncident->id;
                $mgEquipment->save();
            }
        }

        if($request->households) { 
            for($i=0; $i < count($request->households); $i++) {

                $mgHousehold = new MgAffectedHousehold();
                $mgHousehold->household_id = $request->households[$i];
                $mgHousehold->mg_incident_id = $mgIncident->id;
                $mgHousehold->save();
            }
        }

        if ($request->file('photos')) {

            foreach($request->photos as $photo) {

                $original_name = $photo->getClientOriginalName();
                $extra_name  = uniqid().'_'.time().'_'.uniqid().'.'.$photo->extension();
                $encoded_base64_image = substr($photo, strpos($photo, ',') + 1); 
                $destinationPath = public_path().'/incidents/mg/' ;
                $photo->move($destinationPath, $extra_name);
    
                $mgIncidentPhoto = new MgIncidentPhoto();
                $mgIncidentPhoto->slug = $extra_name;
                $mgIncidentPhoto->mg_incident_id = $mgIncident->id;
                $mgIncidentPhoto->save();
            }
        }

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
        $mgStatus = IncidentStatusMgSystem::where('id', $mgIncident->incident_status_mg_system_id)
            ->first();
        $mgIncidentEquipments = DB::table('mg_incident_equipment')
            ->join('incident_equipment', 'mg_incident_equipment.incident_equipment_id', 
                '=', 'incident_equipment.id')
            ->join('mg_incidents', 'mg_incident_equipment.mg_incident_id', 
                '=', 'mg_incidents.id')
            ->where('mg_incident_equipment.mg_incident_id', $id)
            ->where('mg_incident_equipment.is_archived', 0)
            ->get();
        $mgAffectedHouseholds = DB::table('mg_affected_households')
            ->join('households', 'mg_affected_households.household_id', 
                '=', 'households.id')
            ->join('mg_incidents', 'mg_affected_households.mg_incident_id', 
                '=', 'mg_incidents.id')
            ->where('mg_affected_households.mg_incident_id', $id)
            ->where('mg_affected_households.is_archived', 0)
            ->get();

        $mgIncidentPhotos = MgIncidentPhoto::where('mg_incident_id', $id)
            ->get();

        return view('incidents.mg.show', compact('mgIncident', 'community', 
            'incident', 'mgStatus', 'mgIncidentEquipments', 'mgAffectedHouseholds',
            'mgIncidentPhotos', 'energySystem'));
    }

    /**
     * View Edit page. 
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function editPage($id)
    {
        $mgIncident = MgIncident::findOrFail($id);

        return response()->json($mgIncident);
    }

        /**
     * View Edit page.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) 
    {
        $mgIncident = MgIncident::findOrFail($id); 
        $communities = Community::where('is_archived', 0)
            ->orderBy('english_name', 'ASC')
            ->get();
        $energySystems = EnergySystem::where('is_archived', 0)->get();
        $incidents = Incident::where('is_archived', 0)->get();
        $mgIncidents = IncidentStatusMgSystem::where('is_archived', 0)->get();
        $mgIncidentEquipments = MgIncidentEquipment::where('mg_incident_id', $id)
            ->where('is_archived', 0)
            ->get();
        $incidentEquipments = IncidentEquipment::where('is_archived', 0)
            ->where("incident_equipment_type_id", 3)
            ->orderBy('name', 'ASC')
            ->get(); 
        $mgAffectedHouseholds = DB::table('mg_affected_households')
            ->join('households', 'mg_affected_households.household_id', 
                '=', 'households.id')
            ->join('mg_incidents', 'mg_affected_households.mg_incident_id', 
                '=', 'mg_incidents.id')
            ->where('mg_affected_households.mg_incident_id', $id)
            ->where('mg_affected_households.is_archived', 0)
            ->select('mg_affected_households.id', 'households.english_name')
            ->get();

        $households = DB::table('all_energy_meters')
            ->join("households", "all_energy_meters.household_id", "households.id")
            ->where('households.community_id', $mgIncident->community_id)
            ->select("households.id", "households.english_name")
            ->get();
        $mgIncidentPhotos = MgIncidentPhoto::where('mg_incident_id', $id)
            ->get();

        return view('incidents.mg.edit', compact('mgIncident', 'communities', 'energySystems', 
            'incidents', 'mgIncidents', 'incidentEquipments', 'mgIncidentEquipments',
            'mgAffectedHouseholds', 'households', 'mgIncidentPhotos'));
    }

    /**
     * Update an existing resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request, int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $mgIncident = MgIncident::findOrFail($id);

        if($request->date) {

            $mgIncident->date = $request->date;
            $year = explode('-', $request->date);
            $mgIncident->year = $year[0];
        }

        $mgIncident->community_id = $request->community_id;
        $mgIncident->energy_system_id = $request->energy_system_id;
        $mgIncident->incident_id = $request->incident_id;
        $mgIncident->incident_status_mg_system_id = $request->incident_status_mg_system_id;
        $mgIncident->response_date = $request->response_date;
        $mgIncident->notes = $request->notes;
        $mgIncident->save();

        if($request->new_equipment) {
 
            for($i=0; $i < count($request->new_equipment); $i++) {

                $mgEquipment = new MgIncidentEquipment();
                $mgEquipment->incident_equipment_id = $request->new_equipment[$i];
                $mgEquipment->mg_incident_id = $mgIncident->id;
                $mgEquipment->save();
            }
        }

        if($request->more_equipment) {

            for($i=0; $i < count($request->more_equipment); $i++) {

                $mgEquipment = new MgIncidentEquipment();
                $mgEquipment->incident_equipment_id = $request->more_equipment[$i];
                $mgEquipment->mg_incident_id = $mgIncident->id;
                $mgEquipment->save();
            }
        }

        if($request->new_household) {
 
            for($i=0; $i < count($request->new_household); $i++) {

                $mgHousehold = new MgAffectedHousehold();
                $mgHousehold->household_id = $request->new_household[$i];
                $mgHousehold->mg_incident_id = $mgIncident->id;
                $mgHousehold->save();
            }
        }

        if($request->more_household) {

            for($i=0; $i < count($request->more_household); $i++) {

                $mgHousehold = new MgAffectedHousehold();
                $mgHousehold->household_id = $request->more_household[$i];
                $mgHousehold->mg_incident_id = $mgIncident->id;
                $mgHousehold->save();
            }
        }

        if ($request->file('more_photos')) {

            foreach($request->more_photos as $photo) {

                $original_name = $photo->getClientOriginalName();
                $extra_name  = uniqid().'_'.time().'_'.uniqid().'.'.$photo->extension();
                $encoded_base64_image = substr($photo, strpos($photo, ',') + 1); 
                $destinationPath = public_path().'/incidents/mg/' ;
                $photo->move($destinationPath, $extra_name);
    
                $mgIncidentPhoto = new MgIncidentPhoto();
                $mgIncidentPhoto->slug = $extra_name;
                $mgIncidentPhoto->mg_incident_id = $mgIncident->id;
                $mgIncidentPhoto->save();
            }
        }

        if ($request->file('new_photos')) {

            foreach($request->new_photos as $photo) {

                $original_name = $photo->getClientOriginalName();
                $extra_name  = uniqid().'_'.time().'_'.uniqid().'.'.$photo->extension();
                $encoded_base64_image = substr($photo, strpos($photo, ',') + 1); 
                $destinationPath = public_path().'/incidents/mg/' ;
                $photo->move($destinationPath, $extra_name);
    
                $mgIncidentPhoto = new MgIncidentPhoto();
                $mgIncidentPhoto->slug = $extra_name;
                $mgIncidentPhoto->mg_incident_id = $mgIncident->id;
                $mgIncidentPhoto->save();
            }
        }

        return redirect('/mg-incident')->with('message', 'MG Incident Updated Successfully!');
    }

     /**
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteMgIncidentPhoto(Request $request)
    {
        $id = $request->id;

        $mgPhoto = MgIncidentPhoto::find($id);

        if($mgPhoto) {

            $mgPhoto->delete();
            
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
    public function deleteMgIncident(Request $request)
    {
        $id = $request->id;

        $mgIncident = MgIncident::find($id);

        if($mgIncident) {

            $mgIncident->is_archived = 1;
            $mgIncident->save();
            
            $response['success'] = 1;
            $response['msg'] = 'MG Incident Deleted successfully'; 
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
    public function deleteMgIncidentEquipment(Request $request)
    {
        $id = $request->id;

        $mgEquipment = MgIncidentEquipment::find($id);

        if($mgEquipment) {

            $mgEquipment->delete();
            
            $response['success'] = 1;
            $response['msg'] = 'Equipment Deleted successfully'; 
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
    public function deletemgAffectedHousehold(Request $request)
    {
        $id = $request->id;

        $mgHousehold = MgAffectedHousehold::find($id);

        if($mgHousehold) {

            $mgHousehold->delete();
            
            $response['success'] = 1;
            $response['msg'] = 'Household Affected Deleted successfully'; 
        } else {

            $response['success'] = 0;
            $response['msg'] = 'Invalid ID.';
        }

        return response()->json($response); 
    }

    /**
     * Get households by community_id.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getHouseholdByCommunity(Request $request)
    {
        $households = Household::where('community_id', $request->community_id)
            ->where('is_archived', 0)
            ->orderBy('english_name', 'ASC')
            ->get();

        if (!$request->community_id) {

            $html = '<option value="">Choose One...</option>';
        } else {

            $html = '<option selected>Choose One...</option>';
            $households = Household::where('community_id', $request->community_id)
                ->where('is_archived', 0)
                ->orderBy('english_name', 'ASC')
                ->get();

            foreach ($households as $household) {
                $html .= '<option value="'.$household->id.'">'.$household->english_name.'</option>';
            }
        }

        return response()->json(['html' => $html]);
    }

    /**
     * Get system by community_id.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getSystemByCommunity(Request $request)
    {
        $systems = EnergySystem::where('community_id', $request->community_id)
            ->where('is_archived', 0)
            ->orderBy('name', 'ASC')
            ->get();

        if (!$request->community_id) {

            $html = '<option value="">Choose One...</option>';
        } else {

            $html = '<option selected>Choose One...</option>';
            $systems = EnergySystem::where('community_id', $request->community_id)
                ->orderBy('name', 'ASC')
                ->where('is_archived', 0)
                ->get();

            foreach ($systems as $system) {
                $html .= '<option value="'.$system->id.'">'.$system->name.'</option>';
            }
        }

        return response()->json(['html' => $html]);
    }

    /**
     * Get households by community_id.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getStatusByIncidentType(Request $request)
    {
        $mgIncidents = IncidentStatusMgSystem::where('incident_id', $request->incident_type_id)
            ->where('is_archived', 0)
            ->orderBy('name', 'ASC')
            ->get();

        if (!$request->incident_type_id) {

            $html = '<option value="">Choose One...</option>';
        } else {

            $html = '<option selected>Choose One...</option>';
            $mgIncidents = IncidentStatusMgSystem::where('incident_id', $request->incident_type_id)
                ->orderBy('name', 'ASC')
                ->where('is_archived', 0)
                ->get();

            foreach ($mgIncidents as $mgIncident) {
                $html .= '<option value="'.$mgIncident->id.'">'.$mgIncident->name.'</option>';
            }
        }

        return response()->json(['html' => $html]);
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
