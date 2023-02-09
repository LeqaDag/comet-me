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

class SubSubRegionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {	
        $data = DB::table('communities')
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

        $regions = Region::all(); 
        if ($request->ajax()) {
            $data = DB::table('sub_sub_regions')
                ->join('sub_regions', 'sub_sub_regions.sub_region_id', '=', 'sub_regions.id')
                ->select('sub_sub_regions.english_name as english_name', 
                    'sub_sub_regions.arabic_name as arabic_name',
                    'sub_sub_regions.id as id', 'sub_sub_regions.created_at as created_at', 
                    'sub_sub_regions.updated_at as updated_at',
                    'sub_regions.english_name as name',
                    'sub_regions.arabic_name as aname')
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
                            $w->orWhere('sub_sub_regions.english_name', 'LIKE', "%$search%")
                            ->orWhere('sub_regions.english_name', 'LIKE', "%$search%")
                            ->orWhere('sub_sub_regions.arabic_name', 'LIKE', "%$search%")
                            ->orWhere('sub_regions.arabic_name', 'LIKE', "%$search%");
                        });
                    }
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('regions.sub_sub_regions.index', compact('regions'))
            ->with('subSubRegions', json_encode($array)
        );
    }
}
