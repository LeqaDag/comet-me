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
use App\Models\SubCommunityHousehold;
use App\Models\Settlement;
use App\Models\ServiceType;
use App\Models\PublicStructure;
use App\Models\PublicStructureCategory;
use App\Models\ProductType;
use App\Models\CommunityWaterSource;
use App\Exports\SubCommunityAndHouseholdsExport; 
use App\Models\NearbySettlement;
use App\Models\NearbyTown;
use App\Models\Town;
use App\Models\WaterSource;
use Carbon\Carbon;
use Image;
use DataTables;
use Excel;

class SubCommunityHouseholdController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {	
        $subHouseholds = SubCommunityHousehold::where('is_archived', 0)->get();

        foreach($subHouseholds as $subHousehold) {

            $subCommunity = SubCommunity::where("community_id", $subHousehold->community_id)
                ->where('is_archived', 0)
                ->first();
            $subHousehold->sub_community_id = $subCommunity->id;
            $subHousehold->save();
        }

        $communityFilter = $request->input('filter');
        $regionFilter = $request->input('second_filter');
        $subRegionFilter = $request->input('third_filter');

        if (Auth::guard('user')->user() != null) {

            if ($request->ajax()) {

                $data = DB::table('sub_community_households')
                    ->join('communities', 'sub_community_households.community_id', 
                        'communities.id')
                    ->join('regions', 'communities.region_id', 'regions.id')
                    ->join('sub_regions', 'communities.sub_region_id', 'sub_regions.id')
                    ->join('community_statuses', 'communities.community_status_id', 
                        'community_statuses.id')
                    ->join('households', 'sub_community_households.household_id', 
                        'households.id')
                    ->join('sub_communities', 'sub_community_households.sub_community_id', 
                        'sub_communities.id')
                    ->where('sub_community_households.is_archived', 0); 

                if($communityFilter != null) {

                    $data->where('communities.id', $communityFilter);
                }
                if ($regionFilter != null) {

                    $data->where('regions.id', $regionFilter);
                }
                if ($subRegionFilter != null) {

                    $data->where('sub_regions.id', $subRegionFilter);
                }

                $data->select(
                    'sub_communities.english_name as english_name', 
                    'sub_communities.arabic_name as arabic_name',
                    'communities.english_name as community_english_name', 
                    'communities.arabic_name as community_arabic_name',
                    'sub_community_households.id as id', 'sub_community_households.created_at as created_at', 
                    'sub_community_households.updated_at as updated_at',
                    'communities.number_of_people as number_of_people',
                    'communities.number_of_household as number_of_household',
                    'regions.english_name as name',
                    'regions.arabic_name as aname',
                    'households.english_name as household',
                    'community_statuses.name as status_name')
                ->latest();

                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {

                        $empty = "";
                        $updateButton = "<a type='button' class='updateSubCommunityHousehold' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#updateSubCommunityModal' ><i class='fa-solid fa-pen-to-square text-success'></i></a>";
                        $deleteButton = "<a type='button' class='deleteSubCommunityHousehold' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";
    
                        if(Auth::guard('user')->user()->user_type_id == 1 || 
                            Auth::guard('user')->user()->user_type_id == 2 ) 
                        {
                                
                            return $updateButton." ".$deleteButton;
                        } else return $empty; 
       
                    })
                    ->filter(function ($instance) use ($request) {
                        if (!empty($request->get('search'))) {
                                $instance->where(function($w) use($request) {
                                $search = $request->get('search');
                                $w->orWhere('communities.english_name', 'LIKE', "%$search%")
                                ->orWhere('communities.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('regions.english_name', 'LIKE', "%$search%")
                                ->orWhere('regions.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('sub_communities.english_name', 'LIKE', "%$search%")
                                ->orWhere('sub_communities.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('households.english_name', 'LIKE', "%$search%")
                                ->orWhere('households.arabic_name', 'LIKE', "%$search%");
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
            $subregions = SubRegion::where('is_archived', 0)
                ->orderBy('english_name', 'ASC')
                ->get();
            $energySystemTypes = EnergySystemType::where('is_archived', 0)->get();
            $subCommunities = SubCommunity::where('is_archived', 0)->get();

            return view('admin.community.sub.index', compact('communities', 'regions', 
                'energySystemTypes', 'subCommunities', 'subregions'));
        } else {

            return view('errors.not-found');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function deleteSubCommunityHousehold(Request $request)
    {
        $id = $request->id;

        $subCommunity = SubCommunityHousehold::findOrFail($id);
        $subCommunity->is_archived = 1;
        $subCommunity->save();

        $response['success'] = 1;
        $response['msg'] = 'Sub Community Household Deleted successfully'; 
        
        return response()->json($response); 
    }

    /**
     * Show the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $community = SubCommunity::findOrFail($id);
        $region = Region::where('id', $community->region_id)->first();
        $subRegion = SubRegion::where('id', $community->sub_region_id)->first();
        $status = CommunityStatus::where('id', $community->community_status_id)->first();
        $publicStructures = PublicStructure::where('community_id', $community->id)->get();
        
        $nearbySettlement = DB::table('nearby_settlements')
            ->where('nearby_settlements.is_archived', 0)
            ->join('communities', 'nearby_settlements.community_id', '=', 'communities.id')
            ->join('settlements', 'nearby_settlements.settlement_id', '=', 'settlements.id')
            ->where('community_id', $community->id)
            ->select('settlements.english_name')
            ->get();
        $nearbyTown = DB::table('nearby_towns')
            ->where('nearby_towns.is_archived', 0)
            ->join('communities', 'nearby_towns.community_id', '=', 'communities.id')
            ->join('towns', 'nearby_towns.town_id', '=', 'towns.id')
            ->where('community_id', $community->id)
            ->select('towns.english_name')
            ->get();
        $compounds = Compound::where('community_id', $community->id)->get();
        $communityWaterSources = DB::table('community_water_sources')
            ->where('community_water_sources.is_archived', 0)
            ->join('communities', 'community_water_sources.community_id', '=', 'communities.id')
            ->join('water_sources', 'community_water_sources.water_source_id', '=', 'water_sources.id')
            ->where('community_id', $community->id)
            ->select('water_sources.name')
            ->get();
            
        $communityRepresentative = DB::table('community_representatives')
            ->where('community_representatives.is_archived', 0)
            ->join('communities', 'community_representatives.community_id', '=', 'communities.id')
            ->join('households', 'community_representatives.household_id', '=', 'households.id')
            ->join('community_roles', 'community_representatives.community_role_id', '=', 'community_roles.id')
            ->where('community_representatives.community_id', $community->id)
            ->select('households.english_name', 'community_roles.role')
            ->get();


        $response['community'] = $community;
        $response['region'] = $region;
        $response['sub-region'] = $subRegion;
        $response['status'] = $status;
        $response['public'] = $publicStructures;
        $response['nearbySettlement'] = $nearbySettlement;
        $response['nearbyTown'] = $nearbyTown;
        $response['compounds'] = $compounds;
        $response['communityWaterSources'] = $communityWaterSources;
        $response['communityRepresentative'] = $communityRepresentative;

        return response()->json($response);
    }

    /**
     * 
     * @return \Illuminate\Support\Collection
     */
    public function export(Request $request) 
    {
                
        return Excel::download(new SubCommunityAndHouseholdsExport($request), 
            'households&sub-communities.xlsx');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $subCommunity = SubCommunityHousehold::create($request->all());
        $subCommunity->save();

        return redirect()->back()->with('message', 'New Sub Community Household Added Successfully!');
    }

    /**
     * Get sub communities by community_id.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getByCommunity(Request $request)
    {
        $subCommunities = SubCommunity::where('community_id', $request->community_id)
            ->where('is_archived', 0)
            ->get();

        if (!$request->community_id) {

            $html = '<option value="">Choose One...</option>';
        } else {

            $html = '';
            $subCommunities = SubCommunity::where('community_id', $request->community_id)
                ->where('is_archived', 0)
                ->get();
            foreach ($subCommunities as $subCommunity) {
                $html .= '<option value="'.$subCommunity->id.'">'.$subCommunity->english_name.'</option>';
            }
        }

        return response()->json(['html' => $html]);
    }
}