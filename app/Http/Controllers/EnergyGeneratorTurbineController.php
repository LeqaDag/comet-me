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

class EnergyGeneratorTurbineController extends Controller
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
                
                $data = DB::table('energy_generator_communities')
                    ->join('communities', 'energy_generator_communities.community_id', 'communities.id')
                    ->leftJoin('energy_generators', 'energy_generator_communities.energy_generator_id', 'energy_generators.id')
                    ->select('energy_generator_communities.id', 'energy_generator_communities.name', 
                        'communities.english_name', 'energy_generators.generator_model',
                        'energy_generator_communities.created_at', 'energy_generator_communities.updated_at')
                    ->latest();   
 
                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
                        $deleteButton = "<a type='button' class='deleteGenerator' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";
                        
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
                                    $w->orWhere('energy_generator_communities.name', 'LIKE', "%$search%")
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

            $generators = EnergyGenerator::where('is_archived', 0)
                ->orderBy('generator_model', 'ASC')
                ->get();

            $turbines = EnergyTurbine::where('is_archived', 0)
                ->orderBy('model', 'ASC')
                ->get();
                
            return view('users.energy.maintenance.generator.index', compact('communities', 'generators', 'turbines'));

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
        $communityGenerator = new EnergyGeneratorCommunity();
        $communityGenerator->community_id = $request->community_id;
        $communityGenerator->energy_generator_id = $request->energy_generator_id;
        $communityGenerator->name = $request->name;
        $communityGenerator->save();

        return redirect()->back()->with('message', 'New Community Generator Inserted Successfully!');
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
     * View Edit page.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function editPage($id)
    {
        $camera = Camera::findOrFail($id);

        return response()->json($camera);
    } 

    /** 
     * View Edit page.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $camera = Camera::findOrFail($id);

        return view('services.camera.components.camera.edit', compact('camera'));
    }

    /**
     * Update an existing resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request, int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $camera = Camera::findOrFail($id);
        if($request->model) $camera->model = $request->model;
        if($request->brand) $camera->brand = $request->brand;
        $camera->save();

        return redirect('/camera-component')->with('message', 'Camera Updated Successfully!');
    }

    /**
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteEnergyGenerator(Request $request)
    {
        $id = $request->id;

        $generator = EnergyGeneratorCommunity::find($id);

        if($generator) {

            $generator->delete();

            $response['success'] = 1;
            $response['msg'] = 'Generator Deleted successfully'; 
        } else {

            $response['success'] = 0;
            $response['msg'] = 'Invalid ID.';
        }

        return response()->json($response); 
    }
}
