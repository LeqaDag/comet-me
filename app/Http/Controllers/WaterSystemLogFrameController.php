<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\User;
use App\Models\Community;
use App\Models\WaterSystem;
use App\Models\WaterSystemLogFrame;
use App\Models\H2oSystemIncident;
use App\Exports\WaterSystemLogframeExport;
use Auth;
use DB;
use Route;
use Excel;
use DataTables;

class WaterSystemLogFrameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {	
        if (Auth::guard('user')->user() != null) {

            if ($request->ajax()) { 

                $data = DB::table('water_system_log_frames')
                    ->join('water_systems', 'water_system_log_frames.water_system_id', 
                        'water_systems.id')
                    ->where('water_system_log_frames.is_archived', 0)
                    ->select('water_system_log_frames.id as id', 'water_system_log_frames.test_date',
                        'water_system_log_frames.leakage', 'water_system_log_frames.reachability', 'water_systems.name',
                        'water_system_log_frames.created_at as created_at',
                        'water_system_log_frames.updated_at as updated_at',)
                    ->latest();

                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
    
                        $viewButton = "<a type='button' class='viewWaterLog' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#waterLogModal' ><i class='fa-solid fa-eye text-info'></i></a>";
                        $updateButton = "<a type='button' class='updateWaterLog' data-id='".$row->id."' ><i class='fa-solid fa-pen-to-square text-success'></i></a>";
                        $deleteButton = "<a type='button' class='deleteWaterLog' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";
                        
                        if(Auth::guard('user')->user()->user_type_id == 1 || 
                            Auth::guard('user')->user()->user_type_id == 2 ||
                            Auth::guard('user')->user()->user_type_id == 5 ||
                            Auth::guard('user')->user()->user_type_id == 11) 
                        {
                                
                            return $viewButton." ". $updateButton." ".$deleteButton;
                        } else return $viewButton;
                    })
                   
                    ->filter(function ($instance) use ($request) {
                        if (!empty($request->get('search'))) {
                                $instance->where(function($w) use($request){
                                $search = $request->get('search');
                                $w->orWhere('water_systems.name', 'LIKE', "%$search%")
                                ->orWhere('water_system_log_frames.test_date', 'LIKE', "%$search%")
                                ->orWhere('water_system_log_frames.leakage', 'LIKE', "%$search%")
                                ->orWhere('water_system_log_frames.reachability', 'LIKE', "%$search%");
                            });
                        }
                    })
                ->rawColumns(['action'])
                ->make(true);
            }
    
            $waterSystems = WaterSystem::orderBy('name', 'ASC')->get();

            return view('system.water.logframe.index', compact('waterSystems'));
            
        } else {

            return view('errors.not-found');
        }
    }

     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {       
        $waterSystem = new WaterSystemLogFrame();
        $waterSystem->water_system_id = $request->water_system_id;
        $waterSystem->test_date = $request->test_date;
        $waterSystem->leakage = $request->leakage;
        $waterSystem->reachability = $request->reachability;
        $waterSystem->free_chlorine = $request->free_chlorine;
        $waterSystem->ph = $request->ph;
        $waterSystem->ec = $request->ec;
        $waterSystem->meter_reading = $request->meter_reading;
        $waterSystem->daily_avg_cluster_consumption = $request->daily_avg_cluster_consumption;
        $waterSystem->daily_avg_capita_consumption = $request->daily_avg_capita_consumption;
        $waterSystem->notes = $request->notes;
        $waterSystem->save();

        return redirect('/water-log')
            ->with('message', 'New Water Logframe Added Successfully!');
    }

    /**
     * Show the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $waterSystemLog = WaterSystemLogFrame::findOrFail($id);
        $waterSystem = WaterSystem::where('id', $waterSystemLog->water_system_id)->first();

        $response['waterSystem'] = $waterSystem;
        $response['waterSystemLog'] = $waterSystemLog;

        return response()->json($response);
    }
    
    /**
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteWaterLog (Request $request)
    {
        $id = $request->id;

        $waterLog = WaterSystemLogFrame::find($id);

        if($waterLog) {

            $waterLog->is_archived = 1;
            $waterLog->save();

            $response['success'] = 1;
            $response['msg'] = 'Water Log Deleted successfully'; 
        } else {

            $response['success'] = 0;
            $response['msg'] = 'Invalid ID.';
        }

        return response()->json($response); 
    }

    /**
     * View Edit page.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function editPage($id)
    {
        $waterSystemLog = WaterSystemLogFrame::findOrFail($id);

        return response()->json($waterSystemLog);
    }

    /**
     * View Edit page.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $waterSystemLog = WaterSystemLogFrame::findOrFail($id);
        
        return view('system.water.logframe.edit', compact('waterSystemLog'));
    }

     /**
     * Update an existing resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request, int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $waterSystemLog = WaterSystemLogFrame::findOrFail($id);
        $waterSystemLog->test_date = $request->test_date;
        if($request->leakage) $waterSystemLog->leakage = $request->leakage;
        $waterSystemLog->reachability = $request->reachability;
        $waterSystemLog->free_chlorine = $request->free_chlorine;
        $waterSystemLog->ph = $request->ph;
        $waterSystemLog->ec = $request->ec;
        $waterSystemLog->meter_reading = $request->meter_reading;
        $waterSystemLog->daily_avg_cluster_consumption = $request->daily_avg_cluster_consumption;
        $waterSystemLog->daily_avg_capita_consumption = $request->daily_avg_capita_consumption;
        $waterSystemLog->notes = $request->notes;
        $waterSystemLog->save();

        return redirect('/water-log')->with('message', 'Water Log Updated Successfully!');
    }

    /**
     * 
     * @return \Illuminate\Support\Collection
     */
    public function export(Request $request) 
    {
                
        return Excel::download(new WaterSystemLogframeExport($request), 'water_logframe.xlsx');
    }
}