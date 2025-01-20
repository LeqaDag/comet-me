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

        return $this->getInternetUsers2();
    }

    public function getInternetUsers() {

        $data = Http::get('http://185.190.140.86/api/users/');

        $holders = json_decode($data, true) ;

        $numberMeters = 0;
        
        // Get Last comet_id
        $last_comet_id = Household::latest('id')->value('comet_id');

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
                            if ($allEnergyMeter->household_id !== null && $allEnergyMeter->household_id !== 0) {

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
                            } else if($allEnergyMeter->public_structure_id !== 0 || $allEnergyMeter->public_structure_id !== null) {

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
                            if($sharedHolder->household_id !== 0 || $sharedHolder->household_id !== null) {

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
                            } else if($sharedHolder->public_structure_id !== 0 || $sharedHolder->public_structure_id !== null) {

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
                                $newHousehold->comet_id = $last_comet_id++;
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
        
        return response()->json(InternetUser::all());
    }


    /**
     * Get Last Internert Users & Update Exists
     * 
     * @return mixed
     */
    public function getInternetUsers2() {

        $data = Http::get('http://185.190.140.86/api/users/');

        $holders = json_decode($data, true) ;

        $numberMeters = 0;
        
        // Get Last comet_id
        $last_house_comet_id = Household::latest('comet_id')->whereNotNull('comet_id')->value('comet_id');
        $last_public_comet_id = PublicStructure::latest('comet_id')->whereNotNull('comet_id')->value('comet_id');
        $last_fake_meter_number = PublicStructure::where('fake_meter_number', 'LIKE', '100%')->latest('id')->value('fake_meter_number');

        $user_type = 0;
        $user_id = 0;
        foreach($holders as $holder):
            
            if($holder["user_group_name"] != "Comet Employee"):

                // Check Community
                $community = Community::where("arabic_name", $holder["user_group_name"])->first();
                if($community):
                
                    
                    $old_comet_id = isset($holder["comet_id"]) && $holder["comet_id"] > 0 ? trim($holder["comet_id"]) : "NO_ID";
                    // Check User Type If Public Structure
                    if($holder["is_public_entity"] == 1):
                        
                        // Check Or Insert 
                        $public_user = PublicStructure::where('comet_id', $old_comet_id)->orWhere('arabic_name',  'LIKE', trim($holder["holder_full_name"]))->first();
                        
                        // New Public User
                        if(!$public_user):
                            $public_user = new PublicStructure;
                            $public_user->english_name = trim($holder["holder_full_name"]);
                            $public_user->fake_meter_number = ++$last_fake_meter_number;
                            $public_user->comet_id = ++$last_public_comet_id;
                        endif;
                        
                        // Update Last Data
                        $public_user->arabic_name = trim($holder["holder_full_name"]);
                        $public_user->phone_number = $holder["holder_mobile"];
                        $public_user->community_id = $community->id;
                        $public_user->out_of_comet = $holder["out_of_comet"] ?? NULL;
                        $public_user->save();
                        $user_id = $public_user->id;
                        $user_type = 0;

                    else: // House Hold Users

                        // Check Or Insert 
                        $h_user = Household::where('comet_id', $old_comet_id)->orWhere('arabic_name', 'LIKE', trim($holder["holder_full_name"]))->first();
                        
                        // New Public User
                        if(!$h_user):
                            $h_user = new Household;
                            $h_user->english_name = trim($holder["holder_full_name"]);
                            $h_user->fake_meter_number = ++$last_fake_meter_number;
                            $h_user->comet_id = ++$last_house_comet_id;
                            $h_user->internet_holder_young = 1;
                            $h_user->community_name = $community->english_name;
                            $h_user->community_id = $community->id;
                            $h_user->energy_service = !empty($holder["meters_list"]) && isset($holder["meters_list"][0]["sn"]) ? 'Yes' : 'No';
                            $h_user->energy_meter = !empty($holder["meters_list"]) && isset($holder["meters_list"][0]["sn"]) ? 'Yes' : 'No';
                            $h_user->energy_system_status = !empty($holder["meters_list"]) && isset($holder["meters_list"][0]["sn"]) ? 'Served' : 'Not Served';
                        endif;
                        
                        // Update Last Data
                        $h_user->arabic_name = trim($holder["holder_full_name"]);
                        $h_user->phone_number = $holder["holder_mobile"];
                        $h_user->out_of_comet = $holder["out_of_comet"] ?? NULL;
                        $h_user->internet_system_status = 'Served';
                        $h_user->save();

                        $user_id = $h_user->id;
                        $user_type = 1;

                    endif;

                    // Insert New Internet User
                    $column = ($user_type == 0) ? 'public_structure_id' : 'household_id';
                    
                    $internetUser = InternetUser::where($column, $user_id)->first() ?? new InternetUser;

                     // Insert New Internet User 
                     $internetUser->public_structure_id = $user_type == 0 ? $user_id : NULL;
                     $internetUser->household_id = $user_type == 1 ? $user_id : NULL;
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
                     $internetUser->number_of_people = $holder["port_limit"] ?? 1;
                     $internetUser->save();

                    
                endif; // End Check Community
            endif;

        endforeach;

        return response()->json(InternetUser::all());
    }
}