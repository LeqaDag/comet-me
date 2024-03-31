<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\User;
use App\Models\Community;
use App\Models\InternetMaintenanceCall;
use App\Models\Household;
use App\Models\MaintenanceActionType;
use App\Models\InternetIssueType;
use App\Models\InternetIssue;
use App\Models\InternetAction;
use App\Models\MaintenanceStatus;
use App\Models\MaintenanceType;
use App\Models\PublicStructure;
use App\Models\PublicStructureCategory;
use App\Models\InternetUser;
use App\Models\InternetMaintenanceCallAction;
use App\Models\InternetMaintenanceCallUser;
use App\Exports\InternetMaintenanceExport;
use Auth;
use DB;
use Route;
use DataTables;
use Excel;

class InternetMaintenanceCallController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {	
        if (Auth::guard('user')->user() != null) {

            $communityFilter = $request->input('community_filter');
            $publicFilter = $request->input('public_filter');
            $dateFilter = $request->input('date_filter');

            if ($request->ajax()) { 

                $data = DB::table('internet_maintenance_calls')
                    ->join('internet_users', 'internet_maintenance_calls.internet_user_id', 
                        'internet_users.id')
                    ->leftJoin('households', 'internet_users.household_id', 'households.id')
                    ->leftJoin('public_structures', 'internet_users.public_structure_id', 
                        'public_structures.id')
                    ->join('communities', 'internet_maintenance_calls.community_id', 'communities.id')
                    ->join('maintenance_types', 'internet_maintenance_calls.maintenance_type_id', 
                        'maintenance_types.id')
                    ->join('maintenance_statuses', 'internet_maintenance_calls.maintenance_status_id', 
                        'maintenance_statuses.id')
                    ->join('users', 'internet_maintenance_calls.user_id', 'users.id')
                    ->where('internet_maintenance_calls.is_archived', 0);

                if($communityFilter != null) {

                    $data->where('communities.id', $communityFilter);
                }
                if ($publicFilter != null) {

                    $data->where("public_structures.public_structure_category_id1", $publicFilter)
                        ->orWhere("public_structures.public_structure_category_id2", $publicFilter)
                        ->orWhere("public_structures.public_structure_category_id3", $publicFilter);
                }
                if ($dateFilter != null) {

                    $data->where('internet_maintenance_calls.date_completed', '>=', $dateFilter);
                }

                $data->select(
                    'internet_maintenance_calls.id as id', 
                    'households.english_name as household_name', 
                    'date_of_call', 'date_completed', 'internet_maintenance_calls.notes',
                    'maintenance_types.type', 'maintenance_statuses.name', 
                    'communities.english_name as community_name',
                    'internet_maintenance_calls.created_at as created_at',
                    'internet_maintenance_calls.updated_at as updated_at',
                    'users.name as user_name', 'public_structures.english_name as public_name'
                )->latest();

                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
    
                        $updateButton = "<a type='button' class='updateInternetMaintenance' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#updateInternetMaintenanceModal' ><i class='fa-solid fa-pen-to-square text-success'></i></a>";
                        $deleteButton = "<a type='button' class='deleteInternetMaintenance' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";
                        $viewButton = "<a type='button' class='viewInternetMaintenance' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#viewInternetMaintenanceModal' ><i class='fa-solid fa-eye text-info'></i></a>";
    
                        if(Auth::guard('user')->user()->user_type_id == 1 || 
                            Auth::guard('user')->user()->user_type_id == 10 ||
                            Auth::guard('user')->user()->user_type_id == 6) 
                        {
                                
                            return $viewButton. " ".$updateButton. " ". $deleteButton ;
                        } else return $viewButton;
                    })
                    ->addColumn('holder', function($row) {

                        if($row->household_name != null) $holder = $row->household_name;
                        else if($row->public_name != null) $holder = $row->public_name;

                        return $holder;
                    })
                    ->filter(function ($instance) use ($request) {
                        if (!empty($request->get('search'))) {
                                $instance->where(function($w) use($request){
                                $search = $request->get('search');
                                $w->orWhere('communities.english_name', 'LIKE', "%$search%")
                                ->orWhere('communities.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('households.english_name', 'LIKE', "%$search%")
                                ->orWhere('public_structures.english_name', 'LIKE', "%$search%")
                                ->orWhere('public_structures.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('households.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('maintenance_statuses.name', 'LIKE', "%$search%")
                                ->orWhere('maintenance_types.type', 'LIKE', "%$search%")
                                ->orWhere('users.name', 'LIKE', "%$search%");
                            });
                        }
                    })
                ->rawColumns(['action', 'holder'])
                ->make(true);
            }
    
            $communities = Community::where('is_archived', 0)
                ->orderBy('english_name', 'ASC')
                ->get();

            $households = DB::table('internet_users')
                ->where('internet_users.is_archived', 0)
                ->join('households', 'internet_users.household_id', 'households.id')
                ->orderBy('households.english_name', 'ASC')
                ->select('households.id as id', 'households.english_name')
                ->get();
    
            $maintenanceTypes = MaintenanceType::where('is_archived', 0)->get();
            $maintenanceStatuses = MaintenanceStatus::where('is_archived', 0)->get();
            $publics = DB::table('internet_users')
                ->where('internet_users.is_archived', 0)
                ->join('public_structures', 'internet_users.public_structure_id', 
                    'public_structures.id')
                ->select('public_structures.id as id', 'public_structures.english_name')
                ->get();

            $users = User::where('is_archived', 0)->get();
            $publicCategories = PublicStructureCategory::where('is_archived', 0)->get();
    
            $internetIssues = InternetIssue::all();

            return view('users.internet.maintenance.index', compact('maintenanceTypes', 
                'maintenanceStatuses', 'users', 'communities', 'internetIssues',
                'households', 'publics', 'publicCategories'));
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
        $this->validate($request, [
            'community_id' => 'required',
            'maintenance_status_id' => 'required',
            'maintenance_type_id' => 'required',
            'user_id' => 'required'
        ]);

        $maintenance = new InternetMaintenanceCall();

        if($request->public_user == "user") {

            $internetUser = InternetUser::where('household_id', $request->internet_user_id)->first();
            $maintenance->internet_user_id = $internetUser->id;
        }

        if($request->public_user == "public") {

            $internetUser = InternetUser::where('public_structure_id', $request->internet_user_id)->first();
            $maintenance->internet_user_id = $internetUser->id;
        }

        $maintenance->community_id = $request->community_id;
        $maintenance->date_of_call = $request->date_of_call;
        $maintenance->visit_date = $request->visit_date;
        if($request->date_completed) {

            $maintenance->date_completed = $request->date_completed;
            $maintenance->maintenance_status_id = 3;
        } else if($request->date_completed == null)  {

            $maintenance->date_completed = null;
            $maintenance->maintenance_status_id = $request->maintenance_status_id;
        } 
        $maintenance->user_id = $request->user_id;
        $maintenance->maintenance_type_id = $request->maintenance_type_id;
        $maintenance->notes = $request->notes;
        $maintenance->save();

        $maintenanceId = $maintenance->id;

        if($request->action_ids) {
            for($i=0; $i < count($request->action_ids); $i++) {

                $h2oMaintenanceCallAction = new InternetMaintenanceCallAction();
                $h2oMaintenanceCallAction->internet_action_id = $request->action_ids[$i];
                $h2oMaintenanceCallAction->internet_maintenance_call_id = $maintenanceId;
                $h2oMaintenanceCallAction->save();
            }
        }

        if($request->performed_by) {
            for($i=0; $i < count($request->performed_by); $i++) {

                $h2oMaintenanceCallUser = new InternetMaintenanceCallUser();
                $h2oMaintenanceCallUser->user_id = $request->performed_by[$i];
                $h2oMaintenanceCallUser->internet_maintenance_call_id = $maintenanceId;
                $h2oMaintenanceCallUser->save();
            }
        }

        return redirect()->back()
        ->with('message', 'New Maintenance Added Successfully!');
    }

