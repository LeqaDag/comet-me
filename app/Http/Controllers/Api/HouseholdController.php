<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use DB;
use Route;

class HouseholdController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {	
        $data = DB::table('households')
            ->join('communities', 'households.community_id', '=', 'communities.id')
            ->join('regions', 'communities.region_id', '=', 'regions.id')
            ->join('sub_regions', 'communities.sub_region_id', '=', 'sub_regions.id')
            ->select('households.english_name as english_name', 
                'households.arabic_name as arabic_name',
                'households.id as id', 
                'communities.english_name as community_name_english',
                'communities.arabic_name as community_name_arabic',)
            ->get(); 
  
        return response()->json($data); 
    }
}