<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\User;
use App\Models\InternetMaintenanceCall;
use App\Models\EnergyMaintenanceAction;
use App\Models\EnergyMaintenanceIssueType;
use App\Models\EnergyMaintenanceIssue;
use App\Models\MaintenanceStatus;
use App\Models\MaintenanceType;
use App\Exports\EnergyActionExport;
use Auth;
use DB;
use Route;
use DataTables;
use Excel;

class EnergyActionController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Auth::guard('user')->user() != null) {

            $issueFilter = $request->input('issue_filter');
            $issueTypeFilter = $request->input('issue_type_filter');

            if ($request->ajax()) {
                $data = DB::table('energy_maintenance_actions')
                    ->join('energy_maintenance_issues', 'energy_maintenance_actions.energy_maintenance_issue_id', 
                        'energy_maintenance_issues.id')
                    ->join('energy_maintenance_issue_types', 'energy_maintenance_actions.energy_maintenance_issue_type_id', 
                        'energy_maintenance_issue_types.id');

                if($issueFilter != null) {

                    $data->where('energy_maintenance_issues.id', $issueFilter);
                }

                if($issueTypeFilter != null) {

                    $data->where('energy_maintenance_issue_types.id', $issueTypeFilter);
                }

                $data
                ->select('energy_maintenance_actions.id as id', 
                    'energy_maintenance_actions.english_name', 
                    'energy_maintenance_actions.arabic_name',
                    'energy_maintenance_issues.english_name as issue',
                    'energy_maintenance_issue_types.name',
                    'energy_maintenance_actions.created_at as created_at',
                    'energy_maintenance_actions.updated_at as updated_at')
                ->latest();

                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {

                        $updateButton = "<a type='button' class='updateEnergyAction' data-id='".$row->id."'><i class='fa-solid fa-pen-to-square text-success'></i></a>";
                        $deleteButton = "<a type='button' class='deleteEnergyAction' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";
                        $viewButton = "<a type='button' class='viewEnergyAction' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#viewEnergyActionModal' ><i class='fa-solid fa-eye text-info'></i></a>";

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
                                $w->orWhere('energy_maintenance_actions.english_name', 'LIKE', "%$search%")
                                ->orWhere('energy_maintenance_actions.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('energy_maintenance_issues.english_name', 'LIKE', "%$search%")
                                ->orWhere('energy_maintenance_issues.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('energy_maintenance_issue_types.name', 'LIKE', "%$search%");
                            });
                        }
                    })
                ->rawColumns(['action'])
                ->make(true);
            }

            $energyIssues = EnergyMaintenanceIssue::all();
            $energyIssueTypes = EnergyMaintenanceIssueType::all();

            return view('users.energy.maintenance.action.index', compact('energyIssues',
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
        $energyAction = new EnergyMaintenanceAction();

        $energyAction->english_name = $request->english_name;
        $energyAction->arabic_name = $request->arabic_name;
        $energyAction->energy_maintenance_issue_type_id = $request->energy_maintenance_issue_type_id;
        $energyAction->energy_maintenance_issue_id = $request->energy_maintenance_issue_id;
        $energyAction->notes = $request->notes;
        $energyAction->save();
  
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
        $energyAction = EnergyMaintenanceAction::findOrFail($id);
        $energyIssues = EnergyMaintenanceIssue::all();
        $energyIssueTypes = EnergyMaintenanceIssueType::all();

        return view('users.energy.maintenance.action.edit', compact('energyIssues',
            'energyIssueTypes', 'energyAction'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(int $id, Request $request)
    {     
        $energyAction = EnergyMaintenanceAction::findOrFail($id);

        if($request->english_name) $energyAction->english_name = $request->english_name;
        if($request->arabic_name) $energyAction->arabic_name = $request->arabic_name;
        if($request->energy_maintenance_issue_type_id) $energyAction->energy_maintenance_issue_type_id = $request->energy_maintenance_issue_type_id;
        if($request->energy_maintenance_issue_id) $energyAction->energy_maintenance_issue_id = $request->energy_maintenance_issue_id;
        if($request->notes) $energyAction->notes = $request->notes;
        $energyAction->save();
  
        return redirect('/energy-action')->with('message', 'Energy Action Updated Successfully!');
    }

    /**
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteEnergyMainAction(Request $request)
    {
        $id = $request->id;

        $energyAction = EnergyMaintenanceAction::find($id);

        if($energyAction) {

            $energyAction->delete(); 

            $response['success'] = 1;
            $response['msg'] = 'Energy Action Deleted successfully'; 
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
                
        return Excel::download(new EnergyActionExport($request), 'energy_actions.xlsx');
    }


    /**
     * Get a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getEnergyAction(Request $request)
    {
        $id = $request->id;

        $energyAction = EnergyMaintenanceAction::find($id);

        return response()->json($energyAction); 
    }
}
