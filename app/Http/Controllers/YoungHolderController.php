<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\User;
use App\Models\YoungHolder;
use App\Models\Household;
use App\Models\AllEnergyMeter;
use App\Models\Community;
use App\Exports\WaterActionExport;
use App\Helpers\SequenceHelper;
use Auth;
use DB;
use Route;
use DataTables;
use Excel;

class YoungHolderController extends Controller
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

                $data = DB::table('young_holders')
                    ->join('households', 'households.id', 'young_holders.household_id')
                    ->join('communities', 'communities.id', 'households.community_id')
                    ->join('all_energy_meters', 'all_energy_meters.id', 'young_holders.all_energy_meter_id')
                    ->leftJoin('households as main_users', 'main_users.id', 'all_energy_meters.household_id')
                    ->select(
                        'young_holders.id as id', 'young_holders.fake_meter_number', 
                        DB::raw('IFNULL(households.english_name, households.arabic_name) 
                            as young_holder'),
                        'main_users.english_name as main_user', 
                        'all_energy_meters.meter_number', 'communities.english_name as community',
                        'young_holders.created_at as created_at',
                        'young_holders.updated_at as updated_at')
                    ->latest();

                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {

                        $deleteButton = "<a type='button' class='deleteYoungHolderHousehold' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";
                        $viewButton = "<a type='button' class='viewYoungHolder' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#viewYoungHolderModal' ><i class='fa-solid fa-eye text-info'></i></a>";

                        if(Auth::guard('user')->user()->user_type_id == 1 || 
                            Auth::guard('user')->user()->user_type_id == 10) 
                        {
                                
                            return $deleteButton ;
                        } else return "";
                    })
                    ->filter(function ($instance) use ($request) {
                        if (!empty($request->get('search'))) {
                                $instance->where(function($w) use($request){
                                $search = $request->get('search');
                                $w->orWhere('households.english_name', 'LIKE', "%$search%")
                                ->orWhere('main_users.english_name', 'LIKE', "%$search%")
                                ->orWhere('communities.english_name', 'LIKE', "%$search%")
                                ->orWhere('all_energy_meters.meter_number', 'LIKE', "%$search%")
                                ->orWhere('young_holders.fake_meter_number', 'LIKE', "%$search%");
                            });
                        }
                    })
                ->rawColumns(['action'])
                ->make(true);
            }

            $communities = Community::where('is_archived', 0)
                ->orderBy('english_name', 'ASC')
                ->get();

            return view('young.index', compact('communities'));
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
        $youngHolder = new YoungHolder();

        $youngHolder->household_id = $request->household_id;
        $youngHolder->all_energy_meter_id = $request->all_energy_meter_id;

        // Adding the fake meter number
        $allEnergyMeter = AllEnergyMeter::findOrFail($request->all_energy_meter_id);
        $meterNumber = $allEnergyMeter->meter_number;

        $recordYoung = YoungHolder::where("is_archived", 0)->count();

        if($recordYoung == 0) {

            $fakeMeterNumber = SequenceHelper::generateSequenceYoung($meterNumber, 1);
            $youngHolder->fake_meter_number = $fakeMeterNumber;
        } else {

            $lastRecord = YoungHolder::latest()->first();

            if($lastRecord->fake_meter_number) {

                $fakeMeterNumber = $lastRecord->fake_meter_number;
                $yPosition = strpos($fakeMeterNumber, 'y');
                $afterY = substr($fakeMeterNumber, $yPosition + 1);
                $afterY = $afterY +1; 
                $newFakeMeterNumber = SequenceHelper::generateSequenceYoung($meterNumber, $afterY);
                $youngHolder->fake_meter_number = $newFakeMeterNumber;
            }
        }
        
        $youngHolder->save();
  
        return redirect()->back()->with('message', 'New Young Holder Linked Successfully!');
    }

    /**
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteyoungHolder(Request $request)
    {
        $id = $request->id;

        $youngHolder = YoungHolder::find($id);

        if($youngHolder) {

            $youngHolder->delete(); 

            $response['success'] = 1;
            $response['msg'] = 'Young Holder Deleted successfully'; 
        } else {

            $response['success'] = 0;
            $response['msg'] = 'Invalid ID.';
        }

        return response()->json($response); 
    }

    /**
     * Get details by community_id.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getYoungAndMainDetailsByCommunity(int $id)
    {
        if (!$id) {

            $young = '<option selected disabled>Choose One...</option>';
            $mainUsers = '<option selected disabled>Choose One...</option>';
        } else {

            $young = '<option selected disabled>Choose One...</option>';
            $mainUsers = '<option selected disabled>Choose One...</option>';

            $households = DB::table('households')
                ->where('households.community_id', $id)
                ->where('households.is_archived', 0)
                ->where('households.internet_holder_young', 1)
                ->select(
                    'households.id', 
                    DB::raw('IFNULL(households.english_name, households.arabic_name) 
                        as household_name')
                    )
                ->get();

            $allEnergyMeters = DB::table('all_energy_meters')
                ->join('households', 'all_energy_meters.household_id', 'households.id')
                ->where('all_energy_meters.community_id', $id)
                ->where('all_energy_meters.is_archived', 0)
                ->where('all_energy_meters.meter_number', '!=', null)
                ->select('all_energy_meters.id as id', 'households.english_name')
                ->get();

            foreach ($households as $household) {

                $young .= '<option value="'.$household->id.'">'.$household->household_name.'</option>';
            }

            foreach ($allEnergyMeters as $allEnergyMeter) {

                $mainUsers .= '<option value="'.$allEnergyMeter->id.'">'.$allEnergyMeter->english_name.'</option>';
            }
        }

        return response()->json([
            'young' => $young,
            'mainUsers' => $mainUsers
        ]);
    }

     /**
     * Get details by community_id.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getMeterNumberByMain(int $id)
    {
        $meterNumber = DB::table('all_energy_meters')
            ->where('all_energy_meters.id', $id)
            ->where('all_energy_meters.is_archived', 0)
            ->select('all_energy_meters.meter_number')
            ->first();

        return response()->json(['meterNumber' => $meterNumber]);
    }
}