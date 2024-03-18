<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\User;
use App\Models\InternetMaintenanceCall;
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

            if ($request->ajax()) {
                $data = DB::table('internet_actions')
                    ->join('internet_issues', 'internet_actions.internet_issue_id', 
                        'internet_issues.id')
                    ->join('internet_issue_types', 'internet_issues.internet_issue_type_id', 
                        'internet_issue_types.id')
                    ->select('internet_actions.id as id', 
                        'internet_actions.english_name', 
                        'internet_actions.arabic_name',
                        'internet_issues.english_name as issue',
                        'internet_issue_types.type',
                        'internet_actions.created_at as created_at',
                        'internet_actions.updated_at as updated_at')
                    ->latest();

                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {

                        $updateButton = "<a type='button' class='updateInternetAction' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#updateInternetActionModal' ><i class='fa-solid fa-pen-to-square text-success'></i></a>";
                        $deleteButton = "<a type='button' class='deleteInternetAction' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";
                        $viewButton = "<a type='button' class='viewInternetAction' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#viewInternetActionModal' ><i class='fa-solid fa-eye text-info'></i></a>";

                        if(Auth::guard('user')->user()->user_type_id == 1 || 
                            Auth::guard('user')->user()->user_type_id == 10 ||
                            Auth::guard('user')->user()->user_type_id == 6) 
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
                                ->orWhere('internet_issues.english_name', 'LIKE', "%$search%")
                                ->orWhere('internet_issues.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('internet_issue_types.type', 'LIKE', "%$search%");
                            });
                        }
                    })
                ->rawColumns(['action'])
                ->make(true);
            }

            $internetIssues = InternetIssue::all();
            $internetIssueTypes = InternetIssueType::all();

            return view('users.internet.maintenance.action.index', compact('internetIssues',
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

        $internetAction->english_name = $request->english_name;
        $internetAction->arabic_name = $request->arabic_name;
        $internetAction->internet_issue_id = $request->internet_issue_id;
        $internetAction->notes = $request->notes;
        $internetAction->save();
  
        return redirect()->back()
            ->with('message', 'New Action Added Successfully!');
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
        if($request->notes) $internetAction->notes = $request->notes;
        $internetAction->save();
  
        return redirect()->back()
            ->with('message', 'Action Updated Successfully!');
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

            $internetAction->delete(); 

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
