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
        $regions = Region::all();
        if ($request->ajax()) {
            $data = DB::table('sub_regions')
                ->join('regions', 'sub_regions.region_id', '=', 'regions.id')
                ->select('sub_regions.english_name as english_name', 'sub_regions.arabic_name as arabic_name',
                    'sub_regions.id as id', 'sub_regions.created_at as created_at', 
                    'sub_regions.updated_at as updated_at',
                    'regions.english_name as name',
                    'regions.arabic_name as aname',
                    'sub_regions.region_id as region_id',)
                ->latest(); 
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row) {

                    $updateButton = "<button class='btn btn-sm btn-info updateSubRegion' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#updateSubRegionModal' ><i class='fa-solid fa-pen-to-square'></i></button>";
                    $deleteButton = "<button class='btn btn-sm btn-danger deleteSubRegion' data-id='".$row->id."'><i class='fa-solid fa-trash'></i></button>";
                    
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

        return view('regions.index', compact('regions'));
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

        return redirect()->back();
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
            $response['id'] = $region->id;

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
        $id = $request->post('id');

        $subRegion = SubRegion::find($id);

        $response = array();
        if(!empty($subRegion)) {
            $updata['english_name'] = $request->post('english_name');
            $updata['arabic_name'] = $request->post('arabic_name');
            $updata['region_id'] = $request->post('region_id');

            if($subRegion->update($updata)) {

                $response['success'] = 1;
                $response['msg'] = 'Update successfully'; 
            } else {

                $response['success'] = 0;
                $response['msg'] = 'Record not updated';
            }
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
    public function deleteSubRegion(Request $request)
    {
        $id = $request->post('id');

        $subRegion = SubRegion::find($id);

        if($subRegion->delete()) {

            $response['success'] = 1;
            $response['msg'] = 'Delete successfully'; 
        } else {

            $response['success'] = 0;
            $response['msg'] = 'Invalid ID.';
        }

        return response()->json($response); 
    }
}
