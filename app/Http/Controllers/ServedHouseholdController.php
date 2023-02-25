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
use App\Models\Household;
use App\Models\HouseholdStatus;
use App\Models\Region;
use App\Models\Structure;
use App\Models\SubRegion;
use App\Models\Profession;
use Carbon\Carbon;
use DataTables;
use mikehaertl\wkhtmlto\Pdf;

class ServedHouseholdController extends Controller
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
                ->where('households.household_status_id', 4)
                ->join('communities', 'households.community_id', '=', 'communities.id')
                ->join('regions', 'communities.region_id', '=', 'regions.id')
                ->select('households.english_name as english_name', 'households.arabic_name as arabic_name',
                    'households.id as id', 'households.created_at as created_at', 
                    'households.updated_at as updated_at',
                    'regions.english_name as region_name',
                    'communities.english_name as name',
                    'communities.arabic_name as aname',)
                ->latest(); 

            
            return Datatables::of($data)
                ->addIndexColumn()
               
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
            ->make(true);
        }

        $dataHouseholdsByCommunity = DB::table('households')
            ->join('energy_users', 'households.id', '=', 'energy_users.household_id')
            ->leftJoin('household_meters', 'energy_users.id', '=', 'household_meters.energy_user_id')
            ->where('households.household_status_id', 4)
            ->join('communities', 'households.community_id', '=', 'communities.id')
            ->join('regions', 'communities.region_id', '=', 'regions.id')
            ->select(
                    DB::raw('regions.english_name as english_name'),
                    DB::raw('count(*) as number'))
            ->groupBy('regions.english_name')
            ->get();
        
        $arrayHouseholdsByCommunity[] = ['Region Name', 'Total'];
        
        foreach($dataHouseholdsByCommunity as $key => $value) {

            $arrayHouseholdsByCommunity[++$key] = [$value->english_name, $value->number];
        }

		return view('employee.household.served')
            ->with('communityServedHouseholdsData', json_encode($arrayHouseholdsByCommunity));
    }
}
