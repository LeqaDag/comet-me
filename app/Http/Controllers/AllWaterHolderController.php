<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\User;
use App\Models\BsfStatus;
use App\Models\Donor;
use App\Models\Community;
use App\Models\CommunityWaterSource;
use App\Models\GridUser;
use App\Models\GridUserDonor;
use App\Models\H2oSharedUser;
use App\Models\H2oStatus;
use App\Models\H2oUser;  
use App\Models\H2oUserDonor;
use App\Models\Household;
use App\Models\WaterUser;
use App\Models\WaterNetworkUser;
use App\Models\EnergySystemType;
use App\Exports\WaterUserExport;
use Auth;
use DB;
use Route;
use DataTables;
use Excel;

class AllWaterHolderController extends Controller
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
                $data = DB::table('h2o_users')
                    ->LeftJoin('grid_users', 'h2o_users.household_id', '=', 'grid_users.household_id')
                    ->join('households', 'h2o_users.household_id', 'households.id')
                    ->join('communities', 'h2o_users.community_id', 'communities.id')
                    ->join('h2o_statuses', 'h2o_users.h2o_status_id', '=', 'h2o_statuses.id')
                    ->where('h2o_statuses.status', 'Used')
                    ->select('h2o_users.id as id', 'households.english_name', 'number_of_h20',
                        'grid_integration_large', 'large_date', 'grid_integration_small', 
                        'small_date', 'is_delivery', 'number_of_bsf', 'is_paid', 
                        'is_complete', 'communities.english_name as community_name',
                        'installation_year', 'h2o_users.created_at as created_at',
                        'h2o_users.updated_at as updated_at', 'h2o_statuses.status')
                    ->latest();
                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
    
                        $viewButton = "<a type='button' class='viewWaterUser' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#viewWaterUserModal' ><i class='fa-solid fa-eye text-info'></i></a>";
    
                        return $viewButton;
                    })
                   
                    ->filter(function ($instance) use ($request) {
                        if (!empty($request->get('search'))) {
                                $instance->where(function($w) use($request){
                                $search = $request->get('search');
                                $w->orWhere('communities.english_name', 'LIKE', "%$search%")
                                ->orWhere('communities.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('households.english_name', 'LIKE', "%$search%")
                                ->orWhere('households.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('h2o_statuses.status', 'LIKE', "%$search%")
                                ->orWhere('grid_integration_large', 'LIKE', "%$search%")
                                ->orWhere('grid_integration_small', 'LIKE', "%$search%");
                            });
                        }
                    })
                ->rawColumns(['action'])
                ->make(true);
            }
    
            $communities = Community::where('is_archived', 0)
                ->orderBy('english_name', 'ASC')
                ->get();
            $bsfStatus = BsfStatus::all();
            $households = Household::where('is_archived', 0)
                ->orderBy('english_name', 'ASC')
                ->get();
            $h2oStatus = H2oStatus::all();
    
            $data = DB::table('h2o_users')
                ->join('h2o_statuses', 'h2o_users.h2o_status_id', '=', 'h2o_statuses.id')
                //->where('h2o_statuses.status', '!=', "Used")
                ->select(
                        DB::raw('h2o_statuses.status as name'),
                        DB::raw('count(*) as number'))
                ->groupBy('h2o_statuses.status')
                ->get();
    
            
            $array[] = ['H2O Status', 'Total'];
            
            foreach($data as $key => $value) {
    
                $array[++$key] = [$value->name, $value->number];
            }
    
            $gridLarge = GridUser::where('grid_integration_large', '!=', 0)
                ->selectRaw('SUM(grid_integration_large) AS sumLarge')
                ->first();
            $gridSmall = GridUser::where('grid_integration_small', '!=', 0)
                ->selectRaw('SUM(grid_integration_small) AS sumSmall')
                ->first();
            
            $arrayGrid[] = ['Grid Integration', 'Total']; 
            
            for($key=0; $key <=2; $key++) {
                if($key == 1) $arrayGrid[$key] = ["Grid Large", $gridLarge->sumLarge];
                if($key == 2) $arrayGrid[$key] = ["Grid Small", $gridSmall->sumSmall];
            }
    
            $totalWaterHouseholds = Household::where("water_system_status", "Served")
                ->selectRaw('SUM(number_of_people) AS number_of_people')
                ->first();
            $totalWaterMale = Household::where("water_system_status", "Served")
                ->selectRaw('SUM(number_of_male) AS number_of_male')
                ->first(); 
            $totalWaterFemale = Household::where("water_system_status", "Served")
                ->selectRaw('SUM(number_of_female) AS number_of_female')
                ->first(); 
            $totalWaterAdults = Household::where("water_system_status", "Served")
                ->selectRaw('SUM(number_of_adults) AS number_of_adults')
                ->first();
            $totalWaterChildren = Household::where("water_system_status", "Served")
                ->selectRaw('SUM(number_of_children) AS number_of_children')
                ->first(); 
    
            $donors = Donor::all();
            $energySystemTypes = EnergySystemType::all();
    
            return view('users.water.index', compact('communities', 'bsfStatus', 'households', 
                'h2oStatus', 'totalWaterHouseholds', 'totalWaterMale', 'totalWaterFemale',
                'totalWaterChildren', 'totalWaterAdults', 'donors', 'energySystemTypes'))
            ->with('h2oChartStatus', json_encode($array))
            ->with('gridChartStatus', json_encode($arrayGrid));
        } else {

            return view('errors.not-found');
        }
    }
}
