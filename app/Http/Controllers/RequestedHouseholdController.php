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
use App\Models\EnergyRequestSystem;
use App\Models\Region;
use App\Models\Structure;
use App\Models\SubRegion;
use App\Models\Profession;
use Carbon\Carbon;
use DataTables;
use mikehaertl\wkhtmlto\Pdf;

class RequestedHouseholdController extends Controller
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
            
                $data = DB::table('households')
                    ->join('communities', 'households.community_id', '=', 'communities.id')
                    ->join('regions', 'communities.region_id', '=', 'regions.id')
                    ->where('households.is_archived', 0)
                    ->where('households.internet_holder_young', 0)
                    ->where('households.household_status_id', 5)
                    ->select('households.english_name as english_name', 
                        'households.arabic_name as arabic_name',
                        'households.id as id', 'households.created_at as created_at', 
                        'households.updated_at as updated_at',
                        'regions.english_name as region_name',
                        'communities.english_name as name', 'households.phone_number',
                        'communities.arabic_name as aname',)
                    ->latest(); 
    
                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
    
                        // $acButton = "<a title='From Initial to AC Survey' type='button' class='initialToAcHousehold' data-id='".$row->id."'><i class='fa-solid fa-check-square text-info'></i></a>";
                        // $updateButton = "<a type='button' class='updateHousehold' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#updateHouseholdModal' ><i class='fa-solid fa-pen-to-square text-success'></i></a>";
                        // $deleteButton = "<a type='button' class='deleteHousehold' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";
                        
                        // return $acButton. " ".$updateButton." ".$deleteButton ;
       
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
                    ->make(true);
            }
    
            return view('employee.household.requested.index');
        } else {

            return view('errors.not-found');
        }
    }

  /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $communities = Community::where('is_archived', 0)
            ->orderBy('english_name', 'ASC')
            ->get();
        $regions = Region::where('is_archived', 0)->get();
        $professions = Profession::where('is_archived', 0)->get();

        return view('employee.household.requested.create', compact('communities', 'regions', 
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
        $this->validate($request, [
            'community_id' => 'required',
            'english_name' => 'required',
            'arabic_name' => 'required',
            'profession_id' => 'required'
        ]);

       // dd($request->all());
        $household = new Household();
        $household->household_status_id = 5;
        $household->english_name = $request->english_name;
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
        $household->electricity_source = $request->electricity_source;
        $household->electricity_source_shared = $request->electricity_source_shared;
        $household->save();
        $id = $household->id;

        $requestedSystem = new EnergyRequestSystem();
        $requestedSystem->household_id = $id;
        $requestedSystem->save();

        $cistern = new Cistern();
        $cistern->number_of_cisterns = $request->number_of_cisterns;
        $cistern->household_id = $id;
        $cistern->save();

        $cistern = new Structure();
        $cistern->number_of_structures = $request->number_of_structures;
        $cistern->number_of_kitchens = $request->number_of_kitchens;
        $cistern->household_id = $id;
        $cistern->save();
        
        return redirect('/requested-household')
            ->with('message', 'New Requested Household Added Successfully!');
    }
}
