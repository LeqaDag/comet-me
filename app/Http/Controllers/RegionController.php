<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\AllEnergyMeter;
use App\Models\AllWaterHolder;
use App\Models\User;
use App\Models\Community;
use Carbon\Carbon;
use App\Models\CometMeter;
use App\Models\CommunityDonor;
use App\Models\CommunityStatus;
use App\Models\CommunityRepresentative;
use App\Models\CommunityRole;
use App\Models\Compound;
use App\Models\Donor;
use App\Models\EnergySystem;
use App\Models\EnergySystemType;
use App\Models\EnergyUser;
use App\Models\Household;
use App\Models\HouseholdMeter;
use App\Models\H2oUser;
use App\Models\GridUser;
use App\Models\MgIncident;
use App\Models\Photo;
use App\Models\Region;
use App\Models\SubRegion;
use App\Models\SubSubRegion;
use App\Models\SubCommunity;
use App\Models\Settlement;
use App\Models\ServiceType;
use App\Models\MeterCase;
use App\Models\PublicStructure;
use App\Models\PublicStructureCategory;
use App\Models\ProductType;
use App\Models\CommunityWaterSource;
use App\Models\Town;
use App\Models\BsfStatus;
use App\Models\H2oSharedUser;
use App\Models\H2oStatus;
use App\Models\InternetUser;
use Auth;
use Route;
use DB;
use DataTables;

