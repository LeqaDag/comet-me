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
use App\Models\CommunityDonor;
use App\Models\CommunityStatus;
use App\Models\Compound;
use App\Models\CompoundHousehold;
use App\Models\Donor;
use App\Models\EnergySystem;
use App\Models\EnergySystemType;
use App\Models\Household;
use App\Models\Settlement;
use App\Models\ServiceType;
use App\Models\PublicStructure;
use App\Models\PublicStructureCategory;
use App\Models\ProductType;
use App\Models\Region;
use App\Models\SubRegion;
use App\Exports\CompoundHouseholdExport; 
use Carbon\Carbon;
use Image;
use DataTables;
use Excel;

class CommunityCompoundController extends Controller
{ 
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {	
        if (Auth::guard('user')->user() != null) {

            $communityFilter = $request->input('filter');
            $regionFilter = $request->input('second_filter');
            $subRegionFilter = $request->input('third_filter');

            if ($request->ajax()) {

                $data = DB::table('compound_households')
                    ->join('communities', 'compound_households.community_id', 
                        'communities.id')
                    ->join('regions', 'communities.region_id', 'regions.id')
                    ->join('sub_regions', 'communities.sub_region_id', 'sub_regions.id')
                    ->join('households', 'compound_households.household_id', 
                        'households.id')
                    ->join('compounds', 'compound_households.compound_id', 
                        'compounds.id') 
                    ->where('compound_households.is_archived', 0);
    
                if($communityFilter != null) {

                    $data->where('communities.id', $communityFilter);
                }
                if ($regionFilter != null) {

                    $data->where('regions.id', $regionFilter);
                }
                if ($subRegionFilter != null) {

                    $data->where('sub_regions.id', $subRegionFilter);
                }

                $data->select(
                    'compounds.english_name as english_name', 
                    'compounds.arabic_name as arabic_name',
                    'communities.english_name as community_english_name', 
                    'communities.arabic_name as community_arabic_name',
                    'compound_households.id as id', 'compound_households.created_at as created_at', 
                    'compound_households.updated_at as updated_at',
                    'communities.number_of_people as number_of_people',
                    'communities.number_of_household as number_of_household',
                    'regions.english_name as name',
                    'regions.arabic_name as aname',
                    'households.english_name as household')
                ->latest(); 

                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {

                        $empty = "";
                      //  $updateButton = "<a type='button' class='updateCompoundCommunityHousehold' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#updateCompoundCommunityModal' ><i class='fa-solid fa-pen-to-square text-success'></i></a>";
                        $deleteButton = "<a type='button' class='deleteCompoundHousehold' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";
    
                        if(Auth::guard('user')->user()->user_type_id == 1 || 
                            Auth::guard('user')->user()->user_type_id == 2 ) 
                        {
                                
                            return $deleteButton;
                        } else return $empty; 
       
                    })
                    ->filter(function ($instance) use ($request) {
                        if (!empty($request->get('search'))) {
                                $instance->where(function($w) use($request) {
                                $search = $request->get('search');
                                $w->orWhere('communities.english_name', 'LIKE', "%$search%")
                                ->orWhere('communities.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('regions.english_name', 'LIKE', "%$search%")
                                ->orWhere('regions.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('compounds.english_name', 'LIKE', "%$search%")
                                ->orWhere('compounds.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('households.english_name', 'LIKE', "%$search%")
                                ->orWhere('households.arabic_name', 'LIKE', "%$search%");
                            });
                        }
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            
            $communities = Community::where('is_archived', 0)
                ->orderBy('english_name', 'ASC')
                ->get();
            $regions = Region::where('is_archived', 0)
                ->orderBy('english_name', 'ASC')
                ->get();
            $subregions = SubRegion::where('is_archived', 0)
                ->orderBy('english_name', 'ASC')
                ->get();
            $compounds = Compound::where('is_archived', 0)->get();
            $households =  Household::where('is_archived', 0)
                ->orderBy('english_name', 'ASC')
                ->get();
            $energySystemTypes = EnergySystemType::where('is_archived', 0)
                ->orderBy('name', 'ASC')
                ->get();

            return view('admin.community.compound.index', compact('communities', 'regions', 
                'compounds', 'households', 'energySystemTypes', 'subregions'));
        } else {

            return view('errors.not-found');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function deleteCompoundHousehold(Request $request)
    {
        $id = $request->id;

        $compoundCommunity = CompoundHousehold::findOrFail($id);
        $compoundCommunity->is_archived = 1;
        $compoundCommunity->save();

        $response['success'] = 1;
        $response['msg'] = 'Compound Household Deleted successfully'; 
        
        return response()->json($response); 
    }

    /**
     * 
     * @return \Illuminate\Support\Collection
     */
    public function export(Request $request) 
    {
                
        return Excel::download(new CompoundHouseholdExport($request), 
            'Compound Households.xlsx');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->household_id) {

            for($i=0; $i < count($request->household_id); $i++) {

                $compoundHousehold = new CompoundHousehold();
                $compoundHousehold->household_id = $request->household_id[$i];
                $compoundHousehold->compound_id = $request->compound_id;
                $compoundHousehold->community_id = $request->community_id;
                $compoundHousehold->energy_system_type_id = $request->energy_system_type_id;
                $compoundHousehold->save();

                $household = Household::findOrFail($request->household_id[$i]);
                if($request->energy_system_type_id) {
                    
                    $household->energy_system_type_id = $request->energy_system_type_id;
                    $household->save();
                }
            } 
       
            return redirect()->back()->with('message', 'New Compound Households Added Successfully!');
        } else {

            return redirect()->back()->with('error', 'You missed up selecting households!');
        }
            
    }

    /**
     * Get sub communities by community_id.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getByCommunity(Request $request)
    {
        if (!$request->community_id) {

            $htmlHouseholds = '<option value="">Choose One...</option>';
            $htmlCompounds = '<option value="">Choose One...</option>';
            $htmlEnergySystems = '<option value="">Choose One...</option>';
        } else { 

            $htmlCompounds = '<option value="">Choose One...</option>';
            $htmlHouseholds = '<option value="">Choose One...</option>';
            $htmlEnergySystems = '<option value="">Choose One...</option>';
            
            $households = Household::where('community_id', $request->community_id)
                ->where('is_archived', 0)
                ->orderBy('english_name', 'ASC')
                ->get();

            $compounds = Compound::where('community_id', $request->community_id)
                ->where('is_archived', 0)
                ->orderBy('english_name', 'ASC')
                ->get();

            foreach ($compounds as $compound) {
                $htmlCompounds .= '<option value="'.$compound->id.'">'.$compound->english_name.'</option>';
            }

            foreach ($households as $household) {
                $htmlHouseholds .= '<option value="'.$household->id.'">'.$household->english_name.'</option>';
            }

            $energySystems = EnergySystem::where('community_id', $request->community_id)
                ->get();

            foreach($energySystems as $energySystem) {

                $htmlEnergySystems .= '<option value="'.$energySystem->id.'">'.$energySystem->name.'</option>';
            }
        }

        return response()->json([
            'htmlHouseholds' => $htmlHouseholds,
            'htmlCompounds' => $htmlCompounds,
            'htmlEnergySystems' => $htmlEnergySystems
        ]);
    }

    /**
     * View Edit page.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function editPage($id)
    {
        $compoundHousehold = CompoundHousehold::findOrFail($id);

        return response()->json($compoundHousehold);
    } 

    /**
     * View Edit page.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) 
    {
        $compoundHousehold = CompoundHousehold::findOrFail($id);
        $compounds = Compound::where('is_archived', 0)
            ->get();

        return view('admin.community.compound.edit', compact('compoundHousehold', 'compounds'));
    }
}