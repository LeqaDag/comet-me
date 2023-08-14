<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\User;
use App\Models\Community;
use App\Models\Region;
use Carbon\Carbon;
use App\Models\Donor;
use App\Models\Household;
use App\Models\Photo;
use App\Models\PublicStructure;
use App\Models\PublicStructureCategory;
use App\Exports\PublicStructureExport;
use Auth;
use Route;
use DB;
use Excel;
use PDF;
use DataTables;

class PublicStructureController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    { 
        if (Auth::guard('user')->user() != null) {

            if ($request->ajax()) {

                $data = DB::table('public_structures')
                    ->join('communities', 'public_structures.community_id', '=', 'communities.id')
                    ->where('public_structures.is_archived', 0)
                    ->select('public_structures.english_name', 'public_structures.arabic_name',
                        'public_structures.id as id', 'public_structures.created_at as created_at', 
                        'public_structures.updated_at as updated_at', 
                        'communities.english_name as community_name')
                    ->latest(); 
    
                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
    
                        $viewButton = "<a type='button' class='viewPublicStructure' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#viewPublicStructureModal' ><i class='fa-solid fa-eye text-info'></i></a>";
                        $updateButton = "<a type='button' class='updatePublicStructure' data-id='".$row->id."'><i class='fa-solid fa-pen-to-square text-success'></i></a>";
                        $deleteButton = "<a type='button' class='deletePublicStructure' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";
                        
                        if(Auth::guard('user')->user()->user_type_id == 1 || 
                            Auth::guard('user')->user()->user_type_id == 2 ||
                            Auth::guard('user')->user()->user_type_id == 3 ||
                            Auth::guard('user')->user()->user_type_id == 4 ||
                            Auth::guard('user')->user()->user_type_id == 5 ||
                            Auth::guard('user')->user()->user_type_id == 6) 
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
                                ->orWhere('public_structures.english_name', 'LIKE', "%$search%")
                                ->orWhere('public_structures.arabic_name', 'LIKE', "%$search%");
                            });
                        }
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
    
            $communities = Community::where('is_archived', 0)
                ->orderBy('english_name', 'ASC')
                ->get();
            $regions = Region::where('is_archived', 0)
                ->orderBy('english_name', 'ASC')
                ->get();
            $donors = Donor::where('is_archived', 0)
                ->orderBy('donor_name', 'ASC')
                ->get();

            $publicCategories = PublicStructureCategory::all();

            return view('public.index', compact('communities', 'donors', 'publicCategories', 'regions'));
           
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
        $publicStructure = new PublicStructure();
        $publicStructure->english_name = $request->english_name;
        $publicStructure->arabic_name = $request->arabic_name;
        $publicStructure->community_id = $request->community_id;
        $publicStructure->notes = $request->notes;
        $publicStructure->public_structure_category_id1 = $request->public_structure_category_id1;
        $publicStructure->public_structure_category_id2 = $request->public_structure_category_id2;
        $publicStructure->public_structure_category_id3 = $request->public_structure_category_id3;

        if($request->public_structure_category_id1 ||
            $request->public_structure_category_id2 || $request->public_structure_category_id3) 
        {

        } else {
            $publicStructure->comet_meter = 1;
        }
        $publicStructure->save();
        
        return redirect('/public-structure')
            ->with('message', 'New Public Structure Added Successfully!');
    }

    /**
     * View Edit page. 
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function editPage($id)
    {
        $publicStructure = PublicStructure::findOrFail($id);

        return response()->json($publicStructure);
    }

        /**
     * View Edit page.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) 
    {
        $publicStructure = PublicStructure::findOrFail($id);
        $publicCategories = PublicStructureCategory::all();

        return view('public.edit', compact('publicStructure', 'publicCategories'));
    }

    /**
     * Update an existing resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request, int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $publicStructure = PublicStructure::findOrFail($id);

        $publicStructure->english_name = $request->english_name;
        $publicStructure->arabic_name = $request->arabic_name;
        $publicStructure->notes = $request->notes;
        $publicStructure->public_structure_category_id1 = $request->public_structure_category_id1;
        $publicStructure->public_structure_category_id2 = $request->public_structure_category_id2;
        $publicStructure->public_structure_category_id3 = $request->public_structure_category_id3;
        $publicStructure->save();

        return redirect('/public-structure')->with('message', 'Public Structure Updated Successfully!');
    }

    /**
     * Show the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $publicStructure = PublicStructure::findOrFail($id);
        $community = Community::where('id', $publicStructure->community_id)->first();

        $response['publicStructure'] = $publicStructure;
        $response['community'] = $community;

        return response()->json($response);
    }

    /**
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deletePublicStructure(Request $request)
    {
        $id = $request->id;

        $public = PublicStructure::find($id);

        if($public) {

            $public->is_archived = 1;
            $public->save();

            $response['success'] = 1;
            $response['msg'] = 'Public Structure Deleted successfully'; 
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
                
        return Excel::download(new PublicStructureExport($request), 'public_structures.xlsx');
    }
}
