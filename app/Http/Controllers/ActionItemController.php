<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
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
use App\Models\FbsUserIncident;
use App\Models\H2oSystemIncident;
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
use App\Models\InternetNetworkIncident;
use App\Models\InternetUserIncident;
use App\Models\InternetUser;
use App\Models\RecommendedCommunityEnergySystem;
use App\Models\MeterList;
use App\Models\WaterNetworkUser;
use App\Exports\MissingHouseholdDetailsExport;
use App\Exports\MissingHouseholdAcExport;
use App\Exports\InProgressHouseholdExport;
use Auth;
use Route;
use DB;
use Excel;
use PDF;

class ActionItemController extends Controller
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
        if (Auth::guard('user')->user() != null) {
            
            $internetUsers = InternetUser::where("is_archived", 0)->get();
            $internetDataApi = Http::get('http://185.190.140.86/api/data/');
            $internetDataApi = json_decode($internetDataApi, true);
            $totalContract = $internetDataApi[0]["total_contracts"];

            $youngHolders = Household::where('internet_holder_young', 1)
                ->whereNull('english_name')
                ->get();
            $internetManager = User::where("user_type_id", 6)->first();
            $communitiesNotInSystems = DB::table('internet_users')
                ->leftJoin('internet_system_communities', function ($join) {
                    $join->on('internet_users.community_id', 
                        'internet_system_communities.community_id');
                })
                ->join('communities', 'internet_users.community_id', 
                    'communities.id')
                ->whereNull('internet_system_communities.community_id')
                ->groupBy('communities.id')
                ->select('communities.id', 'communities.english_name')
                ->get();

            $archivedHouseholds = Household::where('is_archived', 0);
            $archivedCommunities = Community::where('is_archived', 0);

            $missingPhoneNumbers = $archivedHouseholds->whereNull("phone_number")->count();
            $missingAdultNumbers = $archivedHouseholds->whereNull("number_of_adults")->count();
            $missingMaleNumbers = $archivedHouseholds->whereNull("number_of_male")->count();
            $missingFemaleNumbers = $archivedHouseholds->whereNull("number_of_female")->count();
            $missingChildrenNumbers = $archivedHouseholds->whereNull("number_of_children")->count();

            $users = User::get();

            $missingEnergySystems = DB::table('recommended_community_energy_systems')
                ->join('communities', 'recommended_community_energy_systems.community_id', 
                    'communities.id')
                ->join('energy_system_types', 'energy_system_types.id', 
                    'recommended_community_energy_systems.energy_system_type_id')
                ->leftJoin('energy_systems', function ($join) {
                    $join->on('energy_systems.community_id', 'communities.id')
                        ->on('energy_systems.energy_system_type_id', 
                            'recommended_community_energy_systems.energy_system_type_id');
                })
                ->where('recommended_community_energy_systems.is_archived', 0)
                ->whereIn('energy_system_types.id', [1, 3, 4]) 
                ->whereIn('communities.community_status_id', [1, 2])
                ->whereNull('energy_systems.community_id')
                ->select('communities.english_name', 'energy_system_types.name')
                ->get();

            $communitiesAC = Community::where("community_status_id", 2)
                ->where('is_archived', 0)
                ->get();
            $acHouseholds = DB::table('households')
                ->where('households.is_archived', 0)
                ->where('households.internet_holder_young', 0)
                ->where('households.household_status_id', 2)
                ->join('communities', 'communities.id', 'households.community_id')
                ->where('communities.community_status_id', '!=', 2)
                ->select('households.english_name', 'communities.english_name as community')
                ->get();
                
            $countHouseholds = DB::table('households')
                ->where('households.is_archived', 0)
                ->where('households.internet_holder_young', 0)
                ->where('households.household_status_id', 3)
                ->join('communities', 'communities.id', 'households.community_id');
                //->where('communities.community_status_id', '!=', 2)
 
            $inProgressHouseholdsActiveCommunity = $countHouseholds
                ->where('communities.community_status_id', 3)
                ->get();
            $inProgressHouseholdsInitialCommunity = $countHouseholds
                ->where('communities.community_status_id', 1)
                ->get();
            $inProgressHouseholdsAcCommunity = DB::table('households')
                ->where('households.is_archived', 0)
                ->where('households.internet_holder_young', 0)
                ->where('households.household_status_id', 3)
                ->join('communities', 'communities.id', 'households.community_id')
                ->where('communities.community_status_id', 2)
                ->get();
           

            $missingCommunityDonors = DB::table('communities')
                ->leftJoin('community_donors', function ($join) {
                    $join->on('communities.id', 'community_donors.community_id')
                        ->where('community_donors.is_archived', 0);
                })
                ->whereNull('community_donors.community_id')
                ->where('communities.community_status_id', 3)
                ->select('communities.english_name', 'communities.id')
                ->get();

            $newEnergyHolders = AllEnergyMeter::where("is_archived", 0)
                ->where("meter_number", 0)
                ->where("is_main", "Yes")
                ->get();

            $newCommunityFbs = DB::table('households')
                ->join('communities', 'households.community_id', 'communities.id')
                //->join('all_energy_meters', 'all_energy_meters', '')
                ->where('households.is_archived', 0)
                ->where('households.internet_holder_young', 0)
                ->where('households.household_status_id', 2)
                ->get();
                
            $newCommunityMgExtension = DB::table('households')
                ->join('communities', 'households.community_id', 'communities.id')
                ->where('households.is_archived', 0)
                ->where('households.internet_holder_young', 0)
                ->where('households.household_status_id', 2)
                ->get(); 

            $communityWaterService =  DB::table('communities')
                ->where('communities.is_archived', 0)
                ->join('all_water_holders', 'communities.id', 'all_water_holders.community_id')
                ->whereNull('communities.water_service')
                ->orWhere('communities.water_service', 'No')
                ->select('communities.*')
                ->groupBy('communities.id')
                ->get();

            $communityWaterServiceYear =  Community::where('is_archived', 0)
                ->where('water_service', 'Yes')
                ->whereNull('water_service_beginning_year')
                ->select('communities.*')
                ->get();

            $communityInternetService =  DB::table('communities')
                ->where('communities.is_archived', 0)
                ->join('internet_users', 'communities.id', 'internet_users.community_id')
                ->whereNull('communities.internet_service')
                ->orWhere('communities.internet_service', 'No')
                ->select('communities.*')
                ->groupBy('communities.id')
                ->get();

            $communityInternetServiceYear = Community::where('is_archived', 0)
                ->where('internet_service', 'Yes')
                ->whereNull('internet_service_beginning_year')
                ->select('communities.*')
                ->get();

            $missingCommunityRepresentatives= DB::table('communities')
                ->where('communities.is_archived', 0)
                ->leftJoin('community_representatives', function ($join) {
                    $join->on('communities.id', 'community_representatives.community_id')
                        ->where('community_representatives.is_archived', 0);
                })
                ->whereNull('community_representatives.community_id')
                ->select('communities.*')
                ->get();
            
          
            $missingSchoolDetails = DB::table('public_structures')
                ->where('public_structures.is_archived', 0)
                ->where("public_structure_category_id1", 1)
                ->leftJoin('school_public_structures', function ($join) {
                    $join->on('public_structures.id', 'school_public_structures.public_structure_id')
                        ->where('school_public_structures.is_archived', 0)
                        ->where('school_public_structures.number_of_students', '!=', NULL);
                })
                ->whereNull('school_public_structures.public_structure_id')
                ->join('communities', 'public_structures.community_id', 'communities.id')
                ->select(
                    'public_structures.english_name', 
                    'communities.english_name as community')
                ->get();

            $newEnergyUsers = AllEnergyMeter::where('meter_number', 0)
                ->where('is_archived', 0)
                ->get();
 
            
            $mgIncidents = MgIncident::where('is_archived', 0)->get();
            $fbsIncidents = FbsUserIncident::where('is_archived', 0)->get();
            $waterIncidents = H2oSystemIncident::where('is_archived', 0)->get();
            $networkIncidents = InternetNetworkIncident::where('is_archived', 0)->get();
            $internetHolderIncidents = InternetUserIncident::where('is_archived', 0)->get();

            return view('actions.index', compact('youngHolders', 'internetManager',
                'communitiesNotInSystems', 'missingPhoneNumbers', 'missingAdultNumbers',
                'missingMaleNumbers', 'missingFemaleNumbers', 'missingChildrenNumbers',
                'users', 'missingEnergySystems', 'acHouseholds', 'internetUsers',
                'internetDataApi', 'missingCommunityDonors', 'communitiesAC',
                'newCommunityFbs', 'newCommunityMgExtension', 'newEnergyHolders',
                'missingCommunityRepresentatives', 'communityWaterService', 
                'communityWaterServiceYear', 'communityInternetService', 'waterIncidents',
                'communityInternetServiceYear', 'missingSchoolDetails', 'fbsIncidents',
                'inProgressHouseholdsAcCommunity', 'newEnergyUsers', 'mgIncidents',
                'networkIncidents', 'internetHolderIncidents'));

        } else {

            return view('errors.not-found');
        }    
    }

    /**
     * 
     * @return \Illuminate\Support\Collection
     */
    public function householdMissingDetails(Request $request) 
    {       
        $data = DB::table('households')
            ->join('communities', 'households.community_id', 'communities.id')
            ->join('regions', 'communities.region_id', 'regions.id')
            ->join('sub_regions', 'communities.sub_region_id', 'sub_regions.id')
            ->leftJoin('household_statuses', 'households.household_status_id', 
                'household_statuses.id')
            ->leftJoin('professions', 'households.profession_id', 
                'professions.id')
            ->leftJoin('energy_request_systems', 'households.id', 
                'energy_request_systems.household_id')
            ->leftJoin('all_energy_meters', 'households.id', 
                'all_energy_meters.household_id')
            ->leftJoin('energy_system_types', 'energy_system_types.id', 
                'all_energy_meters.energy_system_type_id')
            ->leftJoin('all_energy_meter_donors', 'all_energy_meters.id', 
                'all_energy_meter_donors.all_energy_meter_id')
            ->leftJoin('donors as energy_donor', 'all_energy_meter_donors.donor_id', 'energy_donor.id')
            ->leftJoin('all_water_holders', 'households.id', 
                'all_water_holders.household_id')
            ->leftJoin('all_water_holder_donors', 'all_water_holders.id', 
                'all_water_holder_donors.all_water_holder_id')
            ->leftJoin('donors as water_donor', 'all_water_holder_donors.donor_id', 'water_donor.id')
            ->leftJoin('internet_users', 'households.id', 
                'internet_users.household_id')
            ->leftJoin('internet_user_donors', 'internet_users.id', 
                'internet_user_donors.internet_user_id')
            ->leftJoin('donors as internet_donor', 'internet_user_donors.donor_id', 'internet_donor.id')
            ->where('households.is_archived', 0)
            ->select('households.english_name as english_name', 
                'households.arabic_name as arabic_name', 
                'communities.english_name as community_name',
                'regions.english_name as region', 'sub_regions.english_name as sub_region',
                'professions.profession_name', 
                'number_of_male', 'number_of_female', 'number_of_children','number_of_adults', 
                'school_students', 'household_statuses.status', 
                'all_energy_meters.is_main', 'energy_system_types.name',
                'all_energy_meters.meter_number', 
                DB::raw('group_concat(DISTINCT energy_donor.donor_name) as meter_donor'),
                'energy_request_systems.date', 
                'water_system_status', 
                DB::raw('group_concat(DISTINCT water_donor.donor_name) as water_donor'),
                'internet_system_status',
                DB::raw('group_concat(DISTINCT internet_donor.donor_name) as internet_donor'),
            )
            ->groupBy('households.id');
            
        return Excel::download(new MissingHouseholdDetailsExport($request, $data), 
            'missing_details_household.xlsx');
    }

     /**
     * 
     * @return \Illuminate\Support\Collection
     */
    public function householdAcExport(Request $request) 
    {       
        $data = DB::table('households')
            ->join('communities', 'communities.id', 'households.community_id')
            ->join('community_statuses', 'communities.community_status_id', 
                'community_statuses.id')
            ->join('regions', 'communities.region_id', 'regions.id')
            ->join('sub_regions', 'communities.sub_region_id', 'sub_regions.id')
            ->leftJoin('professions', 'households.profession_id', 
                'professions.id')
            ->where('households.is_archived', 0)
            ->where('households.internet_holder_young', 0)
            ->where('households.household_status_id', 2)
            ->where('communities.community_status_id', '!=', 2)
            ->select(
                'households.english_name', 'communities.english_name as community',
                'community_statuses.name', 'regions.english_name as region', 
                'sub_regions.english_name as sub_region', 
                'professions.profession_name', 
                'number_of_male', 'number_of_female', 'number_of_children', 
                'number_of_adults');
            
        return Excel::download(new MissingHouseholdAcExport($request, $data), 
            'ac_households.xlsx');
    }

    /**
     * 
     * @return \Illuminate\Support\Collection
     */
    public function householdInProgressExport(Request $request) 
    {       
        $data = DB::table('households')
            ->join('communities', 'communities.id', 'households.community_id')
            ->join('community_statuses', 'communities.community_status_id', 
                'community_statuses.id')
            ->join('regions', 'communities.region_id', 'regions.id')
            ->leftJoin('professions', 'households.profession_id', 
                'professions.id')
            ->leftJoin('energy_system_types', 'households.energy_system_type_id', 
                'energy_system_types.id')
            ->where('households.is_archived', 0)
            ->where('households.internet_holder_young', 0)
            ->where('households.household_status_id', 3)
            ->where('communities.community_status_id', 2)
            ->select(
                'households.english_name', 'communities.english_name as community',
                'regions.english_name as region', 
                'energy_system_types.name',  
                'professions.profession_name', 
                'number_of_male', 'number_of_female', 'number_of_children', 
                'number_of_adults');
            
        return Excel::download(new InProgressHouseholdExport($request, $data), 
            'in_progress_households.xlsx');
    }

    /**
     * 
     * @return \Illuminate\Support\Collection
     */
    public function householdMissingDonors(Request $request) 
    {    
        $community = Community::findOrFail($request->community_id);   
        $missingEnergyUserDonors = DB::table('all_energy_meters')
            ->leftJoin('households', 'all_energy_meters.household_id', 
                'households.id')
            ->leftJoin('public_structures', 'all_energy_meters.public_structure_id', 
                'public_structures.id')
            ->join('energy_system_types', 'all_energy_meters.energy_system_type_id', 
                'energy_system_types.id')
            ->leftJoin('all_energy_meter_donors', function ($join) {
                $join->on('all_energy_meters.id', 
                    'all_energy_meter_donors.community_id')
                    ->where('all_energy_meter_donors.is_archived', 0);
            })
            ->whereNull('all_energy_meter_donors.all_energy_meter_id')
            ->where('all_energy_meters.community_id', 
                $request->community_id)
            ->select( 
                DB::raw('IFNULL(households.english_name, public_structures.english_name) 
                    as holder_name'),
                'households.english_name', 
                'energy_system_types.name')
            ->get();
            
        return response()->json([
            'community' => $community,
            'html' => $missingEnergyUserDonors
        ]);
    }
}