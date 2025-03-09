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
use App\Models\PublicStructure;
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
use App\Models\EnergyRequestStatus;
use App\Models\PublicStructureStatus;
use App\Models\EnergyRequestSystem;
use App\Exports\ConfirmedHousehold;
use Carbon\Carbon;
use DataTables;
use mikehaertl\wkhtmlto\Pdf;
use Excel;

class MiscHouseholdController extends Controller
{
   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {	
        $status = "Confirmed";
        $statusHousehold = HouseholdStatus::where('status', 'like', '%' . $status . '%')->first();
        $statusPublic = PublicStructureStatus::where('status', 'like', '%' . $status . '%')->first();

        if (Auth::guard('user')->user() != null) {

            $communityFilter = $request->input('filter');
            $regionFilter = $request->input('second_filter');

            if ($request->ajax()) {
            
                $dataHousehold = DB::table('households')
                    ->where('households.is_archived', 0)
                    ->where('households.internet_holder_young', 0)
                    ->where('households.household_status_id', $statusHousehold->id)
                    ->join('communities', 'households.community_id', 'communities.id')
                    ->join('regions', 'communities.region_id', 'regions.id');
                    
                $dataPublic = DB::table('public_structures')
                    ->where('public_structures.is_archived', 0)
                    ->where('public_structures.public_structure_status_id', $statusPublic->id)
                    ->join('communities', 'public_structures.community_id', 'communities.id')
                    ->join('regions', 'communities.region_id', 'regions.id');

                if ($communityFilter != null) {

                    $dataHousehold->where('communities.id', $communityFilter);
                    $dataPublic->where('communities.id', $communityFilter);
                }

                if ($regionFilter != null) {

                    $dataHousehold->where('regions.id', $regionFilter);
                    $dataPublic->where('regions.id', $regionFilter);
                }

                $dataHousehold->select(
                    'households.english_name as english_name', 
                    'households.arabic_name as arabic_name',
                    'households.id as id', 'households.created_at as created_at', 
                    'households.updated_at as updated_at',
                    'regions.english_name as region_name',
                    'communities.english_name as name',
                    'communities.arabic_name as aname',
                    DB::raw("'household' as source"))
                ->latest(); 
                
                $dataPublic->select(
                    'public_structures.english_name as english_name',
                    'public_structures.arabic_name as arabic_name',
                    'public_structures.id as id',
                    'public_structures.created_at as created_at',
                    'public_structures.updated_at as updated_at',
                    'regions.english_name as region_name',
                    'communities.english_name as name',
                    'communities.arabic_name as aname',
                    DB::raw("'public' as source")
                )->latest(); 
                
                // Combine the two queries using unionAll() and order by the latest records
                $data = $dataHousehold->unionAll($dataPublic)->latest();

                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) { 
    
                        if($row->source == "household") {

                            $detailsButton = "<a type='button' class='detailsHouseholdButton' data-bs-toggle='modal' data-bs-target='#householdDetails' data-id='".$row->id."'><i class='fa-solid fa-eye text-primary'></i></a>";
                            $moveButton = "<a type='button' title='Start Working' class='moveMISCHousehold' data-id='".$row->id."'><i class='fa-solid fa-check text-success'></i></a>";
                        } else if($row->source == "public") {

                            $detailsButton = "<a type='button' class='detailsPublicButton' data-bs-toggle='modal' data-bs-target='#publicDetails' data-id='".$row->id."'><i class='fa-solid fa-eye text-primary'></i></a>";
                            $moveButton = "<a type='button' title='Start Working' class='moveMISCPublic' data-id='".$row->id."'><i class='fa-solid fa-check text-success'></i></a>";
                        } 
                        
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

            $households = Household::where('is_archived', 0)
                ->orderBy('english_name', 'ASC')
                ->get();
            $requestStatuses = EnergyRequestStatus::where('is_archived', 0)
                ->orderBy('name', 'ASC')
                ->get();

            $energySystemTypes = EnergySystemType::where('is_archived', 0)->get();

            return view('employee.household.misc.index', compact('communities', 'regions', 'energySystemTypes',
                'requestStatuses'));
        } else {

            return view('errors.not-found');
        }
    }

    /**
     * Move a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function moveMISCHousehold(Request $request)
    {
        $id = $request->id;

        $household = Household::find($id);
        $status = "AC Completed";
        $statusHousehold = HouseholdStatus::where('status', 'like', '%' . $status . '%')->first();
        $lastCycleYear = EnergySystemCycle::latest()->first();
        $energySystem = EnergySystem::where("energy_system_type_id", 2)->latest()->first();

        if($household) {
            
            if($statusHousehold) {

                $household->household_status_id = $statusHousehold->id;
                $household->energy_system_cycle_id = $lastCycleYear->id; 
                $household->save();

                $allEnergyMeter = new AllEnergyMeter();
                $allEnergyMeter->household_id = $household->id;
                $allEnergyMeter->installation_type_id = 2;
                $allEnergyMeter->community_id = $household->community_id;
                $allEnergyMeter->energy_system_cycle_id = $lastCycleYear->id;
                $allEnergyMeter->energy_system_type_id = 2;
                $allEnergyMeter->ground_connected = "No";
                $allEnergyMeter->energy_system_id = $energySystem->id;
                $allEnergyMeter->meter_number = 0;
                $allEnergyMeter->meter_case_id = 12; 
                $allEnergyMeter->save();
            }
        } 

        $response['success'] = 1;
        $response['msg'] = 'MISC Household Confirmed successfully'; 

        return response()->json($response); 
    }

    /**
     * Move a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function moveMISCPublic(Request $request)
    {
        $id = $request->id;

        $public = PublicStructure::find($id);
        $status = "AC Completed";
        $statusPublic = PublicStructureStatus::where('status', 'like', '%' . $status . '%')->first();
        $lastCycleYear = EnergySystemCycle::latest()->first();
        $energySystem = EnergySystem::where("energy_system_type_id", 2)->latest()->first();

        if($public) {
            
            if($statusPublic) {

                $public->public_structure_status_id = $statusPublic->id;
                $public->energy_system_cycle_id = $lastCycleYear->id; 
                $public->save();

                $allEnergyMeter = new AllEnergyMeter();
                $allEnergyMeter->public_structure_id = $public->id;
                $allEnergyMeter->installation_type_id = 2;
                $allEnergyMeter->community_id = $public->community_id;
                $allEnergyMeter->energy_system_cycle_id = $lastCycleYear->id;
                $allEnergyMeter->energy_system_type_id = 2;
                $allEnergyMeter->ground_connected = "No";
                $allEnergyMeter->energy_system_id = $energySystem->id;
                $allEnergyMeter->meter_number = 0;
                $allEnergyMeter->meter_case_id = 12; 
                $allEnergyMeter->save();
            }
        } 

        $response['success'] = 1;
        $response['msg'] = 'MISC Public Confirmed successfully'; 

        return response()->json($response); 
    }

    /**
     * 
     * @return \Illuminate\Support\Collection
     */
    public function export(Request $request) 
    {
                
        return Excel::download(new ConfirmedHousehold($request), 'MISC Confirmed.xlsx');
    }
}
