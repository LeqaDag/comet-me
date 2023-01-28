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
use App\Models\Region;
use App\Models\Structure;
use App\Models\SubRegion;
use App\Models\Profession;
use Carbon\Carbon;
use DataTables;

class HouseholdController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {	
        // $houses = Household::all();
        // foreach($houses as $house) {
        //     if($house->community_id == 1 || $house->community_id == 2 || 
        //         $house->community_id == 3  || $house->community_id == 4 || 
        //         $house->community_id == 5 || $house->community_id == 7 ||
        //         $house->community_id == 8 || $house->community_id == 9 ) {
        //         $house->status = "AC Survey";
        //         $house->save();
        //     }
        // }
        
		$communities = Community::paginate();
        $households = Household::paginate();
        $regions = Region::all();
        $subregions = SubRegion::all();

        if ($request->ajax()) {
            
            $data = DB::table('households')
                ->join('communities', 'households.community_id', '=', 'communities.id')
                ->select('households.english_name as english_name', 'households.arabic_name as arabic_name',
                    'households.id as id', 'households.created_at as created_at', 
                    'households.updated_at as updated_at',
                    'communities.english_name as name',
                    'communities.arabic_name as aname',)
                ->latest(); 

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row) {

                    $updateButton = "<button class='btn btn-sm btn-info updateHousehold' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#updateHouseholdModal' ><i class='fa-solid fa-pen-to-square'></i></button>";
                    $deleteButton = "<button class='btn btn-sm btn-danger deleteHousehold' data-id='".$row->id."'><i class='fa-solid fa-trash'></i></button>";
                    
                    return $updateButton." ".$deleteButton;
   
                })
               
                ->filter(function ($instance) use ($request) {
                    if (!empty($request->get('search'))) {
                            $instance->where(function($w) use($request) {
                                $search = $request->get('search');
                                $w->orWhere('households.english_name', 'LIKE', "%$search%")
                                ->orWhere('communities.english_name', 'LIKE', "%$search%")
                                ->orWhere('communities.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('households.arabic_name', 'LIKE', "%$search%");
                        });
                    }
                })
                ->rawColumns(['action'])
                ->make(true);
                
        }

		return view('employee.household.index', compact('communities', 'regions', 
            'households', 'subregions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $communities = Community::all();
        $regions = Region::all();
        $professions = Profession::all();

        return view('employee.household.create', compact('communities', 'regions', 
            'professions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      
        $household = new Household();
        $household->arabic_name = $request->arabic_name;
        $household->women_name_arabic = $request->women_name_arabic;
        $household->profession_id = $request->profession_id;
        $household->phone_number = $request->phone_number;
        $household->community_id = $request->community_id;
        $household->number_of_children = $request->number_of_children;
        $household->number_of_adults = $request->number_of_adults;
        $household->university_students = $request->university_students;
        $household->school_students = $request->school_students;
        $household->number_of_male = $request->number_of_male;
        $household->number_of_female = $request->number_of_female;
        $household->demolition_order = $request->demolition_order;
        $household->notes = $request->notes;
        $household->size_of_herd = $request->size_of_herd;
        $household->save();
        $id = $household->id;

        $cistern = new Cistern();
        $cistern->number_of_cisterns = $request->number_of_cisterns;
        $cistern->household_id = $id;
        $cistern->save();

        $cistern = new Structure();
        $cistern->number_of_structures = $request->number_of_structures;
        $cistern->number_of_kitchens = $request->number_of_kitchens;
        $cistern->household_id = $id;
        $cistern->save();
        
        return redirect('/household');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function newProfession(String $name)
    {
        $profession = new Profession();
        $profession->profession_name = $name;
        $profession->save();
        $id = $profession->id;

        return response()->json(['name' => $name, 'id' => $id]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function newCommunity(Request $request)
    {
        $regions = SubRegion::where('region_id', $request->region_id)->get();
 
        if (!$request->region_id) {
            $html = '<option value="">Choose One...</option>';
        } else {
            $html = '';
            $regions = SubRegion::where('region_id', $request->region_id)->get();
            foreach ($regions as $region) {
                $html .= '<option value="'.$region->id.'">'.$region->english_name.'</option>';
            }
        }

        return response()->json(['html' => $html]);
    }

    /**
     * Get households by community_id.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getByCommunity(Request $request)
    {
        $households = Household::where('community_id', $request->community_id)->get();
 
        if (!$request->community_id) {
            $html = '<option value="">Choose One...</option>';
        } else {
            $html = '';
            $households = Household::where('community_id', $request->community_id)->get();
            foreach ($households as $household) {
                $html .= '<option value="'.$household->id.'">'.$household->english_name.'</option>';
            }
        }

        return response()->json(['html' => $html]);
    }
}
