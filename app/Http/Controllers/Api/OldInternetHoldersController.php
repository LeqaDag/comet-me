<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\InternetUser;
use App\Models\AllEnergyMeter;
use Auth;
use DB;
use Route;

class OldInternetHoldersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {	

        $query = DB::table('internet_users')
            ->join('communities', 'internet_users.community_id', 'communities.id')
            ->leftJoin('public_structures', 'internet_users.public_structure_id', 'public_structures.id')
            ->leftJoin('households', 'internet_users.household_id', 'households.id')
            ->where('internet_users.is_archived', 0)
            ->join('all_energy_meters', 'all_energy_meters.community_id', 'internet_users.community_id')
            ->select(
                'internet_users.id as id',
                DB::raw('IFNULL(households.english_name, public_structures.english_name) as contract_holder'),
                DB::raw('IFNULL(households.arabic_name, public_structures.arabic_name) as contract_holder_arabic'),
                'communities.english_name as community_name',
                DB::raw('IFNULL(households.phone_number, public_structures.phone_number) as contract_phone_number'),
            )
            ->distinct()
            ->get();

            //die( $query);
        foreach($query as $q) {

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
            
        return response()->json($query, 200, [], JSON_UNESCAPED_UNICODE);
    }
}