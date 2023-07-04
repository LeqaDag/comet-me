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
use App\Models\Donor;
use App\Models\EnergyDonor;
use App\Models\EnergySystem;
use App\Models\EnergySystemType;
use App\Models\EnergyUser;
use App\Models\EnergyHolder;
use App\Models\EnergyPublicStructure;
use App\Models\Household;
use App\Models\HouseholdMeter;
use App\Models\MeterCase;
use App\Models\ServiceType;
use App\Models\PublicStructure;
use App\Models\PublicStructureCategory;
use App\Exports\EnergyUserExport;
use App\Models\Region;
use App\Models\Vendor;
use App\Models\VendorUserName;
use Carbon\Carbon;
use Image;
use DataTables;
use Excel;

class EnergyUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Auth::guard('user')->user() != null) {

            $holders = DB::table('all_energy_meters')
                ->join('communities', 'all_energy_meters.community_id', '=', 'communities.id')
                ->leftJoin('households', 'all_energy_meters.household_id', '=', 'households.id')
                // ->leftJoin('household_meters', 'all_energy_meters.id', 
                //     '=', 'household_meters.energy_user_id')
                ->leftJoin('public_structures', 'all_energy_meters.public_structure_id', 
                    '=', 'public_structures.id')
                ->join('energy_systems', 'all_energy_meters.energy_system_id', '=', 'energy_systems.id')
                ->join('energy_system_types', 'all_energy_meters.energy_system_type_id', '=', 'energy_system_types.id')
                ->join('meter_cases', 'all_energy_meters.meter_case_id', '=', 'meter_cases.id')
                ->groupBy('communities.english_name', 'energy_system_types.name')
                ->where('all_energy_meters.installation_date', '>', '2023-03-01')
                ->select('all_energy_meters.meter_active',
                    'all_energy_meters.id as id',
                    'communities.english_name as community_name',
                    'communities.id as community_id',
                    'energy_systems.name as energy_name', 
                    'all_energy_meters.installation_date',
                    'energy_system_types.name as energy_type_name',
                    'communities.number_of_household', 'communities.number_of_people')
                //->selectRaw('COUNT("households.id") as number')
                ->get(); 

