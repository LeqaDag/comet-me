<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
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
        $url = "http://185.190.140.86/api/users/";
        $responses = Http::get($url);
      
        $responses = $responses->getBody()->getContents();

        $j = json_decode($responses, true);
        die($j); 

        foreach($responses as $response) {

            die($response);
        }
    }
}