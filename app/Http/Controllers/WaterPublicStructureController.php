<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\User;
use App\Models\BsfStatus;
use App\Models\Community;
use App\Models\CommunityWaterSource;
use App\Models\GridUser;
use App\Models\GridUserDonor;
use App\Models\H2oSharedUser;
use App\Models\GridPublicStructure;
use App\Models\H2oStatus;
use App\Models\H2oPublicStructure;
use App\Models\H2oUserDonor;
use App\Models\Household;
use App\Models\WaterUser;
use App\Models\EnergyPublicStructure;
use Auth;
use DB;
use Route;
use DataTables;

class WaterPublicStructureController extends Controller
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

                $data = DB::table('h2o_public_structures')
                    ->join('h2o_statuses', 'h2o_public_structures.h2o_status_id', 'h2o_statuses.id')
                    ->join('public_structures', 'h2o_public_structures.public_structure_id', 'public_structures.id')
                    ->join('communities', 'public_structures.community_id', 'communities.id')
                    ->select('h2o_public_structures.id as id',  
                        'communities.english_name as community_name',
                        'h2o_public_structures.created_at as created_at', 
                        'public_structures.english_name', 'public_structures.arabic_name',
                        'h2o_public_structures.updated_at as updated_at', 
                        'h2o_statuses.status', 'h2o_public_structures.installation_year')
                    ->latest();
                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
    
                        $updateButton = "<a type='button' class='updateWaterUser' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#updateWaterUserModal' ><i class='fa-solid fa-pen-to-square text-success'></i></a>";
                        $deleteButton = "<a type='button' class='deleteWaterUser' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";
                        $viewButton = "<a type='button' class='viewWaterUser' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#viewWaterUserModal' ><i class='fa-solid fa-eye text-info'></i></a>";
    
                        return $updateButton." ".$deleteButton. " ". $viewButton;
                    }) 
                   
                    ->filter(function ($instance) use ($request) {
                        if (!empty($request->get('search'))) {
                                $instance->where(function($w) use($request){
                                $search = $request->get('search');
                                $w->orWhere('communities.english_name', 'LIKE', "%$search%")
                                ->orWhere('communities.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('public_structures.english_name', 'LIKE', "%$search%")
                                ->orWhere('public_structures.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('h2o_public_structures.installation_year', 'LIKE', "%$search%")
                                ->orWhere('h2o_statuses.status', 'LIKE', "%$search%");
                            });
                        }
                    })
                ->rawColumns(['action'])
                ->make(true);
            }
    
            $communities = Community::all();
            $bsfStatus = BsfStatus::all();
            $households = Household::all();
            $h2oStatus = H2oStatus::all();
    
            return view('users.water.public.index', compact('communities', 'bsfStatus', 'households', 
                'h2oStatus'));
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
        $h2oPublicStructure = new H2oPublicStructure();
        $h2oPublicStructure->community_id = $request->community_id;
        $h2oPublicStructure->public_structure_id = $request->public_structure_id;
        $h2oPublicStructure->h2o_status_id = $request->h2o_status_id;
        $h2oPublicStructure->bsf_status_id = $request->bsf_status_id;
        $h2oPublicStructure->number_of_bsf = $request->number_of_bsf;
        $h2oPublicStructure->number_of_h20 = $request->number_of_h20; 
        $h2oPublicStructure->h2o_request_date = $request->h2o_request_date; 
        $h2oPublicStructure->installation_year = $request->installation_year;
        $h2oPublicStructure->save();

        $gridPublicStructure = new GridPublicStructure();
        $gridPublicStructure->community_id = $request->community_id;
        $gridPublicStructure->public_structure_id = $request->public_structure_id;
        $gridPublicStructure->request_date = $request->request_date;
        $gridPublicStructure->grid_access = $request->grid_access;
        $gridPublicStructure->grid_integration_large = $request->grid_integration_large;
        $gridPublicStructure->large_date = $request->large_date;
        $gridPublicStructure->grid_integration_small = $request->grid_integration_small;
        $gridPublicStructure->small_date = $request->small_date;
        $gridPublicStructure->is_delivery = $request->is_delivery;
        $gridPublicStructure->is_paid = $request->is_paid;
        $gridPublicStructure->is_complete = $request->is_complete;
        $gridPublicStructure->notes = $request->notes;
        $gridPublicStructure->save();

        return redirect()->back()->with('message', 'New Public Structure Added Successfully!');
    }

    /**
     * Get public meter holder by community_id.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getByPublic(Request $request)
    {
        $publicMeter = EnergyPublicStructure::where('public_structure_id', $request->public_id)->first();

        if($publicMeter == null) {

            $response['meter_number'] = "No";
        } else {

            $response['meter_number'] = $publicMeter->meter_number;
        }
        

        return response()->json($response);
    }
}
