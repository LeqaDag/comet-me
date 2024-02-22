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
use App\Models\CommunityDonor;
use App\Models\CometMeter;
use App\Models\CometMeterDonor;
use App\Models\Donor;
use App\Models\EnergyDonor;
use App\Models\EnergySystem;
use App\Models\EnergySystemType;
use App\Models\EnergyUser;
use App\Models\EnergyHolder;
use App\Models\EnergyPublicStructure;
use App\Models\EnergyPublicStructureDonor;
use App\Models\InstallationType;
use App\Models\Household;
use App\Models\HouseholdMeter;
use App\Models\MeterCase;
use App\Models\ServiceType;
use App\Models\PublicStructure;
use App\Models\PublicStructureCategory;
use App\Models\Region;
use App\Models\VendorUserName;
use App\Exports\CometMeters;
use Carbon\Carbon;
use Image;
use DataTables;
use Excel;

class EnergyCometMeterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $cometMeters = CometMeterDonor::all();
 
        // foreach($cometMeters as $cometMeter) {

        //     $comet = CometMeter::where("name", $cometMeter->comet_meter_name)->first();
        //     $allEnergyMeter = AllEnergyMeter::where("meter_number", $comet->meter_number)->first();
        //     $exist = AllEnergyMeterDonor::where("all_energy_meter_id", $allEnergyMeter->id)->first();

        //     if($exist) {

        //     } else {

        //         $cometMeterDonor = new AllEnergyMeterDonor();
        //         $cometMeterDonor->all_energy_meter_id = $allEnergyMeter->id;
        //         $cometMeterDonor->community_id = $allEnergyMeter->community_id;
        //         $cometMeterDonor->donor_id = $cometMeter->donor_id;
        //         $cometMeterDonor->save();
        //     }
        // }
        
        if (Auth::guard('user')->user() != null) {

            if ($request->ajax()) {

                $dataPublic = DB::table('all_energy_meters')
                    ->join('public_structures', 'all_energy_meters.public_structure_id', '=', 'public_structures.id')
                    ->join('communities', 'public_structures.community_id', '=', 'communities.id')
                    ->join('energy_systems', 'all_energy_meters.energy_system_id', '=', 'energy_systems.id')
                    ->join('energy_system_types', 'all_energy_meters.energy_system_type_id', '=', 'energy_system_types.id')
                    ->join('meter_cases', 'all_energy_meters.meter_case_id', '=', 'meter_cases.id')
                    ->where('all_energy_meters.is_archived', 0)
                    ->where('public_structures.comet_meter', 1)
                    ->select('all_energy_meters.meter_number', 
                        'all_energy_meters.id as id', 'all_energy_meters.created_at as created_at', 
                        'all_energy_meters.updated_at as updated_at', 
                        'communities.english_name as community_name',
                        'public_structures.english_name as public_name',
                        'energy_systems.name as energy_name', 
                        'energy_system_types.name as energy_type_name',)
                    ->latest(); 
    
                 
                return Datatables::of($dataPublic)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
    
                        $viewButton = "<a type='button' class='viewCometMeterUser' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#viewEnergyCometModal' ><i class='fa-solid fa-eye text-info'></i></a>";
                        $updateButton = "<a type='button' class='updateEnergyComet' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#updateEnergyUserModal' ><i class='fa-solid fa-pen-to-square text-success'></i></a>";
                        $deleteButton = "<a type='button' class='deleteEnergyComet' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";
                        
                            if(Auth::guard('user')->user()->user_type_id == 1 || 
                                Auth::guard('user')->user()->user_type_id == 2 ||
                                Auth::guard('user')->user()->user_type_id == 3 ||
                                Auth::guard('user')->user()->user_type_id == 4 ||
                                Auth::guard('user')->user()->user_type_id == 12) 
                            {
                                    
                                return $viewButton." ". $updateButton." ".$deleteButton;
                            } else return $viewButton;
       
                    })
                    ->filter(function ($instance) use ($request) {
                        if (!empty($request->get('search'))) {
                                $instance->where(function($w) use($request) {
                                $search = $request->get('search');
                                $w->orWhere('communities.english_name', 'LIKE', "%$search%")
                                ->orWhere('communities.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('energy_systems.name', 'LIKE', "%$search%")
                                ->orWhere('energy_system_types.name', 'LIKE', "%$search%")
                                ->orWhere('public_structures.english_name', 'LIKE', "%$search%")
                                ->orWhere('public_structures.meter_number', 'LIKE', "%$search%");
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
            $energySystems = EnergySystem::where('is_archived', 0)->get();
            $energySystemTypes = EnergySystemType::where('is_archived', 0)->get();
            $meters = MeterCase::where('is_archived', 0)->get();
            $installationTypes = InstallationType::where('is_archived', 0)->get();
            
            return view('users.energy.comet.index', compact('communities', 'households',
                'energySystems', 'energySystemTypes', 'meters', 'installationTypes'));
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
        $cometMeter = new AllEnergyMeter();
        $cometMeter->installation_type_id = $request->installation_type_id;
        $cometMeter->community_id = $request->community_id;
        $cometMeter->meter_number = $request->meter_number;
        $cometMeter->public_structure_id = $request->public_structure_id;
        $cometMeter->energy_system_id = $request->energy_system_id;
        $cometMeter->energy_system_type_id = $request->energy_system_type_id;
        $cometMeter->meter_case_id = $request->meter_case_id;
        $cometMeter->installation_date = $request->installation_date;
        $cometMeter->daily_limit = $request->daily_limit;
        $cometMeter->notes = $request->notes;
        $cometMeter->save();

        return redirect()->back()->with('message', 'New Comet Meter Added Successfully!');
    }

    /**
     * Show the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $energyPublic = AllEnergyMeter::findOrFail($id);
        $energyMeterDonors = DB::table('all_energy_meter_donors')
            ->where('all_energy_meter_donors.is_archived', 0)
            ->where('all_energy_meter_donors.all_energy_meter_id', $id)
            ->join('donors', 'all_energy_meter_donors.donor_id', '=', 'donors.id')
            ->select('donors.donor_name', 'all_energy_meter_donors.all_energy_meter_id')
            ->get();

        $community = Community::where('id', $energyPublic->community_id)->first();
        $public = PublicStructure::where('id', $energyPublic->public_structure_id)->first();
        $meter = MeterCase::where('id', $energyPublic->meter_case_id)->first();
        $systemType = EnergySystemType::where('id', $energyPublic->energy_system_type_id)->first();
        $system = EnergySystem::where('id', $energyPublic->energy_system_id)->first();
        $installationType = InstallationType::where('id', $energyPublic->installation_type_id)->first();
        $vendor = DB::table('community_vendors')
            ->where('community_id', $energyPublic->community_id)
            ->where('community_vendors.is_archived', 0)
            ->join('vendor_user_names', 'community_vendors.vendor_username_id', 
                'vendor_user_names.id')
            ->select('vendor_user_names.name')
            ->first();

        $response['energyPublic'] = $energyPublic;
        $response['energyMeterDonors'] = $energyMeterDonors;
        $response['community'] = $community;
        $response['public'] = $public;
        $response['meter'] = $meter;
        $response['type'] = $systemType;
        $response['system'] = $system;
        $response['installationType'] = $installationType;
        $response['vendor'] = $vendor;

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
        $energyPublic = AllEnergyMeter::findOrFail($id);

        return response()->json($energyPublic);
    } 

    /**
     * View Edit page.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) 
    {
        $energyPublic = AllEnergyMeter::findOrFail($id);
        $energyDonors = AllEnergyMeterDonor::where("all_energy_meter_id", $id)->get();
        $community_id = Community::findOrFail($energyPublic->community_id);
        $communities = Community::where('is_archived', 0)
            ->orderBy('english_name', 'ASC')
            ->get();
        $communityVendors = DB::table('community_vendors')
            //->where('community_id', $community_id->id)
            ->where('community_vendors.is_archived', 0)
            ->join('vendor_user_names', 'community_vendors.vendor_username_id', 
                '=', 'vendor_user_names.id')
            ->select('vendor_user_names.name', 'community_vendors.id as id',
                'vendor_user_names.id as vendor_username_id')
            ->groupBy('vendor_user_names.id')
            ->get();

        $publicStructures = PublicStructure::findOrFail($energyPublic->public_structure_id);
        $meterCases = MeterCase::where('is_archived', 0)->get();
        $vendor = VendorUserName::where('id', $energyPublic->vendor_username_id)->first();

        $energySystems = EnergySystem::where('is_archived', 0)->get();
        $donors = Donor::where('is_archived', 0)->get();

        $installationTypes = InstallationType::where('is_archived', 0)->get();

        return view('users.energy.comet.edit', compact('publicStructures', 'communities',
            'meterCases', 'energyPublic', 'communityVendors', 'vendor', 'energySystems',
            'energyDonors', 'donors', 'installationTypes'));
    }
    
    /**
     * Update an existing resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request, int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
       // dd($request->all());
        $energyPublic = AllEnergyMeter::find($id);

        $energyPublic->meter_number = $request->meter_number;
        $energyPublic->daily_limit = $request->daily_limit;
        $energyPublic->installation_date = $request->installation_date;
        if($request->installation_type_id) $energyPublic->installation_type_id = $request->installation_type_id;

        if($request->meter_active) $energyPublic->meter_active = $request->meter_active;

        if($request->vendor_username_id) $energyPublic->vendor_username_id = $request->vendor_username_id;

        if($request->energy_system_id) $energyPublic->energy_system_id = $request->energy_system_id;

        if($request->notes) $energyPublic->notes = $request->notes;


        if($request->meter_case_id == 1 || $request->meter_case_id == 2 ||
            $request->meter_case_id == 3 || $request->meter_case_id == 4 ||
            $request->meter_case_id == 5 || $request->meter_case_id == 6 ||
            $request->meter_case_id == 7 || $request->meter_case_id == 8 ||
            $request->meter_case_id == 9 || $request->meter_case_id == 10 ||
            $request->meter_case_id == 11 || $request->meter_case_id == 12 ||
            $request->meter_case_id == 13 || $request->meter_case_id == 14) 
        {

            if($request->meter_case_id == 1) {
                $household = Household::findOrFail($energyPublic->household_id);
                $household->household_status_id = 4;
                $household->energy_service = "Yes";
                $household->energy_meter = "Yes";
                $household->save();
            }

            $energyPublic->meter_case_id = $request->meter_case_id;
        }

        $energyPublic->save(); 

        if($request->donors) {
            for($i=0; $i < count($request->donors); $i++) {

                $energyMeterDonor = new AllEnergyMeterDonor();
                $energyMeterDonor->donor_id = $request->donors[$i];
                $energyMeterDonor->all_energy_meter_id = $id;
                $energyMeterDonor->community_id = $energyPublic->community_id;
                $energyMeterDonor->save();
            }
        }

        if($request->new_donors) {
            for($i=0; $i < count($request->new_donors); $i++) {

                $energyMeterDonor = new AllEnergyMeterDonor();
                $energyMeterDonor->donor_id = $request->new_donors[$i];
                $energyMeterDonor->all_energy_meter_id = $id;
                $energyMeterDonor->community_id = $energyPublic->community_id;
                $energyMeterDonor->save();
            }
        }

        return redirect('/comet-meter')->with('message', 'Energy Comet Meter Updated Successfully!');
    }

    /**
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteCometMeter(Request $request)
    {
        $id = $request->id;

        $cometMeter = AllEnergyMeter::find($id);

        if($cometMeter) {

            $cometMeter->is_archived = 1;
            $cometMeter->save();
            
            $response['success'] = 1;
            $response['msg'] = 'Comet Meter Delete successfully'; 
        } else {

            $response['success'] = 0;
            $response['msg'] = 'Invalid ID.';
        }

        return response()->json($response); 
    }

    /**
     * 
     * @return \Illuminate\Support\Collection
     */
    public function export(Request $request) 
    {

        return Excel::download(new CometMeters($request), 'energy_comet_meters.xlsx');
    }
}
