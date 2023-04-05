<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\User;
use App\Models\Community;
use Carbon\Carbon;
use App\Models\CommunityDonor;
use App\Models\CommunityStatus;
use App\Models\CommunityRepresentative;
use App\Models\CommunityRole;
use App\Models\Compound;
use App\Models\Donor;
use App\Models\EnergySystem;
use App\Models\EnergySystemType;
use App\Models\EnergyUser;
use App\Models\Household;
use App\Models\HouseholdMeter;
use App\Models\H2oUser;
use App\Models\GridUser;
use App\Models\Photo;
use App\Models\Region;
use App\Models\SubRegion;
use App\Models\SubCommunity;
use App\Models\Settlement;
use App\Models\ServiceType;
use App\Models\PublicStructure;
use App\Models\PublicStructureCategory;
use App\Models\ProductType;
use App\Models\CommunityWaterSource;
use App\Models\Town;
use App\Models\BsfStatus;
use App\Models\H2oSharedUser;
use App\Models\H2oStatus;
use App\Models\Incident;
use App\Models\MgIncident;
use App\Models\IncidentStatusMgSystem;
use App\Models\InternetUser;
use App\Models\MeterList;
use Auth;
use Route;
use DB;
use Excel;
use PDF;

class ChartController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        if (Auth::guard('user')->user() != null) {

            $services = ServiceType::where("service_name", "!=", "Electricity")->get();
            $regions = Region::all();

            return view('chart.index', compact('services', 'regions'));
        } else {

            return view('errors.not-found');
        }    
    }

    /**
     * Get resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getByService(Request $request)
    {
        $servedHouseholds = Household::where('household_status_id', 4)->count();
        $totalH2oUsers = 0;
        $InternetUsers = 0;

        if($request->service_id == 2) {
            if($request->region_id == 0) {

                $totalH2oUsers = DB::table('h2o_users')
                    ->LeftJoin('grid_users', 'h2o_users.household_id', '=', 'grid_users.household_id')
                    ->join('communities', 'h2o_users.community_id', 'communities.id')
                    ->join('regions', 'communities.region_id', 'regions.id')
                    ->count();
            } else {
    
                $totalH2oUsers = DB::table('h2o_users')
                    ->LeftJoin('grid_users', 'h2o_users.household_id', '=', 'grid_users.household_id')
                    ->join('communities', 'h2o_users.community_id', 'communities.id')
                    ->join('regions', 'communities.region_id', 'regions.id')
                    ->where('regions.id', $request->region_id )
                    ->count();
            }
        } else if($request->service_id == 3) {

            if($request->region_id == 0) {

                $InternetUsers = InternetUser::count() * 5;
            } else {
    
                $InternetUsers = DB::table('internet_users')
                    ->join('communities', 'internet_users.community_id', 'communities.id')
                    ->join('regions', 'communities.region_id', 'regions.id')
                    ->where('regions.id', $request->region_id)
                    ->count() * 5;
            }
        }

        return response()->json([
            'servedHouseholds' => $servedHouseholds,
            'totalH2oUsers' => $totalH2oUsers,
            'InternetUsers' => $InternetUsers
        ]);
    }
}