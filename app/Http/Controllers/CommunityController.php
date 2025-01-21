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
use App\Models\AllEnergyMeter;
use App\Models\AllWaterHolder;
use App\Models\AllEnergyMeterDonor;
use App\Models\Community;
use App\Models\CommunityDonor;
use App\Models\CommunityStatus; 
use App\Models\CommunityService;
use App\Models\CommunityProduct;
use App\Models\CommunityRepresentative;
use App\Models\CommunityRole;
use App\Models\Compound;
use App\Models\Donor;
use App\Models\EnergySystem;
use App\Models\EnergySystemCycle;
use App\Models\EnergySystemType;
use App\Models\Household;
use App\Models\Photo;
use App\Models\Region;
use App\Models\SecondNameCommunity;
use App\Models\SubRegion;
use App\Models\SubCommunity;
use App\Models\Settlement;
use App\Models\ServiceType;
use App\Models\PublicStructure;
use App\Models\SchoolPublicStructure;
use App\Models\GridCommunityCompound;
use App\Models\PublicStructureCategory;
use App\Models\ProductType;
use App\Models\CommunityWaterSource;
use App\Models\SchoolCommunity;
use App\Exports\CommunityExport;
use App\Models\NearbySettlement;
use App\Models\NearbyTown;
use App\Models\InternetUser;
use App\Models\H2oUser;
use App\Models\GridUser;
use App\Models\Town;
use App\Models\RecommendedCommunityEnergySystem;
use App\Models\WaterSource;
use Carbon\Carbon;
use Image;
use DataTables;
use Excel;

class CommunityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {	
        $data = DB::table('households')
            ->join('communities', 'communities.id', 'households.community_id')
            ->select(
                'households.community_id AS id',
                DB::raw('COUNT(CASE WHEN households.is_archived = 0 AND households.internet_holder_young = 0
                THEN 1 ELSE NULL END) as total_household'),
                )
            ->groupBy('households.community_id')
            ->get();
       
        
        foreach($data as $d) {

            $community = Community::findOrFail($d->id);
            //$community->number_of_household = NULL;
            $community->number_of_household = $d->total_household;
            $community->save();
        }

        $households = DB::table('households')
            ->join('communities', 'communities.id', 'households.community_id')
            ->select(
                'households.community_id AS id',
                DB::raw(
                    'SUM(CASE WHEN households.is_archived = 0 THEN households.number_of_adults + households.number_of_children 
                        ELSE 0 END) as total_people'),
                DB::raw(
                    'SUM(CASE WHEN households.is_archived = 0 THEN households.number_of_male + households.number_of_female 
                        ELSE 0 END) as total_people1')
                )
            ->groupBy('households.community_id')
            ->get();

        foreach($households as $household) {

            $community = Community::findOrFail($household->id);
            //$community->number_of_household = NULL;
            if($household->total_people > $household->total_people1) $community->number_of_people = $household->total_people;
            else $community->number_of_people = $household->total_people1;
            $community->save();
        }
        
        // $communities = Community::get();

        // foreach($communities as $community) {

        //     $location = "https://www.google.com/maps?q={$community->latitude},{$community->longitude}";
        //     $community->location_gis = $location;
        //     $community->save();
        // }

