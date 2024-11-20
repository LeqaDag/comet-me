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
use App\Models\InternetUser;
use App\Models\InternetUserDonor;
use App\Models\InternetNetworkIncident;
use App\Models\InternetIncidentStatus;
use App\Models\InternetNetworkIncidentEquipment;
use App\Models\InternetNetworkIncidentPhoto;
use App\Models\IncidentEquipment;
use App\Models\InternetNetworkAffectedHousehold;
use App\Models\InternetNetworkAffectedArea;
use App\Models\Incident;
use App\Models\Household;
use App\Models\Region;
use App\Exports\InternetNetworkIncidentExport;
use App\Exports\AllIncidentExport;
use Carbon\Carbon;
use Intervention\Image\Facades\Image;
use DataTables;
use Excel;

class InternetNetworkIncidentController extends Controller
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

                $data = DB::table('internet_network_incidents')
                    ->join('communities', 'internet_network_incidents.community_id', '=', 'communities.id')
                    ->join('incidents', 'internet_network_incidents.incident_id', '=', 'incidents.id')
                    ->join('internet_incident_statuses', 
                        'internet_network_incidents.internet_incident_status_id', 
                        '=', 'internet_incident_statuses.id')
                    ->where('internet_network_incidents.is_archived', 0);
    
                if($communityFilter != null) {

                    $data->where('communities.id', $communityFilter);
                }
                if ($typeFilter != null) {

                    $data->where('internet_network_incidents.incident_id', $typeFilter);
                }
                if ($dateFilter != null) {

                    $data->where('internet_network_incidents.date', '>=', $dateFilter);
                }

                $data->select(
                    'internet_network_incidents.date', 'internet_network_incidents.year',
                    'internet_network_incidents.id as id', 'internet_network_incidents.created_at as created_at', 
                    'internet_network_incidents.updated_at as updated_at', 
                    'communities.english_name as community_name', 
                    'incidents.english_name as incident', 
                    'internet_incident_statuses.name',
                    'internet_network_incidents.notes'
                )->orderBy('internet_network_incidents.date', 'desc'); 

                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
    
                        $viewButton = "<a type='button' class='viewInternetNetworkIncident' data-id='".$row->id."'><i class='fa-solid fa-eye text-info'></i></a>";
                        $updateButton = "<a type='button' class='updateInternetNetworkIncident' data-id='".$row->id."'><i class='fa-solid fa-pen-to-square text-success'></i></a>";
                        $deleteButton = "<a type='button' class='deleteInternetNetworkIncident' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";
                        
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
                                ->orWhere('internet_incident_statuses.name', 'LIKE', "%$search%")
                                ->orWhere('internet_network_incidents.date', 'LIKE', "%$search%")
                                ->orWhere('incidents.english_name', 'LIKE', "%$search%");
                            });
                        }
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
    
            $communities = Community::where('is_archived', 0)
                ->where('internet_service', 'yes')
                ->orderBy('english_name', 'ASC')
                ->get();
            $incidents = Incident::where('is_archived', 0)->get();
            $internetIncidentStatuses = InternetIncidentStatus::where('is_archived', 0)->get();
            $incidentEquipments = IncidentEquipment::where('is_archived', 0)
                ->where("incident_equipment_type_id", 4)
                ->orderBy('name', 'ASC')
                ->get(); 
            $donors = Donor::where('is_archived', 0)
                ->orderBy('donor_name', 'ASC')
                ->get();

            $households =  DB::table('internet_users')
                ->join('households', 'internet_users.household_id', 
                    '=', 'households.id')
                ->select('households.id', 'households.english_name')
                ->get();

            return view('incidents.internet.network.index', compact('communities',
                'incidents', 'internetIncidentStatuses', 'donors', 'incidentEquipments',
                'households'));
                
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
        $internetNetworkIncident = new InternetNetworkIncident();

        if($request->date) {

            $internetNetworkIncident->date = $request->date;
            $year = explode('-', $request->date);
            $internetNetworkIncident->year = $year[0];
        }

        $internetNetworkIncident->community_id = $request->community_id;
        $internetNetworkIncident->incident_id = $request->incident_id;
        if($request->incident_id == 4) {

            $internetNetworkIncident->order_number = $request->order_number;
            $internetNetworkIncident->order_date = $request->order_date;
            $internetNetworkIncident->geolocation_lat = $request->geolocation_lat;
            $internetNetworkIncident->geolocation_long = $request->geolocation_long;
            $internetNetworkIncident->hearing_date = $request->hearing_date;
            $internetNetworkIncident->structure_description = $request->structure_description;
            $internetNetworkIncident->building_permit_request_number = $request->building_permit_request_number;
            $internetNetworkIncident->building_permit_request_submission_date = $request->building_permit_request_submission_date;
            $internetNetworkIncident->illegal_construction_case_number = $request->illegal_construction_case_number;
            $internetNetworkIncident->district_court_case_number = $request->district_court_case_number;
            $internetNetworkIncident->supreme_court_case_number = $request->supreme_court_case_number;
            $internetNetworkIncident->case_chronology = $request->case_chronology;
        }
        $internetNetworkIncident->internet_incident_status_id = $request->internet_incident_status_id;
        $internetNetworkIncident->response_date = $request->response_date;
        $internetNetworkIncident->notes = $request->notes;
        $internetNetworkIncident->next_step = $request->next_step;
        $internetNetworkIncident->monetary_losses = $request->monetary_losses;
        $internetNetworkIncident->save();
        $id = $internetNetworkIncident->id;

        if($request->affected_community_id) {
            for($i=0; $i < count($request->affected_community_id); $i++) {

                $affectedArea = new InternetNetworkAffectedArea();
                $affectedArea->affected_community_id = $request->affected_community_id[$i];
                $affectedArea->internet_network_incident_id = $id;
                $affectedArea->save();
            }
        }

        if($request->household_id) {
            for($i=0; $i < count($request->household_id); $i++) {

                $affectedHousehold = new InternetNetworkAffectedHousehold();
                $affectedHousehold->household_id = $request->household_id[$i];
                $affectedHousehold->internet_network_incident_id = $id;
                $affectedHousehold->save();
            }
        }

        if($request->incident_equipment_id) {
            for($i=0; $i < count($request->incident_equipment_id); $i++) {

                $internetEquipment = new InternetNetworkIncidentEquipment();
                $internetEquipment->incident_equipment_id = $request->incident_equipment_id[$i];
                $internetEquipment->internet_network_incident_id = $id;
                $internetEquipment->save();
            }
        }

        if ($request->file('photos')) {

            foreach($request->photos as $photo) {

                $original_name = $photo->getClientOriginalName();
                $extra_name  = uniqid().'_'.time().'_'.uniqid().'.'.$photo->extension();
                $encoded_base64_image = substr($photo, strpos($photo, ',') + 1); 
                $destinationPath = public_path().'/incidents/internet/' ;
                $photo->move($destinationPath, $extra_name);
    
                $internetIncidentPhoto = new InternetNetworkIncidentPhoto();
                $internetIncidentPhoto->slug = $extra_name;
                $internetIncidentPhoto->internet_network_incident_id = $id;
                $internetIncidentPhoto->save();
            }
        }

        return redirect()->back()
            ->with('message', 'New Network Incident Added Successfully!');
    }

    /**
     * Show the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $networkIncident = InternetNetworkIncident::findOrFail($id);

        $community = Community::where('id', $networkIncident->community_id)->first();
        $incident = Incident::where('id', $networkIncident->incident_id)->first();
        $internetStatus = InternetIncidentStatus::where('id', 
            $networkIncident->internet_incident_status_id)->first();

        $networkIncidentEquipments = InternetNetworkIncidentEquipment::where('internet_network_incident_id', 
            $id)->get();

        $networkIncidentPhotos = InternetNetworkIncidentPhoto::where('internet_network_incident_id', 
            $id)->get();

        $affectedHouseholds = InternetNetworkAffectedHousehold::where('internet_network_incident_id', 
            $id)->get();

        $affectedAreas = InternetNetworkAffectedArea::where('internet_network_incident_id', $id)
            ->get();

        return view('incidents.internet.network.show', compact('networkIncident', 'community', 
            'incident', 'internetStatus', 'networkIncidentEquipments', 'networkIncidentPhotos',
            'affectedHouseholds', 'affectedAreas'));
    }

    /**
     * View Edit page.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) 
    {
        $networkIncident = InternetNetworkIncident::findOrFail($id);
        $communities = Community::where('is_archived', 0)
            ->where('internet_service', 'yes')
            ->orderBy('english_name', 'ASC')
            ->get();
        $incidents = Incident::where('is_archived', 0)->get();
        $internetIncidentStatuses = InternetIncidentStatus::where('is_archived', 0)->get();
        $incidentEquipments = IncidentEquipment::where('is_archived', 0)
            ->where("incident_equipment_type_id", 4)
            ->orderBy('name', 'ASC')
            ->get(); 

        $internetIncidentEquipments = InternetNetworkIncidentEquipment::where('internet_network_incident_id', $id)
            ->get();

        $internetIncidentPhotos = InternetNetworkIncidentPhoto::where('internet_network_incident_id', $id)
            ->get();

        $affectedAreas = InternetNetworkAffectedArea::where('internet_network_incident_id', $id)
            ->get();

        $affectedHouseholds = InternetNetworkAffectedHousehold::where('internet_network_incident_id', $id)
            ->get();

        $households =  DB::table('internet_users')
            ->join('households', 'internet_users.household_id', 
                '=', 'households.id')
            ->select('households.id', 'households.english_name')
            ->get();

        return view('incidents.internet.network.edit', compact('networkIncident', 'communities', 
            'incidents', 'internetIncidentStatuses', 'internetIncidentEquipments', 
            'incidentEquipments', 'internetIncidentPhotos', 'affectedAreas',
            'affectedHouseholds', 'households'));
    }

    /**
     * Update an existing resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request, int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $networkIncident = InternetNetworkIncident::findOrFail($id);

        if($request->date) {

            $networkIncident->date = $request->date;
            $year = explode('-', $request->date);
            $networkIncident->year = $year[0];
        }

        $networkIncident->incident_id = $request->incident_id;
        if($request->incident_id == 4) {

            $networkIncident->order_number = $request->order_number;
            $networkIncident->order_date = $request->order_date;
            $networkIncident->geolocation_lat = $request->geolocation_lat;
            $networkIncident->geolocation_long = $request->geolocation_long;
            $networkIncident->hearing_date = $request->hearing_date;
            $networkIncident->structure_description = $request->structure_description;
            $networkIncident->building_permit_request_number = $request->building_permit_request_number;
            $networkIncident->building_permit_request_submission_date = $request->building_permit_request_submission_date;
            $networkIncident->illegal_construction_case_number = $request->illegal_construction_case_number;
            $networkIncident->district_court_case_number = $request->district_court_case_number;
            $networkIncident->supreme_court_case_number = $request->supreme_court_case_number;
            $networkIncident->case_chronology = $request->case_chronology;
        }
        $networkIncident->internet_incident_status_id = $request->internet_incident_status_id;
        $networkIncident->notes = $request->notes;
        $networkIncident->next_step = $request->next_step;
        if($request->response_date == null) $networkIncident->response_date = null;
        if($request->response_date) $networkIncident->response_date = $request->response_date;
        if($request->monetary_losses) $networkIncident->monetary_losses = $request->monetary_losses;
        $networkIncident->save();

        if($request->new_affected_households) {
 
            for($i=0; $i < count($request->new_affected_households); $i++) {

                $networkAffectedHousehold = new InternetNetworkAffectedHousehold();
                $networkAffectedHousehold->household_id = $request->new_affected_households[$i];
                $networkAffectedHousehold->internet_network_incident_id = $networkIncident->id;
                $networkAffectedHousehold->save();
            }
        }

        if($request->more_affected_households) {

            for($i=0; $i < count($request->more_affected_households); $i++) {

                $networkAffectedHousehold = new InternetNetworkAffectedHousehold();
                $networkAffectedHousehold->household_id = $request->more_affected_households[$i];
                $networkAffectedHousehold->internet_network_incident_id = $networkIncident->id;
                $networkAffectedHousehold->save();
            }
        }

        if($request->new_affected_areas) {
 
            for($i=0; $i < count($request->new_affected_areas); $i++) {

                $networkAffectedArea = new InternetNetworkAffectedArea();
                $networkAffectedArea->affected_community_id = $request->new_affected_areas[$i];
                $networkAffectedArea->internet_network_incident_id = $networkIncident->id;
                $networkAffectedArea->save();
            }
        }

        if($request->more_affected_areas) {

            for($i=0; $i < count($request->more_affected_areas); $i++) {

                $networkAffectedArea = new InternetNetworkAffectedArea();
                $networkAffectedArea->affected_community_id = $request->more_affected_areas[$i];
                $networkAffectedArea->internet_network_incident_id = $networkIncident->id;
                $networkAffectedArea->save();
            }
        }

        if($request->new_equipment) {
 
            for($i=0; $i < count($request->new_equipment); $i++) {

                $internetEquipment = new InternetNetworkIncidentEquipment();
                $internetEquipment->incident_equipment_id = $request->new_equipment[$i];
                $internetEquipment->internet_network_incident_id = $networkIncident->id;
                $internetEquipment->save();
            }
        }

        if($request->more_equipment) {

            for($i=0; $i < count($request->more_equipment); $i++) {

                $internetEquipment = new InternetNetworkIncidentEquipment();
                $internetEquipment->incident_equipment_id = $request->more_equipment[$i];
                $internetEquipment->internet_network_incident_id = $networkIncident->id;
                $internetEquipment->save();
            }
        }

        if ($request->file('more_photos')) {

            foreach($request->more_photos as $photo) {

                $original_name = $photo->getClientOriginalName();
                $extra_name  = uniqid().'_'.time().'_'.uniqid().'.'.$photo->extension();
                $encoded_base64_image = substr($photo, strpos($photo, ',') + 1); 
                $destinationPath = public_path().'/incidents/internet/' ;
                $photo->move($destinationPath, $extra_name);
    
                $internetIncidentPhoto = new InternetNetworkIncidentPhoto();
                $internetIncidentPhoto->slug = $extra_name;
                $internetIncidentPhoto->internet_network_incident_id = $networkIncident->id;
                $internetIncidentPhoto->save();
            }
        }

        if ($request->file('new_photos')) {

            foreach($request->new_photos as $photo) {

                $original_name = $photo->getClientOriginalName();
                $extra_name  = uniqid().'_'.time().'_'.uniqid().'.'.$photo->extension();
                $encoded_base64_image = substr($photo, strpos($photo, ',') + 1); 
                $destinationPath = public_path().'/incidents/internet/' ;
                $photo->move($destinationPath, $extra_name);
    
                $internetIncidentPhoto = new InternetNetworkIncidentPhoto();
                $internetIncidentPhoto->slug = $extra_name;
                $internetIncidentPhoto->internet_network_incident_id = $networkIncident->id;
                $internetIncidentPhoto->save();
            }
        }

        return redirect('/incident-network')
            ->with('message', 'Internet Network Incident Updated Successfully!');
    }

    /**
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteInternetNetworkIncident(Request $request)
    {
        $id = $request->id;

        $internetIncident = InternetNetworkIncident::find($id);

        if($internetIncident) {

            $internetIncident->is_archived = 1;
            $internetIncident->save();
            
            $response['success'] = 1;
            $response['msg'] = 'Internet Nework Incident Deleted successfully'; 
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
    public function deleteAreaAffected(Request $request)
    {
        $id = $request->id;

        $networkArea = InternetNetworkAffectedArea::find($id);

        if($networkArea) {

            $networkArea->delete();
            
            $response['success'] = 1;
            $response['msg'] = 'Affected Area Deleted successfully'; 
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
    public function deleteAffectedHousehold(Request $request)
    {
        $id = $request->id;

        $networkHousehold = InternetNetworkAffectedHousehold::find($id);

        if($networkHousehold) {

            $networkHousehold->delete();
            
            $response['success'] = 1;
            $response['msg'] = 'Household Affected Deleted successfully'; 
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
    public function deleteNetworkPhoto(Request $request)
    {
        $id = $request->id;

        $networkPhoto = InternetNetworkIncidentPhoto::find($id);

        if($networkPhoto) {

            $networkPhoto->delete();
            
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
    public function deleteNetworkEquipment(Request $request)
    {
        $id = $request->id;

        $internetEquipment = InternetNetworkIncidentEquipment::find($id);

        if($internetEquipment) {

            $internetEquipment->delete();
            
            $response['success'] = 1;
            $response['msg'] = 'Equipment Deleted successfully'; 
        } else {

            $response['success'] = 0;
            $response['msg'] = 'Invalid ID.';
        }

        return response()->json($response); 
    }

    /**
     * Get internet users by community_id.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getInternetUsersByCommunity(Request $request)
    {
        $internetUsers = DB::table('internet_users')
            ->join('communities', 'internet_users.community_id', 'communities.id')
            ->join('households', 'internet_users.household_id', 'households.id')
            ->where('internet_users.community_id', $request->community_id)
            ->where('internet_users.is_archived', 0)
            ->orderBy('households.english_name', 'ASC')
            ->select('households.id as id', 'households.english_name')
            ->get();

        if (!$request->community_id) {

            $html = '<option value="">Choose One...</option>';
        } else {

            $html = '<option selected>Choose One...</option>';
            $internetUsers = DB::table('internet_users')
                ->join('communities', 'internet_users.community_id', 'communities.id')
                ->join('households', 'internet_users.household_id', 'households.id')
                ->where('internet_users.community_id', $request->community_id)
                ->where('internet_users.is_archived', 0)
                ->orderBy('households.english_name', 'ASC')
                ->select('households.id as id', 'households.english_name')
                ->get();

            foreach ($internetUsers as $internetUser) {
                $html .= '<option value="'.$internetUser->id.'">'.$internetUser->english_name.'</option>';
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
        if($request->type == "all") {

            return Excel::download(new AllIncidentExport($request), 'all_incidents.xlsx');
        }  else {

            return Excel::download(new InternetNetworkIncidentExport($request), 'internet_network_incidents.xlsx');
        }
    }
}
