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
        $data = Http::get('http://185.190.140.86/api/users/');

        //$input = html_entity_decode($data);

        //$manage = json_decode(preg_replace('/\s+/', '', $data), true );
        $type = gettype($data);
        die($data->body().length());
    }
}