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
use App\Models\EnergyUser;
use App\Models\InstallationType;
use App\Models\EnergyHolder;
use App\Models\EnergyPublicStructure;
use App\Models\MeterCase;
use Carbon\Carbon;
use DataTables;
use mikehaertl\wkhtmlto\Pdf;

class InProgressHouseholdController extends Controller
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
                    ->where('households.household_status_id', 3)
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
    
                        $detailsButton = "<a type='button' class='detailsHouseholdButton' data-bs-toggle='modal' data-bs-target='#householdDetails' data-id='".$row->id."'><i class='fa-solid fa-eye text-primary'></i></a>";
                        $updateButton = "<a type='button' class='updateHousehold' data-id='".$row->id."'><i class='fa-solid fa-pen-to-square text-success'></i></a>";
                        $deleteButton = "<a type='button' class='deleteHousehold' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";
                        
                        if(Auth::guard('user')->user()->user_type_id == 1 || 
                            Auth::guard('user')->user()->user_type_id == 2 || 
                            Auth::guard('user')->user()->user_type_id == 3 || 
                            Auth::guard('user')->user()->user_type_id == 4 || 
                            Auth::guard('user')->user()->user_type_id == 12 ) 
                        {
                            return $detailsButton." ". $updateButton." ".$deleteButton;
                        } else return $detailsButton;

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
                ->where('households.household_status_id', 3)
                ->where('households.is_archived', 0)
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
    
            return view('employee.household.progress', compact('communities', 'households', 
                'energySystems', 'energySystemTypes', 'meters', 'professions'))
                ->with('communityAcHouseholdsData', json_encode($arrayAcHouseholdsByCommunity));
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
        $installationTypes = InstallationType::where('is_archived', 0)->get();
        $energySystemTypes = EnergySystemType::where('is_archived', 0)->get();
        $households = Household::where('is_archived', 0)
            ->orderBy('english_name', 'ASC')
            ->get();
        $professions  = Profession::where('is_archived', 0)->get();


        return view('employee.household.elc_create', compact('communities', 'energySystemTypes', 
            'households', 'professions', 'installationTypes'));
    }

     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { 
       // dd($request->all()); 
        if($request->household_id) {

            for($i=0; $i < count($request->household_id); $i++) {

                $household = Household::findOrFail($request->household_id[$i]);
                $household->household_status_id = 3;
                $household->save(); 

                $energyUser = new AllEnergyMeter();
                $energyUser->installation_type_id = $request->misc;
                $energyUser->household_id = $request->household_id[$i];
                $energyUser->community_id = $request->community_id;
                $energyUser->energy_system_type_id = $request->energy_system_type_id;
                $energyUser->energy_system_id = $request->energy_system_id;
                $energyUser->meter_number = 0;
                $energyUser->meter_case_id = 12;
                $energyUser->save();

                $community = Community::findOrFail($request->community_id);
                if($community->community_status_id == 1) {

                    $community->community_status_id = 3;
                    $community->save();
                }
            }
        }
     
        return redirect('/progress-household')
            ->with('message', 'New Elc. Added Successfully!');
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

        return view('employee.household.progress.edit', compact('household', 'regions', 'communities',
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
        
        return redirect('/progress-household')
            ->with('message', 'In Progress Household Updated Successfully!');
    }
}
