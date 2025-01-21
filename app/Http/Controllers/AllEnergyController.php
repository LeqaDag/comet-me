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
use App\Models\AllEnergyMeterHistoryCase; 
use App\Models\AllEnergyMeterDonor;
use App\Models\AllEnergyVendingMeter;
use App\Models\AllEnergyMeterPhase;
use App\Models\ElectricityCollectionBox;
use App\Models\ElectricityPhase;
use App\Models\User;
use App\Models\Community;
use App\Models\CommunityDonor;
use App\Models\CompoundHousehold;
use App\Models\CommunityVendor; 
use App\Models\CommunityService;
use App\Models\Donor;
use App\Models\DisplacedHousehold;
use App\Models\DisplacedHouseholdStatus;
use App\Models\EnergyDonor;
use App\Models\EnergySystem;
use App\Models\EnergySystemType;
use App\Models\EnergyUser;
use App\Models\EnergyHolder;
use App\Models\EnergyPublicStructure;
use App\Models\EnergyPublicStructureDonor;
use App\Models\Household;
use App\Models\HouseholdMeter;
use App\Models\InstallationType;
use App\Models\MeterCase;
use App\Models\ServiceType;
use App\Models\PublicStructure;
use App\Models\PublicStructureCategory;
use App\Models\Region;
use App\Models\VendorUserName;
use App\Models\EnergySystemCycle;
use App\Exports\AllEnergyExport;
use App\Exports\PurchaseEnergyExport;
use App\Imports\PurchaseEnergyImport;
use App\Helpers\SequenceHelper;
use Carbon\Carbon;
use Image;
use DataTables;
use Excel;

