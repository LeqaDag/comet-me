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
use App\Models\EnergyUser;
use App\Models\Household;
use App\Models\PublicStructure;
use App\Models\PublicStructureCategory;
use App\Models\RefrigeratorHolder;
use Carbon\Carbon;
use Image;
use DataTables;

class RefrigeratorHolderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $holders = RefrigeratorHolder::all();
 
        // foreach($holders as $holder) {
        //     // $community = Community::where('english_name', $holder->community_name)->first();

        //     // $holder->community_id = $community->id;
 
        //     $household = Household::where('english_name', $holder->household_name)->first();
        //     $holder->household_id = $household->id;
        //     $holder->save();
        // }

        if ($request->ajax()) {

            $data = DB::table('refrigerator_holders')
                ->join('communities', 'refrigerator_holders.community_id', '=', 'communities.id')
                ->leftJoin('households', 'refrigerator_holders.household_id', '=', 'households.id')
                ->leftJoin('public_structures', 'refrigerator_holders.public_structure_id', 
                    '=', 'public_structures.id')
                ->select('refrigerator_holders.refrigerator_type_id', 'refrigerator_holders.date',
                    'refrigerator_holders.id as id', 'refrigerator_holders.created_at as created_at', 
                    'refrigerator_holders.updated_at as updated_at', 
                    'communities.english_name as community_name',
                    'households.english_name as household_name',
                    'public_structures.english_name as public_name',
                    'refrigerator_holders.payment', 'refrigerator_holders.is_paid', 
                    'refrigerator_holders.receive_number', 'refrigerator_holders.status', 
                    'refrigerator_holders.year')
                ->latest(); 

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row) {

                    $viewButton = "<a type='button' class='viewRefrigeratorHolder' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#viewRefrigeratorHolderModal'><i class='fa-solid fa-eye text-info'></i></a>";
                    $deleteButton = "<a type='button' class='deleteRefrigeratorHolder' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";
                    
                    return $viewButton." ".$deleteButton;
                })
                ->filter(function ($instance) use ($request) {
                    if (!empty($request->get('search'))) {
                            $instance->where(function($w) use($request) {
                            $search = $request->get('search');
                            $w->orWhere('communities.english_name', 'LIKE', "%$search%")
                            ->orWhere('households.english_name', 'LIKE', "%$search%")
                            ->orWhere('communities.arabic_name', 'LIKE', "%$search%")
                            ->orWhere('households.arabic_name', 'LIKE', "%$search%")
                            ->orWhere('public_structures.english_name', 'LIKE', "%$search%")
                            ->orWhere('public_structures.arabic_name', 'LIKE', "%$search%")
                            ->orWhere('refrigerator_holders.receive_number', 'LIKE', "%$search%");
                        });
                    }
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $communities = Community::all();
        $households = Household::all();

        return view('users.refrigerator.index', compact('communities', 'households'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $refrigeratorHolder = new RefrigeratorHolder();
        if($request->is_household == "no") {

            $refrigeratorHolder->public_structure_id = $request->public_structure_id;
        } else {

            $refrigeratorHolder->household_id = $request->household_id;
        }
        $refrigeratorHolder->refrigerator_type_id = $request->refrigerator_type_id;
        $refrigeratorHolder->community_id = $request->community_id;
        $refrigeratorHolder->number_of_fridge = $request->number_of_fridge;
        $refrigeratorHolder->date = $request->date;
        $refrigeratorHolder->year = $request->year;
        $refrigeratorHolder->is_paid = $request->is_paid;
        $refrigeratorHolder->payment = $request->payment;
        $refrigeratorHolder->receive_number = $request->receive_number;
        $refrigeratorHolder->notes = $request->notes;
        $refrigeratorHolder->save();

        return redirect()->back()->with('message', 'New Refrigerator Holder Added Successfully!');
    }

    /**
     * Show the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $refrigeratorHolder = RefrigeratorHolder::findOrFail($id);
        $community = Community::where('id', $refrigeratorHolder->community_id)->first();
        $household = Household::where('id', $refrigeratorHolder->household_id)->first();
        $public = PublicStructure::where('id', $refrigeratorHolder->public_structure_id)->first();

        $response['refrigerator'] = $refrigeratorHolder;
        $response['community'] = $community;
        $response['household'] = $household;
        $response['public'] = $public;

        return response()->json($response);
    }

    /**
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteRefrigeratorHolder(Request $request)
    {
        $id = $request->id;

        $refrigeratorHolder = RefrigeratorHolder::find($id);

        if($refrigeratorHolder->delete()) {

            $response['success'] = 1;
            $response['msg'] = 'Refrigerator Holder Deleted successfully'; 
        } else {

            $response['success'] = 0;
            $response['msg'] = 'Invalid ID.';
        }

        return response()->json($response); 
    }
}