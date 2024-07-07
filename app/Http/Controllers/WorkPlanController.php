<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\User;
use App\Models\UserType;
use App\Models\ActionItem;
use App\Models\ActionStatus;
use App\Models\ActionPriority;
use App\Models\ActionItemOther;
use App\Models\Community;
use App\Mail\ActionItemMail;
use Auth;
use DB;
use Route;
use DataTables;
use Excel;
use Mail;

class WorkPlanController extends Controller
{ 
    /**
     * Display a listing of the resource. 
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {	
        if (Auth::guard('user')->user() != null) {

            $userFilter = $request->input('user_filter');
            $otherUserFilter = $request->input('assigned_user_filter');
            $statusFilter = $request->input('status_filter');
            $priorityFilter = $request->input('priority_filter');
            $startDateFilter = $request->input('start_date_filter');
            $endDateFilter = $request->input('end_date_filter');

            if ($request->ajax()) {

                $data = DB::table('action_items')
                    ->join('users', 'action_items.user_id', 'users.id')
                    ->join('action_priorities', 'action_items.action_priority_id', 'action_priorities.id')
                    ->join('action_statuses', 'action_items.action_status_id', 'action_statuses.id')
                    ->leftJoin('action_item_others', 'action_items.id', 'action_item_others.action_item_id')
                    ->leftJoin('users as others_users', 'others_users.id', 'action_item_others.user_id')
                    ->where('action_items.is_archived', 0);

                if($userFilter != null) {

                    $data->where('users.id', $userFilter);
                }
                if($otherUserFilter != null) {

                    $data->where('others_users.id', $otherUserFilter);
                }
                if ($statusFilter != null) {

                    $data->where('action_statuses.id', $statusFilter);
                }
                if ($priorityFilter != null) {

                    $data->where('action_priorities.id', $priorityFilter);
                }
                if ($startDateFilter != null) {

                    $data->where('action_items.date', '>=', $startDateFilter);
                }
                if ($endDateFilter != null) {

                    $data->where('action_items.due_date', "<=", $endDateFilter);
                }

                $data->select(
                    'action_items.id as id', 'action_items.task',
                    'action_priorities.name as priority', 'action_items.date',
                    'users.name', 'action_items.created_at as created_at', 'users.image',
                    'action_items.updated_at as updated_at', 'action_statuses.status',
                    'action_statuses.id as status_id', 'action_priorities.id as priority_id',
                    DB::raw('GROUP_CONCAT(others_users.name) as other_users'), // Concatenate other_users
                    DB::raw('GROUP_CONCAT(others_users.image) as other_images'),
                )
                ->groupBy('action_items.id') 
                ->distinct()
                ->latest();
  
                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
    
                        $detailsButton = "<a type='button' class='detailsWorkPlanButton' data-bs-toggle='modal' data-bs-target='#workPlanDetails' data-id='".$row->id."'><i class='fa-solid fa-eye text-primary'></i></a>";
                        $updateButton = "<a type='button' class='updateWorkPlan' data-id='".$row->id."'><i class='fa-solid fa-pen-to-square text-success'></i></a>";
                        $deleteButton = "<a type='button' class='deleteWorkPlan' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";
                        
                        if(Auth::guard('user')->user()->user_type_id != 1 || 
                            Auth::guard('user')->user()->user_type_id != 2) 
                        {
                                
                            return $detailsButton." ". $updateButton." ".$deleteButton;
                        } else return $detailsButton; 
                    })
                    ->addColumn('statusLabel', function($row) {

                        if($row->status_id == 1) 
                        $statusLabel = "<span class='badge rounded-pill bg-label-info'>".$row->status."</span>";

                        else if($row->status_id == 2) 
                        $statusLabel = "<span class='badge rounded-pill bg-label-warning'>".$row->status."</span>";
                       
                        else if($row->status_id == 3) 
                        $statusLabel = "<span class='badge rounded-pill bg-label-danger'>".$row->status."</span>";

                        else if($row->status_id == 4) 
                        $statusLabel = "<span class='badge rounded-pill bg-label-success'>".$row->status."</span>";

                        return $statusLabel;
                    })
                    ->addColumn('priorityLabel', function($row) {

                        if($row->priority_id == 1) 
                        $priorityLabel = "<span class='badge bg-primary'>".$row->priority."</span>";

                        else if($row->priority_id == 2) 
                        $priorityLabel = "<span class='badge bg-warning text-dark'>".$row->priority."</span>";
                       
                        else if($row->priority_id == 3) 
                        $priorityLabel = "<span class='badge bg-danger'>".$row->priority."</span>";

                        return $priorityLabel;
                    })
                   
                    ->filter(function ($instance) use ($request) {
                        if (!empty($request->get('search'))) {
                                $instance->where(function($w) use($request){
                                $search = $request->get('search');
                                $w->orWhere('action_items.task', 'LIKE', "%$search%")
                                ->orWhere('action_items.date', 'LIKE', "%$search%")
                                ->orWhere('action_items.due_date', 'LIKE', "%$search%")
                                ->orWhere('users.name', 'LIKE', "%$search%")
                                ->orWhere('action_statuses.status', 'LIKE', "%$search%")
                                ->orWhere('action_priorities.name', 'LIKE', "%$search%");
                            });
                        }
                    })
                ->rawColumns(['action', 'statusLabel', 'priorityLabel'])
                ->make(true);
            }
    
            $actionStatuses = ActionStatus::all();  
            $actionPriorities = ActionPriority::all(); 
            $users = User::where('is_archived', 0)
                ->whereIn('user_type_id', [1, 2, 3, 4, 5, 6])
                ->orderBy('name', 'ASC')
                ->get();

            $otherUsers = User::where('is_archived', 0)
                ->orderBy('name', 'ASC')
                ->get();

            return view('plans.index', compact('actionStatuses', 'actionPriorities', 'users', 'otherUsers'));
        } else {

            return view('errors.not-found');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Show the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $actionItem = ActionItem::findOrFail($id);
        $user = User::where('id', $actionItem->user_id)->first();
        $userType = UserType::where('id', $user->user_type_id)->first();
        $status = ActionStatus::where('id', $actionItem->action_status_id)->first();
        $priority = ActionPriority::where('id', $actionItem->action_priority_id)->first();
        $others = DB::table('action_item_others')
            ->join('action_items', 'action_item_others.action_item_id', 'action_items.id')
            ->join('users as others', 'action_item_others.user_id', 'others.id')
            ->where('action_item_others.action_item_id', $id)
            ->select('others.name')
            ->get();

        $response['actionItem'] = $actionItem;
        $response['user'] = $user;
        $response['userType'] = $userType;
        $response['status'] = $status;
        $response['priority'] = $priority;
        $response['others'] = $others;

        return response()->json($response);
    }

    /** 
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $actionItem = new ActionItem();
        $actionItem->user_id = $request->user_id;
        $actionItem->task = $request->task;
        $actionItem->date = $request->date;
        $actionItem->due_date = $request->due_date;
        $actionItem->action_status_id = $request->action_status_id;
        $actionItem->action_priority_id = $request->action_priority_id;
        $actionItem->notes = $request->notes;
        $actionItem->save(); 

        $user = User::findOrFail($request->user_id);

        if($request->other_ids) {
            for($i=0; $i < count($request->other_ids); $i++) {

                $assignedToOther = new ActionItemOther();
                $assignedToOther->user_id = $request->other_ids[$i];
                $assignedToOther->action_item_id = $actionItem->id;
                $assignedToOther->save();

                $otherUser = User::findOrFail($request->other_ids[$i]);
                try { 

                    $details = [
                        'title' => 'New Action Item',
                        'name' => $otherUser->name,
                        'body' => $user->name .' has assigned a new action item with you called : '.$request->task .' ,please review it on your account.',
                        'start_date' => $request->date,
                        'end_date' => $request->due_date,
                    ];
                    
                    Mail::to($otherUser->email)->send(new ActionItemMail($details));
                } catch (Exception $e) {
        
                    info("Error: ". $e->getMessage());
                }
            }
        }

        try { 

            $details = [
                'title' => 'Your Action Item',
                'name' => $user->name,
                'body' => 'You have a new action item called : '.$request->task .' ,please review it on your account.',
                'start_date' => $request->date,
                'end_date' => $request->due_date,
            ];
            
            Mail::to($user->email)->send(new ActionItemMail($details));
        } catch (Exception $e) {

            info("Error: ". $e->getMessage());
        }

        return redirect()->back()->with('message', 'New Action Item Added Successfully!');
    }

    /**
     * View Edit page.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) 
    {
        $actionItem = ActionItem::findOrFail($id);
        $actionStatuses = ActionStatus::all();
        $actionPriorities = ActionPriority::all();
        $others = DB::table('action_item_others')
            ->join('action_items', 'action_item_others.action_item_id', 'action_items.id')
            ->join('users as others', 'action_item_others.user_id', 'others.id')
            ->where('action_item_others.action_item_id', $id)
            ->select('others.name as name', 'action_item_others.id as id')
            ->get();

        $users = User::where('id', '!=', $actionItem->user_id)
            ->orderBy('name', 'ASC')
            ->where('is_archived', 0)
            ->get();

        return view('plans.edit', compact('actionItem', 'actionStatuses', 'actionPriorities', 'others',
            'users'));
    }

    /**
     * Update an existing resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request, int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $actionItem = ActionItem::where('id', $id)->first();
        $actionItem->task = $request->task;
        if($request->date) $actionItem->date = $request->date;
        if($request->due_date) $actionItem->due_date = $request->due_date;
        if($request->action_status_id) $actionItem->action_status_id = $request->action_status_id;
        if($request->action_priority_id) $actionItem->action_priority_id = $request->action_priority_id;
        if($request->notes) $actionItem->notes = $request->notes;
        $actionItem->save(); 

        $user = User::findOrFail($actionItem->user_id);

        if($request->new_other) {
 
            for($i=0; $i < count($request->new_other); $i++) {

                $assignedToOther = new ActionItemOther();
                $assignedToOther->user_id = $request->new_other[$i];
                $assignedToOther->action_item_id = $actionItem->id;
                $assignedToOther->save();

                $otherUser = User::findOrFail($request->new_other[$i]);
            }

            try { 

                $details = [
                    'title' => 'New Action Item',
                    'name' => $otherUser->name,
                    'body' => $user->name .' has assigned a new action item with you called : '.$actionItem->task .' ,please review it on your account.',
                    'start_date' => $actionItem->date,
                    'end_date' => $actionItem->due_date,
                ];
                
                Mail::to($otherUser->email)->send(new ActionItemMail($details));
            } catch (Exception $e) {
    
                info("Error: ". $e->getMessage());
            }
        }

        if($request->more_other) {

            for($i=0; $i < count($request->more_other); $i++) {

                $assignedToOther = new ActionItemOther();
                $assignedToOther->user_id = $request->more_other[$i];
                $assignedToOther->action_item_id = $actionItem->id;
                $assignedToOther->save();

                $otherUser = User::findOrFail($request->more_other[$i]);
            }

            try { 

                $details = [
                    'title' => 'New Action Item',
                    'name' => $otherUser->name,
                    'body' => $user->name .' has assigned a new action item with you called : '.$actionItem->task .' ,please review it on your account.',
                    'start_date' => $actionItem->date,
                    'end_date' => $actionItem->due_date,
                ];
                
                Mail::to($otherUser->email)->send(new ActionItemMail($details));
            } catch (Exception $e) {
    
                info("Error: ". $e->getMessage());
            }
        }

        return redirect('/work-plan')
            ->with('message', 'Action Item Updated Successfully!');
    }

    /**
     * Get other users.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getOtherUser(Request $request)
    {
        if (!$request->id) {

            $html = '<option disabled selected>Choose One...</option>';
        } else {

            $html = '<option disabled selected>Choose One...</option>';
            $users = User::where('id', '!=', $request->id)
                //->whereIn('user_type_id', [1, 2, 3, 4, 5, 6])
                ->orderBy('name', 'ASC')
                ->where('is_archived', 0)
                ->get();

            foreach ($users as $user) {

                $html .= '<option value="'.$user->id.'">'.$user->name.'</option>';
            }
        }

        return response()->json(['html' => $html]);
    }

    /**
     * Get other users.
     *
     * @return \Illuminate\Http\Response
     */
    public function getOtherUsers()
    {
        $id = Auth::guard('user')->user()->id;

        $html = '<option disabled selected>Choose One...</option>';
        $users = User::where('id', '!=', $id)
            //->whereIn('user_type_id', [1, 2, 3, 4, 5, 6])
            ->orderBy('name', 'ASC')
            ->where('is_archived', 0)
            ->get();

        foreach ($users as $user) {

            $html .= '<option value="'.$user->id.'">'.$user->name.'</option>';
        }

        return response()->json(['html' => $html]);
    }

    /**
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteOtherUser(Request $request)
    {
        $id = $request->id;
 
        $other = ActionItemOther::findOrFail($id);

        if($other) {

            $other->delete();

            $response['success'] = 1;
            $response['msg'] = 'User Deleted successfully'; 
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
    public function deleteOtherUserFromAdmin(Request $request)
    {
        $id = $request->id;
 
        $other = ActionItemOther::findOrFail($id);

        if($other) {

            $other->delete();

            $response['success'] = 1;
            $response['msg'] = 'User Deleted successfully'; 
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
    public function deleteWorkPlan(Request $request)
    {
        $workPlan = ActionItem::findOrFail($request->id);

        if($workPlan) {

            $workPlan->is_archived = 1;
            $workPlan->save();

            $response['success'] = 1;
            $response['msg'] = 'Action Item Deleted successfully'; 
        } else {

            $response['success'] = 0;
            $response['msg'] = 'Invalid ID.';
        }

        return response()->json($response); 
    }
}