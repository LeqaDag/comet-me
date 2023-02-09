<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use DB;
use Route;
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
use App\Models\Region;
use Carbon\Carbon;
use Image;
use DataTables;

class EnergyUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $energyDonors = EnergyDonor::get();
        // foreach($energyDonors as $energyDonor) {
        //     $household = Household::where("english_name", $energyDonor->household_name)
        //     ->first();
        //     $energyDonor->household_id = $household->id;

        //     $community = Community::where("english_name", $energyDonor->community_name)
        //     ->first();
        //     $energyDonor->community_id = $community->id;

        //     $donor = Donor::where("donor_name", $energyDonor->donor_energy_name)
        //     ->first();
        //     $energyDonor->donor_id = $donor->id;

        //     $energyDonor->save();
        // }

        // $households = Household::where("community_id", 126)
        //     ->orWhere("community_id", 8)
        //     ->orWhere("community_id", 7)
        //     ->orWhere("community_id", 9)
        //     ->orWhere("community_id", 10)
        //     ->orWhere("community_id", 11)
        //     ->orWhere("community_id", 12)
        //     ->orWhere("community_id", 13)
        //     ->orWhere("community_id", 14)
        //     ->orWhere("community_id", 15)
        //     ->get();

        // foreach($households as $household) {
        //     $household->energy_meter = "No";
        //     $household->energy_service = "No";
        //     $household->save();
        // }


        // $householdsUsers = EnergyUser::where("community_id", 126)
        //     ->orWhere("community_id", 8)
        //     ->orWhere("community_id", 7)
        //     ->orWhere("community_id", 9)
        //     ->orWhere("community_id", 10)
        //     ->orWhere("community_id", 11)
        //     ->orWhere("community_id", 12)
        //     ->orWhere("community_id", 13)
        //     ->orWhere("community_id", 14)
        //     ->orWhere("community_id", 15)
        //     ->get();

        // foreach($householdsUsers as $household) {
        //     $household->meter_active = "No";
        //     $household->save();
        // }


        // $householdsMeters = EnergyUser::get();

        // foreach($householdsMeters as $householdMeter) {
        //     $household = Household::where("id", $householdMeter->household_id)
        //     ->first();

        //     $household->energy_meter = $householdMeter->meter_active;
        //     $household->save();
        // }

        // $numberOfMeterActiveNo = Household::where("energy_meter", "Yes")
        // ->count();
        // dd($numberOfMeterActiveNo);

        // $energyUsers = EnergyUser::all();
        // foreach($energyUsers as $energyUser) {

        //     $community = Community::where('english_name', $energyUser->community_name)
        //     ->first();
        //     $household = Household::where('english_name', $energyUser->household_name)
        //     ->first();
        //     $energySystem = EnergySystem::where('name', $energyUser->energy_system)
        //     ->first();
        //     $energySystemType = EnergySystemType::where('name', $energyUser->energy_system_type)
        //     ->first();
        //     $meter = MeterCase::where('meter_case_name_english', $energyUser->meter_case_name)
        //     ->first();

        //     $energyUser->community_id = $community->id;
        //     $energyUser->household_id = $household->id;
        //     $energyUser->energy_system_id = $energySystem->id;
        //     $energyUser->energy_system_type_id = $energySystemType->id;
        //     $energyUser->meter_case_id = $meter->id;
        //     $energyUser->save();
        // }

        // $energyPublics = EnergyPublicStructure::all();
        // foreach($energyPublics as $energyPublic) {

        //     $public = PublicStructure::where('english_name', $energyPublic->public_structure_name)
        //     ->first();
        //     $energySystem = EnergySystem::where('name', $energyPublic->energy_system)
        //     ->first();
        //     $energySystemType = EnergySystemType::where('name', $energyPublic->energy_system_type)
        //     ->first();
        //     $meter = MeterCase::where('meter_case_name_english', $energyPublic->meter_case_name)
        //     ->first();

        //     $energyPublic->public_structure_id = $public->id;
        //     $energyPublic->energy_system_id = $energySystem->id;
        //     $energyPublic->energy_system_type_id = $energySystemType->id;
        //     $energyPublic->meter_case_id = $meter->id;
        //     $energyPublic->save();
        // }

        if ($request->ajax()) {

            $data = DB::table('energy_users')
                ->join('communities', 'energy_users.community_id', '=', 'communities.id')
                ->join('households', 'energy_users.household_id', '=', 'households.id')
                ->join('energy_systems', 'energy_users.energy_system_id', '=', 'energy_systems.id')
                ->join('energy_system_types', 'energy_users.energy_system_type_id', '=', 'energy_system_types.id')
                ->join('meter_cases', 'energy_users.meter_case_id', '=', 'meter_cases.id')
                ->select('energy_users.meter_number', 'energy_users.meter_active',
                    'energy_users.id as id', 'energy_users.created_at as created_at', 
                    'energy_users.updated_at as updated_at', 
                    'communities.english_name as community_name',
                    'households.english_name as household_name',
                    'energy_systems.name as energy_name', 
                    'energy_system_types.name as energy_type_name',)
                ->latest(); 

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row) {

                    $updateButton = "<a type='button' class='updateEnergyUser' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#updateEnergyUserModal' ><i class='fa-solid fa-pen-to-square text-success'></i></a>";
                    $deleteButton = "<a type='button' class='deleteEnergyUser' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";
                    
                    return $updateButton." ".$deleteButton;
   
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
                            ->orWhere('energy_users.meter_number', 'LIKE', "%$search%");
                        });
                    }
                })
                ->rawColumns(['action'])
                ->make(true);

            // $dataPublic = DB::table('energy_public_structures')
            //     ->join('public_structures', 'energy_public_structures.public_structure_id ', '=', 'public_structures.id')
            //     ->join('communities', 'public_structures.community_id', '=', 'communities.id')
            //     ->join('energy_systems', 'energy_public_structures.energy_system_id', '=', 'energy_systems.id')
            //     ->join('energy_system_types', 'energy_public_structures.energy_system_type_id', '=', 'energy_system_types.id')
            //     ->join('meter_cases', 'energy_public_structures.meter_case_id', '=', 'meter_cases.id')
            //     ->select('energy_public_structures.meter_number', 'energy_public_structures.meter_active',
            //         'energy_public_structures.id as id', 'energy_public_structures.created_at as created_at', 
            //         'energy_public_structures.updated_at as updated_at', 
            //         'communities.english_name as community_name',
            //         'public_structures.english_name as public_name',
            //         'energy_systems.name as energy_name', 
            //         'energy_system_types.name as energy_type_name',)
            //     ->latest(); 

             
            // return Datatables::of($dataPublic)
            //     ->addIndexColumn()
            //     ->addColumn('action', function($row) {

            //         $updateButton = "<button class='btn btn-sm btn-info updateEnergyUser' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#updateEnergyUserModal' ><i class='fa-solid fa-pen-to-square'></i></button>";
            //         $deleteButton = "<button class='btn btn-sm btn-danger deleteEnergyUser' data-id='".$row->id."'><i class='fa-solid fa-trash'></i></button>";
                    
            //         return $updateButton." ".$deleteButton;
   
            //     })
            //     ->filter(function ($instance) use ($request) {
            //         if (!empty($request->get('search'))) {
            //                 $instance->where(function($w) use($request) {
            //                 $search = $request->get('search');
            //                 $w->orWhere('communities.english_name', 'LIKE', "%$search%")
            //                 ->orWhere('households.english_name', 'LIKE', "%$search%")
            //                 ->orWhere('communities.arabic_name', 'LIKE', "%$search%")
            //                 ->orWhere('households.arabic_name', 'LIKE', "%$search%")
            //                 ->orWhere('energy_systems.name', 'LIKE', "%$search%")
            //                 ->orWhere('energy_system_types.name', 'LIKE', "%$search%")
            //                 ->orWhere('energy_users.meter_number', 'LIKE', "%$search%");
            //             });
            //         }
            //     })
            //     ->rawColumns(['action'])
            //     ->make(true);
        }

        $communities = Community::all();
        $households = Household::all();
        $energySystems = EnergySystem::all();
        $energySystemTypes = EnergySystemType::all();
        $meters = MeterCase::all();
        $energyUsersNumbers = EnergyUser::count();
        $energyMgNumbers = EnergyUser::where("energy_system_type_id", 1)
            ->where("meter_active", "Yes")
            ->count();
        $energyFbsNumbers = EnergyUser::where("energy_system_type_id", 2)
            ->where("meter_active", "Yes")
            ->count();
        $energyMmgNumbers = EnergyUser::where("energy_system_type_id", 3)
            ->where("meter_active", "Yes")
            ->count();
        $energySmgNumbers = EnergyUser::where("energy_system_type_id", 4)
            ->where("meter_active", "Yes")
            ->count();
        $householdMeterNumbers = HouseholdMeter::count();

        $schools = DB::table('energy_public_structures')
            ->join('public_structures', 'energy_public_structures.public_structure_id', 
                '=', 'public_structures.id')
            ->join('public_structure_categories', 'public_structures.public_structure_category_id1', 
                '=', 'public_structure_categories.id')
            ->where('public_structures.public_structure_category_id1', 1)
            ->orWhere('public_structures.public_structure_category_id2', 1)
            ->orWhere('public_structures.public_structure_category_id3', 1)
            ->count();

        $clinics = DB::table('energy_public_structures')
            ->join('public_structures', 'energy_public_structures.public_structure_id', 
                '=', 'public_structures.id')
            ->join('public_structure_categories', 'public_structures.public_structure_category_id1', 
                '=', 'public_structure_categories.id')
            ->where('public_structures.public_structure_category_id1', 3)
            ->orWhere('public_structures.public_structure_category_id2', 3)
            ->orWhere('public_structures.public_structure_category_id3', 3)
            ->count();
     

        $data = DB::table('energy_users')
            ->join('meter_cases', 'energy_users.meter_case_id', '=', 'meter_cases.id')
            ->where('meter_cases.meter_case_name_english', '!=', "Used")
            ->select(
                    DB::raw('meter_cases.meter_case_name_english as name'),
                    DB::raw('count(*) as number'))
            ->groupBy('meter_cases.meter_case_name_english')
            ->get();

          
        $array[] = ['Meter Case', 'Total'];
        
        foreach($data as $key => $value) {

            $array[++$key] = [$value->name, $value->number];
        }
      

        $dataPublicStructures = DB::table('energy_public_structures')
            ->join('meter_cases', 'energy_public_structures.meter_case_id', '=', 'meter_cases.id')
            //->where('meter_cases.meter_case_name_english', '!=', "Used")
            ->select(
                    DB::raw('meter_cases.meter_case_name_english as name'),
                    DB::raw('count(*) as number'))
            ->groupBy('meter_cases.meter_case_name_english')
            ->get();

          
        $arrayPublicStructures[] = ['Meter Case', 'Total'];
        
        foreach($dataPublicStructures as $key => $value) {

            $arrayPublicStructures[++$key] = [$value->name, $value->number];
        }

        
        return view('users.energy.index', compact('communities', 'households', 
            'energySystems', 'energySystemTypes', 'meters', 'energyMgNumbers', 
            'energyFbsNumbers', 'energyMmgNumbers', 'energyUsersNumbers',
            'energySmgNumbers', 'householdMeterNumbers',))
            ->with('energy_users', json_encode($array))
            ->with('energy_public_structures', json_encode($arrayPublicStructures)
        );
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
    public function getEnergySystemByType($energy_type_id)
    {
        $energySystems = EnergySystem::where('energy_system_type_id', $energy_type_id)->get();
 
        if (!$energy_type_id) {

            $html = '<option value="">Choose One...</option>';
        } else {

            $html = '';
            $energySystems = EnergySystem::where('energy_system_type_id', $energy_type_id)->get();
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
}