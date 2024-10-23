<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\User;
use App\Models\AllEnergyMeter;
use App\Models\Community;
use App\Models\CommunityService;
use App\Models\InternetUser;
use App\Models\Household;
use App\Models\PublicStructure;
use App\Models\YoungHolder;
use Auth;
use DB;  
use Route; 

class InternetHolderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */ 
    public function index()
    {	
        /** 
         * this api should contains the following: 
         * 1. Main users : meter number must be appeared. 
         * 2. Sahred user : after getting the flag for him from here http://comet-me.info/api/energy-holder 
         *    must take the name from the api (add is_main to internet is_main)
         * 3. Young holder: add his full name to add him as a new houshold on DB, then add his english name
         * 4. Main Public : meter number must be appeared. 
         * 5. Sahred Public : after getting the flag from here http://comet-me.info/api/energy-holder 
         *    must take the name from the api
         * 6. New Public Structures: add is_public flag to the internet flag to add them in the platform 
        */

        $this->getInternetUsers();

       die(InternetUser::get());
    }

    public function getInternetUsers() {

        $data = Http::get('http://185.190.140.86/api/users/');

        $holders = json_decode($data, true) ;

        $numberMeters = 0;
        
        foreach($holders as $holder) {
            
            if($holder["user_group_name"] == "Comet Employee") {
            } else {

                $community = Community::where("arabic_name", $holder["user_group_name"])->first();
                if($community) {

                    // retrieve the community 
                    $community->internet_service = "Yes";
                    $community->save();

                    $communityService = new CommunityService();
                    $communityService->service_id = 3;
                    $communityService->community_id = $community->id;
                    $communityService->save();

                    // create new internet user 
                    $internetUser = new InternetUser();
                    $internetUser->internet_status_id = 1;
                    $internetUser->start_date = $holder["created_on"];
                    $internetUser->active = $holder["active"];
                    $internetUser->last_purchase_date = $holder["last_purchase_date"];
                    $internetUser->expired_gt_than_30d = $holder["expired_gt_than_30d"];
                    $internetUser->expired_gt_than_60d = $holder["expired_gt_than_60d"];
                    $internetUser->is_expire = $holder["is_expire"];
                    $internetUser->paid = $holder["paid"];
                    $internetUser->community_id = $community->id;
                    $internetUser->is_hotspot = $holder["is_hotspot"];
                    $internetUser->is_ppp = $holder["is_ppp"];
                    $internetUser->from_api = 1;

                    // main users 
                    if($holder["have_meter"] == 1) {

                        // first step is relaying on the meter number for the main holders 
                        $allEnergyMeter = AllEnergyMeter::where('is_archived', 0)
                            ->where('meter_number', $holder["meters_list"][0]["sn"])
                            ->first();
        
                        if($allEnergyMeter) {

                            // Check if the meter is for user (new/existing main user)
                            if($allEnergyMeter->household_id != 0 || $allEnergyMeter->household_id != null) {

                                $household = Household::findOrFail($allEnergyMeter->household_id);
                                $household->phone_number = $holder["cardnum"];
                                $household->internet_system_status = "Served";
                                $household->save();

                                $exisiInternetHolder = InternetUser::where('household_id', $allEnergyMeter->household_id)->first();
                                if($exisiInternetHolder) {

                                    $exisiInternetHolder->active = $holder["active"];
                                    $exisiInternetHolder->last_purchase_date = $holder["last_purchase_date"];
                                    $exisiInternetHolder->expired_gt_than_30d = $holder["expired_gt_than_30d"];
                                    $exisiInternetHolder->expired_gt_than_60d = $holder["expired_gt_than_60d"];
                                    $exisiInternetHolder->is_expire = $holder["is_expire"];
                                    $exisiInternetHolder->paid = $holder["paid"];
                                    $exisiInternetHolder->is_hotspot = $holder["is_hotspot"];
                                    $exisiInternetHolder->is_ppp = $holder["is_ppp"];
                                    $exisiInternetHolder->from_api = 1;
                                    $exisiInternetHolder->save();
                                } else {

                                    $internetUser->household_id = $allEnergyMeter->household_id;
                                    $internetUser->community_id = $allEnergyMeter->community_id;
                                    $internetUser->save();
                                }

                            // new/existing main public 
                            } else if($allEnergyMeter->public_structure_id != 0 || $allEnergyMeter->public_structure_id != null) {

                                $publicStructure = PublicStructure::findOrFail($allEnergyMeter->public_structure_id);
                                $publicStructure->phone_number = $holder["cardnum"];
                                $publicStructure->save();

                                $exisiInternetPublic = InternetUser::where('public_structure_id', $allEnergyMeter->public_structure_id)->first();
                                if($exisiInternetPublic) {

                                    $exisiInternetPublic->active = $holder["active"];
                                    $exisiInternetPublic->last_purchase_date = $holder["last_purchase_date"];
                                    $exisiInternetPublic->expired_gt_than_30d = $holder["expired_gt_than_30d"];
                                    $exisiInternetPublic->expired_gt_than_60d = $holder["expired_gt_than_60d"];
                                    $exisiInternetPublic->is_expire = $holder["is_expire"];
                                    $exisiInternetPublic->paid = $holder["paid"];
                                    $exisiInternetPublic->is_hotspot = $holder["is_hotspot"];
                                    $exisiInternetPublic->is_ppp = $holder["is_ppp"];
                                    $exisiInternetPublic->from_api = 1;
                                    $exisiInternetPublic->save();
                                } else {

                                    $internetUser->public_structure_id = $allEnergyMeter->public_structure_id;
                                    $internetUser->community_id = $allEnergyMeter->community_id;
                                    $internetUser->save();
                                }
                            }
                        }  
                        
                        // This code is for shared holders
                        $sharedHolder = AllEnergyMeter::where('is_archived', 0)
                            ->where('fake_meter_number', $holder["meters_list"][0]["sn"])
                            ->first();

                        if($sharedHolder) {

                            // Check if the meter is for shared user (new/existing shared user)
                            if($sharedHolder->household_id != 0 || $sharedHolder->household_id != null) {

                                $household = Household::findOrFail($sharedHolder->household_id);
                                $household->phone_number = $holder["cardnum"];
                                $household->internet_system_status = "Served";
                                $household->save();

                                $exisiInternetHolder = InternetUser::where('household_id', $sharedHolder->household_id)->first();
                                if($exisiInternetHolder) {

                                    $exisiInternetHolder->active = $holder["active"];
                                    $exisiInternetHolder->last_purchase_date = $holder["last_purchase_date"];
                                    $exisiInternetHolder->expired_gt_than_30d = $holder["expired_gt_than_30d"];
                                    $exisiInternetHolder->expired_gt_than_60d = $holder["expired_gt_than_60d"];
                                    $exisiInternetHolder->is_expire = $holder["is_expire"];
                                    $exisiInternetHolder->paid = $holder["paid"];
                                    $exisiInternetHolder->is_hotspot = $holder["is_hotspot"];
                                    $exisiInternetHolder->is_ppp = $holder["is_ppp"];
                                    $exisiInternetHolder->from_api = 1;
                                    $exisiInternetHolder->save();
                                } else {

                                    $internetUser->household_id = $sharedHolder->household_id;
                                    $internetUser->community_id = $sharedHolder->community_id;
                                    $internetUser->save();
                                }

                            // new/existing shared public 
                            } else if($sharedHolder->public_structure_id != 0 || $sharedHolder->public_structure_id != null) {

                                $publicStructure = PublicStructure::findOrFail($sharedHolder->public_structure_id);
                                $publicStructure->phone_number = $holder["cardnum"];
                                $publicStructure->save();

                                $exisiInternetPublic = InternetUser::where('public_structure_id', $sharedHolder->public_structure_id)->first();
                                if($exisiInternetPublic) {

                                    $exisiInternetPublic->active = $holder["active"];
                                    $exisiInternetPublic->last_purchase_date = $holder["last_purchase_date"];
                                    $exisiInternetPublic->expired_gt_than_30d = $holder["expired_gt_than_30d"];
                                    $exisiInternetPublic->expired_gt_than_60d = $holder["expired_gt_than_60d"];
                                    $exisiInternetPublic->is_expire = $holder["is_expire"];
                                    $exisiInternetPublic->paid = $holder["paid"];
                                    $exisiInternetPublic->is_hotspot = $holder["is_hotspot"];
                                    $exisiInternetPublic->is_ppp = $holder["is_ppp"];
                                    $exisiInternetPublic->from_api = 1;
                                    $exisiInternetPublic->save();
                                } else {

                                    $internetUser->public_structure_id = $sharedHolder->public_structure_id;
                                    $internetUser->community_id = $sharedHolder->community_id;
                                    $internetUser->save();
                                }
                            }
                        }
                        
                        // This code is for out of comet public structures 
                        $outOfCometPublic = PublicStructure::where("is_archived", 0)
                            ->where("out_of_comet", 1)
                            ->where("fake_meter_number", $holder["meters_list"][0]["sn"])
                            ->first();

                        if($outOfCometPublic) {

                            $outOfCometPublic->phone_number = $holder["cardnum"];
                            $outOfCometPublic->save();

                            $exisiInternetPublic = InternetUser::where('public_structure_id', $outOfCometPublic->id)->first();
                            if($exisiInternetPublic) {

                                $exisiInternetPublic->active = $holder["active"];
                                $exisiInternetPublic->last_purchase_date = $holder["last_purchase_date"];
                                $exisiInternetPublic->expired_gt_than_30d = $holder["expired_gt_than_30d"];
                                $exisiInternetPublic->expired_gt_than_60d = $holder["expired_gt_than_60d"];
                                $exisiInternetPublic->is_expire = $holder["is_expire"];
                                $exisiInternetPublic->paid = $holder["paid"];
                                $exisiInternetPublic->is_hotspot = $holder["is_hotspot"];
                                $exisiInternetPublic->is_ppp = $holder["is_ppp"];
                                $exisiInternetPublic->from_api = 1;
                                $exisiInternetPublic->save();
                            } else {

                                $internetUser->public_structure_id = $outOfCometPublic->id;
                                $internetUser->community_id = $outOfCometPublic->community_id;
                                $internetUser->save();
                            }
                        }

                        // This code is for young holders
                        if($holder["is_young"] == 1) {

                            $youngHolder = YoungHolder::where("is_archived", 0)
                                ->where("fake_meter_number", $holder["meters_list"][0]["sn"])
                                ->first();
                            
                            if($youngHolder) {

                                $household = Household::findOrFail($youngHolder->household_id);
                                $household->phone_number = $holder["cardnum"];
                                $household->internet_system_status = "Served";
                                $household->save();

                                $exisiInternetHolder = InternetUser::where('household_id', $youngHolder->household_id)->first();
                                if($exisiInternetHolder) {

                                    $exisiInternetHolder->active = $holder["active"];
                                    $exisiInternetHolder->last_purchase_date = $holder["last_purchase_date"];
                                    $exisiInternetHolder->expired_gt_than_30d = $holder["expired_gt_than_30d"];
                                    $exisiInternetHolder->expired_gt_than_60d = $holder["expired_gt_than_60d"];
                                    $exisiInternetHolder->is_expire = $holder["is_expire"];
                                    $exisiInternetHolder->paid = $holder["paid"];
                                    $exisiInternetHolder->is_hotspot = $holder["is_hotspot"];
                                    $exisiInternetHolder->is_ppp = $holder["is_ppp"];
                                    $exisiInternetHolder->from_api = 1;
                                    $exisiInternetHolder->save();
                                } else {

                                    $internetUser->household_id = $youngHolder->household_id;
                                    $internetUser->community_id = $community->id;
                                    $internetUser->save();
                                }
                            } else {

                                $household = Household::where("is_archived", 0)
                                    ->where("arabic_name", $holder["holder_full_name"])
                                    ->first();

                                $newHousehold = new Household();
                                $newHousehold->arabic_name = $holder["holder_full_name"];
                                $newHousehold->phone_number = $holder["holder_mobile"];
                                $newHousehold->internet_holder_young = 1;
                                $newHousehold->community_id = $community->id;
                                $newHousehold->internet_system_status = "Served";
                                $newHousehold->profession_id = 1;
                                $newHousehold->save();
            
                                $internetUser->household_id = $newHousehold->id;
                                $internetUser->save();

                                // Function to create the fake meter number relay on he meter number
                            }
                        }

                        // should send a message called "you've a new meter number not registering on the DB" 
                        if(!$allEnergyMeter) {

                        } 

                    } else if($holder["have_meter"] == 0) {

                        // Retrevie all the names with null meters 
                        
                    }
                }
            }
        }
    }
}