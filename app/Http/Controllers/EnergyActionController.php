<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\User;

use App\Models\ActionCategory;
use App\Models\EnergyAction;
use App\Models\EnergyIssue;

use App\Models\InternetMaintenanceCall;
use App\Models\EnergyMaintenanceAction;
use App\Models\EnergyMaintenanceIssueType;
use App\Models\EnergyMaintenanceIssue;
use App\Models\MaintenanceStatus;
use App\Models\MaintenanceType;
use App\Exports\EnergyActionExport;
use Auth;
use DB;
use Route;
use DataTables;
use Excel;

class EnergyActionController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $incrementalNumber = 100; 
        // $energyActions = EnergyAction::all();
        // foreach($energyActions as $energyAction) {

        //     $actionCategory = ActionCategory::findOrFail($energyAction->action_category_id);
        //     $energyAction->comet_id = $incrementalNumber;
        //     $energyAction->full_comet_id = $actionCategory->comet_id . $incrementalNumber;
        //     $energyAction->save();

        //     $incrementalNumber++;
        // }

        if (Auth::guard('user')->user() != null) {

            $categoryFilter = $request->input('category_filter');

            if ($request->ajax()) {
                $data = DB::table('energy_actions')
                    ->join('action_categories', 'energy_actions.action_category_id', 
                        'action_categories.id')
                    ->where('energy_actions.is_archived', 0);

                if($categoryFilter != null) {

                    $data->where('action_categories.id', $categoryFilter);
                }

                $data->select('energy_actions.id as id', 
                    'energy_actions.english_name', 
                    'energy_actions.arabic_name',
                    'action_categories.english_name as category',
                    'energy_actions.created_at as created_at',
                    'energy_actions.updated_at as updated_at')
                ->latest();

                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {

                        $updateButton = "<a type='button' class='updateEnergyAction' data-id='".$row->id."'><i class='fa-solid fa-pen-to-square text-success'></i></a>";
                        $deleteButton = "<a type='button' class='deleteEnergyAction' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";
                        $viewButton = "<a type='button' class='viewEnergyAction' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#viewEnergyActionModal' ><i class='fa-solid fa-eye text-info'></i></a>";

                        if(Auth::guard('user')->user()->user_type_id == 1 || 
                            Auth::guard('user')->user()->user_type_id == 2) 
                        {
                                
                            return $updateButton. " ". $deleteButton ;
                        } else return "";
                    })
                    ->filter(function ($instance) use ($request) {
                        if (!empty($request->get('search'))) {
                                $instance->where(function($w) use($request){
                                $search = $request->get('search');
                                $w->orWhere('energy_actions.english_name', 'LIKE', "%$search%")
                                ->orWhere('energy_actions.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('action_categories.english_name', 'LIKE', "%$search%")
                                ->orWhere('action_categories.arabic_name', 'LIKE', "%$search%");
                            });
                        }
                    })
                ->rawColumns(['action'])
                ->make(true);
            }

            $actionCategories = ActionCategory::where("is_archived", 0)->get();

            return view('users.energy.maintenance.action.index', compact('actionCategories'));
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
        $energyAction = new EnergyAction();
        // Get last comet_id
        $last_comet_id = EnergyAction::latest('id')->value('comet_id') + 1;
        $energyAction->english_name = $request->english_name;
        $energyAction->arabic_name = $request->arabic_name;
        $energyAction->action_category_id = $request->action_category_id;
        $energyAction->comet_id = $last_comet_id;
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
        $energyAction = EnergyAction::findOrFail($id);
        $energyCategories = ActionCategory::where("is_archived", 0)->get();

        return view('users.energy.maintenance.action.edit', compact('energyCategories', 'energyAction'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(int $id, Request $request)
    {     
        $energyAction = EnergyAction::findOrFail($id);

        if($request->english_name) $energyAction->english_name = $request->english_name;
        if($request->arabic_name) $energyAction->arabic_name = $request->arabic_name;
        if($request->action_category_id) $energyAction->action_category_id = $request->action_category_id;
        if($request->notes) $energyAction->notes = $request->notes;
        $energyAction->save();
  
        return redirect('/energy-action')->with('message', 'Energy Action Updated Successfully!');
    }

    /**
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteEnergyMainAction(Request $request)
    {
        $id = $request->id;

        $energyAction = EnergyAction::find($id);

        if($energyAction) {

            $energyAction->is_archived = 1;
            $energyAction->save(); 

            $response['success'] = 1;
            $response['msg'] = 'Energy Action Deleted successfully'; 
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
                
        return Excel::download(new EnergyActionExport($request), 'energy_actions.xlsx');
    }


    /**
     * Get a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getEnergyAction(Request $request)
    {
        $id = $request->id;

        $energyAction = EnergyAction::find($id);

        return response()->json($energyAction); 
    }
}
