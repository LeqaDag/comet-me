<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\User;
use App\Models\Community;
use App\Models\InternetUser;
use App\Models\Household;
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

        $holders = json_decode(preg_replace('/\s+/', '', $data), true );

        foreach($holders as $holder) {

            // Mufaqara 7
            if($holder["user_group_id"] == 2) {
 

            // Susya 15
            } else if($holder["user_group_id"] == 3) {

            // Um al-Khair South 9
            } else if($holder["user_group_id"] == 4) {

            // Tuba 5
            } else if($holder["user_group_id"] == 5) {

            // Jib al-Deeb 22
            } else if($holder["user_group_id"] == 6) {

            // Sha'ab al-Buttum 9
            } else if($holder["user_group_id"] == 7) {

            // Um al-Khair South 8
            } else if($holder["user_group_id"] == 8) {

            // Ghuiwn 6
            } else if($holder["user_group_id"] == 9) {

            // Khallet al-Dabe'a 2
            } else if($holder["user_group_id"] == 10) {
 
            // Ras al-Tin 16
            } else if($holder["user_group_id"] == 12) {

            // Maghyer al-Abeed 2
            } else if($holder["user_group_id"] == 13) {

            // Sfai Fouq 4
            } else if($holder["user_group_id"] == 14) {

            // Wadi Rakhim 8
            } else if($holder["user_group_id"] == 15) {

            // Heribet al-Nabi 2
            } else if($holder["user_group_id"] == 16) {

                dd(preg_replace("/[^\p{L}[:space:]]/u",'',$holder["holder_full_name"]));
                dd(str_replace(" ", "ضضض", $holder["holder_full_name"]));
                $internetUser = new InternetUser();
                $internetUser->internet_status_id = 1;
                $internetUser->start_date = $holder["created_on"];
                $internetUser->internet_status_id = 1;
                $internetUser->community_id = 104;
                $household = Household::where("arabic_name", $holder["holder_full_name"])->first();
                if($household) {

                    $internetUser->household_id = $household->id;
                    $internetUser->save();
                } else {

                    $newHousehold = new Household();
                    $newHousehold->arabic_name = $holder["holder_full_name"];
                    $newHousehold->internet_holder_young = 1;
                    $newHousehold->community_id = 104;
                    $newHousehold->profession_id = 1;
                    $newHousehold->save();

                    $internetUser->household_id = $newHousehold->id;
                    $internetUser->save();
                }
            }
            // Wadi Jehish 3
            else if($holder["user_group_id"] == 17) {

            }
            // Magher al-Deir 7
            else if($holder["user_group_id"] == 18) {

            }
            
        }

       die(InternetUser::get());
    }
}