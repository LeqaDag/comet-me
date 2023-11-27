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
use App\Models\InternetUser;
use App\Models\InternetUserDonor;
use App\Models\InternetUserIncident;
use App\Models\InternetIncidentStatus;
use App\Models\InternetUserIncidentEquipment;
use App\Models\InternetUserIncidentPhoto;
use App\Models\IncidentEquipment;
use App\Models\Incident;
use App\Models\Household;
use App\Models\Region;
use App\Exports\InternetUserIncidentExport;
use Carbon\Carbon;
use Intervention\Image\Facades\Image;
use DataTables;
use Excel;

class InternetUserIncidentController extends Controller
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

                $data = DB::table('internet_user_incidents')
                    ->join('communities', 'internet_user_incidents.community_id', '=', 'communities.id')
                    ->join('internet_users', 'internet_user_incidents.internet_user_id', '=', 'internet_users.id')
                    ->join('households', 'internet_users.household_id', '=', 'households.id')
                    ->join('incidents', 'internet_user_incidents.incident_id', '=', 'incidents.id')
                    ->join('internet_incident_statuses', 
                        'internet_user_incidents.internet_incident_status_id', 
                        '=', 'internet_incident_statuses.id')
                    ->where('internet_user_incidents.is_archived', 0)
                    ->select('internet_user_incidents.date', 'internet_user_incidents.year',
                        'internet_user_incidents.id as id', 'internet_user_incidents.created_at as created_at', 
                        'internet_user_incidents.updated_at as updated_at', 
                        'communities.english_name as community_name', 
                        'households.english_name as household_name',
                        'incidents.english_name as incident', 
                        'internet_incident_statuses.name',
                        'internet_user_incidents.notes')
                    ->orderBy('internet_user_incidents.date', 'desc'); 
    
                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
    
                        $viewButton = "<a type='button' class='viewInternetIncident' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#viewInternetIncidentModal' ><i class='fa-solid fa-eye text-info'></i></a>";
                        $updateButton = "<a type='button' class='updateInternetIncident' data-id='".$row->id."'><i class='fa-solid fa-pen-to-square text-success'></i></a>";
                        $deleteButton = "<a type='button' class='deleteInternetIncident' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";
                        
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
                                ->orWhere('households.english_name', 'LIKE', "%$search%")
                                ->orWhere('households.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('internet_user_incidents.date', 'LIKE', "%$search%")
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

            return view('incidents.internet.user.index', compact('communities',
                'incidents', 'internetIncidentStatuses', 'donors', 'incidentEquipments'));
                
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
        $internetIncident = new InternetUserIncident();

        if($request->date) {

            $internetIncident->date = $request->date;
            $year = explode('-', $request->date);
            $internetIncident->year = $year[0];
        }

        $internetIncident->community_id = $request->community_id;
        $internetUser = InternetUser::where('household_id', $request->internet_user_id)->first();
        $internetIncident->internet_user_id = $internetUser->id;
        $internetIncident->incident_id = $request->incident_id;
        $internetIncident->internet_incident_status_id = $request->internet_incident_status_id;
        $internetIncident->response_date = $request->response_date;
        $internetIncident->notes = $request->notes;
        $internetIncident->save();
        $id = $internetIncident->id;

        if($request->incident_equipment_id) {
            for($i=0; $i < count($request->incident_equipment_id); $i++) {

                $internetEquipment = new InternetUserIncidentEquipment();
                $internetEquipment->incident_equipment_id = $request->incident_equipment_id[$i];
                $internetEquipment->internet_user_incident_id = $id;
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
    
                $internetIncidentPhoto = new InternetUserIncidentPhoto();
                $internetIncidentPhoto->slug = $extra_name;
                $internetIncidentPhoto->internet_user_incident_id = $id;
                $internetIncidentPhoto->save();
            }
        }

        return redirect()->back()
            ->with('message', 'New Internet Incident Added Successfully!');
    }

    /**
     * Show the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $internetIncident = InternetUserIncident::findOrFail($id);
        $internetUser = InternetUser::where('id', $internetIncident->internet_user_id)->first();

       // $internetUser = Household::findOrFail($householdId->household_id);
        $community = Community::where('id', $internetIncident->community_id)->first();
        $incident = Incident::where('id', $internetIncident->incident_id)->first();
        $internetStatus = InternetIncidentStatus::where('id', 
            $internetIncident->internet_incident_status_id)->first();

        $internetIncidentEquipments = DB::table('internet_user_incident_equipment')
            ->join('incident_equipment', 'internet_user_incident_equipment.incident_equipment_id', 
                '=', 'incident_equipment.id')
            ->join('internet_user_incidents', 'internet_user_incident_equipment.internet_user_incident_id', 
                '=', 'internet_user_incidents.id')
            ->where('internet_user_incident_equipment.internet_user_incident_id', $id)
            ->get();

        $internetIncidentPhotos = InternetUserIncidentPhoto::where('internet_user_incident_id', $id)
            ->get();

        return view('incidents.internet.user.show', compact('internetIncident', 'internetUser', 
            'community', 'incident', 'internetStatus', 
            'internetIncidentEquipments', 'internetIncidentPhotos'));
    }

    /**
     * View Edit page.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) 
    {
        $internetIncident = InternetUserIncident::findOrFail($id);
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

        $internetIncidentEquipments = InternetUserIncidentEquipment::where('internet_user_incident_id', $id)
            ->get();

        $internetIncidentPhotos = InternetUserIncidentPhoto::where('internet_user_incident_id', $id)
            ->get();

        return view('incidents.internet.user.edit', compact('internetIncident', 'communities', 
            'incidents', 'internetIncidentStatuses', 'internetIncidentEquipments', 
            'incidentEquipments', 'internetIncidentPhotos'));
    }

    /**
     * Update an existing resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request, int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $internetIncident = InternetUserIncident::findOrFail($id);

        if($request->date) {

            $internetIncident->date = $request->date;
            $year = explode('-', $request->date);
            $internetIncident->year = $year[0];
        }

        $internetIncident->incident_id = $request->incident_id;
        $internetIncident->internet_incident_status_id = $request->internet_incident_status_id;
        $internetIncident->notes = $request->notes;
        if($request->response_date == null) $internetIncident->response_date = null;
        if($request->response_date) $internetIncident->response_date = $request->response_date;
        $internetIncident->save();

        if($request->new_equipment) {
 
            for($i=0; $i < count($request->new_equipment); $i++) {

                $internetEquipment = new InternetUserIncidentEquipment();
                $internetEquipment->incident_equipment_id = $request->new_equipment[$i];
                $internetEquipment->internet_user_incident_id = $internetIncident->id;
                $internetEquipment->save();
            }
        }

        if($request->more_equipment) {

            for($i=0; $i < count($request->more_equipment); $i++) {

                $internetEquipment = new InternetUserIncidentEquipment();
                $internetEquipment->incident_equipment_id = $request->more_equipment[$i];
                $internetEquipment->internet_user_incident_id = $internetIncident->id;
                $internetEquipment->save();
            }
        }

        if ($request->file('new_photos')) {

            foreach($request->new_photos as $photo) {

                $original_name = $photo->getClientOriginalName();
                $extra_name  = uniqid().'_'.time().'_'.uniqid().'.'.$photo->extension();
                $encoded_base64_image = substr($photo, strpos($photo, ',') + 1); 
                $destinationPath = public_path().'/incidents/internet/' ;
                $photo->move($destinationPath, $extra_name);
    
                $internetIncidentPhoto = new InternetUserIncidentPhoto();
                $internetIncidentPhoto->slug = $extra_name;
                $internetIncidentPhoto->internet_user_incident_id = $internetIncident->id;
                $internetIncidentPhoto->save();
            }
        }

        if ($request->file('more_photos')) {

            foreach($request->more_photos as $photo) {

                $original_name = $photo->getClientOriginalName();
                $extra_name  = uniqid().'_'.time().'_'.uniqid().'.'.$photo->extension();
                $encoded_base64_image = substr($photo, strpos($photo, ',') + 1); 
                $destinationPath = public_path().'/incidents/internet/' ;
                $photo->move($destinationPath, $extra_name);
    
                $internetIncidentPhoto = new InternetUserIncidentPhoto();
                $internetIncidentPhoto->slug = $extra_name;
                $internetIncidentPhoto->internet_user_incident_id = $internetIncident->id;
                $internetIncidentPhoto->save();
            }
        }

        return redirect('/incident-internet-user')
            ->with('message', 'Internet User Incident Updated Successfully!');
    }

    /**
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteInternetUserIncident(Request $request)
    {
        $id = $request->id;

        $internetIncident = InternetUserIncident::find($id);

        if($internetIncident) {

            $internetIncident->is_archived = 1;
            $internetIncident->save();
            
            $response['success'] = 1;
            $response['msg'] = 'Internet User Incident Deleted successfully'; 
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
    public function deleteInternetUserPhoto(Request $request)
    {
        $id = $request->id;

        $internetPhoto = InternetUserIncidentPhoto::find($id);

        if($internetPhoto) {

            $internetPhoto->delete();
            
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
    public function deleteIncidentEquipment(Request $request)
    {
        $id = $request->id;

        $internetEquipment = InternetUserIncidentEquipment::find($id);

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

            $html = '<option disabled selected>Choose One...</option>';
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
                
        return Excel::download(new InternetUserIncidentExport($request), 'internet_user_incidents.xlsx');
    }
}
