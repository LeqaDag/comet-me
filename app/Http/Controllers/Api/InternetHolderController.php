<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\User;
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
        $data = Http::get('http://185.190.140.86/api/users/');

        $holders = json_decode($data, true) ;

        foreach($holders as $holder) {

            if($holder["user_group_name"] == "Comet Employee" ||
                $holder["user_group_name"] == "أبو فلاح	") {
            } else {

                $community = Community::where("arabic_name", $holder["user_group_name"])->first();
                $existCommunityService = CommunityService::where("community_id", $community->id)
                    ->where("service_id", 3)
                    ->first();

                if($existCommunityService) {

                } else {
                    
                    $communityService = new CommunityService();
                    $communityService->service_id = 3;
                    $communityService->community_id = $community->id;
                    $communityService->save();
                }

                $household = Household::where("is_archived", 0)
                    ->where("arabic_name", $holder["holder_full_name"])
                    ->first();
                $public = PublicStructure::where("is_archived", 0)
                    ->where("arabic_name", $holder["holder_full_name"])
                    ->first();
                
                if($community == null) dd($holder["user_group_name"] );
                $internetUser = new InternetUser();
                $internetUser->internet_status_id = 1;
                $internetUser->start_date = $holder["created_on"];
                $internetUser->active = $holder["active"];
                $internetUser->is_expire = $holder["is_expire"];
                $internetUser->community_id = $community->id;
                
                $community->internet_service = "Yes";
                $community->save();
                
                if($household) {

                    $existInternetHolder = InternetUser::where("household_id", $household->id)->first();
                    if($existInternetHolder) {

                    } else {
                        $household->internet_system_status = "Served";
                        $household->save();
                        $internetUser->household_id = $household->id;
                        $internetUser->save();
                    }
                } else if($public) {

                    $existInternetPublic = InternetUser::where("public_structure_id", $public->id)->first();
                    if($existInternetPublic) {

                    } else {
                         
                        $internetUser->public_structure_id = $public->id;
                        $internetUser->save();
                    }
                } else { 

                    $newHousehold = new Household();
                    $newHousehold->arabic_name = $holder["holder_full_name"];
                    $newHousehold->internet_holder_young = 1;
                    $newHousehold->community_id = $community->id;
                    $newHousehold->internet_system_status = "Served";
                    $newHousehold->profession_id = 1;
                    $newHousehold->save();

                    $internetUser->household_id = $newHousehold->id;
                    $internetUser->save();
                }
            }
        }

       die(InternetUser::get());
    }
}