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
use Intervention\Image\Facades\Image;
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
                    ->leftJoin('households', 'workshop_communities.household_id', 'households.id')
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
                        DB::raw('group_concat(DISTINCT co_trainers.name) as co_trainer'),
                        DB::raw("IF(households.id IS NOT NULL, 'yes', 'no') as is_household"),
                        'households.english_name as household'
                    )
                    ->groupBy('workshop_communities.id')
                    ->distinct()
                    ->latest();

                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
    
                        $viewButton = "<a type='button' class='viewAllWorkshops' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#viewAllWorkshopModal' ><i class='fa-solid fa-eye text-info'></i></a>";
                        $updateButton = "<a type='button' class='updateAllWorkshops' data-id='".$row->id."' ><i class='fa-solid fa-pen-to-square text-success'></i></a>";

                        return $viewButton." ". $updateButton;
                    })
                    ->filter(function ($instance) use ($request) {
                        if (!empty($request->get('search'))) {
                                $instance->where(function($w) use($request){
                                $search = $request->get('search');
                                $w->orWhere('communities.english_name', 'LIKE', "%$search%")
                                ->orWhere('communities.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('workshop_types.english_name', 'LIKE', "%$search%")
                                ->orWhere('workshop_types.arabic_name', 'LIKE', "%$search%");
                              //  ->orWhere('lead.name', 'LIKE', "%$search%")
                               // ->orWhere('lead.co_trainer', 'LIKE', "%$search%");
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
        $household = null;

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
        
        $workshopCommunityPhotos = WorkshopCommunityPhoto::where('workshop_community_id', $id)->get();
        if($allWorkshop->household_id) $household = Household::findOrFail($allWorkshop->household_id);

        $response['allWorkshop'] = $allWorkshop;
        $response['community'] = $community;
        $response['workshopType'] = $workshopType;
        $response['leadBy'] = $leadBy;
        $response['coTrainers'] = $coTrainers;
        $response['workshopCommunityPhotos'] = $workshopCommunityPhotos;
        $response['household'] = $household;

        return response()->json($response);
    }

     /**
     * View Edit page.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) 
    {
        $workshopCommunity = WorkshopCommunity::findOrFail($id);

        $workshopType = WorkshopType::findOrFail($workshopCommunity->workshop_type_id);
 
        $workshopCommunityCoTrainers = DB::table('workshop_community_co_trainers')
            ->join('workshop_communities', 'workshop_community_co_trainers.workshop_community_id', 'workshop_communities.id')
            ->join('users as co_trainers', 'workshop_community_co_trainers.user_id', 'co_trainers.id')
            ->where('workshop_community_co_trainers.workshop_community_id', $id)
            ->select(
                'co_trainers.name', 'co_trainers.id as co_trainer_id', 
                'workshop_community_co_trainers.id as id'
            )->get();

        $workshopCommunityPhotos = WorkshopCommunityPhoto::where('workshop_community_id', $id)
            ->get();

        $users = User::where("is_archived", 0)->get();

        $coTrainers = User::where("is_archived", 0)->get();

        return view('workshop.edit', compact('workshopCommunity', 'workshopType', 'users',
            'workshopCommunityPhotos', 'workshopCommunityCoTrainers', 'coTrainers'));
    }

    /**
     * Update an existing resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request, int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $workshopCommunity = WorkshopCommunity::findOrFail($id);

        if($request->number_of_hours) $workshopCommunity->number_of_hours = $request->number_of_hours;
        if($request->number_of_male) $workshopCommunity->number_of_male = $request->number_of_male;
        if($request->number_of_female) $workshopCommunity->number_of_female = $request->number_of_female;
        if($request->number_of_youth) $workshopCommunity->number_of_youth = $request->number_of_youth;
        if($request->lead_by) $workshopCommunity->lead_by = $request->lead_by;
        if($request->notes) $workshopCommunity->notes = $request->notes;
        if($request->stories) $workshopCommunity->stories = $request->stories;
        $workshopCommunity->save();

        if($request->new_co_trainers) {
 
            for($i=0; $i < count($request->new_co_trainers); $i++) {

                $workshopCoTrainer = new WorkshopCommunityCoTrainer();
                $workshopCoTrainer->user_id = $request->new_co_trainers[$i];
                $workshopCoTrainer->workshop_community_id = $workshopCommunity->id;
                $workshopCoTrainer->save();
            }
        }

        if($request->more_co_trainers) {

            for($i=0; $i < count($request->more_co_trainers); $i++) {

                $workshopCoTrainer = new WorkshopCommunityCoTrainer();
                $workshopCoTrainer->user_id = $request->more_co_trainers[$i];
                $workshopCoTrainer->workshop_community_id = $workshopCommunity->id;
                $workshopCoTrainer->save();
            }
        }

        if ($request->file('new_photos')) {

            foreach($request->new_photos as $photo) {

                $original_name = $photo->getClientOriginalName();
                $extra_name  = uniqid().'_'.time().'_'.uniqid(). '.'. $photo->extension();
                $encoded_base64_image = substr($photo, strpos($photo, ',') + 1); 
                $destinationPath = public_path().'/workshops' ;
                $photo->move($destinationPath, $extra_name);
    
                $workshopPhoto = new WorkshopCommunityPhoto();
                $workshopPhoto->name = $extra_name;
                $workshopPhoto->workshop_community_id = $id;
                $workshopPhoto->save();
            }
        }

        if ($request->file('more_photos')) {

            foreach($request->more_photos as $photo) {

                $original_name = $photo->getClientOriginalName();
                $extra_name  = uniqid().'_'.time().'_'.uniqid(). '.'. $photo->extension();
                $encoded_base64_image = substr($photo, strpos($photo, ',') + 1); 
                $destinationPath = public_path().'/workshops' ;
                $photo->move($destinationPath, $extra_name);
    
                $workshopPhoto = new WorkshopCommunityPhoto();
                $workshopPhoto->name = $extra_name;
                $workshopPhoto->workshop_community_id = $id;
                $workshopPhoto->save();
            }
        }

        return redirect('/all-workshop')->with('message', 'Workshop Data Updated Successfully!');
    }

    /**
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteWorkshopPhoto(Request $request)
    {
        $id = $request->id;

        $workshopPhoto = WorkshopCommunityPhoto::find($id);

        if($workshopPhoto) {

            $workshopPhoto->delete();
            
            $response['success'] = 1;
            $response['msg'] = 'Photo Deleted successfully'; 
        } else {

            $response['success'] = 0;
            $response['msg'] = 'Invalid ID.';
        }

        return response()->json($response); 
    }

    /**
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteWorkshopCommunityCoTrainer(Request $request)
    {
        $id = $request->id;

        $workshopCoTrainer = WorkshopCommunityCoTrainer::find($id);

        if($workshopCoTrainer) {

            $workshopCoTrainer->delete();
            
            $response['success'] = 1;
            $response['msg'] = 'Co-Trainer Deleted successfully'; 
        } else {

            $response['success'] = 0;
            $response['msg'] = 'Invalid ID.';
        }

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
        Excel::import(new ImportWorkshops, $request->file('excel_file')); 
            
        return back()->with('success', 'Excel Data Imported successfully.');
    }
}