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

class NvrComponentController extends Controller
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
                
                $data = DB::table('nvr_cameras')
                    ->select('nvr_cameras.id', 'nvr_cameras.model', 'nvr_cameras.brand')
                    ->latest();   
 
                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
                        $updateButton = "<a type='button' class='updateNvr' data-id='".$row->id."'><i class='fa-solid fa-pen-to-square text-success'></i></a>";
                        $deleteButton = "<a type='button' class='deleteNvr' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";
                        
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
                                    $w->orWhere('nvr_cameras.model', 'LIKE', "%$search%")
                                    ->orWhere('nvr_cameras.brand', 'LIKE', "%$search%");
                            });
                        }
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }

            return view('services.camera.components.index');

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
        if (!empty($request->nvr_models) && count($request->nvr_models) > 0) {

            for ($i = 0; $i < count($request->nvr_models); $i++) {
                $newNvrCamera = new NvrCamera();
                $newNvrCamera->model = $request->nvr_models[$i]["subject"];
                $newNvrCamera->brand = $request->nvr_brands[$i]["subject"];
                $newNvrCamera->save();
            }
        }

        return redirect()->back()->with('success_message', 'New NVR Inserted Successfully!');
    }

    /**
     * View Edit page.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function editPage($id)
    {
        $cameraNvr = NvrCamera::findOrFail($id);

        return response()->json($cameraNvr);
    } 

    /** 
     * View Edit page.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $cameraNvr = NvrCamera::findOrFail($id);

        return view('services.camera.components.nvr.edit', compact('cameraNvr'));
    }

    /**
     * Update an existing resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request, int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $cameraNvr = NvrCamera::findOrFail($id);
        if($request->model) $cameraNvr->model = $request->model;
        if($request->brand) $cameraNvr->brand = $request->brand;
        $cameraNvr->save();

        return redirect('/camera-component')->with('success_message', 'Nvr Updated Successfully!');
    }

    /**
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteNvr(Request $request)
    {
        $id = $request->id;

        $cameraNvr = NvrCamera::find($id);

        if($cameraNvr) {

            $cameraNvr->delete();

            $response['success'] = 1;
            $response['msg'] = 'NVR Deleted successfully'; 
        } else {

            $response['success'] = 0;
            $response['msg'] = 'Invalid ID.';
        }

        return response()->json($response); 
    }
}
