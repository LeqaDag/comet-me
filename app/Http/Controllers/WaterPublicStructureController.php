<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\AllWaterHolder;
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
use App\Models\H2oSharedPublicStructure;
use App\Models\H2oUserDonor;
use App\Models\Household;
use App\Models\PublicStructure;
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

                $data =  DB::table('h2o_shared_public_structures')
                    ->join('h2o_public_structures', 'h2o_shared_public_structures.h2o_public_structure_id', 
                        'h2o_public_structures.id')
                    ->join('public_structures', 'h2o_public_structures.public_structure_id', 
                        'public_structures.id')
                    ->join('communities', 'h2o_public_structures.community_id', 'communities.id')
                    ->select('h2o_shared_public_structures.id as id', 'public_structures.english_name', 
                        'communities.english_name as community_name', 
                        'h2o_shared_public_structures.public_structure_name as shared',
                        'h2o_shared_public_structures.created_at as created_at', 
                        'public_structures.arabic_name',
                        'h2o_shared_public_structures.updated_at as updated_at', )
                    ->latest();

                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
                        $empty = "";
                        //$updateButton = "<a type='button' class='updateWaterUser' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#updateWaterUserModal' ><i class='fa-solid fa-pen-to-square text-success'></i></a>";
                        $deleteButton = "<a type='button' class='deleteSharedPublic' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";
                        //$viewButton = "<a type='button' class='viewWaterUser' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#viewWaterUserModal' ><i class='fa-solid fa-eye text-info'></i></a>";
                        if(Auth::guard('user')->user()->user_type_id == 1 || 
                            Auth::guard('user')->user()->user_type_id == 2 ||
                            Auth::guard('user')->user()->user_type_id == 5 ||
                            Auth::guard('user')->user()->user_type_id == 11) 
                        {
                                
                            return $deleteButton;
                        } else return $empty;
                    }) 
                   
                    ->filter(function ($instance) use ($request) {
                        if (!empty($request->get('search'))) {
                                $instance->where(function($w) use($request){
                                $search = $request->get('search');
                                $w->orWhere('communities.english_name', 'LIKE', "%$search%")
                                ->orWhere('communities.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('public_structures.english_name', 'LIKE', "%$search%")
                                ->orWhere('public_structures.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('h2o_shared_public_structures.public_structure_name', 'LIKE', "%$search%");
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
        $publicStructure = PublicStructure::findOrFail($request->public_structure_id);

        $h2oSharedPublicStructure = new H2oSharedPublicStructure();
        $h2oSharedPublicStructure->public_structure_name = $publicStructure->english_name;
        $h2oSharedPublicStructure->h2o_public_structure_id = $request->h2o_public_structure_id;
        $h2oSharedPublicStructure->public_structure_id = $request->public_structure_id;
        $h2oSharedPublicStructure->save();

        $allWaterHolder = new AllWaterHolder();
        $allWaterHolder->is_main = "No";
        $allWaterHolder->public_structure_id = $request->public_structure_id;
        $allWaterHolder->community_id = $request->community_id[0];
        $allWaterHolder->water_system_id = 1;
        $allWaterHolder->save();


        return redirect()->back()->with('message', 'New Shared Public Structure Added Successfully!');
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

    /**
     * Get households by community_id.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getH2oPublicByCommunity(Request $request)
    {
        $h2oPublics = DB::table('h2o_public_structures')
            ->join('communities', 'h2o_public_structures.community_id', 'communities.id')
            ->join('public_structures', 'h2o_public_structures.public_structure_id', 'public_structures.id')
            ->where('h2o_public_structures.community_id', $request->community_id)
            ->select('h2o_public_structures.id as id', 'public_structures.english_name')
            ->get();

        if (!$request->community_id) {

            $html = '<option value="">Choose One...</option>';
        } else {

            $html = '<option selected>Choose One...</option>';
            $h2oPublics = DB::table('h2o_public_structures')
                ->join('communities', 'h2o_public_structures.community_id', 'communities.id')
                ->join('public_structures', 'h2o_public_structures.public_structure_id', 'public_structures.id')
                ->where('h2o_public_structures.community_id', $request->community_id)
                ->select('h2o_public_structures.id as id', 'public_structures.english_name')
                ->get();
            foreach ($h2oPublics as $h2oPublic) {
                $html .= '<option value="'.$h2oPublic->id.'">'.$h2oPublic->english_name.'</option>';
            }
        }

        return response()->json(['html' => $html]);
    }

    /**
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteSharedPublic(Request $request)
    {
        $id = $request->id;
        $h2oSharedPublic = H2oSharedPublicStructure::findOrFail($id);
        $allWaterHolder = AllWaterHolder::where("public_structure_id", $h2oSharedPublic->public_structure_id)->first();
  
        if($h2oSharedPublic->delete()) {

            if($allWaterHolder) $allWaterHolder->delete();

            $response['success'] = 1;
            $response['msg'] = 'Shared Public Deleted successfully'; 
        } else {

            $response['success'] = 0;
            $response['msg'] = 'Invalid ID.';
        }

        return response()->json($response); 
    }
}
