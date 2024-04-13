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
use App\Models\ActionItem;
use App\Models\ActionStatus;
use App\Models\ActionPriority;
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
use App\Models\GridCommunityCompound;
use App\Models\Setting;
use App\Models\SubRegion;
use App\Models\SubCommunity;
use App\Models\Settlement;
use App\Models\ServiceType;
use App\Models\PublicStructure;
use App\Models\PublicStructureCategory;
use App\Models\ProductType;
use App\Models\CommunityWaterSource;
use App\Models\ElectricityMaintenanceCall;
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

            $notStartedACSurveyCommunities =  Community::where("community_status_id", 1)
                ->where('is_archived', 0)
                ->get();

            Community::where("community_status_id", 1)
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

            $miscRequested =  DB::table('households')
                ->join('energy_request_systems', 'energy_request_systems.household_id', 'households.id')
                ->where('households.is_archived', 0)
                ->where('households.household_status_id', 5)
                ->where('energy_request_systems.recommendede_energy_system_id', 2)
                ->get(); 
 
            $queryCompounds = DB::table('compounds')
                ->join('communities', 'communities.id', 'compounds.community_id')
                ->join('regions', 'communities.region_id', 'regions.id')
                ->join('community_statuses', 'communities.community_status_id', 
                    'community_statuses.id')
                ->join('compound_households', 'compound_households.compound_id', 'compounds.id')
                ->join('households', 'compound_households.household_id', 'households.id')
                ->leftJoin('energy_system_types', 'households.energy_system_type_id', 'energy_system_types.id')
                ->where('communities.is_archived', 0)
                ->where('communities.community_status_id', 1)
                ->select(
                    'compounds.english_name',
                    DB::raw('COUNT(compound_households.household_id) as number_of_household')
                    )
                ->groupBy('compounds.english_name')
                ->get(); 

            $notStartedACInstallationCommunities = DB::table('communities')
                ->join('regions', 'communities.region_id', 'regions.id')
                ->join('community_statuses', 'communities.community_status_id', 
                    'community_statuses.id')
                ->join('households as all_households', 'all_households.community_id',
                    'communities.id')
                ->leftJoin('energy_system_types as all_energy_types', 'all_energy_types.id',
                    'all_households.energy_system_type_id')
                ->where('communities.is_archived', 0)
                ->where('communities.community_status_id', 2)
                ->whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('compounds')
                        ->whereRaw('compounds.community_id = communities.id');
                })
                ->select( 
                    'communities.english_name', 'communities.id',
                    DB::raw('COUNT(CASE WHEN all_households.household_status_id = 2 
                        THEN 1 ELSE NULL END) as number'),
                    DB::raw('SUM(CASE WHEN all_households.household_status_id IN 
                        (2, 1) THEN 1 ELSE 0 END) as number_of_households')
                    )
                ->groupBy('communities.english_name')
                ->get();

            $notStartedACInstallationCompounds = DB::table('compounds')
                ->join('communities', 'communities.id', 'compounds.community_id')
                ->join('regions', 'communities.region_id', 'regions.id')
                ->join('community_statuses', 'communities.community_status_id', 
                    'community_statuses.id')
                ->join('compound_households', 'compound_households.compound_id', 'compounds.id')
                ->join('households', 'compound_households.household_id', 'households.id')
                ->leftJoin('energy_system_types', 'households.energy_system_type_id', 
                    'energy_system_types.id')
                ->where('communities.is_archived', 0)
                ->where(function ($query) {
                    $query->where('communities.community_status_id', 2);
                })
                ->select(
                    'compounds.id',
                    'compounds.english_name', 
                    DB::raw('COUNT(CASE WHEN households.household_status_id = 2 THEN 1 ELSE NULL END) as number'),
                    DB::raw('SUM(CASE WHEN households.household_status_id IN (2, 3) THEN 1 ELSE 0 END) as number_of_households')
                )
                ->groupBy('compounds.english_name')
                ->havingRaw('number > 0') // Exclude records where the number is zero
                ->get();

          //  die($notStartedACInstallationCompounds);

            $totalNotStartedAC = $notStartedACInstallationCommunities->count() +
                $notStartedACInstallationCompounds->count();

            $communitiesElecticityRoomMissing = DB::table('communities')
                ->leftJoin('grid_community_compounds', 'communities.id', 
                    'grid_community_compounds.community_id')
                ->leftJoin('households', 'communities.id', 'households.community_id')  
                ->where('grid_community_compounds.electricity_room', 'No')
                ->where('households.household_status_id', 3)
                ->select('communities.id', 'communities.english_name as community',  
                    'grid_community_compounds.grid',
                    'grid_community_compounds.electricity_room')
                ->distinct()
                ->get();

            $communitiesGridMissing = DB::table('communities')
                ->leftJoin('grid_community_compounds', 'communities.id', 
                    'grid_community_compounds.community_id')
                ->leftJoin('households', 'communities.id', 'households.community_id')  
                ->where('households.household_status_id', 3)
                ->where('grid_community_compounds.grid', 'No')
                ->select('communities.id', 'communities.english_name as community',  
                    'grid_community_compounds.grid',
                    'grid_community_compounds.electricity_room')
                ->distinct()
                ->get();

            $compoundsElecticityRoomMissing = DB::table('compounds')
                ->leftJoin('grid_community_compounds', 'compounds.id', 'grid_community_compounds.compound_id')
                ->leftJoin('communities', 'communities.id', 'compounds.community_id')
                ->leftJoin('households', 'communities.id', 'households.community_id')  
                ->where('households.household_status_id', 3)
                ->where('grid_community_compounds.electricity_room', 'No')
                ->select(
                    'compounds.id', 'compounds.english_name as compound',  
                    'grid_community_compounds.grid',
                    'grid_community_compounds.electricity_room')
                ->groupBy('compounds.id')
                ->get(); 

            //die($compoundsElecticityRoomMissing);
            $compoundsGridMissing = DB::table('compounds')
                ->leftJoin('grid_community_compounds', 'compounds.id', 'grid_community_compounds.compound_id')
                ->leftJoin('communities', 'communities.id', 'compounds.community_id')
                ->leftJoin('households', 'communities.id', 'households.community_id')  
                ->where('households.household_status_id', 3)
                ->where('grid_community_compounds.grid', 'No')
                ->select('compounds.id', 'compounds.english_name as compound',  
                    'grid_community_compounds.grid',
                    'grid_community_compounds.electricity_room')
                ->groupBy('compounds.id')
                ->get();

            $communitiesNeedToCompleteElectricity = DB::table('grid_community_compounds')
                ->join('communities', 'communities.id', 'grid_community_compounds.community_id')
                ->where('communities.community_status_id', [2, 3])
                ->where('grid_community_compounds.electricity_room', 'No')
                ->select('communities.english_name as community')
                ->get();

            $communitiesMgSmgNotDCInstallations = DB::table('all_energy_meters')
                ->join('communities', 'communities.id', 'all_energy_meters.community_id')
                ->where(function($query) {
                    $query->where('all_energy_meters.is_archived', 0)
                        ->where('all_energy_meters.energy_system_type_id', '!=', 2)
                        ->where('all_energy_meters.meter_number', 0)
                        ->where('communities.community_status_id', 2);
                })
                ->orWhere(function($query) {
                    $query->where('all_energy_meters.is_archived', 0)
                        ->where('all_energy_meters.energy_system_type_id', '!=', 2)
                        ->where('all_energy_meters.is_main', 'No')
                        ->whereNull('all_energy_meters.meter_number')
                        ->where('communities.community_status_id', 2);
                })
                ->select(
                    'communities.id',
                    'communities.english_name as community',
                    DB::raw('COUNT(all_energy_meters.id) as number_of_holders'),
                    DB::raw('COUNT(communities.id) as number'),
                    )
                ->groupBy('communities.id')
                ->get();
 
            $holdersMgSmgNotDCInstallations =  DB::table('all_energy_meters')
                ->join('communities', 'communities.id', 'all_energy_meters.community_id')
                ->leftJoin('households', 'households.id', 'all_energy_meters.household_id')
                ->leftJoin('public_structures', 'public_structures.id', 
                    'all_energy_meters.public_structure_id')
                ->where(function($query) {
                    $query->where('all_energy_meters.is_archived', 0)
                        ->where('all_energy_meters.energy_system_type_id', '!=', 2)
                        ->where('all_energy_meters.meter_number', 0)
                        ->where('communities.community_status_id', 2);
                })
                ->orWhere(function($query) {
                    $query->where('all_energy_meters.is_archived', 0)
                        ->where('all_energy_meters.energy_system_type_id', '!=', 2)
                        ->where('all_energy_meters.is_main', 'No')
                        ->whereNull('all_energy_meters.meter_number')
                        ->where('communities.community_status_id', 2);
                })
                ->select(
                    'communities.id',
                    'communities.english_name as community',
                    'all_energy_meters.is_main',
                    DB::raw('IFNULL(households.english_name, public_structures.english_name) 
                        as holder')
                )
                ->get();
            
    
            $communitiesFbsNotDCInstallations = DB::table('all_energy_meters')
                ->join('communities', 'communities.id', 'all_energy_meters.community_id')
                ->where('all_energy_meters.energy_system_type_id', 2)
                ->where('all_energy_meters.meter_number', 0)
                ->select(
                    'communities.id',
                    'communities.english_name as community',
                    DB::raw('COUNT(all_energy_meters.id) as number_of_holders'),
                    DB::raw('COUNT(communities.id) as number'),
                    )
                ->groupBy('communities.id')
                ->get();

            $holdersFbsNotDCInstallations =  DB::table('all_energy_meters')
                ->join('communities', 'communities.id', 'all_energy_meters.community_id')
                ->where('all_energy_meters.energy_system_type_id', 2)
                ->where('all_energy_meters.meter_number', 0)
                ->leftJoin('households', 'households.id', 'all_energy_meters.household_id')
                ->leftJoin('public_structures', 'public_structures.id', 
                    'all_energy_meters.public_structure_id')
                ->select(
                    'communities.id',
                    'communities.english_name as community',
                    DB::raw('IFNULL(households.english_name, public_structures.english_name) 
                    as holder'),
                )
                ->get();


            $electricityNewMaintenances = DB::table('electricity_maintenance_calls')
                ->where('electricity_maintenance_calls.is_archived', 0)
                ->join('communities', 'electricity_maintenance_calls.community_id', 'communities.id')
                ->join('electricity_maintenance_call_actions', 'electricity_maintenance_calls.id', 
                    'electricity_maintenance_call_actions.electricity_maintenance_call_id')
                ->leftJoin('energy_systems', 'electricity_maintenance_calls.energy_system_id', 
                    'energy_systems.id')
                ->leftJoin('households', 'electricity_maintenance_calls.household_id', 'households.id')
                ->leftJoin('public_structures', 'electricity_maintenance_calls.public_structure_id', 
                    'public_structures.id')
                ->leftJoin('maintenance_electricity_actions', 'maintenance_electricity_actions.id', 
                    'electricity_maintenance_call_actions.maintenance_electricity_action_id')
                ->where('electricity_maintenance_calls.maintenance_status_id', 1)
                ->where('electricity_maintenance_call_actions.maintenance_electricity_action_id', '!=', 75)
                ->select(
                    'communities.english_name as community',
                    'electricity_maintenance_calls.date_of_call',
                    DB::raw('COALESCE(households.english_name, public_structures.english_name, energy_systems.name) as holder'),
                    DB::raw('GROUP_CONCAT(DISTINCT maintenance_electricity_actions.maintenance_action_electricity_english) as maintenance_actions')
                )
                ->groupBy('electricity_maintenance_calls.id')
                ->get();

            $electricityInProgressMaintenances = DB::table('electricity_maintenance_calls')
                ->where('electricity_maintenance_calls.is_archived', 0)
                ->join('communities', 'electricity_maintenance_calls.community_id', 'communities.id')
                ->join('electricity_maintenance_call_actions', 'electricity_maintenance_calls.id', 
                    'electricity_maintenance_call_actions.electricity_maintenance_call_id')
                ->leftJoin('energy_systems', 'electricity_maintenance_calls.energy_system_id', 
                    'energy_systems.id')
                ->leftJoin('households', 'electricity_maintenance_calls.household_id', 'households.id')
                ->leftJoin('public_structures', 'electricity_maintenance_calls.public_structure_id', 
                    'public_structures.id')
                ->leftJoin('maintenance_electricity_actions', 'maintenance_electricity_actions.id', 
                    'electricity_maintenance_call_actions.maintenance_electricity_action_id')
                ->where('electricity_maintenance_calls.maintenance_status_id', 2)
                ->select(
                    'communities.english_name as community',
                    'electricity_maintenance_calls.date_of_call',
                    DB::raw('COALESCE(households.english_name, public_structures.english_name, energy_systems.name) as holder'),
                    DB::raw('GROUP_CONCAT(DISTINCT maintenance_electricity_actions.maintenance_action_electricity_english) as maintenance_actions')
                )
                ->groupBy('electricity_maintenance_calls.id')
                ->get();

            $refrigeratorNewMaintenances =  DB::table('refrigerator_maintenance_calls')
                ->where('refrigerator_maintenance_calls.is_archived', 0)
                ->join('communities', 'refrigerator_maintenance_calls.community_id', 'communities.id')
                ->join('refrigerator_maintenance_call_actions', 'refrigerator_maintenance_calls.id', 
                    'refrigerator_maintenance_call_actions.refrigerator_maintenance_call_id')
                ->leftJoin('households', 'refrigerator_maintenance_calls.household_id', 'households.id')
                ->leftJoin('public_structures', 'refrigerator_maintenance_calls.public_structure_id', 
                    'public_structures.id')
                ->leftJoin('maintenance_refrigerator_actions', 
                    'refrigerator_maintenance_call_actions.maintenance_refrigerator_action_id', 
                    'maintenance_refrigerator_actions.id')
                ->where('refrigerator_maintenance_calls.maintenance_status_id', 1)
                ->where('refrigerator_maintenance_call_actions.refrigerator_maintenance_call_id', '!=', 75)
                ->select(
                    'communities.english_name as community',
                    'refrigerator_maintenance_calls.date_of_call',
                    DB::raw('COALESCE(households.english_name, public_structures.english_name) as holder'),
                    DB::raw('GROUP_CONCAT(DISTINCT maintenance_refrigerator_actions.maintenance_action_refrigerator) as maintenance_actions')
                )
                ->groupBy('refrigerator_maintenance_calls.id')
                ->get();
        

            $refrigeratorInProgressMaintenances =DB::table('refrigerator_maintenance_calls')
                ->where('refrigerator_maintenance_calls.is_archived', 0)
                ->join('communities', 'refrigerator_maintenance_calls.community_id', 'communities.id')
                ->join('refrigerator_maintenance_call_actions', 'refrigerator_maintenance_calls.id', 
                    'refrigerator_maintenance_call_actions.refrigerator_maintenance_call_id')
                ->leftJoin('households', 'refrigerator_maintenance_calls.household_id', 'households.id')
                ->leftJoin('public_structures', 'refrigerator_maintenance_calls.public_structure_id', 
                    'public_structures.id')
                ->leftJoin('maintenance_refrigerator_actions', 
                    'refrigerator_maintenance_call_actions.maintenance_refrigerator_action_id', 
                    'maintenance_refrigerator_actions.id')
                ->where('refrigerator_maintenance_calls.maintenance_status_id', 2)
                ->select(
                    'communities.english_name as community',
                    'refrigerator_maintenance_calls.date_of_call',
                    DB::raw('COALESCE(households.english_name, public_structures.english_name) as holder'),
                    DB::raw('GROUP_CONCAT(DISTINCT maintenance_refrigerator_actions.maintenance_action_refrigerator) as maintenance_actions')
                )
                ->groupBy('refrigerator_maintenance_calls.id')
                ->get();

            $waterNewMaintenances = DB::table('h2o_maintenance_calls')
                ->where('h2o_maintenance_calls.is_archived', 0)
                ->join('communities', 'h2o_maintenance_calls.community_id', 'communities.id')
                ->join('h2o_maintenance_call_actions', 'h2o_maintenance_calls.id', 
                    'h2o_maintenance_call_actions.h2o_maintenance_call_id')
                ->leftJoin('households', 'h2o_maintenance_calls.household_id', 'households.id')
                ->leftJoin('public_structures', 'h2o_maintenance_calls.public_structure_id', 
                    'public_structures.id')
                ->leftJoin('maintenance_h2o_actions', 'h2o_maintenance_call_actions.maintenance_h2o_action_id', 
                    'maintenance_h2o_actions.id')
                ->where('h2o_maintenance_calls.maintenance_status_id', 1)
                ->select(
                    'communities.english_name as community',
                    'h2o_maintenance_calls.date_of_call',
                    DB::raw('COALESCE(households.english_name, public_structures.english_name) as holder'),
                    DB::raw('GROUP_CONCAT(DISTINCT maintenance_h2o_actions.maintenance_action_h2o) as maintenance_actions')
                )
                ->groupBy('h2o_maintenance_calls.id')
                ->get();

            $waterInProgressMaintenances =  DB::table('h2o_maintenance_calls')
                ->where('h2o_maintenance_calls.is_archived', 0)
                ->join('communities', 'h2o_maintenance_calls.community_id', 'communities.id')
                ->join('h2o_maintenance_call_actions', 'h2o_maintenance_calls.id', 
                    'h2o_maintenance_call_actions.h2o_maintenance_call_id')
                ->leftJoin('households', 'h2o_maintenance_calls.household_id', 'households.id')
                ->leftJoin('public_structures', 'h2o_maintenance_calls.public_structure_id', 
                    'public_structures.id')
                ->leftJoin('maintenance_h2o_actions', 'h2o_maintenance_call_actions.maintenance_h2o_action_id', 
                    'maintenance_h2o_actions.id')
                ->where('h2o_maintenance_calls.maintenance_status_id', 2)
                ->select(
                    'communities.english_name as community',
                    'h2o_maintenance_calls.date_of_call',
                    DB::raw('COALESCE(households.english_name, public_structures.english_name) as holder'),
                    DB::raw('GROUP_CONCAT(DISTINCT maintenance_h2o_actions.maintenance_action_h2o) as maintenance_actions')
                )
                ->groupBy('h2o_maintenance_calls.id')
                ->get();
      
            $missingUserEnergDonors = DB::table('all_energy_meters')
                ->join('communities', 'all_energy_meters.community_id', 'communities.id')
                ->join('energy_systems', 'all_energy_meters.energy_system_id', 'energy_systems.id')
                ->leftJoin('energy_system_types', 'all_energy_meters.energy_system_type_id', 
                    'energy_system_types.id')
                ->leftJoin('all_energy_meter_donors', 'all_energy_meters.id', 
                    'all_energy_meter_donors.all_energy_meter_id')
                ->join('households', 'households.id', 
                    'all_energy_meters.household_id')
                ->join('household_statuses', 'households.household_status_id', 'household_statuses.id')
                ->whereNull('all_energy_meter_donors.all_energy_meter_id')
                ->select(
                    'communities.english_name as community', 
                    'households.english_name as household_name', 'household_statuses.status',
                    'energy_systems.name as energy_name', 'energy_system_types.name as type')
               // ->groupBy('communities.id')
                ->get();

            $missingEnergyPublicDonors = DB::table('all_energy_meters')
                ->join('communities', 'all_energy_meters.community_id', 'communities.id')
                ->join('energy_systems', 'all_energy_meters.energy_system_id', 'energy_systems.id')
                ->leftJoin('energy_system_types', 'all_energy_meters.energy_system_type_id', 
                    'energy_system_types.id')
                ->leftJoin('all_energy_meter_donors', 'all_energy_meters.id', 
                    'all_energy_meter_donors.all_energy_meter_id')
                ->join('public_structures', 'public_structures.id', 
                    'all_energy_meters.public_structure_id')
                ->whereNull('all_energy_meter_donors.all_energy_meter_id')
                ->select(
                    'communities.english_name', 'public_structures.english_name as public',
                    'energy_systems.name as energy_name', 'energy_system_types.name as type')
                ->groupBy('communities.id')
                ->get();

            $missingUserWaterDonors = DB::table('all_water_holders')
                ->join('communities', 'all_water_holders.community_id', 'communities.id')
                ->leftJoin('all_water_holder_donors', 'all_water_holders.id', 
                    'all_water_holder_donors.all_water_holder_id')
                ->join('households', 'households.id', 
                    'all_water_holders.household_id')
                ->whereNull('all_water_holder_donors.all_water_holder_id')
                ->select(
                    'communities.english_name as community', 
                    'households.english_name as household_name')
                ->get();

            $missingPublicWaterDonors = DB::table('all_water_holders')
                ->join('communities', 'all_water_holders.community_id', 'communities.id')
                ->leftJoin('all_water_holder_donors', 'all_water_holders.id', 
                    'all_water_holder_donors.all_water_holder_id')
                ->join('public_structures', 'public_structures.id', 
                    'all_water_holders.public_structure_id')
                ->whereNull('all_water_holder_donors.all_water_holder_id')
                ->select(
                    'communities.english_name', 'public_structures.english_name as public')
                ->groupBy('communities.id')
                ->get();

            $missingUserInternetDonors = DB::table('internet_users')
                ->join('communities', 'internet_users.community_id', 'communities.id')
                ->leftJoin('internet_user_donors', 'internet_users.id', 
                    'internet_user_donors.internet_user_id')
                ->join('households', 'households.id', 
                    'internet_users.household_id')
                ->whereNull('internet_user_donors.internet_user_id')
                ->select(
                    'communities.english_name as community', 
                    'households.english_name as household_name')
                ->get();

            $missingPublicInternetDonors = DB::table('internet_users')
                ->join('communities', 'internet_users.community_id', 'communities.id')
                ->leftJoin('internet_user_donors', 'internet_users.id', 
                    'internet_user_donors.internet_user_id')
                ->join('public_structures', 'public_structures.id', 
                    'internet_users.public_structure_id')
                ->whereNull('internet_user_donors.internet_user_id')
                ->select(
                    'communities.english_name as community', 
                    'public_structures.english_name as public')
                ->get();
            
            $notYetConnectedGround  = DB::table('all_energy_meters')
                ->where('all_energy_meters.is_archived', 0)
                ->where('all_energy_meters.energy_system_type_id', 2)
                ->where('all_energy_meters.ground_connected', "No")
                ->join('communities', 'all_energy_meters.community_id', 'communities.id')
                ->leftJoin('households', 'all_energy_meters.household_id', 'households.id')
                ->leftJoin('public_structures', 'all_energy_meters.public_structure_id', 
                    'public_structures.id')
                ->join('energy_systems', 'all_energy_meters.energy_system_id', 'energy_systems.id')
                ->select(
                    'communities.english_name as community',
                    DB::raw('IFNULL(households.english_name, public_structures.english_name) 
                        as holder'),
                    'energy_systems.name as energy_system'
                )
                ->get();

            $notYetSafteyCheckedFbs =  DB::table('all_energy_meters')
                ->leftJoin('all_energy_meter_safety_checks', function($join) {
                    $join->on('all_energy_meters.id', '=', 'all_energy_meter_safety_checks.all_energy_meter_id')
                        ->where('all_energy_meter_safety_checks.is_archived', 0);
                })
                ->join('communities', 'all_energy_meters.community_id', 'communities.id')
                ->leftJoin('households', 'all_energy_meters.household_id', 'households.id')
                ->leftJoin('public_structures', 'all_energy_meters.public_structure_id', 'public_structures.id')
                ->join('energy_systems', 'all_energy_meters.energy_system_id', 'energy_systems.id')
                ->select(
                    'communities.english_name as community',
                    DB::raw('IFNULL(households.english_name, public_structures.english_name) as holder'),
                    'energy_systems.name as energy_system'
                )
                ->whereNull('all_energy_meter_safety_checks.all_energy_meter_id')
                ->where('all_energy_meters.is_archived', 0)
                ->where('all_energy_meters.energy_system_type_id', 2)
                ->get(); 

            $notYetSafteyCompletedFbs = DB::table('all_energy_meter_safety_checks')
                ->join('all_energy_meters', 'all_energy_meters.id', 
                    'all_energy_meter_safety_checks.all_energy_meter_id')
                ->where('all_energy_meters.is_archived', 0)
                ->where('all_energy_meter_safety_checks.is_archived', 0)
                ->whereNull('all_energy_meter_safety_checks.ph_loop')
                ->where('all_energy_meters.energy_system_type_id', 2)
                ->where('all_energy_meters.ground_connected', "No")
                ->join('communities', 'all_energy_meters.community_id', 'communities.id')
                ->leftJoin('households', 'all_energy_meters.household_id', 'households.id')
                ->leftJoin('public_structures', 'all_energy_meters.public_structure_id', 
                    'public_structures.id')
                ->join('energy_systems', 'all_energy_meters.energy_system_id', 'energy_systems.id')
                ->select(
                    'communities.english_name as community',
                    DB::raw('IFNULL(households.english_name, public_structures.english_name) 
                        as holder'),
                    'energy_systems.name as energy_system'
                )
                ->get();

            $notYetCompletedSafteyCheckedMg =  DB::table('all_energy_meter_safety_checks')
                ->join('all_energy_meters', 'all_energy_meters.id', 
                    'all_energy_meter_safety_checks.all_energy_meter_id')
                ->where('all_energy_meters.is_archived', 0)
                ->where('all_energy_meter_safety_checks.is_archived', 0)
                ->whereNull('all_energy_meter_safety_checks.ph_loop')
                ->where('all_energy_meters.energy_system_type_id', '!=', 2)
                ->join('communities', 'all_energy_meters.community_id', 'communities.id')
                ->leftJoin('households', 'all_energy_meters.household_id', 'households.id')
                ->leftJoin('public_structures', 'all_energy_meters.public_structure_id', 
                    'public_structures.id')
                ->join('energy_systems', 'all_energy_meters.energy_system_id', 'energy_systems.id')
                ->select(
                    'communities.english_name as community',
                    DB::raw('IFNULL(households.english_name, public_structures.english_name) 
                        as holder'),
                    'energy_systems.name as energy_system'
                )
                ->get();

            $notYetSafteyCheckedMg = DB::table('all_energy_meters')
                ->leftJoin('all_energy_meter_safety_checks', function($join) {
                    $join->on('all_energy_meters.id', '=', 'all_energy_meter_safety_checks.all_energy_meter_id')
                        ->where('all_energy_meter_safety_checks.is_archived', 0);
                })
                ->join('communities', 'all_energy_meters.community_id', 'communities.id')
                ->leftJoin('households', 'all_energy_meters.household_id', 'households.id')
                ->leftJoin('public_structures', 'all_energy_meters.public_structure_id', 'public_structures.id')
                ->join('energy_systems', 'all_energy_meters.energy_system_id', 'energy_systems.id')
                ->select(
                    'communities.english_name as community',
                    DB::raw('IFNULL(households.english_name, public_structures.english_name) as holder'),
                    'energy_systems.name as energy_system'
                )
                ->whereNull('all_energy_meter_safety_checks.all_energy_meter_id')
                ->where('all_energy_meters.is_archived', 0)
                ->where('all_energy_meters.energy_system_type_id', '!=', 2)
                ->get();

            $actionItems = ActionItem::where('is_archived', 0)->get();

            // Group action items by user_id
            $groupedActionItems = $actionItems->where('action_status_id', '!=', 4)
                ->groupBy('user_id');
            $actionStatuses = ActionStatus::all();
            $actionPriorities = ActionPriority::all();

            if(Auth::guard('user')->user()->user_type_id == 1 || 
                Auth::guard('user')->user()->user_type_id == 2) {

                    return view('actions.admin.index', compact('actionStatuses', 'actionPriorities',
                    'youngHolders', 'internetManager',
                    'communitiesNotInSystems', 'missingPhoneNumbers', 'missingAdultNumbers',
                    'missingMaleNumbers', 'missingFemaleNumbers', 'missingChildrenNumbers',
                    'users', 'missingEnergySystems', 'acHouseholds', 'internetUsers',
                    'internetDataApi', 'missingCommunityDonors', 'communitiesAC',
                    'newCommunityFbs', 'newCommunityMgExtension', 'newEnergyHolders',
                    'missingCommunityRepresentatives', 'communityWaterService', 
                    'communityWaterServiceYear', 'communityInternetService', 'waterIncidents',
                    'communityInternetServiceYear', 'missingSchoolDetails', 'fbsIncidents',
                    'inProgressHouseholdsAcCommunity', 'newEnergyUsers', 'mgIncidents',
                    'networkIncidents', 'internetHolderIncidents', 'notStartedACSurveyCommunities',
                    'notStartedACInstallationCommunities', 'miscRequested', 'queryCompounds',
                    'notStartedACInstallationCompounds', 'totalNotStartedAC',
                    'communitiesGridMissing', 'compoundsGridMissing', 
                    'communitiesNeedToCompleteElectricity', 'communitiesElecticityRoomMissing',
                    'compoundsElecticityRoomMissing', 'communitiesFbsNotDCInstallations',
                    'communitiesMgSmgNotDCInstallations',
                    'electricityNewMaintenances', 'electricityInProgressMaintenances',
                    'refrigeratorNewMaintenances', 'refrigeratorInProgressMaintenances',
                    'waterNewMaintenances', 'waterInProgressMaintenances', 'missingUserEnergDonors',
                    'missingEnergyPublicDonors', 'missingUserWaterDonors',
                    'missingPublicWaterDonors', 'missingUserInternetDonors', 
                    'missingPublicInternetDonors', 'holdersFbsNotDCInstallations',
                    'holdersMgSmgNotDCInstallations', 'notYetSafteyCheckedFbs', 
                    'notYetConnectedGround', 'notYetSafteyCheckedMg', 
                    'notYetCompletedSafteyCheckedMg', 'notYetSafteyCompletedFbs'),
                    ['groupedActionItems' => $groupedActionItems]
                );
            } else {

                return view('actions.users.index', compact('actionStatuses', 'actionPriorities',
                    'youngHolders', 'internetManager',
                    'communitiesNotInSystems', 'missingPhoneNumbers', 'missingAdultNumbers',
                    'missingMaleNumbers', 'missingFemaleNumbers', 'missingChildrenNumbers',
                    'users', 'missingEnergySystems', 'acHouseholds', 'internetUsers',
                    'internetDataApi', 'missingCommunityDonors', 'communitiesAC',
                    'newCommunityFbs', 'newCommunityMgExtension', 'newEnergyHolders',
                    'missingCommunityRepresentatives', 'communityWaterService', 
                    'communityWaterServiceYear', 'communityInternetService', 'waterIncidents',
                    'communityInternetServiceYear', 'missingSchoolDetails', 'fbsIncidents',
                    'inProgressHouseholdsAcCommunity', 'newEnergyUsers', 'mgIncidents',
                    'networkIncidents', 'internetHolderIncidents', 'notStartedACSurveyCommunities',
                    'notStartedACInstallationCommunities', 'miscRequested', 'queryCompounds',
                    'notStartedACInstallationCompounds', 'totalNotStartedAC',
                    'communitiesGridMissing', 'compoundsGridMissing', 
                    'communitiesNeedToCompleteElectricity', 'communitiesElecticityRoomMissing',
                    'compoundsElecticityRoomMissing', 'communitiesFbsNotDCInstallations',
                    'communitiesMgSmgNotDCInstallations',
                    'electricityNewMaintenances', 'electricityInProgressMaintenances',
                    'refrigeratorNewMaintenances', 'refrigeratorInProgressMaintenances',
                    'waterNewMaintenances', 'waterInProgressMaintenances', 'missingUserEnergDonors',
                    'missingEnergyPublicDonors', 'missingUserWaterDonors',
                    'missingPublicWaterDonors', 'missingUserInternetDonors', 
                    'missingPublicInternetDonors', 'holdersFbsNotDCInstallations',
                    'holdersMgSmgNotDCInstallations', 'notYetSafteyCheckedFbs', 
                    'notYetConnectedGround', 'notYetSafteyCheckedMg', 
                    'notYetCompletedSafteyCheckedMg', 'notYetSafteyCompletedFbs'),
                    ['groupedActionItems' => $groupedActionItems]
                );
            }

        } else {

            return view('errors.not-found');
        }    
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { 
        $actionItem = new ActionItem();
        $actionItem->task = $request->task;
        $actionItem->action_status_id = $request->action_status_id;
        $actionItem->action_priority_id = $request->action_priority_id;
        $actionItem->date = $request->date;
        $actionItem->due_date = $request->due_date;
        $actionItem->notes = $request->notes;
        $actionItem->user_id = Auth::guard('user')->user()->id;
        $actionItem->save();

        return redirect()->back()->with('message', 'New Action Item Added Successfully!');
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
            ->leftJoin('household_statuses', 'households.household_status_id', 
                'household_statuses.id')
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
                'households.english_name', 'household_statuses.status',
                'energy_system_types.name')
            ->get();
            
        return response()->json([
            'community' => $community,
            'html' => $missingEnergyUserDonors
        ]);
    }

    /**
     * 
     * @return \Illuminate\Support\Collection
     */
    public function updateActionStatus(Request $request)
    {
        $actionItemId = $request->input('actionItemId');
        $newStatusId = $request->input('newStatusId');

        $actionItem = ActionItem::findOrFail($actionItemId);
        $actionItem->action_status_id = $newStatusId;
        $actionItem->save(); 

        return response()->json(
            [
                'success' => true,
                'status' => $request->input('newStatusId')
            ]
        );
    }

    /**
     * 
     * @return \Illuminate\Support\Collection
     */
    public function updateActionNote(Request $request)
    {
        $actionItemId = $request->input('actionItemId');
        $newNotes = $request->input('newNotes');

        $actionItem = ActionItem::findOrFail($actionItemId);
        $actionItem->notes = $newNotes;
        $actionItem->save();

        return response()->json(['success' => true]);
    }
}