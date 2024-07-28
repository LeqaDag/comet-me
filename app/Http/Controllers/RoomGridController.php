<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use App\Models\AllEnergyMeter;
use App\Models\User;
use App\Models\Community;
use Carbon\Carbon;
use App\Models\ActionItem;
use App\Models\ActionStatus;
use App\Models\ActionPriority;
use App\Models\CommunityDonor;
use App\Models\CommunityStatus;
use App\Models\CommunityService;
use App\Models\CommunityRepresentative;
use App\Models\CommunityRole;
use App\Models\Compound;
use App\Models\Donor; 
use App\Models\CameraIncident;
use App\Models\EnergySystem;
use App\Models\EnergySystemType;
use App\Models\Household;
use App\Models\FbsUserIncident;
use App\Models\H2oSystemIncident; 
use App\Models\GridCommunityCompound;
use App\Models\Setting;
use App\Models\SubRegion;
use App\Models\SubCommunity;
use App\Models\Settlement;
use App\Models\ServiceType;
use App\Models\EnergySystemCycle;
use App\Models\InternetUser;
use Auth;
use Route;
use DB;
use Excel;
use PDF;
use DataTables;
use Mail;

class RoomGridController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { 
        if($request->compound_id) {

            $gridCompound = GridCommunityCompound::where("compound_id", $request->compound_id)
                ->whereNull('community_id')
                ->first();
            if($gridCompound) {

                $gridCompound->electricity_room = $request->electricity_room;
                $gridCompound->grid = $request->grid;
                $gridCompound->save();
            }
        }

        if($request->community_id) {

            $gridCommunity = GridCommunityCompound::where("community_id", $request->community_id)->first();
            if($gridCommunity) {

                $gridCommunity->electricity_room = $request->electricity_room;
                $gridCommunity->grid = $request->grid;
                $gridCommunity->save();
            }
        }

        return redirect()->back()->with('message', 'The Status Updated Successfully!');
    }

    /**
     * View Edit page.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) 
    {
   
    }
}