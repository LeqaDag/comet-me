<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\User;
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

            if ($request->ajax()) {
                $data = DB::table('energy_maintenance_issues')
                    ->select('energy_maintenance_issues.id as id', 
                        'energy_maintenance_issues.english_name', 
                        'energy_maintenance_issues.arabic_name',
                        'energy_maintenance_issues.created_at as created_at',
                        'energy_maintenance_issues.updated_at as updated_at')
                    ->latest();

                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {

                        $updateButton = "<a type='button' class='updateEnergyIssue' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#updateEnergyIssueModal' ><i class='fa-solid fa-pen-to-square text-success'></i></a>";
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
                                $w->orWhere('energy_maintenance_issues.english_name', 'LIKE', "%$search%")
                                ->orWhere('energy_maintenance_issues.arabic_name', 'LIKE', "%$search%");
                            });
                        }
                    })
                ->rawColumns(['action'])
                ->make(true);
            }

            $energyIssueTypes = EnergyMaintenanceIssueType::all();

            return view('users.energy.maintenance.issue.index', compact('energyIssueTypes'));
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
        $energyIssue = new EnergyMaintenanceIssue();

        $energyIssue->english_name = $request->english_name;
        $energyIssue->arabic_name = $request->arabic_name;
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

        $energyIssue = EnergyMaintenanceIssue::find($id);

        return response()->json($energyIssue); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(int $id, Request $request)
    {     
        $energyIssue = EnergyMaintenanceIssue::findOrFail($id);

        if($request->english_name) $energyIssue->english_name = $request->english_name;
        if($request->arabic_name) $energyIssue->arabic_name = $request->arabic_name;
        if($request->notes == null) $energyIssue->notes = null;
        if($request->notes) $energyIssue->notes = $request->notes;
        $energyIssue->save();
  
        return redirect()->back()
            ->with('message', 'Issue Updated Successfully!');
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

        $energyIssue = EnergyMaintenanceIssue::find($id);

        if($energyIssue) {

            $energyIssue->delete();

            $response['success'] = 1;
            $response['msg'] = 'Energy Issue Deleted successfully'; 
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
                
        return Excel::download(new EnergyIssuesExport($request), 'energy_issues.xlsx');
    }
}
