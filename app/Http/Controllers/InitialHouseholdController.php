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
use App\Models\Cistern;
use App\Models\Household;
use App\Models\HouseholdStatus;
use App\Models\Region;
use App\Models\Structure;
use App\Models\SubRegion;
use App\Models\Profession;
use Carbon\Carbon;
use DataTables;
use mikehaertl\wkhtmlto\Pdf;

class InitialHouseholdController extends Controller
{
   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {	
        if ($request->ajax()) {
            
            $data = DB::table('households')
                ->where('households.household_status_id', 1)
                ->join('communities', 'households.community_id', '=', 'communities.id')
                ->join('regions', 'communities.region_id', '=', 'regions.id')
                ->select('households.english_name as english_name', 'households.arabic_name as arabic_name',
                    'households.id as id', 'households.created_at as created_at', 
                    'households.updated_at as updated_at',
                    'regions.english_name as region_name',
                    'communities.english_name as name',
                    'communities.arabic_name as aname',)
                ->latest(); 

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row) {

                    $acButton = "<a title='From Initial to AC Survey' type='button' class='initialToAcHousehold' data-id='".$row->id."'><i class='fa-solid fa-check-square text-info'></i></a>";
                    $updateButton = "<a type='button' class='updateHousehold' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#updateHouseholdModal' ><i class='fa-solid fa-pen-to-square text-success'></i></a>";
                    $deleteButton = "<a type='button' class='deleteHousehold' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";
                    
                    return $acButton. " ".$updateButton." ".$deleteButton ;
   
                })
               
                ->filter(function ($instance) use ($request) {
                    if (!empty($request->get('search'))) {
                            $instance->where(function($w) use($request) {
                                $search = $request->get('search');
                                $w->orWhere('households.english_name', 'LIKE', "%$search%")
                                ->orWhere('communities.english_name', 'LIKE', "%$search%")
                                ->orWhere('communities.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('households.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('regions.english_name', 'LIKE', "%$search%")
                                ->orWhere('regions.arabic_name', 'LIKE', "%$search%");
                        });
                    }
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $dataHouseholdsByCommunity = DB::table('households')
            ->where('households.household_status_id', 1)
            ->join('communities', 'households.community_id', '=', 'communities.id')
            ->select(
                    DB::raw('communities.english_name as english_name'),
                    DB::raw('count(*) as number'))
            ->groupBy('communities.english_name')
            ->get();
        $arrayHouseholdsByCommunity[] = ['Community Name', 'Total'];
        
        foreach($dataHouseholdsByCommunity as $key => $value) {

            $arrayHouseholdsByCommunity[++$key] = [$value->english_name, $value->number];
        }

		return view('employee.household.initial')
            ->with('communityHouseholdsData', json_encode($arrayHouseholdsByCommunity));
    }

     /**
     * Change resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function initialToAcSurveyHousehold(Request $request)
    {
        $id = $request->id;

        $household = Household::find($id);
        $household->household_status_id = 2;
        $household->save();
        $response['success'] = 1;
        $response['msg'] = 'Household updated successfully'; 
      
        return response()->json($response); 
    }
}
