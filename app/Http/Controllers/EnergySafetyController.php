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
use App\Models\AllEnergyMeterSafetyCheck;
use App\Models\User;
use App\Models\Community;
use App\Models\EnergySystem;
use App\Models\EnergySystemType;
use App\Models\Household; 
use App\Models\MeterCase;
use App\Models\Region;
use App\Models\SubRegion;
use App\Models\PublicStructure;
use App\Exports\EnergySafetyExport;
use App\Models\ElectricityMaintenanceCall;
use App\Models\ElectricityMaintenanceCallAction;
use Carbon\Carbon;
use Image;
use DataTables;
use Excel;

class EnergySafetyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $energySafety = AllEnergyMeterSafetyCheck::get();

        foreach($energySafety as $energy) {

            $allEnergyMeter = AllEnergyMeter::where("id", $energy->all_energy_meter_id)->first();
            $allEnergyMeter->ground_connected = "Yes";
            $allEnergyMeter->save();
        }

        if (Auth::guard('user')->user() != null) {

            if ($request->ajax()) {

                $data = DB::table('all_energy_meter_safety_checks')
                    ->join('all_energy_meters', 'all_energy_meters.id', 
                        '=', 'all_energy_meter_safety_checks.all_energy_meter_id')
                    ->join('communities', 'all_energy_meters.community_id', '=', 'communities.id')
                    ->leftJoin('households', 'all_energy_meters.household_id', '=', 'households.id')
                    ->LeftJoin('public_structures', 'all_energy_meters.public_structure_id', 
                        'public_structures.id')
                    ->join('energy_systems', 'all_energy_meters.energy_system_id', '=', 'energy_systems.id')
                    ->join('energy_system_types', 'all_energy_meters.energy_system_type_id', '=', 'energy_system_types.id')
                    ->join('meter_cases', 'all_energy_meters.meter_case_id', '=', 'meter_cases.id')
                    ->where('all_energy_meter_safety_checks.is_archived', 0)
                    ->select('all_energy_meters.meter_number', 
                        'all_energy_meters.ground_connected',
                        'all_energy_meter_safety_checks.id as id', 
                        'all_energy_meter_safety_checks.created_at as created_at', 
                        'all_energy_meter_safety_checks.updated_at as updated_at', 
                        'communities.english_name as community_name',
                        'households.english_name as household_name',
                        'public_structures.english_name as public_name',
                        'energy_system_types.name as energy_type_name',
                        'meter_cases.meter_case_name_english')
                    ->latest(); 
    
                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
                        $viewButton = "<a type='button' class='viewEnergySafety' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#viewEnergySafetyModal' ><i class='fa-solid fa-eye text-info'></i></a>";
                        $updateButton = "<a type='button' class='updateEnergySafety' data-id='".$row->id."' ><i class='fa-solid fa-pen-to-square text-success'></i></a>";
                        $deleteButton = "<a type='button' class='deleteEnergySafety' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";
                        
                        if(Auth::guard('user')->user()->user_type_id == 1 || 
                            Auth::guard('user')->user()->user_type_id == 2 ||
                            Auth::guard('user')->user()->user_type_id == 4) 
                        {
                                 
                            return $viewButton." ". $updateButton." ".$deleteButton;
                        } else return $viewButton;

                    })
                    ->addColumn('holder', function($row) {

                        if($row->household_name != null) $holder = $row->household_name;
                        else if($row->public_name != null) $holder = $row->public_name;

                        return $holder;
                    })
                    ->filter(function ($instance) use ($request) {
                        if (!empty($request->get('search'))) {
                                $instance->where(function($w) use($request) {
                                $search = $request->get('search');
                                $w->orWhere('communities.english_name', 'LIKE', "%$search%")
                                ->orWhere('households.english_name', 'LIKE', "%$search%")
                                ->orWhere('public_structures.english_name', 'LIKE', "%$search%")
                                ->orWhere('public_structures.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('communities.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('households.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('energy_systems.name', 'LIKE', "%$search%")
                                ->orWhere('energy_system_types.name', 'LIKE', "%$search%")
                                ->orWhere('all_energy_meters.meter_number', 'LIKE', "%$search%")
                                ->orWhere('meter_cases.meter_case_name_english', 'LIKE', "%$search%")
                                ->orWhere('meter_cases.meter_case_name_arabic', 'LIKE', "%$search%")
                                ->orWhere('all_energy_meter_safety_checks.visit_date', 'LIKE', "%$search%");
                            });
                        }
                    })
                    ->rawColumns(['action', 'holder'])
                    ->make(true);
            }

            $communities = Community::where('is_archived', 0)
                ->orderBy('english_name', 'ASC')
                ->get();
            $meterCases = MeterCase::where('is_archived', 0)->get();
            $energySystemTypes = EnergySystemType::where('is_archived', 0)->get();

            // 987 - 11 "FBS with no ground connected"
            $data = DB::table('all_energy_meters')
                ->where('all_energy_meters.is_archived', 0)
                ->where('all_energy_meters.energy_system_type_id', 2)
                ->select(
                    DB::raw('all_energy_meters.ground_connected as name'),
                    DB::raw('count(*) as number'))
                ->groupBy('all_energy_meters.ground_connected')
                ->get();
              
            // $data1= DB::table('all_energy_meters')
            //     ->join('meter_cases', 'all_energy_meters.meter_case_id', '=', 'meter_cases.id')
            //     ->where('all_energy_meters.is_archived', 0)
            //     ->where('all_energy_meters.energy_system_type_id', 2)
            //     ->select(
            //         DB::raw('all_energy_meters.ground_connected as name'),
            //         DB::raw('count(*) as number'))
            //     ->groupBy('all_energy_meters.ground_connected')
            //     ->get();

            // die($data1);

            $array[] = ['Ground Connected', 'Total'];
            
            foreach($data as $key => $value) {
    
                $array[++$key] = [$value->name, $value->number];
            }

            $regions = Region::where('is_archived', 0)
                ->orderBy('english_name', 'ASC')
                ->get();

            $subRegions = SubRegion::where('is_archived', 0)
                ->orderBy('english_name', 'ASC')
                ->get();

            $checkDate = DB::table('all_energy_meter_safety_checks')
                ->select('visit_date', DB::raw('COUNT(*) AS cnt'))
                ->groupBy('visit_date')
                ->orderByRaw('COUNT(*) DESC')
                ->take(1)
                ->get(); 
            
            $groundYes =  DB::table('all_energy_meter_safety_checks')
                ->join('all_energy_meters', 'all_energy_meters.id', 
                    'all_energy_meter_safety_checks.all_energy_meter_id')
                ->where('all_energy_meters.is_archived', 0)
                ->where('all_energy_meter_safety_checks.is_archived', 0)
                ->where('all_energy_meters.energy_system_type_id', 2)
                ->where('all_energy_meters.ground_connected', "Yes")
                ->count();

            $groundNo =  DB::table('all_energy_meter_safety_checks')
                ->join('all_energy_meters', 'all_energy_meters.id', 
                    '=', 'all_energy_meter_safety_checks.all_energy_meter_id')
                ->where('all_energy_meters.is_archived', 0)
                ->where('all_energy_meter_safety_checks.is_archived', 0)
                ->where('all_energy_meters.energy_system_type_id', 2)
                ->where('all_energy_meters.ground_connected', "No")
                ->count();  


            $notYetChecked =  DB::table('all_energy_meters')
                ->leftJoin('all_energy_meter_safety_checks', function($join) {
                    $join->on('all_energy_meters.id', 'all_energy_meter_safety_checks.all_energy_meter_id')
                        ->where('all_energy_meter_safety_checks.is_archived', 0);
                })
                ->whereNull('all_energy_meter_safety_checks.all_energy_meter_id')
                ->where('all_energy_meters.is_archived', 0)
                ->where('all_energy_meters.energy_system_type_id', 2)
                ->count();
 
            $groundConnectedFbs = AllEnergyMeter::where("is_archived", 0)
                ->where('all_energy_meters.energy_system_type_id', 2)
                ->where('all_energy_meters.ground_connected', "Yes")
                ->count();
            $groundNotConnectedFbs = AllEnergyMeter::where("is_archived", 0)
                ->where('all_energy_meters.energy_system_type_id', 2)
                ->where('all_energy_meters.ground_connected', "No")
                ->count();
          
            $badResultsNumber = AllEnergyMeterSafetyCheck::where("ph_loop", "<", 10)
                ->where("n_loop", "<", 10)
                ->count();

            $totalNumberFbs = AllEnergyMeter::where("is_archived", 0)
                ->where('all_energy_meters.energy_system_type_id', 2)
                ->count();

            $totalNumberMg = AllEnergyMeter::where("is_archived", 0)
                ->where('all_energy_meters.energy_system_type_id', '!=', 2)
                ->count();

            return view('safety.energy.index', compact('communities', 'energySystemTypes', 
                'meterCases', 'regions', 'subRegions', 'checkDate', 'groundYes',
                'groundNo', 'notYetChecked', 'groundConnectedFbs', 'groundNotConnectedFbs',
                'badResultsNumber', 'totalNumberMg', 'totalNumberFbs'))
                ->with('energy_users', json_encode($array)
            );
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
        $energySafety = new AllEnergyMeterSafetyCheck();
        $maintenance = new ElectricityMaintenanceCall();

        if($request->holder_id) {

            if($request->public_user == "user") {

                $allEnergyMeter = AllEnergyMeter::where("household_id", $request->holder_id)
                    ->first();
           
                $allEnergyMeter->ground_connected = "Yes";
                $allEnergyMeter->save();

                $maintenance->energy_user_id = $allEnergyMeter->id;
                $maintenance->household_id = $allEnergyMeter->household_id;
                $maintenance->community_id = $allEnergyMeter->community_id;
            } else if($request->public_user == "public") {
                
                $allEnergyMeter = AllEnergyMeter::where("public_structure_id", $request->holder_id)
                    ->first();
                $allEnergyMeter->ground_connected = "Yes";
                $allEnergyMeter->save();

                $maintenance->public_structure_id = $allEnergyMeter->public_structure_id;
                $maintenance->community_id = $allEnergyMeter->community_id;
            }
            
            $energySafety->all_energy_meter_id = $allEnergyMeter->id;

            if($request->meter_case_id) {

                $allEnergyMeter->meter_case_id = $request->meter_case_id;
                $allEnergyMeter->save();
            }

            // if($request->ground_connected) {

            //     $allEnergyMeter->ground_connected = $request->ground_connected;
            //     $allEnergyMeter->save();
            // }
        } 

        $energySafety->visit_date = $request->visit_date;
        $energySafety->rcd_x_phase0 = $request->rcd_x_phase0;
        $energySafety->rcd_x_phase1 = $request->rcd_x_phase1;
        $energySafety->rcd_x1_phase0 = $request->rcd_x1_phase0;
        $energySafety->rcd_x1_phase1 = $request->rcd_x1_phase1;
        $energySafety->rcd_x5_phase0 = $request->rcd_x5_phase0;
        $energySafety->rcd_x5_phase1 = $request->rcd_x5_phase1;
        $energySafety->ph_loop = $request->ph_loop;
        $energySafety->n_loop = $request->n_loop;
        $energySafety->notes = $request->notes;
        $energySafety->save();

        if($request->ph_loop < 10 && $request->n_loop < 10) {

            $maintenance->date_of_call = $request->visit_date;
            $maintenance->maintenance_status_id = 1;
            $maintenance->user_id = Auth::guard('user')->user()->id;
            $maintenance->maintenance_type_id = 1;
            $maintenance->notes = $request->notes;
            $maintenance->save();

            $maintenanceId = $maintenance->id;

            $electricityMaintenanceCallAction = new ElectricityMaintenanceCallAction();
            $electricityMaintenanceCallAction->maintenance_electricity_action_id = 75;
            $electricityMaintenanceCallAction->electricity_maintenance_call_id = $maintenanceId;
            $electricityMaintenanceCallAction->save();
        }

        return redirect()->back()->with('message', 'New Meter Saftey Check Added Successfully!');
    }

    /**
     * Show the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $energySafety = AllEnergyMeterSafetyCheck::findOrFail($id);
       
        $allEnergyMeter = AllEnergyMeter::where("id", $energySafety->all_energy_meter_id)->first();

        if($allEnergyMeter->household_id != NULL || $allEnergyMeter->household_id != 0) {
            $householdId = $allEnergyMeter->household_id;
            $household = Household::where('id', $householdId)->first();
            
            $response['household'] = $household;
        }

        if($allEnergyMeter->public_structure_id != NULL || $allEnergyMeter->household_id != 0) {
            $publicId = $allEnergyMeter->public_structure_id;
            $public = PublicStructure::where('id', $publicId)->first();
            
            $response['public'] = $public;
        }
       
        $community = Community::where('id', $allEnergyMeter->community_id)->first();
        $meter = MeterCase::where('id', $allEnergyMeter->meter_case_id)->first();
        $systemType = EnergySystemType::where('id', $allEnergyMeter->energy_system_type_id)->first();

        $response['community'] = $community;
        $response['energySafety'] = $energySafety;
        $response['allEnergyMeter'] = $allEnergyMeter;
        $response['meter'] = $meter;
        $response['systemType'] = $systemType;

        return response()->json($response);
    }

    /**
     * View Edit page.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function editPage($id)
    {
        $energySafety = AllEnergyMeterSafetyCheck::findOrFail($id);

        return response()->json($energySafety);
    }

    /**
     * View Edit page.
     *
     * @param  int $id 
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $household = null;
        $public = null;
        $energySafety = AllEnergyMeterSafetyCheck::findOrFail($id);

        $energyMeter = AllEnergyMeter::where("id", $energySafety->all_energy_meter_id)->first();
        if($energyMeter->household_id != NULL || $energyMeter->household_id != 0) {
            $householdId = $energyMeter->household_id;
            $household = Household::where('id', $householdId)->first();
        }

        if($energyMeter->public_structure_id != NULL || $energyMeter->household_id != 0) {
            $publicId = $energyMeter->public_structure_id;
            $public = PublicStructure::where('id', $publicId)->first();
        }

        $communities = Community::where('is_archived', 0)
            ->orderBy('english_name', 'ASC')
            ->get();
        $meterCases = MeterCase::where('is_archived', 0)->get();
        $energySystemTypes = EnergySystemType::where('is_archived', 0)->get();

        return view('safety.energy.edit', compact('energySafety', 'communities', 'meterCases', 
            'energySystemTypes', 'energyMeter', 'household', 'public'));
    }

    /**
     * Update an existing resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request, int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //dd($request->all());
        $energySafety = AllEnergyMeterSafetyCheck::findOrFail($id);

        $energyMeter = AllEnergyMeter::where("id", $energySafety->all_energy_meter_id)->first();
        if($request->meter_case_id) {

            $energyMeter->meter_case_id = $request->meter_case_id;
            $energyMeter->save();
        }
 
        if($request->ground_connected) {

            $energyMeter->ground_connected = $request->ground_connected;
            $energyMeter->save();
        }

        $energySafety->visit_date = $request->visit_date;
        $energySafety->rcd_x_phase0 = $request->rcd_x_phase0;
        $energySafety->rcd_x_phase1 = $request->rcd_x_phase1;
        $energySafety->rcd_x1_phase0 = $request->rcd_x1_phase0;
        $energySafety->rcd_x1_phase1 = $request->rcd_x1_phase1;
        $energySafety->rcd_x5_phase0 = $request->rcd_x5_phase0;
        $energySafety->rcd_x5_phase1 = $request->rcd_x5_phase1;
        $energySafety->ph_loop = $request->ph_loop;
        $energySafety->n_loop = $request->n_loop;
        $energySafety->notes = $request->notes;
        $energySafety->save();

        
        if($request->ph_loop > 10) {

            $maintenance = new ElectricityMaintenanceCall();
            $exist = [];
            if($energyMeter->household_id) {

                $exist = ElectricityMaintenanceCall::where('household_id', $energyMeter->household_id)
                    ->where('date_of_call', $request->visit_date)
                    ->first();

                if($exist) {
                } else {

                    $maintenance->household_id = $energyMeter->household_id;
                    $maintenance->energy_user_id = $energyMeter->id;
                    $maintenance->community_id = $energyMeter->community_id;

                    $maintenance->date_of_call = $request->visit_date;
                    $maintenance->maintenance_status_id = 1;
                    $maintenance->user_id = Auth::guard('user')->user()->id;
                    $maintenance->maintenance_type_id = 1;
                    $maintenance->notes = $request->notes;
                    $maintenance->save();

                    $maintenanceId = $maintenance->id;

                    $electricityMaintenanceCallAction = new ElectricityMaintenanceCallAction();
                    $electricityMaintenanceCallAction->maintenance_electricity_action_id = 75;
                    $electricityMaintenanceCallAction->electricity_maintenance_call_id = $maintenanceId;
                    $electricityMaintenanceCallAction->save();

                }
            } else if($energyMeter->public_structure_id) {

                $exist = ElectricityMaintenanceCall::where('public_structure_id', $energyMeter->public_structure_id)
                    ->where('date_of_call', $request->visit_date)
                    ->first();

                if($exist) {
                } else {

                    $maintenance->public_structure_id = $energyMeter->public_structure_id;
                    $maintenance->community_id = $energyMeter->community_id;
                    $maintenance->date_of_call = $request->visit_date;
                    $maintenance->maintenance_status_id = 1;
                    $maintenance->user_id = Auth::guard('user')->user()->id;
                    $maintenance->maintenance_type_id = 1;
                    $maintenance->notes = $request->notes;
                    $maintenance->save();

                    $maintenanceId = $maintenance->id;

                    $electricityMaintenanceCallAction = new ElectricityMaintenanceCallAction();
                    $electricityMaintenanceCallAction->maintenance_electricity_action_id = 75;
                    $electricityMaintenanceCallAction->electricity_maintenance_call_id = $maintenanceId;
                    $electricityMaintenanceCallAction->save();
                }
            }
        }
        
        if($request->rcd_x1_phase0 && $request->rcd_x5_phase0 && $request->rcd_x5_phase1  < 30) {

        }

        return redirect('/energy-safety')->with('message', 'Meter Safety Updated Successfully!');
    }
    /**
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteEnergySafety(Request $request)
    {
        $id = $request->id;

        $energySafety = AllEnergyMeterSafetyCheck::find($id);
        $allEnergyMeter = AllEnergyMeter::where("id", $energySafety->all_energy_meter_id)->first();
        if($allEnergyMeter->household_id) {

            $exist = ElectricityMaintenanceCall::where('household_id', $allEnergyMeter->household_id)
                ->where('date_of_call', $energySafety->visit_date)
                ->first();
        } else if($allEnergyMeter->public_structure_id) {

            $exist = ElectricityMaintenanceCall::where('public_structure_id', $allEnergyMeter->public_structure_id)
                ->where('date_of_call', $energySafety->visit_date)
                ->first();
        }
        
        if($exist) {

            $exist->is_archived = 1;
            $exist->save();
        }

        if($energySafety) {

            $energySafety->is_archived = 1;
            $energySafety->save();

            $response['success'] = 1;
            $response['msg'] = 'Energy Safety Deleted Successfully'; 
        } else {

            $response['success'] = 0;
            $response['msg'] = 'Invalid ID.';
        }

        return response()->json($response); 
    }

    /**
     * Get households by community_id.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getInfo(Request $request)
    {
        if($request->publicUser == "user") {

            $energyHolder = AllEnergyMeter::where('household_id', $request->holder_id)->first();
        } else if($request->publicUser == "public") {
            
            $energyHolder = AllEnergyMeter::where('public_structure_id', $request->holder_id)->first();
        }

        if($energyHolder == null) {

            $response['meter_number'] = "No";
           // $response['meter_case'] = null;
            $response['ground_connected'] = null;
        } else {

            $response['meter_number'] = $energyHolder->meter_number;

            // $meter = MeterCase::where('id', $energyHolder->meter_case_id)->first();
            // die($energyHolder);
            // $response['meter_case'] = $meter->meter_case_name_english;

            if($energyHolder->energy_system_type_id == 2) {

                $response['ground_connected'] = $energyHolder->ground_connected;

            } else {

                $response['ground_connected'] = 0;
            }
         
        }
        
        return response()->json($response);
    }

    /**
     * 
     * @return \Illuminate\Support\Collection
     */
    public function export(Request $request) 
    {
                
        return Excel::download(new EnergySafetyExport($request), 'meter_safety_checks.xlsx');
    }
}
