<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\User;
use App\Models\ActionCategory;
use App\Models\RefrigeratorAction;
use App\Models\RefrigeratorIssue;
use App\Exports\RefrigeratorActionExport;
use Auth;
use DB;
use Route;
use DataTables;
use Excel;

class RefrigeratorActionController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $refrigeratorActions = RefrigeratorAction::all();

        // foreach($refrigeratorActions as $refrigeratorAction) {

        //     $category = ActionCategory::where("english_name", $refrigeratorAction->category)->first();

        //     $refrigeratorAction->action_category_id = $category->id;
        //     $refrigeratorAction->save();
        // }

        // $incrementalNumber = 400; 
        // $refrigeratorActions = RefrigeratorAction::all();
        // foreach($refrigeratorActions as $refrigeratorAction) {

        //     $actionCategory = ActionCategory::findOrFail($refrigeratorAction->action_category_id);
        //     $refrigeratorAction->comet_id = $incrementalNumber;
        //     $refrigeratorAction->full_comet_id = $actionCategory->comet_id . $incrementalNumber;
        //     $refrigeratorAction->save();

        //     $incrementalNumber++;
        // }

        if (Auth::guard('user')->user() != null) {

            $categoryFilter = $request->input('category_filter');

            if ($request->ajax()) {
                $data = DB::table('refrigerator_actions')
                    ->join('action_categories', 'refrigerator_actions.action_category_id', 
                        'action_categories.id')
                    ->where('refrigerator_actions.is_archived', 0);

                if($categoryFilter != null) {

                    $data->where('action_categories.id', $categoryFilter);
                }

                $data->select('refrigerator_actions.id as id', 
                    'refrigerator_actions.english_name', 
                    'refrigerator_actions.arabic_name',
                    'action_categories.english_name as category',
                    'refrigerator_actions.created_at as created_at',
                    'refrigerator_actions.updated_at as updated_at')
                ->latest();

                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {

                        $updateButton = "<a type='button' class='updateRefrigeratorAction' data-id='".$row->id."'><i class='fa-solid fa-pen-to-square text-success'></i></a>";
                        $deleteButton = "<a type='button' class='deleteRefrigeratorAction' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";
                        $viewButton = "<a type='button' class='viewRefrigeratorAction' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#viewRefrigeratorActionModal' ><i class='fa-solid fa-eye text-info'></i></a>";

                        if(Auth::guard('user')->user()->user_type_id == 1 || 
                            Auth::guard('user')->user()->user_type_id == 2) 
                        {
                                
                            return $updateButton. " ". $deleteButton ;
                        } else return "";
                    })
                    ->filter(function ($instance) use ($request) {
                        if (!empty($request->get('search'))) {
                                $instance->where(function($w) use($request){
                                $search = $request->get('search');
                                $w->orWhere('refrigerator_actions.english_name', 'LIKE', "%$search%")
                                ->orWhere('refrigerator_actions.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('refrigerator_action_categories.english_name', 'LIKE', "%$search%")
                                ->orWhere('refrigerator_action_categories.arabic_name', 'LIKE', "%$search%");
                            });
                        }
                    })
                ->rawColumns(['action'])
                ->make(true);
            }

            $actionCategories = ActionCategory::where("is_archived", 0)->get();

            return view('users.refrigerator.maintenance.action.index', compact('actionCategories'));
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
        $refrigeratorAction = new RefrigeratorAction();
        // Get last comet_id
        $last_comet_id = RefrigeratorAction::latest('id')->value('comet_id') + 1;
        $refrigeratorAction->english_name = $request->english_name;
        $refrigeratorAction->arabic_name = $request->arabic_name;
        $refrigeratorAction->action_category_id = $request->action_category_id;
        $refrigeratorAction->comet_id = $last_comet_id;
        $refrigeratorAction->notes = $request->notes;
        $refrigeratorAction->save();
  
        return redirect()->back()
            ->with('message', 'New Action Added Successfully!');
    }

    /**
     * View Edit page.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) 
    {
        $refrigeratorAction = RefrigeratorAction::findOrFail($id);
        $energyCategories = ActionCategory::where("is_archived", 0)->get();

        return view('users.refrigerator.maintenance.action.edit', compact('energyCategories', 'refrigeratorAction'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(int $id, Request $request)
    {     
        $refrigeratorAction = RefrigeratorAction::findOrFail($id);

        if($request->english_name) $refrigeratorAction->english_name = $request->english_name;
        if($request->arabic_name) $refrigeratorAction->arabic_name = $request->arabic_name;
        if($request->action_category_id) $refrigeratorAction->action_category_id = $request->action_category_id;
        if($request->notes) $refrigeratorAction->notes = $request->notes;
        $refrigeratorAction->save();
  
        return redirect('/refrigerator-action')->with('message', 'Refrigerator Action Updated Successfully!');
    }

    /**
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteRefrigeratorMainAction(Request $request)
    {
        $id = $request->id;

        $refrigeratorAction = RefrigeratorAction::find($id);

        if($refrigeratorAction) {

            $refrigeratorAction->is_archived = 1;
            $refrigeratorAction->save(); 

            $response['success'] = 1;
            $response['msg'] = 'Refrigerator Action Deleted successfully'; 
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
                
        return Excel::download(new RefrigeratorActionExport($request), 'refrigerator_actions.xlsx');
    }

    /**
     * Get a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getEnergyAction(Request $request)
    {
        $id = $request->id;

        $energyAction = EnergyAction::find($id);

        return response()->json($energyAction); 
    }
}
