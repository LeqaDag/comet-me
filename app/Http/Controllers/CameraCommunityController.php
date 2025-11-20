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
use App\Models\Donor;
use App\Models\Community;
use App\Models\Compound;
use App\Models\CommunityService;
use App\Models\CameraCommunityType;
use App\Models\CameraCommunity;
use App\Models\CameraCommunityDonor;
use App\Models\NvrCommunityType;
use App\Models\CameraCommunityPhoto;
use App\Models\Camera;
use App\Models\NvrCamera;
use App\Models\Region; 
use App\Models\Household; 
use App\Models\SubRegion;
use App\Models\Repository;
use App\Exports\CameraCommunityExport;
use Carbon\Carbon;
use DataTables;
use Excel;
use Illuminate\Support\Facades\URL;

class CameraCommunityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {	
        // $cameraCommunities = CameraCommunity::where('is_archived', 0)->get();

        // foreach($cameraCommunities as $cameraCommunity) {

        //     $cameraCommunityDonor = new CameraCommunityDonor();
        //     $cameraCommunityDonor->camera_community_id = $cameraCommunity->id;
        //     $cameraCommunityDonor->donor_id = 2;
        //     $cameraCommunityDonor->save();

        // }

        // die( $cameraCommunities );

        $allCommunityCameras = CameraCommunity::where('is_archived', 0)
            ->where('community_id', '!=', NULL)
            ->get();
        
        foreach($allCommunityCameras as $allCommunityCamera) {

            
            $communityService = CommunityService::firstOrCreate(
                ['community_id' => $allCommunityCamera->community_id, 'service_id' => 4]
            );

            $dateTime = Carbon::createFromFormat('Y-m-d', $allCommunityCamera->date);
            $year = $dateTime->year;
            $community = Community::findOrFail($allCommunityCamera->community_id);
            $community->camera_service = "Yes";
            $community->camera_service_beginning_year = $year;
            $community->save();
        }

