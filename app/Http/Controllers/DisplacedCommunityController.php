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
use App\Models\H2oUser;
use App\Models\GridUser;
use App\Models\InternetUser;
use App\Models\User;
use App\Models\Community;
use App\Models\CommunityDonor;
use App\Models\CommunityStatus;
use App\Models\Compound;
use App\Models\DisplacedHousehold;
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
use App\Models\SecondNameCommunity;
use App\Models\ProductType;
use App\Models\CommunityWaterSource;
use Carbon\Carbon;
use Image;
use DataTables;

class DisplacedCommunityController extends Controller
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

                $data = DB::table('communities')
                    ->join('regions', 'communities.region_id', 'regions.id')
                    ->join('community_statuses', 'communities.community_status_id', 'community_statuses.id')
                    ->leftJoin('displaced_households', 'displaced_households.old_community_id', 
                        'communities.id')
                    ->leftJoin('communities as new_communities', 'displaced_households.new_community_id', 
                        'new_communities.id')
                    ->leftJoin('sub_regions as new_regions', 'displaced_households.sub_region_id', 
                        'new_regions.id')
                    ->where('communities.community_status_id', 5)
                    ->select(
                        'communities.english_name as english_name', 'communities.arabic_name as arabic_name',
                        'communities.id as id', 'communities.created_at as created_at', 
                        'communities.updated_at as updated_at',
                        DB::raw('COUNT(displaced_households.household_id) as number_of_household'),
                        'communities.number_of_people as number_of_people',
                        'regions.english_name as name',
                        'regions.arabic_name as aname',
                        'new_communities.english_name as new_community',
                        'new_regions.english_name as new_region')
                    ->groupBy('communities.id')
                    ->latest(); 
    
                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
                        $detailsButton = "<a type='button' class='displacedCommunityButton' data-id='".$row->id."'><i class='fa-solid fa-eye text-primary'></i></a>";
    
                        return $detailsButton;
                    })
                    ->filter(function ($instance) use ($request) {
                        if (!empty($request->get('search'))) {
                                $instance->where(function($w) use($request) {
                                $search = $request->get('search');
                                $w->orWhere('communities.english_name', 'LIKE', "%$search%")
                                ->orWhere('communities.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('regions.english_name', 'LIKE', "%$search%")
                                ->orWhere('regions.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('new_regions.english_name', 'LIKE', "%$search%")
                                ->orWhere('new_regions.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('new_communities.english_name', 'LIKE', "%$search%")
                                ->orWhere('community_statuses.name', 'LIKE', "%$search%");
                            });
                        }
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
    
            $communityRecords = Community::where("community_status_id", "5")->count();
            $regions = Region::where('is_archived', 0)->get();
            $subregions = SubRegion::where('is_archived', 0)->get();
            $products = ProductType::where('is_archived', 0)->get();
            $energyTypes = EnergySystemType::where('is_archived', 0)->get();
    
            return view('employee.community.displaced.index', compact('regions', 
                'communityRecords', 'subregions', 'products', 'energyTypes'));
        } else {

            return view('errors.not-found');
        }
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

        $displacedHousehold = DisplacedHousehold::where("old_community_id", $id)->count();
        $allDisplacedHouseholds = DB::table('displaced_households')
            ->join('households', 'households.id', 'displaced_households.household_id')
            ->where("displaced_households.old_community_id", $id)
            ->select(
            DB::raw("sum(households.number_of_male + households.number_of_female) AS total_people"))
            ->get();
        $displacedPeople = 0;

        foreach($allDisplacedHouseholds as $allDisplacedHousehold) {

            $displacedPeople += $allDisplacedHousehold->total_people;
        }

        $newCommunity = DB::table('displaced_households')
            ->leftJoin('communities as new_communities', 'displaced_households.new_community_id', 
                'new_communities.id')
            ->leftJoin('sub_regions as new_regions', 'displaced_households.sub_region_id', 
                'new_regions.id')
            ->where('displaced_households.old_community_id', $id)
            ->select(
                'new_communities.id',
                'new_communities.english_name as new_community',
                'new_communities.arabic_name as new_arabic_community',
                )
            ->groupBy('displaced_households.old_community_id')
            ->get();

        $newSubRegion = DisplacedHousehold::where('old_community_id', $id)
            ->groupBy('old_community_id')
            ->first();
       
        $newCommunityId = $newCommunity[0]->id;

        $publicStructures = [];
        $nearbySettlements = [];
        $nearbyTowns = [];
        $compounds = [];
        $energyDonors = [];
        $communityWaterSources  = [];
        $communityRecommendedEnergy = [];
        $communityRepresentative = [];
        $totalMeters = 0;
        $waterDonors = [];
        $internetDonors = [];
        $totalWaterHolders  = 0;
        $gridLarge = 0;
        $gridSmall = 0;
        $internetHolders = 0;
        $photos = [];
        $secondName = [];
        
        if($newCommunityId != null) {

            $publicStructures = PublicStructure::where('community_id', $newCommunityId)
                ->where('comet_meter', 0)
                ->get();

            $nearbySettlements = DB::table('nearby_settlements')
                ->where('nearby_settlements.is_archived', 0)
                ->join('communities', 'nearby_settlements.community_id', '=', 'communities.id')
                ->join('settlements', 'nearby_settlements.settlement_id', '=', 'settlements.id')
                ->where('community_id', $newCommunityId)
                ->select('settlements.english_name')
                ->get();
            $nearbyTowns = DB::table('nearby_towns')
                ->where('nearby_towns.is_archived', 0)
                ->join('communities', 'nearby_towns.community_id', '=', 'communities.id')
                ->join('towns', 'nearby_towns.town_id', '=', 'towns.id')
                ->where('community_id', $newCommunityId)
                ->select('towns.english_name')
                ->get();
            $compounds = Compound::where('community_id', $newCommunityId)
                ->where('is_archived', 0)
                ->get();
            $communityWaterSources = DB::table('community_water_sources')
                ->where('community_water_sources.is_archived', 0)
                ->join('communities', 'community_water_sources.community_id', '=', 'communities.id')
                ->join('water_sources', 'community_water_sources.water_source_id', '=', 'water_sources.id')
                ->where('community_id', $newCommunityId)
                ->select('water_sources.name')
                ->get();
            $communityRecommendedEnergy = DB::table('recommended_community_energy_systems')
                ->where('recommended_community_energy_systems.is_archived', 0)
                ->join('communities', 'recommended_community_energy_systems.community_id', 
                    '=', 'communities.id')
                ->join('energy_system_types', 'recommended_community_energy_systems.energy_system_type_id', 
                    '=', 'energy_system_types.id')
                ->where('community_id', $newCommunityId)
                ->select('energy_system_types.name')
                ->get();  
                
            $communityRepresentative = DB::table('community_representatives')
                ->where('community_representatives.is_archived', 0)
                ->join('communities', 'community_representatives.community_id', '=', 'communities.id')
                ->join('households', 'community_representatives.household_id', '=', 'households.id')
                ->join('community_roles', 'community_representatives.community_role_id', '=', 'community_roles.id')
                ->where('community_representatives.community_id', $newCommunityId)
                ->select('households.english_name', 'community_roles.role')
                ->get();
    
            $secondName = SecondNameCommunity::where('community_id', $newCommunityId)->first();
    
            $totalMeters = AllEnergyMeter::where("community_id", $newCommunityId)->count();
            $energyDonors = DB::table('community_donors')
                ->join('donors', 'community_donors.donor_id', 'donors.id')
                ->join('service_types', 'community_donors.service_id', 'service_types.id')
                ->where('community_donors.is_archived', 0)
                ->where('community_donors.service_id', 1)
                ->where('community_donors.community_id', $newCommunityId)
                ->select('donors.donor_name')
                ->get();
    
            $waterDonors = DB::table('community_donors')
                ->join('donors', 'community_donors.donor_id', 'donors.id')
                ->join('service_types', 'community_donors.service_id', 'service_types.id')
                ->where('community_donors.is_archived', 0)
                ->where('community_donors.service_id', 2)
                ->where('community_donors.community_id', $newCommunityId)
                ->select('donors.donor_name')
                ->get();
    
            $internetDonors = DB::table('community_donors')
                ->join('donors', 'community_donors.donor_id', 'donors.id')
                ->join('service_types', 'community_donors.service_id', 'service_types.id')
                ->where('community_donors.is_archived', 0)
                ->where('community_donors.service_id', 3)
                ->where('community_donors.community_id', $newCommunityId)
                ->select('donors.donor_name')
                ->get();
    
     
            $totalWaterHolders = H2oUser::where("is_archived", 0)
                ->where("community_id", $newCommunityId)
                ->count();
    
            $gridLarge = GridUser::where('is_archived', 0)
                ->where('grid_integration_large', '!=', 0)
                ->where("community_id", $newCommunityId)
                ->selectRaw('SUM(grid_integration_large) AS sum')
                ->first();
            $gridSmall = GridUser::where('is_archived', 0)
                ->where('grid_integration_small', '!=', 0)
                ->where("community_id", $newCommunityId)
                ->selectRaw('SUM(grid_integration_small) AS sum')
                ->first();
    
            $internetHolders = InternetUser::where("is_archived", 0)
                ->where("community_id", $newCommunityId)
                ->count();
    
            $photos = Photo::where("community_id", $newCommunityId)->get();
        }
        
        return view('employee.community.displaced.show', compact('community', 'newCommunity',
            'newSubRegion', 'displacedHousehold', 'displacedPeople',

            'energyDonors', 'waterDonors',
            'internetDonors', 'nearbySettlements', 'totalMeters', 'communityWaterSources',
            'totalWaterHolders', 'gridLarge', 'gridSmall', 'internetHolders', 'secondName',
            'communityRepresentative', 'publicStructures', 'compounds', 'nearbyTowns', 'photos'));
    }
}