    /**
     * View Edit page.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) 
    {
        $internetMaintenance = InternetMaintenanceCall::findOrFail($id);
        $actions = "";

        $internetUser = InternetUser::findOrFail($internetMaintenance->internet_user_id);
        $maintenanceTypes = MaintenanceType::where('is_archived', 0)->get();
        $maintenanceStatuses = MaintenanceStatus::where('is_archived', 0)->get();

        $allInternetActions = InternetAction::all();

        $internetActions = DB::table('internet_maintenance_call_actions')
            ->join('internet_maintenance_calls', 'internet_maintenance_call_actions.internet_maintenance_call_id', 
                'internet_maintenance_calls.id')
            ->join('internet_actions', 'internet_maintenance_call_actions.internet_action_id', 
                'internet_actions.id')
            ->where('internet_maintenance_call_actions.internet_maintenance_call_id', $internetMaintenance->id)
            ->select('internet_maintenance_call_actions.id', 
                'internet_actions.english_name')
            ->get();

        $performedUsers = DB::table('internet_maintenance_call_users')
            ->join('internet_maintenance_calls', 'internet_maintenance_call_users.internet_maintenance_call_id', 
                'internet_maintenance_calls.id')
            ->join('users', 'internet_maintenance_call_users.user_id', 'users.id')
            ->where('internet_maintenance_call_users.internet_maintenance_call_id', $internetMaintenance->id)
            ->select('internet_maintenance_call_users.id', 'users.name')
            ->get();

        $users = User::where('is_archived', 0)->get();
        $internetIssues = InternetIssue::all();

        return view('users.internet.maintenance.edit', compact('internetMaintenance',
            'maintenanceTypes',  'maintenanceStatuses', 'allInternetActions', 'internetUser',
            'internetActions', 'performedUsers', 'users', 'internetIssues'));
    }

    /**
     * Update an existing resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request, int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $maintenance = InternetMaintenanceCall::findOrFail($id);

        $maintenance->date_of_call = $request->date_of_call;
        $maintenance->visit_date = $request->visit_date;
        if($request->date_completed) {

            $maintenance->date_completed = $request->date_completed;
            $maintenance->maintenance_status_id = 3;
        } else if($request->date_completed == null)  {

            $maintenance->date_completed = null;
        } else {

            $maintenance->maintenance_status_id = $request->maintenance_status_id;
        }

        $maintenance->user_id = $request->user_id;
        $maintenance->maintenance_type_id = $request->maintenance_type_id;
        $maintenance->notes = $request->notes;
        $maintenance->save();

        if($request->actions) {
            for($i=0; $i < count($request->actions); $i++) {

                $maintenanceCallAction = new InternetMaintenanceCallAction();
                $maintenanceCallAction->internet_action_id = $request->actions[$i];
                $maintenanceCallAction->internet_maintenance_call_id = $id;
                $maintenanceCallAction->save();
            }
        }

        if($request->new_actions) {
            for($i=0; $i < count($request->new_actions); $i++) {

                $maintenanceCallAction = new InternetMaintenanceCallAction();
                $maintenanceCallAction->internet_action_id = $request->new_actions[$i];
                $maintenanceCallAction->internet_maintenance_call_id = $id;
                $maintenanceCallAction->save();
            }
        }

        if($request->users) {
            if($request->users) {
                for($i=0; $i < count($request->users); $i++) {
    
                    $internetMaintenanceCallUser = new InternetMaintenanceCallUser();
                    $internetMaintenanceCallUser->user_id = $request->users[$i];
                    $internetMaintenanceCallUser->internet_maintenance_call_id = $id;
                    $internetMaintenanceCallUser->save();
                }
            }
        }

        if($request->new_users) {
            if($request->new_users) {
                for($i=0; $i < count($request->new_users); $i++) {
    
                    $internetMaintenanceCallUser = new InternetMaintenanceCallUser();
                    $internetMaintenanceCallUser->user_id = $request->new_users[$i];
                    $internetMaintenanceCallUser->internet_maintenance_call_id = $id;
                    $internetMaintenanceCallUser->save();
                }
            }
        }
        
        return redirect('/internet-maintenance')->with('message', 
            'Internet Maintenance Updated Successfully!');
    }

    /**
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteInternetMaintenance(Request $request)
    {
        $id = $request->id;

        $maintenance = InternetMaintenanceCall::find($id);

        if($maintenance) {

            $maintenance->is_archived = 1;
            $maintenance->save();

            $response['success'] = 1;
            $response['msg'] = 'Internet Maintenance Deleted successfully'; 
        } else {

            $response['success'] = 0;
            $response['msg'] = 'Invalid ID.';
        }

        return response()->json($response); 
    }

    /**
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteInternetAction(Request $request)
    {
        $id = $request->id;

        $internetMaintenance = InternetMaintenanceCallAction::find($id);

        if($internetMaintenance) {

            $internetMaintenance->delete();
            
            $response['success'] = 1;
            $response['msg'] = 'Internet Maintenance Action Deleted successfully'; 
        } else {

            $response['success'] = 0;
            $response['msg'] = 'Invalid ID.';
        }

        return response()->json($response); 
    }

    /**
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deletePerformedInternetUser(Request $request)
    {
        $id = $request->id;

        $internetPerformedBy = InternetMaintenanceCallUser::find($id);

        if($internetPerformedBy) {

            $internetPerformedBy->delete();
            
            $response['success'] = 1;
            $response['msg'] = 'Internet Maintenance User Deleted successfully'; 
        } else {

            $response['success'] = 0;
            $response['msg'] = 'Invalid ID.';
        }

        return response()->json($response); 
    }

    /**
     * Show the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $internetMaintenance = InternetMaintenanceCall::findOrFail($id);
        
        if($internetMaintenance->internet_user_id != NULL) {
         
            $internetUser = InternetUser::where('id', $internetMaintenance->internet_user_id)->first();
            $response['internetUser'] = $internetUser;

            if($internetUser->household_id) {

                $household = Household::where('id', $internetUser->household_id)->first();
                $response['household'] = $household;
            }
            if($internetUser->public_structure_id) {

                $public = PublicStructure::where('id', $internetUser->public_structure_id)->first();
                $response['public'] = $public;
            }
        }
       
        $community = Community::where('id', $internetMaintenance->community_id)->first();
        $status = MaintenanceStatus::where('id', $internetMaintenance->maintenance_status_id)
            ->first();
        $type = MaintenanceType::where('id', $internetMaintenance->maintenance_type_id)
            ->first();
        $user = User::where('id', $internetMaintenance->user_id)->first();

        $internetActions = DB::table('internet_maintenance_call_actions')
            ->join('internet_maintenance_calls', 'internet_maintenance_calls.id', 
                'internet_maintenance_call_actions.internet_maintenance_call_id' )
            ->join('internet_actions', 'internet_maintenance_call_actions.internet_action_id', 
                'internet_actions.id')
            ->where('internet_maintenance_call_actions.internet_maintenance_call_id', $internetMaintenance->id)
            ->select('internet_maintenance_call_actions.id', 'internet_actions.english_name')
            ->get();

        $performedUsers = DB::table('internet_maintenance_call_users')
            ->join('internet_maintenance_calls', 'internet_maintenance_call_users.internet_maintenance_call_id', 
                'internet_maintenance_calls.id')
            ->join('users', 'internet_maintenance_call_users.user_id', 'users.id')
            ->where('internet_maintenance_call_users.internet_maintenance_call_id', $internetMaintenance->id)
            ->select('internet_maintenance_call_users.id', 'users.name')
            ->get();

        $response['community'] = $community;
        $response['internetMaintenance'] = $internetMaintenance;
        $response['status'] = $status;
        $response['type'] = $type;
        $response['user'] = $user;
        $response['internetActions'] = $internetActions;
        $response['performedUsers'] = $performedUsers;
        $response['internetUser'] = $internetUser;

        return response()->json($response);
    }

    /**
     * 
     * @return \Illuminate\Support\Collection
     */
    public function export(Request $request)
    {
                
        return Excel::download(new InternetMaintenanceExport($request), 'internet_maintenance.xlsx');
    }

