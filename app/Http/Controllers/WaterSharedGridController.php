<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\AllWaterHolder;
use App\Models\User;
use App\Models\BsfStatus;
use App\Models\Donor;
use App\Models\Community;
use App\Models\CommunityWaterSource;
use App\Models\GridUser;
use App\Models\GridUserDonor;
use App\Models\GridSharedUser;
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

class WaterSharedGridController extends Controller
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

                $data = DB::table('grid_shared_users')
                    ->join('households', 'grid_shared_users.household_id', 'households.id')
                    ->join('communities', 'households.community_id', 'communities.id')
                    ->where('grid_shared_users.is_archived', 0)
                    ->select('grid_shared_users.id as id', 'households.english_name', 
                        'communities.english_name as community_name', 
                        'grid_shared_users.grid_user_english_name', 
                        'grid_shared_users.grid_user_arabic_name',
                        'grid_shared_users.created_at as created_at',
                        'grid_shared_users.updated_at as updated_at', )
                    ->latest();

                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
                        $empty = "";
                       // $updateButton = "<a type='button' class='updateWaterUser' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#updateWaterUserModal' ><i class='fa-solid fa-pen-to-square text-success'></i></a>";
                        $deleteButton = "<a type='button' class='deleteGridUser' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";
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
                                ->orWhere('households.english_name', 'LIKE', "%$search%")
                                ->orWhere('households.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('grid_shared_users.grid_user_english_name', 'LIKE', "%$search%")
                                ->orWhere('grid_shared_users.grid_user_arabic_name', 'LIKE', "%$search%");
                            });
                        }
                    })
                ->rawColumns(['action'])
                ->make(true);
            }
    
            $communities = Community::where('is_archived', 0)
                ->orderBy('english_name', 'ASC')
                ->get();
            $households = Household::where('is_archived', 0)
                ->orderBy('english_name', 'ASC')
                ->get();
    
            return view('users.water.shared.grid.index', compact('communities', 'households'));
                
        } else {

            return view('errors.not-found');
        }
    }

     /**
     * Get households by community_id.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getGridUsersByCommunity(Request $request)
    {
        $gridUsers = DB::table('grid_users')
            ->join('communities', 'grid_users.community_id', 'communities.id')
            ->join('households', 'grid_users.household_id', 'households.id')
            ->where('grid_users.community_id', $request->community_id)
            ->where('grid_users.is_archived', 0)
            ->orderBy('households.english_name', 'ASC')
            ->select('grid_users.id as id', 'households.english_name')
            ->get();

        if (!$request->community_id) {

            $html = '<option value="">Choose One...</option>';
        } else {

            $html = '<option selected>Choose One...</option>';
            $gridUsers = DB::table('grid_users')
                ->join('communities', 'grid_users.community_id', 'communities.id')
                ->join('households', 'grid_users.household_id', 'households.id')
                ->where('grid_users.community_id', $request->community_id)
                ->where('grid_users.is_archived', 0)
                ->orderBy('households.english_name', 'ASC')
                ->select('grid_users.id as id', 'households.english_name')
                ->get();
            foreach ($gridUsers as $household) {
                $html .= '<option value="'.$household->id.'">'.$household->english_name.'</option>';
            }
        }

        return response()->json(['html' => $html]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $gridUser = GridUser::findOrFail($request->grid_user_id);
        $household = Household::findOrFail($gridUser->household_id);

        $sharedUser = new GridSharedUser();
        $sharedUser->household_id = $request->household_id;
        $sharedUser->grid_user_id = $request->grid_user_id;
        $sharedUser->grid_user_english_name = $household->english_name;
        $sharedUser->grid_user_arabic_name = $household->arabic_name;
        $sharedUser->save();

        $allWaterHolder =  new AllWaterHolder();
        $allWaterHolder->is_main = "No";
        $allWaterHolder->household_id = $request->household_id;
        $allWaterHolder->community_id = $request->community_id[0];
        $allWaterHolder->save();

        $subHousehold = Household::where('id', $request->household_id)->first();
        $subHousehold->water_service = "Yes";
        $subHousehold->save();

        return redirect()->back()->with('message', 'New Shared Grid User Added Successfully!');
    }

    /**
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteSharedGridUser(Request $request)
    {
        $id = $request->id;

        $sharedUser = GridSharedUser::find($id);
        $allWaterHolder = AllWaterHolder::where("household_id", $sharedUser->household_id)->first();

        if($sharedUser) {

            $sharedUser->is_archived = 1;
            $sharedUser->save();

            if($allWaterHolder) 
            {
                $allWaterHolder->is_archived = 1;
                $allWaterHolder->save();
            }
            
            $response['success'] = 1;
            $response['msg'] = 'Shared Grid User Deleted successfully'; 
        } else {

            $response['success'] = 0;
            $response['msg'] = 'Invalid ID.';
        }

        return response()->json($response); 
    }
}