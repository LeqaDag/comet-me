<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use App\Models\Community;
use App\Models\ElectricityMaintenanceCall;
use App\Models\ElectricityMaintenanceCallAction;
use App\Models\EnergyMaintenanceAction;
use App\Models\EnergyMaintenanceIssue;
use App\Models\EnergyTurbineCommunity;
use App\Models\EnergyGeneratorCommunity;
use App\Models\EnergyGenerator;
use App\Models\EnergyTurbine;
use Carbon\Carbon;
use Auth;
use DB; 
use Route;
use DataTables;
use Excel;
use Illuminate\Support\Facades\URL;

class EnergyTurbineController extends Controller
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
                
                $data = DB::table('energy_turbine_communities')
                    ->join('communities', 'energy_turbine_communities.community_id', 'communities.id')
                    ->leftJoin('energy_turbines', 'energy_turbine_communities.energy_turbine_id', 'energy_turbines.id')
                    ->select(
                        'energy_turbine_communities.id', 'energy_turbine_communities.name as turbine_name', 
                        'communities.english_name as community', 'energy_turbines.model',
                        'energy_turbine_communities.created_at', 'energy_turbine_communities.updated_at')
                    ->latest();   
 
                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
                        $deleteButton = "<a type='button' class='deleteTurbine' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";
                        
                        if(Auth::guard('user')->user()->user_type_id == 1 || 
                            Auth::guard('user')->user()->user_type_id == 2 || 
                            Auth::guard('user')->user()->user_type_id == 4 || 
                            Auth::guard('user')->user()->user_type_id == 7) 
                        {
                                
                            return $deleteButton;
                        } 

                    })
                    ->filter(function ($instance) use ($request) {
                        if (!empty($request->get('search'))) {
                                $instance->where(function($w) use($request) {
                                    $search = $request->get('search');
                                    $w->orWhere('energy_turbine_communities.name', 'LIKE', "%$search%")
                                    ->orWhere('communities.english_name', 'LIKE', "%$search%");
                            });
                        }
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }

            $communities = Community::where('is_archived', 0)
                ->orderBy('english_name', 'ASC')
                ->get();

            $turbines = EnergyTurbine::where('is_archived', 0)
                ->orderBy('generator_model', 'ASC')
                ->get();

            return view('users.energy.maintenance.generator.index', compact('communities', 'turbines'));

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
        $communityTurbine = new EnergyTurbineCommunity();
        $communityTurbine->community_id = $request->community_id;
        $communityTurbine->energy_turbine_id = $request->energy_turbine_id;
        $communityTurbine->name = $request->name;
        $communityTurbine->save();

        return redirect()->back()->with('message', 'New Community Turbine Inserted Successfully!');
    }

    /**
     * Show the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response 
     */
    public function show($id)
    {
      
    }

    /**
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteEnergyTurbine(Request $request)
    {
        $id = $request->id;

        $turbine = EnergyTurbineCommunity::find($id);

        if($turbine) {

            $turbine->delete();

            $response['success'] = 1;
            $response['msg'] = 'Turbine Deleted successfully'; 
        } else {

            $response['success'] = 0;
            $response['msg'] = 'Invalid ID.';
        }

        return response()->json($response); 
    }
}