        if (Auth::guard('user')->user() != null) {

            $communities = Community::where('is_archived', 0)
                ->orderBy('english_name', 'ASC')
                ->get();

            $communityFilter = $request->input('community_filter');
            $regionFilter = $request->input('region_filter');
            $dateFilter = $request->input('date_filter');

            if ($request->ajax()) {
                
                $data = DB::table('camera_communities')
                    ->leftJoin('communities', 'camera_communities.community_id', 'communities.id')
                    ->leftJoin('regions', 'communities.region_id', 'regions.id')
                    ->leftJoin('sub_regions', 'communities.region_id', 'regions.id')
                    ->leftJoin('households', 'camera_communities.household_id', 'households.id')
                    ->leftJoin('repositories', 'camera_communities.repository_id', 'repositories.id')
                    ->leftJoin('regions as repository_regions', 'repositories.region_id', 
                        'repository_regions.id')
                    ->leftJoin('camera_community_types', 'camera_communities.id', 
                        'camera_community_types.camera_community_id')
                    ->leftJoin('nvr_community_types', 'camera_communities.id', 
                        'nvr_community_types.camera_community_id')
                    ->leftJoin('compounds', 'camera_communities.compound_id', 'compounds.id')
                    ->where('camera_communities.is_archived', 0);
                    
                $requestedData = DB::table('requested_cameras')
                    ->join('communities', 'requested_cameras.community_id', 'communities.id')
                    ->join('regions', 'communities.region_id', 'regions.id')
                    ->join('camera_request_statuses', 'requested_cameras.camera_request_status_id', 'camera_request_statuses.id')
                    ->where('requested_cameras.is_archived', 0);

                if($communityFilter != null) {

                    $data->where('communities.id', $communityFilter);
                    $requestedData->where('communities.id', $communityFilter);
                }
                if ($regionFilter != null) {

                    $data->where('regions.id', $regionFilter);
                    $requestedData->where('regions.id', $regionFilter);
                }
                if ($dateFilter != null) {

                    $data->where('camera_communities.date', '>=', $dateFilter);
                }

                $data->select(
                    DB::raw('IFNULL(communities.english_name, repositories.name) as name'), 
                    'households.english_name',
                    'camera_communities.id as id', 'camera_communities.created_at as created_at', 
                    'camera_communities.updated_at as updated_at', 'camera_communities.date as installation_date',
                    DB::raw('IFNULL(regions.english_name, repository_regions.english_name) as region'),
                    DB::raw('SUM(DISTINCT camera_community_types.number) as camera_number'),
                    DB::raw('SUM(DISTINCT nvr_community_types.number) as nvr_number'),
                    'compounds.english_name as compound'
                )->groupBy('camera_communities.id')->latest();  
 
                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
                        $detailsButton = "<a type='button' class='viewCameraCommunityButton' data-id='".$row->id."'><i class='fa-solid fa-eye text-primary'></i></a>";
                        $updateButton = "<a type='button' class='updateCameraCommunity' data-id='".$row->id."'><i class='fa-solid fa-pen-to-square text-success'></i></a>";
                        $deleteButton = "<a type='button' class='deleteCameraCommunity' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";
                        
                        if(Auth::guard('user')->user()->user_type_id != 7 || 
                            Auth::guard('user')->user()->user_type_id != 11 || 
                            Auth::guard('user')->user()->user_type_id != 8) 
                        {
                                
                            return $detailsButton." ". $updateButton." ".$deleteButton;
                        } else return $detailsButton; 

                    })
                    ->filter(function ($instance) use ($request) {
                        if (!empty($request->get('search'))) {
                                $instance->where(function($w) use($request) {
                                    $search = $request->get('search');
                                    $w->orWhere('households.english_name', 'LIKE', "%$search%")
                                    ->orWhere('households.arabic_name', 'LIKE', "%$search%")
                                    ->orWhere('communities.english_name', 'LIKE', "%$search%")
                                    ->orWhere('communities.arabic_name', 'LIKE', "%$search%")
                                    ->orWhere('regions.arabic_name', 'LIKE', "%$search%")
                                    ->orWhere('regions.english_name', 'LIKE', "%$search%")
                                    ->orWhere('repositories.name', 'LIKE', "%$search%");
                            });
                        }
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }

            $donors = Donor::where('is_archived', 0)->get();
            $regions = Region::where('is_archived', 0)->get();
            $subRegions = SubRegion::where('is_archived', 0)->get();
            $cameras = Camera::all();
            $nvrCameras = NvrCamera::all();
            $repositories = Repository::all();
            $compounds = Compound::where('is_archived', 0)->get();

            return view('services.camera.index', compact('communities', 'cameras', 'subRegions',
                'nvrCameras', 'repositories', 'regions', 'compounds'));

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
        $validatedData = $request->validate([
            'camera_id.*' => 'required',
            'addMoreInputFieldsCameraNumber.*.subject' => 'required',
            'addMoreInputFieldsNvrNumber.*.subject' => 'required',
            'addMoreInputFieldsNvrIpAddress.*.subject' => 'required',
        ]);

        $cameraCommunity = new CameraCommunity();

        if($request->community_id) {

            $cameraCommunity->community_id = $request->community_id;
            $community = Community::findOrFail($request->community_id);
            $community->camera_service = "Yes";
            if($request->date) $year = Carbon::parse($request->date)->year;
            $community->camera_service_beginning_year = $year;
            $community->save();

            $communityService = CommunityService::firstOrCreate(
                ['community_id' =>  $request->community_id, 'service_id' => 4]
            );
        } else if($request->repository_id ) {

            $cameraCommunity->repository_id = $request->repository_id;
            $cameraCommunity->comet_internal = 1;
        }
       
        $cameraCommunity->date = $request->date;
        $cameraCommunity->notes = $request->notes;
        if($request->household_id) $cameraCommunity->household_id = $request->household_id;
        $cameraCommunity->save();

        foreach ($validatedData['camera_id'] as $index => $cameraId) {
            $cameraCommunityType = new CameraCommunityType(); 
            $cameraCommunityType->camera_id = $cameraId;
            $cameraCommunityType->camera_community_id = $cameraCommunity->id;
            $cameraCommunityType->number = $validatedData['addMoreInputFieldsCameraNumber'][$index]['subject'];
            $cameraCommunityType->save();
        }

        foreach ($validatedData['camera_id'] as $index => $cameraNvrId) {
            $nvrCommunityType = new NvrCommunityType(); 
            $nvrCommunityType->nvr_camera_id = $cameraNvrId;
            $nvrCommunityType->camera_community_id = $cameraCommunity->id;
            $nvrCommunityType->ip_address = $validatedData['addMoreInputFieldsNvrIpAddress'][$index]['subject'];
            $nvrCommunityType->number = $validatedData['addMoreInputFieldsNvrNumber'][$index]['subject'];
            $nvrCommunityType->save();
        }

        if ($request->file('photos')) { 

            foreach($request->photos as $photo) {

                $original_name = $photo->getClientOriginalName();
                $extra_name  = uniqid().'_'.time().'_'.uniqid().'.'.$photo->extension();
                $encoded_base64_image = substr($photo, strpos($photo, ',') + 1); 
                $destinationPath = public_path().'/cameras/community/' ;
                $photo->move($destinationPath, $extra_name);
    
                $cameraCommunityPhoto = new CameraCommunityPhoto();
                $cameraCommunityPhoto->slug = $extra_name;
                $cameraCommunityPhoto->camera_community_id = $cameraCommunity->id;
                $cameraCommunityPhoto->save();
            }
        }

        return redirect()->back()->with('message', 'New Installed Cameras Inserted Successfully!');
    }

