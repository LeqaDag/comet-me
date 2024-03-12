<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\User;
use App\Models\InternetMaintenanceCall;
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

            if ($request->ajax()) {
                $data = DB::table('internet_issues')
                    ->join('internet_issue_types', 'internet_issues.internet_issue_type_id', 
                        'internet_issue_types.id')
                    ->select('internet_issues.id as id', 
                        'internet_issues.english_name', 
                        'internet_issues.arabic_name',
                        'internet_issue_types.type',
                        'internet_issues.created_at as created_at',
                        'internet_issues.updated_at as updated_at')
                    ->latest();

                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {

                        $updateButton = "<a type='button' class='updateInternetIssue' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#updateInternetIssueModal' ><i class='fa-solid fa-pen-to-square text-success'></i></a>";
                        $deleteButton = "<a type='button' class='deleteInternetIssue' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";

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
                                $w->orWhere('internet_issues.english_name', 'LIKE', "%$search%")
                                ->orWhere('internet_issues.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('internet_issue_types.type', 'LIKE', "%$search%");
                            });
                        }
                    })
                ->rawColumns(['action'])
                ->make(true);
            }

            $internetIssueTypes = InternetIssueType::all();

            return view('users.internet.maintenance.issue.index', compact('internetIssueTypes'));
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

        $internetIssue->english_name = $request->english_name;
        $internetIssue->arabic_name = $request->arabic_name;
        $internetIssue->internet_issue_type_id = $request->internet_issue_type_id;
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(int $id, Request $request)
    {     
        $internetIssue = InternetIssue::findOrFail($id);

        if($request->english_name) $internetIssue->english_name = $request->english_name;
        if($request->arabic_name) $internetIssue->arabic_name = $request->arabic_name;
        if($request->notes) $internetIssue->notes = $request->notes;
        $internetIssue->save();
  
        return redirect()->back()
            ->with('message', 'Issue Updated Successfully!');
    }

    /**
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteInternetIssue(Request $request)
    {
        $id = $request->id;

        $internetIssue = InternetIssue::find($id);

        if($internetIssue) {

            $internetIssue->delete();

            $response['success'] = 1;
            $response['msg'] = 'Internet Issue Deleted successfully'; 
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
                
        return Excel::download(new InternetIssuesExport($request), 'internet_issues.xlsx');
    }
}
