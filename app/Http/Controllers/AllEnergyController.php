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
use App\Models\EnergyPublicStructureDonor;
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

class AllEnergyController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
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
                    'energy_system_types.name as energy_type_name',
                    'meter_cases.meter_case_name_english')
                ->latest(); 

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row) {
                    $donorButton = "<a type='button' class='donorEnergyUser' data-id='".$row->id."'><i class='fa-solid fa-dollar text-warning'></i></a>";
                    $viewButton = "<a type='button' class='viewEnergyUser' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#viewEnergyUserModal' ><i class='fa-solid fa-eye text-info'></i></a>";
                    $updateButton = "<a type='button' class='updateAllEnergyUser' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#updateAllEnergyUserModal' ><i class='fa-solid fa-pen-to-square text-success'></i></a>";
                    $deleteButton = "<a type='button' class='deleteAllEnergyUser' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";
                    
                    return $donorButton." ". $viewButton." ". $updateButton." ".$deleteButton;
   
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
                            ->orWhere('energy_users.meter_number', 'LIKE', "%$search%")
                            ->orWhere('meter_cases.meter_case_name_english', 'LIKE', "%$search%")
                            ->orWhere('meter_cases.meter_case_name_arabic', 'LIKE', "%$search%");
                        });
                    }
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        
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

        $meterCases = MeterCase::get();

        return view('users.energy.not_active.index', compact('meterCases'))
            ->with('energy_users', json_encode($array)
        );
    }

    /**
     * Get a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getEnergyUserData(int $id)
    {
        $energy = EnergyUser::find($id);
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateEnergyUserData(Request $request)
    {
        $energyUser = EnergyUser::findOrFail($request->id);
        $energyUser->meter_number = $request->meter_number;
        $energyUser->daily_limit = $request->daily_limit;
        $energyUser->installation_date = $request->installation_date;

        $communityId = $energyUser->community_id;
        $communityDonors = CommunityDonor::where('community_id', $communityId)->get();
        if($communityDonors) {
            foreach($communityDonors as $communityDonor) {
                $energyDonor = EnergyDonor::where('household_id', $energyUser->household_id)->first();
                $energyDonor->delete();
                EnergyDonor::create([
                    'community_id' => $communityDonor->community_id,
                    'household_id' => $energyUser->household_id,
                    'donor_id' => $communityDonor->donor_id
                ]);
            }
        }
      
        if($request->meter_active) $energyUser->meter_active = $request->meter_active;

        if($request->meter_case_id == 1 || $request->meter_case_id == 2 ||
            $request->meter_case_id == 3 || $request->meter_case_id == 4 ||
            $request->meter_case_id == 5 || $request->meter_case_id == 6 ||
            $request->meter_case_id == 7 || $request->meter_case_id == 8 ||
            $request->meter_case_id == 9 || $request->meter_case_id == 10 ||
            $request->meter_case_id == 11 || $request->meter_case_id == 12 ||
            $request->meter_case_id == 13 || $request->meter_case_id == 14) {

                if($request->meter_case_id == 1) {
                    $household = Household::findOrFail($energyUser->household_id);
                    $household->household_status_id = 4;
                    $household->save();
                }

                $energyUser->meter_case_id = $request->meter_case_id;
            }

        $energyUser->save();
       
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
        
    }

    /**
     * View Edit page.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function editDonor($id)
    {
        $energy = EnergyUser::find($id);
      
        return response()->json($energy);
    }

    /**
     * View Edit page.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $energy = EnergyUser::find($id);
        $energyDonors = DB::table('energy_donors')
            ->join('communities', 'energy_donors.community_id', '=', 'communities.id')
            ->join('households', 'energy_donors.household_id', '=', 'households.id')
            ->join('donors', 'energy_donors.donor_id', '=', 'donors.id')
            ->where('energy_donors.household_id', $energy->household_id)
            ->select('energy_donors.id as id', 'communities.english_name as community_name',
                'households.english_name as household_name',
                'donors.donor_name as donor_name')
            ->get();
        $donors = Donor::all();

        return view('users.energy.not_active.donor_edit', compact('energy', 'energyDonors', 
            'donors'));
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
        $energyUser = EnergyUser::find($id);
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

        return redirect('/all-meter')->with('message', 'Donors Updated Successfully!');
    }
}
