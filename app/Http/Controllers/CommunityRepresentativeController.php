<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use DB;
use Route;
use App\Models\User;
use App\Models\Community;
use App\Models\CommunityDonor;
use App\Models\CommunityStatus;
use App\Models\CommunityRepresentative;
use App\Models\CommunityRole;
use App\Models\Compound;
use App\Models\Donor;
use App\Models\EnergySystem;
use App\Models\EnergySystemType;
use App\Models\Household;
use App\Models\Photo;
use App\Models\Region;
use App\Models\SubRegion;
use App\Models\SubCommunity;
use App\Models\Settlement;
use App\Models\ServiceType;
use App\Models\PublicStructure;
use App\Models\PublicStructureCategory;
use App\Models\ProductType;
use App\Models\CommunityWaterSource;
use App\Exports\CommunityExport;
use App\Models\NearbySettlement;
use App\Models\NearbyTown;
use App\Models\Town;
use App\Models\WaterSource;
use Carbon\Carbon;
use Image;
use DataTables;
use Excel;

class CommunityRepresentativeController extends Controller
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

                $data = DB::table('community_representatives')
                    ->join('communities', 'community_representatives.community_id', '=', 'communities.id')
                    ->join('regions', 'communities.region_id', '=', 'regions.id')
                    ->join('households', 'community_representatives.household_id', 
                        '=', 'households.id')
                    ->join('community_statuses', 'communities.community_status_id', 
                        '=', 'community_statuses.id')
                    ->join('community_roles', 'community_representatives.community_role_id', 
                        '=', 'community_roles.id')
                    ->where('communities.is_archived', 0)
                    ->select('communities.english_name as english_name', 'communities.arabic_name as arabic_name',
                        'community_representatives.id as id', 'community_representatives.created_at as created_at', 
                        'community_representatives.updated_at as updated_at',
                        'communities.number_of_people as number_of_people',
                        'communities.number_of_household as number_of_household',
                        'regions.english_name as name',
                        'regions.arabic_name as aname',
                        'community_roles.role',
                        'households.english_name as household',
                        'households.phone_number',
                        'community_statuses.name as status_name')
                    ->latest(); 
    
                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
                        $detailsButton = "<a type='button' class='detailsRepresentativeButton' data-bs-toggle='modal' data-bs-target='#communityRepresentativeModel' data-id='".$row->id."'><i class='fa-solid fa-eye text-primary'></i></a>";
                       // $updateButton = "<a type='button' class='updateRepresentative' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#updateRepresentativeModal' ><i class='fa-solid fa-pen-to-square text-success'></i></a>";
                        $deleteButton = "<a type='button' class='deleteRepresentative' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";
    
                        return $detailsButton. " ". $deleteButton;
                    })
                    ->filter(function ($instance) use ($request) {
                        if (!empty($request->get('search'))) {
                                $instance->where(function($w) use($request) {
                                $search = $request->get('search');
                                $w->orWhere('communities.english_name', 'LIKE', "%$search%")
                                ->orWhere('communities.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('regions.english_name', 'LIKE', "%$search%")
                                ->orWhere('regions.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('households.english_name', 'LIKE', "%$search%")
                                ->orWhere('households.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('community_roles.role', 'LIKE', "%$search%")
                                ->orWhere('community_statuses.name', 'LIKE', "%$search%");
                            });
                        }
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }

    
            $communities = Community::all();
            $communityRoles = CommunityRole::all();
            
            return view('admin.community.representatives.index', compact('communities', 
                'communityRoles'));
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
        $communityRepresentative = new CommunityRepresentative();
        $communityRepresentative->community_role_id = $request->community_role_id;
        $communityRepresentative->community_id = $request->community_id;
        $communityRepresentative->household_id = $request->household_id;
        $communityRepresentative->save();

        if($request->phone_number) {

            $household = Household::findOrFail($request->household_id);
            $household->phone_number = $request->phone_number;
            $household->save();
        }

        return redirect()->back()->with('message', 
            'New Community Representative Inserted Successfully!');
    }

    /**
     * Show the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $representative = CommunityRepresentative::findOrFail($id);
        $household = Household::where('id', $representative->household_id)->first();
        $community = Community::where('id', $representative->community_id)->first();
        $region = Region::where('id', $community->region_id)->first();
        $status = CommunityStatus::where('id', $community->community_status_id)->first();
        $role = CommunityRole::where('id', $representative->community_role_id)->first();

    
        $response['community'] = $community;
        $response['region'] = $region;
        $response['status'] = $status;
        $response['household'] = $household;
        $response['role'] = $role;

        
        $html = '<option disabled selected>Choose one...</option>';
        $households = Household::where('community_id', $representative->community_id)->get();
        foreach ($households as $household) {

            $html .= '<option value="'.$household->id.'">'.$household->english_name.'</option>';
        }

        return response()->json([
            'html' => $html,
            'response' => $response]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function deleteCommunityRepresentative(Request $request)
    {
        $id = $request->id;

        $communityRepresentative = CommunityRepresentative::find($id);

        if($communityRepresentative->delete()) {

            $response['success'] = 1;
            $response['msg'] = 'Community Representative Deleted successfully'; 
        } else {

            $response['success'] = 0;
            $response['msg'] = 'Invalid ID.';
        }

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
        $communityRepresentative = CommunityRepresentative::findOrFail($id);

        return response()->json($communityRepresentative);
    } 

    /**
     * Update an existing resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request, int $id
     * @return \Illuminate\Http\Response
     */
    public function updateRepresentative(Request $request, $id)
    {
        $communityRepresentative = CommunityRepresentative::findOrFail($id);
        //die($communityRepresentative);
        if($request->community_role_id !=null) {

            $communityRepresentative->community_role_id = $request->community_role_id;
            $communityRepresentative->save();
        }
            
        if($request->household_id !=null) {

            $communityRepresentative->household_id = $request->household_id;
            $communityRepresentative->save();
        }
         
        if($request->phone !=null) {

            $household = Household::findOrFail($request->household_id);
            $household->phone_number = $request->phone;
            $household->save();
        }

        return redirect('/representative')->with('message', 
            'Community Representative Updated Successfully!');
    }
}