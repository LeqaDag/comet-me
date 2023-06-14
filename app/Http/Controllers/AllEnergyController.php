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
use App\Models\CommunityVendor;
use App\Models\Donor;
use App\Models\EnergyDonor;
use App\Models\EnergySystem;
use App\Models\EnergySystemType;
use App\Models\EnergyUser;
use App\Models\EnergyHolder;
use App\Models\EnergyPublicStructure;
use App\Models\EnergyPublicStructureDonor;
use App\Models\Household;
use App\Models\HouseholdMeter;
use App\Models\MeterCase;
use App\Models\ServiceType;
use App\Models\PublicStructure;
use App\Models\PublicStructureCategory;
use App\Models\Region;
use App\Models\VendorUsername;
use App\Exports\AllEnergyExport;
use Carbon\Carbon;
use Image;
use DataTables;
use Excel;

class AllEnergyController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $energyMeters = AllEnergyMeter::where("community_id", 1)
        //     ->get();

        // foreach($energyMeters as $energyMeter) {

        //     $energyMeter->meter_active = "Yes";
        //     $energyMeter->installation_date = "2022-11-30";
        //     $energyMeter->meter_case_id = 1;
        //     $energyMeter->save();
        // }
        if (Auth::guard('user')->user() != null) {

            if ($request->ajax()) {

                $data = DB::table('all_energy_meters')
                    ->join('communities', 'all_energy_meters.community_id', '=', 'communities.id')
                    ->join('households', 'all_energy_meters.household_id', '=', 'households.id')
                    ->join('energy_systems', 'all_energy_meters.energy_system_id', '=', 'energy_systems.id')
                    ->join('energy_system_types', 'all_energy_meters.energy_system_type_id', '=', 'energy_system_types.id')
                    ->join('meter_cases', 'all_energy_meters.meter_case_id', '=', 'meter_cases.id')
                    ->select('all_energy_meters.meter_number', 'all_energy_meters.meter_active',
                        'all_energy_meters.id as id', 'all_energy_meters.created_at as created_at', 
                        'all_energy_meters.updated_at as updated_at', 
                        'communities.english_name as community_name',
                        'households.english_name as household_name',
                        'energy_systems.name as energy_name', 
                        'energy_system_types.name as energy_type_name',
                        'meter_cases.meter_case_name_english')
                    ->latest(); 
    
                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
                        $viewButton = "<a type='button' class='viewEnergyUser' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#viewEnergyUserModal' ><i class='fa-solid fa-eye text-info'></i></a>";
                        $updateButton = "<a type='button' class='updateAllEnergyUser' data-id='".$row->id."' ><i class='fa-solid fa-pen-to-square text-success'></i></a>";
                        $deleteButton = "<a type='button' class='deleteAllEnergyUser' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";
                        
                        return $viewButton." ". $updateButton." ".$deleteButton;
       
                    })
                    ->filter(function ($instance) use ($request) {
                        if (!empty($request->get('search'))) {
                                $instance->where(function($w) use($request) {
                                $search = $request->get('search');
                                $w->orWhere('communities.english_name', 'LIKE', "%$search%")
                                ->orWhere('households.english_name', 'LIKE', "%$search%")
                                ->orWhere('communities.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('households.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('energy_systems.name', 'LIKE', "%$search%")
                                ->orWhere('energy_system_types.name', 'LIKE', "%$search%")
                                ->orWhere('all_energy_meters.meter_number', 'LIKE', "%$search%")
                                ->orWhere('meter_cases.meter_case_name_english', 'LIKE', "%$search%")
                                ->orWhere('meter_cases.meter_case_name_arabic', 'LIKE', "%$search%");
                            });
                        }
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            
            $data = DB::table('all_energy_meters')
                ->join('meter_cases', 'all_energy_meters.meter_case_id', '=', 'meter_cases.id')
                ->where('meter_cases.meter_case_name_english', '!=', "Used")
                ->where('household_id', '!=', 0)
                ->select(
                        DB::raw('meter_cases.meter_case_name_english as name'),
                        DB::raw('count(*) as number'))
                ->groupBy('meter_cases.meter_case_name_english')
                ->get();
    
              
            $array[] = ['Meter Case', 'Total'];
            
            foreach($data as $key => $value) {
    
                $array[++$key] = [$value->name, $value->number];
            }
    
            $communities = Community::where('is_archived', 0)->get();
            $meterCases = MeterCase::get();
            $energySystemTypes = EnergySystemType::all();
    
            return view('users.energy.not_active.index', compact('communities', 'energySystemTypes', 
                'meterCases'))
                ->with('energy_users', json_encode($array)
            );
        } else {

            return view('errors.not-found');
        }
    }

    /**
     * Get a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getEnergyUserData(int $id)
    {
        $energy = AllEnergyMeter::find($id);
        $meterCase = MeterCase::where("id", $energy->meter_case_id)->first();
        $response = array();

        if(!empty($energy)) {

            $response['meter_number'] = $energy->meter_number;
            $response['daily_limit'] = $energy->daily_limit;
            $response['installation_date'] = $energy->installation_date;
            $response['notes'] = $energy->notes;
            $response['meter_active'] = $energy->meter_active;
            $response['meter_case_id'] = $meterCase->meter_case_name_english;
            $response['id'] = $energy->id;

            $response['success'] = 1;
        } else {

            $response['success'] = 0;
        }

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
        $energyUser = AllEnergyMeter::findOrFail($id);

        return response()->json($energyUser);
    }

    /**
     * View Edit page.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $energyUser = AllEnergyMeter::findOrFail($id);
        $energyDonors = AllEnergyMeterDonor::where("all_energy_meter_id", $id)->get();
        $community_id = Community::findOrFail($energyUser->community_id);
        $communities = Community::where('is_archived', 0)->get();
        $communityVendors = DB::table('community_vendors')
            ->where('community_id', $community_id->id)
            ->join('vendor_usernames', 'community_vendors.vendor_username_id', 
                '=', 'vendor_usernames.id')
            ->select('vendor_usernames.name', 'community_vendors.id as id',
                'vendor_usernames.id as vendor_username_id')
            ->get();
        
        $energySystems = EnergySystem::all();
        $household = Household::findOrFail($energyUser->household_id);
        $meterCases = MeterCase::all();
        $vendor = VendorUsername::where('id', $energyUser->vendor_username_id)->first();
        $donors = Donor::all();

        return view('users.energy.not_active.edit_energy', compact('household', 'communities',
            'meterCases', 'energyUser', 'communityVendors', 'vendor', 'energySystems',
            'energyDonors', 'donors'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateEnergyDonorData(Request $request) 
    {
        $energyDonors = DB::table('energy_donors')
            ->join('communities', 'energy_donors.community_id', '=', 'communities.id')
            ->join('households', 'energy_donors.household_id', '=', 'households.id')
            ->join('donors', 'energy_donors.donor_id', '=', 'donors.id')
            ->where('energy_donors.household_id', $energyUser->household_id)
            ->select('energy_donors.id as id', 'communities.english_name as community_name',
                'households.english_name as household_name',
                'donors.donor_name as donor_name', 'energy_donors.household_id',
                'donors.id as donor_id', 'energy_donors.community_id')
            ->get();
        
        // if($request->donor_id) {
        //     foreach($request->donor_id as $donorId) {
        //         $donorUser = EnergyDonor::findOrFail($energyDonor->id);
        //         $donorUser->donor_id = $donorId;
        //         $donorUser->save();
        //     }
        // }

        if($request->donors) {
            foreach($request->donors as $donorId) {
                EnergyDonor::create([
                    'donor_id' => $donorId,
                    'community_id' => $energyUser->community_id,
                    'household_id' => $energyUser->household_id,
                ]);
            }
        }

        return response()->json(['success'=> 'Energy User updated successfully!']);
    }

    /**
     * Get a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getEnergyUserDonors(int $id)
    {
        $energyDonors = AllEnergyMeterDonor::where("all_energy_meter_id", $id)->get();

        return response()->json($energyDonors);
    }

    /**
     * View Edit page.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function editDonor($id)
    {
        $energyDonors = AllEnergyMeterDonor::where("all_energy_meter_id", $id);

        return view('users.energy.not_active.donor_edit', compact('energyDonors'));
    }

    /**
     * Update an existing resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request, int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $energyUser = AllEnergyMeter::find($id);

        $energyUser->meter_number = $request->meter_number;
        $energyUser->daily_limit = $request->daily_limit;
        $energyUser->installation_date = $request->installation_date;
        if($request->misc) $energyUser->misc = $request->misc;
      
        if($request->meter_active) $energyUser->meter_active = $request->meter_active;

        if($request->vendor_username_id) $energyUser->vendor_username_id = $request->vendor_username_id;

        if($request->energy_system_id) $energyUser->energy_system_id = $request->energy_system_id;

        if($request->meter_case_id == 1 || $request->meter_case_id == 2 ||
            $request->meter_case_id == 3 || $request->meter_case_id == 4 ||
            $request->meter_case_id == 5 || $request->meter_case_id == 6 ||
            $request->meter_case_id == 7 || $request->meter_case_id == 8 ||
            $request->meter_case_id == 9 || $request->meter_case_id == 10 ||
            $request->meter_case_id == 11 || $request->meter_case_id == 12 ||
            $request->meter_case_id == 13 || $request->meter_case_id == 14) 
        {

            if($request->meter_case_id == 1) {
                $household = Household::findOrFail($energyUser->household_id);
                $household->household_status_id = 4;
                $household->energy_service = "Yes";
                $household->energy_meter = "Yes";
                $household->save();
            }

            $energyUser->meter_case_id = $request->meter_case_id;
        }

        $energyUser->save(); 
        
        if($energyUser->meter_active == "Yes" || $energyUser->meter_case_id == 1) {

            $householdMeters = HouseholdMeter::where('energy_user_id', $energyUser->id)->get();

            if($householdMeters != []) {
                foreach($householdMeters as $householdMeter) {

                    $household = Household::findOrFail($householdMeter->household_id);
                    $household->household_status_id = 4;
                    $household->save();
                }
            } 
        }

        if($request->donors) {
            for($i=0; $i < count($request->donors); $i++) {

                $energyMeterDonor = new AllEnergyMeterDonor();
                $energyMeterDonor->donor_id = $request->donors[$i];
                $energyMeterDonor->all_energy_meter_id = $id;
                $energyMeterDonor->community_id = $energyUser->community_id;
                $energyMeterDonor->save();
            }
        }

        if($request->new_donors) {
            for($i=0; $i < count($request->new_donors); $i++) {

                $energyMeterDonor = new AllEnergyMeterDonor();
                $energyMeterDonor->donor_id = $request->new_donors[$i];
                $energyMeterDonor->all_energy_meter_id = $id;
                $energyMeterDonor->community_id = $energyUser->community_id;
                $energyMeterDonor->save();
            }
        }

        return redirect('/all-meter')->with('message', 'Energy User Updated Successfully!');
    }

    /**
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteEnergyUser(Request $request)
    {
        $id = $request->id;

        $user = AllEnergyMeter::find($id);
        $sharedMeters = HouseholdMeter::where("energy_user_id", $id)->get();

        if($sharedMeters) {
            foreach($sharedMeters as $sharedMeter) {
                $sharedMeter->delete();
            }
        }

        if($user->delete()) {

            $response['success'] = 1;
            $response['msg'] = 'Energy User Deleted successfully'; 
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

        return Excel::download(new AllEnergyExport($request), 'energy_meters_summary.xlsx');
    }
}