    /**
     * Show the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response 
     */
    public function show($id)
    {
        $sharedHouseholds = [];
        $cameraCommunity = CameraCommunity::findOrFail($id);
        $cameraCommunityTypes = CameraCommunityType::where("camera_community_id", $id)->get();
        $nvrCommunityTypes = NvrCommunityType::where("camera_community_id", $id)->get();

        $cameraPhotos = CameraCommunityPhoto::where('camera_community_id', $id)
            ->get();

        $cameraDonors = CameraCommunityDonor::where("camera_community_id", $id)
            ->where("is_archived", 0)
            ->get();

        return view('services.camera.show', compact('cameraCommunity', 'nvrCommunityTypes', 
            'sharedHouseholds', 'cameraPhotos', 'cameraCommunityTypes', 'cameraDonors'));
    }


     /**
     * View Edit page.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function editPage($id)
    {
        $cameraCommunity = CameraCommunity::findOrFail($id);

        return response()->json($cameraCommunity);
    } 

    /** 
     * View Edit page.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $communities = Community::where('is_archived', 0)
            ->orderBy('english_name', 'ASC')
            ->get();
        $cameraCommunity = CameraCommunity::findOrFail($id);
        $households = Household::where('is_archived', 0)
            ->orderBy('english_name', 'ASC')
            ->where("community_id", $cameraCommunity->community_id)
            ->get();

        $cameras = Camera::all();
        $nvrCameras = NvrCamera::all();
        $donors = Donor::where('is_archived', 0)->get();

        $cameraDonorsId = CameraCommunityDonor::where("camera_community_id", $id)
            ->where("is_archived", 0)
            ->pluck('donor_id'); 

        $moreDonors = Donor::where('is_archived', 0)
            ->whereNotIn('id', $cameraDonorsId) 
            ->get();

        $cameraDonors = CameraCommunityDonor::where("camera_community_id", $id)
            ->where("is_archived", 0)
            ->get();
        $communityCameraTypes = CameraCommunityType::where("camera_community_id", $id)->get();
        $communityNvrTypes = NvrCommunityType::where("camera_community_id", $id)->get();
        $cameraCommunityPhotos = CameraCommunityPhoto::where("camera_community_id", $id)->get();
            
        return view('services.camera.edit', compact('communities', 'cameras', 'donors',
            'cameraCommunity', 'nvrCameras', 'households', 'communityCameraTypes',
            'communityNvrTypes', 'cameraCommunityPhotos', 'cameraDonors', 'moreDonors'));
    }

    /**
     * Update an existing resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request, int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $cameraCommunity = CameraCommunity::findOrFail($id);
        if($request->household_id) $cameraCommunity->household_id = $request->household_id;
        if($request->date == null) $cameraCommunity->date = null;
        if($request->date) $cameraCommunity->date = $request->date;
        if($request->comet_internal) $cameraCommunity->comet_internal = 1;
        if($request->notes) $cameraCommunity->notes = $request->notes;
        $cameraCommunity->save();

        if($request->camera_id) {

            $validatedData = $request->validate([
                'camera_id.*' => 'required',
                'addMoreInputFieldsCameraNumber.*.subject' => 'required'
            ]);

            foreach ($validatedData['camera_id'] as $index => $cameraId) {
                $cameraCommunityType = new CameraCommunityType(); 
                $cameraCommunityType->camera_id = $cameraId;
                $cameraCommunityType->camera_community_id = $cameraCommunity->id;
                $cameraCommunityType->number = $validatedData['addMoreInputFieldsCameraNumber'][$index]['subject'];
                $cameraCommunityType->save();
            }
        }

        if($request->nvr_id) {

            $validatedData = $request->validate([
                'nvr_id.*' => 'required',
                'addMoreInputFieldsNvrNumber.*.subject' => 'required',
                'addMoreInputFieldsNvrIpAddress.*.subject' => 'required',
            ]);

            foreach ($validatedData['nvr_id'] as $index => $cameraNvrId) {
                $nvrCommunityType = new NvrCommunityType(); 
                $nvrCommunityType->nvr_camera_id = $cameraNvrId;
                $nvrCommunityType->camera_community_id = $cameraCommunity->id;
                $nvrCommunityType->ip_address = $validatedData['addMoreInputFieldsNvrIpAddress'][$index]['subject'];
                $nvrCommunityType->number = $validatedData['addMoreInputFieldsNvrNumber'][$index]['subject'];
                $nvrCommunityType->save();
            }
        }

        if ($request->file('more_photos')) {

            foreach($request->more_photos as $photo) {

                $original_name = $photo->getClientOriginalName();
                $extra_name  = uniqid().'_'.time().'_'.uniqid().'.'.$photo->extension();
                $encoded_base64_image = substr($photo, strpos($photo, ',') + 1); 
                $destinationPath = public_path().'/cameras/community/' ;
                $photo->move($destinationPath, $extra_name);
    
                $cameraCommunityPhoto = new CameraCommunityPhoto();
                $cameraCommunityPhoto->slug = $extra_name;
                $cameraCommunityPhoto->camera_community_id = $id;
                $cameraCommunityPhoto->save();
            }
        }

        if ($request->file('new_photos')) {

            foreach($request->new_photos as $photo) {

                $original_name = $photo->getClientOriginalName();
                $extra_name  = uniqid().'_'.time().'_'.uniqid().'.'.$photo->extension();
                $encoded_base64_image = substr($photo, strpos($photo, ',') + 1); 
                $destinationPath = public_path().'/cameras/community/' ;
                $photo->move($destinationPath, $extra_name);
    
                $cameraCommunityPhoto = new CameraCommunityPhoto();
                $cameraCommunityPhoto->slug = $extra_name;
                $cameraCommunityPhoto->camera_community_id = $id;
                $cameraCommunityPhoto->save();
            }
        }

        // update new/more donors
        if($request->donors) {

            for($i=0; $i < count($request->donors); $i++) {

                $cameraDonor = new CameraCommunityDonor();
                $cameraDonor->donor_id = $request->donors[$i];
                $cameraDonor->camera_community_id = $id;
                $cameraDonor->save();
            }
        }

        if($request->new_donors) {

            for($i=0; $i < count($request->new_donors); $i++) {

                $cameraDonor = new CameraCommunityDonor();
                $cameraDonor->donor_id = $request->new_donors[$i];
                $cameraDonor->camera_community_id = $id;
                $cameraDonor->save();
            }
        }
        

        return redirect('/camera')->with('message', 'Installed Camera Updated Successfully!');
    }

    /**
     * Update an existing resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request, int $id
     * @return \Illuminate\Http\Response
     */
    public function updateIpAddress(Request $request)
    {
        $id = $request->input('id');
        $ip_address = $request->input('ip_address');

        $nvrCommunityType = NvrCommunityType::findOrFail($id);
        $nvrCommunityType->ip_address = $ip_address;
        $nvrCommunityType->save();

        return response()->json(['success' => true]);
    }

