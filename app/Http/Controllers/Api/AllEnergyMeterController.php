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

class AllEnergyMeterController extends Controller
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
        // This code for adding the fake meter numbers for the existing records for the turbines.
        // $allTurbines = EnergyTurbineCommunity::all();

        // foreach($allTurbines as $allTurbine) {

        //     $fakeMeterNumber = 'ET'. $turbineIndex;

        //     $exist = EnergyTurbineCommunity::where('fake_meter_number', $fakeMeterNumber)->first();
        //     if($exist) {
        //     } else {

        //         $allTurbine->fake_meter_number =$fakeMeterNumber;
        //         $allTurbine->save();
        //     }

        //     $turbineIndex++;
        // }

        // This code for adding the fake meter numbers for the existing records for the generators.
        // $allGenerators = EnergyGeneratorCommunity::all();

        // foreach($allGenerators as $allGenerator) {

        //     $fakeMeterNumber = 'EG'.  $generatorIndex;
        //     $exist = EnergyGeneratorCommunity::where('fake_meter_number', $fakeMeterNumber)->first();
        //     if($exist) {
        //     } else {

        //         $allGenerator->fake_meter_number =$fakeMeterNumber;
        //         $allGenerator->save();
        //     }

        //     $generatorIndex++;
        // }

        // This code for adding the fake meter numbers for the existing records for the mg energy system.
        // $energySystems = EnergySystem::all();

        // foreach($energySystems as $energySystem) {

        //     $fakeMeterNumber = 'ES'.  $energySystemIndex;
        //     $exist = EnergySystem::where('fake_meter_number', $fakeMeterNumber)->first();
        //     if($exist) {
        //     } else {

        //         $energySystem->fake_meter_number =$fakeMeterNumber;
        //         $energySystem->save();
        //     }

        //     $energySystemIndex++;
        // }

        // This code for adding the fake meter numbers for the existing records for the mg water system.
        // $waterSystems = WaterSystem::all();

        // foreach($waterSystems as $waterSystem) {

        //     $fakeMeterNumber = 'WS'.  $waterSystemIndex;
        //     $exist = WaterSystem::where('fake_meter_number', $fakeMeterNumber)->first();
        //     if($exist) {
        //     } else {

        //         $waterSystem->fake_meter_number =$fakeMeterNumber;
        //         $waterSystem->save();
        //     }

        //     $waterSystemIndex++;
        // }

        // // This code for adding the fake meter numbers for the existing records for the public structures.
        // $outOfCometPublicStructures = PublicStructure::where("is_archived", 0)
        //     ->where("out_of_comet", 1)
        //     ->get();

        // foreach($outOfCometPublicStructures as $outOfCometPublicStructure) {
        //     $fakeMeterNumber = SequenceHelper::generateSequencePublic($outOfCometPublic);
        //     $exist = PublicStructure::where("is_archived", 0)
        //         ->where("out_of_comet", 1)
        //         ->where('fake_meter_number', $fakeMeterNumber)
        //         ->first();
        //     if($exist) {
        //     } else {
        //         $outOfCometPublicStructure->fake_meter_number = $fakeMeterNumber;
        //         $outOfCometPublicStructure->save();
        //     }
        //     $outOfCometPublic++;
        // }

        // This code for adding the fake meter numbers for the existing records and it applies only once.

        // foreach ($sharedUsers as $sharedUser) {

        //     $fakeMeterNumber = SequenceHelper::generateSequence($sharedUser->meter_number, $incrementalNumber);

        //     $exist = AllEnergyMeter::where('fake_meter_number', $fakeMeterNumber)->first();

        //     if($exist) {

        //         $incrementalNumber++;
        //     } else {

        //         $allEnergyMeter = null;
        //         if($sharedUser->shared_household_id) {

        //             $allEnergyMeter = AllEnergyMeter::where("is_archived", 0)
        //                 ->whereNull("meter_number")
        //                 ->where("household_id", $sharedUser->shared_household_id)
        //                 ->first();
        //         } else if($sharedUser->shared_public_id) {

        //             $allEnergyMeter = AllEnergyMeter::where("is_archived", 0)
        //                 ->whereNull("meter_number")
        //                 ->where("public_structure_id", $sharedUser->shared_public_id)
        //                 ->first();
        //         }
        //         if($allEnergyMeter) {

        //             $allEnergyMeter->fake_meter_number = $fakeMeterNumber;
        //             $allEnergyMeter->save();

        //             $incrementalNumber++;
        //         }
        //     }
        // }


        // Caching the households query
        $households =  DB::table('households')
                ->join('communities', 'households.community_id', 'communities.id')
                ->leftJoin('household_statuses', 'households.household_status_id', 'household_statuses.id')
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
                ->where('households.is_archived', 0)
                ->select(
                    'communities.english_name as english_community_name',
                    'communities.arabic_name as arabic_community_name',
                    'compounds.english_name as english_compound_name',
                    'compounds.arabic_name as arabic_compound_name',
                    'households.english_name as holder_name_english',
                    'households.arabic_name as holder_name_arabic',
                    'households.comet_id',
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
                    'main_users.english_name as main_holder'
                )
                ->distinct()
                ->get();

        $publics = DB::table('public_structures')
                ->join('communities', 'public_structures.community_id', 'communities.id')
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
                ->where('public_structures.is_archived', 0)
                ->select(
                    'communities.english_name as english_community_name',
                    'communities.arabic_name as arabic_community_name',
                    DB::raw('false as english_compound_name'),
                    DB::raw('false as arabic_compound_name'),
                    'public_structures.english_name as holder_name_english',
                    'public_structures.arabic_name as holder_name_arabic',
                    'public_structures.comet_id',
                    'public_structures.phone_number',
                    'public_structure_statuses.status as energy_system_status',
                    DB::raw('IFNULL(all_energy_meters.meter_number, public_structures.fake_meter_number)
                        as meter_number'),
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
                )
                ->distinct()
                ->get();


        $turbines = Cache::remember('energy_turbine_communities', 3600, function () {
            return DB::table('energy_turbine_communities')
                ->join('communities', 'energy_turbine_communities.community_id', 'communities.id')
                ->select(
                    'communities.english_name as english_community_name',
                    'communities.arabic_name as arabic_community_name',
                    DB::raw('false as english_compound_name'),
                    DB::raw('false as arabic_compound_name'),
                    'energy_turbine_communities.comet_id',
                    'energy_turbine_communities.name as holder_name_english',
                    'energy_turbine_communities.name as holder_name_arabic',
                    DB::raw('false as phone_number'),
                    'energy_turbine_communities.fake_meter_number as meter_number',
                    DB::raw('false as energy_type'), DB::raw('false as meter_case'),
                    DB::raw('false as is_main'), DB::raw('false as is_archived'),
                    DB::raw('false as water_system_status'), DB::raw('false as internet_system_status'),
                    DB::raw('false as is_ppp'),DB::raw('false as is_hotspot'), DB::raw('false as main_holder')

                )
                ->get();
        });

        $energySystems = Cache::remember('energy_systems', 3600, function () {
            return DB::table('energy_systems')
                ->leftJoin('communities', 'energy_systems.community_id', 'communities.id')
                ->where('energy_systems.is_archived', 0)
                ->select(
                    'communities.english_name as english_community_name',
                    'communities.arabic_name as arabic_community_name',
                    DB::raw('false as english_compound_name'),
                    DB::raw('false as arabic_compound_name'),
                    'energy_systems.comet_id',
                    'energy_systems.name as holder_name_english',
                    'energy_systems.name as holder_name_arabic',
                    DB::raw('false as phone_number'),
                    'energy_systems.fake_meter_number as meter_number',
                    DB::raw('false as energy_type'), DB::raw('false as meter_case'),
                    DB::raw('false as is_main'), DB::raw('false as is_archived'),
                    DB::raw('false as water_system_status'), DB::raw('false as internet_system_status'),
                    DB::raw('false as is_ppp'),DB::raw('false as is_hotspot'), DB::raw('false as main_holder')
                )
                ->get();
        });

        $waterSystems = Cache::remember('water_systems', 3600, function () {
            return DB::table('water_systems')
                ->leftJoin('water_system_types', 'water_systems.water_system_type_id', 'water_system_types.id')
                ->leftJoin('communities', 'water_systems.community_id', 'communities.id')
                ->select(
                    'communities.english_name as english_community_name',
                    'communities.arabic_name as arabic_community_name',
                    DB::raw('false as english_compound_name'),
                    DB::raw('false as arabic_compound_name'),
                    'water_systems.comet_id',
                    'water_systems.name as holder_name_english',
                    'water_systems.name as holder_name_arabic',
                    DB::raw('false as phone_number'),
                    'water_systems.fake_meter_number as meter_number',
                    DB::raw('false as energy_type'), DB::raw('false as meter_case'),
                    DB::raw('false as is_main'), DB::raw('false as is_archived'),
                    DB::raw('false as water_system_status'), DB::raw('false as internet_system_status'),
                    DB::raw('false as is_ppp'),DB::raw('false as is_hotspot'), DB::raw('false as main_holder')

                )
                ->get();
        });

        $generators = Cache::remember('energy_generator_communities', 3600, function () {
            return DB::table('energy_generator_communities')
            ->join('communities', 'energy_generator_communities.community_id', 'communities.id')
            ->select(
                'communities.english_name as english_community_name',
                'communities.arabic_name as arabic_community_name',
                DB::raw('false as english_compound_name'),
                DB::raw('false as arabic_compound_name'),
                'energy_generator_communities.comet_id',
                'energy_generator_communities.name as holder_name_english',
                'energy_generator_communities.name as holder_name_arabic',
                DB::raw('false as phone_number'),
                'energy_generator_communities.fake_meter_number as meter_number',
                DB::raw('false as energy_type'), DB::raw('false as meter_case'),
                DB::raw('false as is_main'), DB::raw('false as is_archived'),
                DB::raw('false as water_system_status'), DB::raw('false as internet_system_status'),
                DB::raw('false as is_ppp'),DB::raw('false as is_hotspot'), DB::raw('false as main_holder'),

            )
            ->get();
        });

        $data = collect([$households, $publics, $turbines, $generators, $energySystems, $waterSystems])->flatten();


        return response()->json($data, 200, [], JSON_UNESCAPED_UNICODE);
    }
}
