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
use App\Models\AllWaterHolder;
use App\Models\AllEnergyMeterDonor;
use App\Models\User; 
use App\Models\Community; 
use App\Models\EnergySystemType;
use App\Models\GridIntegrationType;
use App\Models\CommunityService;
use App\Models\WaterRequestStatus;
use App\Models\WaterSystemStatus;
use App\Models\WaterSystemType;
use App\Models\WaterRequestSystem;
use App\Models\WaterSystemCycle;
use App\Models\WaterHolderStatus;
use App\Models\Household;
use App\Models\PublicStructure;
use App\Models\InstallationType;
use App\Models\MeterCase;
use App\Models\Region;
use App\Models\H2oUser;
use App\Models\GridUser;
use App\Models\H2oPublicStructure;
use App\Models\GridPublicStructure;
use App\Exports\WaterRequestSystemExport;
use App\Exports\Water\WaterProgressExport;
use Carbon\Carbon;
use Image;
use Excel; 
use DataTables;

class WaterConfirmedSystemController extends Controller
{
    
    // This method for generating the action buttons
    private function generateActionButtons($row)
    {
        $updateButton = "<a type='button' class='updateWaterUser' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#updateWaterUserModal' ><i class='fa-solid fa-pen-to-square text-success'></i></a>";
        $deleteButton = "<a type='button' class='deleteConfirmedWaterUser' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";
        //$viewButton = "<a type='button' class='viewWaterUser' data-id='".$row->id."' ><i class='fa-solid fa-eye text-info'></i></a>";

        if(Auth::guard('user')->user()->user_type_id == 1 || 
            Auth::guard('user')->user()->user_type_id == 2 || 
            Auth::guard('user')->user()->user_type_id == 5 ||
            Auth::guard('user')->user()->user_type_id == 11) 
        {
                
            return $updateButton." ".$deleteButton;
        } else return $viewButton;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {	
        if (!Auth::guard('user')->user()) {

            return view('errors.not-found');
        }

        $regionFilter = $request->input('region_filter');
        $communityFilter = $request->input('community_filter');

        if ($request->ajax()) {

            $query = DB::table('all_water_holders')
                ->join('communities', 'all_water_holders.community_id', '=', 'communities.id')
                ->leftJoin('public_structures', 'all_water_holders.public_structure_id', '=', 'public_structures.id')
                ->leftJoin('households', 'all_water_holders.household_id', '=', 'households.id')
                ->leftJoin('water_holder_statuses', 'households.water_holder_status_id', '=', 'water_holder_statuses.id')
                ->leftJoin(
                    'water_request_systems as requested_households',
                    'all_water_holders.household_id', 'requested_households.household_id'
                )
                ->leftJoin(
                    'water_request_systems as requested_publics',
                    'all_water_holders.public_structure_id', 'requested_publics.public_structure_id'
                )
                ->leftJoin(
                    'water_system_types as requested_household_types',
                    'requested_households.water_system_type_id', 'requested_household_types.id'
                )
                ->leftJoin(
                    'water_system_types as requested_public_types',
                    'requested_publics.water_system_type_id', 'requested_public_types.id'
                )
                ->where('all_water_holders.is_archived', 0)
                ->where(function ($q) {
                    $q->where('requested_households.water_holder_status_id', 2)
                    ->orWhere('requested_publics.water_holder_status_id', 2);
                })
                ->select(
                    'all_water_holders.id',
                    DB::raw('IFNULL(households.english_name, public_structures.english_name) as holder'),
                    'communities.english_name as community_name',
                    DB::raw('IFNULL(requested_households.date, requested_publics.date) as date'),
                    DB::raw('IFNULL(requested_household_types.type, requested_public_types.type) as type')
                );

            if ($request->search) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('households.english_name', 'LIKE', "%{$search}%")
                      ->orWhere('communities.english_name', 'LIKE', "%{$search}%")
                      ->orWhere('requested_household_types.type', 'LIKE', "%{$search}%")
                      ->orWhere('requested_public_types.type', 'LIKE', "%{$search}%");
                });
            }

            if ($regionFilter) $query->where('communities.region_id', $regionFilter);
            if ($communityFilter) $query->where('communities.id', $communityFilter);
            
            $totalFiltered = $query->count();

            $columnIndex = $request->order[0]['column'] ?? 0;
            $columnName = $request->columns[$columnIndex]['data'] ?? 'all_water_holders.id';
            $direction  = $request->order[0]['dir'] ?? 'desc';

            $query->orderBy($columnName, $direction);

            $data = $query
                ->offset($request->start)
                ->limit($request->length)
                ->get();

            $totalRecords = $data->count();

            foreach ($data as $row) {
                $row->action = $this->generateActionButtons($row); 
            }

            return response()->json([
                'draw' => intval($request->draw),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $totalFiltered,
                'data' => $data
            ]);
        }  
    }

    /**
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteConfirmedWaterUser(Request $request)
    {
        $id = $request->id;

        $allWaterHolder = AllWaterHolder::find($id);

        if($allWaterHolder->household_id) {

            $waterRequestSystem = WaterRequestSystem::where("household_id", $allWaterHolder->household_id)->first();
            $waterRequestSystem->water_holder_status_id = 1;
            $waterRequestSystem->save();

            $exist = AllWaterHolder::where("household_id", $waterRequestSystem->household_id)->first();
            
            if($exist) $exist->delete();
        } else if($allWaterHolder->public_structure_id) {

            $waterRequestSystem = WaterRequestSystem::where("public_structure_id", $allWaterHolder->public_structure_id)->first();
            $waterRequestSystem->water_holder_status_id = 1;
            $waterRequestSystem->save();

            $exist = AllWaterHolder::where("public_structure_id", $waterRequestSystem->public_structure_id)->first();
            
            if($exist) $exist->delete();
        }

        $response['success'] = 1;
        $response['msg'] = 'Water Confirmed Holder Deleted successfully'; 

        return response()->json($response); 
    }
}