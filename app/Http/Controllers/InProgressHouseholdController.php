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

class InProgressHouseholdController extends Controller
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
            
                $data = DB::table('households')
                    ->where('households.household_status_id', 3)
                    ->where('internet_holder_young', 0)
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
    
                        $empty = "";
                        $acButton = "<select id='sharedHouseholdsSelect' class='sharedHousehold form-control' data-id='".$row->id."'><option selected>'". $row->energy_meter ."'</option><option value='No'>No</option><option value='Yes'>Yes</option></select>";
                        if(Auth::guard('user')->user()->user_type_id == 1 || 
                            Auth::guard('user')->user()->user_type_id == 2 || 
                            Auth::guard('user')->user()->user_type_id == 3 || 
                            Auth::guard('user')->user()->user_type_id == 4 || 
                            Auth::guard('user')->user()->user_type_id == 12 ) 
                        {
                            return $acButton;
                        }
                        else  return $empty;
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
                ->where('households.household_status_id', 3)
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
    
            $communities = Community::where('is_archived', 0)->get();
            $households = Household::all();
            $energySystems = EnergySystem::all();
            $energySystemTypes = EnergySystemType::all();
            $meters = MeterCase::all();
            $professions  = Profession::all();
    
            return view('employee.household.progress', compact('communities', 'households', 
                'energySystems', 'energySystemTypes', 'meters', 'professions'))
                ->with('communityAcHouseholdsData', json_encode($arrayAcHouseholdsByCommunity));
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
        $communities = Community::where('is_archived', 0)->get();
        $energySystemTypes = EnergySystemType::all();
        $households = Household::all();
        $professions  = Profession::all();

        return view('employee.household.elc_create', compact('communities', 'energySystemTypes', 
            'households', 'professions'));
    }

     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { 
       // dd($request->all());
        if($request->household_id) {
            //for($i=0; $i < count($request->household_id); $i++) {

                //$household = Household::findOrFail($request->household_id[$i]);
                $household = Household::findOrFail($request->household_id);
                $household->household_status_id = 3;
                $household->save(); 

                $energyUser = new AllEnergyMeter();
                $energyUser->misc = $request->misc;
                $energyUser->household_id = $request->household_id;
                $energyUser->community_id = $request->community_id;
                $energyUser->energy_system_type_id = $request->energy_system_type_id;
                $energyUser->energy_system_id = $request->energy_system_id;
                $energyUser->meter_number = 0;
                $energyUser->meter_case_id = 12;
                $energyUser->save();

           // }
        }
     
        return redirect('/progress-household')
            ->with('message', 'New Elc. Added Successfully!');
    }
}
