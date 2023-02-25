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

class WaterPublicStructureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {	
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
    }
}
