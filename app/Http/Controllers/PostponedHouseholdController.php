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
use App\Models\AllEnergyMeterDonor;
use App\Models\AllWaterHolder;
use App\Models\AllWaterHolderDonor;
use App\Models\CommunityRepresentative;
use App\Models\ElectricityMaintenanceCall;
use App\Models\FbsUserIncident;
use App\Models\GridUser;
use App\Models\H2oUser;
use App\Models\H2oMaintenanceCall;
use App\Models\InternetUser;
use App\Models\RefrigeratorHolder;
use App\Models\RefrigeratorMaintenanceCall;
use App\Models\Donor;
use App\Models\EnergySystem;
use App\Models\EnergySystemType;
use App\Models\PublicStructureCategory;
use App\Models\User;
use App\Models\Community;
use App\Models\CommunityHousehold;
use App\Models\Cistern;
use App\Models\Household;
use App\Models\HouseholdMeter;
use App\Models\EnergySystemCycle;
use App\Models\HouseholdStatus;
use App\Models\Region;
use App\Models\Structure;
use App\Models\SubRegion;
use App\Models\Profession;
use App\Models\MovedHousehold;
use App\Models\PostponedHousehold;
use App\Models\EnergyRequestSystem;
use Carbon\Carbon;
use DataTables;
use mikehaertl\wkhtmlto\Pdf;

class PostponedHouseholdController extends Controller
{
   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {	
        if (Auth::guard('user')->user() != null) {

            $communityFilter = $request->input('filter');
            $regionFilter = $request->input('second_filter');
            $postponedByFilter = $request->input('third_filter');

            if ($request->ajax()) {

                $status = "Postponed";
                $statusHousehold = HouseholdStatus::where('status', 'like', '%' . $status . '%')->first();

                $data = DB::table('households')
                    ->where('households.is_archived', 0)
                    ->where('households.internet_holder_young', 0)
                    ->where('households.household_status_id', $statusHousehold->id)
                    ->join('communities', 'households.community_id', 'communities.id')
                    ->join('regions', 'communities.region_id', 'regions.id')
                    ->leftJoin('postponed_households', 'postponed_households.household_id', 'households.id')
                    ->leftJoin('users', 'postponed_households.referred_by', 'users.id')
                    ->where('postponed_households.is_archived', 0);
                if ($communityFilter != null) {
                    $data->where('communities.id', $communityFilter);
                }

                if ($regionFilter != null) {
                    $data->where('regions.id', $regionFilter);
                }

                if ($postponedByFilter != null) {
                    $data->where('users.id', $postponedByFilter);
                }

                $data->select('households.english_name as english_name', 'households.arabic_name as arabic_name',
                    'households.id as id', 'households.created_at as created_at', 
                    'households.updated_at as updated_at',
                    'regions.english_name as region_name',
                    'communities.english_name as name',
                    'communities.arabic_name as aname',
                    'postponed_households.reason', 'users.name as referred_by')
                ->latest(); 

                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
    
                        $detailsButton = "<a type='button' class='detailsHouseholdButton' data-bs-toggle='modal' data-bs-target='#householdDetails' data-id='".$row->id."'><i class='fa-solid fa-eye text-primary'></i></a>";
                        $moveButton = "<a type='button' title='Start Working' class='movePostponedHousehold' data-id='".$row->id."'><i class='fa-solid fa-check text-success'></i></a>";

                        if(Auth::guard('user')->user()->user_type_id == 1 || 
                            Auth::guard('user')->user()->user_type_id == 2 || 
                            Auth::guard('user')->user()->user_type_id == 3 || 
                            Auth::guard('user')->user()->user_type_id == 4) 
                        {
                                
                            return $moveButton. " " .$detailsButton;
                        } else return $detailsButton; 
                    })
                   
                    ->filter(function ($instance) use ($request) {
                        if (!empty($request->get('search'))) {
                                $instance->where(function($w) use($request) {
                                    $search = $request->get('search');
                                    $w->orWhere('households.english_name', 'LIKE', "%$search%")
                                    ->orWhere('communities.english_name', 'LIKE', "%$search%")
                                    ->orWhere('communities.arabic_name', 'LIKE', "%$search%")
                                    ->orWhere('households.arabic_name', 'LIKE', "%$search%")
                                    ->orWhere('regions.english_name', 'LIKE', "%$search%")
                                    ->orWhere('regions.arabic_name', 'LIKE', "%$search%");
                            });
                        }
                    })
                    ->make(true);
            }
    
            $communities = Community::where('is_archived', 0)
                ->orderBy('english_name', 'ASC')
                ->get();
            $regions = Region::where('is_archived', 0)->get();
            $users = User::where('is_archived', 0)->get();

            return view('employee.household.postponed.index', compact('communities', 'regions', 'users'));
        } else {

            return view('errors.not-found');
        }
    }

    /**
     * View Edit page.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function editPage($id)
    {
    } 

    /** 
     * View Edit page.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    }

    /**
     * Update an existing resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request, int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

    }

    /**
     * Confirm the postponed household
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function movePostponedHousehold(Request $request)
    {
        $id = $request->id;
        
        $household = Household::find($id);
        $status = "Confirmed";
        $statusHousehold = HouseholdStatus::where('status', 'like', '%' . $status . '%')->first();
        $lastCycleYear = EnergySystemCycle::latest()->first();

        if($household) {
            
            $postponedHousehold = PostponedHousehold::where("is_archived", 0)
                ->where("household_id", $id)
                ->first();
            if($postponedHousehold) {

                $postponedHousehold->is_archived = 1;
                $postponedHousehold->save();
            }

            if($household->energy_system_type_id == 2) {

                $household->household_status_id = $statusHousehold->id;
                $household->energy_system_cycle_id = $lastCycleYear->id; 
                $household->save();
            } else {

                $status = "AC Completed";
                $statusHousehold = HouseholdStatus::where('status', 'like', '%' . $status . '%')->first();
                $household->household_status_id = $statusHousehold->id;
                $household->energy_system_cycle_id = $lastCycleYear->id; 
                $household->save();

                $energySystem = EnergySystem::where("is_archived", 0)
                    ->where("community_id", $household->community_id)
                    ->first();

                $allEnergyMeter = new AllEnergyMeter();
                $allEnergyMeter->household_id = $household->id;
                $allEnergyMeter->installation_type_id = 3;
                $allEnergyMeter->community_id = $household->community_id;
                $allEnergyMeter->energy_system_cycle_id = $lastCycleYear->id;
                $allEnergyMeter->energy_system_type_id = $energySystem->energy_system_type_id;
                $allEnergyMeter->ground_connected = "Yes";
                $allEnergyMeter->energy_system_id = $energySystem->id;
                $allEnergyMeter->meter_number = 0;
                $allEnergyMeter->meter_case_id = 12; 
                $allEnergyMeter->save();
            }
        } 

        $response['success'] = 1;
        $response['msg'] = 'Postponed Household Confirmed successfully'; 

        return response()->json($response); 
    }
}
