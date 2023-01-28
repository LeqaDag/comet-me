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

class AcCommunityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {	
		$communities = Community::where("community_status_id", "2")->paginate();
        $communityRecords = Community::where("community_status_id", "2")->count();
        $regions = Region::all();
        $subregions = SubRegion::all();
        $products = ProductType::all();
        $energyTypes = EnergySystemType::all();

		return view('employee.community.ac', compact('communities', 'regions', 
            'communityRecords', 'subregions', 'products', 'energyTypes'));
    }
}
