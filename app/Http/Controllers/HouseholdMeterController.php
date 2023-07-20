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
use Carbon\Carbon;
use Image;
use DataTables;

class HouseholdMeterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Auth::guard('user')->user() != null) {

            if ($request->ajax()) {

                $data = DB::table('household_meters')
                    ->join('all_energy_meters', 'household_meters.energy_user_id', '=', 'all_energy_meters.id')
                    ->join('households', 'household_meters.household_id', '=', 'households.id')
                    ->join('communities', 'all_energy_meters.community_id', '=', 'communities.id')
                    ->where('household_meters.is_archived', 0)
                    ->select('communities.english_name as community_name',
                        'household_meters.id as id', 'household_meters.created_at',
                        'household_meters.updated_at',
                        'households.english_name as household_name',
                        'household_meters.user_name', 'household_meters.user_name_arabic')
                    ->latest(); 
    
                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
    
                        $viewButton = "<a type='button' class='viewHouseholdMeterUser' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#viewHouseholdMeterUserModal' ><i class='fa-solid fa-eye text-info'></i></a>";
                        $updateButton = "<a type='button' class='updateAllHouseholdMeterUser' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#updateAllHouseholdMeterUserModal' ><i class='fa-solid fa-pen-to-square text-success'></i></a>";
                        $deleteButton = "<a type='button' class='deleteAllHouseholdMeterUser' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";
                        
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
    
            return view('users.energy.shared.index', compact('communities', 'households'));
            
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
        $household = Household::where('id', $householdMeter->household_id)->first();

        $response['household'] = $household;
        $response['householdMeters'] = $householdMeter;

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
                ->join('households', 'all_energy_meters.household_id', '=', 'households.id')
                ->orderBy('households.english_name', 'ASC')
                ->select('all_energy_meters.id', 'households.english_name')
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

            $html = '<option selected>Choose One...</option>';
            $households = Household::where('community_id', $energyUser->community_id)
                ->where('id', '!=', $energyUser->household_id)
                ->where('is_archived', 0)
                ->orderBy('english_name', 'ASC')
                ->get();
            foreach ($households as $household) {
                $html .= '<option value="'.$household->id.'">'.$household->english_name.'</option>';
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
        $household = Household::findOrFail($energyUser->household_id);
        
        $householdMeter = new HouseholdMeter();
        $householdMeter->user_name = $household->english_name;
        $householdMeter->user_name_arabic = $household->arabic_name;
        $householdMeter->household_id = $request->household_id;
        $householdMeter->energy_user_id = $request->energy_user_id;
        $householdMeter->save();
        
        return redirect()->back()->with('message', 'New Sub-Region Added Successfully!');
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

            $householdMeter->is_archived = 1;
            $householdMeter->save();
            
            $response['success'] = 1;
            $response['msg'] = 'Household Meter Deleted successfully'; 
        } else {

            $response['success'] = 0;
            $response['msg'] = 'Invalid ID.';
        }

        return response()->json($response); 
    }
}