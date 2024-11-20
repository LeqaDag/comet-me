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
use App\Models\AllEnergyMeter;
use App\Models\Community;
use App\Models\CommunityDonor;
use App\Models\Donor;
use App\Models\CameraCommunityType;
use App\Models\CameraCommunity;
use App\Models\NvrCommunityType;
use App\Models\CameraCommunityPhoto;
use App\Models\Incident;
use App\Models\IncidentEquipment;
use App\Models\IncidentStatusMgSystem;
use App\Models\Region;
use App\Models\CameraIncident;
use App\Models\CameraIncidentEquipment;
use App\Models\CameraIncidentPhoto;
use App\Models\InternetIncidentStatus;
use App\Exports\CameraIncidentExport;
use App\Exports\AllIncidentExport;
use Carbon\Carbon;
use Intervention\Image\Facades\Image;
use DataTables;
use Excel;

class CameraIncidentController extends Controller
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

                $data = DB::table('camera_incidents')
                    ->leftJoin('communities', 'camera_incidents.community_id', 'communities.id')
                    ->leftJoin('repositories', 'camera_incidents.repository_id', 'repositories.id')
                    ->join('incidents', 'camera_incidents.incident_id', 'incidents.id')
                    ->join('internet_incident_statuses', 'camera_incidents.internet_incident_status_id', 
                        'internet_incident_statuses.id')
                    ->where('camera_incidents.is_archived', 0);
    
                if($communityFilter != null) {

                    $data->where('communities.id', $communityFilter);
                }
                if ($typeFilter != null) {

                    $data->where('camera_incidents.incident_id', $typeFilter);
                }
                if ($dateFilter != null) {

                    $data->where('camera_incidents.date', '>=', $dateFilter);
                }

                $data
                ->select('camera_incidents.date', 'camera_incidents.year',
                    'camera_incidents.id as id', 'camera_incidents.created_at as created_at', 
                    'camera_incidents.updated_at as updated_at', 
                    DB::raw('IFNULL(communities.english_name, repositories.name) 
                        as holder'),
                    'incidents.english_name as incident',
                    'internet_incident_statuses.name as status',
                    'camera_incidents.notes')
                ->latest(); 

                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
    
                        $viewButton = "<a type='button' class='viewCameraIncident' data-id='".$row->id."'><i class='fa-solid fa-eye text-info'></i></a>";
                        $updateButton = "<a type='button' class='updateCameraIncident' data-id='".$row->id."'><i class='fa-solid fa-pen-to-square text-success'></i></a>";
                        $deleteButton = "<a type='button' class='deleteCameraIncident' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";
                        
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
                                ->orWhere('repositories.name', 'LIKE', "%$search%")
                                ->orWhere('internet_incident_statuses.name', 'LIKE', "%$search%")
                                ->orWhere('camera_incidents.date', 'LIKE', "%$search%")
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
            $cameraCommunities = CameraCommunity::where('is_archived', 0)
                ->get();

            $installedCommunityCameras = DB::table('camera_communities')
                ->join('communities', 'camera_communities.community_id', 'communities.id')
                ->where('camera_communities.is_archived', 0)
                ->select('communities.english_name', 'communities.id')
                ->get();

            $incidents = Incident::where('is_archived', 0)->get();
            $mgIncidentsNumber = Incident::where('is_archived', 0)->count();
            $donors = Donor::where('is_archived', 0)
                ->orderBy('donor_name', 'ASC') 
                ->get();
    
            $dataIncidents = DB::table('camera_incidents')
                ->leftJoin('communities', 'camera_incidents.community_id', 'communities.id')
                ->join('sub_regions', 'communities.sub_region_id', 'sub_regions.id')
                ->join('incidents', 'camera_incidents.incident_id', 'incidents.id')
                ->join('internet_incident_statuses', 'camera_incidents.internet_incident_status_id', 
                    'internet_incident_statuses.id')
                ->where('camera_incidents.is_archived', 0)
                ->select(
                    DB::raw('internet_incident_statuses.name as name'),
                    DB::raw('count(*) as number'))
                ->groupBy('internet_incident_statuses.name')
                ->get();
                
            $arrayIncidents[] = ['English Name', 'Number'];
            
            foreach($dataIncidents as $key => $value) {
    
                $arrayIncidents[++$key] = [$value->name, $value->number];
            }
    
            $incidentEquipments = IncidentEquipment::where('is_archived', 0)
                ->where("incident_equipment_type_id", 5)
                ->orderBy('name', 'ASC')
                ->get(); 

            $households = DB::table('all_energy_meters')
                ->join("households", "all_energy_meters.household_id", "households.id")
                ->select("households.id", "households.english_name")
                ->get();

            $installedRepositoryCameras = DB::table('camera_communities')
                ->join('repositories', 'camera_communities.repository_id', 'repositories.id')
                ->where('camera_communities.is_archived', 0)
                ->select('repositories.name', 'repositories.id')
                ->get();

            $internetIncidentStatuses = InternetIncidentStatus::where('is_archived', 0)->get();

            return view('incidents.camera.index', compact('communities', 'cameraCommunities',
                'incidents', 'mgIncidentsNumber', 'donors', 'installedCommunityCameras',
                'incidentEquipments', 'households', 'installedRepositoryCameras', 'internetIncidentStatuses'))
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
        $cameraIncident = new CameraIncident();
   
        if($request->date) {

            $cameraIncident->date = $request->date;
            $year = explode('-', $request->date);
            $cameraIncident->year = $year[0];
        }

        if($request->community_id) $cameraIncident->community_id = $request->community_id;  
        if($request->repository_id) $cameraIncident->repository_id = $request->repository_id;  

        $cameraIncident->incident_id = $request->incident_id;
        if($request->incident_id == 4) {

            $cameraIncident->order_number = $request->order_number;
            $cameraIncident->order_date = $request->order_date;
            $cameraIncident->geolocation_lat = $request->geolocation_lat;
            $cameraIncident->geolocation_long = $request->geolocation_long;
            $cameraIncident->hearing_date = $request->hearing_date;
            $cameraIncident->structure_description = $request->structure_description;
            $cameraIncident->building_permit_request_number = $request->building_permit_request_number;
            $cameraIncident->building_permit_request_submission_date = $request->building_permit_request_submission_date;
            $cameraIncident->illegal_construction_case_number = $request->illegal_construction_case_number;
            $cameraIncident->district_court_case_number = $request->district_court_case_number;
            $cameraIncident->supreme_court_case_number = $request->supreme_court_case_number;
            $cameraIncident->case_chronology = $request->case_chronology;
        }
        $cameraIncident->internet_incident_status_id = $request->internet_incident_status_id;
        if($request->response_date) $cameraIncident->response_date = $request->response_date;
        $cameraIncident->monetary_losses = $request->monetary_losses;
        $cameraIncident->notes = $request->notes;
        $cameraIncident->save();

        if($request->incident_equipment_id) {
            for($i=0; $i < count($request->incident_equipment_id); $i++) {

                $cameraIncidentEquipment = new CameraIncidentEquipment();
                $cameraIncidentEquipment->incident_equipment_id = $request->incident_equipment_id[$i];
                $cameraIncidentEquipment->camera_incident_id = $cameraIncident->id;
                $cameraIncidentEquipment->save();
            }
        }

        if ($request->file('photos')) {

            foreach($request->photos as $photo) {

                $original_name = $photo->getClientOriginalName();
                $extra_name  = uniqid().'_'.time().'_'.uniqid().'.'.$photo->extension();
                $encoded_base64_image = substr($photo, strpos($photo, ',') + 1); 
                $destinationPath = public_path().'/incidents/camera/' ;
                $photo->move($destinationPath, $extra_name);
    
                $cameraIncidentPhoto = new CameraIncidentPhoto();
                $cameraIncidentPhoto->slug = $extra_name;
                $cameraIncidentPhoto->camera_incident_id = $cameraIncident->id;
                $cameraIncidentPhoto->save();
            }
        }

        return redirect()->back()
        ->with('message', 'New Camera Incident Added Successfully!');
    }

    /**
     * Show the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cameraIncident = CameraIncident::findOrFail($id);
        $cameraCommunity = 0;
        $cameraRepository = 0;
        if($cameraIncident->community_id) $cameraCommunity = CameraCommunity::where("community_id", $cameraIncident->community_id)->first();
        if($cameraIncident->repository_id) $cameraRepository = CameraCommunity::where("repository_id", $cameraIncident->repository_id)->first();
        $incident = Incident::where('id', $cameraIncident->incident_id)->first();
        $cameraStatus = InternetIncidentStatus::where('id', $cameraIncident->internet_incident_status_id)
            ->first();
        $cameraIncidentEquipments = DB::table('camera_incident_equipment')
            ->join('incident_equipment', 'camera_incident_equipment.incident_equipment_id', 
                'incident_equipment.id')
            ->join('camera_incidents', 'camera_incident_equipment.camera_incident_id', 
                'camera_incidents.id')
            ->where('camera_incident_equipment.camera_incident_id', $id)
            ->where('camera_incident_equipment.is_archived', 0)
            ->get();

        $cameraIncidentPhotos = CameraIncidentPhoto::where('camera_incident_id', $id)
            ->get();

        return view('incidents.camera.show', compact('cameraIncident', 'incident', 'cameraStatus', 'cameraIncidentEquipments', 
            'cameraIncidentPhotos', 'cameraCommunity', 'cameraRepository'));
    }

    /**
     * View Edit page. 
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function editPage($id)
    {
        $mgIncident = CameraIncident::findOrFail($id);

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
        $cameraIncident = CameraIncident::findOrFail($id); 
        $incidents = Incident::where('is_archived', 0)->get();
        $internetIncidentStatuses = InternetIncidentStatus::where('is_archived', 0)->get();
        $cameraIncidentEquipments = CameraIncidentEquipment::where('camera_incident_id', $id)
            ->where('is_archived', 0)
            ->get();
        $incidentEquipments = IncidentEquipment::where('is_archived', 0)
            ->where("incident_equipment_type_id", 5)
            ->orderBy('name', 'ASC')
            ->get(); 

        $cameraIncidentPhotos = CameraIncidentPhoto::where('camera_incident_id', $id)
            ->get();

        return view('incidents.camera.edit', compact('cameraIncident', 'incidents', 'internetIncidentStatuses', 
            'incidentEquipments', 'cameraIncidentEquipments', 'cameraIncidentPhotos'));
    }

    /**
     * Update an existing resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request, int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $cameraIncident = CameraIncident::findOrFail($id);

        if($request->date) {

            $cameraIncident->date = $request->date;
            $year = explode('-', $request->date);
            $cameraIncident->year = $year[0];
        }

        $cameraIncident->incident_id = $request->incident_id;
        if($request->incident_id == 4) {

            $cameraIncident->order_number = $request->order_number;
            $cameraIncident->order_date = $request->order_date;
            $cameraIncident->geolocation_lat = $request->geolocation_lat;
            $cameraIncident->geolocation_long = $request->geolocation_long;
            $cameraIncident->hearing_date = $request->hearing_date;
            $cameraIncident->structure_description = $request->structure_description;
            $cameraIncident->building_permit_request_number = $request->building_permit_request_number;
            $cameraIncident->building_permit_request_submission_date = $request->building_permit_request_submission_date;
            $cameraIncident->illegal_construction_case_number = $request->illegal_construction_case_number;
            $cameraIncident->district_court_case_number = $request->district_court_case_number;
            $cameraIncident->supreme_court_case_number = $request->supreme_court_case_number;
            $cameraIncident->case_chronology = $request->case_chronology;
        }
        $cameraIncident->internet_incident_status_id = $request->internet_incident_status_id;
        $cameraIncident->response_date = $request->response_date;
        if($request->monetary_losses) $cameraIncident->monetary_losses = $request->monetary_losses;
        $cameraIncident->notes = $request->notes;
        $cameraIncident->save();

        if($request->new_equipment) {
 
            for($i=0; $i < count($request->new_equipment); $i++) {

                $cameraEquipment = new CameraIncidentEquipment();
                $cameraEquipment->incident_equipment_id = $request->new_equipment[$i];
                $cameraEquipment->camera_incident_id = $cameraIncident->id;
                $cameraEquipment->save();
            }
        }

        if($request->more_equipment) {

            for($i=0; $i < count($request->more_equipment); $i++) {

                $cameraEquipment = new CameraIncidentEquipment();
                $cameraEquipment->incident_equipment_id = $request->more_equipment[$i];
                $cameraEquipment->camera_incident_id = $cameraIncident->id;
                $cameraEquipment->save();
            }
        }

        if ($request->file('more_photos')) {

            foreach($request->more_photos as $photo) {

                $original_name = $photo->getClientOriginalName();
                $extra_name  = uniqid().'_'.time().'_'.uniqid().'.'.$photo->extension();
                $encoded_base64_image = substr($photo, strpos($photo, ',') + 1); 
                $destinationPath = public_path().'/incidents/camera/' ;
                $photo->move($destinationPath, $extra_name);
    
                $cameraIncidentPhoto = new CameraIncidentPhoto();
                $cameraIncidentPhoto->slug = $extra_name;
                $cameraIncidentPhoto->camera_incident_id = $cameraIncident->id;
                $cameraIncidentPhoto->save();
            }
        }

        if ($request->file('new_photos')) {

            foreach($request->new_photos as $photo) {

                $original_name = $photo->getClientOriginalName();
                $extra_name  = uniqid().'_'.time().'_'.uniqid().'.'.$photo->extension();
                $encoded_base64_image = substr($photo, strpos($photo, ',') + 1); 
                $destinationPath = public_path().'/incidents/mg/' ;
                $photo->move($destinationPath, $extra_name);
    
                $cameraIncidentPhoto = new CameraIncidentPhoto();
                $cameraIncidentPhoto->slug = $extra_name;
                $cameraIncidentPhoto->camera_incident_id = $cameraIncident->id;
                $cameraIncidentPhoto->save();
            }
        }

        return redirect('/incident-camera')->with('message', 'Camera Incident Updated Successfully!');
    }

     /**
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteCameraIncidentPhoto(Request $request)
    {
        $id = $request->id;

        $cameraPhoto = CameraIncidentPhoto::find($id);

        if($cameraPhoto) {

            $cameraPhoto->delete();
            
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
    public function deleteCameraIncident(Request $request)
    {
        $id = $request->id;

        $cameraIncident = CameraIncident::find($id);

        if($cameraIncident) {

            $cameraIncident->is_archived = 1;
            $cameraIncident->save();
            
            $response['success'] = 1;
            $response['msg'] = 'Camera Incident Deleted successfully'; 
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
    public function deleteCameraIncidentEquipment(Request $request)
    {
        $id = $request->id;

        $cameraEquipment = CameraIncidentEquipment::find($id);

        if($cameraEquipment) {

            $cameraEquipment->delete();
            
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

            return Excel::download(new CameraIncidentExport($request), 'camera_incidents.xlsx');
        }   
    }
}
