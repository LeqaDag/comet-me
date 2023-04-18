<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\User;
use App\Models\BsfStatus;
use App\Models\Donor;
use App\Models\Community;
use App\Models\CommunityWaterSource;
use App\Models\GridUser;
use App\Models\GridUserDonor;
use App\Models\H2oSharedUser;
use App\Models\H2oStatus;
use App\Models\H2oUser;
use App\Models\H2oUserDonor;
use App\Models\Household;
use App\Models\WaterUser;
use Auth;
use DB;
use Route;
use DataTables;

class AllWaterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {	
        if ($request->ajax()) {
            $data = DB::table('h2o_users') 
                ->LeftJoin('grid_users', 'h2o_users.household_id', '=', 'grid_users.household_id')
                ->join('households', 'h2o_users.household_id', 'households.id')
                ->join('communities', 'h2o_users.community_id', 'communities.id')
                ->join('h2o_statuses', 'h2o_users.h2o_status_id', '=', 'h2o_statuses.id')
               // ->where('h2o_statuses.status', 'Used')
                ->select('h2o_users.id as id', 'households.english_name', 'number_of_h20',
                    'grid_integration_large', 'large_date', 'grid_integration_small', 
                    'small_date', 'is_delivery', 'number_of_bsf', 'is_paid', 
                    'is_complete', 'communities.english_name as community_name',
                    'installation_year', 'h2o_users.created_at as created_at',
                    'h2o_users.updated_at as updated_at', 'h2o_statuses.status')
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
                            ->orWhere('households.english_name', 'LIKE', "%$search%")
                            ->orWhere('households.arabic_name', 'LIKE', "%$search%")
                            ->orWhere('h2o_statuses.status', 'LIKE', "%$search%")
                            ->orWhere('grid_integration_large', 'LIKE', "%$search%")
                            ->orWhere('grid_integration_small', 'LIKE', "%$search%");
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

  
		return view('users.water.all.index', compact('communities', 'bsfStatus', 'households', 
            'h2oStatus'));
    }

    /**
     * View Edit page.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function editPage($id)
    {
        $h2oUser = H2oUser::findOrFail($id);

        return response()->json($h2oUser);
    }

    /**
     * View Edit page.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $h2oUser = H2oUser::findOrFail($id);
        $gridUser = GridUser::where('household_id', $h2oUser->household_id)->first();
        $communities = Community::all();
        $h2oStatuses = H2oStatus::all();
        $bsfStatuses = BsfStatus::all();
        $households = Household::where('community_id', $h2oUser->community_id)->get();

        return view('users.water.all.edit', compact('households', 'h2oStatuses', 'communities',
            'h2oUser', 'gridUser', 'bsfStatuses'));
    }

    /**
     * Update an existing resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request, int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
       // dd($request->all());
        $h2oUser = H2oUser::findOrFail($id);
        $gridUser = GridUser::where('household_id', $h2oUser->household_id)->first();

        if($request->household_id) {
            $h2oUser->household_id = $request->household_id;
        }

        if($gridUser != null && $request->household_id) {
            $gridUser->household_id = $request->household_id;
        }
            
        if($request->h2o_status_id) {
            $h2oUser->h2o_status_id = $request->h2o_status_id;
        }
        if($request->bsf_status_id) {
            $h2oUser->bsf_status_id = $request->bsf_status_id;
        }
        $h2oUser->number_of_bsf = $request->number_of_bsf;
        $h2oUser->number_of_h20 = $request->number_of_h20; 
        $h2oUser->installation_year = $request->installation_year;
        $h2oUser->h2o_request_date = $request->h2o_request_date; 
        $h2oUser->h2o_installation_date = $request->h2o_installation_date;
        $h2oUser->save();

        if($gridUser == null) {
            $gridUser = new GridUser();
            $gridUser->community_id = $h2oUser->community_id;
            $gridUser->household_id = $h2oUser->household_id;
        }
        if($request->request_date) {
            $gridUser->request_date = $request->request_date;
        }
        if($request->grid_integration_large) $gridUser->grid_integration_large = $request->grid_integration_large;
        if($request->large_date) $gridUser->large_date = $request->large_date;
        if($request->grid_integration_small) $gridUser->grid_integration_small = $request->grid_integration_small;
        if($request->small_date) $gridUser->small_date = $request->small_date;

        if($request->is_delivery) {
            $gridUser->is_delivery = $request->is_delivery;
        }
        if($request->is_paid) {
            $gridUser->is_paid = $request->is_paid;
        }
        if($request->is_complete) {
            $gridUser->is_complete = $request->is_complete;
        }

        $gridUser->save();

        return redirect('/all-water')->with('message', 'User Updated Successfully!');
    }
}