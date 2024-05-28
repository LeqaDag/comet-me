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
use App\Models\SchoolCommunity;
use App\Models\SchoolPublicStructure;
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
        $publicStructureSchools = PublicStructure::where('is_archived', 0)
            ->where('public_structure_category_id1', 1)
            ->orWhere('public_structure_category_id2', 1)
            ->orWhere('public_structure_category_id3', 1)
            ->get();
        
        foreach($publicStructureSchools as  $publicStructureSchool) {

            $existSchool = SchoolPublicStructure::where('is_archived', 0)
                ->where('public_structure_id', $publicStructureSchool->id)
                ->first();
            if($existSchool) {

            } else {

                $newSchools = new SchoolPublicStructure();
                $newSchools->public_structure_id =  $publicStructureSchool->id;
                $newSchools->save();
            }
        }

        $schools = SchoolPublicStructure::where('is_archived', 0)->get();

        foreach($schools as $school) {

            $publicStructure = PublicStructure::findOrFail($school->public_structure_id);
            if($publicStructure) {

                $community = Community::findOrFail($publicStructure->community_id);
                if($community) {

                    $school->grade_from = $community->grade_from;
                    $school->grade_to = $community->grade_to;
                    $school->number_of_students = $community->school_students;
                    $school->number_of_boys = $community->school_male;
                    $school->number_of_girls = $community->school_female;
                    $school->save();
                }
            }
        }

        $communityFilter = $request->input('filter');
        $categoryFilter = $request->input('second_filter');

        if (Auth::guard('user')->user() != null) {

            if ($request->ajax()) {

                $data = DB::table('public_structures')
                    ->join('communities', 'public_structures.community_id', 'communities.id')
                    ->where('public_structures.is_archived', 0);
    
                if($communityFilter != null) {

                    $data->where('communities.id', $communityFilter);
                }
                if ($categoryFilter != null) {

                    $data->where("public_structures.public_structure_category_id1", $categoryFilter)
                        ->orWhere("public_structures.public_structure_category_id2", $categoryFilter)
                        ->orWhere("public_structures.public_structure_category_id3", $categoryFilter);
                }

                $data->select(
                    'public_structures.english_name', 'public_structures.arabic_name',
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

            return view('public.index', compact('communities', 'donors', 'publicCategories', 
                'regions'));
           
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
        $communities = Community::where('is_archived', 0)
            ->orderBy('english_name', 'ASC')
            ->get();
        $publicStructure = PublicStructure::findOrFail($id);
        $publicCategories = PublicStructureCategory::all();

        $schoolPublicStructure = SchoolPublicStructure::where("public_structure_id", $id)->first();
        $schoolCommunities = SchoolCommunity::where("public_structure_id", $id)->get();
        
        return view('public.edit', compact('publicStructure', 'publicCategories',
            'schoolPublicStructure', 'schoolCommunities', 'communities'));
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
        if($request->public_structure_category_id1) $publicStructure->public_structure_category_id1 = $request->public_structure_category_id1;
        if($request->public_structure_category_id2) $publicStructure->public_structure_category_id2 = $request->public_structure_category_id2;
        if($request->public_structure_category_id3) $publicStructure->public_structure_category_id3 = $request->public_structure_category_id3;
        $publicStructure->save();

        $existSchoolPublicStructure = SchoolPublicStructure::where("public_structure_id", $id)->first();
        if($existSchoolPublicStructure) {

            $existSchoolPublicStructure->grade_from = $request->grade_from;
            $existSchoolPublicStructure->grade_to = $request->grade_to;
            $existSchoolPublicStructure->number_of_students = $request->number_of_boys + $request->number_of_girls;
            $existSchoolPublicStructure->number_of_boys = $request->number_of_boys;
            $existSchoolPublicStructure->number_of_girls = $request->number_of_girls;
            $existSchoolPublicStructure->save();
        } else {

            $schoolPublicStructure = new SchoolPublicStructure();
            $schoolPublicStructure->grade_from = $request->grade_from;
            $schoolPublicStructure->grade_to = $request->grade_to;
            $schoolPublicStructure->number_of_students = $request->number_of_boys + $request->number_of_girls;
            $schoolPublicStructure->number_of_boys = $request->number_of_boys;
            $schoolPublicStructure->number_of_girls = $request->number_of_girls;
            $schoolPublicStructure->public_structure_id = $id;
            $schoolPublicStructure->save();
        }
        
        if($request->communities) {
            for($i=0; $i < count($request->communities); $i++) {

                $schoolCommunity = new SchoolCommunity();
                $schoolCommunity->community_id = $request->communities[$i];
                $schoolCommunity->public_structure_id = $id;
                $schoolCommunity->save();
            }
        }

        if($request->new_communities) {
            for($i=0; $i < count($request->new_communities); $i++) {

                $schoolCommunity = new SchoolCommunity();
                $schoolCommunity->community_id = $request->new_communities[$i];
                $schoolCommunity->public_structure_id = $id;
                $schoolCommunity->save();
            }
        }

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
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteschoolCommunity(Request $request)
    {
        $id = $request->id;

        $public = SchoolCommunity::find($id);

        if($public) {

            $public->delete();

            $response['success'] = 1;
            $response['msg'] = 'Served Community Deleted successfully'; 
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
