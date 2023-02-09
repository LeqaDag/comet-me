<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use DB;
use Route;
use App\Models\Region;
use App\Models\SubRegion;
use Carbon\Carbon;
use Image;
use DataTables;

class RegionController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $subregion = Region::create($request->all());
        $subregion->save();

        return redirect()->back()->with('message', 'New Region Added Successfully!');
    }
}