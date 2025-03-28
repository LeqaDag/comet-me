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
use App\Models\User;
use App\Models\Community;
use App\Models\EnergySystem;
use App\Models\EnergySystemType;
use App\Models\EnergyRequestStatus;
use App\Models\EnergyRequestSystem;
use App\Models\Household;
use App\Models\HouseholdStatus;
use App\Models\PublicStructure;
use App\Models\InstallationType;
use App\Models\EnergySystemCycle;
use App\Models\Region;
use App\Models\Profession;
use App\Models\PostponedHousehold; 
use App\Models\DeletedRequestedHousehold; 
use App\Exports\EnergyRequestSystemExport;
use App\Exports\EnergyRequestedHousehold; 
use Carbon\Carbon;
use Image;
use Excel;
use DataTables;

class EnergyRequestSystemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $requestedSystems = EnergyRequestSystem::where("installation_type_id", 2)->get();
        foreach($requestedSystems as $requestedSystem) {

            $requestedSystem->recommendede_energy_system_id = 2;
            $requestedSystem->save();
        }

        if (Auth::guard('user')->user() != null) {
 
            $communityFilter = $request->input('community_filter');
            $systemTypeFilter = $request->input('system_type_filter');
            $dateFilter = $request->input('date_filter');
            $statusFilter = $request->input('household_status');

            if ($request->ajax()) {

                $data = DB::table('households')
                    ->join('communities', 'households.community_id', 'communities.id')
                    ->join('regions', 'communities.region_id', 'regions.id')
                    ->leftJoin('all_energy_meters', 'all_energy_meters.household_id', 'households.id')
                    ->leftJoin('energy_system_types', 'all_energy_meters.energy_system_type_id', 'energy_system_types.id')
                    ->leftJoin('users', 'households.referred_by_id', 'users.id')
                    ->leftJoin('energy_system_types as energy_types', 'households.energy_system_type_id', 'energy_types.id')
                    ->where('households.is_archived', 0)
                    ->where('households.internet_holder_young', 0)
                    ->where('households.household_status_id', 5);
                    
                if($communityFilter != null) {

                    $data->where('communities.id', $communityFilter);
                }
                if ($statusFilter != null) {

                    if($statusFilter == "served") $data->where('all_energy_meters.is_main', 'No');
                    else if($statusFilter == "service_requested") {

                        $data->where(function ($query) {
                            $query->where('all_energy_meters.is_main', '!=', 'No')
                                  ->orWhereNull('all_energy_meters.is_main');
                        });
                    }
                }
                if ($systemTypeFilter != null) {

                    $data->where(function($query) use ($systemTypeFilter) {
                        $query->where('energy_system_types.id', $systemTypeFilter)
                              ->orWhere('energy_types.id', $systemTypeFilter);
                    });
                }
                if ($dateFilter != null) {

                    $data->whereRaw('DATE(households.created_at) >= ?', [$dateFilter])
                        ->orWhereRaw('households.request_date >= ?', [$dateFilter]);
                }

                $data->select('households.english_name as english_name', 
                    'households.arabic_name as arabic_name',
                    'households.id as id', 
                    DB::raw('CASE 
                            WHEN households.request_date IS NOT NULL THEN households.request_date 
                            ELSE DATE(households.created_at) 
                        END as created_at
                    '),
                    DB::raw("CASE WHEN all_energy_meters.is_main = 'No' THEN 'Served'
                        ELSE 'Service requested' END AS status"),
                    'households.updated_at as updated_at', 'users.name as referred_by',
                    'regions.english_name as region_name', 
                    DB::raw('IFNULL(energy_system_types.name, energy_types.name) 
                        as type'),
                    'communities.english_name as community_name', 'households.phone_number',
                    'communities.arabic_name as aname')
                ->latest(); 

                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
    
                        $viewButton = "<a type='button' class='viewEnergyRequest' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#viewEnergyRequestModal' ><i class='fa-solid fa-eye text-info'></i></a>";
                        $moveButton = "<a type='button' title='Start Working' class='moveEnergyRequest' data-id='".$row->id."'><i class='fa-solid fa-check text-success'></i></a>";
                        $postponeButton = "<a type='button' title='Postpone this requested household' class='postponedEnergyRequest' data-id='".$row->id."'><i class='fa-solid fa-rotate-right text-warning'></i></a>";
                        $deleteButton = "<a type='button' class='deleteEnergyRequest' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";
         
                        return $moveButton. " " . $viewButton. " " . $postponeButton. " " . $deleteButton;
                    })
                    ->filter(function ($instance) use ($request) {
                        if (!empty($request->get('search'))) {
                                $instance->where(function($w) use($request) {
                                $search = $request->get('search');
                                $w->orWhere('communities.english_name', 'LIKE', "%$search%")
                                ->orWhere('communities.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('energy_types.name', 'LIKE', "%$search%")
                                ->orWhere('energy_system_types.name', 'LIKE', "%$search%")
                                ->orWhere('users.name', 'LIKE', "%$search%")
                                ->orWhere('households.english_name', 'LIKE', "%$search%")
                                ->orWhere('households.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('households.phone_number', 'LIKE', "%$search%");
                            });
                        }
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
    
            $communities = Community::where('is_archived', 0)
                ->orderBy('english_name', 'ASC')
                ->get();
            $households = Household::where('is_archived', 0)
                ->orderBy('english_name', 'ASC')
                ->get();
            $requestStatuses = EnergyRequestStatus::where('is_archived', 0)
                ->orderBy('name', 'ASC')
                ->get();

            $energySystemTypes = EnergySystemType::where('is_archived', 0)->get();

            return view('request.energy.index', compact('communities', 'households',
                'requestStatuses', 'energySystemTypes'));
        } else {

            return view('errors.not-found');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $communities = Community::where('is_archived', 0)
            ->orderBy('english_name', 'ASC')
            ->get();
        $requestStatuses = EnergyRequestStatus::where('is_archived', 0)
            ->orderBy('name', 'ASC')
            ->get();

        $professions = Profession::where('is_archived', 0)->get();

        $installationTypes = InstallationType::where('is_archived', 0)->get();
        $energySystemTypes = EnergySystemType::where('is_archived', 0)->get();
        $users = User::where('is_archived', 0)->get();

        return view('request.energy.create', compact('communities', 'requestStatuses', 'energySystemTypes', 
            'users', 'professions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $last_comet_id = Household::latest('id')->value('comet_id');

        $energyRequestHousehold = new Household();
        $energyRequestHousehold->comet_id = ++$last_comet_id;
        $energyRequestHousehold->household_status_id = 5;
        $energyRequestHousehold->english_name = $request->english_name;
        $energyRequestHousehold->arabic_name = $request->arabic_name;
        $energyRequestHousehold->profession_id = $request->profession_id;
        $energyRequestHousehold->phone_number = $request->phone_number;
        $energyRequestHousehold->community_id = $request->community_id;
        $energyRequestHousehold->number_of_people = $request->number_of_people;
        $energyRequestHousehold->number_of_male = $request->number_of_male;
        $energyRequestHousehold->number_of_female = $request->number_of_female;
        $energyRequestHousehold->number_of_adults = $request->number_of_adults;
        $energyRequestHousehold->number_of_children = $request->number_of_children;
        $energyRequestHousehold->school_students = $request->school_students;
        $energyRequestHousehold->university_students = $request->university_students;
        $energyRequestHousehold->demolition_order = $request->demolition_order;
        $energyRequestHousehold->energy_system_type_id = $request->energy_system_type_id;
        $energyRequestHousehold->request_date = $request->request_date;
        $energyRequestHousehold->referred_by_id = $request->referred_by_id; 
        $energyRequestHousehold->notes = $request->notes;
        $energyRequestHousehold->save();

        return redirect('/energy-request')
            ->with('message', 'New Energy Requested Household Added Successfully!');
    }

    /**
     * Confirm the requested household
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function moveEnergyRequest(Request $request)
    {
        $id = $request->id;

        $household = Household::find($id);
        $status = "Confirmed";
        $statusHousehold = HouseholdStatus::where('status', 'like', '%' . $status . '%')->first();
        $lastCycleYear = EnergySystemCycle::latest()->first();

        if($household) {
            
            $energySystem = EnergySystem::where("is_archived", 0)
                ->where("community_id", $household->community_id)
                ->first();

            if($household->energy_system_type_id == 2) { 

                $household->household_status_id = $statusHousehold->id;
                $household->energy_system_cycle_id = $lastCycleYear->id; 
                $household->save();
            } else if($energySystem) {

                $status = "AC Completed";
                $statusHousehold = HouseholdStatus::where('status', 'like', '%' . $status . '%')->first();
                $household->household_status_id = $statusHousehold->id;
                $household->energy_system_cycle_id = $lastCycleYear->id; 
                $household->save();

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
            } else {
                
                $household->household_status_id = $statusHousehold->id;
                $household->energy_system_cycle_id = $lastCycleYear->id; 
                $household->save();
            }
        } 

        $response['success'] = 1;
        $response['msg'] = 'Requested Household Confirmed successfully'; 

        return response()->json($response); 
    }

    /**
     * Postponed the requested household
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postponedEnergyRequest(Request $request)
    {
        $id = $request->id;

        $household = Household::find($id);
        $status = "Postponed";
        $statusHousehold = HouseholdStatus::where('status', 'like', '%' . $status . '%')->first();

        if($household) {
            
            if($statusHousehold) {

                $household->household_status_id = $statusHousehold->id;
                $household->save();

                $user = Auth::guard('user')->user();

                $postponedHousehold = new PostponedHousehold();
                $postponedHousehold->household_id = $id;
                $postponedHousehold->reason = $request->reason;
                $postponedHousehold->referred_by = $user->id;
                $postponedHousehold->save();
            }
        } 

        $response['success'] = 1;
        $response['msg'] = 'Requested Household Postponed successfully'; 

        return response()->json($response); 
    }

    /**
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteEnergyRequest(Request $request)
    {
        $id = $request->id;

        $household = Household::find($id);

        $householdMeter = AllEnergyMeter::where("is_archived", 0)
            ->where("household_id", $id)
            ->first();

        if($householdMeter) {
            
            $household->household_status_id = 4;
            $household->save();
        } else {

            $household->is_archived = 1;
            $household->save();
        }

        $existDeleted = DeletedRequestedHousehold::where("is_archived", 0)
            ->where("household_id", $id)
            ->first();

        if(!$existDeleted) {

            $user = Auth::guard('user')->user();

            $deletedRequestedHousehold = new DeletedRequestedHousehold();
            $deletedRequestedHousehold->household_id = $id;
            $deletedRequestedHousehold->reason = $request->reason;
            $deletedRequestedHousehold->referred_by = $user->id;
            $deletedRequestedHousehold->save();
        }

        $response['success'] = 1;
        $response['msg'] = 'Requested Household Removed successfully'; 

        return response()->json($response); 
    }

    /**
     * Get households by community_id.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getRequestedByCommunity(Request $request)
    {
        if (!$request->community_id) {

            $html = '<option value="">Choose One...</option>';
        } else {

            $html = '<option selected>Choose One...</option>';
            $households = Household::where('community_id', $request->community_id)
                ->where('is_archived', 0)
                ->where('household_status_id', 5)
                ->orderBy('english_name', 'ASC')
                ->get();

            foreach ($households as $household) {
                $html .= '<option value="'.$household->id.'">'.$household->english_name.'</option>';
            }
        }

        return response()->json(['html' => $html]);
    }

    /**
     * 
     * @return \Illuminate\Support\Collection
     */
    public function export(Request $request) 
    {
                
        return Excel::download(new EnergyRequestSystemExport($request), 'Energy Project.xlsx');
    }

    /**
     * 
     * @return \Illuminate\Support\Collection
     */
    public function exportRequested(Request $request) 
    {
                
        return Excel::download(new EnergyRequestedHousehold($request), 'Requested Households.xlsx');
    }
}
