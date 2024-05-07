<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\AllEnergyMeter;
use App\Models\User;
use App\Models\Community;
use Carbon\Carbon;
use App\Models\CommunityDonor;
use App\Models\CommunityStatus;
use App\Models\CommunityService;
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
use App\Models\Setting;
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
use App\Models\Incident;
use App\Models\MgIncident;
use App\Models\IncidentStatusMgSystem;
use App\Models\InternetUser;
use App\Models\MeterList;
use App\Models\HomePhoto;
use App\Models\WaterNetworkUser;
use Auth;
use Route;
use DB;
use Excel;
use Artisan;
use PDF;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     * 
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        Artisan::call('schedule:run');

        // $communities = DB::table("internet_users")
        //     ->join("communities", "internet_users.community_id", "communities.id")
        //     ->groupBy("communities.id")
        //     ->get();
        
        // foreach($communities as $community) {

        //     $exist = CommunityService::where("community_id", $community->id)
        //         ->where("service_id", 3)
        //         ->first();
        //     if($exist) {

        //     } else {

        //         $communityService = new CommunityService();
        //         $communityService->community_id = $community->id;
        //         $communityService->service_id = 3;
        //         $communityService->save();
        //     }
        // }


        if (Auth::guard('user')->user() != null) {
            
            $mgIncidentsYear = DB::table('mg_incidents')
                ->where('mg_incidents.is_archived', 0)
                ->join('incidents', 'mg_incidents.incident_id', '=', 'incidents.id')
                ->select('incidents.english_name as name', 'mg_incidents.year')
                ->selectRaw('count(*) as number')
                ->groupBy('incidents.english_name', 'mg_incidents.year')
                ->get();

            $fbsIncidentsYear = DB::table('fbs_user_incidents')
                ->where('fbs_user_incidents.is_archived', 0)
                ->join('incidents', 'fbs_user_incidents.incident_id', '=', 'incidents.id')
                ->select('incidents.english_name as name', 'fbs_user_incidents.year')
                ->selectRaw('count(*) as number')
                ->groupBy('incidents.english_name', 'fbs_user_incidents.year')
                ->get();

            $h2oIncidentsYear = DB::table('h2o_system_incidents')
                ->where('h2o_system_incidents.is_archived', 0)
                ->join('incidents', 'h2o_system_incidents.incident_id', '=', 'incidents.id')
                ->select('incidents.english_name as name', 'h2o_system_incidents.year')
                ->selectRaw('count(*) as number')
                ->groupBy('incidents.english_name', 'h2o_system_incidents.year')
                ->get();
 
            $allIncidents = DB::table('mg_incidents')
                ->where('mg_incidents.is_archived', 0)
                ->join('incidents', 'mg_incidents.incident_id', '=', 'incidents.id')
                ->leftJoin('fbs_user_incidents', 'mg_incidents.incident_id', '=', 
                    'fbs_user_incidents.incident_id')
                ->leftJoin('h2o_system_incidents', 'mg_incidents.incident_id', '=', 
                    'h2o_system_incidents.incident_id')
                ->select('incidents.english_name as name', 'mg_incidents.year as mg_year',
                    'fbs_user_incidents.year as fbs_year', 'h2o_system_incidents.year as h2o_year')
                ->get();
                

            $energyMeter = AllEnergyMeter::all();
            $energyUserCount = AllEnergyMeter::count();

            $communityNumbers = Community::where("is_archived", 0)
                ->where("community_status_id", 3)
                ->count();
            $householdNumbers = Household::where('internet_holder_young', 0)
                ->where('is_archived', 0)
                ->where('household_status_id', 4)
                ->count(); 
            $regionNumbers = Region::where('is_archived', 0)->count();
 
            $h2oUsersNumbers = H2oUser::where('is_archived', 0)->count();
            $h2oSharedNumbers = H2oSharedUser::where('is_archived', 0)->count();
            $gridUsersNumber = GridUser::where('is_archived', 0)->count();
    
            $totalH2oUsers = $h2oUsersNumbers + $h2oSharedNumbers;

            $gridLarge = GridUser::where('is_archived', 0)
                ->where('grid_integration_large', '!=', 0)
                ->selectRaw('SUM(grid_integration_large) AS sum')
                ->first();
            $gridSmall = GridUser::where('is_archived', 0)
                ->where('grid_integration_small', '!=', 0)
                ->selectRaw('SUM(grid_integration_small) AS sum')
                ->first();
            $h2oNumber = H2oUser::where('is_archived', 0)
                ->selectRaw('SUM(number_of_h20) AS sum')
                ->first();

            $numberOfPeople = Household::where('energy_system_status', 'Served')
                ->where('internet_holder_young', 0)
                ->where('is_archived', 0)
                ->selectRaw('SUM(number_of_people) AS number_of_people')
                ->first();
            $numberOfMale = Household::selectRaw('SUM(number_of_male) AS number_of_male')
                ->where('energy_system_status', 'Served')
                ->where('is_archived', 0)
                ->where('internet_holder_young', 0)
                ->first();
            $numberOfFemale = Household::selectRaw('SUM(number_of_female) AS number_of_female')
                ->where('energy_system_status', 'Served')
                ->where('is_archived', 0)
                ->where('internet_holder_young', 0)
                ->first();
            $numberOfAdults = Household::selectRaw('SUM(number_of_adults) AS number_of_adults')
                ->where('energy_system_status', 'Served')
                ->where('is_archived', 0)
                ->where('internet_holder_young', 0)
                ->first();
            $numberOfChildren = Household::selectRaw('SUM(number_of_children) AS number_of_children')
                ->where('energy_system_status', 'Served')
                ->where('is_archived', 0)
                ->where('internet_holder_young', 0)
                ->first();
            $systemHoldersNumber = Household::where("energy_service", "Yes")
                ->where('energy_system_status', 'Served')
                ->where('is_archived', 0)
                ->orWhere("water_service", "Yes")
                ->count();

            $mgIncidentsNumber = MgIncident::where('is_archived', 0)->count();

            $initialYearEnergy = DB::table('communities')
                ->where('communities.is_archived', 0)
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
                ->where('communities.is_archived', 0)
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
                ->where('communities.is_archived', 0)
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
                ->where('is_archived', 0)
                ->where("community_status_id", 3)
                ->get();
            $communitiesMasafersCount = Community::where("sub_sub_region_id", 1)
                ->where('is_archived', 0)
                ->where("community_status_id", 3)
                ->count();
            $countHouseholds = 0;
            $countEnergyUsers = 0;
            $countMgSystem = 0;
            $countFbsSystem = 0;
            $countH2oUsers = 0;
            $countGridUsers = 0;
            $countInternetUsers = 0;

            foreach($communitiesMasafers as $communitiesMasafer) {
                $householdsCount = H2oUser::where('community_id', $communitiesMasafer->id)
                    ->where('is_archived', 0)
                    ->count();

                $countH2oUsers+=$householdsCount;
            }

            foreach($communitiesMasafers as $communitiesMasafer) {
                $householdsCount = GridUser::where('community_id', $communitiesMasafer->id)
                    ->where('is_archived', 0)
                    ->count();

                $countGridUsers+=$householdsCount;
            }

            foreach($communitiesMasafers as $communitiesMasafer) {
                $householdsCount = Household::where('community_id', $communitiesMasafer->id)
                    ->where('is_archived', 0)
                    ->count();

                $countHouseholds+=$householdsCount;
            }

            foreach($communitiesMasafers as $communitiesMasafer) {
                $energyUsers = AllEnergyMeter::where('community_id', $communitiesMasafer->id)
                    ->where('is_archived', 0)
                    ->count();

                $countEnergyUsers+=$energyUsers;
            }

            foreach($communitiesMasafers as $community) {
                $InternetCount = InternetUser::where('community_id', $community->id)
                    ->where('is_archived', 0)
                    ->count();

                $countInternetUsers+= $InternetCount;
            }

            $countMgSystem =  DB::table('all_energy_meters')
                ->where('all_energy_meters.is_archived', 0)
                ->join('communities', 'all_energy_meters.community_id', '=', 'communities.id')
                ->join('energy_systems', 'all_energy_meters.energy_system_id', '=', 'energy_systems.id')
                ->join('energy_system_types', 'all_energy_meters.energy_system_type_id', '=', 'energy_system_types.id')
                ->where('communities.sub_sub_region_id', 1)
                ->where('all_energy_meters.energy_system_type_id', 1)
                ->select(
                    DB::raw('energy_systems.name as name'),
                    DB::raw('count(*) as number'))
                ->groupBy('energy_systems.name')
                ->get();

            $countFbsSystem =  DB::table('all_energy_meters')
                ->where('all_energy_meters.is_archived', 0)
                ->join('communities', 'all_energy_meters.community_id', '=', 'communities.id')
                ->join('energy_systems', 'all_energy_meters.energy_system_id', '=', 'energy_systems.id')
                ->join('energy_system_types', 'all_energy_meters.energy_system_type_id', '=', 'energy_system_types.id')
                ->where('communities.sub_sub_region_id', 1)
                ->where('all_energy_meters.energy_system_type_id', 2)
                ->get();

            $dataIncidents = DB::table('mg_incidents')
                ->where('mg_incidents.is_archived', 0)
                ->join('communities', 'mg_incidents.community_id', '=', 'communities.id')
                ->join('sub_regions', 'communities.sub_region_id', '=', 'sub_regions.id')
                ->join('incidents', 'mg_incidents.incident_id', '=', 'incidents.id')
                ->join('incident_status_mg_systems', 'mg_incidents.incident_status_mg_system_id', 
                    '=', 'incident_status_mg_systems.id')
                ->where('incident_status_mg_systems.incident_id', "=",  4)
                ->select(
                    DB::raw('incident_status_mg_systems.name as name'),
                    DB::raw('count(*) as number'))
                ->groupBy('incident_status_mg_systems.name')
                ->get();
            $arrayIncidents[] = ['English Name', 'Number'];
             
            foreach($dataIncidents as $key => $value) {

                $arrayIncidents[++$key] = [$value->name, $value->number];
            }

            $totalMgSystem =  DB::table('energy_systems')
                ->where('energy_systems.is_archived', 0)
                ->where('energy_systems.energy_system_type_id', 1)
                ->orWhere('energy_systems.energy_system_type_id', 4)
                ->get();

            $totalFbsSystem =  DB::table('all_energy_meters')
                ->where('all_energy_meters.is_archived', 0)
                ->join('communities', 'all_energy_meters.community_id', '=', 'communities.id')
                ->join('energy_systems', 'all_energy_meters.energy_system_id', '=', 'energy_systems.id')
                ->join('energy_system_types', 'all_energy_meters.energy_system_type_id', '=', 'energy_system_types.id')
                ->where('all_energy_meters.energy_system_type_id', 2)
                ->get();

            // Cumulative sum
            $totals = DB::table('communities')
                ->where('communities.is_archived', 0)
                ->whereNotNull("communities.energy_service_beginning_year")
                ->select(
                    DB::raw('communities.energy_service_beginning_year as energy_service_beginning_year'),
                    DB::raw('count(*) as number'))
                ->groupBy('communities.energy_service_beginning_year')
                ->get();

            $cumulativeSum[] = ['Year', 'Sum'];
            $sum = 0;

            foreach($totals as $key => $value) {

                $sum += $value->number;
                $cumulativeSum[++$key] = 
                [$value->energy_service_beginning_year, $sum];
            }

            // Cumulative sum water
            $totalWater = DB::table('communities')
                ->where('communities.is_archived', 0)
                ->whereNotNull("communities.water_service_beginning_year")
                ->select(
                    DB::raw('communities.water_service_beginning_year as water_service_beginning_year'),
                    DB::raw('count(*) as number'))
                ->groupBy('communities.water_service_beginning_year')
                ->get();

            $cumulativeSumWater[] = ['Year', 'Sum'];
            $sumWater = 0;

            foreach($totalWater as $key => $value) {

                $sumWater += $value->number;
                $cumulativeSumWater[++$key] = 
                [$value->water_service_beginning_year, $sumWater];
            }

            // Cumulative sum Internet
            $totalInternet = DB::table('communities')
                ->where('communities.is_archived', 0)
                ->whereNotNull("communities.internet_service_beginning_year")
                ->select(
                    DB::raw('communities.internet_service_beginning_year as internet_service_beginning_year'),
                    DB::raw('count(*) as number'))
                ->groupBy('communities.internet_service_beginning_year')
                ->get();

            $cumulativeSumInternet[] = ['Year', 'Sum'];
            $sumInternet = 0;
 
            foreach($totalInternet as $key => $value) {

                $sumInternet += $value->number;
                $cumulativeSumInternet[++$key] = 
                [$value->internet_service_beginning_year, $sumInternet];
            }

            $energyUsers = AllEnergyMeter::where("meter_case_id", 1)
                ->where('is_archived', 0)
                ->count();
            $sharedEnergy = HouseholdMeter::where('is_archived', 0)->count();
            $InternetUsers = InternetUser::where('is_archived', 0)->count() * 5;

            $energyUsers += $sharedEnergy;
            // total of served households energyUsers/servedHouseholds
            $servedHouseholdCount = Household::where('household_status_id', 4)
                ->where('is_archived', 0)
                ->count();

            $waterNetworkUsers = WaterNetworkUser::where('is_archived', 0)->count();
    
            $activeInternetCommuntiies = Community::where('internet_service', 'Yes')
                ->where('is_archived', 0);

            $activeInternetCommuntiiesCount = InternetUser::groupBy('community_id')->get();
            
            $InternetUsersCounts = DB::table('internet_users')
                ->where('internet_users.is_archived', 0)
                ->whereNotNull('internet_users.household_id');
                //->join('households', 'internet_users.household_id', 'households.id')
                //->join('communities', 'internet_users.community_id', 'communities.id')
                //->where('communities.internet_service', 'Yes')
               // ->whereNotNull('internet_users.household_id');


            $InternetPublicCount = DB::table('internet_users')
                ->where('internet_users.is_archived', 0)
                //->join('communities', 'internet_users.community_id', 'communities.id')
               // ->where('communities.internet_service', 'Yes')
                ->whereNull('internet_users.household_id')
                ->count();
            
            $allInternetPeople=0;
         
            foreach($activeInternetCommuntiies->get() as $activeInternetCommuntiy) 
            {
                $allInternetPeople+= Household::where('community_id', $activeInternetCommuntiy->id)
                    ->where('is_archived', 0)
                    ->count();
            }
 
            // percentage of how many internet contract holders we have out of 
            // total households in active "internet" community
            $internetPercentage = round(($InternetUsersCounts->count())/$allInternetPeople * 100, 2);

            $allContractHolders = DB::table('internet_users')
                ->where('internet_users.is_archived', 0)
                ->count();

            $allInternetUsersCounts = $InternetUsersCounts
                ->join('households', 'internet_users.household_id', 'households.id')
                ->where('households.internet_holder_young', 0)
                ->count();

            $youngInternetHolders = DB::table('internet_users')
                ->where('internet_users.is_archived', 0)
                ->join('households', 'internet_users.household_id', 'households.id')
                //->join('communities', 'internet_users.community_id', 'communities.id')
               // ->where('communities.internet_service', 'Yes')
                ->whereNotNull('internet_users.household_id')
                ->where('households.internet_holder_young', 1)
                ->count();

            $communitiesInternet = DB::table('internet_users')
                ->join('communities', 'internet_users.community_id', 'communities.id')
                ->join('regions', 'communities.region_id', 'regions.id')
                ->join('sub_regions', 'communities.sub_region_id', 'sub_regions.id')
                ->where('internet_users.is_archived', 0)
                ->groupBy('internet_users.community_id')
                ->select(
                    'communities.english_name', 'communities.number_of_people', 
                    'communities.internet_service_beginning_year',
                    'regions.english_name as region_name',
                    'sub_regions.english_name as sub_region_name'
                )
                ->get(); 

            $ratedPowerMG = EnergySystem::where("energy_system_type_id", 1)
                ->orWhere("energy_system_type_id", 3)
                ->orWhere("energy_system_type_id", 4)
                ->select(DB::raw('SUM(total_rated_power) as total_rated_power'))
                ->first();

            $ratedPowerFBS = DB::table('all_energy_meters') 
                ->join('energy_systems', 'all_energy_meters.energy_system_id', 'energy_systems.id')
                ->join('energy_system_types', 'all_energy_meters.energy_system_type_id', 
                    'energy_system_types.id')
                ->where('all_energy_meters.is_archived', 0)
                ->where('all_energy_meters.energy_system_type_id', 2)
                ->select(DB::raw('SUM(total_rated_power) as total_rated_power'))
                ->first();

            $totalRatedPower = round(
                $ratedPowerMG->total_rated_power + $ratedPowerFBS->total_rated_power, 3);

            $communities = Community::where("latitude", "!=", NULL)
                ->select("id", "english_name", "latitude", "longitude")->get();

            $regions = Region::where('is_archived', 0)
                ->orderBy('english_name', 'ASC')
                ->get(); 
            $subregions = SubRegion::where('is_archived', 0)
                ->orderBy('english_name', 'ASC')
                ->get();
            $energySystemTypes = EnergySystemType::where('is_archived', 0)->get();
            $donors = Donor::where('is_archived', 0)
                ->orderBy('donor_name', 'ASC')
                ->get();

            $services = ServiceType::where('is_archived', 0)
                ->orderBy('service_name', 'ASC')
                ->get();
                
            $statuses = CommunityStatus::where('is_archived', 0)
                ->orderBy('name', 'ASC')
                ->get();

            return view('employee.dashboard', compact('householdNumbers', 'numberOfPeople',
                'communityNumbers', 'h2oUsersNumbers', 'h2oSharedNumbers', 'gridUsersNumber', 
                'gridLarge', 'regionNumbers', 'gridSmall', 'h2oNumber', 'systemHoldersNumber',
                'numberOfMale', 'numberOfFemale', 'numberOfAdults', 'numberOfChildren',
                'countEnergyUsers', 'countHouseholds', 'countMgSystem', 'countFbsSystem', 
                'countH2oUsers', 'countGridUsers', 'mgIncidentsNumber', 'communitiesMasafersCount',
                'countInternetUsers', 'energyUsers', 'InternetUsers', 'totalH2oUsers',
                'servedHouseholdCount', 'waterNetworkUsers', 'internetPercentage', 
                'activeInternetCommuntiies', 'allInternetPeople', 'allInternetUsersCounts',
                'InternetPublicCount', 'activeInternetCommuntiiesCount', 'youngInternetHolders',
                'totalMgSystem', 'totalFbsSystem', 'communitiesInternet', 'allContractHolders',
                'totalRatedPower', 'communities', 'regions', 'subregions', 'energySystemTypes',
                'donors', 'services', 'statuses'))
                ->with(
                    'initialYearEnergyData', json_encode($arrayYearEnergy))
                ->with(
                    'cumulativeSumWaterData', json_encode($cumulativeSumWater))
                ->with(
                    'cumulativeSumInternetData', json_encode($cumulativeSumInternet))
                ->with(
                    'cumulativeSum', json_encode($cumulativeSum))
                ->with(
                    'incidentsData', json_encode($arrayIncidents));

        } else {

            return view('errors.not-found');
        }    
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function showMainPage()
    { 
        $settings = Setting::get();
        $teamMembers = User::get();
        $images = HomePhoto::get();

        return view('welcome', compact('settings', 'teamMembers', 'images'));
    }

    /**
     * Get the manual as a pdf
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function downloadPdf()
    {
        $myFile = public_path("comet-me.pdf");
        $headers = ['Content-Type: application/pdf'];
        $newName = 'Comet-me-manual'.time().'.pdf';

        return response()->download($myFile, $newName, $headers);
    }

    /**
     * Change resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function incidentDetails(Request $request)
    {
        $incidentStatus = $request->selected_data;

        $statusMg = IncidentStatusMgSystem::where("name", $incidentStatus)->first();
        $status_id = $statusMg->id;

        $dataIncidents = DB::table('mg_incidents')
            ->where('mg_incidents.is_archived', 0)
            ->join('communities', 'mg_incidents.community_id', '=', 'communities.id')
            ->join('energy_systems', 'mg_incidents.energy_system_id', '=', 'energy_systems.id')
            ->join('incidents', 'mg_incidents.incident_id', '=', 'incidents.id')
            ->join('incident_status_mg_systems', 'mg_incidents.incident_status_mg_system_id', 
                '=', 'incident_status_mg_systems.id')
            ->where("mg_incidents.incident_status_mg_system_id", $status_id)
            ->select("communities.english_name as community", "mg_incidents.date",
                "incidents.english_name as incident",
                "energy_systems.name as energy")
            ->get();

        $response = $dataIncidents; 
      
        return response()->json($response); 
    }

    /**
     * Filter Community Map
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function CommunityMapFilter(Request $request)
    {
        $communities = DB::table("communities")->whereNotNull("communities.latitude");
   
        // Search Region
        if($request->regions) {
            $regionIds = $request->regions;
        
            $communities->where(function ($query) use ($regionIds) {
                foreach ($regionIds as $regionId) {
                    if (is_array($regionId)) {
                        $query->orWhereIn('communities.id', function ($subQuery) use ($regionId) {
                            $subQuery->select('communities.id')
                                ->from('regions')
                                ->whereIn('communities.region_id', $regionId);
                        });
                    } else {
                        $query->orWhereIn('communities.id', function ($subQuery) use ($regionId) {
                            $subQuery->select('communities.id')
                                ->from('regions')
                                ->where('communities.region_id', $regionId);
                        });
                    }
                }
            });
        }

        // Search Sub Region
        if($request->sub_regions) {
            $subRegionIds = $request->sub_regions;
        
            $communities->where(function ($query) use ($subRegionIds) {
                foreach ($subRegionIds as $subRegionId) {
                    if (is_array($subRegionId)) {
                        $query->orWhereIn('communities.id', function ($subQuery) use ($subRegionId) {
                            $subQuery->select('communities.id')
                                ->from('sub_regions')
                                ->whereIn('communities.sub_region_id', $subRegionId);
                        });
                    } else {
                        $query->orWhereIn('communities.id', function ($subQuery) use ($subRegionId) {
                            $subQuery->select('communities.id')
                                ->from('sub_regions')
                                ->where('communities.sub_region_id', $subRegionId);
                        });
                    }
                }
            });
        }

        // Search Bedouin/Fallah
        if($request->bedouin_fallah) {
            $bedouin_fallahIds = $request->bedouin_fallah;
    
            $communities->where(function ($query) use ($bedouin_fallahIds) {
                foreach ($bedouin_fallahIds as $bedouin_fallahId) {
                    if (is_array($bedouin_fallahId)) {

                        $query->whereIn('communities.is_bedouin', "Yes")
                            ->whereIn('communities.is_fallah', "Yes");
                    } else {
                        
                        if($bedouin_fallahId == "bedouin") {

                            $query->where('communities.is_bedouin', "Yes");
                        } else if($bedouin_fallahId == "fallah") {

                            $query->where('communities.is_fallah', "Yes");
                        }
                    }
                }
            });
        }

        // Search Services
        if ($request->services) {
            $serviceIds = $request->services;
        
            $communities->where(function ($query) use ($serviceIds) {
                foreach ($serviceIds as $serviceId) {
                    if (is_array($serviceId)) {
                        $query->orWhereIn('communities.id', function ($subQuery) use ($serviceId) {
                            $subQuery->select('community_services.community_id')
                                ->from('community_services')
                                ->whereIn('community_services.service_id', $serviceId);
                        });
                    } else {
                        $query->orWhereIn('id', function ($subQuery) use ($serviceId) {
                            $subQuery->select('community_services.community_id')
                                ->from('community_services')
                                ->where('community_services.service_id', $serviceId);
                        });
                    }
                }
            });
        }

        // Search Service Year
        if($request->years) {

            $yearIds = $request->years;
        
            $communities->where(function ($query) use ($yearIds) {
                foreach ($yearIds as $yearId) {
                    if (is_array($yearId)) {
                        $query->orWhere(function ($subQuery) use ($yearId) {
                            $subQuery->whereIn('energy_service_beginning_year', $yearId)
                                ->orWhereIn('water_service_beginning_year', $yearId)
                                ->orWhereIn('internet_service_beginning_year', $yearId);
                        });
                    } else {
                        $query->orWhere(function ($subQuery) use ($yearId) {
                            $subQuery->where('energy_service_beginning_year', $yearId)
                                ->orWhere('water_service_beginning_year', $yearId)
                                ->orWhere('internet_service_beginning_year', $yearId);
                        });
                    }
                }
            });
        }

        // Search community statuses
        if($request->statuses) {
            $statusesIds = $request->statuses;
        
            $communities->where(function ($query) use ($statusesIds) {
                foreach ($statusesIds as $statusesId) {
                    if (is_array($statusesId)) {
                        $query->orWhereIn('communities.id', function ($subQuery) use ($statusesId) {
                            $subQuery->select('communities.id')
                                ->from('community_statuses')
                                ->whereIn('communities.community_status_id', $statusesId);
                        });
                    } else {
                        $query->orWhereIn('communities.id', function ($subQuery) use ($statusesId) {
                            $subQuery->select('communities.id')
                                ->from('community_statuses')
                                ->where('communities.community_status_id', $statusesId);
                        });
                    }
                }
            });
        }

        // Search System Types
        if($request->system_types) {
            $systemTypeIds = $request->system_types; 
        
            $communities->leftJoin("all_energy_meters", "communities.id", 
                "all_energy_meters.community_id");
             //   ->groupBy("communities.id");

            $communities->where(function ($query) use ($systemTypeIds) {
                foreach ($systemTypeIds as $systemTypeId) {
                    if (is_array($systemTypeId)) {
                        $query->orWhereIn('communities.id', function ($subQuery) use ($systemTypeId) {
                            $subQuery->select('all_energy_meters.community_id')
                                ->from('energy_system_types')
                                ->whereIn('energy_system_types.id', $systemTypeId);
                        });
                    } else {
                        $query->orWhereIn('communities.id', function ($subQuery) use ($systemTypeId) {
                            $subQuery->select('all_energy_meters.community_id')
                                ->from('energy_system_types')
                                ->where('energy_system_types.id', $systemTypeId);
                        });
                    }
                }
            });
        }

        // Search Donors
        if ($request->donors) {
            $donorIds = $request->donors;
        
            $communities->where(function ($query) use ($donorIds) {
                foreach ($donorIds as $donorId) {
                    if (is_array($donorId)) {
                        $query->orWhereIn('communities.id', function ($subQuery) use ($donorId) {
                            $subQuery->select('communities.id')
                                ->from('community_donors')
                                ->whereIn('community_donors.donor_id', $donorId);
                        });
                    } else {
                        $query->orWhereIn('communities.id', function ($subQuery) use ($donorId) {
                            $subQuery->select('communities.id')
                                ->from('community_donors')
                                ->where('community_donors.donor_id', $donorId);
                        });
                    }
                }
            });
        }

        if ($request->incidents) {
            $incidentsIds = $request->incidents;
    
            $communities->where(function ($query) use ($incidentsIds) {
                $query->where(function ($subQuery) use ($incidentsIds) {
                    foreach ($incidentsIds as $incidentId) {
                        switch ($incidentId) {
                            case 'mg':
                                $subQuery->orWhereExists(function ($mgQuery) {
                                    $mgQuery->select(DB::raw(1))
                                        ->from('mg_incidents')
                                        ->whereColumn('communities.id', 
                                            'mg_incidents.community_id');
                                });
                                break;
                            case 'fbs':
                                $subQuery->orWhereExists(function ($fbsQuery) {
                                    $fbsQuery->select(DB::raw(1))
                                        ->from('fbs_user_incidents')
                                        ->whereColumn('communities.id', 
                                            'fbs_user_incidents.community_id');
                                });
                                break;
                            case 'water':
                                $subQuery->orWhereExists(function ($waterQuery) {
                                    $waterQuery->select(DB::raw(1))
                                        ->from('h2o_system_incidents')
                                        ->whereColumn('communities.id', 
                                            'h2o_system_incidents.community_id');
                                });
                                break;
                            case 'internet':
                                $subQuery->orWhereExists(function ($internetQuery) {
                                    $internetQuery->select(DB::raw(1))
                                        ->from('internet_network_incidents')
                                        ->leftJoin('internet_user_incidents', 'communities.id',  
                                            'internet_user_incidents.community_id')
                                        ->whereColumn('communities.id', 
                                            'internet_network_incidents.community_id');
                                });
                                break;
                        }
                    }
                });
            });
        }

        return response()->json([
            'communities' => $communities->get()
        ]); 
    }

     /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function Ticket()
    {
   
        return view('ticket.scan');
    }

    /**
     * Filter Community Map
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function Scan(Request $request)
    {
        $scannedData = $request->code;

        return response()->json([
            'scannedData' => $scannedData
        ]); 
    }
}