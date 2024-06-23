<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\Household;
use App\Models\AllEnergyMeter;
use App\Models\InternetUser;
use App\Models\PublicStructure;
use Auth;
use DB;
use Route;

class AllEnergyHolderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {	
        $queryHolders = DB::table('internet_users')
            ->join('communities', 'internet_users.community_id', 'communities.id')
            ->leftJoin('public_structures', 'internet_users.public_structure_id', 'public_structures.id')
            ->leftJoin('households', 'internet_users.household_id', 'households.id')
            ->where('internet_users.is_archived', 0)
            ->join('all_energy_meters', 'all_energy_meters.community_id', 'internet_users.community_id')
            ->select(
                'internet_users.id as id',
                DB::raw('IFNULL(households.id, public_structures.id) as holder_id'),
                DB::raw('IFNULL(households.english_name, public_structures.english_name) as contract_holder'),
                DB::raw('IFNULL(households.arabic_name, public_structures.arabic_name) as contract_holder_arabic'),
                'communities.english_name as community_name',
                DB::raw('IFNULL(households.phone_number, public_structures.phone_number) as contract_phone_number'),
            )
            ->distinct()
            ->get();

        foreach($queryHolders as $q) {

            $internetUser = InternetUser::findOrFail($q->id);
            $meterNumbers = [];
            $meterCases = [];

            if($internetUser->household_id) {
                $allEnergyMeters = DB::table('all_energy_meters')
                    ->where('all_energy_meters.is_archived', 0)
                    ->where('all_energy_meters.household_id', $internetUser->household_id)
                    ->join('meter_cases', 'all_energy_meters.meter_case_id', 'meter_cases.id')
                    ->select(
                        'all_energy_meters.meter_number',
                        'meter_cases.meter_case_name_english'
                    )
                    ->get();
        
                foreach ($allEnergyMeters as $meter) {

                    $meterNumbers[] = $meter->meter_number;
                    $meterCases[] = $meter->meter_case_name_english;
                }
            } 
        
            if($internetUser->public_structure_id) {

                $allEnergyMeters = DB::table('all_energy_meters')
                    ->where('all_energy_meters.is_archived', 0)
                    ->where('all_energy_meters.public_structure_id', $internetUser->public_structure_id)
                    ->join('meter_cases', 'all_energy_meters.meter_case_id', 'meter_cases.id')
                    ->select(
                        'all_energy_meters.meter_number',
                        'meter_cases.meter_case_name_english'
                    )
                    ->get();
        
                foreach ($allEnergyMeters as $meter) {

                    $meterNumbers[] = $meter->meter_number;
                    $meterCases[] = $meter->meter_case_name_english;
                }
            }
        
            // Assign meter numbers to the query result
            $q->meter_numbers = $meterNumbers;
            $q->meter_cases = $meterCases;
        }

        $query = DB::table('all_energy_meters')
            ->join('communities', 'all_energy_meters.community_id', 'communities.id')
            ->leftJoin('households', 'all_energy_meters.household_id', 'households.id')
            ->leftJoin('public_structures', 'all_energy_meters.public_structure_id', 'public_structures.id')
            ->join('meter_cases', 'all_energy_meters.meter_case_id', 'meter_cases.id')
            ->where('all_energy_meters.is_archived', 0)
            ->select(
                DB::raw('IFNULL(households.id, public_structures.id) as energy_id'),
                DB::raw('IFNULL(households.english_name, public_structures.english_name) as energy_holder'),
                DB::raw('IFNULL(households.arabic_name, public_structures.arabic_name) as energy_holder_arabic'),
                'communities.english_name as community_name', 'communities.english_name as community_name_arabic',
                DB::raw('IFNULL(households.phone_number, public_structures.phone_number) as phone_number'),
                'all_energy_meters.meter_number', 'all_energy_meters.is_main',
                'meter_cases.meter_case_name_english as meter_case',
                DB::raw("CASE WHEN all_energy_meters.public_structure_id != 0 THEN 'yes' ELSE 'no' END as is_public_entity")
            )
            ->distinct()
            ->get();
            
        $dataHouseholds =  DB::table('households')
            ->join('communities', 'households.community_id', 'communities.id')
            ->where('households.is_archived', 0)
            ->leftJoin('all_energy_meters', function ($join) {
                $join->on('communities.id', 'all_energy_meters.community_id')
                    ->where('all_energy_meters.is_archived', 0);
            })
            ->whereNull('all_energy_meters.community_id')
            ->select(
                'households.id as id',
                'households.english_name as household_english',
                'households.arabic_name as household_arabic',
                'communities.english_name as community_english',
                'communities.arabic_name as community_arabic', 
                'households.phone_number'
            )
            ->get();

        $dataPublics = DB::table('public_structures')
            ->join('communities', 'public_structures.community_id', 'communities.id')
            ->where('public_structures.is_archived', 0)
            ->where('public_structures.comet_meter', 0)
            ->leftJoin('all_energy_meters', function ($join) {
                $join->on('communities.id', 'all_energy_meters.community_id')
                    ->where('all_energy_meters.is_archived', 0);
            })
            ->whereNull('all_energy_meters.community_id')
            ->select(
                'public_structures.id',
                'public_structures.english_name as public_english',
                'public_structures.arabic_name as public_arabic',
                'communities.english_name as community_english',
                'communities.arabic_name as community_arabic', 
                'public_structures.phone_number'
            )
            ->get();

        $dataShared = DB::table('household_meters')
            ->join('households as shared', 'household_meters.household_id', 'shared.id')
            ->join('communities', 'shared.community_id', 'communities.id')
            ->join('all_energy_meters', 'household_meters.energy_user_id', 'all_energy_meters.id')
            ->join('households as main_users', 'all_energy_meters.household_id', 'main_users.id')
            ->where('household_meters.is_archived', 0)
            ->select(
                'shared.english_name as shared_english',
                'shared.arabic_name as shared_arabic',
                'communities.english_name as community_english',
                'communities.arabic_name as community_arabic',
                'main_users.english_name as main_english',
                'main_users.arabic_name as main_arabic',
                'all_energy_meters.meter_number'
            )
            ->get();

        return response()->json([
            'contract_holders' => $queryHolders,
            'main_users' => $query,
            'shared_users' => $dataShared,
            'households' => $dataHouseholds,
            'publics' => $dataPublics
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }
}