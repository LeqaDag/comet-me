<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\User;
use App\Models\ActionCategory;
use App\Models\InternetAction;
use App\Models\InternetIssueType;
use App\Models\InternetIssue;
use App\Models\MaintenanceStatus;
use App\Models\MaintenanceType;
use App\Exports\InternetIssuesExport;
use Auth;
use DB;
use Route;
use DataTables;
use Excel;

class InternetIssueController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Auth::guard('user')->user() != null) {

            $actionFilter = $request->input('action_filter');
            $actionCategoryFilter = $request->input('category_filter');

            if ($request->ajax()) { 

                $data = DB::table('internet_issues')
                    ->join('internet_actions', 'internet_issues.internet_action_id', 'internet_actions.id')
                    ->join('action_categories', 'internet_actions.action_category_id', 'action_categories.id')
                    ->where('internet_issues.is_archived', 0) 
                    ->where('internet_actions.is_archived', 0);

                if($actionFilter != null) {

                    $data->where('internet_actions.id', $actionFilter);
                }

                if($actionCategoryFilter != null) {

                    $data->where('action_categories.id', $actionCategoryFilter);
                }

                $data->select('internet_issues.id as id', 
                    'internet_issues.english_name', 
                    'internet_issues.arabic_name',
                    'internet_actions.english_name as internet_action', 
                    'internet_issues.created_at as created_at',
                    'internet_issues.updated_at as updated_at')
                ->latest();

                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {

                        $updateButton = "<a type='button' class='updateInternetIssue' data-id='".$row->id."'><i class='fa-solid fa-pen-to-square text-success'></i></a>";
                        $deleteButton = "<a type='button' class='deleteInternetIssue' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";

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
                                $w->orWhere('internet_issues.english_name', 'LIKE', "%$search%")
                                ->orWhere('internet_issues.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('internet_actions.english_name', 'LIKE', "%$search%")
                                ->orWhere('internet_actions.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('action_categories.english_name', 'LIKE', "%$search%")
                                ->orWhere('action_categories.arabic_name', 'LIKE', "%$search%");
                            });
                        }
                    })
                ->rawColumns(['action'])
                ->make(true);
            }

            $actionCategories = ActionCategory::where("is_archived", 0)->get();
            $internetActions = InternetAction::where('is_archived', 0)->get();

            return view('users.internet.maintenance.issue.index', compact('actionCategories', 'internetActions'));
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
        $internetIssue = new InternetIssue();

        // Get last comet_id
        $last_comet_id = InternetIssue::latest('id')->value('unique_id') + 1;

        $internetIssue->english_name = $request->english_name;
        $internetIssue->arabic_name = $request->arabic_name;
        $internetIssue->internet_action_id = $request->internet_action_id;
        $internetAction = InternetAction::findOrFail($request->internet_action_id);
        $actionCategory = ActionCategory::findOrFail($internetAction->action_category_id);
        $internetIssue->unique_id = $last_comet_id;
        $internetIssue->comet_id = $actionCategory->comet_id . "I" . $internetAction->comet_id . $last_comet_id;
        $internetIssue->notes = $request->notes;
        $internetIssue->save();

        return redirect()->back()
            ->with('message', 'New Issue Added Successfully!');
    }

    /**
     * Get a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getInternetIssue(Request $request)
    {
        $id = $request->id;

        $internetIssue = InternetIssue::find($id);

        return response()->json($internetIssue); 
    }

    /**
     * View Edit page.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) 
    {
        $internetIssue = InternetIssue::findOrFail($id);
        $internetActions = InternetAction::where("is_archived", 0)->get();
        $internetCategories = ActionCategory::where("is_archived", 0)->get();

        return view('users.internet.maintenance.issue.edit', compact('internetIssue', 'internetCategories', 
            'internetActions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(int $id, Request $request)
    {     
        $internetIssue = InternetIssue::findOrFail($id);

        $last_comet_id = InternetIssue::latest('id')->value('unique_id') + 1;

        if($request->english_name) $internetIssue->english_name = $request->english_name;
        if($request->arabic_name) $internetIssue->arabic_name = $request->arabic_name;
        if($request->internet_action_id) {

            $internetIssue->internet_action_id = $request->internet_action_id;
            $internetAction = InternetAction::findOrFail($request->internet_action_id);
            $internetAction->action_category_id = $request->action_category_id;
            $internetAction->save();

            $actionCategory = ActionCategory::findOrFail($internetAction->action_category_id);
            $internetIssue->unique_id = $last_comet_id;
            $internetIssue->comet_id = $actionCategory->comet_id . "I" . $internetAction->comet_id . $last_comet_id;
        }
        if($request->notes == null) $internetIssue->notes = null;
        if($request->notes) $internetIssue->notes = $request->notes;
        $internetIssue->save(); 

        return redirect('/internet-issue')->with('message', 'Internet Issue Updated Successfully!');
    }

    /**
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteInternetMainIssue(Request $request)
    {
        $id = $request->id;

        $internetIssue = InternetIssue::find($id);

        if($internetIssue) {

            $internetIssue->is_archived = 1;
            $internetIssue->save();

            $response['success'] = 1;
            $response['msg'] = 'Internet Issue Deleted successfully'; 
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
    public function getInternetActionBasedOnCategory(Request $request)
    {
        if (!$request->id) {

            $html = '<option disabled selected>Choose One...</option>';
        } else {

            $html = '<option  disabled selected>Choose One...</option>';

            $internetActions = InternetAction::where('action_category_id', $request->id)
                ->orderBy('english_name', 'ASC')
                ->where('is_archived', 0)
                ->get();

            foreach ($internetActions as $internetAction) {

                $html .= '<option value="'.$internetAction->id.'">'.$internetAction->english_name.'</option>';
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
                
        return Excel::download(new InternetIssuesExport($request), 'internet_issues.xlsx');
    }
}
