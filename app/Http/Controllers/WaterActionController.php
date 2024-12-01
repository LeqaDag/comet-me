<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\User;
use App\Models\WaterAction;
use App\Models\ActionCategory;
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

            $categoryFilter = $request->input('category_filter');

            if ($request->ajax()) {

                $data = DB::table('water_actions')
                    ->join('action_categories', 'water_actions.action_category_id', 'action_categories.id')
                    ->where('water_actions.is_archived', 0);

                if($categoryFilter != null) {

                    $data->where('action_categories.id', $categoryFilter);
                }

                $data->select('water_actions.id as id', 
                    'water_actions.english_name', 
                    'water_actions.arabic_name',
                    'action_categories.english_name as category',
                    'water_actions.created_at as created_at',
                    'water_actions.updated_at as updated_at')
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
                                $w->orWhere('action_categories.english_name', 'LIKE', "%$search%")
                                ->orWhere('action_categories.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('water_actions.english_name', 'LIKE', "%$search%")
                                ->orWhere('water_actions.arabic_name', 'LIKE', "%$search%");
                            });
                        }
                    })
                ->rawColumns(['action'])
                ->make(true);
            }

            $actionCategories = ActionCategory::where("is_archived", 0)->get();

            return view('users.water.maintenance.action.index', compact('actionCategories'));
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
        $waterAction = new WaterAction();
        // Get last comet_id
        $last_comet_id = WaterAction::latest('id')->value('comet_id') + 1;
        $waterAction->english_name = $request->english_name;
        $waterAction->arabic_name = $request->arabic_name;
        $waterAction->action_category_id = $request->action_category_id;
        $waterAction->comet_id = $last_comet_id;
        $waterAction->notes = $request->notes;
        $waterAction->save();

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
        $waterAction = WaterAction::findOrFail($id);
        $actionCategories = ActionCategory::where("is_archived", 0)->get();

        return view('users.water.maintenance.action.edit', compact('waterAction', 'actionCategories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(int $id, Request $request)
    {     
        $waterAction = WaterAction::findOrFail($id);

        if($request->english_name) $waterAction->english_name = $request->english_name;
        if($request->arabic_name) $waterAction->arabic_name = $request->arabic_name;
        if($request->action_category_id) $waterAction->action_category_id = $request->action_category_id;
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

        $waterAction = WaterAction::find($id);

        if($waterAction) {

            $waterAction->is_archived = 1;
            $waterAction->save(); 

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