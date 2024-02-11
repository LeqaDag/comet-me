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
use App\Models\AllEnergyMeterDonor;
use App\Models\AllWaterHolder;
use App\Models\AllWaterHolderDonor;
use App\Models\CommunityRepresentative;
use App\Models\ElectricityMaintenanceCall;
use App\Models\FbsUserIncident;
use App\Models\GridUser;
use App\Models\H2oUser;
use App\Models\H2oSharedUser;
use App\Models\H2oMaintenanceCall;
use App\Models\InternetUser;
use App\Models\RefrigeratorHolder;
use App\Models\RefrigeratorMaintenanceCall;
use App\Models\Donor;
use App\Models\EnergySystem;
use App\Models\EnergySystemType;
use App\Models\PublicStructureCategory;
use App\Models\User;
use App\Models\Community;
use App\Models\CommunityHousehold;
use App\Models\Cistern;
use App\Models\Household;
use App\Models\HouseholdMeter;
use App\Models\HouseholdStatus;
use App\Models\Region;
use App\Models\Structure;
use App\Models\SubRegion;
use App\Models\Profession;
use App\Models\MovedHousehold;
use App\Models\GridSharedUser;
use App\Exports\HouseholdExport;
use Carbon\Carbon;
use DataTables;
use mikehaertl\wkhtmlto\Pdf;
use Excel;
use Illuminate\Support\Facades\URL;

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
        //         $house->community_id == 8 || $house->community_id == 9 ||
        //         $house->community_id == 10 || $house->community_id == 11 ||
        //         $house->community_id == 14 || $house->community_id == 15 ||
        //         $house->community_id == 12 || $house->community_id == 126) {
             
        //         $house->household_status_id  = 2;
        //         $house->save();

        //     } if($house->community_id == 139 || $house->community_id == 140) {
             
        //         $house->household_status_id  = 1;
        //         $house->save();
        //     }
        // }
        
        if (Auth::guard('user')->user() != null) {

            $communities = Community::where('is_archived', 0)
                ->orderBy('english_name', 'ASC')
                ->get();
            $households = Household::paginate();
            $regions = Region::where('is_archived', 0)->get();
            $subregions = SubRegion::where('is_archived', 0)->get();

            $householdRecords = Household::where('is_archived', 0)->count();
            $householdsInitial = Household::where("household_status_id", 1)
                ->where('is_archived', 0)
                ->get();
            $householdInitial = Household::where("household_status_id", 1)
                ->where('is_archived', 0)
                ->count();
            $householdsAC = Community::where("community_status_id", 2)
                ->where('is_archived', 0)
                ->get();
            $householdAC = Community::where("community_status_id", 2)
                ->where('is_archived', 0)
                ->count();
            $householdsServed = Household::where("household_status_id", 4)
                ->where('is_archived', 0)
                ->get();
            $householdServed = Household::where("household_status_id", 4)
                ->where('is_archived', 0)
                ->count();

            $householdWater =  Household::where("water_service", "Yes")
                ->where('is_archived', 0)
                ->selectRaw('SUM(number_of_people) AS number_of_people')
                ->count();
            $householdInternet = Household::where("internet_system_status", "Served")
                ->where('is_archived', 0)
                ->count(); 

            $communityFilter = $request->input('filter');
            $regionFilter = $request->input('second_filter');
            $statusFilter = $request->input('third_filter');

            if ($request->ajax()) {
                
                $data = DB::table('households')
                    ->join('communities', 'households.community_id', '=', 'communities.id')
                    ->join('regions', 'communities.region_id', '=', 'regions.id')
                    ->join('sub_regions', 'communities.sub_region_id', '=', 'sub_regions.id')
                    ->join('household_statuses', 'households.household_status_id', 
                        'household_statuses.id')
                    // ->leftJoin('refrigerator_holders', 'households.id', '=', 'refrigerator_holders.household_id')
                    // ->leftJoin('refrigerator_holder_receive_numbers', 'refrigerator_holders.id', 
                    //     '=', 'refrigerator_holder_receive_numbers.refrigerator_holder_id')
                    // ->leftJoin('all_energy_meters', 'households.id', '=', 
                    //     'all_energy_meters.household_id')
                    ->where('internet_holder_young', 0)
                    ->where('households.is_archived', 0);
                    
                if($communityFilter != null) {

                    $data->where('communities.id', $communityFilter);
                }
                if ($regionFilter != null) {

                    $data->where('regions.id', $regionFilter);
                }
                if ($statusFilter != null) {

                    $data->where('household_statuses.id', $statusFilter);
                }

                $data->select(
                    'households.english_name as english_name', 
                    'households.arabic_name as arabic_name',
                    'households.id as id', 'households.created_at as created_at', 
                    'households.updated_at as updated_at',
                    'communities.english_name as name',
                    'communities.arabic_name as aname',
                    'household_statuses.status',
                    //'refrigerator_holder_receive_numbers.receive_number',
                    //'all_energy_meters.is_main'
                    )
                ->groupBy('households.id')
                ->latest();
 
                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
                        $detailsButton = "<a type='button' class='detailsHouseholdButton' data-bs-toggle='modal' data-bs-target='#householdDetails' data-id='".$row->id."'><i class='fa-solid fa-eye text-primary'></i></a>";
                        $updateButton = "<a type='button' class='updateHousehold' data-id='".$row->id."'><i class='fa-solid fa-pen-to-square text-success'></i></a>";
                        $deleteButton = "<a type='button' class='deleteHousehold' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";
                        
                        if(Auth::guard('user')->user()->user_type_id != 7 || 
                            Auth::guard('user')->user()->user_type_id != 11 || 
                            Auth::guard('user')->user()->user_type_id != 8) 
                        {
                                
                            return $detailsButton." ". $updateButton." ".$deleteButton;
                        } else return $detailsButton; 

                    })
                    ->addColumn('statusLabel', function($row) {

                        if($row->status == "Initial") 
                        $statusLabel = "<span class='badge rounded-pill bg-label-dark'>".$row->status."</span>";

                        else if($row->status == "AC Survey") 
                        $statusLabel = "<span class='badge rounded-pill bg-label-primary'>".$row->status."</span>";
                       
                        else if($row->status == "In Progress") 
                        $statusLabel = "<span class='badge rounded-pill bg-label-warning'>".$row->status."</span>";

                        else if($row->status == "Served") 
                        $statusLabel = "<span class='badge rounded-pill bg-label-success'>".$row->status."</span>";

                        else if($row->status == "Requested") 
                        $statusLabel = "<span class='badge rounded-pill bg-label-info'>".$row->status."</span>";

                        else if($row->status == "Displaced") 
                        $statusLabel = "<span class='badge rounded-pill bg-label-danger'>".$row->status."</span>";

                        return $statusLabel;
                    })
                    ->addColumn('checkStatus', function($row) {

                        $checkStatus = "<input type='checkbox' class='householdStatus form-check-input' name='household_status_id[]' id='". $row->id ."' value='". $row->id ."'>";
                      
                        return $checkStatus;
                    })
                    ->addColumn('icon', function($row) {

                        // $icon = "<i class='fa-solid fa-check text-success'></i>";

                        // if($row->receive_number != NULL) $icon = "<i class='fa-solid fa-check text-success'></i>";
                        // else $icon = "<i class='fa-solid fa-close text-danger'></i>";

                        // return $icon;
                    })
                    ->filter(function ($instance) use ($request) {
                        if (!empty($request->get('search'))) {
                                $instance->where(function($w) use($request) {
                                    $search = $request->get('search');
                                    $w->orWhere('households.english_name', 'LIKE', "%$search%")
                                    ->orWhere('communities.english_name', 'LIKE', "%$search%")
                                    ->orWhere('communities.arabic_name', 'LIKE', "%$search%")
                                    ->orWhere('regions.arabic_name', '=', $search)
                                    ->orWhere('regions.english_name', '=', $search)
                                    ->orWhere('households.arabic_name', 'LIKE', "%$search%")
                                    ->orWhere('household_statuses.status', 'LIKE', "%$search%")
                                   // ->orWhere('all_energy_meters.is_main', 'LIKE', "%$search%")
                                    ;
                            });
                        }
                    })
                    ->rawColumns(['action', 'icon', 'statusLabel', 'checkStatus'])
                    ->make(true);
            }

            $dataHouseholdsByRegion = DB::table('households')
                ->where('households.household_status_id', 4)
                ->join('communities', 'households.community_id', '=', 'communities.id')
                ->where('households.is_archived', 0)
                ->join('regions', 'communities.region_id', '=', 'regions.id')
                ->select(
                        DB::raw('regions.english_name as english_name'),
                        DB::raw('count(*) as number'))
                ->groupBy('regions.english_name')
                ->get();
            $arrayHouseholdsByRegion[] = ['Region Name', 'Total'];
            
            foreach($dataHouseholdsByRegion as $key => $value) {

                $arrayHouseholdsByRegion[++$key] = [$value->english_name, $value->number];
            }

            $dataHouseholdsBySubRegion = DB::table('households')
                ->where('households.household_status_id', 4)
                ->where('households.is_archived', 0)
                ->join('communities', 'households.community_id', '=', 'communities.id')
                ->join('regions', 'communities.region_id', '=', 'regions.id')
                ->join('sub_regions', 'communities.sub_region_id', '=', 'sub_regions.id')
                ->select(
                        DB::raw('sub_regions.english_name as english_name'),
                        DB::raw('count(*) as number'))
                ->groupBy('sub_regions.english_name')
                ->get();
            $arrayHouseholdsBySubRegion[] = ['Region Name', 'Total'];
            
            foreach($dataHouseholdsBySubRegion as $key => $value) {

                $arrayHouseholdsBySubRegion[++$key] = [$value->english_name, $value->number];
            }
             
            $energySystemTypes = EnergySystemType::where('is_archived', 0)->get();
            $donors = Donor::where('is_archived', 0)->get();
            $publicCategories = PublicStructureCategory::where('is_archived', 0)->get();
            $householdStatuses = HouseholdStatus::where('is_archived', 0)->get();

            return view('employee.household.index', compact('communities', 'regions', 
                'households', 'subregions', 'householdsInitial', 'householdInitial', 
                'householdsServed', 'householdServed', 'householdRecords',
                'householdsAC', 'householdAC', 'householdWater', 'householdInternet',
                'publicCategories', 'donors', 'energySystemTypes', 'householdStatuses'))
                ->with('regionHouseholdsData', json_encode($arrayHouseholdsByRegion))
                ->with('subRegionHouseholdsData', json_encode($arrayHouseholdsBySubRegion));

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
        $energySystemTypes = EnergySystemType::where('is_archived', 0)->get();

        return view('employee.household.create', compact('communities', 'regions', 
            'professions', 'energySystemTypes'));
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
        $household->number_of_people = $request->number_of_male + $request->number_of_female;
        if($request->energy_system_type_id) $household->energy_system_type_id = $request->energy_system_type_id;

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
        
        $data = DB::table('households')
            ->where('households.is_archived', 0)
            ->join('communities', 'communities.id', '=', 'households.community_id')
            ->select(
                'households.community_id AS id',
                DB::raw("count(households.community_id) AS total_household"))
            ->groupBy('households.community_id')
            ->get();
       
        
        foreach($data as $d) {
            $community = Community::findOrFail($d->id);
            $community->number_of_household = $d->total_household;
            $community->save();
        }

        $peopleHouseholds = DB::table('households')
            ->where('households.is_archived', 0)
            ->join('communities', 'communities.id', '=', 'households.community_id')
            ->select(
                'households.community_id AS id',
                DB::raw("sum(households.number_of_male + households.number_of_female) AS total_people"))
            ->groupBy('households.community_id')
            ->get();

        foreach($peopleHouseholds as $peopleHousehold) {
            $community = Community::findOrFail($peopleHousehold->id);
            $community->number_of_people = $peopleHousehold->total_people;
            $community->save();
        }

        return redirect('/household')
            ->with('message', 'New Household Added Successfully!');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getCommunityEnergySource(int $community_id)
    {
        $community = Community::findOrFail($community_id);
 
        $html = '';
        $community = Community::findOrFail($community_id);

        if($community->energy_source == "Grid") {

            $html .= '<option selected value="Grid">'.$community->energy_source.' </option><option value="Old Solar System">Old Solar System</option> <option value="Generator">Generator</option>';
        } else if($community->energy_source == "Old Solar System") {

            $html .= '<option selected value="Old Solar System">'.$community->energy_source.'</option><option value="Grid">Grid</option><option value="Generator">Generator</option>';
        } else if($community->energy_source == "Generator") {

            $html .= '<option selected value="Generator">'.$community->energy_source.' </option><option value="Grid">Grid</option> <option value="Old Solar System">Old Solar System</option>';
        } else {

            $html = '<option disabled selected>Choose One...</option><option value="Grid">Grid</option><option value="Old Solar System">Old Solar System</option><option value="Generator">Generator</option>';
        }
        

        return response()->json(['html' => $html]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function newHousehold(Request $request)
    {
        //dd($request->all());
        $household = new Household();
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
        $household->save();
 
        $html = '<option value="'.$household->id.'">'.$household->english_name.'</option>';

        return response()->json(['html' => $html]);
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
        $regions = SubRegion::where('region_id', $request->region_id)
            ->where('is_archived', 0)
            ->get();
 
        if (!$request->region_id) {
            $html = '<option value="">Choose One...</option>';
        } else {
            $html = '';
            $regions = SubRegion::where('region_id', $request->region_id)
                ->where('is_archived', 0)
                ->get();
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
        $households = Household::where('community_id', $request->community_id)
            ->where('is_archived', 0)
            ->orderBy('english_name', 'ASC')
            ->get();

        if (!$request->community_id) {

            $html = '<option value="">Choose One...</option>';
        } else {

            $html = '<option selected>Choose One...</option>';
            $households = Household::where('community_id', $request->community_id)
                ->orderBy('english_name', 'ASC')
                ->where('is_archived', 0)
                ->get();
            foreach ($households as $household) {
                $html .= '<option value="'.$household->id.'">'.$household->english_name.'</option>';
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
    public function getNonUserByCommunity(Request $request)
    {
        if (!$request->community_id) {

            $html = '<option value="">Choose One...</option>';
        } else {

            $html = '<option disabled selected>Choose One...</option>';
            $households = DB::table('households')
                ->where('households.community_id', $request->community_id)
               // ->leftJoin('all_energy_meters', 'households.id', 'all_energy_meters.household_id')
                //->whereNull('all_energy_meters.household_id')
                ->select('households.id', 'households.english_name')
                ->get();

            foreach ($households as $household) {
                $html .= '<option value="'.$household->id.'">'.$household->english_name.'</option>';
            }
        }

        return response()->json(['html' => $html]);
    }

    /**
     * Get community by installation type
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getCommunityByType(Request $request)
    {
        if (!$request->installation_type) {

            $html = '<option value="">Choose One...</option>';
        } else {

            $html = '<option disabled selected>Choose One...</option>';

            if($request->installation_type == 1) {

                $communities = Community::where('is_archived', 0)
                    ->where('community_status_id', 1)
                    ->orWhere('community_status_id', 2)
                    ->get();

            } else {
 
                $communities = Community::where('is_archived', 0)
                    ->where('community_status_id', '!=', 1)
                    ->get();
            }
            

            foreach ($communities as $community) {
                $html .= '<option value="'.$community->id.'">'.$community->english_name.'</option>';
            }
        }

        return response()->json(['html' => $html]);
    }

    /**
     * Write code on Construct
     *
     * @return \Illuminate\Http\Response
     */
    public function exportPdf()
    {
        $render = view('employee.household.index')->render();
  
        $pdf = new Pdf;
        $pdf->addPage($render);
        $pdf->setOptions(['javascript-delay' => 5000]);
        $pdf->saveAs(public_path('report.pdf'));
   
        return response()->download(public_path('report.pdf'));
    }

    /**
     * Show the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $household = Household::findOrFail($id);
        $community = Community::where('id', $household->community_id)->first();
        $profession = Profession::where('id', $household->profession_id)->first();
        $status = HouseholdStatus::where('id', $household->household_status_id)->first();
        $cistern = Cistern::where('household_id', $id)->first();
        $structure = Structure::where('household_id', $id)->first();
        $communityHousehold = CommunityHousehold::where('household_id', $id)->first();

        $response['community'] = $community;
        $response['household'] = $household;
        $response['profession'] = $profession;
        $response['status'] = $status;
        $response['cistern'] = $cistern;
        $response['structure'] = $structure;
        $response['communityHousehold'] = $communityHousehold;

        return response()->json($response);
    }

    /**
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteHousehold(Request $request)
    {
        $id = $request->id;

        $household = Household::find($id);

        if($household) {

            $household->is_archived = 1;
            $household->save();

            $response['success'] = 1;
            $response['msg'] = 'Household Deleted successfully'; 
        } else {

            $response['success'] = 0;
            $response['msg'] = 'Invalid ID.';
        }

        return response()->json($response); 
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
        $energySystemTypes = EnergySystemType::where('is_archived', 0)
            ->orderBy('name', 'ASC')
            ->get();
        $householdStatuses = HouseholdStatus::where('is_archived', 0)->get();

        return view('employee.household.edit', compact('household', 'regions', 'communities',
            'professions', 'structure', 'cistern', 'communityHousehold', 'energySystemTypes',
            'householdStatuses'));
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
        $householdMeter = HouseholdMeter::where('user_name', $household->english_name)->first();

        // update user name for shared h2o
        $h2oUser = H2oUser::where('household_id', $household->id)->first();
        if($h2oUser) {

            $sharedH2oUser = H2oSharedUser::where('h2o_user_id', $h2oUser->id)->first();
            if($sharedH2oUser) {

                if($request->english_name) $sharedH2oUser->user_english_name = $request->english_name;
                if($request->arabic_name) $sharedH2oUser->user_arabic_name = $request->arabic_name;
                $sharedH2oUser->save();
            }
        }
        // end updating user name for shared h2o
        
        // update user name for shared grid user
        $gridUser = GridUser::where('household_id', $household->id)->first();
        if($gridUser) {

            $sharedGridUser = GridSharedUser::where('grid_user_id', $gridUser->id)->first();
            if($sharedGridUser) {

                if($request->english_name) $sharedGridUser->grid_user_english_name = $request->english_name;
                if($request->arabic_name) $sharedGridUser->grid_user_arabic_name = $request->arabic_name;
                $sharedGridUser->save();
            }
        }
        // end updating user name for shared grid user

        $household->english_name = $request->english_name;
        $household->arabic_name = $request->arabic_name;
        $household->women_name_arabic = $request->women_name_arabic;
        $household->profession_id = $request->profession_id;
        $household->phone_number = $request->phone_number;

        if($request->community_id) {

            $movedHousehold = new MovedHousehold();
            $movedHousehold->household_id = $id;
            $movedHousehold->old_community_id = $household->community_id;
            $movedHousehold->new_community_id  = $request->community_id;
            $movedHousehold->save();

            $household->community_id = $request->community_id;

            $allEnergyMeter = AllEnergyMeter::where("household_id", $id)->first();
            if($allEnergyMeter) {

                $allEnergyMeter->community_id = $request->community_id;
                if($allEnergyMeter->energy_system_type != 2) {

                    $energySystem = EnergySystem::where("community_id", $request->community_id)->first();
                    if($energySystem) {
                        $allEnergyMeter->energy_system_id = $energySystem->id;
                    }
                }
                $allEnergyMeter->save();

                $allEnergyMeterDonors = AllEnergyMeterDonor::where("all_energy_meter_id", $id)->get();
                if($allEnergyMeterDonors) {

                    foreach($allEnergyMeterDonors as $allEnergyMeterDonor) {

                        $allEnergyMeterDonor->community_id = $request->community_id;
                        $allEnergyMeterDonor->save();
                    }
                }

                $userIncidents = FbsUserIncident::where("energy_user_id", $allEnergyMeter->id)->get();
                if($userIncidents) {

                    foreach($userIncidents as $userIncident) {

                        $userIncident->community_id = $request->community_id;
                        $userIncident->save();
                    }
                }
            }

            $allWaterHolder = AllWaterHolder::where("household_id", $id)->first();
            if($allWaterHolder) {

                $allWaterHolder->community_id = $request->community_id;
                $allWaterHolder->save();
                $allWaterHolderDonors = AllWaterHolderDonor::where("all_water_holder_id", $id)->get();
                if($allWaterHolderDonors) {

                    foreach($allWaterHolderDonors as $allWaterHolderDonor) {

                        $allWaterHolderDonor->community_id = $request->community_id;
                        $allWaterHolderDonor->save();
                    }
                }

                $gridUser = GridUser::where("household_id", $id)->first();
                if($gridUser) {

                    $gridUser->community_id = $request->community_id;
                    $gridUser->save();
                }

                $h2oUser = H2oUser::where("household_id", $id)->first();
                if($h2oUser) {

                    $h2oUser->community_id = $request->community_id;
                    $h2oUser->save();
                }
            }

            $communityRepresentative = CommunityRepresentative::where("household_id", $id)->first();
            if($communityRepresentative) {

                $communityRepresentative->is_archived = 1;
                $communityRepresentative->save();
            }

            // $energyMaintenance = ElectricityMaintenanceCall::where("household_id", $id)->first();
            // if($energyMaintenance) {

            //     $energyMaintenance->community_id = $request->community_id;
            //     $energyMaintenance->save();   
            // }

            // $h2oMaintenance = H2oMaintenanceCall::where("household_id", $id)->first();
            // if($h2oMaintenance) {

            //     $h2oMaintenance->community_id = $request->community_id;
            //     $h2oMaintenance->save();   
            // }

            $internetUser = InternetUser::where("household_id", $id)->first();
            if($internetUser) {

                $internetUser->community_id = $request->community_id;
                $internetUser->save();   
            }

            $refrigeratorHolders = RefrigeratorHolder::where("household_id", $id)->get();
            if($refrigeratorHolders) {

                foreach($refrigeratorHolders as $refrigeratorHolder) {

                    $refrigeratorHolder->community_id = $request->community_id;
                    $refrigeratorHolder->save(); 
                }  
            }
        }

        $household->number_of_children = $request->number_of_children;
        $household->number_of_people = $request->number_of_people;
        $household->number_of_adults = $request->number_of_adults;
        $household->university_students = $request->university_students;
        $household->school_students = $request->school_students;
        $household->number_of_male = $request->number_of_male;
        $household->number_of_female = $request->number_of_female;
        $household->demolition_order = $request->demolition_order;
        if($request->household_status_id) $household->household_status_id = $request->household_status_id;
        if($request->water_system_status) $household->water_system_status = $request->water_system_status;
        if($request->internet_system_status) $household->internet_system_status = $request->internet_system_status;
        $household->notes = $request->notes;
        $household->size_of_herd = $request->size_of_herd;
        if($request->electricity_source) $household->electricity_source = $request->electricity_source;
        if($request->electricity_source_shared) $household->electricity_source_shared = $request->electricity_source_shared;
        if($request->energy_system_type_id) $household->energy_system_type_id = $request->energy_system_type_id;
        $household->save();

        if($householdMeter) {

            if($request->english_name) $householdMeter->user_name = $request->english_name;
            if($request->arabic_name) $householdMeter->user_name_arabic = $request->arabic_name;
            $householdMeter->save();
        }

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
        
        return redirect('/household')
            ->with('message', 'Household Updated Successfully!');
    }

    /**
     * 
     * @return \Illuminate\Support\Collection
     */
    public function export(Request $request) 
    {
                
        return Excel::download(new HouseholdExport($request), 'households.xlsx');
    }
}
