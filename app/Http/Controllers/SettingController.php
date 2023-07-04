<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use DB;
use Route;
use App\Models\Setting;
use Carbon\Carbon;
use DataTables;
use mikehaertl\wkhtmlto\Pdf;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Arr;
use Excel;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {	
        if (Auth::guard('user')->user() != null) {

            $settings = Setting::get();

            return view('admin.setting.index', compact('settings'));

        } else {

            return view('errors.not-found');
        }
    }

    /**
     * View resource by id.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {	
        $setting = Setting::findOrFail($id);
        $response = $setting;

        return response()->json($response);
    }

     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateSetting(Request $request, int $id)
    {
        $setting = Setting::findOrFail($request->id);
        $setting->link = $request->link;
        $setting->name = $request->program;
        $setting->english_name = $request->english_name;
        $setting->arabic_name = $request->arabic_name;
        $setting->save();
 
        $response = 1;

        return response()->json($response );
    }
}