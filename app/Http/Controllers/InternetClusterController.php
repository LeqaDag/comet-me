<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use DB;
use Route;
use App\Models\User;
use App\Models\Community;
use App\Models\InternetUser;
use App\Models\InternetUserDonor;
use App\Models\InternetSystemType;
use App\Models\InternetSystem;
use App\Models\InternetSystemCommunity;
use App\Models\Household;
use App\Models\InternetSystemCommunityType;
use App\Models\InternetCluster;
use App\Models\InternetClusterCommunity;
use Carbon\Carbon;
use Image;
use DataTables;

class InternetClusterController extends Controller
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
 
                $data = DB::table('internet_cluster_communities')
                    ->join('communities', 'internet_cluster_communities.community_id', 
                        '=', 'communities.id')
                    ->join('internet_clusters', 'internet_cluster_communities.internet_cluster_id', 
                        '=', 'internet_clusters.id')
                    ->leftJoin('internet_users', 'internet_cluster_communities.community_id', 
                        '=', 'internet_users.community_id')
                    ->select('internet_clusters.name', 
                        'internet_cluster_communities.id as id',
                        'internet_cluster_communities.created_at as created_at', 
                        'internet_cluster_communities.updated_at as updated_at', 
                        'communities.english_name as community_name',
                        DB::raw("count(internet_users.community_id) as count"))
                    ->groupBy('internet_cluster_communities.community_id')
                    ->latest(); 
    
                return Datatables::of($data)
                    ->addIndexColumn()
                    // ->addColumn('action', function($row) {
                    //     $viewButton = "<a type='button' class='viewInternetSystem' data-id='".$row->id."' ><i class='fa-solid fa-eye fa-lg text-info'></i></a>";
                    //     $updateButton = "<a type='button' class='updateInternetSystem' data-id='".$row->id."' ><i class='fa-solid fa-pen-to-square fa-lg text-success'></i></a>";
                    //     $deleteButton = "<a type='button' class='deleteInternetSystem' data-id='".$row->id."'><i class='fa-solid fa-trash fa-lg text-danger'></i></a>";
                        
                    //     if(Auth::guard('user')->user()->user_type_id == 1 || 
                    //         Auth::guard('user')->user()->user_type_id == 2 ||
                    //         Auth::guard('user')->user()->user_type_id == 6 ||
                    //         Auth::guard('user')->user()->user_type_id == 10) 
                    //     {
                                
                    //         return $viewButton." ". $updateButton." ".$deleteButton;
                    //     } else return $viewButton;
                        
                    // })
                    ->filter(function ($instance) use ($request) {
                        if (!empty($request->get('search'))) {
                                $instance->where(function($w) use($request) {
                                $search = $request->get('search');
                                $w->orWhere('communities.english_name', 'LIKE', "%$search%")
                                ->orWhere('internet_clusters.name', 'LIKE', "%$search%")
                                ->orWhere('communities.arabic_name', 'LIKE', "%$search%");
                            });
                        }
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }

            $internetClusters = InternetCluster::all();
            $communities = Community::where('is_archived', 0)
                ->orderBy('english_name', 'ASC')
                ->get();

            return view('system.internet.cluster.index', compact('communities', 'internetClusters'));
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
       
        return redirect('/internet-cluster')
            ->with('message', 'New Internet Cluster Added Successfully!');
    }

    /**
     * View Edit page.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function editPage($id)
    {
        $internetSystem = InternetSystem::findOrFail($id);

        return response()->json($internetSystem);
    }

    /**
     * View Edit page.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       
    }

    /**
     * Update an existing resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request, int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        return redirect('/internet-cluster')->with('message', 'Internet Cluster Updated Successfully!');
    }

    /**
     * Show the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function showPage($id)
    {
        $internetSystem = InternetSystem::findOrFail($id);

        return response()->json($internetSystem);
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
    public function deleteInternetCluster(Request $request)
    {
        $id = $request->id;

        $internetSystem = InternetSystem::find($id);

        if($internetSystem->delete()) {

       
            $response['success'] = 1;
            $response['msg'] = 'Internet System Deleted successfully'; 
        } else {

            $response['success'] = 0;
            $response['msg'] = 'Invalid ID.';
        }

        return response()->json($response); 
    }
}