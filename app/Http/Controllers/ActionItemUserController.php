<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\AllEnergyMeter;
use App\Models\User;
use App\Models\Community;
use Carbon\Carbon;
use App\Models\ActionItem;
use App\Models\ActionItemOther;
use App\Models\ActionStatus;
use App\Models\ActionPriority;
use App\Models\CommunityDonor;
use App\Models\CommunityStatus;
use App\Models\CommunityService;
use App\Models\CommunityRepresentative;
use App\Models\CommunityRole;
use App\Models\Compound;
use App\Models\Donor; 
use App\Models\EnergySystem;
use App\Models\EnergySystemType;
use App\Models\EnergyUser;
use App\Models\Household;
use App\Models\HouseholdMeter;
use App\Models\H2oUser;
use App\Models\GridUser;
use App\Models\Photo;
use App\Models\Region;
use App\Models\FbsUserIncident;
use App\Models\H2oSystemIncident; 
use App\Models\GridCommunityCompound;
use App\Models\Setting;
use App\Models\SubRegion;
use App\Models\SubCommunity;
use App\Models\Settlement;
use App\Models\ServiceType;
use App\Models\PublicStructure;
use App\Models\PublicStructureCategory;
use App\Models\ProductType;
use App\Models\CommunityWaterSource;
use App\Models\ElectricityMaintenanceCall;
use App\Models\Town;
use App\Models\BsfStatus; 
use App\Models\H2oSharedUser;
use App\Models\H2oStatus;
use App\Models\Incident;
use App\Models\MgIncident;
use App\Models\IncidentStatusMgSystem;
use App\Models\InternetNetworkIncident;
use App\Models\InternetUserIncident;
use App\Models\EnergySystemCycle;
use App\Models\InternetUser;
use App\Models\RecommendedCommunityEnergySystem;
use App\Models\MeterList;
use App\Models\WaterNetworkUser;
use App\Exports\MissingHouseholdDetailsExport;
use App\Exports\MissingHouseholdAcExport;
use App\Exports\InProgressHouseholdExport;
use App\Exports\EnergyCompoundHousehold;
use App\Mail\ActionItemMail;
use Auth;
use Route;
use DB;
use Excel;
use PDF;
use DataTables;
use Mail;

class ActionItemUserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth'); 
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        if (Auth::guard('user')->user() != null) {
     
            $statusFilter = $request->input('status_filter');
            $priorityFilter = $request->input('priority_filter');
            $startDateFilter = $request->input('start_date_filter');
            $endDateFilter = $request->input('end_date_filter');
 
            if ($request->ajax()) {

                $data =  DB::table('action_items')
                    ->join('users', 'action_items.user_id', 'users.id')
                    ->join('action_priorities', 'action_items.action_priority_id', 'action_priorities.id')
                    ->join('action_statuses', 'action_items.action_status_id', 'action_statuses.id')
                    ->leftJoin('action_item_others', 'action_items.id', 'action_item_others.action_item_id')
                    ->where(function ($query) {
                        $query->where('action_items.user_id', Auth::guard('user')->user()->id)
                            ->orWhere('action_item_others.user_id', Auth::guard('user')->user()->id); 
                    }) 
                    ->distinct()
                    ->where('action_items.is_archived', 0);

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
                    'users.name as owner_name',
                    DB::raw('DATE(action_items.created_at) as created_at'),
                    'action_items.updated_at as updated_at', 'action_statuses.status',
                    'action_statuses.id as status_id', 'action_priorities.id as priority_id'
                )
                ->latest();

                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
    
                        $detailsButton = "<a type='button' class='detailsUserActionItemButton' data-bs-toggle='modal' data-bs-target='#actionItemUserDetails' data-id='".$row->id."'><i class='fa-solid fa-eye text-primary'></i></a>";

                        if($row->owner_name == Auth::guard('user')->user()->name) {

                            $updateButton = "<a type='button' class='updateUserActionItem' data-id='".$row->id."'><i class='fa-solid fa-pen-to-square text-success'></i></a>";
                            $deleteButton = "<a type='button' class='deleteUserActionItem' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";
                            
                            if(Auth::guard('user')->user()->user_type_id != 1 || 
                                Auth::guard('user')->user()->user_type_id != 2) 
                            {
                                     
                                return $detailsButton." ". $updateButton." ".$deleteButton;
                            } else return $detailsButton; 
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
                    ->addColumn('owner', function($row) {

                        $owner = "";

                        if($row->owner_name == Auth::guard('user')->user()->name) $owner = "You";
                        else $owner = $row->owner_name;
                        
                        return $owner;
                    })
                    ->filter(function ($instance) use ($request) {
                        if (!empty($request->get('search'))) {
                                $instance->where(function($w) use($request){
                                $search = $request->get('search');
                                $w->orWhere('action_items.task', 'LIKE', "%$search%")
                                ->orWhere('action_items.date', 'LIKE', "%$search%")
                                ->orWhere('action_items.due_date', 'LIKE', "%$search%")
                                ->orWhere('action_statuses.status', 'LIKE', "%$search%")
                                ->orWhere('action_priorities.name', 'LIKE', "%$search%");
                            });
                        }
                    })
                ->rawColumns(['action', 'statusLabel', 'priorityLabel', 'owner'])
                ->make(true);
            }
    
            $energyCycles = EnergySystemCycle::orderBy('name', 'ASC')->get();

            return view('actions.users.action.index', compact('energyCycles'));
            
        } else {

            return view('errors.not-found');
        }    
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

        $response['actionItem'] = $actionItem;
        $response['user'] = $user;
        $response['userType'] = $userType;
        $response['status'] = $status;
        $response['priority'] = $priority;

        return response()->json($response);
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

        return redirect('/action-item')
            ->with('message', 'Action Item Updated Successfully!');
    }

    /**
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteUserActionItem(Request $request)
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