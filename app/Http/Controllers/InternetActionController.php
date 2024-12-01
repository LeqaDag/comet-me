<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\User;
use App\Models\InternetMaintenanceCall;
use App\Models\ActionCategory;
use App\Models\InternetAction;
use App\Models\InternetIssue;
use App\Models\InternetIssueType;
use App\Models\MaintenanceStatus;
use App\Models\MaintenanceType;
use App\Exports\InternetActionExport;
use Auth;
use DB;
use Route;
use DataTables;
use Excel;

class InternetActionController extends Controller
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

                $data = DB::table('internet_actions')
                    ->join('action_categories', 'internet_actions.action_category_id', 'action_categories.id')
                    ->where('internet_actions.is_archived', 0);

                if($categoryFilter != null) {

                    $data->where('action_categories.id', $categoryFilter);
                }

                $data->select('internet_actions.id as id', 
                    'internet_actions.english_name', 
                    'internet_actions.arabic_name',
                    'action_categories.english_name as category',
                    'internet_actions.created_at as created_at',
                    'internet_actions.updated_at as updated_at')
                ->latest();

                return Datatables::of($data) 
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {

                        $updateButton = "<a type='button' class='updateInternetAction' data-id='".$row->id."'><i class='fa-solid fa-pen-to-square text-success'></i></a>";
                        $deleteButton = "<a type='button' class='deleteInternetAction' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";
                        $viewButton = "<a type='button' class='viewInternetAction' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#viewInternetActionModal' ><i class='fa-solid fa-eye text-info'></i></a>";

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
                                $w->orWhere('internet_actions.english_name', 'LIKE', "%$search%")
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
            $internetIssueTypes = InternetIssueType::all();

            return view('users.internet.maintenance.action.index', compact('actionCategories',
                'internetIssueTypes'));
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
        $internetAction = new InternetAction();
        // Get last comet_id
        $last_comet_id = InternetAction::latest('id')->value('comet_id') + 1;
        $internetAction->english_name = $request->english_name;
        $internetAction->arabic_name = $request->arabic_name;
        $internetAction->action_category_id = $request->action_category_id;
        $internetAction->comet_id = $last_comet_id;
        $internetAction->notes = $request->notes;
        $internetAction->save();
  
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
        $internetAction = InternetAction::findOrFail($id);
        $actionCategories = ActionCategory::where("is_archived", 0)->get();

        return view('users.internet.maintenance.action.edit', compact('internetAction', 'actionCategories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(int $id, Request $request)
    {     
        $internetAction = InternetAction::findOrFail($id);

        if($request->english_name) $internetAction->english_name = $request->english_name;
        if($request->arabic_name) $internetAction->arabic_name = $request->arabic_name;
        if($request->action_category_id) $internetAction->action_category_id = $request->action_category_id;
        if($request->notes) $internetAction->notes = $request->notes;
        $internetAction->save();
  
        return redirect('/internet-action')->with('message', 'Internet Action Updated Successfully!');
    }

    /**
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteInternetMainAction(Request $request)
    {
        $id = $request->id;

        $internetAction = InternetAction::find($id);

        if($internetAction) {

            $internetAction->is_archived = 1;
            $internetAction->save(); 

            $response['success'] = 1;
            $response['msg'] = 'Internet Action Deleted successfully'; 
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
                
        return Excel::download(new InternetActionExport($request), 'internet_actions.xlsx');
    }


    /**
     * Get a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getInternetAction(Request $request)
    {
        $id = $request->id;

        $internetAction = InternetAction::find($id);

        return response()->json($internetAction); 
    }
}
