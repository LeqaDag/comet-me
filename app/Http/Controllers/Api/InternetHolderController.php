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
 
        $internetData =  Http::get('http://185.190.140.86/api/users/');
        $internetHolders = json_decode($internetData, true);

        foreach($internetHolders as $internetHolder) {

            // Not including comet employee contracts 
            if($internetHolder["user_group_name"] == "Comet Employee") {
                
            } else {

                // create new internet user
                $internetUser = new InternetUser();
                $internetUser->internet_status_id = 1;
                $internetUser->start_date = $internetHolder["created_on"];
                $internetUser->active = $internetHolder["active"];
                $internetUser->last_purchase_date = $internetHolder["last_purchase_date"];
                $internetUser->expired_gt_than_30d = $internetHolder["expired_gt_than_30d"];
                $internetUser->expired_gt_than_60d = $internetHolder["expired_gt_than_60d"];
                $internetUser->is_expire = $internetHolder["is_expire"];
                $internetUser->paid = $internetHolder["paid"];
                $internetUser->is_hotspot = $internetHolder["is_hotspot"];
                $internetUser->is_ppp = $internetHolder["is_ppp"];

                if($internetHolder["have_meter"] == 1) {

                    // first step is relaying on the meter number
                    $allEnergyMeter = AllEnergyMeter::where('is_archived', 0)
                        ->where('meter_number', $internetHolder["meters_list"][0]["sn"])
                        ->first();
     
                    if($allEnergyMeter) {

                        // retrieve the community 
                        $community = Community::findOrFail($allEnergyMeter->community_id);
                        $community->internet_service = "Yes";
                        $community->save();

                        $communityService = new CommunityService();
                        $communityService->service_id = 3;
                        $communityService->community_id = $community->id;
                        $communityService->save();

                        // Check if the meter is for user (new/existing main user)
                        if($allEnergyMeter->household_id != 0 || $allEnergyMeter->household_id != null) {

                            $household = Household::findOrFail($allEnergyMeter->household_id );
                            $household->phone_number = $internetHolder["cardnum"];
                            $household->internet_system_status = "Served";
                            $household->save();

                            $exisiInternetHolder = InternetUser::where('household_id', $allEnergyMeter->household_id)->first();
                            if($exisiInternetHolder) {

                                $exisiInternetHolder->active = $internetHolder["active"];
                                $exisiInternetHolder->last_purchase_date = $internetHolder["last_purchase_date"];
                                $exisiInternetHolder->expired_gt_than_30d = $internetHolder["expired_gt_than_30d"];
                                $exisiInternetHolder->expired_gt_than_60d = $internetHolder["expired_gt_than_60d"];
                                $exisiInternetHolder->is_expire = $internetHolder["is_expire"];
                                $exisiInternetHolder->paid = $internetHolder["paid"];
                                $exisiInternetHolder->is_hotspot = $internetHolder["is_hotspot"];
                                $exisiInternetHolder->is_ppp = $internetHolder["is_ppp"];
                                $exisiInternetHolder->save();
                            } else {

                                $internetUser->household_id = $allEnergyMeter->household_id;
                                $internetUser->community_id = $allEnergyMeter->community_id;
                            }

                        // new/existing main public 
                        } else if($allEnergyMeter->public_structure_id != 0 || $allEnergyMeter->public_structure_id != null) {

                            $publicStructure = PublicStructure::findOrFail($allEnergyMeter->public_structure_id);
                            $publicStructure->phone_number = $internetHolder["cardnum"];
                            $publicStructure->save();

                            $exisiInternetPublic = InternetUser::where('public_structure_id', $allEnergyMeter->public_structure_id)->first();
                            if($exisiInternetPublic) {

                                $exisiInternetPublic->active = $internetHolder["active"];
                                $exisiInternetPublic->last_purchase_date = $internetHolder["last_purchase_date"];
                                $exisiInternetPublic->expired_gt_than_30d = $internetHolder["expired_gt_than_30d"];
                                $exisiInternetPublic->expired_gt_than_60d = $internetHolder["expired_gt_than_60d"];
                                $exisiInternetPublic->is_expire = $internetHolder["is_expire"];
                                $exisiInternetPublic->paid = $internetHolder["paid"];
                                $exisiInternetPublic->is_hotspot = $internetHolder["is_hotspot"];
                                $exisiInternetPublic->is_ppp = $internetHolder["is_ppp"];
                                $exisiInternetPublic->save();
                            } else {

                                $internetUser->public_structure_id = $allEnergyMeter->public_structure_id;
                                $internetUser->community_id = $allEnergyMeter->community_id;
                            }
                        }
                    }

                    // should send a message called "you've a new meter number not registering on the DB" 
                    if(!$allEnergyMeter) {

                    } 

                } else if($internetHolder["have_meter"] == 0) {

                    // new/existing shared user
                    if($internetHolder["is_public_entity"] == 0) {

                        $household = Household::where("arabic_name", $internetHolder["holder_full_name"])->first();
                        if($household) {

                            $household->phone_number = $internetHolder["cardnum"];
                            $household->internet_system_status = "Served";
                            $household->save();
    
                            $exisiInternetHolder = InternetUser::where('household_id', $household->id)->first();
                            if($exisiInternetHolder) {
    
                                $exisiInternetHolder->active = $internetHolder["active"];
                                $exisiInternetHolder->last_purchase_date = $internetHolder["last_purchase_date"];
                                $exisiInternetHolder->expired_gt_than_30d = $internetHolder["expired_gt_than_30d"];
                                $exisiInternetHolder->expired_gt_than_60d = $internetHolder["expired_gt_than_60d"];
                                $exisiInternetHolder->is_expire = $internetHolder["is_expire"];
                                $exisiInternetHolder->is_hotspot = $internetHolder["is_hotspot"];
                                $exisiInternetHolder->is_ppp = $internetHolder["is_ppp"];
                                $exisiInternetHolder->paid = $internetHolder["paid"];
                                $exisiInternetHolder->save();
                            } else {
    
                                $internetUser->household_id = $household->id;
                                $internetUser->community_id = $household->community_id;
                            }
                        }

                    // new/existing shared public
                    } else if($internetHolder["is_public_entity"] == 1) {

                        $publicStructure = PublicStructure::where("arabic_name", $internetHolder["holder_full_name"])->first();
                        if($publicStructure) {

                            $publicStructure->phone_number = $internetHolder["cardnum"];
                            $publicStructure->save();
    
                            $exisiInternetPublic = InternetUser::where('public_structure_id', $publicStructure->id)->first();
                            
                            if($exisiInternetPublic) {
    
                                $exisiInternetPublic->active = $internetHolder["active"];
                                $exisiInternetPublic->last_purchase_date = $internetHolder["last_purchase_date"];
                                $exisiInternetPublic->expired_gt_than_30d = $internetHolder["expired_gt_than_30d"];
                                $exisiInternetPublic->expired_gt_than_60d = $internetHolder["expired_gt_than_60d"];
                                $exisiInternetPublic->is_expire = $internetHolder["is_expire"];
                                $exisiInternetPublic->is_hotspot = $internetHolder["is_hotspot"];
                                $exisiInternetPublic->is_ppp = $internetHolder["is_ppp"];
                                $exisiInternetPublic->paid = $internetHolder["paid"];
                                $exisiInternetPublic->save();
                            } else {
    
                                $internetUser->public_structure_id = $publicStructure->id;
                                $internetUser->community_id = $publicStructure->community_id;
                            }
                        }
                    }

                    $community = Community::where("arabic_name", $internetHolder["user_group_name"])->first();
                    // Young holder
                    if($internetHolder["is_young"] == 1 && $internetHolder["is_public_entity"] == 0) {

                        $newHousehold = new Household();
                        $newHousehold->arabic_name = $internetHolder["holder_full_name"];
                        $newHousehold->phone_number = $internetHolder["cardnum"];
                        $newHousehold->internet_holder_young = 1;
                        $newHousehold->community_id = $community->id;
                        $newHousehold->internet_system_status = "Served";
                        $newHousehold->profession_id = 1;
                        $newHousehold->save();
    
                        $internetUser->community_id = $community->id;
                        $internetUser->household_id = $newHousehold->id;
                    
                    // new public structure
                    } else if($internetHolder["is_young"] == 0 && $internetHolder["is_public_entity"] == 1) {

                        $newPublic = new Household();
                        $newPublic->arabic_name = $internetHolder["holder_full_name"];
                        $newPublic->phone_number = $internetHolder["cardnum"];
                        $newPublic->community_id = $community->id;
                        $newPublic->save();
    
                        $internetUser->community_id = $community->id;
                        $internetUser->household_id = $newPublic->id;
                    } 
                }
                
                $internetUser->save();
            }
        }


        // $data = Http::get('http://185.190.140.86/api/users/');

        // $holders = json_decode($data, true) ;

        // foreach($holders as $holder) {

        //     if($holder["user_group_name"] == "Comet Employee" ||
        //         $holder["user_group_name"] == "أبو فلاح	") {
        //     } else {

        //         $community = Community::where("arabic_name", $holder["user_group_name"])->first();
        //         $existCommunityService = CommunityService::where("community_id", $community->id)
        //             ->where("service_id", 3)
        //             ->first();

        //         if($existCommunityService) {

        //         } else {
                    
        //             $communityService = new CommunityService();
        //             $communityService->service_id = 3;
        //             $communityService->community_id = $community->id;
        //             $communityService->save();
        //         }

        //         $household = Household::where("is_archived", 0)
        //             ->where("arabic_name", $holder["holder_full_name"])
        //             ->first();
        //         $public = PublicStructure::where("is_archived", 0)
        //             ->where("arabic_name", $holder["holder_full_name"])
        //             ->first();
                
        //         $internetUser = new InternetUser();
        //         $internetUser->internet_status_id = 1;
        //         $internetUser->start_date = $holder["created_on"];
        //         $internetUser->active = $holder["active"];
        //         $internetUser->last_purchase_date = $holder["last_purchase_date"];
        //         $internetUser->expired_gt_than_30d = $holder["expired_gt_than_30d"];
        //         $internetUser->expired_gt_than_60d = $holder["expired_gt_than_60d"];
        //         $internetUser->is_expire = $holder["is_expire"];
        //         $internetUser->paid = $holder["paid"];
        //         $internetUser->community_id = $community->id;
                
        //         $community->internet_service = "Yes";
        //         $community->save();
                
        //         if($household) {

        //             $household->phone_number = $holder["holder_mobile"];
        //             $household->save();

        //             $existInternetHolder = InternetUser::where("household_id", $household->id)->first();
        //             if($existInternetHolder) {

        //                 $existInternetHolder->is_expire = $holder["is_expire"];
        //                 $existInternetHolder->active = $holder["active"];
        //                 $existInternetHolder->paid = $holder["paid"];
        //                 $existInternetHolder->last_purchase_date = $holder["last_purchase_date"];
        //                 $existInternetHolder->expired_gt_than_30d = $holder["expired_gt_than_30d"];
        //                 $existInternetHolder->expired_gt_than_60d = $holder["expired_gt_than_60d"];
        //                 $existInternetHolder->save();
        //             } else {

        //                 $household->internet_system_status = "Served";
        //                 $household->phone_number = $holder["holder_mobile"];
        //                 $household->save();
        //                 $internetUser->household_id = $household->id;
        //                 $internetUser->save();
        //             }
        //         } else if($public) {

        //             $public->phone_number = $holder["holder_mobile"];
        //             $public->save();

        //             $existInternetPublic = InternetUser::where("public_structure_id", $public->id)->first();
        //             if($existInternetPublic) {

        //                 $existInternetPublic->is_expire = $holder["is_expire"];
        //                 $existInternetPublic->active = $holder["active"];
        //                 $existInternetPublic->paid = $holder["paid"];
        //                 $existInternetPublic->last_purchase_date = $holder["last_purchase_date"];
        //                 $existInternetPublic->expired_gt_than_30d = $holder["expired_gt_than_30d"];
        //                 $existInternetPublic->expired_gt_than_60d = $holder["expired_gt_than_60d"];
        //                 $existInternetPublic->save();
        //             } else {
                         
        //                 $internetUser->public_structure_id = $public->id;
        //                 $internetUser->save();
        //             }
        //         } else { 

        //             if($holder["is_public_entity"] == 0) {

        //                 $newHousehold = new Household();
        //                 $newHousehold->arabic_name = $holder["holder_full_name"];
        //                 $newHousehold->phone_number = $holder["holder_mobile"];
        //                 $newHousehold->internet_holder_young = 1;
        //                 $newHousehold->community_id = $community->id;
        //                 $newHousehold->internet_system_status = "Served";
        //                 $newHousehold->profession_id = 1;
        //                 $newHousehold->save();
    
        //                 $internetUser->household_id = $newHousehold->id;
        //                 $internetUser->save();
        //             } else if($holder["is_public_entity"] == 1) {

        //             }
        //         }
        //     }
        // }

       die(InternetUser::get());
    }
}