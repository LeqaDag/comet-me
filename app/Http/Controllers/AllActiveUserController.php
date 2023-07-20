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
use App\Models\AllEnergyMeterDonor;
use App\Models\User;
use App\Models\Community;
use App\Models\CommunityDonor;
use App\Models\CommunityVendor;
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
use App\Models\InternetUser;
use App\Models\MeterCase;
use App\Models\H2oUser;
use App\Models\H2oSharedUser;
use App\Models\GridUser;
use App\Models\ServiceType;
use App\Models\PublicStructure;
use App\Models\PublicStructureCategory;
use App\Models\Region;
use App\Models\VendorUsername;
use App\Exports\AllActiveUserExport;
use App\Models\WaterNetworkUser;
use Carbon\Carbon;
use Image;
use DataTables;
use Excel;

class AllActiveUserController extends Controller
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
                    ->join('communities', 'households.community_id', '=', 'communities.id')
                    ->join('regions', 'communities.region_id', '=', 'regions.id')
                    ->join('sub_regions', 'communities.sub_region_id', '=', 'sub_regions.id')
                    ->where('households.energy_system_status', 'Served')
                    ->orWhere('households.water_system_status', 'Served')
                    ->orWhere('households.internet_system_status', 'Served')
                    ->select('communities.english_name as community_name',
                        'households.id as id', 'households.created_at as created_at', 
                        'households.updated_at as updated_at', 'regions.english_name as region',
                        'households.english_name as household_name',
                        'households.arabic_name as arabic_name', 'households.energy_system_status', 
                        'households.water_system_status', 'households.internet_system_status')
                    ->latest(); 
    
                return Datatables::of($data)
                    ->addIndexColumn()
                    ->filter(function ($instance) use ($request) {
                        if (!empty($request->get('search'))) {
                                $instance->where(function($w) use($request) {
                                $search = $request->get('search');
                                $w->orWhere('communities.english_name', 'LIKE', "%$search%")
                                ->orWhere('communities.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('households.english_name', 'LIKE', "%$search%")
                                ->orWhere('households.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('regions.english_name', 'LIKE', "%$search%")
                                ->orWhere('regions.arabic_name', 'LIKE', "%$search%");
                            });
                        }
                    })
                ->rawColumns(['action'])
                ->make(true);
            }
    
            $communities = Community::where('is_archived', 0)
                ->orderBy('english_name', 'ASC')
                ->get();
            $energySystemTypes = EnergySystemType::where('is_archived', 0)->get();
            $regions = Region::where('is_archived', 0)->get();

            return view('users.all.index', compact('communities', 'energySystemTypes', 'regions'));
        } else {
 
            return view('errors.not-found');
        }
    }

    /**
     * 
     * @return \Illuminate\Support\Collection
     */
    public function export(Request $request) 
    {

        return Excel::download(new AllActiveUserExport($request), 'all_active_users.xlsx');
    }
}