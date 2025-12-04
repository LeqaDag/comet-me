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
use App\Models\MeterHistories;
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
                    ->leftJoin('energy_system_cycles', 'households.energy_system_cycle_id', 'energy_system_cycles.id')
                    ->where('households.is_archived', 0)
                    ->where('households.internet_holder_young', 0)
                    ->where('households.household_status_id', 5);
                    
                if($communityFilter != null) {

                    $data->where('communities.id', $communityFilter);
                }

                //new check if status filter is displaced table displaced_households_status_id 
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
                    'communities.arabic_name as aname',
                    'households.confirmation_notes',
                    'energy_system_cycles.name as cycle_year')
                ->latest(); 

                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
    
                        $viewButton = "<a type='button' class='viewEnergyRequest' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#viewEnergyRequestModal' ><i class='fa-solid fa-eye text-info'></i></a>";
                        $moveButton = "<a type='button' title='Start Working' class='moveEnergyRequest' 
                            data-id='".$row->id."' 
                            data-cycle='".$row->cycle_year."' 
                            data-notes='".$row->confirmation_notes."'>
                            <i class='fa-solid fa-check text-success'></i></a>";

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
            $energyCycles = EnergySystemCycle::select('id', 'name')->get();

            return view('request.energy.index', compact('communities', 'households',
                'requestStatuses', 'energySystemTypes', 'energyCycles'));
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

        // Pull communities linked to FBS from all_energy_meters table.
        // We try to join installation_types and match on english_name containing 'FBS'.
        // This is a best-effort filter; if your installation types use a different column or naming,
        // adjust the where clause accordingly.
        $fbsCommunities = DB::table('all_energy_meters')
            ->join('communities', 'all_energy_meters.community_id', 'communities.id')
            ->leftJoin('installation_types', 'all_energy_meters.installation_type_id', 'installation_types.id')
            ->where('all_energy_meters.is_archived', 0)
            // installation_types table uses a 'type' column in this project (not english_name),
            // so filter on that column for FBS values.
            ->where('installation_types.type', 'like', '%FBS%')
            ->select('communities.id', 'communities.english_name')
            ->distinct()
            ->orderBy('communities.english_name', 'ASC')
            ->get();

        return view('request.energy.create', compact('communities', 'requestStatuses', 'energySystemTypes', 
            'users', 'professions', 'fbsCommunities'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // If a main household (existing) was selected — handle FBS upgrade flow
        if ($request->filled('selected_household')) {
            $selectedHouseholdId = $request->input('selected_household');
            $household = Household::find($selectedHouseholdId);

            if (!$household) {
                return redirect('/energy-request')->with('error', 'Selected household not found.');
            }

            // If user chose to keep the same meter, do minimal updates and return
            if ($request->input('keep_same_meter') == '1') {
                // nothing to change to the meter allocation
                return redirect('/energy-request')->with('message', 'FBS Upgrade: kept existing meter.');
            }

            // If user chose to assign a new meter, validate and process
            if ($request->input('keep_same_meter') == '0') {
                if (!$request->filled('new_meter_number')) {
                    return redirect()->back()->withInput()->with('error', 'Please provide a new meter number when assigning a new meter.');
                }

                $newMeterNumber = $request->input('new_meter_number');

                // Deactivate or mark previous main meter(s) for this household as not main
                $prevMeters = AllEnergyMeter::where('household_id', $household->id)->get();
                foreach ($prevMeters as $pm) {
                    $pm->is_main = 'No';
                    $pm->save();
                }

                // If an existing meter with that number exists, reassign it
                $existingMeterRecord = AllEnergyMeter::where('meter_number', $newMeterNumber)->first();
                if ($existingMeterRecord) {
                    $existingMeterRecord->household_id = $household->id;
                    $existingMeterRecord->is_main = 'Yes';
                    $existingMeterRecord->community_id = $household->community_id ?? $existingMeterRecord->community_id;
                    $existingMeterRecord->save();

                    // Update HouseholdMeter mapping
                    $householdMeter = \App\Models\HouseholdMeter::where('household_id', $household->id)->first();
                    if (!$householdMeter) {
                        $householdMeter = new \App\Models\HouseholdMeter();
                        $householdMeter->household_id = $household->id;
                    }
                    $householdMeter->energy_user_id = $existingMeterRecord->id;
                    $householdMeter->user_name = $household->english_name;
                    $householdMeter->user_name_arabic = $household->arabic_name;
                    $householdMeter->household_name = $household->english_name;
                    $householdMeter->save();

                    // Create meter history
                    try {
                        $status = \App\Models\MeterHistoryStatuses::where('english_name', 'like', '%used by other%')
                            ->orWhere('english_name', 'like', '%replac%')
                            ->first();

                        $history = new MeterHistories();
                        $history->date = now()->format('Y-m-d');
                        $history->meter_history_status_id = $status->id ?? null;
                        $history->old_meter_number = $existingMeterRecord->meter_number;
                        $history->new_meter_number = $existingMeterRecord->meter_number;
                        $history->household_id = $household->id;
                        $history->community_id = $household->community_id;
                        $history->main_energy_meter_id = $existingMeterRecord->id;
                        $history->all_energy_meter_id = $existingMeterRecord->id;
                        $history->notes = 'FBS upgrade: existing meter reassigned to household via request.';
                        $history->save();
                    } catch (\Exception $e) {
                        \Log::error('Failed to create meter history after reassigning existing meter', ['error' => $e->getMessage()]);
                    }

                    return redirect('/energy-request')->with('message', 'FBS Upgrade: assigned existing meter to household.');
                }

                // Otherwise create a new AllEnergyMeter record for the new meter number
                if (AllEnergyMeter::where('meter_number', $newMeterNumber)->exists()) {
                    return redirect()->back()->withInput()->with('error', 'Meter number already exists — please select the existing meter or choose a different number.');
                }

                $newAllEnergyMeter = new AllEnergyMeter();
                $newAllEnergyMeter->household_id = $household->id;
                $newAllEnergyMeter->community_id = $household->community_id;
                $newAllEnergyMeter->installation_type_id = 3;
                $newAllEnergyMeter->meter_number = $newMeterNumber;
                $newAllEnergyMeter->is_main = 'Yes';
                $newAllEnergyMeter->meter_case_id = $request->input('new_meter_case') ?? 12;
                $newAllEnergyMeter->save();

                $householdMeter = \App\Models\HouseholdMeter::where('household_id', $household->id)->first();
                if (!$householdMeter) {
                    $householdMeter = new \App\Models\HouseholdMeter();
                    $householdMeter->household_id = $household->id;
                }
                $householdMeter->energy_user_id = $newAllEnergyMeter->id;
                $householdMeter->user_name = $household->english_name;
                $householdMeter->user_name_arabic = $household->arabic_name;
                $householdMeter->household_name = $household->english_name;
                $householdMeter->save();

                try {
                    $status = \App\Models\MeterHistoryStatuses::where('english_name', 'like', '%replac%')
                        ->orWhere('english_name', 'like', '%used by other%')
                        ->first();

                    $history = new MeterHistories();
                    $history->date = now()->format('Y-m-d');
                    $history->meter_history_status_id = $status->id ?? null;
                    $firstPrev = $prevMeters->first();
                    $history->old_meter_number = $firstPrev->meter_number ?? null;
                    $history->new_meter_number = $newAllEnergyMeter->meter_number;
                    $history->household_id = $household->id;
                    $history->community_id = $household->community_id;
                    $history->main_energy_meter_id = $newAllEnergyMeter->id;
                    $history->all_energy_meter_id = $newAllEnergyMeter->id;
                    $history->notes = 'FBS upgrade: new meter assigned to household via request.';
                    $history->save();
                } catch (\Exception $e) {
                    \Log::error('Failed to create meter history for new meter assignment', ['error' => $e->getMessage()]);
                }

                return redirect('/energy-request')->with('message', 'FBS Upgrade: new meter assigned and history updated.');
            }
            // end keep_same_meter == 0 handling
        }

        // Default flow: create a new requested household (original behavior)
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

        return redirect('/energy-request')->with('message', 'New Energy Requested Household Added Successfully!');
    }

    /**
     * AJAX: search meter numbers for autocomplete suggestions
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function searchMeters(Request $request)
    {
        $q = $request->get('q', null);

        $query = AllEnergyMeter::where('is_archived', 0)
            ->whereNotNull('meter_number');

        if ($q) {
            $query->where('meter_number', 'like', "%{$q}%");
        }

        $meters = $query->orderBy('meter_number', 'ASC')
            ->limit(30)
            ->pluck('meter_number');

        return response()->json($meters);
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
        $notes = $request->notes;
        $lastCycleYear = EnergySystemCycle::latest()->first();
        $cycleyear = $request->cycleyear ?? $lastCycleYear->id;

        $household = Household::find($id);

        if (!$household) {
            return response()->json([
                'success' => 0,
                'msg' => 'Household not found.'
            ], 404);
        }

        $household->confirmation_notes = $notes;
        $household->energy_system_cycle_id = $cycleyear;

        $status = "Confirmed";
        $statusHousehold = HouseholdStatus::where('status', 'like', '%' . $status . '%')->first();

        if (!$statusHousehold) {
            return response()->json([
                'success' => 0,
                'msg' => 'Household status not found.'
            ], 400);
        }

        $energySystem = EnergySystem::where("is_archived", 0)
            ->where("community_id", $household->community_id)
            ->first();

        if ($household->energy_system_type_id == 2) {
            $household->household_status_id = $statusHousehold->id;
            $household->save();
        } elseif ($energySystem) {
            $status = "AC Completed";
            $statusHousehold = HouseholdStatus::where('status', 'like', '%' . $status . '%')->first();

            if (!$statusHousehold) {
                return response()->json([
                    'success' => 0,
                    'msg' => 'Household status not found for AC.'
                ], 400);
            }

            $household->household_status_id = $statusHousehold->id;
            $household->save();

            $allEnergyMeter = new AllEnergyMeter();
            $allEnergyMeter->household_id = $household->id;
            $allEnergyMeter->installation_type_id = 3;
            $allEnergyMeter->community_id = $household->community_id;
            $allEnergyMeter->energy_system_cycle_id = $cycleyear;
            $allEnergyMeter->energy_system_type_id = $energySystem->energy_system_type_id;
            $allEnergyMeter->ground_connected = "Yes";
            $allEnergyMeter->energy_system_id = $energySystem->id;
            $allEnergyMeter->meter_number = 0;
            $allEnergyMeter->meter_case_id = 12;
            $allEnergyMeter->save();
        } else {
            $household->household_status_id = $statusHousehold->id;
            $household->save();
        }

        return response()->json([
            'success' => 1,
            'msg' => 'Requested Household Confirmed successfully'
        ]); 
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
        // Decide which export to run based on the chosen export_type
        $type = $request->input('export_type');

        // Normalize type
        if ($type) $type = strtolower(trim($type));

        // If called as an Energy Project export (client sets this when no choices are made),
        // return the multi-sheet EnergyRequestSystemExport.
        if ($request->input('energy_project') == '1') {
            return Excel::download(new \App\Exports\EnergyRequestSystemExport($request), 'Energy Project.xlsx');
        }

        switch ($type) {
            case 'requested':
            case 'request':
                return Excel::download(new \App\Exports\EnergyRequestedHousehold($request), 'Requested Households.xlsx');

            case 'confirmed':
            case 'misc_confirmed':
                return Excel::download(new \App\Exports\ConfirmedHousehold($request), 'Confirmed Households.xlsx');

            case 'served':
                // ConfirmedHousehold  
                $request->merge(['status' => 'served']);
                return Excel::download(new \App\Exports\ConfirmedHousehold($request), 'Served Households.xlsx');

            case 'initial':
                // Initial status export
                $initialStatus = HouseholdStatus::where('status', 'like', '%Initial%')->first();
                if ($initialStatus) {
                    $request->merge(['status' => [$initialStatus->id]]);
                }
                return Excel::download(\App\Exports\HouseholdExport::exportInitial($request), 'Initial households.xlsx');

            case 'ac':
            case 'ac_survey':
            case 'ac_completed':
                $acStatus = HouseholdStatus::where('status', 'like', '%AC%')->first();
                if ($acStatus) {
                    $request->merge(['status' => [$acStatus->id]]);
                }
                return Excel::download(new \App\Exports\HouseholdExport($request), 'AC Households.xlsx');

            case 'dc':
                // DC / Active No meter - fall back to EnergyMISCHousehold
                return Excel::download(new \App\Exports\EnergyMISCHousehold($request), 'DC Households.xlsx');

            default:
                // Unknown type: default to confirmed export
                return Excel::download(new \App\Exports\ConfirmedHousehold($request), 'Confirmed Households.xlsx');
        }
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
