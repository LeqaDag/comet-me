<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\AllWaterHolder;
use App\Models\AllWaterHolderDonor;
use App\Models\User;
use App\Models\BsfStatus;
use App\Models\Donor;
use App\Models\Community;
use App\Models\CommunityWaterSource;
use App\Models\GridUser;
use App\Models\GridUserDonor;
use App\Models\GridPublicStructure;
use App\Models\H2oSharedUser;
use App\Models\H2oStatus;
use App\Models\H2oPublicStructure;
use App\Models\H2oUser;  
use App\Models\H2oUserDonor;
use App\Models\H2oSharedPublicStructure;
use App\Models\Household;
use App\Models\WaterUser;
use App\Models\WaterNetworkUser;
use App\Models\EnergySystemType;
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

            if ($request->ajax()) {
                $data = DB::table('all_water_holders') 
                    ->join('communities', 'all_water_holders.community_id', 'communities.id')
                    ->LeftJoin('public_structures', 'all_water_holders.public_structure_id', 
                        'public_structures.id')
                    ->LeftJoin('households', 'all_water_holders.household_id', 'households.id')
                    ->LeftJoin('h2o_users', 'h2o_users.household_id', 'households.id')
                    ->LeftJoin('grid_users', 'h2o_users.household_id', '=', 'grid_users.household_id')
                    ->leftJoin('water_network_users', 'households.id', 
                        '=', 'water_network_users.household_id')
                    ->LeftJoin('h2o_statuses', 'h2o_users.h2o_status_id', '=', 'h2o_statuses.id')
                    ->where('all_water_holders.is_archived', 0)
                    ->select('all_water_holders.id as id', 'households.english_name', 
                        'h2o_users.number_of_h20', 'grid_users.grid_integration_large', 
                        'grid_users.large_date', 'grid_users.grid_integration_small', 
                        'grid_users.small_date', 'grid_users.is_delivery', 'h2o_users.number_of_bsf', 
                        'grid_users.is_paid', 'communities.english_name as community_name', 
                        'grid_users.is_complete', 'all_water_holders.created_at as created_at',
                        'all_water_holders.installation_year', 'h2o_statuses.status',
                        'all_water_holders.updated_at as updated_at', 'all_water_holders.is_main',
                        'public_structures.english_name as public')
                    ->latest();
                
              
                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
    
                        $updateButton = "<a type='button' class='updateWaterUser' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#updateWaterUserModal' ><i class='fa-solid fa-pen-to-square text-success'></i></a>";
                        $deleteButton = "<a type='button' class='deleteWaterUser' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";
                        $viewButton = "<a type='button' class='viewWaterUser' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#viewWaterUserModal' ><i class='fa-solid fa-eye text-info'></i></a>";
    
                        if(Auth::guard('user')->user()->user_type_id == 1 || 
                            Auth::guard('user')->user()->user_type_id == 2 ||
                            Auth::guard('user')->user()->user_type_id == 5 ||
                            Auth::guard('user')->user()->user_type_id == 11) 
                        {
                                
                            return $viewButton." ". $updateButton." ".$deleteButton;
                        } else return $viewButton;
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
                                ->orWhere('public_structures.english_name', 'LIKE', "%$search%")
                                ->orWhere('public_structures.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('h2o_statuses.status', 'LIKE', "%$search%")
                                ->orWhere('grid_users.grid_integration_large', 'LIKE', "%$search%")
                                ->orWhere('grid_users.grid_integration_small', 'LIKE', "%$search%");
                            });
                        }
                    })
                ->rawColumns(['action', 'icon'])
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
                ->selectRaw('SUM(grid_integration_large) AS sumLarge')
                ->first();
            $gridSmall = GridUser::where('grid_integration_small', '!=', 0)
                ->where('is_archived', 0)
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
                ->count();
            $networkUsers = WaterNetworkUser::where('is_archived', 0)->count();

            return view('users.water.all.index', compact('communities', 'bsfStatus', 'households', 
                'h2oStatus', 'totalWaterHouseholds', 'totalWaterMale', 'totalWaterFemale',
                'totalWaterChildren', 'totalWaterAdults', 'donors', 'energySystemTypes',
                'h2oUsers', 'gridUsers', 'networkUsers', 'h2oSharedUsers'))
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

        return view('users.water.all.edit', compact('allWaterHolder', 'allWaterHolderDonors',
            'h2oPublic', 'gridPublic', 'households', 'h2oStatuses', 'communities', 'h2oUser',
            'networkUser', 'h2oSharedPublic', 'h2oSharedUser',  'gridUser', 'bsfStatuses', 
            'donors'));
    }

    /**
     * Update an existing resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request, int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //dd($request->all());

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
            $allWaterHolder->save();
        }

        if($h2oPublic) {

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
            $allWaterHolder->save();
        }

        // if($gridUser == null) {
        //     $gridUser = new GridUser();
        //     $gridUser->community_id = $h2oUser->community_id;
        //     $gridUser->household_id = $h2oUser->household_id;
        // }

        if($gridUser) {

            if($request->request_date) {
                $gridUser->request_date = $request->request_date;
            }
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
        }

        if($gridPublic) {

            if($request->request_date) {
                $gridPublic->request_date = $request->request_date;
            }
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

            $mainWaterDonor->is_archived = 1;
            $mainWaterDonor->save();

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
                            $sharedDonor->is_archived = 1;
                            $sharedDonor->save();
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