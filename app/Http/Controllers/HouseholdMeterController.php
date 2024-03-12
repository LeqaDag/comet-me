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
use App\Models\InstallationType;
use App\Models\VendorUserName;
use App\Exports\HouseholdMeters;
use Carbon\Carbon;
use Image;
use DataTables;
use Excel;

class HouseholdMeterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $householdMeters = HouseholdMeter::where('is_archived', 0)->get();

        // foreach($householdMeters as $householdMeter) {

        //     $energyUser = AllEnergyMeter::where('id', $householdMeter->energy_user_id)->first();
        //     if($energyUser) {

        //         $existEnergyMeter = AllEnergyMeter::where('household_id', $householdMeter->household_id)->first();
        //         if($existEnergyMeter) {

        //         } else {

        //             $newAllEnergyMeter = new AllEnergyMeter();
        //             $newAllEnergyMeter->household_id = $householdMeter->household_id;
        //             $newAllEnergyMeter->is_main = "No";
        //             $newAllEnergyMeter->community_id = $energyUser->community_id;
        //             $newAllEnergyMeter->energy_system_type_id = $energyUser->energy_system_type_id;
        //             $newAllEnergyMeter->energy_system_id  = $energyUser->energy_system_id ;
        //             $newAllEnergyMeter->save();
        //         }
        //     }
        // }

        if (Auth::guard('user')->user() != null) {

            $communityFilter = $request->input('community_filter');
            $typeFilter = $request->input('type_filter');
            $dateFilter = $request->input('date_filter');

            if ($request->ajax()) {

                $data = DB::table('household_meters')
                    ->join('all_energy_meters', 'household_meters.energy_user_id', 'all_energy_meters.id')
                    ->leftJoin('households', 'household_meters.household_id', 'households.id')
                    ->leftJoin('public_structures', 'household_meters.public_structure_id', 
                        'public_structures.id')
                    ->join('communities', 'all_energy_meters.community_id', 'communities.id')
                    ->where('household_meters.is_archived', 0);
     
                if($communityFilter != null) {

                    $data->where('communities.id', $communityFilter);
                }
                if ($typeFilter != null) {

                    $data->where('all_energy_meters.installation_type_id', $typeFilter);
                }
                if ($dateFilter != null) {

                    $data->where('all_energy_meters.installation_date', '>=', $dateFilter);
                }
                
                $data->select(
                    'communities.english_name as community_name',
                    'household_meters.id as id', 'household_meters.created_at',
                    'household_meters.updated_at',
                    DB::raw('IFNULL(households.english_name, public_structures.english_name) 
                    as household_name'),
                    'household_meters.user_name', 'household_meters.user_name_arabic')
                ->latest(); 

                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
    
                        $viewButton = "<a type='button' class='viewHouseholdMeterUser' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#viewEnergySharedUserModal' ><i class='fa-solid fa-eye text-info'></i></a>";
                        $deleteButton = "<a type='button' class='deleteAllHouseholdMeterUser' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";
                        
                        if(Auth::guard('user')->user()->user_type_id == 1 || 
                            Auth::guard('user')->user()->user_type_id == 2 ||
                            Auth::guard('user')->user()->user_type_id == 3 ||
                            Auth::guard('user')->user()->user_type_id == 4 ||
                            Auth::guard('user')->user()->user_type_id == 12) 
                        {
                                
                            return $viewButton." ". $deleteButton;
                        } else return $viewButton;
       
                    })
                    ->filter(function ($instance) use ($request) {
                        if (!empty($request->get('search'))) {
                                $instance->where(function($w) use($request) {
                                $search = $request->get('search');
                                $w->orWhere('household_meters.user_name', 'LIKE', "%$search%")
                                ->orWhere('household_meters.user_name_arabic', 'LIKE', "%$search%")
                                ->orWhere('communities.english_name', 'LIKE', "%$search%")
                                ->orWhere('households.english_name', 'LIKE', "%$search%")
                                ->orWhere('communities.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('households.arabic_name', 'LIKE', "%$search%");
                            });
                        }
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
    
            $communities = Community::where('is_archived', 0)
                ->orderBy('english_name', 'ASC')
                ->get();
            $households = DB::table('all_energy_meters')
                ->where('all_energy_meters.is_archived', 0)
                ->join('households', 'all_energy_meters.household_id', '=', 'households.id')
                ->get();
    
            $installationTypes = InstallationType::where('is_archived', 0)->get();

            return view('users.energy.shared.index', compact('communities', 'households', 
                'installationTypes'));
            
        } else {

            return view('errors.not-found');
        }
    }

    /**
     * Show the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $householdMeter = HouseholdMeter::findOrFail($id);
        $mainUser = AllEnergyMeter::findOrFail($householdMeter->energy_user_id);
        $user = Household::where('id', $mainUser->household_id)->first();
        $sharedUser = Household::where('id', $householdMeter->household_id)->first();
        $sharedPublic = PublicStructure::where('id', $householdMeter->public_structure_id)->first();

        $community = Community::where('id', $user->community_id)->first();
        $meter = MeterCase::where('id', $mainUser->meter_case_id)->first();
        $systemType = EnergySystemType::where('id', $mainUser->energy_system_type_id)->first();
        $system = EnergySystem::where('id', $mainUser->energy_system_id)->first();
        $vendor = VendorUserName::where('id', $mainUser->vendor_username_id)->first();
        $installationType = InstallationType::where('id', $mainUser->installation_type_id)->first();

        $response['user'] = $user;
        $response['mainUser'] = $mainUser;
        $response['sharedUser'] = $sharedUser;
        $response['sharedPublic'] = $sharedPublic;
        $response['householdMeters'] = $householdMeter;
        $response['community'] = $community;
        $response['meter'] = $meter;
        $response['type'] = $systemType;
        $response['system'] = $system;
        $response['vendor'] = $vendor;
        $response['installationType'] = $installationType;

        return response()->json($response);
    }

    /**
     * Show the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function getUsers($id)
    {
        $community = Community::findOrFail($id);

        if (!$id) {

            $html = '<option value="">Choose One...</option>';
        } else {

            $html = '<option selected>Choose One...</option>';
            $users = DB::table('all_energy_meters')
                ->where('all_energy_meters.is_archived', 0)
                ->where('all_energy_meters.community_id', $community->id)
                ->join('households', 'all_energy_meters.household_id', 'households.id')
                // ->leftJoin('public_structures', 'all_energy_meters.public_structure_id', 
                //     'public_structures.id')
                ->select('all_energy_meters.id', 
                    'households.english_name')
                    //DB::raw('IFNULL(households.english_name, public_structures.english_name) as english_name'))
                // ->orderByRaw('CASE 
                //     WHEN households.english_name IS NOT NULL THEN 0 
                //     WHEN public_structures.english_name IS NOT NULL THEN 1 
                //     ELSE 2 
                //     END')
                ->orderBy('english_name', 'ASC')
                ->get();

            foreach ($users as $user) {
                $html .= '<option value="'.$user->id.'">'.$user->english_name.'</option>';
            }
        }

        return response()->json(['html' => $html]);
    }


    /**
     * Show the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function getHouseholds($id)
    {
        $energyUser = AllEnergyMeter::findOrFail($id);

        if (!$id) {

            $html = '<option value="">Choose One...</option>';
        } else {

            $html = '<option disabled selected>Choose One...</option>';
            $households = DB::table('households')
                ->where('households.community_id', $energyUser->community_id)
                ->where('households.id', '!=', $energyUser->household_id)
                ->leftJoin('all_energy_meters', 'households.id', 'all_energy_meters.household_id')
                ->whereNull('all_energy_meters.household_id')
                ->where('households.is_archived', 0)
                ->select('households.id', 'households.english_name')
                ->orderBy('households.english_name', 'ASC')
                ->get();

            foreach ($households as $household) {
                $html .= '<option value="'.$household->id.'">'.$household->english_name.'</option>';
            }
        }

        return response()->json(['html' => $html]);
    }

     /**
     * Show the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function getPublicStructures($id)
    {
        $energyUser = AllEnergyMeter::findOrFail($id);
       
        if (!$id) {

            $html = '<option value="">Choose One...</option>';
        } else {

            $html = '<option disabled selected>Choose One...</option>';

            $publics = DB::table('public_structures')
                ->where('public_structures.community_id', $energyUser->community_id)
                ->where('public_structures.id', '!=', $energyUser->public_structure_id)
                ->leftJoin('all_energy_meters', 'public_structures.id', 
                    'all_energy_meters.public_structure_id')
                ->whereNull('all_energy_meters.public_structure_id')
                ->where('public_structures.is_archived', 0)
                ->select('public_structures.id', 'public_structures.english_name')
                ->orderBy('public_structures.english_name', 'ASC')
                ->get();

            foreach ($publics as $public) {
                $html .= '<option value="'.$public->id.'">'.$public->english_name.'</option>';
            }
        }

        return response()->json(['html' => $html]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $energyUser = AllEnergyMeter::where('id', $request->energy_user_id)->first();

        if($request->household_id) {

            for($i=0; $i < count($request->household_id); $i++) {

                if($energyUser) {

                    $household = Household::where('id', $request->household_id[$i])->first();
                    if($energyUser->meter_number == 0) $household->household_status_id = 3;
                   
                    else $household->household_status_id = 4;
                    
                    $household->save();

                    $newAllEnergyMeter = new AllEnergyMeter();
                    $newAllEnergyMeter->household_id = $request->household_id[$i];
                    $newAllEnergyMeter->is_main = "No";
                    $newAllEnergyMeter->community_id = $energyUser->community_id;
                    $newAllEnergyMeter->installation_type_id = 4;
                    $newAllEnergyMeter->energy_system_type_id = $energyUser->energy_system_type_id;
                    $newAllEnergyMeter->energy_system_id  = $energyUser->energy_system_id ;
                    $newAllEnergyMeter->save();
                }

                $householdMeter = new HouseholdMeter();
                $householdMeter->user_name = $household->english_name;
                $householdMeter->user_name_arabic = $household->arabic_name;
                $householdMeter->household_id = $request->household_id[$i];
                $householdMeter->energy_user_id = $request->energy_user_id;
                $householdMeter->save();
            }
        }

        if($request->public_id) {

            for($i=0; $i < count($request->public_id); $i++) {

                if($energyUser) {

                    $public = PublicStructure::where('id', $request->public_id[$i])->first();
          
                    $newAllEnergyMeter = new AllEnergyMeter();
                    $newAllEnergyMeter->public_structure_id = $request->public_id[$i];
                    $newAllEnergyMeter->is_main = "No";
                    $newAllEnergyMeter->community_id = $energyUser->community_id;
                    $newAllEnergyMeter->installation_type_id = 4;
                    $newAllEnergyMeter->energy_system_type_id = $energyUser->energy_system_type_id;
                    $newAllEnergyMeter->energy_system_id = $energyUser->energy_system_id ;
                    $newAllEnergyMeter->save();
                }

                $householdMeter = new HouseholdMeter();
                $householdMeter->user_name = $household->english_name;
                $householdMeter->user_name_arabic = $household->arabic_name;
                $householdMeter->public_structure_id = $request->public_id[$i];
                $householdMeter->energy_user_id = $request->energy_user_id;
                $householdMeter->save();
            }
        }
        
        return redirect()->back()->with('message', 'New Shared Holders Added Successfully!');
    }

     /**
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteHouseholdMeter(Request $request)
    {
        $id = $request->id;

        $householdMeter = HouseholdMeter::find($id);

        if($householdMeter) {

            $householdMeter->delete();
            
            $response['success'] = 1;
            $response['msg'] = 'Household Meter Deleted successfully'; 
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

        return Excel::download(new HouseholdMeters($request), 'shared_users.xlsx');
    }
}