<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\User;
use App\Models\WaterAction;
use App\Models\ActionCategory;
use App\Models\WaterIssue;
use App\Models\MaintenanceStatus;
use App\Exports\WaterIssuesExport;
use Auth;
use DB;
use Route;
use DataTables; 
use Excel; 

class WaterIssueController extends Controller
{
 
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        // $incrementalNumber = 500; 
        // $waterActions = WaterAction::all();
        // foreach($waterActions as $waterAction) {

        //     $actionCategory = ActionCategory::findOrFail($waterAction->action_category_id);
        //     $waterAction->comet_id = $incrementalNumber;
        //     $waterAction->save();

        //     $incrementalNumber++;
        // }

        // $incrementalNumber = 1; 
        // $waterIssues = WaterIssue::all();
        // foreach($waterIssues as $waterIssues) {

        //     $waterAction = WaterAction::findOrFail($waterIssues->water_action_id);
        //     $actionCategory = ActionCategory::findOrFail($waterAction->action_category_id);
        //     $waterIssues->unique_id = $incrementalNumber;
            
        //     $waterIssues->comet_id = $actionCategory->comet_id . "W" . $waterAction->comet_id . $incrementalNumber;
        //     $waterIssues->save();

        //     $incrementalNumber++;
        // }

        if (Auth::guard('user')->user() != null) {

            $actionFilter = $request->input('action_filter');
            $actionCategoryFilter = $request->input('category_filter');

            if ($request->ajax()) { 

                $data = DB::table('water_issues')
                    ->join('water_actions', 'water_issues.water_action_id', 'water_actions.id')
                    ->join('action_categories', 'water_actions.action_category_id', 'action_categories.id')
                    ->where('water_issues.is_archived', 0) 
                    ->where('water_actions.is_archived', 0);

                if($actionFilter != null) {

                    $data->where('water_actions.id', $actionFilter);
                }

                if($actionCategoryFilter != null) {

                    $data->where('action_categories.id', $actionCategoryFilter);
                }

                $data->select('water_issues.id as id', 
                    'water_issues.english_name', 
                    'water_issues.arabic_name',
                    'water_actions.english_name as water_action', 
                    'water_issues.created_at as created_at',
                    'water_issues.updated_at as updated_at')
                ->latest();

                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {

                        $updateButton = "<a type='button' class='updateWaterIssue' data-id='".$row->id."'><i class='fa-solid fa-pen-to-square text-success'></i></a>";
                        $deleteButton = "<a type='button' class='deleteWaterIssue' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";

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
                                $w->orWhere('water_issues.english_name', 'LIKE', "%$search%")
                                ->orWhere('water_issues.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('water_actions.english_name', 'LIKE', "%$search%")
                                ->orWhere('water_actions.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('action_categories.english_name', 'LIKE', "%$search%")
                                ->orWhere('action_categories.arabic_name', 'LIKE', "%$search%");
                            });
                        }
                    })
                ->rawColumns(['action'])
                ->make(true);
            }

            $actionCategories = ActionCategory::where("is_archived", 0)->get();
            $waterActions = WaterAction::where('is_archived', 0)->get();

            return view('users.water.maintenance.issue.index', compact('actionCategories', 'waterActions'));
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
        $waterIssue = new WaterIssue();

        // Get last comet_id
        $last_comet_id = WaterIssue::latest('id')->value('unique_id') + 1;

        $waterIssue->english_name = $request->english_name;
        $waterIssue->arabic_name = $request->arabic_name;
        $waterIssue->water_action_id = $request->water_action_id;
        $waterAction = WaterAction::findOrFail($request->water_action_id);
        $actionCategory = ActionCategory::findOrFail($waterAction->action_category_id);
        $waterIssue->unique_id = $last_comet_id;
        $waterIssue->comet_id = $actionCategory->comet_id . "W" . $waterAction->comet_id . $last_comet_id;
        $waterIssue->notes = $request->notes;
        $waterIssue->save();
  
        return redirect()->back()
            ->with('message', 'New Issue Added Successfully!');
    }

    /**
     * Get a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getEnergyIssue(Request $request)
    {
        $id = $request->id;

        $waterIssue = WaterIssue::find($id);

        return response()->json($waterIssue); 
    }

    /**
     * View Edit page.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) 
    {
        $waterIssue = WaterIssue::findOrFail($id);
        $waterActions = WaterAction::where("is_archived", 0)->get();
        $waterCategories = ActionCategory::where("is_archived", 0)->get();

        return view('users.water.maintenance.issue.edit', compact('waterIssue', 'waterCategories', 
            'waterActions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(int $id, Request $request)
    {     
        $waterIssue = WaterIssue::findOrFail($id);

        $last_comet_id = WaterIssue::latest('id')->value('unique_id') + 1;

        if($request->english_name) $waterIssue->english_name = $request->english_name;
        if($request->arabic_name) $waterIssue->arabic_name = $request->arabic_name;
        if($request->water_action_id) {

            $waterIssue->water_action_id = $request->water_action_id;
            $waterAction = WaterAction::findOrFail($request->water_action_id);
            $waterAction->action_category_id = $request->action_category_id;
            $waterAction->save();

            $actionCategory = ActionCategory::findOrFail($waterAction->action_category_id);
            $waterIssue->unique_id = $last_comet_id;
            $waterIssue->comet_id = $actionCategory->comet_id . "W" . $waterAction->comet_id . $last_comet_id;
        }
        if($request->notes == null) $waterIssue->notes = null;
        if($request->notes) $waterIssue->notes = $request->notes;
        $waterIssue->save(); 
  
        return redirect('/water-issue')->with('message', 'Water Issue Updated Successfully!');
    }

    /**
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteWaterMainIssue(Request $request)
    {
        $id = $request->id;

        $waterIssue = WaterIssue::find($id);

        if($waterIssue) {

            $waterIssue->is_archived = 1;
            $waterIssue->save();

            $response['success'] = 1;
            $response['msg'] = 'Water Issue Deleted successfully'; 
        } else {

            $response['success'] = 0;
            $response['msg'] = 'Invalid ID.';
        }

        return response()->json($response); 
    }

    /**
     * Get a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getWaterActionBasedOnCategory(Request $request)
    {
        if (!$request->id) {

            $html = '<option disabled selected>Choose One...</option>';
        } else {

            $html = '<option  disabled selected>Choose One...</option>';

            $waterActions = WaterAction::where('action_category_id', $request->id)
                ->orderBy('english_name', 'ASC')
                ->where('is_archived', 0)
                ->get();

            foreach ($waterActions as $waterAction) {

                $html .= '<option value="'.$waterAction->id.'">'.$waterAction->english_name.'</option>';
            }
        }

        return response()->json(['html' => $html]);
    }

    /**
     * 
     * @return \Illuminate\Support\Collection
     */
    public function export(Request $request) 
    {
                
        return Excel::download(new WaterIssuesExport($request), 'water_issues.xlsx');
    }
}