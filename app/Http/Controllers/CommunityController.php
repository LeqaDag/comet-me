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
use App\Models\ServiceType;
use App\Models\PublicStructure;
use App\Models\ProductType;
use App\Models\CommunityWaterSource;
use Carbon\Carbon;
use Image;
use DataTables;

class CommunityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {	
        if ($request->ajax()) {

            $data = DB::table('communities')
                ->join('regions', 'communities.region_id', '=', 'regions.id')
                ->join('sub_regions', 'communities.sub_region_id', '=', 'sub_regions.id')
                ->select('communities.english_name as english_name', 'communities.arabic_name as arabic_name',
                    'communities.id as id', 'communities.created_at as created_at', 
                    'communities.updated_at as updated_at',
                    'communities.number_of_people as number_of_people',
                    'regions.english_name as name',
                    'regions.arabic_name as aname',
                    'sub_regions.english_name as subname')
                ->latest(); 

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row) {

                    $updateButton = "<button class='btn btn-sm btn-info updateCommunity' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#updateCommunityModal' ><i class='fa-solid fa-pen-to-square'></i></button>";
                    $deleteButton = "<button class='btn btn-sm btn-danger deleteCommunity' data-id='".$row->id."'><i class='fa-solid fa-trash'></i></button>";
                    
                    return $updateButton." ".$deleteButton;
   
                })
                ->filter(function ($instance) use ($request) {
                    if (!empty($request->get('search'))) {
                            $instance->where(function($w) use($request) {
                            $search = $request->get('search');
                            $w->orWhere('communities.english_name', 'LIKE', "%$search%")
                            ->orWhere('communities.arabic_name', 'LIKE', "%$search%")
                            ->orWhere('regions.english_name', 'LIKE', "%$search%")
                            ->orWhere('regions.arabic_name', 'LIKE', "%$search%")
                            ->orWhere('sub_regions.subname', 'LIKE', "%$search%");
                        });
                    }
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        

		$communities = Community::paginate();
        $communityRecords = Community::count();
        $communityWater = Community::where("water_service", "yes")->count();
        $communityInternet = Community::where("internet_service", "yes")->count();
        $regions = Region::all();
        $subregions = SubRegion::all();
        $communitiesWater = Community::where("water_service", "yes")->get();
        $communitiesInternet = Community::where("internet_service", "yes")->get();
        $communitiesAC = Community::where("community_status_id", 2)->get();
        $communityAC = Community::where("community_status_id", 2)->count();
        $products = ProductType::all();
        $energyTypes = EnergySystemType::all();

        $communitiesInitial = Community::where("community_status_id", 1)->get();
        $communityInitial = Community::where("community_status_id", 1)->count();

        $communitiesSurvyed = Community::where("community_status_id", 3)->get();
        $communitySurvyed = Community::where("community_status_id", 3)->count();



		return view('employee.community.index', compact('communities', 'regions', 
            'communityRecords', 'communityWater', 'communityInternet', 'subregions',
            'communitiesWater', 'communitiesInternet', 'communitiesAC', 'communityAC',
            'products', 'energyTypes', 'communitiesInitial', 'communityInitial', 
            'communitiesSurvyed', 'communitySurvyed'));
    }

    /**
     * Display a listing of filtering resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexFiltering(Request $request)
    {
        $filter = $request->query('filter');

        if (!empty($filter)) {
            $communities = Community::sortable()
                ->where('communities.english_name', 'like', '%'.$filter.'%')
                ->paginate(5);
        } else {
            $communities = Community::sortable()
                ->paginate(5);
        }

        return view('community.filter.index-filtering')
            ->with('communities', $communities)
            ->with('filter', $filter);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $communityDonors = CommunityDonor::all();
        // foreach($communityDonors as $communityDonor) {
        //     $community = Community::where('english_name', $communityDonor->community_name)
        //     ->first();
        //     $donor = Donor::where('donor_name', $communityDonor->donor_name)
        //     ->first();
        //     $service = ServiceType::where('service_name', $communityDonor->service_type)
        //     ->first();

        //     $communityDonor->community_id = $community->id;
        //     $communityDonor->donor_id = $donor->id;
        //     $communityDonor->service_id = $service->id;
        //     $communityDonor->save();
        // }

        // $energyAll = EnergySystem::all();
        // foreach($energyAll as $energy) {
        //     $type = EnergySystemType::where('name', $energy->system_type)
        //     ->first();
        //     $energy->energy_system_type_id = $type->id;
        //     $energy->save();
        // }

        // $subCommunities = SubCommunity::all();
        // foreach($subCommunities as $subCommunity) {
        //     $community = Community::where('english_name', $subCommunity->community_name)
        //     ->first();
        //     $subCommunity->community_id = $community->id;
        //     $household = Household::where('english_name', $subCommunity->household_name)
        //     ->first();
        //     $subCommunity->household_id = $household->id;
        //     $subCommunity->save();
        // }

        $data = DB::table('households')
            ->join('communities', 'communities.id', '=', 'households.community_id')
            ->select(
                'households.community_id AS id',
                DB::raw("count(households.community_id) AS total_people"))
            ->groupBy('households.community_id')
            ->get();
       
        foreach($data as $d) {
            $community = Community::findOrFail($d->id);
            //$community->number_of_people = NULL;
            $community->number_of_people = $d->total_people;
            $community->save();
        }

        return redirect('community');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $community = new Community();
        $community->english_name = $request->english_name;
        $community->arabic_name = $request->arabic_name;
        $community->region_id = $request->region_id;
        $community->sub_region_id = $request->sub_region_id;
        $community->location_gis = $request->location_gis;
        $community->number_of_compound = $request->number_of_compound;
        $community->number_of_people = $request->number_of_people;
        $community->number_of_households = $request->number_of_households;
        $community->is_fallah = $request->is_fallah;
        $community->is_bedouin = $request->is_bedouin;
        $community->settlement = $request->settlement;
        $community->demolition = $request->demolition;
        $community->land_status = $request->land_status;
        $community->lawyer = $request->lawyer;
        $community->hospital_town = $request->hospital_town;
        $community->notes = $request->notes;
        $community->save();
        $id = $community->id;

        $lastCommunity = Community::findOrFail($id);
      
        if($request->addMoreInputFieldsCompoundName) {
            foreach($request->addMoreInputFieldsCompoundName as $compoundName) {
                if($compoundName["subject"] != NULL) {
                    Compound::create([
                        'arabic_name' => $compoundName["subject"],
                        'community_id' => $id,
                    ]);
                }
            }
        }
        
        if($request->school == "yes") {
            $publicStructure = new PublicStructure();
            $publicStructure->english_name = "School " . $lastCommunity->english_name;
            $publicStructure->arabic_name = "مدرسة  " . $lastCommunity->arabic_name;
            $publicStructure->category_id1 = 1;
            $publicStructure->school_grade = $request->description;
            $publicStructure->save();

        } else if($request->school == "no") {
            $lastCommunity->school_town = $request->description;
            $lastCommunity->save();
        }

        if($request->mosque == "yes") {
            
            $publicStructure = new PublicStructure();
            $publicStructure->english_name = "Mosque " . $lastCommunity->english_name;
            $publicStructure->arabic_name = "مسجد  " . $lastCommunity->arabic_name;
            $publicStructure->category_id1 = 2;
            $publicStructure->save();
        }

        if($request->clinic == "yes") {

            $publicStructure = new PublicStructure();
            $publicStructure->english_name = "Clinic " . $lastCommunity->english_name;
            $publicStructure->arabic_name = "عيادة  " . $lastCommunity->arabic_name;
            $publicStructure->category_id1 = 3;
            $publicStructure->save();
        }

        return redirect()->back();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getByRegion(Request $request)
    {
        $regions = SubRegion::where('region_id', $request->region_id)->get();
 
        if (!$request->region_id) {
            $html = '<option value="">Choose One...</option>';
        } else {
            $html = '';
            $regions = SubRegion::where('region_id', $request->region_id)->get();
            foreach ($regions as $region) {
                $html .= '<option value="'.$region->id.'">'.$region->english_name.'</option>';
            }
        }

        return response()->json(['html' => $html]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $community = Community::findOrFail($id);
        $community->is_archived = 1;
        $community->save();

        return redirect()->back();
    }
}