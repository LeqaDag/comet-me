<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use DB; 
use Route;
use App\Models\AllEnergyMeter;
use App\Models\AllEnergyMeterDonor;
use App\Models\User;
use App\Models\Community;
use App\Models\EnergySystemType;
use App\Models\EnergyRequestStatus;
use App\Models\EnergyRequestSystem;
use App\Models\Household;
use App\Models\PublicStructure;
use App\Models\InstallationType;
use App\Models\Region;
use App\Exports\EnergyRequestSystemExport;
use Carbon\Carbon;
use Image;
use Excel;
use DataTables;

class EnergyRequestSystemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $requestedSystems = EnergyRequestSystem::where("installation_type_id", 2)->get();
        foreach($requestedSystems as $requestedSystem) {

            $requestedSystem->recommendede_energy_system_id = 2;
            $requestedSystem->save();
        }

        if (Auth::guard('user')->user() != null) {
 
            if ($request->ajax()) {

                $dataPublic = DB::table('energy_request_systems')
                    ->join('households', 'energy_request_systems.household_id', '=', 'households.id')
                    ->join('communities', 'households.community_id', '=', 'communities.id')
                    ->leftJoin('energy_request_statuses', 'energy_request_systems.energy_request_status_id', 
                        '=', 'energy_request_statuses.id')
                    ->where('energy_request_systems.is_archived', 0)
                    ->select('energy_request_systems.date', 
                        'energy_request_systems.id as id', 'energy_request_systems.created_at as created_at', 
                        'energy_request_systems.updated_at as updated_at', 
                        'communities.english_name as community_name', 'energy_request_statuses.name',
                        'households.english_name as household',
                        'energy_request_systems.energy_service',
                        'energy_request_systems.water_service',
                        'energy_request_systems.internet_service')
                    ->latest(); 
    
                 
                return Datatables::of($dataPublic)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
    
                        $viewButton = "<a type='button' class='viewEnergyRequest' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#viewEnergyRequestModal' ><i class='fa-solid fa-eye text-info'></i></a>";
                        $updateButton = "<a type='button' class='updateEnergyRequest' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#updateEnergyUserModal' ><i class='fa-solid fa-pen-to-square text-success'></i></a>";
                        $deleteButton = "<a type='button' class='deleteEnergyRequest' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";
    
                        if(Auth::guard('user')->user()->user_type_id == 1 || 
                            Auth::guard('user')->user()->user_type_id == 2 ||
                            Auth::guard('user')->user()->user_type_id == 3 ||
                            Auth::guard('user')->user()->user_type_id == 4 ||
                            Auth::guard('user')->user()->user_type_id == 5 ||
                            Auth::guard('user')->user()->user_type_id == 6) 
                        {
                                
                            return $viewButton." ". $updateButton." ".$deleteButton;
                        } else return $viewButton;
       
                    })
                    ->filter(function ($instance) use ($request) {
                        if (!empty($request->get('search'))) {
                                $instance->where(function($w) use($request) {
                                $search = $request->get('search');
                                $w->orWhere('communities.english_name', 'LIKE', "%$search%")
                                ->orWhere('communities.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('energy_request_systems.date', 'LIKE', "%$search%")
                                ->orWhere('households.english_name', 'LIKE', "%$search%")
                                ->orWhere('households.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('energy_request_statuses.name', 'LIKE', "%$search%");
                            });
                        }
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
    
            $communities = Community::where('is_archived', 0)
                //->where('community_status_id', 4)
                ->orderBy('english_name', 'ASC')
                ->get();
            $households = Household::where('is_archived', 0)
                ->orderBy('english_name', 'ASC')
                ->get();
            $requestStatuses = EnergyRequestStatus::where('is_archived', 0)
                ->orderBy('name', 'ASC')
                ->get();

            return view('request.energy.index', compact('communities', 'households',
                'requestStatuses'));
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
        $communities = Community::where('is_archived', 0)
            ->orderBy('english_name', 'ASC')
            ->get();
        $requestStatuses = EnergyRequestStatus::where('is_archived', 0)
            ->orderBy('name', 'ASC')
            ->get();

        $installationTypes = InstallationType::where('is_archived', 0)->get();
        $energySystemTypes = EnergySystemType::where('is_archived', 0)->get();

        return view('request.energy.create', compact('communities', 'requestStatuses', 'energySystemTypes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // $energyRequestSystem = new EnergyRequestSystem();
        // $energyRequestSystem->community_id = $request->community_id;
        // $energyRequestSystem->community_id = $request->community_id;
        // $energyRequestSystem->community_id = $request->community_id;
        // $energyRequestSystem->community_id = $request->community_id;
        // $energyRequestSystem->community_id = $request->community_id;
        // $energyRequestSystem->save();

        return redirect()->back()
            ->with('message', 'New Energy Requested System Added Successfully!');
    }


     /**
     * Get households by community_id.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getRequestedByCommunity(Request $request)
    {
        if (!$request->community_id) {

            $html = '<option value="">Choose One...</option>';
        } else {

            $html = '<option selected>Choose One...</option>';
            $households = Household::where('community_id', $request->community_id)
                ->where('is_archived', 0)
                ->where('household_status_id', 5)
                ->orderBy('english_name', 'ASC')
                ->get();

            foreach ($households as $household) {
                $html .= '<option value="'.$household->id.'">'.$household->english_name.'</option>';
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
                
        return Excel::download(new EnergyRequestSystemExport($request), 'Energy Project.xlsx');
    }
}
