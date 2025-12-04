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
use App\Models\EnergyRequestSystem;
use App\Models\EnergySystemCycle;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;
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
                    ->leftJoin('all_energy_meters', 'all_energy_meters.household_id', 'households.id')
                    ->leftJoin('energy_system_types', 'all_energy_meters.energy_system_type_id', 'energy_system_types.id')
                    ->leftJoin('household_statuses', 'households.household_status_id', 'household_statuses.id')
                    ->leftJoin('users', 'households.referred_by_id', 'users.id')
                    ->leftJoin('energy_system_types as energy_types', 'households.energy_system_type_id', 'energy_types.id')
                    ->join('communities', 'households.community_id', 'communities.id')
                    ->join('regions', 'communities.region_id', 'regions.id')
                                        ->where('households.is_archived', 0)
                                        ->where('households.internet_holder_young', 0)
                                        ->where(function($q) {
                                                $q->where('households.household_status_id', 5)
                                                    ->orWhere('household_statuses.status', 'Displaced');
                                        })
                    ->select('households.english_name as english_name', 
                        'households.arabic_name as arabic_name',
                        'households.id as id', 
                        DB::raw('CASE WHEN households.request_date IS NOT NULL THEN households.request_date ELSE DATE(households.created_at) END as created_at'),
                        DB::raw("CASE WHEN household_statuses.status = 'Displaced' THEN 'Displaced' WHEN all_energy_meters.is_main = 'No' THEN 'Served' ELSE 'Service requested' END AS status"),
                        'households.updated_at as updated_at', 'users.name as referred_by',
                        'regions.english_name as region_name', 
                        DB::raw('IFNULL(energy_system_types.name, energy_types.name) as type'),
                        'communities.english_name as name', 'households.phone_number',
                        'communities.arabic_name as aname')
                    ->latest();

                $communityFilter = $request->input('community_filter');
                $regionFilter = $request->input('region_filter');
                $systemTypeFilter = $request->input('system_type_filter');
                $statusFilter = $request->input('household_status');
                $dateFilter = $request->input('date_filter');

                if ($communityFilter) {
                    $data->where('communities.id', $communityFilter);
                }
                if ($regionFilter) {
                    $data->where('regions.id', $regionFilter);
                }
                if ($systemTypeFilter) {
                    $data->where('households.energy_system_type_id', $systemTypeFilter);
                }
                if ($statusFilter) {
                    // map filter values from the UI to DB conditions
                    switch ($statusFilter) {
                        case 'displaced':
                            $data->where('household_statuses.status', 'Displaced');
                            break;
                        case 'served':
                            $data->where('all_energy_meters.is_main', 'No');
                            break;
                        case 'service_requested':
                            $data->where(function($q) {
                                $q->where('all_energy_meters.is_main', 'Yes')
                                  ->orWhereNull('all_energy_meters.is_main');
                            });
                            break;
                        case 'requested':
                            $data->where('households.household_status_id', 5);
                            break;
                        case 'confirmed':
                            $data->where('household_statuses.status', 'Confirmed');
                            break;
                        default:
                            break;
                    }
                }
                if ($dateFilter) {
                    // filter by request_date or created_at 
                    $data->where(function($q) use ($dateFilter) {
                        $q->whereRaw('DATE(households.created_at) = ?', [$dateFilter])
                          ->orWhereRaw('households.request_date = ?', [$dateFilter]);
                    });
                }
    
                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {

                        $moveButton = "<a type='button' title='Start Working' class='moveEnergyRequest' data-id='".$row->id."'><i class='fa-solid fa-check text-success'></i></a>";
                        $detailsButton = "<a type='button' class='detailsHouseholdButton' data-bs-toggle='modal' data-bs-target='#householdDetails' data-id='".$row->id."'><i class='fa-solid fa-eye text-info'></i></a>";
                        $postponeButton = "<a type='button' title='Postpone' class='postponedEnergyRequest' data-id='".$row->id."'><i class='fa-solid fa-rotate-right text-warning'></i></a>";
                        $deleteButton = "<a type='button' class='deleteHousehold' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";

                        // Show move + view + postpone + delete for most users; else only view
                        if(Auth::guard('user')->user()->user_type_id != 7 && Auth::guard('user')->user()->user_type_id != 11 && Auth::guard('user')->user()->user_type_id != 8) {
                            return $moveButton . ' ' . $detailsButton . ' ' . $postponeButton . ' ' . $deleteButton;
                        }

                        return $detailsButton;
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
        $professions = Profession::where('is_archived', 0)->get();
        $energySystems = EnergySystemType::where('is_archived', 0)->get();
        $energySystemTypes = EnergySystemType::where('is_archived', 0)->get();
        $energyCycles = EnergySystemCycle::get();
        $regions = Region::where('is_archived', 0)
            ->orderBy('english_name', 'ASC')
            ->get(); 
        $energyTypes = EnergySystemType::where('is_archived', 0)->get();

        return view('employee.household.requested.create', compact('communities', 'regions', 
            'professions', 'energySystems', 'energyTypes', 'energyCycles'));
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
        $household->number_of_people = $request->number_of_male + $request->number_of_female;
        $household->energy_system_type_id = $request->recommendede_energy_system_id;
        $household->save();

        $id = $household->id;

        $requestedSystem = new EnergyRequestSystem();
        $requestedSystem->household_id = $id;
        $requestedSystem->date = Carbon::now(); 
        $requestedSystem->energy_service = "Yes";
        $requestedSystem->recommendede_energy_system_id = $request->recommendede_energy_system_id;
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

        return redirect('/requested-household')
            ->with('message', 'New Requested Household Added Successfully!');
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

        return view('employee.household.requested.edit', compact('household', 'regions', 'communities',
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
        $householdMeter = HouseholdMeter::where('user_name', $household->english_name)->first();

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
        $household->notes = $request->notes;
        $household->size_of_herd = $request->size_of_herd;
        if($request->electricity_source) $household->electricity_source = $request->electricity_source;
        if($request->electricity_source_shared) $household->electricity_source_shared = $request->electricity_source_shared;
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
        
        return redirect('/requested-household')
            ->with('message', 'Requested Household Updated Successfully!');
    }

}
