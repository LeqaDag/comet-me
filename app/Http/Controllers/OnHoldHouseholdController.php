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
use App\Models\CommunityHousehold;
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

class OnHoldHouseholdController extends Controller
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
                    ->where('households.is_archived', 0)
                    ->where('households.internet_holder_young', 0)
                    ->where('households.household_status_id', 8)
                    ->join('communities', 'households.community_id', 'communities.id')
                    ->join('regions', 'communities.region_id', 'regions.id')
                    ->leftJoin('compound_households', 'compound_households.household_id', 
                        'households.id')
                    ->leftJoin('compounds', 'compound_households.compound_id', 'compounds.id')
                    ->select(
                        'households.english_name as english_name', 
                        'households.arabic_name as arabic_name',
                        'households.id as id', 'households.created_at as created_at', 
                        'households.updated_at as updated_at',
                        'compounds.english_name as compound',
                        'communities.english_name as name',
                        'communities.arabic_name as aname',
                        'households.energy_meter')
                    ->latest(); 
                
                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {

                        $deleteButton = "<a type='button' class='deleteOnHoldHousehold' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";

                        if(Auth::guard('user')->user()->user_type_id == 1 || 
                            Auth::guard('user')->user()->user_type_id == 2 || 
                            Auth::guard('user')->user()->user_type_id == 3 || 
                            Auth::guard('user')->user()->user_type_id == 4 || 
                            Auth::guard('user')->user()->user_type_id == 12 ) 
                        {
                            return $deleteButton;
                        }
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
                ->where('households.is_archived', 0)
                ->where('households.household_status_id', 8)
                ->join('communities', 'households.community_id', 'communities.id')
                ->select(
                        DB::raw('communities.english_name as english_name'),
                        DB::raw('count(*) as number'))
                ->groupBy('communities.english_name')
                ->get();
            $arrayOnHoldHouseholdsByCommunity[] = ['Community Name', 'Total'];
            
            foreach($dataHouseholdsByCommunity as $key => $value) {
    
                $arrayOnHoldHouseholdsByCommunity[++$key] = [$value->english_name, $value->number];
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
            $professions  = Profession::where('is_archived', 0)->get();
    
            return view('employee.household.on_hold', compact('communities', 'households', 
                'energySystems', 'energySystemTypes', 'meters', 'professions'))
                ->with('communityOnHoldHouseholdsData', json_encode($arrayOnHoldHouseholdsByCommunity));
        } else {

            return view('errors.not-found');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function deleteOnHoldHousehold(Request $request)
    {
        $id = $request->id;

        $onHoldHousehold = Household::findOrFail($id);
        $onHoldHousehold->is_archived = 1;
        $onHoldHousehold->save();

        $response['success'] = 1;
        $response['msg'] = 'On Hold Deleted successfully'; 
        
        return response()->json($response); 
    }
}
