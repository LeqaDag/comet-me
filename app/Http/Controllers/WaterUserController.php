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
use App\Models\BsfStatus;
use App\Models\Community;
use App\Models\CommunityWaterSource;
use App\Models\GridUser;
use App\Models\H2oStatus;
use App\Models\H2oUser;
use App\Models\Household;
use App\Models\WaterUser;

class WaterUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {	
        $communities = Community::all();
        $bsfStatus = BsfStatus::all();
        $households = Household::all();
        $h2oStatus = H2oStatus::all();
        $waterUsers = WaterUser::paginate();

		return view('users.water.index', compact('communities', 'bsfStatus', 'households', 
            'h2oStatus', 'waterUsers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        dd($request->all());
        // $waterUser = WaterUser::create($request->all());
        // $waterUser->save();

        return redirect()->back();
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $community = Community::findOrFail($id);
        $community->is_archived = 1;
        $community->save();

        return redirect()->back();
    }

    /**
     * Get grid access by community_id.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getGridSource(Request $request)
    {
        $community = CommunityWaterSource::where('community_id', $request->community_id)
            ->where('water_source_id', 1)
            ->get();
 
        if (!$request->community_id || $community->count() ==0) {
            $val = "New";
            $html = '<option disabled selected>Choose One...</option> <option value="Yes">Yes</option><option value="No">No</option>';
        } else {
            $val = "Yes";
            $html = '<option value="Yes">Yes</option>';
        }

        return response()->json([
            'html' => $html, 
            'val' => $val
        ]);
    }
}