    /**
     * Get resources from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getHouseholdByCommunity($community_id)
    {
        if (!$community_id) {

            $html = '<option value="">Choose One...</option>';
        } else {

            $html = '<option value="">Choose One...</option>';
            $households = DB::table('internet_users')
                ->join('households', 'internet_users.household_id', '=', 'households.id')
                ->where("internet_users.community_id", $community_id)
                ->orderBy('households.english_name', 'ASC')
                ->select('households.id', 'households.english_name')
                ->get();

            foreach ($households as $household) {
                $html .= '<option value="'.$household->id.'">'.$household->english_name.'</option>';
            }
        }

        return response()->json(['html' => $html]);
    }

    /**
     * Get resources from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getPublicByCommunity($community_id)
    {
        if (!$community_id) {

            $html = '<option value="">Choose One...</option>';
        } else {

            $html = '<option value="">Choose One...</option>';
            $publics = DB::table('internet_users')
                ->join('public_structures', 'internet_users.public_structure_id', 'public_structures.id')
                ->where("internet_users.community_id", $community_id)
                ->orderBy('public_structures.english_name', 'ASC')
                ->select('public_structures.id', 'public_structures.english_name')
                ->get();

            foreach ($publics as $public) {
                $html .= '<option value="'.$public->id.'">'.$public->english_name.'</option>';
            }
        }

        return response()->json(['html' => $html]);
    }

    /**
     * Get resources from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getActions($issue_id)
    {
        if (!$issue_id) {

            $html = '<option value="">Choose One...</option>';
        } else {

            $html = '<option value="">Choose One...</option>';
            $actions = DB::table('internet_actions')
                ->join('internet_issues', 'internet_actions.internet_issue_id', 'internet_issues.id')
                ->where("internet_actions.internet_issue_id", $issue_id)
                ->orderBy('internet_actions.english_name', 'ASC')
                ->select('internet_actions.id', 'internet_actions.english_name')
                ->get();

            foreach ($actions as $action) {
                $html .= '<option value="'.$action->id.'">'.$action->english_name.'</option>';
            }
        }

        return response()->json(['html' => $html]);
    }
}
