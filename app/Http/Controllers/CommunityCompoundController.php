<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use DB;
use Route;
use App\Models\AllEnergyMeter;
use App\Models\User;
use App\Models\Community;
use App\Models\CommunityDonor;
use App\Models\CommunityStatus;
use App\Models\Compound;
use App\Models\CompoundHousehold;
use App\Models\CommunityWaterSource;
use App\Models\NearbyTown;
use App\Models\NearbySettlement;
use App\Models\CommunityProduct;
use App\Models\Donor;
use App\Models\EnergySystem;
use App\Models\EnergySystemType;
use App\Models\Household;
use App\Models\Settlement;
use App\Models\ServiceType;
use App\Models\PublicStructure;
use App\Models\PublicStructureCategory;
use App\Models\EnergySystemCycle;
use App\Models\ProductType;
use App\Models\Region;
use App\Models\H2oUser;
use App\Models\GridUser;
use App\Models\InternetUser;
use App\Models\Photo;
use App\Models\SubRegion;
use App\Models\WaterSource;
use App\Models\Town;
use App\Exports\CompoundHouseholdExport; 
use Carbon\Carbon;
use Image;
use DataTables;
use Excel;

class CommunityCompoundController extends Controller
{ 
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {	
        $data1 = DB::table('compound_households')
            ->select(
                'compound_households.compound_id AS id',
                DB::raw('COUNT(compound_households.household_id) as total_household'),
                )
            ->groupBy('compound_households.compound_id')
            ->get();

        foreach($data1 as $d) {

            $compound = Compound::findOrFail($d->id);
            $compound->number_of_household = $d->total_household;
            $compound->save();
        }

      
        $householdCounts = DB::table('compound_households')
            ->join('households', 'households.id', 'compound_households.household_id')
            ->select(
                'compound_households.compound_id AS id',
                DB::raw(
                    'SUM(CASE WHEN households.is_archived = 0 THEN households.number_of_adults + households.number_of_children 
                        ELSE 0 END) as total_people'),
                DB::raw(
                    'SUM(CASE WHEN households.is_archived = 0 THEN households.number_of_male + households.number_of_female 
                        ELSE 0 END) as total_people1')
                )
            ->groupBy('compound_households.compound_id')
            ->get();

        foreach($householdCounts as $householdCounts) {

            $compound = Compound::findOrFail($householdCounts->id);
            if($householdCounts->total_people > $householdCounts->total_people1) $compound->number_of_people = $householdCounts->total_people;
            else $compound->number_of_people = $householdCounts->total_people1;
            $compound->save();
        }

        if (Auth::guard('user')->user() != null) {

            $communityFilter = $request->input('filter');
            $regionFilter = $request->input('second_filter');
            $subRegionFilter = $request->input('third_filter');

            if ($request->ajax()) {

                $data = DB::table('compound_households')
                    ->join('communities', 'compound_households.community_id', 
                        'communities.id')
                    ->join('regions', 'communities.region_id', 'regions.id')
                    ->join('sub_regions', 'communities.sub_region_id', 'sub_regions.id')
                    ->join('households', 'compound_households.household_id', 
                        'households.id')
                    ->join('compounds', 'compound_households.compound_id', 
                        'compounds.id') 
                    ->where('compound_households.is_archived', 0)
                    ->where('households.is_archived', 0);
    
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
                    'compounds.english_name as english_name', 
                    'compounds.arabic_name as arabic_name',
                    'communities.english_name as community_english_name', 
                    'communities.arabic_name as community_arabic_name',
                    'compound_households.id as id', 'compound_households.created_at as created_at', 
                    'compound_households.updated_at as updated_at',
                    'communities.number_of_people as number_of_people',
                    'communities.number_of_household as number_of_household',
                    'regions.english_name as name',
                    'regions.arabic_name as aname',
                    'households.english_name as household')
                ->latest(); 

                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {

                        $empty = "";
                      //  $updateButton = "<a type='button' class='updateCompoundCommunityHousehold' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#updateCompoundCommunityModal' ><i class='fa-solid fa-pen-to-square text-success'></i></a>";
                        $deleteButton = "<a type='button' class='deleteCompoundHousehold' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";
    
                        if(Auth::guard('user')->user()->user_type_id == 1 || 
                            Auth::guard('user')->user()->user_type_id == 2 || 
                            Auth::guard('user')->user()->user_type_id == 3) 
                        {
                                
                            return $deleteButton;
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
                                ->orWhere('compounds.english_name', 'LIKE', "%$search%")
                                ->orWhere('compounds.arabic_name', 'LIKE', "%$search%")
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
            $compounds = Compound::where('is_archived', 0)->get();
            $households =  Household::where('is_archived', 0)
                ->orderBy('english_name', 'ASC')
                ->get();
            $energySystemTypes = EnergySystemType::where('is_archived', 0)
                ->orderBy('name', 'ASC')
                ->get();

            return view('admin.community.compound.index', compact('communities', 'regions', 
                'compounds', 'households', 'energySystemTypes', 'subregions'));
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
    public function deleteCompoundHousehold(Request $request)
    {
        $id = $request->id;

        $compoundCommunity = CompoundHousehold::findOrFail($id);
        $compoundCommunity->is_archived = 1;
        $compoundCommunity->save();

        $response['success'] = 1;
        $response['msg'] = 'Compound Household Deleted successfully'; 
        
        return response()->json($response); 
    }

    /**
     * 
     * @return \Illuminate\Support\Collection
     */
    public function export(Request $request) 
    {
                
        return Excel::download(new CompoundHouseholdExport($request), 
            'Compound Households.xlsx');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->household_id) {

            for($i=0; $i < count($request->household_id); $i++) {

                $compoundHousehold = new CompoundHousehold();
                $compoundHousehold->household_id = $request->household_id[$i];
                $compoundHousehold->compound_id = $request->compound_id;
                $compoundHousehold->community_id = $request->community_id;
                $compoundHousehold->energy_system_type_id = $request->energy_system_type_id;
                $compoundHousehold->save();

                $household = Household::findOrFail($request->household_id[$i]);
                if($request->energy_system_type_id) {
                    
                    $household->energy_system_type_id = $request->energy_system_type_id;
                    $household->save();
                }
            } 
       
            return redirect()->back()->with('message', 'New Compound Households Added Successfully!');
        } else {

            return redirect()->back()->with('error', 'You missed up selecting households!');
        }
            
    }

    /**
     * Get sub communities by community_id.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getByCommunity(Request $request) 
    {
        if (!$request->community_id) {

            $htmlHouseholds = '<option value="">Choose One...</option>';
            $htmlCompounds = '<option value="">Choose One...</option>';
            $htmlEnergySystems = '<option value="">Choose One...</option>';
        } else { 

            $htmlCompounds = '<option value="">Choose One...</option>';
            $htmlHouseholds = '<option value="">Choose One...</option>';
            $htmlEnergySystems = '<option value="">Choose One...</option>';
            
            $households = Household::where('community_id', $request->community_id)
                ->where('is_archived', 0)
                ->orderBy('english_name', 'ASC')
                ->get();

            $compounds = Compound::where('community_id', $request->community_id)
                ->where('is_archived', 0)
                ->orderBy('english_name', 'ASC')
                ->get();

            foreach ($compounds as $compound) {
                $htmlCompounds .= '<option value="'.$compound->id.'">'.$compound->english_name.'</option>';
            }

            foreach ($households as $household) {
                $htmlHouseholds .= '<option value="'.$household->id.'">'.$household->english_name.'</option>';
            }

            $energySystems = EnergySystem::where('community_id', $request->community_id)
                ->get();

            foreach($energySystems as $energySystem) {

                $htmlEnergySystems .= '<option value="'.$energySystem->id.'">'.$energySystem->name.'</option>';
            }
        }

        return response()->json([
            'htmlHouseholds' => $htmlHouseholds,
            'htmlCompounds' => $htmlCompounds,
            'htmlEnergySystems' => $htmlEnergySystems
        ]);
    }

    
    /**
     * Get sub communities by community_id.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getHouseholdByCompound(Request $request) 
    {
        if (!$request->compound_id) {

            $htmlHouseholds = '<option value="">Choose One...</option>';
        } else { 

            $htmlHouseholds = '<option value="">Choose One...</option>';
            
            $households =  DB::table('compound_households')
                ->join('compounds', 'compound_households.compound_id', 'compounds.id')
                ->join('households', 'compound_households.household_id', 'households.id')
                ->where('compound_households.is_archived', 0)
                ->where('compound_households.compound_id', $request->compound_id)
                ->select('households.id', 'households.english_name')
                ->get();

            foreach ($households as $household) {
                $htmlHouseholds .= '<option value="'.$household->id.'">'.$household->english_name.'</option>';
            }
        }

        return response()->json([
            'htmlHouseholds' => $htmlHouseholds
        ]);
    }

    /**
     * View Edit page.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function editPage($id)
    {
       
    } 

    /**
     * View Edit page.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) 
    {
        $compound = Compound::findOrFail($id);
        $products = ProductType::where('is_archived', 0)->get();

        $compoundWaterSources = CommunityWaterSource::where('compound_id', $id)
            ->where('is_archived', 0)
            ->get();

        $compoundNearbyTowns = NearbyTown::where('compound_id', $id)
            ->where('is_archived', 0)
            ->get();

        $compoundNearbySettlements = NearbySettlement::where('compound_id', $id)
            ->where('is_archived', 0)
            ->get();

        $compoundProductTypes = CommunityProduct::where('compound_id', $id)
            ->get();

        $energySystemTypes = EnergySystemType::where('is_archived', 0)->get();
        $waterSources = WaterSource::where('is_archived', 0)->get();

        $towns = Town::where('is_archived', 0)
            ->orderBy('english_name', 'ASC')
            ->get();

        $settlements = Settlement::where('is_archived', 0)
            ->orderBy('english_name', 'ASC')
            ->get();

        $communities = Community::where('is_archived', 0)
            ->orderBy('english_name', 'ASC')
            ->get(); 

        $energyCycles = EnergySystemCycle::get();
        $communityStatuses = CommunityStatus::where('is_archived', 0)->get();

        return view('admin.community.compound.edit', compact('compound', 'products', 
            'energySystemTypes', 'waterSources', 'communities', 'communityStatuses',
            'compoundWaterSources', 'compoundNearbySettlements', 'towns', 'settlements',
            'compoundNearbyTowns', 'compoundProductTypes', 'energyCycles'));
    }

    /**
     * Update an existing resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request, int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $compound = Compound::findOrFail($id);

        if($request->english_name) $compound->english_name = $request->english_name;
        if($request->arabic_name) $compound->arabic_name = $request->arabic_name;
        if($request->community_id) $compound->community_id = $request->community_id;
        if($request->community_status_id) $compound->community_status_id = $request->community_status_id;
        if($request->reception) $compound->reception = $request->reception;
        if($request->number_of_household) $compound->number_of_household = $request->number_of_household;
        if($request->number_of_people) $compound->number_of_people = $request->number_of_people;
        if($request->is_fallah) $compound->is_fallah = $request->is_fallah;
        if($request->is_bedouin) $compound->is_bedouin = $request->is_bedouin;
        if($request->demolition) $compound->demolition = $request->demolition;
        if($request->demolition_number) $compound->demolition_number = $request->demolition_number;
        if($request->demolition_executed) $compound->demolition_executed = $request->demolition_executed;
        if($request->last_demolition) $compound->last_demolition = $request->last_demolition;
        if($request->demolition_legal_status) $compound->demolition_legal_status = $request->demolition_legal_status;
        if($request->land_status) $compound->land_status = $request->land_status;
        if($request->is_surveyed) $compound->is_surveyed = $request->is_surveyed;
        if($request->last_surveyed_date) $compound->last_surveyed_date = $request->last_surveyed_date;
        if($request->latitude) $compound->latitude = $request->latitude;  
        if($request->longitude) $compound->longitude = $request->longitude;
        if($request->lawyer) $compound->lawyer = $request->lawyer;
        if($request->notes) $compound->notes = $request->notes;

        if($request->energy_system_cycle_id) {

            $compound->energy_system_cycle_id = $request->energy_system_cycle_id;
            $householdCompounds = CompoundHousehold::where('compound_id', $id)->get();

            if($householdCompounds) {

                foreach($householdCompounds as $householdCompound) {

                    $household = Household::findOrFail($householdCompound->household_id);
                    $household->energy_system_cycle_id = $request->energy_system_cycle_id;
                    $household->save();

                    $allEnergyMeter = AllEnergyMeter::where("household_id", $householdCompound->household_id)->first();
                    if($allEnergyMeter) {

                        $allEnergyMeter->energy_system_cycle_id = $request->energy_system_cycle_id;
                        $allEnergyMeter->save();
                    }
                } 
            }
        }

        $compound->save();

        $lastCompound = Compound::findOrFail($id);
        
        if($request->is_kindergarten == "yes") {

            $publicStructureKindergarten = PublicStructure::where('is_archived', 0)
                ->where('compound_id', $id)
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
            if($request->kindergarten_students) $lastCompound->kindergarten_students = $request->kindergarten_students; 
            if($request->kindergarten_male) $lastCompound->kindergarten_male = $request->kindergarten_male; 
            if($request->kindergarten_female) $lastCompound->kindergarten_female = $request->kindergarten_female; 
            $lastCompound->save();

        } else if($request->is_kindergarten == "no") {

            if($request->kindergarten_town_id) $lastCompound->kindergarten_town_id = $request->kindergarten_town_id; 
            if($request->kindergarten_students) $lastCompound->kindergarten_students = $request->kindergarten_students; 
            if($request->kindergarten_male) $lastCompound->kindergarten_male = $request->kindergarten_male; 
            if($request->kindergarten_female) $lastCompound->kindergarten_female = $request->kindergarten_female;
            $lastCompound->save(); 
        }

        if($request->is_school == "yes") {

            $publicStructureSchool = PublicStructure::where('is_archived', 0)
                ->where('compound_id', $id)
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

            if($request->school_students) $lastCompound->school_students = $request->school_students; 
            if($request->school_male) $lastCompound->school_male = $request->school_male; 
            if($request->school_female) $lastCompound->school_female = $request->school_female; 
            if($request->grade_from) $lastCompound->grade_from = $request->grade_from; 
            if($request->grade_to) $lastCompound->grade_to = $request->grade_to;  
            $lastCompound->save();

        } else if($request->is_school == "no") {
            
            if($request->school_town_id) $lastCompound->school_town_id = $request->school_town_id; 
            if($request->school_students) $lastCompound->school_students = $request->school_students; 
            if($request->school_male) $lastCompound->school_male = $request->school_male; 
            if($request->school_female) $lastCompound->school_female = $request->school_female; 
            if($request->grade_from) $lastCompound->grade_from = $request->grade_from; 
            if($request->grade_to) $lastCompound->grade_to = $request->grade_to;  
            $lastCompound->save(); 
        }


        if($request->waters) {
            for($i=0; $i < count($request->waters); $i++) {

                $compoundWaterSource = new CommunityWaterSource();
                $compoundWaterSource->water_source_id = $request->waters[$i];
                $compoundWaterSource->compound_id = $lastCompound->id;
                $compoundWaterSource->save();
            }
        }

        if($request->new_waters) {
            for($i=0; $i < count($request->new_waters); $i++) {

                $compoundNewWaterSource = new CommunityWaterSource();
                $compoundNewWaterSource->water_source_id = $request->new_waters[$i];
                $compoundNewWaterSource->compound_id = $lastCompound->id;
                $compoundNewWaterSource->save();
            }
        }

        if($request->nearby_towns) {
            for($i=0; $i < count($request->nearby_towns); $i++) {

                $compoundNearbyTown = new NearbyTown();
                $compoundNearbyTown->town_id = $request->nearby_towns[$i];
                $compoundNearbyTown->compound_id = $lastCompound->id;
                $compoundNearbyTown->save();
            }
        }

        if($request->new_nearby_towns) {
            for($i=0; $i < count($request->new_nearby_towns); $i++) {

                $compoundNearbyTown = new NearbyTown();
                $compoundNearbyTown->town_id = $request->new_nearby_towns[$i];
                $compoundNearbyTown->compound_id = $lastCompound->id;
                $compoundNearbyTown->save();
            }
        }

        if($request->nearby_settlement) {
            for($i=0; $i < count($request->nearby_settlement); $i++) {

                $compoundNearbySettlement = new NearbySettlement();
                $compoundNearbySettlement->settlement_id = $request->nearby_settlement[$i];
                $compoundNearbySettlement->compound_id = $lastCompound->id;
                $compoundNearbySettlement->save();
            }
        }

        if($request->new_nearby_settlement) {
            for($i=0; $i < count($request->new_nearby_settlement); $i++) {

                $compoundNearbySettlement = new NearbySettlement();
                $compoundNearbySettlement->settlement_id = $request->new_nearby_settlement[$i];
                $compoundNearbySettlement->compound_id = $lastCompound->id;
                $compoundNearbySettlement->save();
            }
        }

        if($request->products) {
            for($i=0; $i < count($request->products); $i++) {

                $compoundProduct = new CommunityProduct();
                $compoundProduct->product_type_id = $request->products[$i];
                $compoundProduct->compound_id = $lastCompound->id;
                $compoundProduct->save();
            }
        }

        if($request->new_products) {
            for($i=0; $i < count($request->new_products); $i++) {

                $compoundProduct = new CommunityProduct();
                $compoundProduct->product_type_id = $request->new_products[$i];
                $compoundProduct->compound_id = $lastCompound->id;
                $compoundProduct->save();
            }
        }

        return redirect('/community-compound')->with('message', 'Compound Updated Successfully!');
    }

    /**
     * Show the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $compound = Compound::findOrFail($id);
        $publicStructures = PublicStructure::where('compound_id', $compound->id)
            ->where('comet_meter', 0)
            ->get();
        $nearbySettlements = DB::table('nearby_settlements')
            ->where('nearby_settlements.is_archived', 0)
            ->join('compounds', 'nearby_settlements.compound_id', 'compounds.id')
            ->join('settlements', 'nearby_settlements.settlement_id', 'settlements.id')
            ->where('compound_id', $compound->id)
            ->select('settlements.english_name')
            ->get();
        $nearbyTowns = DB::table('nearby_towns')
            ->where('nearby_towns.is_archived', 0)
            ->join('compounds', 'nearby_towns.compound_id', 'compounds.id')
            ->join('towns', 'nearby_towns.town_id', 'towns.id')
            ->where('compound_id', $compound->id)
            ->select('towns.english_name')
            ->get();
        $compoundWaterSources = DB::table('community_water_sources')
            ->where('community_water_sources.is_archived', 0)
            ->join('compounds', 'community_water_sources.compound_id', 'compounds.id')
            ->join('water_sources', 'community_water_sources.water_source_id', 'water_sources.id')
            ->where('compound_id', $compound->id)
            ->select('water_sources.name')
            ->get();

        $compoundRepresentative = DB::table('community_representatives')
            ->where('community_representatives.is_archived', 0)
            ->join('compounds', 'community_representatives.compound_id', 'compounds.id')
            ->join('households', 'community_representatives.household_id', 'households.id')
            ->join('community_roles', 'community_representatives.community_role_id', 'community_roles.id')
            ->where('community_representatives.compound_id', $compound->id)
            ->select('households.english_name', 'community_roles.role')
            ->get();

        $totalMeters = DB::table('compound_households')
            ->join('compounds', 'compound_households.compound_id', 'compounds.id')
            ->join('all_energy_meters', 'compound_households.household_id', 'all_energy_meters.household_id')
            ->where('compound_households.is_archived', 0)
            ->where('compound_households.compound_id', $id)
            ->count();

        $energyDonors = DB::table('community_donors')
            ->join('donors', 'community_donors.donor_id', 'donors.id')
            ->join('service_types', 'community_donors.service_id', 'service_types.id')
            ->where('community_donors.is_archived', 0)
            ->where('community_donors.service_id', 1)
            ->where('community_donors.compound_id', $id)
            ->select('donors.donor_name')
            ->get();

        $waterDonors = DB::table('community_donors')
            ->join('donors', 'community_donors.donor_id', 'donors.id')
            ->join('service_types', 'community_donors.service_id', 'service_types.id')
            ->where('community_donors.is_archived', 0)
            ->where('community_donors.service_id', 2)
            ->where('community_donors.compound_id', $id)
            ->select('donors.donor_name')
            ->get();

        $internetDonors = DB::table('community_donors')
            ->join('donors', 'community_donors.donor_id', 'donors.id')
            ->join('service_types', 'community_donors.service_id', 'service_types.id')
            ->where('community_donors.is_archived', 0)
            ->where('community_donors.service_id', 3)
            ->where('community_donors.compound_id', $id)
            ->select('donors.donor_name')
            ->get();

        $totalWaterHolders = DB::table('h2o_users')
            ->join('communities', 'h2o_users.community_id', 'communities.id')
            ->join('compounds', 'compounds.community_id', 'communities.id')
            ->where('h2o_users.is_archived', 0)
            ->where('h2o_users.community_id', $compound->community_id)
            ->count();

        $gridLarge =  DB::table('grid_users')
            ->join('communities', 'grid_users.community_id', 'communities.id')
            ->join('compounds', 'compounds.community_id', 'communities.id')
            ->where('grid_users.is_archived', 0)
            ->where('grid_users.community_id', $compound->community_id)
            ->where('grid_users.grid_integration_large', '!=', 0)
            ->selectRaw('SUM(grid_users.grid_integration_large) AS sum')
            ->first();
        
        $gridSmall = DB::table('grid_users')
            ->join('communities', 'grid_users.community_id', 'communities.id')
            ->join('compounds', 'compounds.community_id', 'communities.id')
            ->where('grid_users.is_archived', 0)
            ->where('grid_users.community_id', $compound->community_id)
            ->where('grid_users.grid_integration_small', '!=', 0)
            ->selectRaw('SUM(grid_integration_small) AS sum')
            ->first();

        $internetHolders =  DB::table('internet_users')
            ->join('communities', 'internet_users.community_id', 'communities.id')
            ->join('compounds', 'compounds.community_id', 'communities.id')
            ->where('internet_users.is_archived', 0)
            ->where('internet_users.community_id', $compound->community_id)
            ->count();

        $photos = Photo::where("compound_id", $id)->get();
        $compoundProductTypes = DB::table('community_products')
            ->join('compounds', 'community_products.compound_id', 'compounds.id')
            ->join('product_types', 'community_products.product_type_id', 'product_types.id')
            ->where('compound_id', $compound->id)
            ->select('product_types.name')
            ->get();

        return view('admin.community.compound.show', compact('compound', 'energyDonors', 'waterDonors',
            'internetDonors', 'nearbySettlements', 'totalMeters', 'compoundWaterSources',
            'totalWaterHolders', 'gridLarge', 'gridSmall', 'internetHolders', 
            'compoundRepresentative', 'publicStructures', 'nearbyTowns', 'photos',
            'compoundProductTypes'));
    }
}