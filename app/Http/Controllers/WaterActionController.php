<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\User;
use App\Models\InternetMaintenanceCall;
use App\Models\MaintenanceH2oAction;
use App\Models\MaintenanceStatus;
use App\Models\MaintenanceType;
use App\Exports\WaterActionExport;
use Auth;
use DB;
use Route;
use DataTables;
use Excel;

class WaterActionController extends Controller
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

                $data = DB::table('maintenance_h2o_actions')
                    ->select(
                        'maintenance_h2o_actions.id as id', 
                        'maintenance_h2o_actions.maintenance_action_h2o_english as english_name', 
                        'maintenance_h2o_actions.maintenance_action_h2o as arabic_name',
                        'maintenance_h2o_actions.created_at as created_at',
                        'maintenance_h2o_actions.updated_at as updated_at')
                    ->latest();

                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {

                        $updateButton = "<a type='button' class='updateWaterAction' data-id='".$row->id."'><i class='fa-solid fa-pen-to-square text-success'></i></a>";
                        $deleteButton = "<a type='button' class='deleteWaterAction' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";
                        $viewButton = "<a type='button' class='viewWaterAction' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#viewEnergyActionModal' ><i class='fa-solid fa-eye text-info'></i></a>";

                        if(Auth::guard('user')->user()->user_type_id == 1 || 
                            Auth::guard('user')->user()->user_type_id == 5 ||
                            Auth::guard('user')->user()->user_type_id == 11) 
                        {
                                
                            return $updateButton. " ". $deleteButton ;
                        } else return "";
                    })
                    ->filter(function ($instance) use ($request) {
                        if (!empty($request->get('search'))) {
                                $instance->where(function($w) use($request){
                                $search = $request->get('search');
                                $w->orWhere('maintenance_h2o_actions.maintenance_action_h2o_english', 'LIKE', "%$search%")
                                ->orWhere('maintenance_h2o_actions.maintenance_action_h2o', 'LIKE', "%$search%");
                            });
                        }
                    })
                ->rawColumns(['action'])
                ->make(true);
            }

            return view('users.water.maintenance.action.index');
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
        $energyAction = new MaintenanceH2oAction();

        $energyAction->maintenance_action_h2o_english = $request->maintenance_action_h2o_english;
        $energyAction->maintenance_action_h2o = $request->maintenance_action_h2o;
        $energyAction->notes = $request->notes;
        $energyAction->save();
  
        return redirect()->back()
            ->with('message', 'New Action Added Successfully!');
    }

    /**
     * View Edit page.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) 
    {
        $waterAction = MaintenanceH2oAction::findOrFail($id);

        return view('users.water.maintenance.action.edit', compact('waterAction'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(int $id, Request $request)
    {     
        $waterAction = MaintenanceH2oAction::findOrFail($id);

        if($request->maintenance_action_h2o_english) $waterAction->maintenance_action_h2o_english = $request->maintenance_action_h2o_english;
        if($request->maintenance_action_h2o) $waterAction->maintenance_action_h2o = $request->maintenance_action_h2o;
        if($request->notes) $waterAction->notes = $request->notes;
        $waterAction->save();
  
        return redirect('/water-action')->with('message', 'Water Action Updated Successfully!');
    }

    /**
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteWaterMainAction(Request $request)
    {
        $id = $request->id;

        $waterAction = MaintenanceH2oAction::find($id);

        if($waterAction) {

            $waterAction->delete(); 

            $response['success'] = 1;
            $response['msg'] = 'Water Action Deleted successfully'; 
        } else {

            $response['success'] = 0;
            $response['msg'] = 'Invalid ID.';
        }

        return response()->json($response); 
    }

    /**
     * 
     * @return \Illuminate\Support\Collection
     */
    public function export(Request $request) 
    {
                
        return Excel::download(new WaterActionExport($request), 'water_actions.xlsx');
    }
}