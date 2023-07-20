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
use App\Models\User;
use App\Models\Community;
use App\Models\CommunityHousehold;
use App\Models\Cistern;
use App\Models\EnergyUser;
use App\Models\EnergySystem;
use App\Models\Household;
use App\Models\HouseholdMeter;
use App\Models\HouseholdStatus;
use App\Models\Region;
use App\Models\Structure;
use App\Models\SubRegion;
use App\Models\Profession;
use App\Models\EnergySystemType;
use App\Models\EnergyHolder;
use App\Models\EnergyPublicStructure;
use App\Models\MeterCase;
use Carbon\Carbon;
use DataTables;
use mikehaertl\wkhtmlto\Pdf;

class AcHouseholdController extends Controller
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
                    ->where('households.household_status_id', 1)
                    ->orWhere('households.household_status_id', 2)
                    ->where('internet_holder_young', 0)
                    ->where('households.is_archived', 0)
                    ->join('communities', 'households.community_id', '=', 'communities.id')
                    ->join('regions', 'communities.region_id', '=', 'regions.id')
                    ->select('households.english_name as english_name', 'households.arabic_name as arabic_name',
                        'households.id as id', 'households.created_at as created_at', 
                        'households.updated_at as updated_at',
                        'regions.english_name as region_name',
                        'communities.english_name as name',
                        'communities.arabic_name as aname',
                        'households.energy_meter')
                    ->latest(); 
    
                
                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
    
                        $empty = "";
                        $updateButton = "<a type='button' class='updateAcHousehold' data-id='".$row->id."'><i class='fa-solid fa-pen-to-square text-success'></i></a>";

                        if(Auth::guard('user')->user()->user_type_id == 1 || 
                            Auth::guard('user')->user()->user_type_id == 2 || 
                            Auth::guard('user')->user()->user_type_id == 3 || 
                            Auth::guard('user')->user()->user_type_id == 4 || 
                            Auth::guard('user')->user()->user_type_id == 12 ) 
                        {
                            return $updateButton;
                        }
                        else  return $empty;
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
                ->where('households.is_archived', 0)
                ->where('households.household_status_id', 1)
                ->orWhere('households.household_status_id', 2)
                ->join('communities', 'households.community_id', '=', 'communities.id')
                ->select(
                        DB::raw('communities.english_name as english_name'),
                        DB::raw('count(*) as number'))
                ->groupBy('communities.english_name')
                ->get();
            $arrayAcHouseholdsByCommunity[] = ['Community Name', 'Total'];
            
            foreach($dataHouseholdsByCommunity as $key => $value) {
    
                $arrayAcHouseholdsByCommunity[++$key] = [$value->english_name, $value->number];
            }
    
            $communities = Community::where('is_archived', 0)
                ->orderBy('english_name', 'ASC')
                ->get();
            $households = Household::where('is_archived', 0)
                ->orderBy('english_name', 'ASC')
                ->get();
            $energySystems = EnergySystem::where('is_archived', 0)->get();
            $energySystemTypes = EnergySystemType::where('is_archived', 0)->get();
            $meters = MeterCase::where('is_archived', 0)->get();
            $professions  = Profession::where('is_archived', 0)->get();
    
            return view('employee.household.ac', compact('communities', 'households', 
                'energySystems', 'energySystemTypes', 'meters', 'professions'))
                ->with('communityAcHouseholdsData', json_encode($arrayAcHouseholdsByCommunity));
        } else {

            return view('errors.not-found');
        }
    }

    /**
     * Change resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function acSubHousehold(Request $request)
    {
        $id = $request->id;
        $household = Household::find($id);
        $households = Household::where("community_id", $household->community_id)
            ->where('is_archived', 0)
            ->orderBy('english_name', 'ASC')
            ->where("id", "!=", $household->id)
            ->select("english_name", "id")
            ->get();

        $response = $households; 
      
        return response()->json($response); 
    }
 
    /**
     * Change resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function acSubHouseholdSave(Request $request)
    {
        $energyUserHouseholdMeter = AllEnergyMeter::where("household_id", $request->id)->first();
        
        $mainHousehold = Household::findOrFail($request->user_id);
        $mainHousehold->energy_service = "Yes";
        $mainHousehold->energy_meter = "Yes";
        $mainHousehold->save();


        $energyUser = AllEnergyMeter::where("household_id", $request->user_id)->first();
      
        if($energyUserHouseholdMeter != null) {
            $energyUserHouseholdMeter->delete();
        } 

        $householdMeter = Household::findOrFail($request->id);
        $householdMeter->energy_service = "Yes";
        $householdMeter->energy_meter = "No";

        if($energyUser != null) {
            if($energyUser->meter_active == "Yes") {

                $householdMeter->household_status_id = 4;
            }
        }
        
        $householdMeter->save();

        $householdSharedMeter = new HouseholdMeter();
        $householdSharedMeter->user_name = $mainHousehold->english_name;
        $householdSharedMeter->user_name_arabic = $mainHousehold->arabic_name;
        $householdSharedMeter->household_name = $householdMeter->english_name;
        $householdSharedMeter->energy_user_id = $energyUser->id;
        $householdSharedMeter->household_id = $request->id;
        $householdSharedMeter->save();

       // die($householdSharedMeter);

        $response = $householdSharedMeter;  
      
        return response()->json($response); 
    }

    /**
     * Change resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function acMainHousehold(Request $request)
    {
        $householdMeter = HouseholdMeter::where("household_id", $request->id)->get();

        if($householdMeter == []) {
            $householdMeter->delete();
        }

        $mainHousehold = Household::findOrFail($request->id);
        $mainHousehold->energy_service = "Yes";
        $mainHousehold->energy_meter = "Yes";
        $mainHousehold->save();

        $response = $mainHousehold;

        return response()->json($response); 
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
        $energySystemTypes = EnergySystemType::where('is_archived', 0)->get();
        $households = Household::where('is_archived', 0)
            ->orderBy('english_name', 'ASC')
            ->get();
        $professions  = Profession::where('is_archived', 0)->get();

        return view('employee.household.create_ac', compact('communities', 'energySystemTypes', 
            'households', 'professions'));
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
        $household->english_name = $request->english_name;
        $household->arabic_name = $request->arabic_name;
        $household->household_status_id = 2;
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

        $cistern = new Cistern();
        $cistern->number_of_cisterns = $request->number_of_cisterns;
        $cistern->household_id = $id;
        $cistern->save();

        $cistern = new Structure();
        $cistern->number_of_structures = $request->number_of_structures;
        $cistern->number_of_kitchens = $request->number_of_kitchens;
        $cistern->household_id = $id;
        $cistern->save();
     
        return redirect('/ac-household')
            ->with('message', 'New AC Household Added Successfully!');
    }

     /**
     * View Edit page.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function editPage($id)
    {
        $household = Household::findOrFail($id);

        return response()->json($household);
    } 

    /** 
     * View Edit page.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $communities = Community::where('is_archived', 0)
            ->orderBy('english_name', 'ASC')
            ->get();
        $regions = Region::where('is_archived', 0)->get();
        $professions = Profession::where('is_archived', 0)->get();
        $household = Household::findOrFail($id);
        $structure = Structure::where("household_id", $id)->first();
        $cistern = Cistern::where("household_id", $id)->first();
        $communityHousehold = CommunityHousehold::where('household_id', $id)->first();

        return view('employee.household.edit_ac', compact('household', 'regions', 'communities',
            'professions', 'structure', 'cistern', 'communityHousehold'));
    }

    /**
     * Update an existing resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request, int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $household = Household::findOrFail($id);
        $household->english_name = $request->english_name;
        $household->arabic_name = $request->arabic_name;
        $household->women_name_arabic = $request->women_name_arabic;
        $household->profession_id = $request->profession_id;
        $household->phone_number = $request->phone_number;
        if($request->community_id) $household->community_id = $request->community_id;
        $household->number_of_children = $request->number_of_children;
        $household->number_of_adults = $request->number_of_adults;
        $household->university_students = $request->university_students;
        $household->school_students = $request->school_students;
        $household->number_of_male = $request->number_of_male;
        $household->number_of_female = $request->number_of_female;
        $household->demolition_order = $request->demolition_order;
        $household->notes = $request->notes;
        $household->size_of_herd = $request->size_of_herd;
        if($request->electricity_source) $household->electricity_source = $request->electricity_source;
        if($request->electricity_source_shared) $household->electricity_source_shared = $request->electricity_source_shared;
        $household->save();

        $cistern = Cistern::where('household_id', $id)->first();
        if($cistern == null) {

            $newCistern = new Cistern();
            $newCistern->number_of_cisterns = $request->number_of_cisterns;
            $newCistern->volume_of_cisterns = $request->volume_of_cisterns;
            $newCistern->shared_cisterns = $request->shared_cisterns;
            $newCistern->distance_from_house = $request->distance_from_house;
            $newCistern->depth_of_cisterns = $request->depth_of_cisterns;
            $newCistern->household_id = $id;
            $newCistern->save();
        } else {
            
            $cistern->number_of_cisterns = $request->number_of_cisterns;
            $cistern->volume_of_cisterns = $request->volume_of_cisterns;
            $cistern->shared_cisterns = $request->shared_cisterns;
            $cistern->distance_from_house = $request->distance_from_house;
            $cistern->depth_of_cisterns = $request->depth_of_cisterns;
            $cistern->household_id = $id;
            $cistern->save();
        }
        
        $structure = Structure::where('household_id', $id)->first();
        if($structure == null) {

            $newStructure = new Structure();
            $newStructure->number_of_structures = $request->number_of_structures;
            $newStructure->number_of_kitchens = $request->number_of_kitchens;
            $newStructure->number_of_animal_shelters = $request->number_of_animal_shelters;
            $newStructure->household_id = $id;
            $newStructure->save();
        } else {
            
            $structure->number_of_structures = $request->number_of_structures;
            $structure->number_of_kitchens = $request->number_of_kitchens;
            $structure->number_of_animal_shelters = $request->number_of_animal_shelters;
            $structure->household_id = $id;
            $structure->save();
        }
        
        $communityHousehold = CommunityHousehold::where('household_id', $id)->first();
        if($communityHousehold == null) {

            $newCommunityHousehold = new CommunityHousehold();
            $newCommunityHousehold->is_there_house_in_town = $request->is_there_house_in_town;
            $newCommunityHousehold->is_there_izbih = $request->is_there_izbih;
            $newCommunityHousehold->how_long = $request->how_long;
            $newCommunityHousehold->length_of_stay = $request->length_of_stay;
            $newCommunityHousehold->household_id = $id;
            $newCommunityHousehold->save();
        } else {
            
            $communityHousehold->is_there_house_in_town = $request->is_there_house_in_town;
            $communityHousehold->is_there_izbih = $request->is_there_izbih;
            $communityHousehold->length_of_stay = $request->length_of_stay;
            $communityHousehold->how_long = $request->how_long;
            $communityHousehold->household_id = $id;
            $communityHousehold->save();
        }

        return redirect('/ac-household')
            ->with('message', 'AC Household Updated Successfully!');
    }
}
