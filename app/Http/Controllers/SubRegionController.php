<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use DB;
use Route;
use App\Models\Region;
use App\Models\SubRegion;
use App\Models\Settlement;
use Carbon\Carbon;
use Image;
use DataTables;

class SubRegionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {	
        if (Auth::guard('user')->user() != null) {

            $regions = Region::all(); 
            if ($request->ajax()) {
                $data = DB::table('sub_regions')
                    ->join('regions', 'sub_regions.region_id', '=', 'regions.id')
                    ->select('sub_regions.english_name as english_name', 
                        'sub_regions.arabic_name as arabic_name',
                        'sub_regions.id as id', 'sub_regions.created_at as created_at', 
                        'sub_regions.updated_at as updated_at',
                        'regions.english_name as name',
                        'regions.arabic_name as aname',
                        'sub_regions.region_id as region_id')
                    ->latest(); 
                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {

                        $updateButton = "<a type='button' class='updateSubRegion' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#updateSubRegionModal' ><i class='fa-solid fa-pen-to-square text-success'></i></a>";
                        $deleteButton = "<a type='button' class='deleteSubRegion' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";
                        
                        return $updateButton." ".$deleteButton;
                    })
                
                    ->filter(function ($instance) use ($request) {
                        if (!empty($request->get('search'))) {
                                $instance->where(function($w) use($request){
                                $search = $request->get('search');
                                $w->orWhere('sub_regions.english_name', 'LIKE', "%$search%")
                                ->orWhere('regions.english_name', 'LIKE', "%$search%")
                                ->orWhere('regions.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('sub_regions.arabic_name', 'LIKE', "%$search%");
                            });
                        }
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }

            return view('regions.sub_regions.index', compact('regions'));

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
        $subregion = SubRegion::create($request->all());
        $subregion->save();

        return redirect()->back()->with('message', 'New Sub-Region Added Successfully!');
    }

    /**
     * Get a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getSubRegionData(int $id)
    {
        $subRegion = SubRegion::find($id);
        $response = array();

        if(!empty($subRegion)) {

            $response['english_name'] = $subRegion->english_name;
            $response['arabic_name'] = $subRegion->arabic_name;
            $response['region_id'] = $subRegion->region_id;

            $response['success'] = 1;
        } else {

            $response['success'] = 0;
        }

        return response()->json($response);
    }

    /**
     * Get a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getRegionData(int $id)
    {
        $region = Region::find($id);
        $response = array();

        if(!empty($region)) {

            $response['english_name'] = $region->english_name;
            $response['arabic_name'] = $region->arabic_name;
            $response['id'] = $id;

            $response['success'] = 1;
        } else {

            $response['success'] = 0;
        }

        return response()->json($response);
    }

    /**
     * Get a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getAllSubRegion()
    {
        $regions = Region::all();
        $response = array();

        if(!empty($regions)) {

            $response['regions'] = $regions;

            $response['success'] = 1;
        } else {

            $response['success'] = 0;
        }

        return response()->json($response);
    }
    /**
     * Update a resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateSubRegion(Request $request)
    {
        $id = $request->id;

        $subRegion = SubRegion::find($id);

        $response = array();
        if(!empty($subRegion)) {
            $subRegion->english_name = $request->english_name;
            $subRegion->arabic_name = $request->arabic_name;
            if($request->region_id != 0) $subRegion->region_id = $request->region_id;
            $subRegion->save();

            $response = 1;
           
        } else {
            $response = 0;
        }

        return response()->json($response); 
    }

    /**
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteSubRegion(Request $request)
    {
        $id = $request->id;

        $subRegion = SubRegion::find($id);

        if($subRegion->delete()) {

            $response['success'] = 1;
            $response['msg'] = 'Sub Region Delete successfully'; 
        } else {

            $response['success'] = 0;
            $response['msg'] = 'Invalid ID.';
        }

        return response()->json($response); 
    }
}