            if ($request->ajax()) {

                $data = DB::table('all_energy_meters')
                    ->join('communities', 'all_energy_meters.community_id', '=', 'communities.id')
                    ->leftJoin('households', 'all_energy_meters.household_id', '=', 'households.id')
                    ->leftJoin('public_structures', 'all_energy_meters.public_structure_id', 
                        '=', 'public_structures.id')
                    ->join('energy_systems', 'all_energy_meters.energy_system_id', '=', 'energy_systems.id')
                    ->join('energy_system_types', 'all_energy_meters.energy_system_type_id', '=', 'energy_system_types.id')
                    ->join('meter_cases', 'all_energy_meters.meter_case_id', '=', 'meter_cases.id')
                    ->where('all_energy_meters.meter_active', 'Yes')
                    ->select('all_energy_meters.meter_number', 'all_energy_meters.meter_active',
                        'all_energy_meters.id as id', 'all_energy_meters.created_at as created_at', 
                        'all_energy_meters.updated_at as updated_at', 
                        'communities.english_name as community_name',
                        'households.english_name as household_name',
                        'public_structures.english_name as public_name',
                        'energy_systems.name as energy_name', 
                        'energy_system_types.name as energy_type_name',
                        'all_energy_meters.daily_limit')
                    ->latest(); 

                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {

                        $viewButton = "<a type='button' class='viewEnergyUser' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#viewEnergyUserModal' ><i class='fa-solid fa-eye text-info'></i></a>";
                        
                        return $viewButton;
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
                                ->orWhere('all_energy_meters.meter_number', 'LIKE', "%$search%");
                            });
                        }
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }

            $communities = Community::where('is_archived', 0)->get();
            $households = Household::all();
            $energySystems = EnergySystem::all();
            $energySystemTypes = EnergySystemType::all();
            $meters = MeterCase::all();
            $energyUsersNumbers = AllEnergyMeter::where('household_id', '!=', 0)->count();
            
            $energyMgNumbers = AllEnergyMeter::where("energy_system_type_id", 1)
                ->where('household_id', '!=', 0)
                ->where("meter_active", "Yes")
                ->count();
            $energyFbsNumbers = AllEnergyMeter::where("energy_system_type_id", 2)
                ->where('household_id', '!=', 0)
                ->where("meter_active", "Yes")
                ->count();
            $energyMmgNumbers = AllEnergyMeter::where("energy_system_type_id", 3)
                ->where('household_id', '!=', 0)
                ->where("meter_active", "Yes")
                ->count();
            $energySmgNumbers = AllEnergyMeter::where("energy_system_type_id", 4)
                ->where('household_id', '!=', 0)
                ->where("meter_active", "Yes")
                ->count();
            $householdMeterNumbers = HouseholdMeter::count();

            $totalSum = AllEnergyMeter::where("meter_case_id", 1)->sum('daily_limit');
            $donors = Donor::all();
            
            return view('users.energy.index', compact('communities', 'households', 
                'energySystems', 'energySystemTypes', 'meters', 'energyMgNumbers', 
                'energyFbsNumbers', 'energyMmgNumbers', 'energyUsersNumbers',
                'energySmgNumbers', 'householdMeterNumbers', 'totalSum', 'donors',
                'holders'));

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
     
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $energyUser = new EnergyUser();
        $energyUser->meter_number = $request->meter_number;
        $energyUser->community_id = $request->community_id;
        $energyUser->household_id = $request->household_id;
        $energyUser->energy_system_id = $request->energy_system_id;
        $energyUser->energy_system_type_id = $request->energy_system_type_id;
        $energyUser->meter_case_id = $request->meter_case_id;
        $energyUser->installation_date = $request->installation_date;
        $energyUser->daily_limit = $request->daily_limit;
        $energyUser->notes = $request->notes;
        $energyUser->save();

        return redirect()->back()->with('message', 'New User Added Successfully!');
    }

    /**
     * Get resources from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getHouseholdByCommunity($community_id)
    {
        $households = Household::where('community_id', $community_id)
            ->where("household_status_id", 2)
            ->get();
 
        if (!$community_id) {

            $html = '<option value="">Choose One...</option>';
        } else {

            $html = '';
            $households = Household::where('community_id', $community_id)
                ->where("household_status_id", 2)
                ->get();
            foreach ($households as $household) {
                $html .= '<option value="'.$household->id.'">'.$household->english_name.'</option>';
            }
        }

        return response()->json(['html' => $html]);
    }

    /**
     * Get resources from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getEnergySystemByType($energy_type_id, $community_id)
    {
        if($community_id == 0) {

            $energySystems = EnergySystem::where('energy_system_type_id', $energy_type_id)->get();
        } else {

            $energySystems = EnergySystem::where('energy_system_type_id', $energy_type_id)
                ->where('community_id', $community_id)
                ->get();
        }
        
 
        if (!$energy_type_id) {

            $html = '<option value="">Choose One...</option>';
        } else {

            $html = '';
            if($community_id == 0) {

                $energySystems = EnergySystem::where('energy_system_type_id', $energy_type_id)->get();
            } else {
                
                $energySystems = EnergySystem::where('energy_system_type_id', $energy_type_id)
                    ->where('community_id', $community_id)
                    ->get();
            }

            foreach ($energySystems as $energyType) {
                $html .= '<option value="'.$energyType->id.'">'.$energyType->name.'</option>';
            }
        }

        return response()->json(['html' => $html]);
    }

    /**
     * Get resources from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getSharedHousehold($community_id, $user_id)
    {
        $households = Household::where('community_id', $community_id)
            ->where("id", "!=", $user_id)
            ->where("household_status_id", 2)
            ->get();
 
        if (!$community_id) {

            $html = '<option value="">Choose One...</option>';
        } else {

            $html = '';
            $households = Household::where('community_id', $community_id)
                ->where("id", "!=", $user_id)
                ->where("household_status_id", 2)
                ->get();
            foreach ($households as $household) {
                $html .= '<option value="'.$household->id.'">'.$household->english_name.'</option>';
            }
        }

        return response()->json(['html' => $html]);
    }

       /**
     * Get resources from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getMiscCommunity($misc)
    {
        $html = '<option disabled selected>Choose One ...</option>';
        if($misc == 1) {

            $communities = Community::where('is_archived', 0)->get();
        } else if($misc == 0) {

            $communities = Community::where('is_archived', 0)
                ->where('community_status_id', 1)
                ->get();
        }

        foreach ($communities as $community) {
            $html .= '<option value="'.$community->id.'">'.$community->english_name.'</option>';
        }
        

        return response()->json(['html' => $html]);
    }

     /**
     * Show the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    { 
        $energyMeter = AllEnergyMeter::findOrFail($id);

        $energyMeterDonors = DB::table('all_energy_meter_donors')
            ->where('all_energy_meter_donors.all_energy_meter_id', $id)
            ->join('donors', 'all_energy_meter_donors.donor_id', '=', 'donors.id')
            ->select('donors.donor_name', 'all_energy_meter_donors.all_energy_meter_id')
            ->get(); 

        $community = Community::where('id', $energyMeter->community_id)->first();

        $household = Household::where('id', $energyMeter->household_id)->first();
        $public = PublicStructure::where('id', $energyMeter->public_structure_id)->first();
        $meter = MeterCase::where('id', $energyMeter->meter_case_id)->first();
        $systemType = EnergySystemType::where('id', $energyMeter->energy_system_type_id)->first();
        $system = EnergySystem::where('id', $energyMeter->energy_system_id)->first();
        $householdMeters = HouseholdMeter::where("energy_user_id", $id)->get();
        $vendor = VendorUserName::where('id', $energyMeter->vendor_username_id)->first();

        $response['energy'] = $energyMeter;
        $response['energyMeterDonors'] = $energyMeterDonors;
        $response['community'] = $community;
        $response['household'] = $household;
        $response['meter'] = $meter;
        $response['type'] = $systemType;
        $response['system'] = $system;
        $response['householdMeters'] = $householdMeters;
        $response['public'] = $public;
        $response['vendor'] = $vendor;

        return response()->json($response);
    }

    /**
     * Get households by community_id.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getByHousehold(Request $request)
    {
        $energyUser = AllEnergyMeter::where('household_id', $request->household_id)->first();

        if($energyUser == null) {

            $response['meter_number'] = "No";
        } else {

            $response['meter_number'] = $energyUser->meter_number;
        }
        

        return response()->json($response);
    }

    /**
     * 
     * @return \Illuminate\Support\Collection
     */
    public function export(Request $request) 
    {

        return Excel::download(new EnergyUserExport($request), 'energy_meters.xlsx');
    }

    /**
     * Get resources from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getEnergyUserByCommunity($community_id)
    {
        $households = DB::table('all_energy_meters')
            ->join('households', 'all_energy_meters.household_id', '=', 'households.id')
            ->where("households.community_id", $community_id)
            ->select('households.id', 'households.english_name')
            ->get();
 
        if (!$community_id) {

            $html = '<option value="">Choose One...</option>';
        } else {

            $html = '<option value="">Select...</option>';
            $households = DB::table('all_energy_meters')
                ->join('households', 'all_energy_meters.household_id', '=', 'households.id')
                ->where("households.community_id", $community_id)
                ->select('households.id', 'households.english_name')
                ->get();
            foreach ($households as $household) {
                $html .= '<option value="'.$household->id.'">'.$household->english_name.'</option>';
            }
        }

        return response()->json(['html' => $html]);
    }

    /**
     * Get resources from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getPublicByCommunity($community_id)
    {
        $publics = DB::table('all_energy_meters')
            ->join('public_structures', 'all_energy_meters.public_structure_id', '=', 'public_structures.id')
            ->where("all_energy_meters.community_id", $community_id)
            ->select('public_structures.id', 'public_structures.english_name')
            ->get();
      
        if (!$community_id) {

            $html = '<option value="">Choose One...</option>';
        } else {

            $html = '<option value="">Select...</option>';
            $publics = DB::table('all_energy_meters')
                ->join('public_structures', 'all_energy_meters.public_structure_id', '=', 'public_structures.id')
                ->where("all_energy_meters.community_id", $community_id)
                ->select('public_structures.id', 'public_structures.english_name')
                ->get();

            foreach ($publics as $public) {
                $html .= '<option value="'.$public->id.'">'.$public->english_name.'</option>';
            }
        }

        return response()->json(['html' => $html]);
    }
}