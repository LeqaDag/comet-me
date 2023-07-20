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
use App\Models\CometMeter;
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

class EnergyCometMeterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Auth::guard('user')->user() != null) {

        } else {

            return view('errors.not-found');
        }
        
        if ($request->ajax()) {

            $dataPublic = DB::table('comet_meters')
                ->join('communities', 'comet_meters.community_id', '=', 'communities.id')
                ->join('energy_systems', 'comet_meters.energy_system_id', '=', 'energy_systems.id')
                ->join('energy_system_types', 'comet_meters.energy_system_type_id', '=', 'energy_system_types.id')
                ->join('meter_cases', 'comet_meters.meter_case_id', '=', 'meter_cases.id')
                ->where('comet_meters.is_archived', 0)
                ->select('comet_meters.meter_number', 'comet_meters.name',
                    'comet_meters.id as id', 'comet_meters.created_at as created_at', 
                    'comet_meters.updated_at as updated_at', 
                    'communities.english_name as community_name',
                    'energy_systems.name as energy_name', 
                    'energy_system_types.name as energy_type_name',)
                ->latest(); 

             
            return Datatables::of($dataPublic)
                ->addIndexColumn()
                ->addColumn('action', function($row) {

                    $viewButton = "<a type='button' class='viewCometMeterUser' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#viewHCometMeterModal' ><i class='fa-solid fa-eye text-info'></i></a>";
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
                            ->orWhere('comet_meters.name', 'LIKE', "%$search%")
                            ->orWhere('comet_meters.meter_number', 'LIKE', "%$search%");
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
        
        return view('users.energy.comet.index', compact('communities', 'households',
            'energySystems', 'energySystemTypes', 'meters'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $cometMeter = new CometMeter();
        $cometMeter->community_id = $request->community_id;
        $cometMeter->meter_number = $request->meter_number;
        $cometMeter->name = $request->name;
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
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteCometMeter(Request $request)
    {
        $id = $request->id;

        $cometMeter = CometMeter::find($id);

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

}
