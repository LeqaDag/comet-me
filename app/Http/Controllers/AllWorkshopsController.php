<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\User;
use App\Models\Community;
use App\Models\Household;
use App\Models\WorkshopType;
use App\Models\WorkshopCommunity;
use App\Models\WorkshopCommunityCoTrainer;
use App\Models\WorkshopCommunityPhoto;
use App\Exports\AllWorkshopsExport;
use App\Imports\ImportWorkshops;
use Auth;
use DB;
use Route;
use DataTables;
use Excel;

class AllWorkshopsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {	
        $communityFilter = $request->input('community_filter');
        $typeFilter = $request->input('type_filter');
        $dateFilter = $request->input('date_filter');

        if (Auth::guard('user')->user() != null) {
  
            if ($request->ajax()) {
                
                $data = DB::table('workshop_communities')
                    ->join('communities', 'workshop_communities.community_id', 'communities.id')
                    ->leftJoin('compounds', 'workshop_communities.compound_id', 'compounds.id')
                    ->join('workshop_types', 'workshop_communities.workshop_type_id', 'workshop_types.id')
                    ->join('users as lead', 'workshop_communities.lead_by', 'lead.id')
                    ->leftJoin('workshop_community_co_trainers', 'workshop_communities.id', 
                        'workshop_community_co_trainers.workshop_community_id')
                    ->leftJoin('users as co_trainers', 'workshop_community_co_trainers.user_id', 'co_trainers.id')
                    ->where('workshop_communities.is_archived', 0);

                    if($communityFilter != null) {

                        $data->where('communities.id', $communityFilter);
                    }
                    if($typeFilter != null) {

                        $data->where('workshop_types.id', $typeFilter);
                    }
                    if($dateFilter != null) {

                        $data->where('workshop_communities.date', '>=', $dateFilter);
                    }

                    $data->select(
                        'workshop_communities.id as id', 
                        'workshop_types.english_name as workshop_type',
                        'workshop_communities.date', 'workshop_communities.notes',
                        'communities.english_name as community_name',
                        'workshop_communities.created_at as created_at',
                        'workshop_communities.updated_at as updated_at',
                        'lead.name as lead_user_name', 'compounds.english_name as compound',
                        DB::raw('group_concat(DISTINCT co_trainers.name) as co_trainer')
                    )
                    ->groupBy('workshop_communities.id')
                    ->distinct()
                    ->latest();

                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
    
                        $viewButton = "<a type='button' class='viewAllWorkshops' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#viewAllWorkshopModal' ><i class='fa-solid fa-eye text-info'></i></a>";
    
                        return $viewButton;
                    })
                    ->filter(function ($instance) use ($request) {
                        if (!empty($request->get('search'))) {
                                $instance->where(function($w) use($request){
                                $search = $request->get('search');
                                $w->orWhere('communities.english_name', 'LIKE', "%$search%")
                                ->orWhere('communities.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('workshop_types.english_name', 'LIKE', "%$search%")
                                ->orWhere('workshop_types.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('lead.name', 'LIKE', "%$search%")
                                ->orWhere('lead.co_trainer', 'LIKE', "%$search%");
                            });
                        }
                    })
                ->rawColumns(['action'])
                ->make(true);
            }
     
            $communities = Community::where('is_archived', 0)
                ->orderBy('english_name', 'ASC')
                ->get();
            $workshopTypes = WorkshopType::where('is_archived', 0)->get();
            $users = User::where('is_archived', 0)->get();


            return view('workshop.index', compact('communities', 'workshopTypes', 'users'));
        } else {

            return view('errors.not-found');
        }
    }

    /**
     * Show the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $allWorkshop = WorkshopCommunity::findOrFail($id);
        
        $coTrainers = null;

        $community = Community::where("is_archived", 0)
            ->where('id', $allWorkshop->community_id)
            ->first();
        $workshopType = WorkshopType::where("is_archived", 0)
            ->where('id', $allWorkshop->workshop_type_id)
            ->first();
        $leadBy = User::where("is_archived", 0)
            ->where('id', $allWorkshop->lead_by)
            ->first();
        $coTrainers = DB::table('workshop_community_co_trainers')
            ->join('workshop_communities', 'workshop_community_co_trainers.workshop_community_id', 'workshop_communities.id')
            ->join('users as co_trainers', 'workshop_community_co_trainers.user_id', 'co_trainers.id')
            ->where('workshop_community_co_trainers.workshop_community_id', $id)
            ->select('co_trainers.name')
            ->get();
        
        WorkshopCommunityCoTrainer::where("is_archived")
            ->where('workshop_community_id', $id)
            ->get();

        $response['allWorkshop'] = $allWorkshop;
        $response['community'] = $community;
        $response['workshopType'] = $workshopType;
        $response['leadBy'] = $leadBy;
        $response['coTrainers'] = $coTrainers;

        return response()->json($response);
    }

    /**
     * 
     * @return \Illuminate\Support\Collection
     */
    public function export(Request $request) 
    {
                
        return Excel::download(new AllWorkshopsExport($request), 'all_workshops.xlsx');
    }

    /**
     * 
     * @return \Illuminate\Support\Collection
     */
    public function import(Request $request)
    {
        Excel::import(new ImportWorkshops, $request->file('file')); 
            
        return back()->with('success', 'Excel Data Imported successfully.');
    }
}