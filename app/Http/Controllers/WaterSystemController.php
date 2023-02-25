<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\User;
use App\Models\BsfStatus;
use App\Models\Community;
use App\Models\CommunityWaterSource;
use App\Models\GridUser;
use App\Models\H2oSharedUser;
use App\Models\H2oStatus;
use App\Models\H2oUser;
use App\Models\Household;
use App\Models\WaterUser;
use App\Models\WaterSystem;
use Auth;
use DB;
use Route;
use DataTables;

class WaterSystemController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {	
        if ($request->ajax()) {

            $data = DB::table('water_systems')->latest();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row) {

                    $viewButton = "<a type='button' class='viewWaterSystem' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#viewWtaerSystemModal' ><i class='fa-solid fa-eye text-info'></i></a>";

                    return $viewButton;
                })
               
                ->filter(function ($instance) use ($request) {
                    if (!empty($request->get('search'))) {
                            $instance->where(function($w) use($request){
                            $search = $request->get('search');
                            $w->orWhere('water_systems.type', 'LIKE', "%$search%")
                            ->orWhere('water_systems.description', 'LIKE', "%$search%")
                            ->orWhere('water_systems.year', 'LIKE', "%$search%");
                        });
                    }
                })
            ->rawColumns(['action'])
            ->make(true);
        }

        $gridLarge = GridUser::selectRaw('SUM(grid_integration_large) AS sumLarge')
            ->first();
        $gridSmall = GridUser::selectRaw('SUM(grid_integration_small) AS sumSmall')
            ->first();
        $h2oSystem = H2oUser::selectRaw('SUM(number_of_h20) AS h2oSystem')
            ->first();
        
        $waterArray[] = ['System Type', 'Total'];
        
        for($key=0; $key <=3; $key++) {
            if($key == 1) $waterArray[$key] = ["Grid Large", $gridLarge->sumLarge];
            if($key == 2) $waterArray[$key] = ["Grid Small", $gridSmall->sumSmall];
            if($key == 3) $waterArray[$key] = ["H2O System", $h2oSystem->h2oSystem];
        }

		return view('system.water.index')
        ->with(
            'waterSystemTypeData', json_encode($waterArray));

    }
}
