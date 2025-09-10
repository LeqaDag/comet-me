<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\AllEnergyMeter;
use App\Models\PublicStructure;
use App\Models\User;
use App\Models\EnergyTurbineCommunity;
use App\Models\EnergyGeneratorCommunity;
use App\Models\EnergySystem;
use App\Models\WaterSystem;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Helpers\SequenceHelper;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Cache;
use Auth;
use DB;
use Route;

class AllEnergyArchiveMeterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $incrementalNumber = 1;
        $outOfCometPublic = 10000;
        $turbineIndex = 70000;
        $generatorIndex = 40000;
        $energySystemIndex = 50000;
        $waterSystemIndex = 60000;
        Cache::forget('energy_turbine_communities');
        Cache::forget('energy_generator_communities');
        Cache::forget('energy_systems');
 

        // Caching the households query
        $households =  DB::table('households')
            ->join('communities', 'households.community_id', 'communities.id')
            ->join('community_statuses', 'communities.community_status_id', 'community_statuses.id')
            ->leftJoin('household_statuses', 'households.household_status_id', 'household_statuses.id')
            ->join('regions', 'communities.region_id', 'regions.id')
            ->join('sub_regions', 'communities.sub_region_id', 'sub_regions.id')
            ->leftJoin('all_energy_meters', 'all_energy_meters.household_id', 'households.id')
            ->leftJoin('energy_systems', 'all_energy_meters.energy_system_id', 'energy_systems.id')
            ->leftJoin('energy_system_types', 'all_energy_meters.energy_system_type_id', 'energy_system_types.id')
            ->leftJoin('meter_cases', 'all_energy_meters.meter_case_id', 'meter_cases.id')
            ->leftJoin('internet_users as internet_household', 'households.id', 'internet_household.household_id')
            ->leftJoin('all_water_holders', 'all_water_holders.household_id', 'households.id')
            ->leftJoin('household_meters', 'household_meters.household_id', 'households.id')
            ->leftJoin('all_energy_meters as main_energy', 'main_energy.id', 'household_meters.energy_user_id')
            ->leftJoin('households as main_users', 'main_energy.household_id', 'main_users.id')
            ->leftJoin('young_holders', 'young_holders.household_id', 'households.id')
            ->leftJoin('compound_households', 'households.id', 'compound_households.household_id')
            ->leftJoin('compounds', 'compound_households.compound_id', 'compounds.id')
            // ->leftJoin('refrigerator_holders', 'households.id', 'refrigerator_holders.household_id')                       
            // ->leftJoin('refrigerator_holder_receive_numbers', 'refrigerator_holders.id', 
            //     'refrigerator_holder_receive_numbers.refrigerator_holder_id')
            ->where('households.is_archived', 0)
            ->select(
                'communities.english_name as english_community_name',
                'communities.arabic_name as arabic_community_name',
                'community_statuses.name as community_status',
                'regions.english_name as region_name',
                'sub_regions.english_name as sub_region_name',
                'compounds.english_name as english_compound_name',
                'compounds.arabic_name as arabic_compound_name',
                'households.english_name as holder_name_english',
                'households.arabic_name as holder_name_arabic',
                'households.comet_id as comet_id',
                'households.fake_meter_number',
                'households.phone_number', 'household_statuses.status as energy_system_status',
                DB::raw('IFNULL(all_energy_meters.meter_number,
                    IFNULL(all_energy_meters.fake_meter_number, young_holders.fake_meter_number)) as meter_number'),
                'energy_system_types.name as energy_type',
                'meter_cases.meter_case_name_english as meter_case',
                'all_energy_meters.is_main', 'all_energy_meters.is_archived',
                DB::raw("CASE WHEN all_water_holders.household_id IS NOT NULL THEN 'Served'
                    ELSE 'Not Served' END AS water_system_status"),
                'households.internet_system_status',
                DB::raw('IFNULL(internet_household.is_ppp, 0) as is_ppp'),
                DB::raw('IFNULL(internet_household.is_hotspot, 0) as is_hotspot'),
                'main_users.english_name as main_holder',
                'all_energy_meters.daily_limit',
                'households.is_surveyed', 'households.last_surveyed_date',
                // DB::raw("CASE WHEN refrigerator_holder_receive_numbers.receive_number IS NOT NULL THEN 'Yes'
                //     ELSE 'No' END AS refrigerator_status"),
            )
            ->distinct()
            ->groupBy('households.id')
            ->get();

        $publics = DB::table('public_structures')
            ->join('communities', 'public_structures.community_id', 'communities.id')
            ->join('regions', 'communities.region_id', 'regions.id')
            ->join('sub_regions', 'communities.sub_region_id', 'sub_regions.id')
            ->join('community_statuses', 'communities.community_status_id', 'community_statuses.id')
            ->leftJoin('public_structure_statuses', 'public_structures.public_structure_status_id', 'public_structure_statuses.id')
            ->leftJoin('all_energy_meters', 'all_energy_meters.public_structure_id', 'public_structures.id')
            ->leftJoin('energy_systems', 'all_energy_meters.energy_system_id', 'energy_systems.id')
            ->leftJoin('energy_system_types', 'all_energy_meters.energy_system_type_id', 'energy_system_types.id')
            ->leftJoin('meter_cases', 'all_energy_meters.meter_case_id', 'meter_cases.id')
            ->leftJoin('internet_users as internet_public', 'public_structures.id', 'internet_public.public_structure_id')
            ->leftJoin('all_water_holders', 'all_water_holders.public_structure_id', 'public_structures.id')
            ->leftJoin('household_meters', 'household_meters.public_structure_id', 'public_structures.id')
            ->leftJoin('all_energy_meters as main_energy', 'main_energy.id', 'household_meters.energy_user_id')
            ->leftJoin('households as main_users', 'main_energy.household_id', 'main_users.id')
            ->leftJoin('public_structures as main_public', 'main_energy.public_structure_id', 'main_public.id')
            // ->leftJoin('refrigerator_holders', 'public_structures.id', 'refrigerator_holders.public_structure_id')                       
            // ->leftJoin('refrigerator_holder_receive_numbers', 'refrigerator_holders.id', 
            //     'refrigerator_holder_receive_numbers.refrigerator_holder_id')
            ->where('public_structures.is_archived', 0)
            ->select(
                'communities.english_name as english_community_name',
                'communities.arabic_name as arabic_community_name',
                'community_statuses.name as community_status',
                'regions.english_name as region_name',
                'sub_regions.english_name as sub_region_name',
                DB::raw('false as english_compound_name'),
                DB::raw('false as arabic_compound_name'),
                'public_structures.english_name as holder_name_english',
                'public_structures.arabic_name as holder_name_arabic',
                'public_structures.comet_id as comet_id',
                'public_structures.phone_number',
                'public_structure_statuses.status as energy_system_status',
               DB::raw('COALESCE(all_energy_meters.meter_number, all_energy_meters.fake_meter_number, 
               public_structures.fake_meter_number) as meter_number'),

                'energy_system_types.name as energy_type',
                'meter_cases.meter_case_name_english as meter_case',
                'all_energy_meters.is_main', 'all_energy_meters.is_archived',
                DB::raw("CASE WHEN all_water_holders.public_structure_id IS NOT NULL THEN 'Served'
                    ELSE 'Not Served' END AS water_system_status"),
                DB::raw("CASE WHEN internet_public.public_structure_id IS NOT NULL THEN 'Served'
                    ELSE 'Not Served' END AS internet_system_status"),
                DB::raw('IFNULL(internet_public.is_ppp, 0) as is_ppp'),
                DB::raw('IFNULL(internet_public.is_hotspot, 0) as is_hotspot'),
                DB::raw('IFNULL(main_users.english_name, main_public.english_name)
                    as main_holder'),
                'all_energy_meters.daily_limit',
                DB::raw('false as is_surveyed'), DB::raw('false as last_surveyed_date'),
                // DB::raw("CASE WHEN refrigerator_holder_receive_numbers.receive_number IS NOT NULL THEN 'Yes'
                //     ELSE 'No' END AS refrigerator_status"),
            )
            ->distinct()
            ->get();


        $turbines = DB::table('energy_turbine_communities')
            ->join('communities', 'energy_turbine_communities.community_id', 'communities.id')
            ->join('community_statuses', 'communities.community_status_id', 'community_statuses.id')
            ->join('regions', 'communities.region_id', 'regions.id')
            ->join('sub_regions', 'communities.sub_region_id', 'sub_regions.id')
            ->select(
                'communities.english_name as english_community_name',
                'communities.arabic_name as arabic_community_name',
                'community_statuses.name as community_status',
                'regions.english_name as region_name',
                'sub_regions.english_name as sub_region_name',
                DB::raw('false as english_compound_name'),
                DB::raw('false as arabic_compound_name'),
                'energy_turbine_communities.comet_id as comet_id',
                'energy_turbine_communities.name as holder_name_english',
                'energy_turbine_communities.name as holder_name_arabic',
                DB::raw('false as phone_number'),
                'energy_turbine_communities.fake_meter_number as meter_number',
                DB::raw('false as energy_type'), DB::raw('false as meter_case'),
                DB::raw('false as is_main'), DB::raw('false as is_archived'),
                DB::raw('false as water_system_status'), DB::raw('false as internet_system_status'),
                DB::raw('false as is_ppp'),DB::raw('false as is_hotspot'), DB::raw('false as main_holder'),
                DB::raw('false as daily_limit'), 
                DB::raw('false as is_surveyed'), DB::raw('false as last_surveyed_date'),
                // DB::raw('false as refrigerator_status'),
            )
            ->get();

        $energySystems = DB::table('energy_systems')
            ->leftJoin('communities', 'energy_systems.community_id', 'communities.id')
            ->join('community_statuses', 'communities.community_status_id', 'community_statuses.id')
            ->join('regions', 'communities.region_id', 'regions.id')
            ->join('sub_regions', 'communities.sub_region_id', 'sub_regions.id')
            ->where('energy_systems.is_archived', 0)
            ->select(
                'communities.english_name as english_community_name',
                'communities.arabic_name as arabic_community_name',
                'community_statuses.name as community_status',
                'regions.english_name as region_name',
                'sub_regions.english_name as sub_region_name',
                DB::raw('false as english_compound_name'),
                DB::raw('false as arabic_compound_name'),
                'energy_systems.comet_id as comet_id',
                'energy_systems.name as holder_name_english',
                'energy_systems.name as holder_name_arabic',
                DB::raw('false as phone_number'),
                'energy_systems.fake_meter_number as meter_number',
                DB::raw('false as energy_type'), DB::raw('false as meter_case'),
                DB::raw('false as is_main'), DB::raw('false as is_archived'),
                DB::raw('false as water_system_status'), DB::raw('false as internet_system_status'),
                DB::raw('false as is_ppp'),DB::raw('false as is_hotspot'), DB::raw('false as main_holder'),
                DB::raw('false as daily_limit'),
                DB::raw('false as is_surveyed'), DB::raw('false as last_surveyed_date'),
                // DB::raw('false as refrigerator_status'),
            )
            ->get();

        $waterSystems = DB::table('water_systems')
            ->leftJoin('water_system_types', 'water_systems.water_system_type_id', 'water_system_types.id')
            ->leftJoin('communities', 'water_systems.community_id', 'communities.id')
            ->leftJoin('community_statuses', 'communities.community_status_id', 'community_statuses.id')
            ->leftJoin('regions', 'communities.region_id', 'regions.id')
            ->leftJoin('sub_regions', 'communities.sub_region_id', 'sub_regions.id')
            ->select(
                DB::raw("'water_system' as source_type"),
                'communities.english_name as english_community_name',
                'communities.arabic_name as arabic_community_name',
                'community_statuses.name as community_status',
                'regions.english_name as region_name',
                'sub_regions.english_name as sub_region_name',
                DB::raw('false as english_compound_name'),
                DB::raw('false as arabic_compound_name'),
                'water_systems.comet_id as comet_id',
                'water_systems.name as holder_name_english',
                'water_systems.name as holder_name_arabic',
                DB::raw('false as phone_number'),
                'water_systems.fake_meter_number as meter_number',
                DB::raw('false as energy_type'), DB::raw('false as meter_case'),
                DB::raw('false as is_main'), DB::raw('false as is_archived'),
                DB::raw('false as water_system_status'), DB::raw('false as internet_system_status'),
                DB::raw('false as is_ppp'),DB::raw('false as is_hotspot'), DB::raw('false as main_holder'),
                DB::raw('false as daily_limit'),
                DB::raw('false as is_surveyed'), DB::raw('false as last_surveyed_date'),
                // DB::raw('false as refrigerator_status'),

            )
            ->get();
        
        $internetSystems = DB::table('internet_systems')
            ->leftJoin('internet_system_communities', 'internet_systems.id', 'internet_system_communities.internet_system_id')
            ->leftJoin('communities', 'internet_system_communities.community_id', 'communities.id')
            ->leftJoin('community_statuses', 'communities.community_status_id', 'community_statuses.id')
            ->leftJoin('regions', 'communities.region_id', 'regions.id')
            ->leftJoin('sub_regions', 'communities.sub_region_id', 'sub_regions.id')
            ->select(
                'communities.english_name as english_community_name',
                'communities.arabic_name as arabic_community_name',
                'community_statuses.name as community_status',
                'regions.english_name as region_name',
                'sub_regions.english_name as sub_region_name',
                DB::raw('false as english_compound_name'),
                DB::raw('false as arabic_compound_name'),
                'internet_systems.comet_id as comet_id',
                'internet_systems.system_name as holder_name_english',
                'internet_systems.system_name as holder_name_arabic',
                DB::raw('false as phone_number'),
                'internet_systems.fake_meter_number as meter_number',
                DB::raw('false as energy_type'), DB::raw('false as meter_case'),
                DB::raw('false as is_main'), DB::raw('false as is_archived'),
                DB::raw('false as internet_system_status'), DB::raw('false as internet_system_status'),
                DB::raw('false as is_ppp'),DB::raw('false as is_hotspot'), DB::raw('false as main_holder'),
                DB::raw('false as daily_limit'),
                DB::raw('false as is_surveyed'), DB::raw('false as last_surveyed_date'),
                // DB::raw('false as refrigerator_status'),
            )
            ->get();

        $generators = DB::table('energy_generator_communities')
            ->join('communities', 'energy_generator_communities.community_id', 'communities.id')
            ->leftJoin('community_statuses', 'communities.community_status_id', 'community_statuses.id')
            ->join('regions', 'communities.region_id', 'regions.id')
            ->join('sub_regions', 'communities.sub_region_id', 'sub_regions.id')
            ->select(
                'communities.english_name as english_community_name',
                'communities.arabic_name as arabic_community_name',
                'community_statuses.name as community_status',
                'regions.english_name as region_name',
                'sub_regions.english_name as sub_region_name',
                DB::raw('false as english_compound_name'),
                DB::raw('false as arabic_compound_name'),
                'energy_generator_communities.comet_id as comet_id',
                'energy_generator_communities.name as holder_name_english',
                'energy_generator_communities.name as holder_name_arabic',
                DB::raw('false as phone_number'),
                'energy_generator_communities.fake_meter_number as meter_number',
                DB::raw('false as energy_type'), DB::raw('false as meter_case'),
                DB::raw('false as is_main'), DB::raw('false as is_archived'),
                DB::raw('false as water_system_status'), DB::raw('false as internet_system_status'),
                DB::raw('false as is_ppp'),DB::raw('false as is_hotspot'), DB::raw('false as main_holder'),
                DB::raw('false as daily_limit'),
                DB::raw('false as is_surveyed'), DB::raw('false as last_surveyed_date'),
                // DB::raw('false as refrigerator_status'),
            )
            ->get();

        $data = collect([$households, $publics, $turbines, $generators, $energySystems, 
            $waterSystems, $internetSystems])->flatten();


        return response()->json($data, 200, [], JSON_UNESCAPED_UNICODE);
    }
}