class RegionController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        // $this->middleware('permission:quality-list|quality-create|quality-edit|quality-delete', ['only' => ['index']]);
        // $this->middleware('permission:quality-create', ['only' => ['create','store']]);
        // $this->middleware('permission:quality-edit', ['only' => ['edit','update']]);
        // $this->middleware('permission:quality-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {	
        if (Auth::guard('user')->user() != null) {

            $regions = Region::where('is_archived', 0)->get(); 
            $subRegions = SubRegion::where('is_archived', 0)->get(); 

            if ($request->ajax()) {

                $data = DB::table('regions')
                    ->where('regions.is_archived', 0)
                    ->select('regions.english_name as english_name', 
                    'regions.arabic_name as arabic_name',
                    'regions.id as id', 'regions.created_at as created_at', 
                    'regions.updated_at as updated_at')
                    ->latest();
 
                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
 
                        $empty = "";
                        $updateButton = "<a type='button' class='updateRegion' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#updateRegionModal'><i class='fa-solid fa-pen-to-square text-success'></i></a>";
                        $deleteButton = "<a type='button' class='deleteRegion' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";
                                
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
                                $w->orWhere('regions.english_name', 'LIKE', "%$search%")
                                ->orWhere('regions.arabic_name', 'LIKE', "%$search%");
                            });
                        }
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            
            return view('regions.index', compact('regions', 'subRegions'));

        } else {

            return view('errors.not-found');
        }
    }

    /**
     * View resource by id.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {	
        $region = Region::findOrFail($id);
        $response = $region;

        return response()->json($response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateRegion(Request $request, int $id)
    {
        $region = Region::findOrFail($request->id);
        $region->english_name = $request->english_name;
        $region->arabic_name = $request->arabic_name;
        $region->save();
 
        $response = 1;

        return response()->json($response );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getByRegion(Request $request)
    {
        $regions = SubRegion::where('is_archived', 0)
            ->where('region_id', $request->region_id)
            ->get();
 
        if (!$request->region_id) {
            $html = '<option value="">Choose One...</option>';
        } else {
            $html = '<option selected>Choose One...</option><option value="0">All Sub Regions</option>';
            $regions = SubRegion::where('is_archived', 0)
                ->where('region_id', $request->region_id)
                ->get();

            foreach ($regions as $region) {
                $html .= '<option value="'.$region->id.'">'.$region->english_name.'</option>';
            }
        }

        return response()->json(['html' => $html]);
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getBySubRegion(Request $request)
    {
        $communities = 0;
        $countHouseholds = 0;
        $countEnergyUsers = 0;
        $countMgSystem = 0;
        $countFbsSystem = 0;
        $countH2oUsers = 0;
        $countCommunities = 0;
        $countInternetUsers = 0; 

        if($request->sub_region_id == 0) {

            $communities = Community::where("region_id", $request->region_id)
                ->where('is_archived', 0)
                ->get();
            $countCommunities = Community::where("region_id", $request->region_id)
                ->where('is_archived', 0)
                ->count();

            $countMgSystem =  DB::table('all_energy_meters')
                ->where('all_energy_meters.is_archived', 0)
                ->join('communities', 'all_energy_meters.community_id', '=', 'communities.id')
                ->join('energy_systems', 'all_energy_meters.energy_system_id', '=', 'energy_systems.id')
                ->join('energy_system_types', 'all_energy_meters.energy_system_type_id', '=', 'energy_system_types.id')
                ->where('communities.region_id', $request->region_id)
                ->where('all_energy_meters.energy_system_type_id', 1)
                ->select(
                    DB::raw('energy_systems.name as name'),
                    DB::raw('count(*) as number'))
                ->groupBy('energy_systems.name')
                ->count();

            $countFbsSystem =  DB::table('all_energy_meters')
                ->where('all_energy_meters.is_archived', 0)
                ->join('communities', 'all_energy_meters.community_id', '=', 'communities.id')
                ->join('energy_systems', 'all_energy_meters.energy_system_id', '=', 'energy_systems.id')
                ->join('energy_system_types', 'all_energy_meters.energy_system_type_id', '=', 'energy_system_types.id')
                ->where('communities.region_id', $request->region_id)
                ->where('all_energy_meters.energy_system_type_id', 2)
                ->select(
                    DB::raw('all_energy_meters.energy_system_type_id as name'),
                    DB::raw('count(*) as number'))
                ->groupBy('all_energy_meters.energy_system_type_id')
                ->count();
            
        } else {

            $communities = Community::where("region_id", $request->region_id)
                ->where('is_archived', 0)
                ->where("sub_region_id", $request->sub_region_id)
                ->get();
            $countCommunities = Community::where("region_id", $request->region_id)
                ->where('is_archived', 0)
                ->where("sub_region_id", $request->sub_region_id)
                ->count();

            $countMgSystem =  DB::table('all_energy_meters')
                ->where('all_energy_meters.is_archived', 0)
                ->join('communities', 'all_energy_meters.community_id', '=', 'communities.id')
                ->join('energy_systems', 'all_energy_meters.energy_system_id', '=', 'energy_systems.id')
                ->join('energy_system_types', 'all_energy_meters.energy_system_type_id', '=', 'energy_system_types.id')
                ->where('communities.region_id', $request->region_id)
                ->where('communities.sub_region_id', $request->sub_region_id)
                ->where('all_energy_meters.energy_system_type_id', 1)
                ->select(
                    DB::raw('energy_systems.name as name'),
                    DB::raw('count(*) as number'))
                ->groupBy('energy_systems.name')
                ->count();

            $countFbsSystem =  DB::table('all_energy_meters')
                ->where('all_energy_meters.is_archived', 0)
                ->join('communities', 'all_energy_meters.community_id', '=', 'communities.id')
                ->join('energy_systems', 'all_energy_meters.energy_system_id', '=', 'energy_systems.id')
                ->join('energy_system_types', 'all_energy_meters.energy_system_type_id', '=', 'energy_system_types.id')
                ->where('communities.region_id', $request->region_id)
                ->where('communities.sub_region_id', $request->sub_region_id)
                ->where('all_energy_meters.energy_system_type_id', 2)
                ->select(
                    DB::raw('all_energy_meters.energy_system_type_id as name'),
                    DB::raw('count(*) as number'))
                ->groupBy('all_energy_meters.energy_system_type_id')
                ->count();
        }

        foreach($communities as $community) {
            $energyUsers = AllEnergyMeter::where('community_id', $community->id)
                ->where('is_archived', 0)
                ->get();

            $countEnergyUsers+= $energyUsers->count();
        }

        foreach($communities as $community) {
            $h2oUserCount = AllWaterHolder::where('community_id', $community->id)
                ->where('is_archived', 0)
                ->count();

            $countH2oUsers+= $h2oUserCount;
        }

        foreach($communities as $community) {
            $householdsCount = Household::where('community_id', $community->id)
                ->where('is_archived', 0)
                ->count();

            $countHouseholds+= $householdsCount;
        }

        foreach($communities as $community) {
            $InternetCount = InternetUser::where('community_id', $community->id)
                ->where('is_archived', 0)
                ->count();

            $countInternetUsers+= $InternetCount;
        }

        return response()->json([
            'countCommunities' => $countCommunities,
            'countHouseholds' => $countHouseholds,
            'countH2oUsers' => $countH2oUsers,
            'countEnergyUsers' => $countEnergyUsers,
            'countMgSystem' => $countMgSystem,
            'countFbsSystem' => $countFbsSystem,
            'countInternetUsers' => $countInternetUsers,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $subregion = Region::create($request->all());
        $subregion->save();

        return redirect()->back()->with('message', 'New Region Added Successfully!');
    }

    /**
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteRegion(Request $request)
    {
        $id = $request->id;

        $region = Region::find($id);
        $subRegions = SubRegion::where("region_id", $id)->get();
        $subSubRegions = SubSubRegion::where("region_id", $id)->get();

        if($region) {

            $region->is_archived = 1;
            $region->save();

            if($subRegions) {
                foreach($subRegions as $subRegion) {
                    $subRegion->is_archived = 1;
                    $subRegion->save();
                }
            }

            if($subSubRegions) {
                foreach($subSubRegions as $subSubRegion) {
                    $subSubRegion->is_archived = 1;
                    $subSubRegion->save();
                }
            }
            
            $response['success'] = 1;
            $response['msg'] = 'Region Delete successfully'; 
        } else {

            $response['success'] = 0;
            $response['msg'] = 'Invalid ID.';
        }

        return response()->json($response); 
    }
}