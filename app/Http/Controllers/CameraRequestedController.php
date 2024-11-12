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
use App\Models\AllWaterHolder;
use App\Models\RequestedCamera;
use App\Models\CameraCommunity;
use App\Models\User;
use App\Models\Community;
use App\Models\CameraRequestStatus;
use App\Models\CommunityService;
use App\Models\Household;
use App\Models\PublicStructure;
use App\Models\Region;
use App\Exports\CameraRequestedExport;
use Carbon\Carbon;
use Image;
use Excel;
use DataTables;

class CameraRequestedController extends Controller
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

                $dataPublic = DB::table('requested_cameras')
                    ->join('communities', 'requested_cameras.community_id', 'communities.id')
                    ->join('camera_request_statuses', 'requested_cameras.camera_request_status_id', 'camera_request_statuses.id')
                    ->where('requested_cameras.is_archived', 0) 
                    ->select(
                        'requested_cameras.date', 'requested_cameras.id as id', 
                        'requested_cameras.created_at as created_at', 
                        'requested_cameras.updated_at as updated_at', 
                        'communities.english_name as community_name', 
                        'camera_request_statuses.name'
                        )
                    ->latest(); 
                 
                return Datatables::of($dataPublic)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
    
                        $viewButton = "<a type='button' class='viewCameraRequest' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#viewCameraRequestModal' ><i class='fa-solid fa-eye text-info'></i></a>";
                        $updateButton = "<a type='button' class='updateCameraRequest' data-id='".$row->id."' ><i class='fa-solid fa-pen-to-square text-success'></i></a>";
                        $deleteButton = "<a type='button' class='deleteCameraRequest' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";
    
                        if(Auth::guard('user')->user()->user_type_id == 1 || 
                            Auth::guard('user')->user()->user_type_id == 2 ||
                            Auth::guard('user')->user()->user_type_id == 5 ||
                            Auth::guard('user')->user()->user_type_id == 11) 
                        {
                                
                            return $viewButton." ". $updateButton." ".$deleteButton;
                        } else return $viewButton;
       
                    })
                    ->filter(function ($instance) use ($request) {
                        if (!empty($request->get('search'))) {
                                $instance->where(function($w) use($request) {
                                $search = $request->get('search');
                                $w->orWhere('communities.english_name', 'LIKE', "%$search%")
                                ->orWhere('communities.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('requested_cameras.date', 'LIKE', "%$search%")
                                ->orWhere('camera_request_statuses.name', 'LIKE', "%$search%");
                            });
                        }
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
    
            $communities = Community::where('is_archived', 0)
                ->orderBy('english_name', 'ASC')
                ->get();
            $requestStatuses = CameraRequestStatus::where('is_archived', 0)
                ->orderBy('name', 'ASC')
                ->get();
            $users = User::where('is_archived', 0)
                ->orderBy('name', 'ASC')
                ->get();

            return view('request.camera.index', compact('communities', 'requestStatuses', 'users'));
        } else {

            return view('errors.not-found');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $cameraRequested = new RequestedCamera();
        $cameraRequested->community_id = $request->community_id;
        $cameraRequested->camera_request_status_id = $request->camera_request_status_id;
        $cameraRequested->date = $request->date;
        $cameraRequested->user_id = $request->user_id;
        $cameraRequested->referred_by = $request->referred_by;
        $cameraRequested->notes = $request->notes;
        $cameraRequested->save();

        return redirect('/camera-request')
            ->with('message', 'New Requested Camera Added Successfully!');
    }

    /**
     * Show the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    { 
        $requestedCamera = RequestedCamera::findOrFail($id);
        $cameraRequestStatus = CameraRequestStatus::findOrFail($requestedCamera->camera_request_status_id);
        $community = Community::where('id', $requestedCamera->community_id)->first();

        $user = null;
        if($requestedCamera->user_id) $user = User::findOrFail($requestedCamera->user_id);

        $response['community'] = $community;
        $response['requestedCamera'] = $requestedCamera;
        $response['cameraRequestStatus'] = $cameraRequestStatus;
        $response['user'] = $user;

        return response()->json($response);
    }

    /**
     * View Edit page.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function editPage($id)
    {
        $requestedCamera = RequestedCamera::findOrFail($id);

        return response()->json($requestedCamera);
    }

    /**
     * View Edit page.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) 
    {
        $requestedCamera = RequestedCamera::findOrFail($id);
        $communities = Community::where('is_archived', 0)
            ->orderBy('english_name', 'ASC')
            ->get();
        $requestStatuses = CameraRequestStatus::where('is_archived', 0)
            ->orderBy('name', 'ASC')
            ->get();
        $users = User::where('is_archived', 0)
            ->orderBy('name', 'ASC')
            ->get();

        return view('request.camera.edit', compact('requestedCamera', 'requestStatuses', 'communities',
            'users'));
    }
    
    /**
     * Update an existing resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request, int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $cameraRequested = RequestedCamera::findOrFail($id);
        if($request->camera_request_status_id) $cameraRequested->camera_request_status_id = $request->camera_request_status_id;
        if($request->date) $cameraRequested->date = $request->date;
        if($request->user_id) $cameraRequested->user_id = $request->user_id;
        if($request->referred_by) $cameraRequested->referred_by = $request->referred_by;
        if($request->notes) $cameraRequested->notes = $request->notes;
        $cameraRequested->save();
        
        return redirect('/camera-request')->with('message', 'Requested Camera Updated Successfully!');
    }

    /**
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteCameraRequest(Request $request)
    {
        $id = $request->id;

        $cameraRequested = RequestedCamera::find($id);

        if($cameraRequested) {

            $cameraRequested->is_archived = 1;
            $cameraRequested->save();

            $response['success'] = 1;
            $response['msg'] = 'Requested Camera Deleted successfully'; 
        } else {

            $response['success'] = 0;
            $response['msg'] = 'Invalid ID.';
        }

        return response()->json($response); 
    }

    /**
     * Get households by community_id.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function getCameraDetailsByCommunity(int $id)
    {
        if (!$id) {

            $cameraDetails = '';
        } else {

            $cameraDetails = CameraCommunity::where('community_id', $id)
                ->where('is_archived', 0)
                ->first();
        }

        return response()->json([
            'cameraDetails' => $cameraDetails
        ]);
    }

    /**
     * 
     * @return \Illuminate\Support\Collection
     */
    public function export(Request $request) 
    {
                
        return Excel::download(new CameraRequestedExport($request), 'Requested Camera.xlsx');
    }
}