class AllEnergyController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
   
        // $allEnergyMeters = AllEnergyMeter::where('is_archived', 0)
        //     ->whereNotNull('household_id')
        //     ->where('community_id', 187) // 179
        //     ->get();

        // foreach($allEnergyMeters as $allEnergyMeter) {

        //     $allEnergyMeter->energy_system_cycle_id = 1;
        //     $allEnergyMeter->save();

        //     $household = Household::where('id', $allEnergyMeter->household_id)->first();

        //     if($household) {

        //         $household->energy_system_cycle_id = 1;
        //         $household->save();
        //     }
        // }

      //  /* Comparasion between platform and vending software
        // $allEnergyMeters = AllEnergyMeter::where("is_archived", 0)->get();
        // $allVendingMeters = AllEnergyVendingMeter::get();

        // foreach($allEnergyMeters as $allEnergyMeter) {

        //     foreach($allVendingMeters as $allVendingMeter)  {

                
        //         if($allEnergyMeter->meter_number == $allVendingMeter->meter_number) {

        //             // $allVendingMeter->community_id = $allEnergyMeter->community_id;

        //             if($allEnergyMeter->household_id) {

        //                 $allVendingMeter->household_id = $allEnergyMeter->household_id;
        //             }
        //             if($allEnergyMeter->public_structure_id) {

        //                 $allVendingMeter->public_structure_id = $allEnergyMeter->public_structure_id;
        //             }

        //             $allVendingMeter->save();

        //             // $allEnergyMeter->last_purchase_date = $allVendingMeter->last_purchase_date;
        //             // $allEnergyMeter->meter_case_id = $allVendingMeter->meter_case_id;
        //             // $allEnergyMeter->meter_notes = $allVendingMeter->notes;
        //             // $allEnergyMeter->save();

        //         }
        //     }
        // }
       // End comparasion*/

        // $allMeters = AllEnergyMeter::get();
        // $allDuplicatedMeters = DB::table('all_energy_meters')
        //     ->select('meter_number', DB::raw('COUNT(*) as `count`'))
        //     ->groupBy('meter_number', )
        //     ->havingRaw('COUNT(*) > 1')
        //     ->get();

        // die($allDuplicatedMeters);

        // $allUsers = AllEnergyMeter::where("installation_type_id", 2)->get();
        

        // foreach($allUsers as $allUser) {

        //     $sharedUser = HouseholdMeter::where('household_id', $allUser->household_id)->first();
            
        //     if($sharedUser) {

        //         $sharedEnergy = AllEnergyMeter::where("household_id", $sharedUser)->first();
        //         $sharedEnergy->installation_type_id = 2;
        //         $sharedEnergy->save();
        //     }
        // }


        // $subUsers = AllEnergyMeter::where("is_main", "No")->get();
        
        // foreach($subUsers as $subUser) {

        //     $mainUser = HouseholdMeter::where('household_id', $subUser->household_id)->first();
            
        //     $energyUser = AllEnergyMeter::where('id', $mainUser->energy_user_id)->first();
            
        //     $subUser->installation_type_id = $energyUser->installation_type_id;
        //     $subUser->save();
        // }

        // $energyUsers = AllEnergyMeter::where("energy_system_type_id", 2)->get();

        // foreach($energyUsers as $energyUser) {

        //     $energyUser->ground_connected = "No";
        //     $energyUser->save();
        // }

        // $energyUsers = AllEnergyMeter::where("energy_system_type_id", 1)
        //     ->orWhere("energy_system_type_id", 3)
        //     ->orWhere("energy_system_type_id", 4)
        //     ->get();

        // foreach($energyUsers as $energyUser) {

        //     $energyUser->ground_connected = "Yes";
        //     $energyUser->save();
        // }

        
        // add the donors for users if it's exist in community-donors 
        $missingUserEnergDonors = DB::table('all_energy_meters')
            ->join('communities', 'all_energy_meters.community_id', 'communities.id')
            ->join('energy_systems', 'all_energy_meters.energy_system_id', 'energy_systems.id')
            ->leftJoin('energy_system_types', 'all_energy_meters.energy_system_type_id', 
                'energy_system_types.id')
            ->leftJoin('all_energy_meter_donors', 'all_energy_meters.id', 
                'all_energy_meter_donors.all_energy_meter_id')
            ->join('households', 'households.id', 
                'all_energy_meters.household_id')
            ->join('household_statuses', 'households.household_status_id', 'household_statuses.id')
            ->whereNull('all_energy_meter_donors.all_energy_meter_id')
            ->where('all_energy_meters.energy_system_id', '!=', 100)
            ->select(
                'communities.english_name as community', 'all_energy_meters.meter_number',
                'households.english_name as household_name', 'household_statuses.status',
                'energy_systems.name as energy_name', 'energy_system_types.name as type',
                'households.id as id', 'all_energy_meters.id as all_energy_meter_id',
                'communities.id as community_id'
                )
            ->get();

        //dd($missingUserEnergDonors->count());
        if(count($missingUserEnergDonors) > 0) {

            foreach($missingUserEnergDonors as $missingUserEnergDonor) {

                $compoundHousehold = CompoundHousehold::where('household_id', $missingUserEnergDonor->id)->first();
                if($compoundHousehold) {

                    $compoundDonors = CommunityDonor::where('compound_id', $compoundHousehold->compound_id)
                        ->where('service_id', 1)
                        ->get();

                    if($compoundDonors) {

                        foreach($compoundDonors as $compoundDonor) {
                                
                            $newAllEnergyCompoundMeterDonor = new AllEnergyMeterDonor();
                            $newAllEnergyCompoundMeterDonor->compound_id = $compoundDonor->compound_id;
                            $newAllEnergyCompoundMeterDonor->all_energy_meter_id = $missingUserEnergDonor->all_energy_meter_id;
                            $newAllEnergyCompoundMeterDonor->donor_id = $compoundDonor->donor_id;
                            $newAllEnergyCompoundMeterDonor->save();
                        }
                    }
                }
                $communityDonors = CommunityDonor::where('community_id', $missingUserEnergDonor->community_id)
                    ->where('service_id', 1)
                    ->get();

                if($communityDonors) {

                    foreach($communityDonors as $communityDonor) {
                                
                        $newAllEnergyCommunityMeterDonor = new AllEnergyMeterDonor();
                        $newAllEnergyCommunityMeterDonor->community_id = $communityDonor->community_id;
                        $newAllEnergyCommunityMeterDonor->all_energy_meter_id = $missingUserEnergDonor->all_energy_meter_id;
                        $newAllEnergyCommunityMeterDonor->donor_id = $communityDonor->donor_id;
                        $newAllEnergyCommunityMeterDonor->save();
                    }
                } 
            }
        }

        $communityFilter = $request->input('community_filter');
        $typeFilter = $request->input('type_filter');
        $dateFilter = $request->input('date_filter');
        $yearFilter = $request->input('year_filter');
        $meterFilter = $request->input('meter_filter');
        $regionFilter = $request->input('region_filter');
        $energyTypeFilter = $request->input('system_type_filter');

        if (Auth::guard('user')->user() != null) {

            if ($request->ajax()) {  
 
                $data = DB::table('all_energy_meters')
                    ->join('communities', 'all_energy_meters.community_id', 'communities.id')
                    ->join('households', 'all_energy_meters.household_id', 'households.id')
                    ->join('energy_systems', 'all_energy_meters.energy_system_id', 'energy_systems.id')
                    ->join('energy_system_types', 'all_energy_meters.energy_system_type_id', 'energy_system_types.id')
                    ->join('meter_cases', 'all_energy_meters.meter_case_id', 'meter_cases.id')
                    ->leftJoin('household_meters', 'household_meters.energy_user_id', 
                        'all_energy_meters.id')
                    ->leftJoin('households as shared_households', 'shared_households.id', 
                        'household_meters.household_id')
                    ->where('all_energy_meters.is_archived', 0);
     
                if($regionFilter != null) {

                    $data->where('communities.region_id', $regionFilter);
                }
                if($communityFilter != null) {

                    $data->where('communities.id', $communityFilter);
                }
                if ($typeFilter != null) {

                    $data->where('all_energy_meters.installation_type_id', $typeFilter);
                }
                if ($energyTypeFilter != null) {

                    $data->where('energy_system_types.id', $energyTypeFilter);
                }
                if ($meterFilter != null) {

                    $data->where('meter_cases.id', $meterFilter);
                }
                if ($yearFilter != null) {

                    $data->whereYear('all_energy_meters.installation_date', $yearFilter);
                }
                if ($dateFilter != null) {

                    $data->where('all_energy_meters.installation_date', '>=', $dateFilter);
                }

                $data->select(
                    'all_energy_meters.meter_number', 'all_energy_meters.meter_active',
                    'all_energy_meters.id as id', 'all_energy_meters.created_at as created_at', 
                    'all_energy_meters.updated_at as updated_at', 
                    'communities.english_name as community_name',
                    'households.english_name as household_name',
                    'energy_systems.name as energy_name', 
                    'energy_system_types.name as energy_type_name',
                    'meter_cases.meter_case_name_english',
                    'all_energy_meters.is_main')
                ->latest()
                ->distinct();

                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
                        $viewButton = "<a type='button' class='viewEnergyUser' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#viewEnergyUserModal' ><i class='fa-solid fa-eye text-info'></i></a>";
                        $updateButton = "<a type='button' class='updateAllEnergyUser' data-id='".$row->id."' ><i class='fa-solid fa-pen-to-square text-success'></i></a>";
                        $deleteButton = "<a type='button' class='deleteAllEnergyUser' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";
                        
                        if(Auth::guard('user')->user()->user_type_id == 1 || 
                            Auth::guard('user')->user()->user_type_id == 2 ||
                            Auth::guard('user')->user()->user_type_id == 3 ||
                            Auth::guard('user')->user()->user_type_id == 4 ||
                            Auth::guard('user')->user()->user_type_id == 12 ||
                            Auth::guard('user')->user()->role_id == 21) 
                        {
                                
                            return $viewButton." ". $updateButton." ".$deleteButton;
                        } else return $viewButton;

                    })
                    ->addColumn('checkbox', function($row) {

                        return "<input type='checkbox' class='record-checkbox' data-id='".$row->id."'>";
                    })
                    ->addColumn('icon', function($row) {

                        $icon = "<i class='fa-solid fa-check text-success'></i>";

                        if($row->is_main == "Yes") $icon = "<i class='fa-solid fa-check text-success'></i>";
                        else if($row->is_main == "No") $icon = "<i class='fa-solid fa-close text-danger'></i>";

                        return $icon;
                    })
                    ->filter(function ($instance) use ($request) {
                        if (!empty($request->get('search'))) {
                                $instance->where(function($w) use($request) {
                                $search = $request->get('search');
                                $w->orWhere('communities.english_name', 'LIKE', "%$search%")
                                ->orWhere('all_energy_meters.is_main', 'LIKE', "%$search%")
                                ->orWhere('households.english_name', 'LIKE', "%$search%")
                                ->orWhere('shared_households.english_name', 'LIKE', "%$search%")
                                ->orWhere('shared_households.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('communities.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('households.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('energy_systems.name', 'LIKE', "%$search%")
                                ->orWhere('energy_system_types.name', 'LIKE', "%$search%")
                                ->orWhere('all_energy_meters.meter_number', 'LIKE', "%$search%")
                                ->orWhere('meter_cases.meter_case_name_english', 'LIKE', "%$search%")
                                ->orWhere('meter_cases.meter_case_name_arabic', 'LIKE', "%$search%");
                            });
                        }
                    })
                    ->rawColumns(['checkbox', 'action', 'icon'])
                    ->make(true);
            }
            
            $data = DB::table('all_energy_meters')
                ->join('meter_cases', 'all_energy_meters.meter_case_id', '=', 'meter_cases.id')
                ->where('meter_cases.meter_case_name_english', '!=', "Used")
                ->where('household_id', '!=', 0)
                ->where('all_energy_meters.is_archived', 0)
                ->select(
                        DB::raw('meter_cases.meter_case_name_english as name'),
                        DB::raw('count(*) as number'))
                ->groupBy('meter_cases.meter_case_name_english')
                ->get();
    
              
            $array[] = ['Meter Case', 'Total'];
            
            foreach($data as $key => $value) {
    
                $array[++$key] = [$value->name, $value->number];
            }
    
            $communities = Community::where('is_archived', 0)
                ->orderBy('english_name', 'ASC')
                ->get();
            $regions = Region::where('is_archived', 0)
                ->orderBy('english_name', 'ASC')
                ->get();
            $meterCases = MeterCase::where('is_archived', 0)->get();
            $energySystemTypes = EnergySystemType::where('is_archived', 0)->get();
            $installationTypes = InstallationType::where('is_archived', 0)->get();
 
            return view('users.energy.not_active.index', compact('communities', 'energySystemTypes', 
                'meterCases', 'installationTypes', 'regions'))
                ->with('energy_users', json_encode($array)
            );
        } else {

            return view('errors.not-found');
        }
    }

    /**
     * Get a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getEnergyUserData(int $id)
    {
        $energy = AllEnergyMeter::find($id);
        $meterCase = MeterCase::where("id", $energy->meter_case_id)->first();
        $response = array();

        if(!empty($energy)) {

            $response['meter_number'] = $energy->meter_number;
            $response['daily_limit'] = $energy->daily_limit;
            $response['installation_date'] = $energy->installation_date;
            $response['notes'] = $energy->notes;
            $response['meter_active'] = $energy->meter_active;
            $response['meter_case_id'] = $meterCase->meter_case_name_english;
            $response['id'] = $energy->id;

            $response['success'] = 1;
        } else {

            $response['success'] = 0;
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
        $energyUser = AllEnergyMeter::findOrFail($id);

        return response()->json($energyUser);
    }

    /**
     * View Edit page.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $energyUser = AllEnergyMeter::findOrFail($id);

        $energyDonors = AllEnergyMeterDonor::where("all_energy_meter_id", $id)
            ->where("is_archived", 0)
            ->get();
        $community_id = Community::findOrFail($energyUser->community_id);
        $communities = Community::where('is_archived', 0)
            ->orderBy('english_name', 'ASC')
            ->get();
        $communityVendors = DB::table('community_vendors')
            //->where('community_id', $community_id->id)
            ->where('community_vendors.is_archived', 0)
            ->join('vendor_user_names', 'community_vendors.vendor_username_id', 
                '=', 'vendor_user_names.id')
            ->select('vendor_user_names.name', 'community_vendors.id as id',
                'vendor_user_names.id as vendor_username_id')
            ->groupBy('vendor_user_names.id')
            ->get();
        
        $energySystems = EnergySystem::where('is_archived', 0)->get();
        $household = Household::findOrFail($energyUser->household_id);
        $meterCases = MeterCase::where('is_archived', 0)->get();
        $vendor = VendorUserName::where('id', $energyUser->vendor_username_id)->first();
        $donors = Donor::where('is_archived', 0)->get();
        $installationTypes = InstallationType::where('is_archived', 0)->get();
        $energyCycles = EnergySystemCycle::get();

        $electricityCollectionBoxes = ElectricityCollectionBox::where('is_archived', 0)->get();
        $electricityPhases = ElectricityPhase::where('is_archived', 0)->get();
        $allEnergyMeterPhase = AllEnergyMeterPhase::where('is_archived', 0)
            ->where('all_energy_meter_id', $id)
            ->first();

        return view('users.energy.not_active.edit_energy', compact('household', 'communities',
            'meterCases', 'energyUser', 'communityVendors', 'vendor', 'energySystems', 'electricityPhases',
            'energyDonors', 'donors', 'installationTypes', 'energyCycles', 'electricityCollectionBoxes',
            'allEnergyMeterPhase'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateEnergyDonorData(Request $request) 
    {
        $energyDonors = DB::table('energy_donors')
            ->join('communities', 'energy_donors.community_id', '=', 'communities.id')
            ->join('households', 'energy_donors.household_id', '=', 'households.id')
            ->join('donors', 'energy_donors.donor_id', '=', 'donors.id')
            ->where('energy_donors.household_id', $energyUser->household_id)
            ->select('energy_donors.id as id', 'communities.english_name as community_name',
                'households.english_name as household_name',
                'donors.donor_name as donor_name', 'energy_donors.household_id',
                'donors.id as donor_id', 'energy_donors.community_id')
            ->get();
        
        // if($request->donor_id) {
        //     foreach($request->donor_id as $donorId) {
        //         $donorUser = EnergyDonor::findOrFail($energyDonor->id);
        //         $donorUser->donor_id = $donorId;
        //         $donorUser->save();
        //     }
        // }

        if($request->donors) {
            foreach($request->donors as $donorId) {
                EnergyDonor::create([
                    'donor_id' => $donorId,
                    'community_id' => $energyUser->community_id,
                    'household_id' => $energyUser->household_id,
                ]);
            }
        }

        return response()->json(['success'=> 'Energy User updated successfully!']);
    }

    /**
     * Get a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getEnergyUserDonors(int $id)
    {
        $energyDonors = AllEnergyMeterDonor::where("all_energy_meter_id", $id)->get();

        return response()->json($energyDonors);
    }

    /** 
     * View Edit page.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function editDonor($id)
    {
        $energyDonors = AllEnergyMeterDonor::where("all_energy_meter_id", $id);

        return view('users.energy.not_active.donor_edit', compact('energyDonors'));
    }
 
    /**
     * Update an existing resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request, int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $existMeterEnergyHolder = AllEnergyMeter::where("meter_number", $request->meter_number)
            ->where('id', "!=", $id)
            ->first();

        if ($existMeterEnergyHolder) {
            
            $holder = null;

            if($existMeterEnergyHolder->household_id) $holder = Household::findOrFail($existMeterEnergyHolder->household_id);
            else if($existMeterEnergyHolder->public_structure_id) $holder = PublicStructure::findOrFail($existMeterEnergyHolder->public_structure_id);

            return redirect()->back()->with('error', 'Attension, this meter number ' . $request->meter_number . ' is already exist for '. $holder->english_name);
        }
  
        $energyUser = AllEnergyMeter::find($id);

        $oldMeterCase = $energyUser->meter_case_id;

        if($request->energy_system_cycle_id) {

            $energyUser->energy_system_cycle_id = $request->energy_system_cycle_id;

            $householdUser = Household::where("id", $energyUser->household_id)->first();
            if($householdUser) {

                $householdUser->energy_system_cycle_id = $request->energy_system_cycle_id;
                $householdUser->save();
            }
        }

        if($energyUser->household_id) {

            $displacedHousehold = DisplacedHousehold::where('household_id', 
                $energyUser->household_id)->first();
                
            if($displacedHousehold) {
                
                if($request->community_id) {
    
                    $community = Community::findOrFail($request->community_id);
                    $displacedHousehold->new_community_id = $request->community_id;
                    $displacedHousehold->sub_region_id = $community->sub_region_id;
                }
                if($request->energy_system_id) $displacedHousehold->new_energy_system_id = $request->energy_system_id;
                if($request->meter_number) $displacedHousehold->new_meter_number = $request->meter_number;
                $displacedHousehold->displaced_household_status_id = 4;
                $displacedHousehold->system_retrieved = "Yes";
                $displacedHousehold->save();
            }
        }

        // This code is for updating the fake_meter_numbers for the shared ones if the main meter number is changed
        if($request->meter_number) {

            $energyUser->meter_number = $request->meter_number;

            $sharedEnergyUsers = DB::table('household_meters')
                ->leftJoin('households', 'household_meters.household_id', 'households.id')
                ->leftJoin('public_structures', 'household_meters.public_structure_id', 
                    'public_structures.id')
                ->join('all_energy_meters', 'household_meters.energy_user_id', 'all_energy_meters.id')
                ->leftJoin('all_energy_meters as shared_energy_users', 'shared_energy_users.household_id', 'households.id')
                ->join('households as main_users', 'all_energy_meters.household_id', 'main_users.id')
                ->where('household_meters.is_archived', 0)
                ->where('all_energy_meters.id', $id)
                ->select(
                    DB::raw('IFNULL(shared_energy_users.fake_meter_number, public_structures.fake_meter_number) 
                        as fake_meter_number'),
                    'all_energy_meters.meter_number', 'main_users.id as main_user_id',
                    'households.id as shared_household_id', 'public_structures.id as shared_public_id'
                    )
                ->distinct()
                ->get();
                
            foreach($sharedEnergyUsers as $sharedEnergyUser) {
                
                $newFakeMeterNumber = null;
                if($sharedEnergyUser->fake_meter_number) $newFakeMeterNumber = SequenceHelper::updateSequence($sharedEnergyUser->fake_meter_number, $request->meter_number); 

                else {

                    $newFakeMeterNumber = SequenceHelper::generateSequence($sharedEnergyUser->meter_number, $incrementalNumber);
                }

                $exist = AllEnergyMeter::where('fake_meter_number', $newFakeMeterNumber)->first();
    
                if($exist) {
                } else {

                    $allEnergyMeter = null;
                    if($sharedEnergyUser->shared_household_id) {
                        
                        $allEnergyMeter = AllEnergyMeter::where("is_archived", 0)
                            ->whereNull("meter_number")
                            ->where("household_id", $sharedEnergyUser->shared_household_id)
                            ->first();
                    } else if($sharedEnergyUser->shared_public_id) {

                        $allEnergyMeter = AllEnergyMeter::where("is_archived", 0)
                            ->whereNull("meter_number")
                            ->where("public_structure_id", $sharedEnergyUser->shared_public_id)
                            ->first();
                    }
                    if($allEnergyMeter) {

                        $allEnergyMeter->fake_meter_number = $newFakeMeterNumber;
                        $allEnergyMeter->save();
                    }
                }

            }
        }

        $energyUser->daily_limit = $request->daily_limit;
        $energyUser->installation_date = $request->installation_date;
        if($request->installation_type_id) $energyUser->installation_type_id = $request->installation_type_id;
        if($request->ground_connected) $energyUser->ground_connected = $request->ground_connected;
        if($request->meter_active) $energyUser->meter_active = $request->meter_active;
        if($request->vendor_username_id) $energyUser->vendor_username_id = $request->vendor_username_id;

        if($request->energy_system_id) $energyUser->energy_system_id = $request->energy_system_id;
        
        if($request->meter_case_id) {

            $energyUser->meter_case_id = $request->meter_case_id;

            if($request->meter_case_id != $oldMeterCase) {

                $meterCaseHistory = new AllEnergyMeterHistoryCase();
                $meterCaseHistory->old_meter_case_id = $oldMeterCase;
                $meterCaseHistory->new_meter_case_id = $request->meter_case_id;
                $meterCaseHistory->all_energy_meter = $id;
                if($request->last_update_date) $meterCaseHistory->last_update_date = $request->last_update_date;
                $meterCaseHistory->save();
            }
        }
        
        if($request->community_id) $energyUser->community_id = $request->community_id;

        if($request->meter_case_id == 1 || $request->meter_case_id == 2 ||
            $request->meter_case_id == 3 || $request->meter_case_id == 4 ||
            $request->meter_case_id == 5 || $request->meter_case_id == 6 ||
            $request->meter_case_id == 7 || $request->meter_case_id == 8 ||
            $request->meter_case_id == 9 || $request->meter_case_id == 10 ||
            $request->meter_case_id == 11 || $request->meter_case_id == 12 ||
            $request->meter_case_id == 13 || $request->meter_case_id == 14) 
        {

            $household = Household::findOrFail($energyUser->household_id);
            $household->household_status_id = 4;
            $household->energy_service = "Yes";
            $household->energy_system_status = "Served";
            $household->energy_meter = "Yes";
            $household->save();
            
            $energyUser->meter_case_id = $request->meter_case_id;
        }

        $energyUser->save(); 
        
        if($energyUser->meter_active == "Yes" || $energyUser->meter_case_id == 1) {

            $householdMeters = HouseholdMeter::where('energy_user_id', $energyUser->id)
                ->where('is_archived', 0)
                ->get();

            if($householdMeters != []) {
                foreach($householdMeters as $householdMeter) {

                    $household = Household::findOrFail($householdMeter->household_id);
                    $household->household_status_id = 4;
                    $household->energy_system_status = "Served";
                    $household->save();
                }
            } 
        }

        if($request->donors) {

            $householdMeters = HouseholdMeter::where('energy_user_id', $energyUser->id)
                ->where('is_archived', 0)
                ->get();
            
            foreach($householdMeters as $householdMeter) {

                $allEnergyMeter = AllEnergyMeter::where('household_id', $householdMeter->household_id)->first();
                for($i=0; $i < count($request->donors); $i++) {

                    $energyMeterDonor = new AllEnergyMeterDonor();
                    $energyMeterDonor->donor_id = $request->donors[$i];
                    $energyMeterDonor->all_energy_meter_id = $allEnergyMeter->id;
                    $energyMeterDonor->community_id = $energyUser->community_id;
                    $energyMeterDonor->save();
                }
            }

            for($i=0; $i < count($request->donors); $i++) {

                $energyMeterDonor = new AllEnergyMeterDonor();
                $energyMeterDonor->donor_id = $request->donors[$i];
                $energyMeterDonor->all_energy_meter_id = $id;
                $energyMeterDonor->community_id = $energyUser->community_id;
                $energyMeterDonor->save();
            }
        }

        if($request->new_donors) {
            $householdMeters = HouseholdMeter::where('energy_user_id', $energyUser->id)
                ->where('is_archived', 0)
                ->get();
            
            foreach($householdMeters as $householdMeter) {

                $allEnergyMeter = AllEnergyMeter::where('household_id', $householdMeter->household_id)->first();
                for($i=0; $i < count($request->donors); $i++) {

                    $energyMeterDonor = new AllEnergyMeterDonor();
                    $energyMeterDonor->donor_id = $request->donors[$i];
                    $energyMeterDonor->all_energy_meter_id = $allEnergyMeter->id;
                    $energyMeterDonor->community_id = $energyUser->community_id;
                    $energyMeterDonor->save();
                }
            }

            for($i=0; $i < count($request->new_donors); $i++) {

                $energyMeterDonor = new AllEnergyMeterDonor();
                $energyMeterDonor->donor_id = $request->new_donors[$i];
                $energyMeterDonor->all_energy_meter_id = $id;
                $energyMeterDonor->community_id = $energyUser->community_id;
                $energyMeterDonor->save();
            }
        }

        // CI & PH
        if($request->electricity_collection_box_id || $request->electricity_phase_id) {

            $existingEnergyMeterPhase = AllEnergyMeterPhase::where("all_energy_meter_id", $id)->first();
            if($existingEnergyMeterPhase) {

                $existingEnergyMeterPhase->electricity_collection_box_id = $request->electricity_collection_box_id;
                $existingEnergyMeterPhase->electricity_phase_id = $request->electricity_phase_id;
                $existingEnergyMeterPhase->save();
            } else {

                $allEnergyMeterPhase = new AllEnergyMeterPhase();
                $allEnergyMeterPhase->all_energy_meter_id = $id;
                $allEnergyMeterPhase->electricity_collection_box_id = $request->electricity_collection_box_id;
                $allEnergyMeterPhase->electricity_phase_id = $request->electricity_phase_id;
                $allEnergyMeterPhase->save();
            }
        }

        return redirect('/all-meter')->with('message', 'Energy User Updated Successfully!');
    }

    /**
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteEnergyUser(Request $request)
    {
        $id = $request->id;

        $user = AllEnergyMeter::find($id);
        $sharedMeters = HouseholdMeter::where("energy_user_id", $id)->get();

        if($sharedMeters) {
            foreach($sharedMeters as $sharedMeter) {
                $sharedMeter->is_archived = 1;
                $sharedMeter->save();
            }
        }

        if($user) {

            $user->is_archived = 1;
            $user->save();

            $response['success'] = 1;
            $response['msg'] = 'Energy User Deleted successfully'; 
        } else {

            $response['success'] = 0;
            $response['msg'] = 'Invalid ID.';
        }

        return response()->json($response); 
    }

    /**
     * Check the meter number 
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function checkMeterNumber(Request $request)
    {
        //die($request);
        // Validate the input
        $request->validate([

            'meter_number' => 'required|digits:11|unique:all_energy_meters,meter_number',
        ], [

            'meter_number.digits' => 'The meter number must be exactly 11 digits.',
            'meter_number.unique' => 'This meter number already exists in the database.',
        ]);

        // Return a JSON response with success message
        return response()->json([
            'success' => true,
            'message' => 'Meter number updated successfully.'
        ]);
    }

    /**
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteEnergyDonor(Request $request)
    {
        $id = $request->id;
        $mainEnergyDonor = AllEnergyMeterDonor::findOrFail($id);

        $user = AllEnergyMeterDonor::find($id);
        $sharedMeters = HouseholdMeter::where("energy_user_id", $user->all_energy_meter_id)->get();
        
        if($user) {

            $user->delete(); 

            if($sharedMeters) {
                foreach($sharedMeters as $sharedMeter) {

                    $sharedEnergyMeter = AllEnergyMeter::where("household_id", $sharedMeter->household_id)->first();
                    $sharedDonor = AllEnergyMeterDonor::where("all_energy_meter_id", $sharedEnergyMeter->id)
                        ->where('donor_id', $mainEnergyDonor->donor_id)
                        ->first();
        
                    $sharedDonor->delete();
                }
            }
            
            $response['success'] = 1;
            $response['msg'] = 'Energy Donor Deleted successfully'; 
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

        return Excel::download(new AllEnergyExport($request), 'energy_meters_summary.xlsx');
    }

    /**
     * 
     * @return \Illuminate\Support\Collection
     */
    public function import(Request $request) 
    {
        try {
            if (DB::transactionLevel() > 0) {
                DB::commit();
            }
            
            AllEnergyVendingMeter::truncate();
    
        } catch (\Exception $e) {
            
            DB::rollBack();
        }

        try {

            Excel::import(new PurchaseEnergyImport(1), $request->file('first_file'));

            return Excel::download(new PurchaseEnergyExport($request), 'purchase_report.xlsx');

            return back()->with('success', 'Purchase Report Exported successfully!');
        } catch (\Exception $e) {
           
            return back()->with('error', 'Error occurred during import: ' . $e->getMessage());
        }
    }
}
