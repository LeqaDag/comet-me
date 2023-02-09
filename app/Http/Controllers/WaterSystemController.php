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
    public function index()
    {	
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
