<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use DB;
use Route;
use App\Models\AllEnergyMeter;
use App\Models\Donor;
use App\Models\Community;
use App\Models\CommunityHousehold;
use App\Models\Household;
use App\Models\HouseholdMeter;
use App\Models\HouseholdStatus;
use App\Models\DisplacedHousehold;
use App\Models\EnergySystemType;
use App\Models\SubRegion;
use App\Exports\DisplacedHouseholdExport;
use Carbon\Carbon;
use DataTables;
use Excel;
use Illuminate\Support\Facades\URL;

class DisplacedHouseholdController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {	
        // $displacedHouseholds = DisplacedHousehold::get();

        // foreach($displacedHouseholds as $displacedHousehold) {

        //     $household = Household::where("english_name", $displacedHousehold->household_name)->first();
        //     $displacedHousehold->household_id = $household->id;
        //     $displacedHousehold->save();
            
        // }

        if (Auth::guard('user')->user() != null) {

            $communities = Community::where('is_archived', 0)
                ->orderBy('english_name', 'ASC')
                ->get();

            if ($request->ajax()) {
                
                $data = DB::table('displaced_households')
                    ->join('communities as old_communities', 'displaced_households.old_community_id', 
                        'old_communities.id')
                    ->leftJoin('communities as new_communities', 'displaced_households.new_community_id', 
                        'new_communities.id')
                    ->leftJoin('sub_regions', 'displaced_households.sub_region_id', 'sub_regions.id')
                    ->join('households', 'displaced_households.household_id', 'households.id')
                    ->where('displaced_households.is_archived', 0)
                    ->select('households.english_name as english_name',
                        'displaced_households.id as id', 'displaced_households.created_at as created_at', 
                        'displaced_households.updated_at as updated_at',
                        'old_communities.english_name as old_community',
                        'new_communities.english_name as new_community',
                        'sub_regions.english_name as region'
                    )
                    ->latest();   
 
                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
                        $detailsButton = "<a type='button' class='viewDisplacedHouseholdButton' data-id='".$row->id."'><i class='fa-solid fa-eye text-primary'></i></a>";
                        $updateButton = "<a type='button' class='updateHousehold' data-id='".$row->id."'><i class='fa-solid fa-pen-to-square text-success'></i></a>";
                        $deleteButton = "<a type='button' class='deleteDisplacedHousehold' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";
                        
                        if(Auth::guard('user')->user()->user_type_id != 7 || 
                            Auth::guard('user')->user()->user_type_id != 11 || 
                            Auth::guard('user')->user()->user_type_id != 8) 
                        {
                                
                            return $detailsButton." ". $updateButton." ".$deleteButton;
                        } else return $detailsButton; 

                    })
                    ->filter(function ($instance) use ($request) {
                        if (!empty($request->get('search'))) {
                                $instance->where(function($w) use($request) {
                                    $search = $request->get('search');
                                    $w->orWhere('households.english_name', 'LIKE', "%$search%")
                                    ->orWhere('old_communities.english_name', 'LIKE', "%$search%")
                                    ->orWhere('old_communities.arabic_name', 'LIKE', "%$search%")
                                    ->orWhere('new_communities.english_name', 'LIKE', "%$search%")
                                    ->orWhere('new_communities.arabic_name', 'LIKE', "%$search%")
                                    ->orWhere('sub_regions.arabic_name', 'LIKE', "%$search%")
                                    ->orWhere('sub_regions.english_name', 'LIKE', "%$search%")
                                    ->orWhere('households.arabic_name', 'LIKE', "%$search%");
                            });
                        }
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }

            $energySystemTypes = EnergySystemType::where('is_archived', 0)->get();
            $donors = Donor::where('is_archived', 0)->get();
            $householdStatuses = HouseholdStatus::where('is_archived', 0)->get();
            $subRegions = SubRegion::where('is_archived', 0)->get();

            return view('employee.household.displaced.index', compact('communities', 
                'energySystemTypes', 'subRegions'));

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
       // dd($request->households[0]);

        if($request->households) {

            if($request->households[0] == "all") {

                $households = Household::where("community_id", $request->old_community_id)
                    ->where("is_archived", 0)
                    ->get();
    
                foreach($households as $household) {
    
                    $displacedHousehold = new DisplacedHousehold();
                    $displacedHousehold->household_id = $household->id;

                    $energyUser = AllEnergyMeter::where("is_archived", 0)
                        ->where("household_id", $household->id)
                        ->first();
                    if($energyUser) {

                        $displacedHousehold->old_meter_number = $energyUser->meter_number; 
                    }

                    // $sharedHousehold =  HouseholdMeter::where("household_id", $household->id)->first();
                    // if($sharedHousehold) {

                    // }

                    $displacedHousehold->old_community_id = $request->old_community_id;
                    $displacedHousehold->old_energy_system_id = $request->old_energy_system_id;
                    $displacedHousehold->new_community_id = $request->new_community_id;
                    $displacedHousehold->area = $request->area;
                    $displacedHousehold->sub_region_id = $request->sub_region_id;
                    $displacedHousehold->displacement_date = $request->displacement_date;
                    $displacedHousehold->system_retrieved = $request->system_retrieved;
                    $displacedHousehold->notes = $request->notes;
                    $displacedHousehold->save();
                }
            } else {

                for($i=0; $i < count($request->households); $i++) {

                    $displacedHousehold = new DisplacedHousehold();
                    $displacedHousehold->household_id = $request->households[$i];
                    $energyUser = AllEnergyMeter::where("is_archived", 0)
                        ->where("household_id", $request->households[$i])
                        ->first();
                    if($energyUser) {

                        $displacedHousehold->old_meter_number = $energyUser->meter_number; 
                    }
                    $displacedHousehold->old_community_id = $request->old_community_id;
                    $displacedHousehold->old_energy_system_id = $request->old_energy_system_id;
                    $displacedHousehold->new_community_id = $request->new_community_id;
                    $displacedHousehold->area = $request->area;
                    $displacedHousehold->sub_region_id = $request->sub_region_id;
                    $displacedHousehold->displacement_date = $request->displacement_date;
                    $displacedHousehold->system_retrieved = $request->system_retrieved;
                    $displacedHousehold->notes = $request->notes;
                    $displacedHousehold->save();
                }
            }
        }

        return redirect()->back()->with('message', 'New Displaced Households Inserted Successfully!');
    }

