<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\User;
use App\Models\Community;
use Carbon\Carbon;
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
use App\Models\Town;
use App\Models\BsfStatus;
use App\Models\H2oSharedUser;
use App\Models\H2oStatus;
use Auth;
use Route;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {    
        // $data = DB::table('communities')
        //     ->join('regions', 'communities.region_id', '=', 'regions.id')
        //     ->join('sub_regions', 'communities.sub_region_id', '=', 'sub_regions.id')
        //     ->select('communities.english_name as english_name', 'communities.arabic_name as arabic_name',
        //         'communities.id as id', 'communities.created_at as created_at', 
        //         'communities.updated_at as updated_at',
        //         'communities.number_of_people as number_of_people',
        //         'regions.english_name as name',
        //         'regions.arabic_name as aname',
        //         'sub_regions.english_name as subname')
        //     ->latest(); 

        // $energyUsers = EnergyUser::all();
        // foreach($energyUsers as $energyUser) {
        //     $household = Household::where("id", $energyUser->household_id)->first();
        //     $household->energy_meter = "Yes";
        //     $household->energy_service = "Yes";
        //     $household->save();
        // } 
        // $householdMeters = HouseholdMeter::all();
        // foreach($householdMeters as $householdMeter) {
        //     $household = Household::where("id", $householdMeter->household_id)->first();
        //     $household->energy_service = "Yes";
        //     $household->save();
        // }

        // $h2oUsers = H2oUser::all();
        // foreach($h2oUsers as $h2oUser) {
        //     $household = Household::where("id", $h2oUser->household_id)->first();
        //     $household->water_service = "Yes";
        //     $household->save();
        // } 
        // $gridUsers = GridUser::all();
        // foreach($gridUsers as $gridUser) {
        //     $household = Household::where("id", $gridUser->household_id)->first();
        //     $household->water_service = "Yes";
        //     $household->save();
        // } 

        $allUsers = User::where('type', 1)
            ->where('is_admin', 0)
            ->get()->count();
        
        $communityNumbers = Community::where("is_archived", 0)
            ->where("community_status_id", 3)
            ->count();
        $householdNumbers = Household::count();
        $regionNumbers = Region::count();

        $h2oUsersNumbers = H2oUser::count();
        $h2oSharedNumbers = H2oSharedUser::count();
        $gridUsersNumber = GridUser::count();
   
        $gridLarge = GridUser::selectRaw('SUM(grid_integration_large) AS sum')
            ->first();
        $gridSmall = GridUser::selectRaw('SUM(grid_integration_small) AS sum')
            ->first();
        $h2oNumber = H2oUser::selectRaw('SUM(number_of_h20) AS sum')
            ->first();

        $numberOfPeople = Household::where("household_status_id", 4)
            ->selectRaw('SUM(number_of_people) AS number_of_people')
            ->first();
        $numberOfMale = Household::selectRaw('SUM(number_of_male) AS number_of_male')
            ->first();
        $numberOfFemale = Household::selectRaw('SUM(number_of_female) AS number_of_female')
            ->first();
        $numberOfAdults = Household::selectRaw('SUM(number_of_adults) AS number_of_adults')
            ->first();
        $numberOfChildren = Household::selectRaw('SUM(number_of_children) AS number_of_children')
            ->first();
        $systemHoldersNumber = Household::where("energy_service", "Yes")
            ->orWhere("water_service", "Yes")
            ->count();

        $initialYearEnergy = DB::table('communities')
            ->whereNotNull("communities.energy_service_beginning_year")
            ->select(
                    DB::raw('communities.energy_service_beginning_year as energy_service_beginning_year'),
                    DB::raw('count(*) as number'))
            ->groupBy('communities.energy_service_beginning_year')
            ->get();
        $arrayYearEnergy[] = ['English Name', 'Number'];
        
        foreach($initialYearEnergy as $key => $value) {

            $arrayYearEnergy[++$key] = 
            [$value->energy_service_beginning_year, $value->number];
        }

        $initialYearWater = DB::table('communities')
            ->whereNotNull("communities.water_service_beginning_year")
            ->select(
                    DB::raw('communities.water_service_beginning_year as water_service_beginning_year'),
                    DB::raw('count(*) as number'))
            ->groupBy('communities.water_service_beginning_year')
            ->get();
        $arrayYearWater[] = ['English Name', 'Number'];
        
        foreach($initialYearWater as $key => $value) {

            $arrayYearWater[++$key] = 
            [$value->water_service_beginning_year, $value->number];
        }

        $initialYearInternet = DB::table('communities')
            ->whereNotNull("communities.internet_service_beginning_year")
            ->select(
                    DB::raw('communities.internet_service_beginning_year as internet_service_beginning_year'),
                    DB::raw('count(*) as number'))
            ->groupBy('communities.internet_service_beginning_year')
            ->get();
        $arrayYearInternet[] = ['English Name', 'Number'];
        
        foreach($initialYearInternet as $key => $value) {

            $arrayYearInternet[++$key] = 
            [$value->internet_service_beginning_year, $value->number];
        }

        $communitiesMasafers = Community::where("sub_sub_region_id", 1)
            ->where("community_status_id", 3)
            ->get();
        $countHouseholds = 0;
        $countEnergyUsers = 0;
        $countMgSystem = 0;
        $countFbsSystem = 0;
        $countH2oUsers = 0;
        $countGridUsers = 0;

        foreach($communitiesMasafers as $communitiesMasafer) {
            $householdsCount = H2oUser::where('community_id', $communitiesMasafer->id)
                ->count();

            $countH2oUsers+=$householdsCount;
        }

        foreach($communitiesMasafers as $communitiesMasafer) {
            $householdsCount = GridUser::where('community_id', $communitiesMasafer->id)
                ->count();

            $countGridUsers+=$householdsCount;
        }


        foreach($communitiesMasafers as $communitiesMasafer) {
            $householdsCount = Household::where('community_id', $communitiesMasafer->id)
                ->count();

            $countHouseholds+=$householdsCount;
        }

        foreach($communitiesMasafers as $communitiesMasafer) {
            $energyUsers = EnergyUser::where('community_id', $communitiesMasafer->id)
                ->count();

            $countEnergyUsers+=$energyUsers;
        }

        $countMgSystem =  DB::table('energy_users')
            ->join('communities', 'energy_users.community_id', '=', 'communities.id')
            ->join('energy_systems', 'energy_users.energy_system_id', '=', 'energy_systems.id')
            ->join('energy_system_types', 'energy_users.energy_system_type_id', '=', 'energy_system_types.id')
            ->where('communities.sub_sub_region_id', 1)
            ->where('energy_users.energy_system_type_id', 1)
            ->select(
                DB::raw('energy_systems.name as name'),
                DB::raw('count(*) as number'))
            ->groupBy('energy_systems.name')
            ->get();

        $countFbsSystem =  DB::table('energy_users')
            ->join('communities', 'energy_users.community_id', '=', 'communities.id')
            ->join('energy_systems', 'energy_users.energy_system_id', '=', 'energy_systems.id')
            ->join('energy_system_types', 'energy_users.energy_system_type_id', '=', 'energy_system_types.id')
            ->where('communities.sub_sub_region_id', 1)
            ->where('energy_users.energy_system_type_id', 2)
            ->get();

        return view('employee.dashboard', compact('householdNumbers', 'numberOfPeople',
            'communityNumbers', 'h2oUsersNumbers', 'h2oSharedNumbers', 'gridUsersNumber', 
            'gridLarge', 'regionNumbers', 'gridSmall', 'h2oNumber', 'systemHoldersNumber',
            'numberOfMale', 'numberOfFemale', 'numberOfAdults', 'numberOfChildren',
            'countEnergyUsers', 'countHouseholds', 'countMgSystem', 'countFbsSystem', 
            'countH2oUsers', 'countGridUsers'))
            ->with(
                'initialYearEnergyData', json_encode($arrayYearEnergy))
            ->with(
                'initialYearWaterData', json_encode($arrayYearWater))
            ->with(
                'initialYearInternetData', json_encode($arrayYearInternet));
            
    }
}