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
use App\Models\H2oIncidentPhoto;
use App\Models\H2oPublicStructure;
use App\Models\GridPublicStructure;
use App\Models\H2oIncidentStatus; 
use App\Models\Household;
use App\Models\User;
use App\Models\Community;
use App\Models\Incident;
use App\Models\IncidentEquipment;
use App\Models\WaterSystem;
use App\Models\WaterIncidentEquipment;
use App\Exports\WaterIncidentExport;
use App\Exports\AllIncidentExport;
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

            $communityFilter = $request->input('community_filter');
            $typeFilter = $request->input('incident_filter');
            $dateFilter = $request->input('date_filter');

            if ($request->ajax()) {

                $data = DB::table('h2o_system_incidents')
                    ->join('communities', 'h2o_system_incidents.community_id', 'communities.id')
                    ->leftJoin('water_systems', 'h2o_system_incidents.water_system_id', 
                        'water_systems.id')
                    ->leftJoin('all_water_holders', 'h2o_system_incidents.all_water_holder_id', 
                        'all_water_holders.id')
                    ->leftJoin('households', 'all_water_holders.household_id', 'households.id')
                    ->leftJoin('public_structures', 'all_water_holders.public_structure_id', 
                        'public_structures.id')
                    ->join('incidents', 'h2o_system_incidents.incident_id', 'incidents.id')
                    ->leftJoin('h2o_incident_statuses', 'h2o_system_incidents.id', 
                        'h2o_incident_statuses.h2o_system_incident_id')
                    ->leftJoin('incident_statuses', 
                        'h2o_incident_statuses.incident_status_id', 
                        'incident_statuses.id')
                    ->where('h2o_system_incidents.is_archived', 0)
                    ->where('h2o_incident_statuses.is_archived', 0);

                if($communityFilter != null) { 

                    $data->where('communities.id', $communityFilter);
                }
                if ($typeFilter != null) {

                    $data->where('h2o_system_incidents.incident_id', $typeFilter);
                }
                if ($dateFilter != null) {

                    $data->where('h2o_system_incidents.date', '>=', $dateFilter);
                }
                
                $data->select([
                    'h2o_system_incidents.date', 'h2o_system_incidents.year',
                    'h2o_system_incidents.id as id', 'h2o_system_incidents.created_at as created_at', 
                    'h2o_system_incidents.updated_at as updated_at', 
                    'communities.english_name as community_name', 
                    'households.english_name as household_name',
                    'public_structures.english_name as public_name', 
                    'water_systems.name as system_name', 
                    'incidents.english_name as incident',
                    DB::raw('group_concat(DISTINCT incident_statuses.name) as incident_status'),
                    'h2o_system_incidents.notes'
                ])
                ->orderBy('h2o_system_incidents.date', 'desc')
                ->groupBy('h2o_system_incidents.id'); 

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
                    ->addColumn('holder', function($row) {

                        if($row->household_name != null) $holder = $row->household_name;
                        else if($row->public_name != null) $holder = $row->public_name;
                        else if($row->system_name != null) $holder = $row->system_name;

                        return $holder;
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
                                ->orWhere('public_structures.english_name', 'LIKE', "%$search%")
                                ->orWhere('public_structures.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('h2o_system_incidents.date', 'LIKE', "%$search%")
                                ->orWhere('incidents.english_name', 'LIKE', "%$search%");
                            });
                        }
                    })
                    ->rawColumns(['action', 'holder']) 
                    ->make(true);
            }
    
            $communities = Community::where('is_archived', 0)
                ->where('water_service', 'Yes')
                ->orderBy('english_name', 'ASC')
                ->get();
            $h2oUsers = DB::table('all_water_holders')
                ->join('households', 'all_water_holders.household_id', '=', 'households.id')
                ->where('all_water_holders.is_archived', 0)
                ->orderBy('households.english_name', 'ASC')
                ->select('households.english_name', 'all_water_holders.id')
                ->get();
    
            $incidents = Incident::where('is_archived', 0)->get();
            $incidentEquipments = IncidentEquipment::where('is_archived', 0)
                ->where("incident_equipment_type_id", 1)
                ->orderBy('name', 'ASC')
                ->get(); 

            $incidentStatuses = IncidentStatus::where('is_archived', 0)->get();
            $h2oIncidentsNumber = H2oSystemIncident::where('is_archived', 0)->count();
            $donors = Donor::where('is_archived', 0)
                ->orderBy('donor_name', 'ASC')
                ->get();
    
            // H2O incidents
            $dataIncidents = DB::table('h2o_system_incidents')
                ->join('communities', 'h2o_system_incidents.community_id', '=', 'communities.id')
                ->join('sub_regions', 'communities.sub_region_id', '=', 'sub_regions.id')
                ->join('incidents', 'h2o_system_incidents.incident_id', '=', 'incidents.id')
                ->join('incident_statuses', 'h2o_system_incidents.incident_status_id', 
                    '=', 'incident_statuses.id')
                ->where('h2o_system_incidents.is_archived', 0)
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
                'incidents', 'incidentStatuses', 'h2oIncidentsNumber', 'donors', 'incidentEquipments'))
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

        //dd($request->all_water_holder_id);

        if($request->date) {

            $waterIncident->date = $request->date;
            $year = explode('-', $request->date);
            $waterIncident->year = $year[0];
        }

        $waterIncident->community_id = $request->community_id[0];
        if($request->public_user == "user") {

            $waterUser = AllWaterHolder::where('household_id', $request->all_water_holder_id)->first();
            $waterIncident->all_water_holder_id = $waterUser->id;
        }

        if($request->public_user == "public") {

            $waterUser = AllWaterHolder::where('public_structure_id', $request->all_water_holder_id)->first();
            $waterIncident->all_water_holder_id = $waterUser->id;
        }

        if($request->public_user == "system") {

            $waterIncident->water_system_id = $request->all_water_holder_id;
        }

        $waterIncident->incident_id = $request->incident_id;
        if($request->incident_id == 4) {

            $waterIncident->order_number = $request->order_number;
            $waterIncident->order_date = $request->order_date;
            $waterIncident->geolocation_lat = $request->geolocation_lat;
            $waterIncident->geolocation_long = $request->geolocation_long;
            $waterIncident->hearing_date = $request->hearing_date;
            $waterIncident->structure_description = $request->structure_description;
        }
        $waterIncident->response_date = $request->response_date;
        $waterIncident->notes = $request->notes;
        $waterIncident->save();
        $id = $waterIncident->id;

        if($request->incident_equipment_id) {
            for($i=0; $i < count($request->incident_equipment_id); $i++) {

                $waterEquipment = new WaterIncidentEquipment();
                $waterEquipment->incident_equipment_id = $request->incident_equipment_id[$i];
                $waterEquipment->h2o_system_incident_id = $id;
                $waterEquipment->save();
            }
        }

        if($request->incident_status_id) {
            for($i=0; $i < count($request->incident_status_id); $i++) {

                $waterStatus = new H2oIncidentStatus();
                $waterStatus->incident_status_id = 
                    $request->incident_status_id[$i];
                $waterStatus->h2o_system_incident_id = $id;
                $waterStatus->save();
            }
        }

        if ($request->file('photos')) {

            foreach($request->photos as $photo) {

                $original_name = $photo->getClientOriginalName();
                $extra_name  = uniqid().'_'.time().'_'.uniqid().'.'.$photo->extension();
                $encoded_base64_image = substr($photo, strpos($photo, ',') + 1); 
                $destinationPath = public_path().'/incidents/water/' ;
                $photo->move($destinationPath, $extra_name);
    
                $h2oIncidentPhoto = new H2oIncidentPhoto();
                $h2oIncidentPhoto->slug = $extra_name;
                $h2oIncidentPhoto->h2o_system_incident_id = $id;
                $h2oIncidentPhoto->save();
            }
        }

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
        if($waterIncident->water_system_id) $waterHolder = WaterSystem::findOrFail($waterIncident->water_system_id);
        else $waterHolder = AllWaterHolder::findOrFail($waterIncident->all_water_holder_id);
        $communities = Community::where('is_archived', 0)
            ->orderBy('english_name', 'ASC')
            ->get();
        $incidents = Incident::where('is_archived', 0)->get();
        $statuses = IncidentStatus::where('is_archived', 0)->get();
        $incidentEquipments = IncidentEquipment::where('is_archived', 0)
            ->where("incident_equipment_type_id", 1)
            ->orderBy('name', 'ASC')
            ->get(); 
        $WaterIncidentEquipments = WaterIncidentEquipment::where('h2o_system_incident_id', $id)
            ->where('is_archived', 0)
            ->get();
        $waterStatuses = DB::table('h2o_incident_statuses')
            ->join('h2o_system_incidents', 'h2o_system_incidents.id', 
                'h2o_incident_statuses.h2o_system_incident_id')
            ->join('incident_statuses', 'h2o_incident_statuses.incident_status_id', 
                'incident_statuses.id')
            ->where('h2o_incident_statuses.h2o_system_incident_id', $id)
            ->where('h2o_incident_statuses.is_archived', 0)
            ->select('h2o_incident_statuses.id', 'incident_statuses.name')
            ->get();
        $waterIncidentPhotos = H2oIncidentPhoto::where('h2o_system_incident_id', $id)
            ->get();

        return view('incidents.water.edit', compact('waterIncident', 'communities', 
            'incidents', 'statuses', 'incidentEquipments', 'WaterIncidentEquipments',
            'waterStatuses', 'waterIncidentPhotos', 'waterHolder'));
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

        if($request->household_id) {

            $waterUser = AllWaterHolder::where('household_id', $request->household_id)->first();
            $waterIncident->all_water_holder_id = $waterUser->id;
        }

        if($request->public_structure_id) {

            $waterUser = AllWaterHolder::where('public_structure_id', $request->public_structure_id)->first();
            $waterIncident->all_water_holder_id = $waterUser->id;
        }

        $waterIncident->incident_id = $request->incident_id;
        if($request->incident_id == 4) {

            $waterIncident->order_number = $request->order_number;
            $waterIncident->order_date = $request->order_date;
            $waterIncident->geolocation_lat = $request->geolocation_lat;
            $waterIncident->geolocation_long = $request->geolocation_long;
            $waterIncident->hearing_date = $request->hearing_date;
            $waterIncident->structure_description = $request->structure_description;
        }
        $waterIncident->equipment = $request->equipment;
        $waterIncident->notes = $request->notes;
        $waterIncident->response_date = $request->response_date;
        $waterIncident->save();

        if($request->new_equipment) {

            for($i=0; $i < count($request->new_equipment); $i++) {

                $fbsEquipment = new WaterIncidentEquipment();
                $fbsEquipment->incident_equipment_id = $request->new_equipment[$i];
                $fbsEquipment->h2o_system_incident_id = $waterIncident->id;
                $fbsEquipment->save();
            }
        }

        if($request->more_equipment) {

            for($i=0; $i < count($request->more_equipment); $i++) {

                $fbsEquipment = new WaterIncidentEquipment();
                $fbsEquipment->incident_equipment_id = $request->more_equipment[$i];
                $fbsEquipment->h2o_system_incident_id = $waterIncident->id;
                $fbsEquipment->save();
            }
        }
        
        if($request->new_statuses) {
            for($i=0; $i < count($request->new_statuses); $i++) {

                $waterStatus = new H2oIncidentStatus();
                $waterStatus->incident_status_id = 
                    $request->new_statuses[$i];
                $waterStatus->h2o_system_incident_id = $id;
                $waterStatus->save();
            }
        }

        if($request->more_statuses) {
            for($i=0; $i < count($request->more_statuses); $i++) {

                $waterStatus = new H2oIncidentStatus();
                $waterStatus->incident_status_id = 
                    $request->more_statuses[$i];
                $waterStatus->h2o_system_incident_id = $id;
                $waterStatus->save();
            }
        }

        if ($request->file('new_photos')) {

            foreach($request->new_photos as $photo) {

                $original_name = $photo->getClientOriginalName();
                $extra_name  = uniqid().'_'.time().'_'.uniqid().'.'.$photo->extension();
                $encoded_base64_image = substr($photo, strpos($photo, ',') + 1); 
                $destinationPath = public_path().'/incidents/water/' ;
                $photo->move($destinationPath, $extra_name);
    
                $waterIncidentPhoto = new H2oIncidentPhoto();
                $waterIncidentPhoto->slug = $extra_name;
                $waterIncidentPhoto->h2o_system_incident_id = $id;
                $waterIncidentPhoto->save();
            }
        }

        if ($request->file('more_photos')) {

            foreach($request->more_photos as $photo) {

                $original_name = $photo->getClientOriginalName();
                $extra_name  = uniqid().'_'.time().'_'.uniqid().'.'.$photo->extension();
                $encoded_base64_image = substr($photo, strpos($photo, ',') + 1); 
                $destinationPath = public_path().'/incidents/water/' ;
                $photo->move($destinationPath, $extra_name);
    
                $waterIncidentPhoto = new H2oIncidentPhoto();
                $waterIncidentPhoto->slug = $extra_name;
                $waterIncidentPhoto->h2o_system_incident_id = $id;
                $waterIncidentPhoto->save();
            }
        }

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

        if($waterIncident->water_system_id) $waterHolder = WaterSystem::findOrFail($waterIncident->water_system_id);
        else $waterHolder = AllWaterHolder::findOrFail($waterIncident->all_water_holder_id);

        $h2oUser = H2oUser::where('household_id', $waterHolder->household_id)->get();
        $h2oPublic = H2oPublicStructure::where('public_structure_id', $waterHolder->public_structure_id)->get();
        $gridPublic = GridPublicStructure::where('public_structure_id', $waterHolder->public_structure_id)->get();
        $gridUser = GridUser::where('household_id', $waterHolder->household_id)->get();
        $community = Community::where('id', $waterIncident->community_id)->first();
        $incident = Incident::where('id', $waterIncident->incident_id)->first();
        $waterStatus = IncidentStatus::where('id', $waterIncident->incident_status_id)->first();
        $waterIncidentEquipments = DB::table('water_incident_equipment')
            ->join('incident_equipment', 'water_incident_equipment.incident_equipment_id', 
                '=', 'incident_equipment.id')
            ->join('h2o_system_incidents', 'water_incident_equipment.h2o_system_incident_id', 
                '=', 'h2o_system_incidents.id')
            ->where('water_incident_equipment.h2o_system_incident_id', $id)
            ->where('water_incident_equipment.is_archived', 0)
            ->get();

        $waterStatuses = DB::table('h2o_incident_statuses')
            ->join('h2o_system_incidents', 'h2o_system_incidents.id', 
                'h2o_incident_statuses.h2o_system_incident_id')
            ->join('incident_statuses', 'h2o_incident_statuses.incident_status_id', 
                'incident_statuses.id')
            ->where('h2o_incident_statuses.h2o_system_incident_id', $id)
            ->where('h2o_incident_statuses.is_archived', 0)
            ->get();
        $waterIncidentPhotos = H2oIncidentPhoto::where('h2o_system_incident_id', $id)
            ->get();
 
        return view('incidents.water.show', compact('waterIncident', 'community', 
            'incident', 'waterHolder', 'waterStatus', 'waterIncidentEquipments',
            'waterStatuses', 'waterIncidentPhotos', 'h2oUser', 'gridUser', 'h2oPublic',
            'gridPublic'));
    }

     /**
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteWaterIncidentPhoto(Request $request)
    {
        $id = $request->id;

        $waterPhoto = H2oIncidentPhoto::find($id);

        if($waterPhoto) {

            $waterPhoto->delete();
            
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
    public function deleteWaterIncidentStatus(Request $request)
    {
        $id = $request->id;

        $waterStatus = H2oIncidentStatus::find($id);

        if($waterStatus) {

            $waterStatus->is_archived = 1;
            $waterStatus->save();
            
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
    public function deleteWaterIncident(Request $request)
    {
        $id = $request->id;

        $waterIncident = H2oSystemIncident::find($id);

        if($waterIncident) {

            $waterIncident->is_archived = 1;
            $waterIncident->save();
            
            $response['success'] = 1;
            $response['msg'] = 'Water Incident Deleted successfully'; 
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
    public function deleteWaterIncidentEquipment(Request $request)
    {
        $id = $request->id;

        $waterEquipment = WaterIncidentEquipment::find($id);

        if($waterEquipment) {

            $waterEquipment->is_archived = 1;
            $waterEquipment->save();
            
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

            return Excel::download(new WaterIncidentExport($request), 'water_incidents.xlsx');
        }       
    }
}
