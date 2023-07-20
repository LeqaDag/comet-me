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
use App\Models\SubSubRegion;
use App\Models\Settlement;
use Carbon\Carbon;
use Image;
use DataTables;

class SubSubRegionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {	
        if (Auth::guard('user')->user() != null) {

            $data = DB::table('communities')
                ->where('communities.is_archived', 0)
                ->join('sub_sub_regions', 'communities.sub_sub_region_id', '=', 'sub_sub_regions.id')
                ->select(
                        DB::raw('sub_sub_regions.english_name as english_name'),
                        DB::raw('count(*) as number'))
                ->groupBy('sub_sub_regions.english_name')
                ->get();
            $array[] = ['English Name', 'Number'];
            
            foreach($data as $key => $value) {

                $array[++$key] = [$value->english_name, $value->number];
            }

            $regions = Region::where('is_archived', 0)
                ->orderBy('english_name', 'ASC')
                ->get(); 
                
            if ($request->ajax()) {
                $data = DB::table('sub_sub_regions')
                    ->where('sub_sub_regions.is_archived', 0)
                    ->join('regions', 'sub_sub_regions.region_id', '=', 'regions.id')
                    ->join('sub_regions', 'sub_sub_regions.sub_region_id', '=', 'sub_regions.id')
                    ->select('sub_sub_regions.english_name as english_name', 
                        'sub_sub_regions.arabic_name as arabic_name',
                        'sub_sub_regions.id as id', 'sub_sub_regions.created_at as created_at', 
                        'sub_sub_regions.updated_at as updated_at',
                        'sub_regions.english_name as name',
                        'sub_regions.arabic_name as aname',
                        'regions.english_name as region')
                    ->latest(); 
                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {

                        $empty = "";
                        $updateButton = "<a type='button' class='updateSubSubRegion' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#updateSubSubRegionModal' ><i class='fa-solid fa-pen-to-square text-success'></i></a>";
                        $deleteButton = "<a type='button' class='deleteSubSubRegion' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";
                        
                        if(Auth::guard('user')->user()->user_type_id == 1 || 
                            Auth::guard('user')->user()->user_type_id == 2 ) 
                        {
                                
                            return $updateButton." ".$deleteButton;
                        }

                        return $empty;
                    })
                
                    ->filter(function ($instance) use ($request) {
                        if (!empty($request->get('search'))) {
                                $instance->where(function($w) use($request){
                                $search = $request->get('search');
                                $w->orWhere('sub_sub_regions.english_name', 'LIKE', "%$search%")
                                ->orWhere('sub_regions.english_name', 'LIKE', "%$search%")
                                ->orWhere('sub_sub_regions.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('sub_regions.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('regions.english_name', 'LIKE', "%$search%")
                                ->orWhere('regions.arabic_name', 'LIKE', "%$search%");
                            });
                        }
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }

            return view('regions.sub_sub_regions.index', compact('regions'))
                ->with('subSubRegions', json_encode($array)
            );

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
        $subregion = SubSubRegion::create($request->all());
        $subregion->save();

        return redirect()->back()->with('message', 'New Sub-Sub-Region Added Successfully!');
    }

    /**
     * Get a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getSubSubRegionData(int $id)
    {
        $subRegion = SubSubRegion::find($id);
        $response = array();

        if(!empty($subRegion)) {

            $response['english_name'] = $subRegion->english_name;
            $response['arabic_name'] = $subRegion->arabic_name;
            $response['region_id'] = $subRegion->region_id;
            $response['sub_region_id'] = $subRegion->sub_region_id;

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
    public function getAllSubSubRegion()
    {
        $subRegions = SubRegion::where('is_archived', 0)->get();
        $response = array();

        if(!empty($subRegions)) {

            $response['subRegions'] = $subRegions;

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
    public function updateSubSubRegion(Request $request)
    {
        $id = $request->id;

        $subSubRegion = SubSubRegion::find($id);

        $response = array();

        if(!empty($subSubRegion)) {
            $subSubRegion->english_name = $request->english_name;
            $subSubRegion->arabic_name = $request->arabic_name;
            if($request->region_id != 0) $subSubRegion->region_id = $request->region_id;
            if($request->sub_region_id != 0) $subSubRegion->sub_region_id = $request->sub_region_id;
            $subSubRegion->save();

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
    public function deleteSubSubRegion(Request $request)
    {
        $id = $request->id;

        $subRegion = SubSubRegion::find($id);

        if($subRegion) {

            $subRegion->is_archived = 1;
            $subRegion->save();
            $response['success'] = 1;
            $response['msg'] = 'Sub Sub Region Deleted successfully'; 
        } else {

            $response['success'] = 0;
            $response['msg'] = 'Invalid ID.';
        }

        return response()->json($response); 
    }
}
