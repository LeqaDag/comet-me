<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Compound;
use App\Models\GridCommunityCompound;
use App\Models\User;
use App\Models\Community;
use App\Models\CommunityDonor;
use App\Models\CommunityStatus;
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
use Auth;
use DB;
use DataTables;
use Route;

class CompoundController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {	
        if (Auth::guard('user')->user() != null) {

            $communityFilter = $request->input('filter1');
            $regionFilter = $request->input('second_filter1');
            $subRegionFilter = $request->input('third_filter1');

            if ($request->ajax()) {

                $data = DB::table('compounds')
                    ->join('communities', 'compounds.community_id', 'communities.id')
                    ->join('regions', 'communities.region_id', 'regions.id')
                    ->join('sub_regions', 'communities.sub_region_id', 'sub_regions.id')
                    ->where('compounds.is_archived', 0);
    
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
                    'communities.english_name as community_english_name', 
                    'compounds.id as id', 'compounds.created_at as created_at', 
                    'compounds.updated_at as updated_at',
                    'communities.number_of_people as number_of_people',
                    'communities.number_of_household as number_of_household',
                    'regions.english_name as name',
                    'regions.arabic_name as aname')
                ->latest(); 

                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {

                        $empty = "";
                        $detailsButton = "<a type='button' class='detailsCompoundButton' data-id='".$row->id."'><i class='fa-solid fa-eye text-primary'></i></a>";
                        $updateButton = "<a type='button' class='updateCompoundButton' data-id='".$row->id."' ><i class='fa-solid fa-pen-to-square text-success'></i></a>";
                        $deleteButton = "<a type='button' class='deleteCompound' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";
    
                        if(Auth::guard('user')->user()->user_type_id == 1 || 
                            Auth::guard('user')->user()->user_type_id == 2 || 
                            Auth::guard('user')->user()->user_type_id == 3 ) 
                        {
                                
                            return  $detailsButton." ". $updateButton." ".$deleteButton;
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
                                ->orWhere('compounds.arabic_name', 'LIKE', "%$search%");
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) 
    {
        if($request->addMoreInputFieldsCompoundName) {
            foreach($request->addMoreInputFieldsCompoundName as $compoundName) {
                if($compoundName["subject"] != NULL) {

                    $compound = new Compound();
                    $compound->english_name = $compoundName["subject"];
                    $compound->community_id = $request->community_id;
                    $compound->save();

                    $gridCompound = new GridCommunityCompound();
                    $gridCompound->compound_id = $compound->id;
                    $gridCompound->save();
                }
            }
        }

        return redirect()->back()->with('message', 'New Compound Added Successfully!');
    }

     /**
     * View Edit page.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function editPage($id)
    {
        $compound = Compound::findOrFail($id);

        return response()->json($compound);
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
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function deleteCompound(Request $request)
    {
        $id = $request->id;

        $compound = Compound::findOrFail($id);
        $compound->is_archived = 1;
        $compound->save();
 
        $response['success'] = 1;
        $response['msg'] = 'Compound Deleted successfully'; 
        
        return response()->json($response); 
    }
}
