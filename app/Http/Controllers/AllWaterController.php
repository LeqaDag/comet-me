<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\AllEnergyMeter;
use App\Models\AllWaterHolder;
use App\Models\AllWaterHolderDonor;
use App\Models\User;
use App\Models\BsfStatus;
use App\Models\Donor;
use App\Models\Community;
use App\Models\CommunityWaterSource;
use App\Models\CommunityService;
use App\Models\GridUser;
use App\Models\GridUserDonor;
use App\Models\GridPublicStructure;
use App\Models\GridSharedUser;
use App\Models\H2oSharedUser;
use App\Models\H2oStatus;
use App\Models\H2oPublicStructure;
use App\Models\H2oUser;  
use App\Models\H2oUserDonor;
use App\Models\H2oSharedPublicStructure;
use App\Models\Household;
use App\Models\PublicStructure; 
use App\Models\WaterUser;
use App\Models\WaterNetworkUser;
use App\Models\EnergySystemType;
use App\Models\WaterSystemType;
use App\Exports\WaterUserExport; 
use Auth;
use DB;
use Route;
use DataTables;
use Excel;

class AllWaterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {	
        // This code is to delete the duplicated donors
        $allWaterDonors = AllWaterHolderDonor::all();

        foreach($allWaterDonors as $allWaterDonor) {

            $allWaterMeter = AllWaterHolder::findOrFail($allWaterDonor->all_water_holder_id);

            if($allWaterMeter) {

                $dupliatedDonors = AllWaterHolderDonor::where("all_water_holder_id", $allWaterMeter->id)
                    ->where("community_id", $allWaterMeter->community_id)
                    ->get();

                $uniqueDonors = [];

                foreach ($dupliatedDonors as $dupliatedDonor) {
                    
                    if (in_array($dupliatedDonor->donor_id, $uniqueDonors)) {

                        $dupliatedDonor->delete();
                    } else {

                        $uniqueDonors[] = $dupliatedDonor->donor_id;
                    }
                }
            }
        }


        $h2oFlag = AllWaterHolder::where("water_system_id", 1)->count();

        //dd($h2oFlag);
        // Add flag to users for type of system
        $allWaterHolders = AllWaterHolder::get();
        //dd($allWaterHolders);

        foreach($allWaterHolders as $allWaterHolder) {

            // $h2oUser = H2oUser::where("household_id", $allWaterHolder->household_id)->first();
            // $gridUser = GridUser::where("household_id", $allWaterHolder->household_id)->first();

            // if($gridUser) {
            //     if($allWaterHolder->water_system_id == 1) {

            //     } else if($gridUser->grid_integration_large >= 1) {

            //         $allWaterHolder->is_large_grid = 1;
            //         $allWaterHolder->water_system_id  = 2;
            //         $allWaterHolder->save();
            //     } else if($gridUser->grid_integration_small >= 1) {

            //         $allWaterHolder->is_small_grid = 1;
            //         $allWaterHolder->water_system_id  = 2;
            //         $allWaterHolder->save();
            //     }
            // } 
            // if($h2oUser) {
            //     $allWaterHolder->is_h2o = 1;
            //     $allWaterHolder->water_system_id  = 1;
            //     $allWaterHolder->save();
            // }
            // $h2oPublic = H2oPublicStructure::where("public_structure_id", $allWaterHolder->public_structure_id)->first();

            // if($h2oPublic) {
            //     $allWaterHolder->is_h2o = 1;
            //     $allWaterHolder->water_system_id  = 1;
            //     $allWaterHolder->save();
            // }
        }

        // $allDonors = H2oUserDonor::all();

        // foreach($allDonors as $allDonor) {

        //     $h2oUser = H2oUser::findOrFail($allDonor->h2o_user_id);
        //     $allWaterHolder = AllWaterHolder::where('household_id', $h2oUser->household_id)->first();

        //     $exist = AllWaterHolderDonor::where("id", $allWaterHolder->id)->first();
        //     if($exist) {
        //     } else {
        //          $allWaterHolderDonor = new AllWaterHolderDonor();
        //     $allWaterHolderDonor->donor_id = $allDonor->donor_id;
        //     $allWaterHolderDonor->community_id = $h2oUser->community_id;
        //     $allWaterHolderDonor->all_water_holder_id = $allWaterHolder->id;
        //     $allWaterHolderDonor->save();
        //     } 
        // }


