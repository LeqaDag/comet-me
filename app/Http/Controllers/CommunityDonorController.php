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
use App\Models\Donor;
use App\Models\ServiceType;
use Carbon\Carbon;
use Image;

class CommunityDonorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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
        if($request->donor_id) {

            for($i=0; $i < count($request->donor_id); $i++) {

                $communityDonor = new CommunityDonor();
                $communityDonor->community_id = $request->community_id;
                $communityDonor->service_id = $request->service_id;
                $communityDonor->donor_id = $request->donor_id[$i];
                $communityDonor->save();
            }
        }  

        $allEnergyMeters = AllEnergyMeter::where("community_id", $request->community_id)->get();

        if($request->service_id == 1) {

            foreach($allEnergyMeters as $allEnergyMeter) {
    
                for($i=0; $i < count($request->donor_id); $i++) {
    
                    $allEnergyMeterDonor = new AllEnergyMeterDonor();
                    $allEnergyMeterDonor->all_energy_meter_id = $allEnergyMeter->id;
                    $allEnergyMeterDonor->community_id = $allEnergyMeter->community_id;
                    $allEnergyMeterDonor->donor_id = $request->donor_id[$i];
                    $allEnergyMeterDonor->save();
                }
            }
        }

        if($request->service_id == 2) {

        }

        if($request->service_id ==3) {

        }

        return redirect()->back()->with('message', 'Community Donors Updated Successfully!');
    }

    /**
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteCommunityDonor(Request $request)
    {
        $id = $request->id;

        $donor = CommunityDonor::find($id);
        $allEnergyMeterDonors = AllEnergyMeterDonor::where("donor_id", $donor->donor_id)
            ->where("community_id", $donor->community_id)
            ->get();


        if($allEnergyMeterDonors) {
            foreach($allEnergyMeterDonors as $allEnergyMeterDonor) {
                $allEnergyMeterDonor->delete();
            }
        }

        if($donor->delete()) {

            $response['success'] = 1;
            $response['msg'] = 'Community Donor Deleted successfully'; 
        } else {

            $response['success'] = 0;
            $response['msg'] = 'Invalid ID.';
        }

        return response()->json($response); 
    }
}
