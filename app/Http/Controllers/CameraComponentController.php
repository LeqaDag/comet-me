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
use App\Models\CameraCommunityType;
use App\Models\CameraCommunity;
use App\Models\NvrCommunityType;
use App\Models\CameraCommunityPhoto;
use App\Models\Camera;
use App\Models\NvrCamera;
use App\Models\Household;
use App\Models\SubRegion;
use App\Exports\CameraCommunityExport;
use Carbon\Carbon;
use DataTables;
use Excel;
use Illuminate\Support\Facades\URL;

class CameraComponentController extends Controller
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
                
                $data = DB::table('cameras')
                    ->select('cameras.id', 'cameras.model', 'cameras.brand')
                    ->latest();   
 
                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
                        $updateButton = "<a type='button' class='updateCamera' data-id='".$row->id."'><i class='fa-solid fa-pen-to-square text-success'></i></a>";
                        $deleteButton = "<a type='button' class='deleteCamera' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";
                        
                        if(Auth::guard('user')->user()->user_type_id != 1 || 
                            Auth::guard('user')->user()->user_type_id != 6 || 
                            Auth::guard('user')->user()->user_type_id != 10) 
                        {
                                
                            return $updateButton." ".$deleteButton;
                        } 

                    })
                    ->filter(function ($instance) use ($request) {
                        if (!empty($request->get('search'))) {
                                $instance->where(function($w) use($request) {
                                    $search = $request->get('search');
                                    $w->orWhere('cameras.model', 'LIKE', "%$search%")
                                    ->orWhere('cameras.brand', 'LIKE', "%$search%");
                            });
                        }
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }

            $cameras = Camera::all();
            $nvrCameras = NvrCamera::all();

            return view('services.camera.components.index', compact('cameras', 'nvrCameras'));

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
        if (!empty($request->camera_models) && count($request->camera_models) > 0) {
            for ($i = 0; $i < count($request->camera_models); $i++) {
                $newCamera = new Camera();
                $newCamera->model = $request->camera_models[$i]["subject"];
                $newCamera->brand = $request->camera_brands[$i]["subject"];
                $newCamera->save();
            }
        }

        return redirect()->back()->with('message', 'New Cameras Inserted Successfully!');
    }

    /**
     * Show the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response 
     */
    public function show($id)
    {
      
    }


    /**
     * View Edit page.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function editPage($id)
    {
        $camera = Camera::findOrFail($id);

        return response()->json($camera);
    } 

    /** 
     * View Edit page.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $camera = Camera::findOrFail($id);

        return view('services.camera.components.camera.edit', compact('camera'));
    }

    /**
     * Update an existing resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request, int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $camera = Camera::findOrFail($id);
        if($request->model) $camera->model = $request->model;
        if($request->brand) $camera->brand = $request->brand;
        $camera->save();

        return redirect('/camera-component')->with('message', 'Camera Updated Successfully!');
    }

    /**
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteCamera(Request $request)
    {
        $id = $request->id;

        $camera = Camera::find($id);

        if($camera) {

            $camera->delete();

            $response['success'] = 1;
            $response['msg'] = 'Camera Deleted successfully'; 
        } else {

            $response['success'] = 0;
            $response['msg'] = 'Invalid ID.';
        }

        return response()->json($response); 
    }
}