    /**
     * Show the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $sharedHouseholds = [];
        $displacedHousehold = DisplacedHousehold::findOrFail($id);
        $energyUser = AllEnergyMeter::where("household_id", $displacedHousehold->household_id)->first();
        if($energyUser) {

            $sharedHouseholds = DB::table("household_meters")
            ->join("households", "household_meters.household_id", "households.id")
            ->where("household_meters.energy_user_id", $energyUser->id)
            ->select("households.english_name")
            ->get();
        }
        
        return view('employee.household.displaced.show', compact('displacedHousehold', 
            'sharedHouseholds'));
    }

    /**
     * Get households by community_id.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getHouseholdByCommunity(Request $request)
    {
        $households = Household::where('community_id', $request->community_id)
            ->where('is_archived', 0)
            ->orderBy('english_name', 'ASC')
            ->get();

        if (!$request->community_id) {

            $html = '<option disabled selected>Choose One...</option>';
        } else {

            $html = '<option selected disabled>Choose One...</option><option class="text-success" value="all">All Households</option>';
            $households = Household::where('community_id', $request->community_id)
                ->where('is_archived', 0)
                ->orderBy('english_name', 'ASC')
                ->get();

            foreach ($households as $household) {
                $html .= '<option value="'.$household->id.'">'.$household->english_name.'</option>';
            }
        }

        return response()->json(['html' => $html]);
    }

    /**
     * Get system by community_id.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getSystemsByCommunity(Request $request)
    {
        if (!$request->community_id) {

            $html = '<option value="">Choose One...</option>';
        } else {

            $html = '<option selected>Choose One...</option>';
            $systems = DB::table("all_energy_meters")
                ->join("energy_systems", "all_energy_meters.energy_system_id", "energy_systems.id")
                ->where("all_energy_meters.community_id", $request->community_id)
                ->select("energy_systems.id", "energy_systems.name")
                ->distinct()
                ->get();
  
            foreach ($systems as $system) {
                $html .= '<option value="'.$system->id.'">'.$system->name.'</option>';
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
    public function deleteDisplacedHousehold(Request $request)
    {
        $id = $request->id;

        $displacedHousehold = DisplacedHousehold::find($id);

        if($displacedHousehold) {

            $displacedHousehold->is_archived = 1;
            $displacedHousehold->save();

            $response['success'] = 1;
            $response['msg'] = 'Displaced Household Deleted successfully'; 
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

        return Excel::download(new DisplacedHouseholdExport($request), 'displaced_families.xlsx'); 
    }
}
