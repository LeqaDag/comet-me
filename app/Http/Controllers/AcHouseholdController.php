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
use App\Models\Cistern;
use App\Models\EnergyUser;
use App\Models\EnergySystem;
use App\Models\Household;
use App\Models\HouseholdMeter;
use App\Models\HouseholdStatus;
use App\Models\Region;
use App\Models\Structure;
use App\Models\SubRegion;
use App\Models\Profession;
use App\Models\EnergySystemType;
use App\Models\EnergyHolder;
use App\Models\EnergyPublicStructure;
use App\Models\MeterCase;
use Carbon\Carbon;
use DataTables;
use mikehaertl\wkhtmlto\Pdf;

class AcHouseholdController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {	
        if ($request->ajax()) {
            
            $data = DB::table('households')
                ->where('households.household_status_id', 2)
                ->join('communities', 'households.community_id', '=', 'communities.id')
                ->join('regions', 'communities.region_id', '=', 'regions.id')
                ->select('households.english_name as english_name', 'households.arabic_name as arabic_name',
                    'households.id as id', 'households.created_at as created_at', 
                    'households.updated_at as updated_at',
                    'regions.english_name as region_name',
                    'communities.english_name as name',
                    'communities.arabic_name as aname',
                    'households.energy_meter')
                ->latest(); 

            
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row) {

                    $acButton = "<select id='sharedHouseholdsSelect' class='sharedHousehold form-control' data-id='".$row->id."'><option selected>'". $row->energy_meter ."'</option><option value='No'>No</option><option value='Yes'>Yes</option></select>";
                    
                    return $acButton;
   
                })
               
                ->filter(function ($instance) use ($request) {
                    if (!empty($request->get('search'))) {
                            $instance->where(function($w) use($request) {
                                $search = $request->get('search');
                                $w->orWhere('households.english_name', 'LIKE', "%$search%")
                                ->orWhere('communities.english_name', 'LIKE', "%$search%")
                                ->orWhere('communities.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('households.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('regions.english_name', 'LIKE', "%$search%")
                                ->orWhere('regions.arabic_name', 'LIKE', "%$search%");
                        });
                    }
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $dataHouseholdsByCommunity = DB::table('households')
            ->where('households.household_status_id', 2)
            ->join('communities', 'households.community_id', '=', 'communities.id')
            ->select(
                    DB::raw('communities.english_name as english_name'),
                    DB::raw('count(*) as number'))
            ->groupBy('communities.english_name')
            ->get();
        $arrayAcHouseholdsByCommunity[] = ['Community Name', 'Total'];
        
        foreach($dataHouseholdsByCommunity as $key => $value) {

            $arrayAcHouseholdsByCommunity[++$key] = [$value->english_name, $value->number];
        }

        $communities = Community::all();
        $households = Household::all();
        $energySystems = EnergySystem::all();
        $energySystemTypes = EnergySystemType::all();
        $meters = MeterCase::all();

		return view('employee.household.ac', compact('communities', 'households', 
            'energySystems', 'energySystemTypes', 'meters'))
            ->with('communityAcHouseholdsData', json_encode($arrayAcHouseholdsByCommunity));
    }

    /**
     * Change resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function acSubHousehold(Request $request)
    {
        $id = $request->id;
        $household = Household::find($id);
        $households = Household::where("community_id", $household->community_id)
            ->where("id", "!=", $household->id)
            ->select("english_name", "id")
            ->get();

        $response = $households; 
      
        return response()->json($response); 
    }

    /**
     * Change resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function acSubHouseholdSave(Request $request)
    {
        $energyUserHouseholdMeter = EnergyUser::where("household_id", $request->id)->first();
        
        $mainHousehold = Household::findOrFail($request->user_id);
        $mainHousehold->energy_service = "Yes";
        $mainHousehold->energy_meter = "Yes";
        $mainHousehold->save();

        $energyUser = EnergyUser::where("household_id", $request->user_id)->first();
      
        if($energyUserHouseholdMeter != null) {
            $energyUserHouseholdMeter->delete();
        } 

        $householdMeter = Household::findOrFail($request->id);
        $householdMeter->energy_service = "Yes";
        $householdMeter->energy_meter = "No";

        if($energyUser != null) {
            if($energyUser->meter_active == "Yes") {

                $householdMeter->household_status_id = 4;
            }
        }
        
        $householdMeter->save();

        $householdMeter = new HouseholdMeter();
        $householdMeter->user_name = $mainHousehold->english_name;
        $householdMeter->user_name_arabic = $mainHousehold->arabic_name;
        $householdMeter->household_name = $householdMeter->english_name;
        $householdMeter->energy_user_id = $energyUser->id;
        $householdMeter->household_id = $request->id;
        $householdMeter->save();

        $response = $householdMeter;  
      
        return response()->json($response); 
    }

    /**
     * Change resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function acMainHousehold(Request $request)
    {
        $householdMeter = HouseholdMeter::where("household_id", $request->id)->get();

        if($householdMeter == []) {
            $householdMeter->delete();
        }

        $mainHousehold = Household::findOrFail($request->id);
        $mainHousehold->energy_service = "Yes";
        $mainHousehold->energy_meter = "Yes";
        $mainHousehold->save();
      
        $response = $mainHousehold;

        return response()->json($response); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $communities = Community::all();
        $energySystemTypes = EnergySystemType::all();
        $households = Household::all();

        return view('employee.household.create_ac', compact('communities', 'energySystemTypes', 
            'households'));
    }

     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->household_id) {
            for($i=0; $i < count($request->household_id); $i++) {

                $household = Household::findOrFail($request->household_id[$i]);
                $household->household_status_id = 2;
                $household->save();

                EnergyUser::create([
                    'household_id' => $request->household_id[$i],
                    'community_id' => $request->community_id,
                    'energy_system_type_id' => $request->energy_system_type_id,
                    'energy_system_id' => $request->energy_system_id,
                    'meter_number' => 0
                ]);  
            }
        }
     
        return redirect('/ac-household')
            ->with('message', 'New Elc. Added Successfully!');
    }
}
