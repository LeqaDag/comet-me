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

class OtherCommunityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {	
      
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function newCommunity(Request $request)
    {      
        $community = new Community();
        $community->english_name = $request->other_english_name;
        $community->arabic_name = $request->other_arabic_name;
        $community->region_id = $request->region_id;
        $community->sub_region_id = $request->sub_region_id;
        $community->energy_system_cycle_id = $request->energy_system_cycle_id;
        $community->number_of_household = $request->other_number_of_household;
        $community->is_fallah = $request->is_fallah;
        $community->is_bedouin = $request->is_bedouin;
        $community->energy_source = $request->energy_source;  
        $community->latitude = $request->latitude; 
        $community->longitude = $request->longitude;
        $community->notes = $request->notes;
        if($request->reception) $community->reception = $request->reception;
        $community->save();

        $id = $community->id;

        if($request->recommended_energy_system_id) {
            for($i=0; $i < count($request->recommended_energy_system_id); $i++) {

                $recommendedEnergy = new RecommendedCommunityEnergySystem();
                $recommendedEnergy->energy_system_type_id = $request->recommended_energy_system_id[$i];
                $recommendedEnergy->community_id = $id;
                $recommendedEnergy->save();
            }
        }

        $lastCommunity = Community::findOrFail($id);
        $html = '<option value="'.$lastCommunity->id.'">'.$lastCommunity->english_name.'</option>';

        return response()->json(['html' => $html]);
    }
}