        if (Auth::guard('user')->user() != null) {

            $regionFilter = $request->input('filter');
            $subRegionFilter = $request->input('second_filter');
            $statusFilter = $request->input('third_filter');

            if ($request->ajax()) {

                $data = DB::table('communities')
                    ->join('regions', 'communities.region_id', '=', 'regions.id')
                    ->join('sub_regions', 'communities.sub_region_id', '=', 'sub_regions.id')
                    ->join('community_statuses', 'communities.community_status_id', 
                        '=', 'community_statuses.id')
                    ->leftJoin('second_name_communities', 'communities.id',
                        '=', 'second_name_communities.community_id')
                    ->where('communities.is_archived', 0);
    
                if($regionFilter != null) {

                    $data->where('regions.id', $regionFilter);
                }
                if ($subRegionFilter != null) {

                    $data->where('sub_regions.id', $subRegionFilter);
                }
                if ($statusFilter != null) {

                    $data->where('community_statuses.id', $statusFilter);
                }

                $data->select(
                    'communities.english_name as english_name', 
                    'communities.arabic_name as arabic_name',
                    'communities.id as id', 'communities.created_at as created_at', 
                    'communities.updated_at as updated_at',
                    'communities.number_of_people as number_of_people',
                    'communities.number_of_household as number_of_household',
                    'regions.english_name as name',
                    'regions.arabic_name as aname',
                    'sub_regions.english_name as subname',
                    'community_statuses.name as status_name')
                ->latest(); 

                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {

                        $detailsButton = "<a type='button' class='detailsCommunityButton' data-id='".$row->id."'><i class='fa-solid fa-eye text-primary'></i></a>";
                        $updateButton = "<a type='button' class='updateCommunity' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#updateCommunityModal' ><i class='fa-solid fa-pen-to-square text-success'></i></a>";
                        $deleteButton = "<a type='button' class='deleteCommunity' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";
    
                        if(Auth::guard('user')->user()->user_type_id == 1 || 
                            Auth::guard('user')->user()->user_type_id == 2 || 
                            Auth::guard('user')->user()->user_type_id == 3) 
                        {
                                
                            return  $detailsButton." ". $updateButton." ".$deleteButton;
                        } else return $detailsButton; 

                    })
                    ->filter(function ($instance) use ($request) {
                        if (!empty($request->get('search'))) {
                            $instance->where(function($w) use($request) {
                                $search = $request->get('search');
                                $w->orWhere('communities.english_name', 'LIKE', "%$search%")
                                ->orWhere('communities.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('regions.english_name', '=', $search)
                                ->orWhere('regions.arabic_name', '=', $search)
                                ->orWhere('sub_regions.english_name', 'LIKE', "%$search%")
                                ->orWhere('community_statuses.name', 'LIKE', "%$search%")
                                ->orWhere('second_name_communities.english_name', 'LIKE', "%$search%")
                                ->orWhere('second_name_communities.arabic_name', 'LIKE', "%$search%");
                            });
                        }
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            
            $communities = Community::paginate();
            $communityStatuses = CommunityStatus::where('is_archived', 0)->get();
            $communityRecords = Community::where('is_archived', 0)->count();
            $communityWater = Community::where("water_service", "yes")
                ->where('is_archived', 0)
                ->count();
            $communityInternet = Community::where("internet_service", "yes")
                ->where('is_archived', 0)
                ->count();
            $regions = Region::where('is_archived', 0)
                ->orderBy('english_name', 'ASC')
                ->get(); 
            $subregions = SubRegion::where('is_archived', 0)
                ->orderBy('english_name', 'ASC')
                ->get();
            $communitiesWater = Community::where("water_service", "yes")
                ->where('is_archived', 0)
                ->get();
            $communitiesInternet = Community::where("internet_service", "yes")
                ->where('is_archived', 0)
                ->get();
            $communitiesAC = Community::where("community_status_id", 2)
                ->where('is_archived', 0)
                ->get();
            $communityAC = Community::where("community_status_id", 2)
                ->where('is_archived', 0)
                ->count();
            $products = ProductType::where('is_archived', 0)->get();
            $energyTypes = EnergySystemType::where('is_archived', 0)->get();
    
            $communitiesInitial = Community::where("community_status_id", 1)
                ->where('is_archived', 0)
                ->get();
            $communityInitial = Community::where("community_status_id", 1)
                ->where('is_archived', 0)
                ->count();
    
            $communitiesSurvyed = Community::where("community_status_id", 3)
                ->where('is_archived', 0)
                ->get();
            $communitySurvyed = Community::where("community_status_id", 3)
                ->where('is_archived', 0)
                ->count();
    
            $settlements = Settlement::where('is_archived', 0)->get();
            $towns = Town::where('is_archived', 0)->get();
            $publicCategories = PublicStructureCategory::where('is_archived', 0)
                ->orderBy('name', 'ASC')
                ->get();
            $publicStructures = PublicStructure::where('is_archived', 0)->get();
    
            $data = DB::table('communities')
                ->where('communities.is_archived', 0)
                ->join('regions', 'communities.region_id', '=', 'regions.id')
                ->select(
                        DB::raw('regions.english_name as english_name'),
                        DB::raw('count(*) as number'))
                ->groupBy('regions.english_name')
                ->get();
            $array[] = ['English Name', 'Number'];
            
            foreach($data as $key => $value) {
    
                $array[++$key] = [$value->english_name, $value->number];
            }
            
            $dataSubRegions = DB::table('communities')
                ->where('communities.is_archived', 0)
                ->join('sub_regions', 'communities.sub_region_id', '=', 'sub_regions.id')
                ->select(
                        DB::raw('sub_regions.english_name as english_name'),
                        DB::raw('count(*) as number'))
                ->groupBy('sub_regions.english_name')
                ->get();
            $arraySubRegions[] = ['English Name', 'Number'];
            
            foreach($dataSubRegions as $key => $value) {
    
                $arraySubRegions[++$key] = [$value->english_name, $value->number];
            }
    
            $energySystemTypes = EnergySystemType::where('is_archived', 0)->get();
            $donors = Donor::where('is_archived', 0)
                ->orderBy('donor_name', 'ASC')
                ->get();
            $waterSources = WaterSource::where('is_archived', 0)->get();
            
            $energyCycles = EnergySystemCycle::get();

            return view('employee.community.index', compact('communities', 'regions', 
                'communityRecords', 'communityWater', 'communityInternet', 'subregions',
                'communitiesWater', 'communitiesInternet', 'communitiesAC', 'communityAC',
                'products', 'energyTypes', 'communitiesInitial', 'communityInitial', 
                'communitiesSurvyed', 'communitySurvyed', 'settlements', 'towns',
                'publicCategories', 'energySystemTypes', 'publicStructures', 'donors',
                'waterSources', 'communityStatuses', 'energyCycles'))
                ->with('regionsData', json_encode($array))->with(
                    'subRegionsData', json_encode($arraySubRegions)
                );
        } else {

            return view('errors.not-found');
        }
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
        $energySystemTypes = EnergySystemType::where('is_archived', 0)->get();
        $waterSources = WaterSource::where('is_archived', 0)->get();
        $energyCycles = EnergySystemCycle::get();
        $settlements = Settlement::where('is_archived', 0)->get();
        $towns = Town::where('is_archived', 0)->get();
        $publicCategories = PublicStructureCategory::where('is_archived', 0)
            ->orderBy('name', 'ASC')
            ->get();
        $publicStructures = PublicStructure::where('is_archived', 0)->get();
        $regions = Region::where('is_archived', 0)
            ->orderBy('english_name', 'ASC')
            ->get(); 
        $subregions = SubRegion::where('is_archived', 0)
            ->orderBy('english_name', 'ASC')
            ->get();
        $products = ProductType::where('is_archived', 0)->get();
        $energyTypes = EnergySystemType::where('is_archived', 0)->get();

        return view('employee.community.create', compact('regions', 'subregions',
            'products', 'energyTypes', 'settlements', 'energyCycles', 'towns',
            'publicCategories', 'energySystemTypes', 'publicStructures',
            'waterSources'));
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
        $community->energy_system_cycle_id = $request->energy_system_cycle_id;
        $community->location_gis = $request->location_gis;
        $community->number_of_compound = $request->number_of_compound;
        $community->number_of_people = $request->number_of_people;
        $community->number_of_household = $request->number_of_household;
        $community->is_fallah = $request->is_fallah;
        $community->is_bedouin = $request->is_bedouin;
        $community->land_status = $request->land_status;
        $community->lawyer = $request->lawyer;
        $community->energy_source = $request->energy_source;  
        $community->latitude = $request->latitude; 
        $community->longitude = $request->longitude;
        $community->notes = $request->notes;
        $community->demolition = $request->demolition;
        $community->demolition_number = $request->demolition_number;
        $community->demolition_executed = $request->demolition_executed;
        $community->last_demolition = $request->last_demolition;
        $community->demolition_legal_status = $request->demolition_legal_status;
        if($request->reception) $community->reception = $request->reception;
        $community->save();

        $id = $community->id;
 
        $gridCommunity = new GridCommunityCompound();
        $gridCommunity->community_id = $id;
        $gridCommunity->save();

        $lastCommunity = Community::findOrFail($id);
      
        if($request->addMoreInputFieldsCompoundName) {
            foreach($request->addMoreInputFieldsCompoundName as $compoundName) {
                if($compoundName["subject"] != NULL) {
                    Compound::create([
                        'english_name' => $compoundName["subject"],
                        'community_id' => $id,
                    ]);
                }
            }
        }
        
        if($request->waters) {
            for($i=0; $i < count($request->waters); $i++) {

                $communityWaterSource = new CommunityWaterSource();
                $communityWaterSource->water_source_id = $request->waters[$i];
                $communityWaterSource->community_id = $id;
                $communityWaterSource->save();
            }
        }

        if($request->product_type_id) {
            for($i=0; $i < count($request->product_type_id); $i++) {

                $communityProduct = new CommunityProduct();
                $communityProduct->product_type_id = $request->product_type_id[$i];
                $communityProduct->community_id = $id;
                $communityProduct->save();
            }
        }

        if($request->settlement) {
            for($i=0; $i < count($request->settlement); $i++) {

                $settlement = new NearbySettlement();
                $settlement->settlement_id = $request->settlement[$i];
                $settlement->community_id = $id;
                $settlement->save();
            }
        }

        if($request->towns) {
            for($i=0; $i < count($request->towns); $i++) {

                $town = new NearbyTown();
                $town->town_id = $request->towns[$i];
                $town->community_id = $id;
                $town->save();
            }
        }

        if($request->recommended_energy_system_id) {
            for($i=0; $i < count($request->recommended_energy_system_id); $i++) {

                $recommendedEnergy = new RecommendedCommunityEnergySystem();
                $recommendedEnergy->energy_system_type_id = $request->recommended_energy_system_id[$i];
                $recommendedEnergy->community_id = $id;
                $recommendedEnergy->save();
            }
        }

        if($request->public_structures) {
            for($i=0; $i < count($request->public_structures); $i++) {

                if($request->public_structures[$i] == 1) {

                    $publicStructure = new PublicStructure();
                    $publicStructure->community_id = $lastCommunity->id;
                    $publicStructure->english_name = "School " . $lastCommunity->english_name;
                    $publicStructure->arabic_name = "مدرسة  " . $lastCommunity->arabic_name;
                    $publicStructure->public_structure_category_id1 = 1;
                    $publicStructure->save();

                    $schoolPublic = new SchoolPublicStructure();
                    $schoolPublic->public_structure_id = $publicStructure->id;
                    if($request->school_students) $schoolPublic->number_of_students = $request->school_students; 
                    if($request->school_male) $schoolPublic->number_of_boys = $request->school_male; 
                    if($request->school_female) $schoolPublic->number_of_girls = $request->school_female; 
                    if($request->grade_from) $schoolPublic->grade_from = $request->grade_from; 
                    if($request->grade_to) $schoolPublic->grade_to = $request->grade_to; 
                    $schoolPublic->save();

                }
                if($request->public_structures[$i] == 2) {

                    $publicStructure = new PublicStructure();
                    $publicStructure->community_id = $lastCommunity->id;
                    $publicStructure->english_name = "Mosque " . $lastCommunity->english_name;
                    $publicStructure->arabic_name = "مسجد  " . $lastCommunity->arabic_name;
                    $publicStructure->public_structure_category_id1 = 2;
                    $publicStructure->save();
                }
                if($request->public_structures[$i] == 3) {

                    $publicStructure = new PublicStructure();
                    $publicStructure->community_id = $lastCommunity->id;
                    $publicStructure->english_name = "Clinic " . $lastCommunity->english_name;
                    $publicStructure->arabic_name = "عيادة  " . $lastCommunity->arabic_name;
                    $publicStructure->public_structure_category_id1 = 3;
                    $publicStructure->save();
                }
                if($request->public_structures[$i] == 4) {

                    $publicStructure = new PublicStructure();
                    $publicStructure->community_id = $lastCommunity->id;
                    $publicStructure->english_name = "Council " . $lastCommunity->english_name;
                    $publicStructure->arabic_name = "مجلس  " . $lastCommunity->arabic_name;
                    $publicStructure->public_structure_category_id1 = 4;
                    $publicStructure->save();
                }
                if($request->public_structures[$i] == 5) {

                    $publicStructure = new PublicStructure();
                    $publicStructure->community_id = $lastCommunity->id;
                    $publicStructure->english_name = "Kindergarten " . $lastCommunity->english_name;
                    $publicStructure->arabic_name = "روضة  " . $lastCommunity->arabic_name;
                    $publicStructure->public_structure_category_id1 = 5;
                    $publicStructure->save();
                }
                if($request->public_structures[$i] == 6) {

                    $publicStructure = new PublicStructure();
                    $publicStructure->community_id = $lastCommunity->id;
                    $publicStructure->english_name = "Community Center " . $lastCommunity->english_name;
                    $publicStructure->arabic_name = "مركز التجمع   " . $lastCommunity->arabic_name;
                    $publicStructure->public_structure_category_id1 = 6;
                    $publicStructure->save();
                }
                if($request->public_structures[$i] == 7) {

                    $publicStructure = new PublicStructure();
                    $publicStructure->community_id = $lastCommunity->id;
                    $publicStructure->english_name = "Madafah " . $lastCommunity->english_name;
                    $publicStructure->arabic_name = "مضافة  " . $lastCommunity->arabic_name;
                    $publicStructure->public_structure_category_id1 = 7;
                    $publicStructure->save();
                }
                if($request->public_structures[$i] == 8) {

                    $publicStructure = new PublicStructure();
                    $publicStructure->community_id = $lastCommunity->id;
                    $publicStructure->english_name = "Water System " . $lastCommunity->english_name;
                    $publicStructure->arabic_name = "نظام الماء  " . $lastCommunity->arabic_name;
                    $publicStructure->public_structure_category_id1 = 8;
                    $publicStructure->save();
                }
                if($request->public_structures[$i] == 9) {

                    $publicStructure = new PublicStructure();
                    $publicStructure->community_id = $lastCommunity->id;
                    $publicStructure->english_name = "Electricity System " . $lastCommunity->english_name;
                    $publicStructure->arabic_name = "نظام الكهرباء  " . $lastCommunity->arabic_name;
                    $publicStructure->public_structure_category_id1 = 9;
                    $publicStructure->save();
                }
            }
        }

        if($request->is_kindergarten == "yes") {

            $publicStructureKindergarten = PublicStructure::where('is_archived', 0)
                ->where('community_id', $id)
                ->where('public_structure_category_id1', 5)
                ->orWhere('public_structure_category_id2', 5)
                ->orWhere('public_structure_category_id3', 5)
                ->first();
            if($publicStructureKindergarten) {

                $publicStructureKindergarten->kindergarten_students = $request->kindergarten_students; 
                $publicStructureKindergarten->kindergarten_male = $request->kindergarten_male; 
                $publicStructureKindergarten->kindergarten_female = $request->kindergarten_female; 
                $publicStructureKindergarten->save(); 
            }
            if($request->kindergarten_students) $lastCommunity->kindergarten_students = $request->kindergarten_students; 
            if($request->kindergarten_male) $lastCommunity->kindergarten_male = $request->kindergarten_male; 
            if($request->kindergarten_female) $lastCommunity->kindergarten_female = $request->kindergarten_female; 
            $lastCommunity->save();

        } else if($request->is_kindergarten == "no") {

            if($request->kindergarten_town_id) $lastCommunity->kindergarten_town_id = $request->kindergarten_town_id; 
            if($request->kindergarten_students) $lastCommunity->kindergarten_students = $request->kindergarten_students; 
            if($request->kindergarten_male) $lastCommunity->kindergarten_male = $request->kindergarten_male; 
            if($request->kindergarten_female) $lastCommunity->kindergarten_female = $request->kindergarten_female;
            $lastCommunity->save(); 
        }

        if($request->is_school == "yes") {

            $publicStructureSchool = PublicStructure::where('is_archived', 0)
                ->where('community_id', $id)
                ->where('public_structure_category_id1', 1)
                ->orWhere('public_structure_category_id2', 1)
                ->orWhere('public_structure_category_id3', 1)
                ->first();
                
            if($publicStructureSchool) {

                $newPublicSchool = SchoolPublicStructure::where('is_archived', 0)
                    ->where('public_structure_id', $publicStructureSchool->id)
                    ->first();

                if($newPublicSchool) {

                    $newPublicSchool->number_of_students = $request->school_students; 
                    $newPublicSchool->number_of_boys = $request->school_male; 
                    $newPublicSchool->number_of_girls = $request->school_female; 
                    $newPublicSchool->grade_from = $request->grade_from; 
                    $newPublicSchool->grade_to = $request->grade_to;  
                    $newPublicSchool->save();
                }
            }

            if($request->school_students) $lastCommunity->school_students = $request->school_students; 
            if($request->school_male) $lastCommunity->school_male = $request->school_male; 
            if($request->school_female) $lastCommunity->school_female = $request->school_female; 
            if($request->grade_from) $lastCommunity->grade_from = $request->grade_from; 
            if($request->grade_to) $lastCommunity->grade_to = $request->grade_to;  
            $lastCommunity->save();

        } else if($request->is_school == "no") {
            
            if($request->school_town_id) $lastCommunity->school_town_id = $request->school_town_id; 
            if($request->school_students) $lastCommunity->school_students = $request->school_students; 
            if($request->school_male) $lastCommunity->school_male = $request->school_male; 
            if($request->school_female) $lastCommunity->school_female = $request->school_female; 
            if($request->grade_from) $lastCommunity->grade_from = $request->grade_from; 
            if($request->grade_to) $lastCommunity->grade_to = $request->grade_to;  
            $lastCommunity->save(); 
        }

        if($request->second_name_english) {

            $secondNameCommunity = SecondNameCommunity::where('community_id', $id)->first();
            if($secondNameCommunity) {

                $secondNameCommunity->english_name = $request->second_name_english;  
            } else {

                $secondNameCommunity = new SecondNameCommunity();
                $secondNameCommunity->english_name = $request->second_name_english; 
                $secondNameCommunity->community_id = $id;
            }
            $secondNameCommunity->save();
        }

        if($request->second_name_arabic) {

            $secondNameCommunity = SecondNameCommunity::where('community_id', $id)->first();
            if($secondNameCommunity) {

                $secondNameCommunity->arabic_name = $request->second_name_arabic;  
            } else {

                $secondNameCommunity = new SecondNameCommunity();
                $secondNameCommunity->arabic_name = $request->second_name_arabic; 
                $secondNameCommunity->community_id = $id;
            }
            $secondNameCommunity->save();
        } 

        return redirect('/community')->with('message', 'New Community Inserted Successfully!');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getByRegion(Request $request)
    {
        $regions = SubRegion::where('region_id', $request->region_id)
            ->where('is_archived', 0)
            ->get();
 
        if (!$request->region_id) {
            $html = '<option value="">Choose One...</option>';
        } else {
            $html = '<option value="">Choose One...</option>';
            $regions = SubRegion::where('region_id', $request->region_id)
                ->where('is_archived', 0)
                ->get();
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
    public function deleteCommunity(Request $request)
    {
        $id = $request->id;

        $community = Community::findOrFail($id);
        $community->is_archived = 1;
        $community->save();

        $communityWaterSources = CommunityWaterSource::where('community_id', $id)->get();
        $compounds = Compound::where('community_id', $id)->get();
        $nearbyTowns = NearbyTown::where('community_id', $id)->get();
        $nearbySettlements = NearbySettlement::where('community_id', $id)->get();
        $secondName = SecondNameCommunity::where('community_id', $id)->first();

        if($communityWaterSources) {
            foreach($communityWaterSources as $communityWaterSource) {
                $communityWaterSource->is_archived = 1;
                $communityWaterSource->save();
            }
        }

        if($compounds) {
            foreach($compounds as $compound) {
                $compound->is_archived = 1;
                $compound->save();
            }
        }

        if($nearbyTowns) {
            foreach($nearbyTowns as $nearbyTown) {
                $nearbyTown->is_archived = 1;
                $nearbyTown->save();
            }
        }

        if($nearbySettlements) {
            foreach($nearbySettlements as $nearbySettlement) {
                $nearbySettlement->is_archived = 1;
                $nearbySettlement->save();
            }
        }

        if($secondName) {
            $secondName->is_archived = 1;
            $secondName->save();
        }

        $response['success'] = 1;
        $response['msg'] = 'Community Deleted successfully'; 
        
        return response()->json($response); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function deleteCommunityCompound(Request $request)
    {
        $id = $request->id;

        $compound = Compound::findOrFail($id);

        if($compound) {

            $compound->is_archived = 1;
            $compound->save();

            $response['success'] = 1;
            $response['msg'] = 'Compound Deleted successfully'; 
        }

        return response()->json($response); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function deleteCommunityWaterSources(Request $request)
    {
        $id = $request->id;

        $communityWater = CommunityWaterSource::findOrFail($id);

        if($communityWater) {

            $communityWater->delete();

            $response['success'] = 1;
            $response['msg'] = 'Water Source Deleted successfully'; 
        }

        return response()->json($response); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function deletecommunityNearbyTowns(Request $request)
    {
        $id = $request->id;

        $nearbyTown = NearbyTown::findOrFail($id);

        if($nearbyTown) {

            $nearbyTown->delete();

            $response['success'] = 1;
            $response['msg'] = 'Nearby Town Deleted successfully'; 
        }

        return response()->json($response); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function deleteCommunityNearbySettlements(Request $request)
    {
        $id = $request->id;

        $nearbySettlement = NearbySettlement::findOrFail($id);

        if($nearbySettlement) {

            $nearbySettlement->delete();

            $response['success'] = 1;
            $response['msg'] = 'Nearby Settlement Deleted successfully'; 
        }

        return response()->json($response); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function deletecommunityProductTypes(Request $request)
    {
        $id = $request->id;

        $communityProduct = CommunityProduct::findOrFail($id);

        if($communityProduct) {

            $communityProduct->delete();

            $response['success'] = 1;
            $response['msg'] = 'Product Deleted successfully'; 
        }

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
        $community = Community::findOrFail($id);
        $region = Region::where('id', $community->region_id)->first();
        $subRegion = SubRegion::where('id', $community->sub_region_id)->first();
        $status = CommunityStatus::where('id', $community->community_status_id)->first();
        $publicStructures = PublicStructure::where('community_id', $community->id)
            ->where('comet_meter', 0)
            ->get();
        $nearbySettlements = DB::table('nearby_settlements')
            ->where('nearby_settlements.is_archived', 0)
            ->join('communities', 'nearby_settlements.community_id', '=', 'communities.id')
            ->join('settlements', 'nearby_settlements.settlement_id', '=', 'settlements.id')
            ->where('community_id', $community->id)
            ->select('settlements.english_name')
            ->get();
        $nearbyTowns = DB::table('nearby_towns')
            ->where('nearby_towns.is_archived', 0)
            ->join('communities', 'nearby_towns.community_id', '=', 'communities.id')
            ->join('towns', 'nearby_towns.town_id', '=', 'towns.id')
            ->where('community_id', $community->id)
            ->select('towns.english_name')
            ->get();
        $compounds = Compound::where('community_id', $community->id)
            ->where('is_archived', 0)
            ->get();
        $communityWaterSources = DB::table('community_water_sources')
            ->where('community_water_sources.is_archived', 0)
            ->join('communities', 'community_water_sources.community_id', '=', 'communities.id')
            ->join('water_sources', 'community_water_sources.water_source_id', '=', 'water_sources.id')
            ->where('community_id', $community->id)
            ->select('water_sources.name')
            ->get();
        $communityRecommendedEnergy = DB::table('recommended_community_energy_systems')
            ->where('recommended_community_energy_systems.is_archived', 0)
            ->join('communities', 'recommended_community_energy_systems.community_id', 
                '=', 'communities.id')
            ->join('energy_system_types', 'recommended_community_energy_systems.energy_system_type_id', 
                '=', 'energy_system_types.id')
            ->where('community_id', $community->id)
            ->select('energy_system_types.name')
            ->get();  
            
        $communityRepresentative = DB::table('community_representatives')
            ->where('community_representatives.is_archived', 0)
            ->join('communities', 'community_representatives.community_id', '=', 'communities.id')
            ->join('households', 'community_representatives.household_id', '=', 'households.id')
            ->join('community_roles', 'community_representatives.community_role_id', '=', 'community_roles.id')
            ->where('community_representatives.community_id', $community->id)
            ->select('households.english_name', 'community_roles.role', 'households.phone_number')
            ->get();

        $secondName = SecondNameCommunity::where('community_id', $id)->first();

        $totalMeters = AllEnergyMeter::where("community_id", $id)->count();
        $energyDonors = DB::table('community_donors')
            ->join('donors', 'community_donors.donor_id', 'donors.id')
            ->join('service_types', 'community_donors.service_id', 'service_types.id')
            ->where('community_donors.is_archived', 0)
            ->where('community_donors.service_id', 1)
            ->where('community_donors.community_id', $id)
            ->select('donors.donor_name')
            ->get();

        $waterDonors = DB::table('community_donors')
            ->join('donors', 'community_donors.donor_id', 'donors.id')
            ->join('service_types', 'community_donors.service_id', 'service_types.id')
            ->where('community_donors.is_archived', 0)
            ->where('community_donors.service_id', 2)
            ->where('community_donors.community_id', $id)
            ->select('donors.donor_name')
            ->get();

        $internetDonors = DB::table('community_donors')
            ->join('donors', 'community_donors.donor_id', 'donors.id')
            ->join('service_types', 'community_donors.service_id', 'service_types.id')
            ->where('community_donors.is_archived', 0)
            ->where('community_donors.service_id', 3)
            ->where('community_donors.community_id', $id)
            ->select('donors.donor_name')
            ->get();

 
        $totalWaterHolders = H2oUser::where("is_archived", 0)
            ->where("community_id", $id)
            ->count();

        $gridLarge = GridUser::where('is_archived', 0)
            ->where('grid_integration_large', '!=', 0)
            ->where("community_id", $id)
            ->selectRaw('SUM(grid_integration_large) AS sum')
            ->first();
        $gridSmall = GridUser::where('is_archived', 0)
            ->where('grid_integration_small', '!=', 0)
            ->where("community_id", $id)
            ->selectRaw('SUM(grid_integration_small) AS sum')
            ->first();

        $internetHolders = InternetUser::where("is_archived", 0)
            ->where("community_id", $id)
            ->count();

        $photos = Photo::where("community_id", $id)->get();
        $communityProductTypes = DB::table('community_products')
            ->join('communities', 'community_products.community_id', 'communities.id')
            ->join('product_types', 'community_products.product_type_id', 'product_types.id')
            ->where('community_id', $community->id)
            ->select('product_types.name')
            ->get();

        $totalSchoolStudents = DB::table('households')
            ->join('communities', 'communities.id', 'households.community_id')
            ->where('households.is_archived', 0)
            ->where('community_id', $community->id)
            ->selectRaw('SUM(households.school_students) AS total_school')
            ->first();

        $totalUnivesrityStudents = DB::table('households')
            ->join('communities', 'communities.id', 'households.community_id')
            ->where('households.is_archived', 0)
            ->where('community_id', $community->id)
            ->selectRaw('SUM(households.university_students) AS total_university')
            ->first();

        $totalAdults = DB::table('households')
            ->join('communities', 'communities.id', 'households.community_id')
            ->where('households.is_archived', 0)
            ->where('community_id', $community->id)
            ->selectRaw('SUM(households.number_of_adults) AS total_adult')
            ->first();

        $totalChildren = DB::table('households')
            ->join('communities', 'communities.id', 'households.community_id')
            ->where('households.is_archived', 0)
            ->where('community_id', $community->id)
            ->selectRaw('SUM(households.number_of_children) AS total_children')
            ->first();

        $schools = null;
        $neighboringCommunitySchool1 = null;
        $neighboringCommunitySchool2 = null;
        $neighboringTownSchool = null;

        $schoolCommunity = PublicStructure::where("community_id", $id)
            ->where('public_structure_category_id1', 1)
            ->orWhere('public_structure_category_id2', 1)
            ->orWhere('public_structure_category_id3', 1)
            ->first();

        if($schoolCommunity) $schools = SchoolPublicStructure::where("public_structure_id", $schoolCommunity->id)->first();

        $neighboringCommunitySchools = SchoolCommunity::where("community_id", $id)
            ->whereNull("town_id")
            ->get();
       
        if($neighboringCommunitySchools->count() == 1) {

            $neighboringCommunitySchool1 = $neighboringCommunitySchools->first();
            $neighboringCommunitySchool2 = null;
        } else if($neighboringCommunitySchools->count() > 1) {

            $neighboringCommunitySchool1 = $neighboringCommunitySchools->first();
            $neighboringCommunitySchool2 = $neighboringCommunitySchools->get(1);
        } else {

            $neighboringCommunitySchool1 = null;
            $neighboringCommunitySchool2 = null;
        } 

        $neighboringTownSchool = SchoolCommunity::where("community_id", $id)
            ->where("town_id", "!=", NULL)
            ->first();

        return view('employee.community.show', compact('community', 'energyDonors', 'waterDonors',
            'internetDonors', 'nearbySettlements', 'totalMeters', 'communityWaterSources',
            'totalWaterHolders', 'gridLarge', 'gridSmall', 'internetHolders', 'secondName',
            'communityRepresentative', 'publicStructures', 'compounds', 'nearbyTowns', 'photos',
            'communityProductTypes', 'totalSchoolStudents', 'totalUnivesrityStudents', 'totalAdults',
            'totalChildren', 'schools', 'neighboringCommunitySchool1', 'neighboringCommunitySchool2', 
            'neighboringTownSchool', 'schoolCommunity'));
    }

    /**
     * Get the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function photo($id)
    {
        $community = Community::findOrFail($id);
        $photos = Photo::where("community_id", $id)->get();

        return view('employee.community.photo', compact('community', 'photos'));
    }

    /**
     * Get the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function map($id)
    {
        $community = Community::findOrFail($id);

        return view('employee.community.map', compact('community'));
    }

    /**
     * 
     * @return \Illuminate\Support\Collection
     */
    public function export(Request $request) 
    {
                
        return Excel::download(new CommunityExport($request), 'communities.xlsx');
    }
 
    /**
     * View Edit page.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function editPage($id)
    {
        $community = Community::findOrFail($id);

        return response()->json($community);
    } 
 
    /**
     * View Edit page.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) 
    {
        $community = Community::findOrFail($id);
        $communityStatuses = CommunityStatus::where('is_archived', 0)->get();
        $products = ProductType::where('is_archived', 0)->get();
        $regions = Region::where('is_archived', 0)
            ->orderBy('english_name', 'ASC')
            ->get();
        $subRegions = SubRegion::where('is_archived', 0)
            ->orderBy('english_name', 'ASC')
            ->get();
        $secondName = SecondNameCommunity::where('community_id', $id)->where('is_archived', 0)->first();

        $compounds = Compound::where('community_id', $community->id)
            ->where('is_archived', 0)
            ->get();

        $recommendedEnergySystems = RecommendedCommunityEnergySystem::where('community_id', $id)
            ->where('is_archived', 0)
            ->get();

        $communityWaterSources = CommunityWaterSource::where('community_id', $id)
            ->where('is_archived', 0)
            ->get();

        $communityNearbyTowns = NearbyTown::where('community_id', $id)
            ->where('is_archived', 0)
            ->get();

        $communityNearbySettlements = NearbySettlement::where('community_id', $id)
            ->where('is_archived', 0)
            ->get();

        $communityProductTypes = CommunityProduct::where('community_id', $id)
            ->get();

        $energySystemTypes = EnergySystemType::where('is_archived', 0)->get();
        $energyCycles = EnergySystemCycle::get();
        $waterSources = WaterSource::where('is_archived', 0)->get();

        $towns = Town::where('is_archived', 0)
            ->orderBy('english_name', 'ASC')
            ->get();

        $settlements = Settlement::where('is_archived', 0)
            ->orderBy('english_name', 'ASC')
            ->get();

        $schoolCommunity = PublicStructure::where("community_id", $id)
            ->where('public_structure_category_id1', 1)
            ->orWhere('public_structure_category_id2', 1)
            ->orWhere('public_structure_category_id3', 1)
            ->first();

        $schools = null;
        $neighboringCommunitySchool1 = null;
        $neighboringCommunitySchool2 = null;
        $neighboringTownSchool = null;

        $schoolCommunities = PublicStructure::where("is_archived", 0)
            ->where("community_id", '!=', $id)
            ->where('public_structure_category_id1', 1)
            ->orWhere('public_structure_category_id2', 1)
            ->orWhere('public_structure_category_id3', 1)
            ->get();

        if($schoolCommunity) $schools = SchoolPublicStructure::where("public_structure_id", $schoolCommunity->id)->first();

        $neighboringCommunitySchools = SchoolCommunity::where("community_id", $id)
            ->whereNull("town_id")
            ->get();
       
        if($neighboringCommunitySchools->count() == 1) {

            $neighboringCommunitySchool1 = $neighboringCommunitySchools->first();
            $neighboringCommunitySchool2 = null;
        } else if($neighboringCommunitySchools->count() > 1) {

            $neighboringCommunitySchool1 = $neighboringCommunitySchools->first();
            $neighboringCommunitySchool2 = $neighboringCommunitySchools->get(1);
        } else {

            $neighboringCommunitySchool1 = null;
            $neighboringCommunitySchool2 = null;
        }

        $neighboringTownSchool = SchoolCommunity::where("community_id", $id)
            ->where("town_id", "!=", NULL)
            ->first();

        return view('employee.community.edit', compact('community', 'products', 
            'communityStatuses', 'regions', 'subRegions', 'secondName', 'compounds',
            'recommendedEnergySystems', 'energySystemTypes', 'energyCycles', 'waterSources',
            'communityWaterSources', 'communityNearbyTowns', 'towns', 'settlements', 'schoolCommunities',
            'communityNearbySettlements', 'communityProductTypes', 'schools', 'schoolCommunity',
            'neighboringTownSchool', 'neighboringCommunitySchool1', 'neighboringCommunitySchool2'));
    }

    /**
     * Update an existing resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request, int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $community = Community::findOrFail($id);

        if($request->english_name) $community->english_name = $request->english_name;
        if($request->arabic_name) $community->arabic_name = $request->arabic_name;
        if($request->region_id) $community->region_id = $request->region_id;
        if($request->sub_region_id) $community->sub_region_id = $request->sub_region_id;
        if($request->energy_system_cycle_id) {

            $community->energy_system_cycle_id = $request->energy_system_cycle_id;
            $households = Household::where("community_id", $id)->get();

            if($households) {

                foreach($households as $household) {

                    $household->energy_system_cycle_id = $request->energy_system_cycle_id;
                    $household->save();
                }
            }

            $allEnergyMeters = AllEnergyMeter::where("community_id", $id)->get();
            if($allEnergyMeters) {

                foreach($allEnergyMeters as $allEnergyMeter) {

                    $allEnergyMeter->energy_system_cycle_id = $request->energy_system_cycle_id;
                    $allEnergyMeter->save();
                }
            }
        }
        if($request->community_status_id) $community->community_status_id = $request->community_status_id;
        if($request->reception) $community->reception = $request->reception;
        if($request->number_of_household) $community->number_of_household = $request->number_of_household;
        if($request->number_of_people) $community->number_of_people = $request->number_of_people;
        if($request->is_fallah) $community->is_fallah = $request->is_fallah;
        if($request->is_bedouin) $community->is_bedouin = $request->is_bedouin;
        if($request->demolition) $community->demolition = $request->demolition;
        if($request->demolition_number) $community->demolition_number = $request->demolition_number;
        if($request->demolition_executed) $community->demolition_executed = $request->demolition_executed;
        if($request->last_demolition) $community->last_demolition = $request->last_demolition;
        if($request->demolition_legal_status) $community->demolition_legal_status = $request->demolition_legal_status;
        if($request->land_status) $community->land_status = $request->land_status;
        if($request->is_surveyed) $community->is_surveyed = $request->is_surveyed;
        if($request->last_surveyed_date) $community->last_surveyed_date = $request->last_surveyed_date;

        if($request->energy_service) {

            $community->energy_service = $request->energy_service;

            $existCommunityService = CommunityService::where("community_id", $id)
                ->where("service_id", 1)
                ->first();

            if($existCommunityService) {

            } else {

                $communityService = new CommunityService();
                $communityService->service_id = 1;
                $communityService->community_id = $id;
                $communityService->save();
            }
        }
        
        if($request->energy_service_beginning_year) $community->energy_service_beginning_year = $request->energy_service_beginning_year;
        
        if($request->water_service) {

            $community->water_service = $request->water_service;
            $existCommunityService = CommunityService::where("community_id", $id)
                ->where("service_id", 2)
                ->first();

            if($existCommunityService) {

            } else {

                $communityService = new CommunityService();
                $communityService->service_id = 2;
                $communityService->community_id = $id;
                $communityService->save();
            }
        }
        
        if($request->water_service_beginning_year) $community->water_service_beginning_year = $request->water_service_beginning_year;
       
        if($request->internet_service) {

            $community->internet_service = $request->internet_service;
            $existCommunityService = CommunityService::where("community_id", $id)
                ->where("service_id", 3)
                ->first();

            if($existCommunityService) {

            } else {

                $communityService = new CommunityService();
                $communityService->service_id = 3;
                $communityService->community_id = $id;
                $communityService->save();
            }
        }
      
        if($request->internet_service_beginning_year) $community->internet_service_beginning_year = $request->internet_service_beginning_year;
        if($request->description) $community->description = $request->description;
        if($request->latitude) $community->latitude = $request->latitude; 
        if($request->longitude) $community->longitude = $request->longitude;
        if($request->lawyer) $community->lawyer = $request->lawyer;
        if($request->notes) $community->notes = $request->notes;
        $community->save();

        $lastCommunity = Community::findOrFail($id);
        
        if($request->is_kindergarten == "yes") {

            $publicStructureKindergarten = PublicStructure::where('is_archived', 0)
                ->where('community_id', $id)
                ->where('public_structure_category_id1', 5)
                ->orWhere('public_structure_category_id2', 5)
                ->orWhere('public_structure_category_id3', 5)
                ->first();
            if($publicStructureKindergarten) {

                $publicStructureKindergarten->kindergarten_students = $request->kindergarten_students; 
                $publicStructureKindergarten->kindergarten_male = $request->kindergarten_male; 
                $publicStructureKindergarten->kindergarten_female = $request->kindergarten_female; 
                $publicStructureKindergarten->save(); 
            }
            if($request->kindergarten_students) $lastCommunity->kindergarten_students = $request->kindergarten_students; 
            if($request->kindergarten_male) $lastCommunity->kindergarten_male = $request->kindergarten_male; 
            if($request->kindergarten_female) $lastCommunity->kindergarten_female = $request->kindergarten_female; 
            $lastCommunity->save();

        } else if($request->is_kindergarten == "no") {

            if($request->kindergarten_town_id) $lastCommunity->kindergarten_town_id = $request->kindergarten_town_id; 
            if($request->kindergarten_students) $lastCommunity->kindergarten_students = $request->kindergarten_students; 
            if($request->kindergarten_male) $lastCommunity->kindergarten_male = $request->kindergarten_male; 
            if($request->kindergarten_female) $lastCommunity->kindergarten_female = $request->kindergarten_female;
            $lastCommunity->save(); 
        }

        $schoolPublic = PublicStructure::where('is_archived', 0)
            ->where("community_id", $id)
            ->where('public_structure_category_id1', 1)
            ->orWhere('public_structure_category_id2', 1)
            ->orWhere('public_structure_category_id3', 1)
            ->first();

        if($schoolPublic) {

            $school = SchoolPublicStructure::where("public_structure_id", $schoolPublic->id)->first();
            if($request->number_of_students) $school->number_of_students = $request->number_of_students;
            if($request->number_of_boys) $school->number_of_boys = $request->number_of_boys;
            if($request->number_of_girls) $school->number_of_girls = $request->number_of_girls;
            if($request->grade_from_community) $school->grade_from = $request->grade_from_community;
            if($request->grade_to_community) $school->grade_to = $request->grade_to_community;
            $school->save();
        }

        $neighboringCommunitySchool1 = null;
        $neighboringCommunitySchool2 = null;

        $neighboringCommunitySchools = SchoolCommunity::where("community_id", $id)
            ->whereNull("town_id")
            ->get();
    
        if($neighboringCommunitySchools->count() == 1) {

            $neighboringCommunitySchool1 = $neighboringCommunitySchools->first();
            $neighboringCommunitySchool2 = null;
        } else if($neighboringCommunitySchools->count() > 1) {

            $neighboringCommunitySchool1 = $neighboringCommunitySchools->first();
            $neighboringCommunitySchool2 = $neighboringCommunitySchools->get(1);
        } else {

            $neighboringCommunitySchool1 = null;
            $neighboringCommunitySchool2 = null;
        }

        if($neighboringCommunitySchool1) {

            if($request->neighboring_school1) $neighboringCommunitySchool1->school_public_structure_id = $request->neighboring_school1;
            if($request->school_students1) $neighboringCommunitySchool1->number_of_student_school = $request->school_students1;
            if($request->school_male1) $neighboringCommunitySchool1->number_of_male = $request->school_male1;
            if($request->school_female1) $neighboringCommunitySchool1->number_of_female = $request->school_female1;
            if($request->grade_from_school1) $neighboringCommunitySchool1->grade_from_school = $request->grade_from_school1;
            if($request->grade_to_school1) $neighboringCommunitySchool1->grade_to_school = $request->grade_to_school1;
            $neighboringCommunitySchool1->save();
        } else {

            $neighboringCommunitySchool1 = new SchoolCommunity();
            $neighboringCommunitySchool1->community_id = $id;
            if($request->neighboring_school1) {
                $neighboringCommunitySchool1->school_public_structure_id = $request->neighboring_school1;
                if($request->school_students1) $neighboringCommunitySchool1->number_of_student_school = $request->school_students1;
                if($request->school_male1) $neighboringCommunitySchool1->number_of_male = $request->school_male1;
                if($request->school_female1) $neighboringCommunitySchool1->number_of_female = $request->school_female1;
                if($request->grade_from_school1) $neighboringCommunitySchool1->grade_from_school = $request->grade_from_school1;
                if($request->grade_to_school1) $neighboringCommunitySchool1->grade_to_school = $request->grade_to_school1;
                $neighboringCommunitySchool1->save();
            }
        }

        if($neighboringCommunitySchool2) {

            if($request->neighboring_school2) $neighboringCommunitySchool2->school_public_structure_id = $request->neighboring_school2;
            if($request->school_students2) $neighboringCommunitySchool2->number_of_student_school = $request->school_students2;
            if($request->school_male2) $neighboringCommunitySchool2->number_of_male = $request->school_male2;
            if($request->school_female2) $neighboringCommunitySchool2->number_of_female = $request->school_female2;
            if($request->grade_from_school2) $neighboringCommunitySchool2->grade_from_school = $request->grade_from_school2;
            if($request->grade_to_school2) $neighboringCommunitySchool2->grade_to_school = $request->grade_to_school2;
            $neighboringCommunitySchool2->save();
        } else {

            $neighboringCommunitySchool2 = new SchoolCommunity();
            $neighboringCommunitySchool2->community_id = $id;
            if($request->neighboring_school2) {
                $neighboringCommunitySchool2->school_public_structure_id = $request->neighboring_school2;
                if($request->school_students2) $neighboringCommunitySchool2->number_of_student_school = $request->school_students2;
                if($request->school_male2) $neighboringCommunitySchool2->number_of_male = $request->school_male2;
                if($request->school_female2) $neighboringCommunitySchool2->number_of_female = $request->school_female2;
                if($request->grade_from_school2) $neighboringCommunitySchool2->grade_from_school = $request->grade_from_school2;
                if($request->grade_to_school2) $neighboringCommunitySchool2->grade_to_school = $request->grade_to_school2;
                $neighboringCommunitySchool2->save();
            }
        }

        $neighboringTownSchool = SchoolCommunity::where("community_id", $id)->where("town_id", "!=", null)->first();
        if($neighboringTownSchool) {

            $neighboringTownSchool->number_of_student_school = $request->school_students_town;
            $neighboringTownSchool->number_of_male = $request->number_of_male;
            $neighboringTownSchool->number_of_female = $request->number_of_female;
            $neighboringTownSchool->grade_from_school = $request->grade_from_school;
            $neighboringTownSchool->grade_to_school = $request->grade_to_school;
            $neighboringTownSchool->save();
        } else {

            $neighboringTown = new SchoolCommunity();
            $neighboringTown->community_id = $id;
            if($request->school_town_id) {
                $neighboringTown->town_id = $request->school_town_id;
                $neighboringTown->number_of_student_school = $request->school_students_town;
                $neighboringTown->number_of_male = $request->number_of_male;
                $neighboringTown->number_of_female = $request->number_of_female;
                $neighboringTown->grade_from_school = $request->grade_from_school;
                $neighboringTown->grade_to_school = $request->grade_to_school;
                $neighboringTown->save();
            }
        }

        if($request->waters) {
            for($i=0; $i < count($request->waters); $i++) {

                $communityWaterSource = new CommunityWaterSource();
                $communityWaterSource->water_source_id = $request->waters[$i];
                $communityWaterSource->community_id = $community->id;
                $communityWaterSource->save();
            }
        }

        if($request->new_waters) {
            for($i=0; $i < count($request->new_waters); $i++) {

                $communityNewWaterSource = new CommunityWaterSource();
                $communityNewWaterSource->water_source_id = $request->new_waters[$i];
                $communityNewWaterSource->community_id = $community->id;
                $communityNewWaterSource->save();
            }
        }

        if($request->nearby_towns) {
            for($i=0; $i < count($request->nearby_towns); $i++) {

                $communityNearbyTown = new NearbyTown();
                $communityNearbyTown->town_id = $request->nearby_towns[$i];
                $communityNearbyTown->community_id = $community->id;
                $communityNearbyTown->save();
            }
        }

        if($request->new_nearby_towns) {
            for($i=0; $i < count($request->new_nearby_towns); $i++) {

                $communityNearbyTown = new NearbyTown();
                $communityNearbyTown->town_id = $request->new_nearby_towns[$i];
                $communityNearbyTown->community_id = $community->id;
                $communityNearbyTown->save();
            }
        }

        if($request->nearby_settlement) {
            for($i=0; $i < count($request->nearby_settlement); $i++) {

                $communityNearbySettlement = new NearbySettlement();
                $communityNearbySettlement->settlement_id = $request->nearby_settlement[$i];
                $communityNearbySettlement->community_id = $community->id;
                $communityNearbySettlement->save();
            }
        }

        if($request->new_nearby_settlement) {
            for($i=0; $i < count($request->new_nearby_settlement); $i++) {

                $communityNearbySettlement = new NearbySettlement();
                $communityNearbySettlement->settlement_id = $request->new_nearby_settlement[$i];
                $communityNearbySettlement->community_id = $community->id;
                $communityNearbySettlement->save();
            }
        }

        if($request->products) {
            for($i=0; $i < count($request->products); $i++) {

                $communityProduct = new CommunityProduct();
                $communityProduct->product_type_id = $request->products[$i];
                $communityProduct->community_id = $community->id;
                $communityProduct->save();
            }
        }

        if($request->new_products) {
            for($i=0; $i < count($request->new_products); $i++) {

                $communityProduct = new CommunityProduct();
                $communityProduct->product_type_id = $request->new_products[$i];
                $communityProduct->community_id = $community->id;
                $communityProduct->save();
            }
        }

        if($request->addMoreInputFieldsCompoundName) {
            foreach($request->addMoreInputFieldsCompoundName as $compoundName) {
                if($compoundName["subject"] != NULL) {
                    Compound::create([
                        'english_name' => $compoundName["subject"],
                        'community_id' => $community->id,
                    ]);
                }
            }
        }

        $secondNameCommunity = SecondNameCommunity::where('community_id', $id)->first();
        if($secondNameCommunity) {

            if($request->second_name_english) {

                $secondNameCommunity->english_name = $request->second_name_english; 
            }
            if($request->second_name_english) {
    
                $secondNameCommunity->arabic_name = $request->second_name_arabic;
            }
            $secondNameCommunity->community_id = $id;
            $secondNameCommunity->save();
        } else {

            $newSecondCommunity = new SecondNameCommunity();
            if($request->second_name_english) {

                $newSecondCommunity->english_name = $request->second_name_english; 
            }
            if($request->second_name_english) {
    
                $newSecondCommunity->arabic_name = $request->second_name_arabic;
            }
            $newSecondCommunity->community_id = $id;
            $newSecondCommunity->save();
        }

        return redirect('/community')->with('message', 'Community Updated Successfully!');
    }
}