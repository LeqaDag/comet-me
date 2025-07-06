<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\Incident;
use Illuminate\Support\Facades\Cache;
use Auth;
use DB;
use Route;

class AllInicdentTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {	
        $incidentTypes = Incident::whereNotIn('english_name', ['SWO', 'Seizures'])->select("english_name", 
            "arabic_name")->get();

        $data = collect([$incidentTypes])->flatten();

        return response()->json([
            'icnidents' => $incidentTypes ?: 'No data found'
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }
}