    /**
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteCameraCommunity(Request $request)
    {
        $id = $request->id;

        $cameraCommunity = CameraCommunity::find($id);

        if($cameraCommunity) {

            $cameraCommunity->is_archived = 1;
            $cameraCommunity->save();

            $response['success'] = 1;
            $response['msg'] = 'Installed Camera in community Deleted successfully'; 
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
    public function deleteCameraDonor(Request $request)
    {
        $id = $request->id;

        $cameraCommunityDonor = CameraCommunityDonor::find($id);

        if($cameraCommunityDonor) {

            $cameraCommunityDonor->is_archived = 1;
            $cameraCommunityDonor->save();

            $response['success'] = 1;
            $response['msg'] = 'Donor Deleted successfully'; 
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
    public function deleteCommunityCamera(Request $request)
    {
        $id = $request->id;

        $cameraCommunityType = CameraCommunityType::find($id);

        if($cameraCommunityType) {

            $cameraCommunityType->delete();

            $response['success'] = 1;
            $response['msg'] = 'Installed Camera in community Deleted successfully'; 
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
    public function deleteCommunityNvrCamera(Request $request)
    {
        $id = $request->id;

        $cameraNvrCommunityType = NvrCommunityType::find($id);

        if($cameraNvrCommunityType) {

            $cameraNvrCommunityType->delete();

            $response['success'] = 1;
            $response['msg'] = 'Installed NVR in community Deleted successfully'; 
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
    public function deleteCommunityCameraPhoto(Request $request)
    {
        $id = $request->id;

        $cameraCommunityPhoto = CameraCommunityPhoto::find($id);

        if($cameraCommunityPhoto) {

            $cameraCommunityPhoto->delete();

            $response['success'] = 1;
            $response['msg'] = 'Photo Deleted successfully'; 
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

        return Excel::download(new CameraCommunityExport($request), 'installed_cameras.xlsx'); 
    }
}