        if (Auth::guard('user')->user() != null) {
 
            $filterValue = $request->input('filter');
            $secondFilterValue = $request->input('second_filter'); 
 
            if ($request->ajax()) {

                $data = DB::table('all_water_holders') 
                    ->join('communities', 'all_water_holders.community_id', 'communities.id')
                    ->LeftJoin('public_structures', 'all_water_holders.public_structure_id', 
                        'public_structures.id')
                    ->LeftJoin('households', 'all_water_holders.household_id', 'households.id')
                    ->LeftJoin('h2o_users', 'h2o_users.household_id', 'households.id')
                    ->LeftJoin('grid_users', 'h2o_users.household_id', 'grid_users.household_id')
                    ->leftJoin('water_network_users', 'households.id', 
                        'water_network_users.household_id')
                    ->LeftJoin('h2o_statuses', 'h2o_users.h2o_status_id', 'h2o_statuses.id')
                    ->leftJoin('h2o_shared_users', 'h2o_shared_users.h2o_user_id', 
                        'h2o_users.id')
                    ->leftJoin('households as shared_households', 'shared_households.id', 
                        'h2o_shared_users.household_id')
                    ->leftJoin('grid_shared_users', 'grid_shared_users.grid_user_id', 
                        'grid_users.id')
                    ->leftJoin('households as shared_grid_households', 'shared_grid_households.id', 
                        'grid_shared_users.household_id')
                    ->where('all_water_holders.is_archived', 0);
                
                if($filterValue != null) {

                    $data->where('communities.id', $filterValue);
                }
                if ($secondFilterValue != null) {

                    $data->where('all_water_holders.installation_year', $secondFilterValue);
                }

                $data->select(
                    'all_water_holders.id as id', 'households.english_name as household_name', 
                    'h2o_users.number_of_h20', 'grid_users.grid_integration_large', 
                    'grid_users.large_date', 'grid_users.grid_integration_small', 
                    'grid_users.small_date', 'grid_users.is_delivery', 'h2o_users.number_of_bsf', 
                    'grid_users.is_paid', 'communities.english_name as community_name', 
                    'grid_users.is_complete', 'all_water_holders.created_at as created_at',
                    'all_water_holders.installation_year', 'h2o_statuses.status',
                    'all_water_holders.updated_at as updated_at', 'all_water_holders.is_main',
                    'public_structures.english_name as public_name')
                ->latest();
              
                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
    
                        $updateButton = "<a type='button' class='updateWaterUser' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#updateWaterUserModal' ><i class='fa-solid fa-pen-to-square text-success'></i></a>";
                        $deleteButton = "<a type='button' class='deleteWaterUser' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";
                        $viewButton = "<a type='button' class='viewWaterUser' data-id='".$row->id."' ><i class='fa-solid fa-eye text-info'></i></a>";
    
                        if(Auth::guard('user')->user()->user_type_id == 1 || 
                            Auth::guard('user')->user()->user_type_id == 2 ||
                            Auth::guard('user')->user()->user_type_id == 5 ||
                            Auth::guard('user')->user()->user_type_id == 11) 
                        {
                                
                            return $viewButton." ". $updateButton." ".$deleteButton;
                        } else return $viewButton;
                    })
                    ->addColumn('holder', function($row) {

                        if($row->household_name != null) $holder = $row->household_name;
                        else if($row->public_name != null) $holder = $row->public_name;

                        return $holder;
                    })
                    ->addColumn('icon', function($row) {

                        $icon = "<i class='fa-solid fa-check text-success'></i>";

                        if($row->is_main == "Yes") $icon = "<i class='fa-solid fa-check text-success'></i>";
                        else if($row->is_main == "No") $icon = "<i class='fa-solid fa-close text-danger'></i>";

                        return $icon;
                    })
                    ->filter(function ($instance) use ($request) {
                        if (!empty($request->get('search'))) {
                                $instance->where(function($w) use($request){
                                $search = $request->get('search');
                                $w->orWhere('communities.english_name', 'LIKE', "%$search%")
                                ->orWhere('communities.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('households.english_name', 'LIKE', "%$search%")
                                ->orWhere('households.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('shared_households.english_name', 'LIKE', "%$search%")
                                ->orWhere('shared_households.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('shared_grid_households.english_name', 'LIKE', "%$search%")
                                ->orWhere('shared_grid_households.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('public_structures.english_name', 'LIKE', "%$search%")
                                ->orWhere('public_structures.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('h2o_statuses.status', 'LIKE', "%$search%")
                                ->orWhere('grid_users.grid_integration_large', 'LIKE', "%$search%")
                                ->orWhere('grid_users.grid_integration_small', 'LIKE', "%$search%");
                            });
                        }
                    })
                ->rawColumns(['action', 'icon', 'holder'])
                ->make(true);
            }
    
            $communities = Community::where('is_archived', 0)
                ->orderBy('english_name', 'ASC')
                ->get();
            $bsfStatus = BsfStatus::where('is_archived', 0)->get();
            $households = Household::where('is_archived', 0)
                ->orderBy('english_name', 'ASC')
                ->get();
            $h2oStatus = H2oStatus::where('is_archived', 0)->get();
    
            $data = DB::table('h2o_users')
                ->join('h2o_statuses', 'h2o_users.h2o_status_id', '=', 'h2o_statuses.id')
                ->where('h2o_users.is_archived', 0)
                ->select(
                        DB::raw('h2o_statuses.status as name'),
                        DB::raw('count(*) as number'))
                ->groupBy('h2o_statuses.status')
                ->get();
            
            $array[] = ['H2O Status', 'Total'];
            
            foreach($data as $key => $value) {
    
                $array[++$key] = [$value->name, $value->number];
            }
    
            $gridLarge = GridUser::where('grid_integration_large', '!=', 0)
                ->where('is_archived', 0)
                ->where('is_delivery', 'Yes')
                ->where('is_complete', 'Yes')
                ->selectRaw('SUM(grid_integration_large) AS sumLarge')
                ->first();
            $gridSmall = GridUser::where('grid_integration_small', '!=', 0)
                ->where('is_archived', 0)
                ->where('is_delivery', 'Yes')
                ->where('is_complete', 'Yes')
                ->selectRaw('SUM(grid_integration_small) AS sumSmall')
                ->first();
            
            $arrayGrid[] = ['Grid Integration', 'Total']; 
            
            for($key=0; $key <=2; $key++) {
                if($key == 1) $arrayGrid[$key] = ["Grid Large", $gridLarge->sumLarge];
                if($key == 2) $arrayGrid[$key] = ["Grid Small", $gridSmall->sumSmall];
            }

            $totalWaterHouseholds = Household::where("water_system_status", "Served")
                ->where('is_archived', 0)
                ->selectRaw('SUM(number_of_people) AS number_of_people')
                ->first();
            $totalWaterMale = Household::where("water_system_status", "Served")
                ->where('is_archived', 0)
                ->selectRaw('SUM(number_of_male) AS number_of_male')
                ->first(); 
            $totalWaterFemale = Household::where("water_system_status", "Served")
                ->where('is_archived', 0)
                ->selectRaw('SUM(number_of_female) AS number_of_female')
                ->first(); 
            $totalWaterAdults = Household::where("water_system_status", "Served")
                ->where('is_archived', 0)
                ->selectRaw('SUM(number_of_adults) AS number_of_adults')
                ->first();
            $totalWaterChildren = Household::where("water_system_status", "Served")
                ->where('is_archived', 0)
                ->selectRaw('SUM(number_of_children) AS number_of_children')
                ->first();
    
            $donors = Donor::where('is_archived', 0)->get();
            $energySystemTypes = EnergySystemType::where('is_archived', 0)->get();

            $h2oUsers = H2oUser::where('is_archived', 0)->count();
            $h2oSharedUsers = H2oSharedUser::where('is_archived', 0)->count();
            $gridUsers = GridUser::where('grid_integration_large', '!=', 0)
                ->orWhere('grid_integration_small', '!=', 0)
                ->where('is_archived', 0)
                ->where('is_delivery', 'Yes')
                ->where('is_complete', 'Yes')
                ->count();
            $networkUsers = WaterNetworkUser::where('is_archived', 0)->count();
            $waterSystemTypes = WaterSystemType::get();

            return view('users.water.all.index', compact('communities', 'bsfStatus', 'households', 
                'h2oStatus', 'totalWaterHouseholds', 'totalWaterMale', 'totalWaterFemale',
                'totalWaterChildren', 'totalWaterAdults', 'donors', 'energySystemTypes',
                'h2oUsers', 'gridUsers', 'networkUsers', 'h2oSharedUsers', 'waterSystemTypes'))
            ->with('h2oChartStatus', json_encode($array))
            ->with('gridChartStatus', json_encode($arrayGrid));
        } else {

            return view('errors.not-found');
        }
    }

    /**
     * View Edit page.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function editPage($id)
    {
        $allWaterHolder = AllWaterHolder::findOrFail($id);

        return response()->json($allWaterHolder);
    }

    /**
     * View Edit page.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $allWaterHolder = AllWaterHolder::findOrFail($id);
        $allWaterHolderDonors = AllWaterHolderDonor::where("all_water_holder_id", $id)
            ->where('is_archived', 0)->get();
   
        $h2oUser = H2oUser::where("household_id", $allWaterHolder->household_id)->first();
        $gridUser = GridUser::where('household_id', $allWaterHolder->household_id)->first();
        $h2oPublic = H2oPublicStructure::where("public_structure_id", $allWaterHolder->public_structure_id)->first();
        $gridPublic = GridPublicStructure::where('public_structure_id', $allWaterHolder->public_structure_id)->first();
        $networkUser = WaterNetworkUser::where('household_id', $allWaterHolder->household_id)->first();
        $h2oSharedPublic = H2oSharedPublicStructure::where("public_structure_id", $allWaterHolder->public_structure_id)->first();
        $h2oSharedUser = H2oSharedUser::where("household_id", $allWaterHolder->household_id)->first();;
         
        $communities =  Community::where('is_archived', 0)
            ->orderBy('english_name', 'ASC')
            ->get();
        $h2oStatuses = H2oStatus::where('is_archived', 0)->get();
        $bsfStatuses = BsfStatus::where('is_archived', 0)->get();
        $households = Household::where('community_id', $allWaterHolder->community_id)
            ->orderBy('english_name', 'ASC')
            ->get();
        $donors = Donor::where('is_archived', 0)->get();
        $waterDonorsId = AllWaterHolderDonor::where("all_water_holder_id", $id)
            ->where("is_archived", 0)
            ->pluck('donor_id'); 

        $moreDonors = Donor::where('is_archived', 0)
            ->whereNotIn('id', $waterDonorsId) 
            ->get();

        return view('users.water.all.edit', compact('allWaterHolder', 'allWaterHolderDonors',
            'h2oPublic', 'gridPublic', 'households', 'h2oStatuses', 'communities', 'h2oUser',
            'networkUser', 'h2oSharedPublic', 'h2oSharedUser',  'gridUser', 'bsfStatuses', 
            'donors', 'moreDonors'));
    }

    /**
     * Update an existing resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request, int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
       // dd($request->all());

        $allWaterHolder = AllWaterHolder::findOrFail($id);
       
        $h2oUser = H2oUser::where("household_id", $allWaterHolder->household_id)->first();
        $gridUser = GridUser::where('household_id', $allWaterHolder->household_id)->first();

        $h2oPublic = H2oPublicStructure::where("public_structure_id", $allWaterHolder->public_structure_id)->first();
        $gridPublic = GridPublicStructure::where('public_structure_id', $allWaterHolder->public_structure_id)->first();

 
        if($h2oUser) {

            if($request->h2o_status_id) {
                $h2oUser->h2o_status_id = $request->h2o_status_id;
            }
            if($request->bsf_status_id) {
                $h2oUser->bsf_status_id = $request->bsf_status_id;
            }

            $h2oUser->number_of_bsf = $request->number_of_bsf;
            $h2oUser->number_of_h20 = $request->number_of_h20; 
            $h2oUser->installation_year = $request->installation_year;
            $h2oUser->h2o_request_date = $request->h2o_request_date; 
            $h2oUser->h2o_installation_date = $request->h2o_installation_date;
            $h2oUser->save();
            
            $allWaterHolder->installation_year = $request->installation_year;
            $allWaterHolder->request_date = $request->h2o_request_date; 
            $allWaterHolder->installation_date = $request->h2o_installation_date;
            $allWaterHolder->notes = $request->notes;
            $allWaterHolder->save();
        } else if($h2oPublic) {

            if($request->h2o_status_id) {
                $h2oPublic->h2o_status_id = $request->h2o_status_id;
            }
            if($request->bsf_status_id) {
                $h2oPublic->bsf_status_id = $request->bsf_status_id;
            }

            $h2oPublic->number_of_bsf = $request->number_of_bsf;
            $h2oPublic->number_of_h20 = $request->number_of_h20; 
            $h2oPublic->installation_year = $request->installation_year;
            $h2oPublic->h2o_request_date = $request->h2o_request_date; 
            $h2oPublic->h2o_installation_date = $request->h2o_installation_date;
            $h2oPublic->save();
            
            $allWaterHolder->installation_year = $request->installation_year;
            $allWaterHolder->request_date = $request->h2o_request_date; 
            $allWaterHolder->installation_date = $request->h2o_installation_date;
            $allWaterHolder->notes = $request->notes;
            $allWaterHolder->save();
        } else if($allWaterHolder->household_id) {

            $newH2oUser =new H2oUser();
            $newH2oUser->community_id = $allWaterHolder->community_id;
            if($request->h2o_status_id) {
                $newH2oUser->h2o_status_id = $request->h2o_status_id;
            }
            if($request->bsf_status_id) {
                $newH2oUser->bsf_status_id = $request->bsf_status_id;
            }

            $newH2oUser->number_of_bsf = $request->number_of_bsf;
            $newH2oUser->number_of_h20 = $request->number_of_h20; 
            $newH2oUser->installation_year = $request->installation_year;
            $newH2oUser->h2o_request_date = $request->h2o_request_date; 
            $newH2oUser->h2o_installation_date = $request->h2o_installation_date;
            $newH2oUser->save();
            
            $allWaterHolder->installation_year = $request->installation_year;
            $allWaterHolder->request_date = $request->h2o_request_date; 
            $allWaterHolder->installation_date = $request->h2o_installation_date;
            $allWaterHolder->notes = $request->notes;
            $allWaterHolder->save();

        } else if($allWaterHolder->public_structure_id) {

            $newH2oPublic =new H2oPublicStructure();
            $newH2oPublic->community_id = $allWaterHolder->community_id;
            if($request->h2o_status_id) {
                $newH2oPublic->h2o_status_id = $request->h2o_status_id;
            }
            if($request->bsf_status_id) {
                $newH2oPublic->bsf_status_id = $request->bsf_status_id;
            }

            $newH2oPublic->number_of_bsf = $request->number_of_bsf;
            $newH2oPublic->number_of_h20 = $request->number_of_h20; 
            $newH2oPublic->installation_year = $request->installation_year;
            $newH2oPublic->h2o_request_date = $request->h2o_request_date; 
            $newH2oPublic->h2o_installation_date = $request->h2o_installation_date;
            $newH2oPublic->save();
            
            $allWaterHolder->installation_year = $request->installation_year;
            $allWaterHolder->request_date = $request->h2o_request_date; 
            $allWaterHolder->installation_date = $request->h2o_installation_date;
            $allWaterHolder->notes = $request->notes;
            $allWaterHolder->save();
        }

        if($gridUser) {

            if($request->request_date) {
                $gridUser->request_date = $request->request_date;
            }
            if($request->small_date == null) $gridUser->small_date = null;
            if($request->large_date == null) $gridUser->large_date = null;
            if($request->grid_integration_large == null) $gridUser->grid_integration_large = null;
            if($request->grid_integration_small == null) $gridUser->grid_integration_small = null;
            if($request->grid_integration_large) $gridUser->grid_integration_large = $request->grid_integration_large;
            if($request->large_date) $gridUser->large_date = $request->large_date;
            if($request->grid_integration_small) $gridUser->grid_integration_small = $request->grid_integration_small;
            if($request->small_date) $gridUser->small_date = $request->small_date;
    
            if($request->is_delivery) {
                $gridUser->is_delivery = $request->is_delivery;
            }
            if($request->is_paid) {
                $gridUser->is_paid = $request->is_paid;
            }
            if($request->is_complete) {
                $gridUser->is_complete = $request->is_complete;
            }
    
            $gridUser->save();
        } else if($gridPublic) {

            if($request->request_date) {
                $gridPublic->request_date = $request->request_date;
            }
            if($request->small_date == null) $gridPublic->small_date = null;
            if($request->large_date == null) $gridPublic->large_date = null;
            if($request->grid_integration_large == null) $gridPublic->grid_integration_large = null;
            if($request->grid_integration_small == null) $gridPublic->grid_integration_small = null;
            if($request->grid_integration_large) $gridPublic->grid_integration_large = $request->grid_integration_large;
            if($request->large_date) $gridPublic->large_date = $request->large_date;
            if($request->grid_integration_small) $gridPublic->grid_integration_small = $request->grid_integration_small;
            if($request->small_date) $gridPublic->small_date = $request->small_date;
    
            if($request->is_delivery) {
                $gridPublic->is_delivery = $request->is_delivery;
            }
            if($request->is_paid) {
                $gridPublic->is_paid = $request->is_paid;
            }
            if($request->is_complete) {
                $gridPublic->is_complete = $request->is_complete;
            }
    
            $gridPublic->save();
        } else if($allWaterHolder->household_id) {

            $newGridUser = new GridUser();
            $newGridUser->household_id = $allWaterHolder->household_id;
            $newGridUser->community_id = $allWaterHolder->community_id;
            if($request->request_date) {
                $newGridUser->request_date = $request->request_date;
            }
            if($request->small_date == null) $newGridUser->small_date = null;
            if($request->large_date == null) $newGridUser->large_date = null;
            if($request->grid_integration_large) $newGridUser->grid_integration_large = $request->grid_integration_large;
            if($request->large_date) $newGridUser->large_date = $request->large_date;
            if($request->grid_integration_small) $newGridUser->grid_integration_small = $request->grid_integration_small;
            if($request->small_date) $newGridUser->small_date = $request->small_date;
    
            if($request->is_delivery) {
                $newGridUser->is_delivery = $request->is_delivery;
            }
            if($request->is_paid) {
                $newGridUser->is_paid = $request->is_paid;
            }
            if($request->is_complete) {
                $newGridUser->is_complete = $request->is_complete;
            }
    
    
            $newGridUser->save();
        } else if($allWaterHolder->public_structure_id) {

            $newGridPublic = new GridPublicStructure();
            $newGridPublic->public_structure_id = $allWaterHolder->public_structure_id;
            $newGridPublic->community_id = $allWaterHolder->community_id;
            
            if($request->request_date) {
                $newGridPublic->request_date = $request->request_date;
            }
            if($request->small_date == null) $newGridPublic->small_date = null;
            if($request->large_date == null) $newGridPublic->large_date = null;
            if($request->grid_integration_large) $newGridPublic->grid_integration_large = $request->grid_integration_large;
            if($request->large_date) $newGridPublic->large_date = $request->large_date;
            if($request->grid_integration_small) $newGridPublic->grid_integration_small = $request->grid_integration_small;
            if($request->small_date) $newGridPublic->small_date = $request->small_date;
    
            if($request->is_delivery) {
                $newGridPublic->is_delivery = $request->is_delivery;
            }
            if($request->is_paid) {
                $newGridPublic->is_paid = $request->is_paid;
            }
            if($request->is_complete) {
                $newGridPublic->is_complete = $request->is_complete;
            }
    
            $newGridPublic->save();
        }


        if($request->donors) {

            if($allWaterHolder->public_structure_id) {

                $h2oMainPublic = H2oPublicStructure::where("public_structure_id", $allWaterHolder->public_structure_id)->first();

                if($h2oMainPublic) {

                    $sharedWaterPublics = H2oSharedPublicStructure::where('h2o_public_structure_id', $h2oMainPublic->id)
                        ->where('is_archived', 0)->get();

                    if($sharedWaterPublics) {
                        foreach($sharedWaterPublics as $sharedWaterPublic) {

                            $sharedWaterHolder = AllWaterHolder::where('public_structure_id', $sharedWaterPublic->public_structure_id)->first();
                            for($i=0; $i < count($request->donors); $i++) {
            
                                $waterHolderDonor = new AllWaterHolderDonor();
                                $waterHolderDonor->donor_id = $request->donors[$i];
                                $waterHolderDonor->all_water_holder_id = $sharedWaterHolder->id;
                                $waterHolderDonor->community_id = $sharedWaterHolder->community_id;
                                $waterHolderDonor->save();
                            }
                        }
                    }
                }
 
                for($i=0; $i < count($request->donors); $i++) {
        
                    $waterHolderDonor = new AllWaterHolderDonor();
                    $waterHolderDonor->donor_id = $request->donors[$i];
                    $waterHolderDonor->all_water_holder_id = $allWaterHolder->id;
                    $waterHolderDonor->community_id = $allWaterHolder->community_id;
                    $waterHolderDonor->save();
                } 
            }   
            
            if($allWaterHolder->household_id) {

                $h2oMainUser = H2oUser::where("household_id", $allWaterHolder->household_id)->first();
                if($h2oMainUser) {

                    $sharedWaterUsers = H2oSharedUser::where('h2o_user_id', $h2oMainUser->id)
                        ->where('is_archived', 0)->get();
                    
                    if($sharedWaterUsers) {

                        foreach($sharedWaterUsers as $sharedWaterUser) {

                            $sharedWaterHolder = AllWaterHolder::where('household_id', $sharedWaterUser->household_id)->first();
                            for($i=0; $i < count($request->donors); $i++) {
            
                                $waterHolderDonor = new AllWaterHolderDonor();
                                $waterHolderDonor->donor_id = $request->donors[$i];
                                $waterHolderDonor->all_water_holder_id = $sharedWaterHolder->id;
                                $waterHolderDonor->community_id = $sharedWaterHolder->community_id;
                                $waterHolderDonor->save();
                            }
                        }
                    }
                }
                
                for($i=0; $i < count($request->donors); $i++) {

                    $waterHolderDonor = new AllWaterHolderDonor();
                    $waterHolderDonor->donor_id = $request->donors[$i];
                    $waterHolderDonor->all_water_holder_id = $id;
                    $waterHolderDonor->community_id = $allWaterHolder->community_id;
                    $waterHolderDonor->save();
                }
            }
        }

        if($request->new_donors) {
            
            if($allWaterHolder->public_structure_id) {

                $h2oMainPublic = H2oPublicStructure::where("public_structure_id", $allWaterHolder->public_structure_id)->first();

                if($h2oMainPublic) {

                    $sharedWaterPublics = H2oSharedPublicStructure::where('h2o_public_structure_id', $h2oMainPublic->id)
                        ->where('is_archived', 0)->get();

                    if($sharedWaterPublics) {
                        foreach($sharedWaterPublics as $sharedWaterPublic) {

                            $sharedWaterHolder = AllWaterHolder::where('public_structure_id', $sharedWaterPublic->public_structure_id)->first();
                            for($i=0; $i < count($request->new_donors); $i++) {
            
                                $waterHolderDonor = new AllWaterHolderDonor();
                                $waterHolderDonor->donor_id = $request->new_donors[$i];
                                $waterHolderDonor->all_water_holder_id = $sharedWaterHolder->id;
                                $waterHolderDonor->community_id = $sharedWaterHolder->community_id;
                                $waterHolderDonor->save();
                            }
                        }
                    }
                }

                for($i=0; $i < count($request->new_donors); $i++) {
        
                    $waterHolderDonor = new AllWaterHolderDonor();
                    $waterHolderDonor->donor_id = $request->new_donors[$i];
                    $waterHolderDonor->all_water_holder_id = $allWaterHolder->id;
                    $waterHolderDonor->community_id = $allWaterHolder->community_id;
                    $waterHolderDonor->save();
                } 
            }   
            
            if($allWaterHolder->household_id) {
                $h2oMainUser = H2oUser::where("household_id", $allWaterHolder->household_id)->first();
                if($h2oMainUser) {
    
                    $sharedWaterUsers = H2oSharedUser::where('h2o_user_id', $h2oMainUser->id)
                        ->where('is_archived', 0)->get();
    
                    if($sharedWaterUsers) {
    
                        foreach($sharedWaterUsers as $sharedWaterUser) {
    
                            $sharedWaterHolder = AllWaterHolder::where('household_id', $sharedWaterUser->household_id)->first();
                            for($i=0; $i < count($request->new_donors); $i++) {
            
                                $waterHolderDonor = new AllWaterHolderDonor();
                                $waterHolderDonor->donor_id = $request->new_donors[$i];
                                $waterHolderDonor->all_water_holder_id = $sharedWaterHolder->id;
                                $waterHolderDonor->community_id = $sharedWaterHolder->community_id;
                                $waterHolderDonor->save();
                            }
                        }
                    }
                } 
    
                for($i=0; $i < count($request->new_donors); $i++) {
    
                    $waterHolderDonor = new AllWaterHolderDonor();
                    $waterHolderDonor->donor_id = $request->new_donors[$i];
                    $waterHolderDonor->all_water_holder_id = $id;
                    $waterHolderDonor->community_id = $allWaterHolder->community_id;
                    $waterHolderDonor->save();
                }
            }
            
        }

        return redirect('/all-water')->with('message', 'User Updated Successfully!');
    }

     /**
     * Show the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $allWaterHolder = AllWaterHolder::findOrFail($id);
      
        $allWaterHolderDonors = DB::table('all_water_holder_donors')
            ->where('all_water_holder_donors.is_archived', 0)
            ->where('all_water_holder_donors.all_water_holder_id', $id)
            ->join('donors', 'all_water_holder_donors.donor_id', '=', 'donors.id')
            ->select('donors.donor_name', 'all_water_holder_donors.all_water_holder_id')
            ->get();

        $h2oUser = null;
        $h2oStatus = null;
        $bsfStatus = null;
        $h2oPublic = null;
        $public = null;
        $gridUser = null;
        $energyUser = null;
        $energyPublic = null;
        $sharedH2oUsers = [];
        $sharedGridUsers = [];
        $sharedH2oPublics = [];
        $mainUser = null;
        $mainGridUser = null;
        $mainH2oPublic = null;

        if($allWaterHolder->household_id && $allWaterHolder->is_main == "Yes") {

            $h2oUser = H2oUser::where('household_id', $allWaterHolder->household_id)->first();

            if($h2oUser) {
                $h2oStatus = H2oStatus::where('id', $h2oUser->h2o_status_id)->first();
                $bsfStatus = BsfStatus::where('id', $h2oUser->bsf_status_id)->first();
                $sharedH2oUsers = H2oSharedUser::where('h2o_user_id', $h2oUser->id)
                    ->where('is_archived', 0)->get();
            }

            $gridUser = GridUser::where('household_id', $allWaterHolder->household_id)->first();
            if($gridUser) {
                $sharedGridUsers = GridSharedUser::where('grid_user_id', $gridUser->id)
                    ->where('is_archived', 0)->get();
            }
            $energyUser = AllEnergyMeter::where("household_id", $allWaterHolder->household_id)->get();
        } 

        if($allWaterHolder->household_id && $allWaterHolder->is_main == "No") {
            
            $household = Household::where('id', $allWaterHolder->household_id)->first();
            $energyUser = AllEnergyMeter::where("household_id", $allWaterHolder->household_id)->get();
            $mainUser = H2oSharedUser::where("household_id", $allWaterHolder->household_id)->first();
            $mainGridUser = GridSharedUser::where("household_id", $allWaterHolder->household_id)->first();
        } 

        if($allWaterHolder->public_structure_id && $allWaterHolder->is_main == "Yes") {

            $h2oUser = H2oPublicStructure::where('public_structure_id', $allWaterHolder->public_structure_id)->first();
            $public = PublicStructure::where('id', $allWaterHolder->public_structure_id)->first();
            
            if($h2oUser) {

                $h2oStatus = H2oStatus::where('id', $h2oUser->h2o_status_id)->first();
                $bsfStatus = BsfStatus::where('id', $h2oUser->bsf_status_id)->first();
                $sharedH2oPublics = H2oSharedPublicStructure::where('h2o_public_structure_id', $h2oUser->id)
                    ->where('is_archived', 0)->get();
            }

            $energyUser = AllEnergyMeter::where("public_structure_id", $allWaterHolder->public_structure_id)->get();
        }
 
        if($allWaterHolder->public_structure_id && $allWaterHolder->is_main == "No") {

            $h2oUser = null;
            $public = PublicStructure::where('id', $allWaterHolder->public_structure_id)->first();
            $energyUser = AllEnergyMeter::where("public_structure_id", $allWaterHolder->public_structure_id)->get();
            $mainH2oPublic = H2oSharedPublicStructure::where("public_structure_id", $allWaterHolder->public_structure_id)->first();
        }

        $community = Community::where('id', $allWaterHolder->community_id)->first();
        $household = Household::where('id', $allWaterHolder->household_id)->first();
        $networkUser = WaterNetworkUser::where('household_id', $allWaterHolder->household_id)->first();

        $waterIncident = DB::table('h2o_system_incidents')
            ->join('all_water_holders', 'h2o_system_incidents.all_water_holder_id', 
                '=', 'all_water_holders.id')
            ->join('incidents', 'h2o_system_incidents.incident_id', '=', 'incidents.id')
            ->leftJoin('h2o_incident_statuses', 'h2o_system_incidents.id', 
                'h2o_incident_statuses.h2o_system_incident_id')
            ->leftJoin('incident_statuses', 
                'h2o_incident_statuses.incident_status_id', 
                '=', 'incident_statuses.id')
            ->where('h2o_system_incidents.is_archived', 0)
            ->where('h2o_system_incidents.all_water_holder_id', $id)
            ->select('h2o_system_incidents.date as incident_date',
                'incidents.english_name as incident', 
                'incident_statuses.name as incident_status',
                'h2o_system_incidents.response_date')
            ->get(); 

        return view('users.water.show', compact('allWaterHolder', 'allWaterHolderDonors', 
            'h2oStatus', 'bsfStatus', 'community', 'household', 'public', 'mainUser',
            'networkUser', 'waterIncident', 'h2oUser', 'gridUser', 'sharedH2oPublics',
            'energyUser', 'energyPublic', 'sharedH2oUsers', 'sharedGridUsers', 'mainGridUser',
            'mainH2oPublic'));
    }

    /**
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteWaterDonor(Request $request)
    {
        $id = $request->id;
        $mainWaterDonor = AllWaterHolderDonor::findOrFail($id);
        $mainWaterHolder = AllWaterHolder::findOrFail($mainWaterDonor->all_water_holder_id);

        $h2oMainUser = H2oUser::where("household_id", $mainWaterHolder->household_id)->first();
        
        if($mainWaterDonor) {

            $mainWaterDonor->delete();

            if($h2oMainUser) {

                $sharedWaterUsers = H2oSharedUser::where('h2o_user_id', $h2oMainUser->id)
                    ->where('is_archived', 0)->get();
    
                if($sharedWaterUsers) {
    
                    foreach($sharedWaterUsers as $sharedWaterUser) {
    
                        $allWaterHolder = AllWaterHolder::where('household_id', $sharedWaterUser->household_id)->first();
                        $sharedDonor = AllWaterHolderDonor::where("all_water_holder_id", $allWaterHolder->id)
                            ->where('donor_id', $mainWaterDonor->donor_id)
                            ->first();
                        if($sharedDonor) {
                            $sharedDonor->delete();
                        }
                    }
                }
            }

            $response['success'] = 1;
            $response['msg'] = 'Water Donor Deleted successfully'; 
        } else {

            $response['success'] = 0;
            $response['msg'] = 'Invalid ID.';
        }

        return response()->json($response); 
    }
}