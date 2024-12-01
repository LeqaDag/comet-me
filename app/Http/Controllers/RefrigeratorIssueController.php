<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\User;
use App\Models\RefrigeratorAction;
use App\Models\ActionCategory;
use App\Models\RefrigeratorIssue;
use App\Models\MaintenanceStatus;
use App\Exports\RefrigeratorIssuesExport;
use Auth;
use DB;
use Route;
use DataTables;
use Excel; 

class RefrigeratorIssueController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $incrementalNumber = 1; 
        // $refrigeratorIssues = RefrigeratorIssue::all();
        // foreach($refrigeratorIssues as $refrigeratorIssue) {

        //     $refrigeratorAction = RefrigeratorAction::findOrFail($refrigeratorIssue->refrigerator_action_id);
        //     $actionCategory = ActionCategory::findOrFail($refrigeratorAction->action_category_id);
        //     $refrigeratorIssue->unique_id = $incrementalNumber;
            
        //     $refrigeratorIssue->comet_id = $actionCategory->comet_id . "R" . $refrigeratorAction->comet_id . $incrementalNumber;
        //     $refrigeratorIssue->save();

        //     $incrementalNumber++;
        // }

        if (Auth::guard('user')->user() != null) {

            $actionFilter = $request->input('action_filter');
            $issueTypeFilter = $request->input('issue_type_filter');

            if ($request->ajax()) {

                $data = DB::table('refrigerator_issues')
                    ->join('refrigerator_actions', 'refrigerator_issues.refrigerator_action_id', 'refrigerator_actions.id')
                    ->where('refrigerator_issues.is_archived', 0) 
                    ->where('refrigerator_actions.is_archived', 0);

                if($actionFilter != null) {

                    $data->where('refrigerator_actions.id', $actionFilter);
                }

                if($issueTypeFilter != null) {

                    $data->where('refrigerator_maintenance_issue_types.id', $issueTypeFilter);
                }

                $data->select('refrigerator_issues.id as id', 
                    'refrigerator_issues.english_name', 
                    'refrigerator_issues.arabic_name',
                    'refrigerator_actions.english_name as refrigerator_action', 
                    'refrigerator_issues.created_at as created_at',
                    'refrigerator_issues.updated_at as updated_at')
                ->latest();

                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {

                        $updateButton = "<a type='button' class='updateRefrigeratorIssue' data-id='".$row->id."'><i class='fa-solid fa-pen-to-square text-success'></i></a>";
                        $deleteButton = "<a type='button' class='deleteRefrigeratorIssue' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";

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
                                $w->orWhere('refrigerator_issues.english_name', 'LIKE', "%$search%")
                                ->orWhere('refrigerator_issues.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('refrigerator_actions.english_name', 'LIKE', "%$search%")
                                ->orWhere('refrigerator_actions.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('refrigerator_maintenance_issue_types.name', 'LIKE', "%$search%");
                            });
                        }
                    })
                ->rawColumns(['action'])
                ->make(true);
            }

            $actionCategories = ActionCategory::where("is_archived", 0)->get();
            $refrigeratorActions = RefrigeratorAction::where('is_archived', 0)->get();

            return view('users.refrigerator.maintenance.issue.index', compact('actionCategories', 'refrigeratorActions'));
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
        $refrigeratorIssue = new RefrigeratorIssue();

        // Get last comet_id
        $last_comet_id = RefrigeratorIssue::latest('id')->value('unique_id') + 1;

        $refrigeratorIssue->english_name = $request->english_name;
        $refrigeratorIssue->arabic_name = $request->arabic_name;
        $refrigeratorIssue->refrigerator_action_id = $request->refrigerator_action_id;
        $refrigeratorAction = RefrigeratorAction::findOrFail($request->refrigerator_action_id);
        $actionCategory = ActionCategory::findOrFail($refrigeratorAction->action_category_id);
        $refrigeratorIssue->unique_id = $last_comet_id;
        $refrigeratorIssue->comet_id = $actionCategory->comet_id . "R" . $refrigeratorAction->comet_id . $last_comet_id;
        $refrigeratorIssue->notes = $request->notes;
        $refrigeratorIssue->save();
  
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

        $energyIssue = EnergyIssue::find($id);

        return response()->json($energyIssue); 
    }

    /**
     * View Edit page.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) 
    {
        $refrigeratorIssue = RefrigeratorIssue::findOrFail($id);
        $refrigeratorActions = RefrigeratorAction::where("is_archived", 0)->get();
        $refrigeratorCategories = ActionCategory::where("is_archived", 0)->get();

        //die($refrigeratorIssue);

        return view('users.refrigerator.maintenance.issue.edit', compact('refrigeratorIssue', 'refrigeratorCategories', 
            'refrigeratorActions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(int $id, Request $request)
    {     
        $refrigeratorIssue = RefrigeratorIssue::findOrFail($id);

        $last_comet_id = RefrigeratorIssue::latest('id')->value('unique_id') + 1;

        if($request->english_name) $refrigeratorIssue->english_name = $request->english_name;
        if($request->arabic_name) $refrigeratorIssue->arabic_name = $request->arabic_name;
        if($request->refrigerator_action_id) {

            $refrigeratorIssue->refrigerator_action_id = $request->refrigerator_action_id;
            $refrigeratorAction = RefrigeratorAction::findOrFail($request->refrigerator_action_id);
            $refrigeratorAction->action_category_id = $request->action_category_id;
            $refrigeratorAction->save();

            $actionCategory = ActionCategory::findOrFail($refrigeratorAction->action_category_id);
            $refrigeratorIssue->unique_id = $last_comet_id;
            $refrigeratorIssue->comet_id = $actionCategory->comet_id . "R" . $refrigeratorAction->comet_id . $last_comet_id;
        }
        if($request->refrigerator_maintenance_issue_type_id) $refrigeratorIssue->refrigerator_maintenance_issue_type_id = $request->refrigerator_maintenance_issue_type_id;
        if($request->notes == null) $refrigeratorIssue->notes = null;
        if($request->notes) $refrigeratorIssue->notes = $request->notes;
        $refrigeratorIssue->save(); 
  
        return redirect('/refrigerator-issue')->with('message', 'Refrigerator Issue Updated Successfully!');
    }

    /**
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteRefrigeratorIssue(Request $request)
    {
        $id = $request->id;

        $refrigeratorIssue = RefrigeratorIssue::find($id);

        if($refrigeratorIssue) {

            $refrigeratorIssue->is_archived = 1;
            $refrigeratorIssue->save();

            $response['success'] = 1;
            $response['msg'] = 'Refrigerator Issue Deleted successfully'; 
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
    public function getRefrigeratorActionBasedOnCategory(Request $request)
    {
        if (!$request->id) {

            $html = '<option disabled selected>Choose One...</option>';
        } else {

            $html = '<option  disabled selected>Choose One...</option>';

            $refrigeratorActions = RefrigeratorAction::where('action_category_id', $request->id)
                ->orderBy('english_name', 'ASC')
                ->where('is_archived', 0)
                ->get();

            foreach ($refrigeratorActions as $refrigeratorAction) {

                $html .= '<option value="'.$refrigeratorAction->id.'">'.$refrigeratorAction->english_name.'</option>';
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
                
        return Excel::download(new RefrigeratorIssuesExport($request), 'refrigerator_issues.xlsx');
    }
}