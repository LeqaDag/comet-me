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
use App\Models\CommunityStatus;
use App\Models\Compound;
use App\Models\Donor;
use App\Models\EnergySystem;
use App\Models\EnergySystemType;
use App\Models\Household;
use App\Models\Photo;
use App\Models\Region;
use App\Models\SubRegion;
use App\Models\SubCommunity;
use App\Models\ServiceType;
use App\Models\PublicStructure;
use App\Models\ProductType;
use App\Models\CommunityWaterSource;
use Carbon\Carbon;
use Image;
use DataTables;

class ServedCommunityController extends Controller
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

                $data = DB::table('communities')
                    ->join('regions', 'communities.region_id', '=', 'regions.id')
                    ->join('sub_regions', 'communities.sub_region_id', '=', 'sub_regions.id')
                    ->join('community_statuses', 'communities.community_status_id', '=', 'community_statuses.id')
                    ->where('community_status_id', 3)
                    ->where('communities.is_archived', 0)
                    ->select('communities.english_name as english_name', 'communities.arabic_name as arabic_name',
                        'communities.id as id', 'communities.created_at as created_at', 
                        'communities.updated_at as updated_at',
                        'communities.number_of_household as number_of_household',
                        'communities.number_of_people as number_of_people',
                        'regions.english_name as name',
                        'regions.arabic_name as aname',
                        'sub_regions.english_name as subname',
                        'community_statuses.name as status_name')
                    ->latest(); 
    
                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
                        $detailsButton = "<a type='button' class='detailsCommunityButton' data-id='".$row->id."'><i class='fa-solid fa-eye text-primary'></i></a>";
    
                        return $detailsButton;
       
                    })
                    ->filter(function ($instance) use ($request) {
                        if (!empty($request->get('search'))) {
                                $instance->where(function($w) use($request) {
                                $search = $request->get('search');
                                $w->orWhere('communities.english_name', 'LIKE', "%$search%")
                                ->orWhere('communities.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('regions.english_name', 'LIKE', "%$search%")
                                ->orWhere('regions.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('sub_regions.english_name', 'LIKE', "%$search%")
                                ->orWhere('community_statuses.name', 'LIKE', "%$search%");
                            });
                        }
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
    
            $communityRecords = Community::where("community_status_id", "3")
            ->where('is_archived', 0)->count();
            $regions = Region::where('is_archived', 0)->get();
            $subregions = SubRegion::where('is_archived', 0)->get();
            $products = ProductType::where('is_archived', 0)->get();
            $energyTypes = EnergySystemType::where('is_archived', 0)->get();
    
            $dataSurveyed = DB::table('communities')
                ->where('communities.is_archived', 0)
                ->where('communities.community_status_id', 3)
                ->select(
                    DB::raw('CASE WHEN communities.is_surveyed IS NULL THEN "No" ELSE "Yes" END as name'),
                    DB::raw('count(*) as number')
                )
                ->groupBy(DB::raw('CASE WHEN communities.is_surveyed IS NULL THEN "No" ELSE "Yes" END'))
                ->get();

            $arraySurveyed[] = ['Is Completed Surveyed', 'Total'];
            
            foreach($dataSurveyed as $key => $value) {

                $arraySurveyed[++$key] = [$value->name, $value->number];
            }

            return view('employee.community.served', compact('regions', 
                'communityRecords', 'subregions', 'products', 'energyTypes'))
                ->with('surveyedCommunityData', json_encode($arraySurveyed));
        } else {

            return view('errors.not-found');
        }
    }
}
