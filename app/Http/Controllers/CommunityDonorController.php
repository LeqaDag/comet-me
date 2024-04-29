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
use App\Models\AllWaterHolder;
use App\Models\AllWaterHolderDonor;
use App\Models\User;
use App\Models\Community;
use App\Models\Compound;
use App\Models\CommunityDonor;
use App\Models\Donor;
use App\Models\InternetUser;
use App\Models\InternetUserDonor;
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

                $existCommunityDonor = CommunityDonor::where('donor_id', $request->donor_id[$i])
                    ->where('service_id', $request->service_id)
                    ->first();
                if($existCommunityDonor) {

                } else {

                    $communityDonor = new CommunityDonor();
                    if($request->energy_system_id) $communityDonor->energy_system_id = $request->energy_system_id;
                    if($request->compound_id) $communityDonor->compound_id = $request->compound_id;
                    if($request->compound_id == null) $communityDonor->community_id = $request->community_id;
                    $communityDonor->service_id = $request->service_id;
                    $communityDonor->donor_id = $request->donor_id[$i];
                    $communityDonor->save();
                }
            }
        }  

        if($request->service_id == 1) {

            if($request->energy_system_id) {

                $allEnergyMeters = DB::table('all_energy_meters')
                    ->join('communities', 'all_energy_meters.community_id', 'communities.id')
                    ->where('communities.id', $request->community_id)
                    ->join('households', 'all_energy_meters.household_id', 'households.id')
                    ->where('all_energy_meters.is_archived', 0)
                    ->where('households.is_archived', 0)
                    ->where('all_energy_meters.energy_system_id', $request->energy_system_id)
                    ->select("all_energy_meters.id", "all_energy_meters.community_id")
                    ->get();
    
                if($allEnergyMeters) {

                    foreach($allEnergyMeters as $allEnergyMeter) {
    
                        for($i=0; $i < count($request->donor_id); $i++) {
            
                            $exisitAllEnergyMeterDonor = AllEnergyMeterDonor::where('all_energy_meter_id', $allEnergyMeter->id)
                                ->where('donor_id', $request->donor_id)
                                ->where('energy_system_id', $request->energy_system_id)
                                ->first();

                            if($exisitAllEnergyMeterDonor) {

                            } else {

                                $allEnergyMeterDonor = new AllEnergyMeterDonor();
                                $allEnergyMeterDonor->all_energy_meter_id = $allEnergyMeter->id;
                                $allEnergyMeterDonor->community_id = $allEnergyMeter->community_id;
                                $allEnergyMeterDonor->energy_system_id = $request->energy_system_id;
                                $allEnergyMeterDonor->donor_id = $request->donor_id[$i];
                                $allEnergyMeterDonor->save();
                            }
                        }
                    }
                }
            } else if($request->compound_id) {

                $allEnergyMeters = DB::table('all_energy_meters')
                    ->join('communities', 'all_energy_meters.community_id', 'communities.id')
                    ->where('communities.id', $request->community_id)
                    ->join('households', 'all_energy_meters.household_id', 'households.id')
                    ->join('compound_households', 'households.id', 'compound_households.household_id')
                    ->where('all_energy_meters.is_archived', 0)
                    ->where('households.is_archived', 0)
                    ->where('compound_households.compound_id', $request->compound_id)
                    ->select("all_energy_meters.id", "all_energy_meters.community_id")
                    ->get();
    
                if($allEnergyMeters) {

                    foreach($allEnergyMeters as $allEnergyMeter) {
    
                        for($i=0; $i < count($request->donor_id); $i++) {
            
                            $exisitAllEnergyMeterDonor = AllEnergyMeterDonor::where('all_energy_meter_id', $allEnergyMeter->id)
                                ->where('donor_id', $request->donor_id)
                                ->where('compound_id', $request->compound_id)
                                ->first();
                            
                            if($exisitAllEnergyMeterDonor) {

                            } else {

                                $allEnergyMeterDonor = new AllEnergyMeterDonor();
                                $allEnergyMeterDonor->all_energy_meter_id = $allEnergyMeter->id;
                                $allEnergyMeterDonor->community_id = $allEnergyMeter->community_id;
                                $allEnergyMeterDonor->compound_id = $request->compound_id;
                                $allEnergyMeterDonor->donor_id = $request->donor_id[$i];
                                $allEnergyMeterDonor->save();
                            }
                        }
                    }
                }
            } else if($request->compound_id == null) {

                $allEnergyMeters = AllEnergyMeter::where("community_id", $request->community_id)->get();

                if($allEnergyMeters) {
                    foreach($allEnergyMeters as $allEnergyMeter) {
    
                        for($i=0; $i < count($request->donor_id); $i++) {
            
                            $exisitAllEnergyMeterDonor = AllEnergyMeterDonor::where('all_energy_meter_id', $allEnergyMeter->id)
                                ->where('donor_id', $request->donor_id)
                                ->first();

                            if($exisitAllEnergyMeterDonor) {

                            } else {

                                $allEnergyMeterDonor = new AllEnergyMeterDonor();
                                $allEnergyMeterDonor->all_energy_meter_id = $allEnergyMeter->id;
                                $allEnergyMeterDonor->community_id = $allEnergyMeter->community_id;
                                $allEnergyMeterDonor->donor_id = $request->donor_id[$i];
                                $allEnergyMeterDonor->save();
                            }
                        }
                    }
                }
            }
        }
       
        $allWaterHolders = AllWaterHolder::where("community_id", $request->community_id)->get();

        // Add donors for water users
        if($request->service_id == 2) {

            foreach($allWaterHolders as $allWaterHolder) {
    
                for($i=0; $i < count($request->donor_id); $i++) {
    
                    $allWaterHolderDonor = new AllWaterHolderDonor();
                    $allWaterHolderDonor->all_water_holder_id = $allWaterHolder->id;
                    if($request->community_id) $allWaterHolderDonor->community_id = $allWaterHolder->community_id;
                    if($request->compound_id) $allWaterHolderDonor->compound_id = $allWaterHolder->compound_id;
                    $allWaterHolderDonor->donor_id = $request->donor_id[$i];
                    $allWaterHolderDonor->save();
                }
            }
        }

        $internetUsers = InternetUser::where("community_id", $request->community_id)->get();

        // Add donors for internet users
        if($request->service_id == 3) {

            foreach($internetUsers as $internetUser) {
    
                for($i=0; $i < count($request->donor_id); $i++) {
    
                    $internetUserDonor = new InternetUserDonor();
                    $internetUserDonor->internet_user_id = $internetUser->id;
                    $internetUserDonor->community_id = $internetUser->community_id;
                    $internetUserDonor->donor_id = $request->donor_id[$i];
                    $internetUserDonor->save();
                }
            }
        }

        return redirect()->back()->with('message', 'Community Donors Updated Successfully!');
    }

    /**
     * View Edit page.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function editPage($id)
    {
        $communityDonor = CommunityDonor::findOrFail($id);
        
        return response()->json($communityDonor);
    } 

    /**
     * Update resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateCommunityDonor(int $id, int $donor_id, int $service_id)
    {
        $communityDonor = CommunityDonor::findOrFail($id);
        
        if($communityDonor->donor_id != $donor_id) {

            $allEnergyMeters = AllEnergyMeter::where("community_id", $communityDonor->community_id)->get();
            $allWaterHolders = AllWaterHolder::where("community_id", $communityDonor->community_id)->get();
            $internetUsers = InternetUser::where("community_id", $communityDonor->community_id)->get();

            if($service_id == 1) {
    
                if($allEnergyMeters) {
                    foreach($allEnergyMeters as $allEnergyMeter) {
        
                        $allEnergyMeterDonor = new AllEnergyMeterDonor();
                        $allEnergyMeterDonor->all_energy_meter_id = $allEnergyMeter->id;
                        $allEnergyMeterDonor->community_id = $allEnergyMeter->community_id;
                        $allEnergyMeterDonor->donor_id = $donor_id;
                        $allEnergyMeterDonor->save();
                    }

                    $oldEnergyMeterDonors = AllEnergyMeterDonor::where("community_id", $communityDonor->community_id)
                        ->where("donor_id", $communityDonor->donor_id)
                        ->get();

                    if($oldEnergyMeterDonors) {
                        
                        foreach($oldEnergyMeterDonors as $oldEnergyMeterDonor) {

                            $oldEnergyMeterDonor->delete();
                        }
                    }
                }

            } else if($service_id == 2) {

                // Add donors for water users
                if($allWaterHolders) {
                    foreach($allWaterHolders as $allWaterHolder) {
        
                        $allWaterHolderDonor = new AllWaterHolderDonor();
                        $allWaterHolderDonor->all_water_holder_id = $allWaterHolder->id;
                        $allWaterHolderDonor->community_id = $allWaterHolder->community_id;
                        $allWaterHolderDonor->donor_id = $donor_id;
                        $allWaterHolderDonor->save();
                    }

                    $oldWaterHolderDonors = AllWaterHolderDonor::where("community_id", $communityDonor->community_id)
                        ->where("donor_id", $communityDonor->donor_id)
                        ->get();

                    if($oldWaterHolderDonors) {
                        
                        foreach($oldWaterHolderDonors as $oldWaterHolderDonor) {

                            $oldWaterHolderDonor->delete();
                        }
                    }
                }
            } else if($service_id == 3) {

                // Add donors for internet users
                if($internetUsers) {
                    foreach($internetUsers as $internetUser) {
        
                        $internetUserDonor = new InternetUserDonor();
                        $internetUserDonor->internet_user_id = $internetUser->id;
                        $internetUserDonor->community_id = $internetUser->community_id;
                        $internetUserDonor->donor_id = $donor_id;
                        $internetUserDonor->save();
                    }

                    $oldInternetUserDonors = AllInternetUserDonor::where("community_id", $communityDonor->community_id)
                        ->where("donor_id", $communityDonor->donor_id)
                        ->get();

                    if($oldInternetUserDonors) {
                        
                        foreach($oldInternetUserDonors as $oldInternetUserDonor) {
                            
                            $oldInternetUserDonor->delete();
                        }
                    }
                } 
            }
        }

        $communityDonor->donor_id = $donor_id;
        $communityDonor->save();

        $response = 1;

        return response()->json($response );
    }

    /**
     * Update an existing resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request, int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $community = Community::findOrFail($id);

        $community->save();

        return redirect('/donor')->with('message', 'Donors Updated Successfully!');
    }

    /**
     * Get a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getDonorData(int $id)
    {
        $donor = Donor::find($id);
        $response = array();

        if(!empty($donor)) {

            $response['english_name'] = $donor->english_name;
            $response['arabic_name'] = $donor->arabic_name;
            $response['id'] = $id;

            $response['success'] = 1;
        } else {

            $response['success'] = 0;
        }

        return response()->json($response);
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
        $allEnergyMeterDonors = null;
        
        if($donor->energy_system_id) {

            $allEnergyMeterDonors = AllEnergyMeterDonor::where("donor_id", $donor->donor_id)
                ->where("energy_system_id", $donor->energy_system_id)
                ->where('is_archived', 0)
                ->get();
        } else if($donor->compound_id) {

            $allEnergyMeterDonors = AllEnergyMeterDonor::where("donor_id", $donor->donor_id)
                ->where("compound_id", $donor->compound_id)
                ->where('is_archived', 0)
                ->get();
        } else if($donor->compound_id == null) {

            $allEnergyMeterDonors = AllEnergyMeterDonor::where("donor_id", $donor->donor_id)
                ->where("community_id", $donor->community_id)
                ->where('is_archived', 0)
                ->get();
        }
       
        $allWaterDonors = AllWaterHolderDonor::where("donor_id", $donor->donor_id)
            ->where("community_id", $donor->community_id)
            ->where('is_archived', 0)
            ->get();
        $internetUserDonors = InternetUserDonor::where("donor_id", $donor->donor_id)
            ->where("community_id", $donor->community_id)
            ->where('is_archived', 0)
            ->get();

        if($donor->service_id == 1) {

            if($allEnergyMeterDonors) {

                foreach($allEnergyMeterDonors as $allEnergyMeterDonor) {

                    $allEnergyMeterDonor->is_archived = 1;
                    $allEnergyMeterDonor->save();
                }
            }
        }

        // Delete water users donors while delteing donor
        if($donor->service_id == 2) {
            if($allWaterDonors) {
                foreach($allWaterDonors as $allWaterDonor) {
                    $allWaterDonor->is_archived = 1;
                    $allWaterDonor->save();
                }
            }
        }

        // Delete internet users donors while delteing donor
        if($donor->service_id == 3) {
            if($internetUserDonors) {
                foreach($internetUserDonors as $internetUserDonor) {
                    $internetUserDonor->is_archived = 1;
                    $internetUserDonor->save();
                }
            }
        }

        if($donor) {

            $donor->is_archived = 1;
            $donor->save();

            $response['success'] = 1;
            $response['msg'] = 'Community Donor Deleted successfully'; 
        } else {

            $response['success'] = 0;
            $response['msg'] = 'Invalid ID.';
        }

        return response()->json($response); 
    }
}