<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\User;
use App\Models\EnergyAction;
use App\Models\EnergyActionCategory;
use App\Models\EnergyIssue;
use App\Models\InternetMaintenanceCall;
use App\Models\EnergyMaintenanceIssueType;
use App\Models\EnergyMaintenanceIssue;
use App\Models\MaintenanceStatus;
use App\Models\MaintenanceType;
use App\Exports\EnergyIssuesExport;
use Auth;
use DB;
use Route;
use DataTables;
use Excel; 

class EnergyIssueController extends Controller
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
            $issueTypeFilter = $request->input('issue_type_filter');

            if ($request->ajax()) {

                $data = DB::table('energy_issues')
                    ->join('energy_maintenance_issue_types', 'energy_issues.energy_maintenance_issue_type_id', 
                        'energy_maintenance_issue_types.id')
                    ->join('energy_actions', 'energy_issues.energy_action_id', 'energy_actions.id')
                    ->where('energy_issues.is_archived', 0) 
                    ->where('energy_actions.is_archived', 0);

                if($actionFilter != null) {

                    $data->where('energy_actions.id', $actionFilter);
                }

                if($issueTypeFilter != null) {

                    $data->where('energy_maintenance_issue_types.id', $issueTypeFilter);
                }

                $data->select('energy_issues.id as id', 
                    'energy_issues.english_name', 
                    'energy_issues.arabic_name',
                    'energy_actions.english_name as energy_action', 
                    'energy_maintenance_issue_types.name as type',  
                    'energy_issues.created_at as created_at',
                    'energy_issues.updated_at as updated_at')
                ->latest();

                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {

                        $updateButton = "<a type='button' class='updateEnergyIssue' data-id='".$row->id."'><i class='fa-solid fa-pen-to-square text-success'></i></a>";
                        $deleteButton = "<a type='button' class='deleteEnergyIssue' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";

                        if(Auth::guard('user')->user()->user_type_id == 1 || 
                            Auth::guard('user')->user()->user_type_id == 7 ||
                            Auth::guard('user')->user()->user_type_id == 4) 
                        {
                                
                            return $updateButton. " ". $deleteButton ;
                        } else return "";
                    })
                    ->filter(function ($instance) use ($request) {
                        if (!empty($request->get('search'))) {
                                $instance->where(function($w) use($request){
                                $search = $request->get('search');
                                $w->orWhere('energy_issues.english_name', 'LIKE', "%$search%")
                                ->orWhere('energy_issues.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('energy_actions.english_name', 'LIKE', "%$search%")
                                ->orWhere('energy_actions.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('energy_maintenance_issue_types.name', 'LIKE', "%$search%");
                            });
                        }
                    })
                ->rawColumns(['action'])
                ->make(true);
            }

            $actionCategories = EnergyActionCategory::where("is_archived", 0)->get();
            $energyActions = EnergyAction::where('is_archived', 0)->get();
            $energyIssueTypes = EnergyMaintenanceIssueType::all();

            return view('users.energy.maintenance.issue.index', compact('actionCategories', 'energyActions', 
                'energyIssueTypes'));
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
        $energyIssue = new EnergyIssue();

        $energyIssue->english_name = $request->english_name;
        $energyIssue->arabic_name = $request->arabic_name;
        $energyIssue->energy_action_id = $request->energy_action_id;
        $energyIssue->energy_maintenance_issue_type_id = $request->energy_maintenance_issue_type_id;
        $energyIssue->notes = $request->notes;
        $energyIssue->save();
  
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
        $energyIssue = EnergyIssue::findOrFail($id);
        $energyActions = EnergyAction::where("is_archived", 0)->get();
        $energyCategories = EnergyActionCategory::where("is_archived", 0)->get();
        $energyIssueTypes = EnergyMaintenanceIssueType::all();

        //die($energyIssue);

        return view('users.energy.maintenance.issue.edit', compact('energyIssue', 'energyCategories', 
            'energyAction', 'energyIssueTypes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(int $id, Request $request)
    {     
        $energyIssue = EnergyIssue::findOrFail($id);

        if($request->english_name) $energyIssue->english_name = $request->english_name;
        if($request->arabic_name) $energyIssue->arabic_name = $request->arabic_name;
        if($request->energy_action_id) $energyIssue->energy_action_id = $request->energy_action_id;
        if($request->energy_action_category_id) {

            $energyAction = EnergyAction::findOrFail($energyIssue->energy_action_id);
            $energyAction->energy_action_category_id = $request->energy_action_category_id;
            $energyAction->save();
        }
        if($request->energy_maintenance_issue_type_id) $energyIssue->energy_maintenance_issue_type_id = $request->energy_maintenance_issue_type_id;
        if($request->notes == null) $energyIssue->notes = null;
        if($request->notes) $energyIssue->notes = $request->notes;
        $energyIssue->save(); 
  
        return redirect('/energy-issue')->with('message', 'Energy Issue Updated Successfully!');
    }

    /**
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteEnergyIssue(Request $request)
    {
        $id = $request->id;

        $energyIssue = EnergyIssue::find($id);

        if($energyIssue) {

            $energyIssue->is_archived = 1;
            $energyIssue->save();

            $response['success'] = 1;
            $response['msg'] = 'Energy Issue Deleted successfully'; 
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
    public function getEnergyActionBasedOnCategory(Request $request)
    {
        if (!$request->id) {

            $html = '<option disabled selected>Choose One...</option>';
        } else {

            $html = '<option  disabled selected>Choose One...</option>';

            $energyActions = EnergyAction::where('energy_action_category_id', $request->id)
                ->orderBy('english_name', 'ASC')
                ->where('is_archived', 0)
                ->get();

            foreach ($energyActions as $energyAction) {

                $html .= '<option value="'.$energyAction->id.'">'.$energyAction->english_name.'</option>';
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
                
        return Excel::download(new EnergyIssuesExport($request), 'energy_issues.xlsx');
    }
}