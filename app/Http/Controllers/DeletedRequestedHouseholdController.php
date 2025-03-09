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
use App\Models\User;
use App\Models\Community;
use App\Models\EnergySystem;
use App\Models\EnergySystemType;
use App\Models\EnergyRequestStatus;
use App\Models\EnergyRequestSystem;
use App\Models\Household;
use App\Models\HouseholdStatus;
use App\Models\PublicStructure;
use App\Models\InstallationType;
use App\Models\EnergySystemCycle;
use App\Models\Region;
use App\Models\Profession;
use App\Models\PostponedHousehold; 
use App\Models\DeletedRequestedHousehold; 
use App\Exports\EnergyRequestSystemExport;
use App\Exports\EnergyRequestedHousehold; 
use Carbon\Carbon;
use Image;
use Excel;
use DataTables;

class DeletedRequestedHouseholdController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Auth::guard('user')->user() != null) {
 
            $communityFilter = $request->input('community_deleted_filter');
            $systemTypeFilter = $request->input('system_type_deleted_filter');

            if ($request->ajax()) {

                $data = DB::table('deleted_requested_households')
                    ->join('households', 'deleted_requested_households.household_id', 'households.id')
                    ->join('communities', 'households.community_id', 'communities.id')
                    ->join('regions', 'communities.region_id', 'regions.id')
                    ->leftJoin('users', 'deleted_requested_households.referred_by', 'users.id')
                    ->leftJoin('all_energy_meters', 'all_energy_meters.household_id', 'households.id')
                    ->leftJoin('energy_system_types', 'all_energy_meters.energy_system_type_id', 'energy_system_types.id')
                    ->leftJoin('energy_system_types as energy_types', 'households.energy_system_type_id', 'energy_types.id')
                    ->where('deleted_requested_households.is_archived', 0);
                    
                if($communityFilter != null) {

                    $data->where('communities.id', $communityFilter);
                }
                if ($systemTypeFilter != null) {

                    $data->where(function($query) use ($systemTypeFilter) {
                        $query->where('energy_system_types.id', $systemTypeFilter)
                              ->orWhere('energy_types.id', $systemTypeFilter);
                    });
                }

                $data->select(
                    'households.english_name as english_name', 
                    'households.arabic_name as arabic_name',
                    'deleted_requested_households.id as id', 
                    'deleted_requested_households.updated_at as updated_at', 
                    'deleted_requested_households.created_at as created_at', 
                    'deleted_requested_households.reason', 
                    'users.name as referred_by',
                    'regions.english_name as region_name', 
                    DB::raw('IFNULL(energy_system_types.name, energy_types.name) 
                        as type'),
                    'communities.english_name as community_name', 'households.phone_number',
                    'communities.arabic_name as aname')
                ->latest(); 

                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
    
                        $returnButton = "<a type='button' title='Return to request' class='returnEnergyDeletedRequest' data-id='".$row->id."'><i class='fa-solid fa-rotate-left text-warning'></i></a>";
                        $deleteButton = "<a type='button' title='Delete permanently' class='deleteEnergyDeletedRequest' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";
         
                        return $returnButton. " " . $deleteButton;
                    })
                    ->filter(function ($instance) use ($request) {
                        if (!empty($request->get('search'))) {
                                $instance->where(function($w) use($request) {
                                $search = $request->get('search');
                                $w->orWhere('communities.english_name', 'LIKE', "%$search%")
                                ->orWhere('communities.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('energy_types.name', 'LIKE', "%$search%")
                                ->orWhere('energy_system_types.name', 'LIKE', "%$search%")
                                ->orWhere('users.name', 'LIKE', "%$search%")
                                ->orWhere('households.english_name', 'LIKE', "%$search%")
                                ->orWhere('households.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('households.phone_number', 'LIKE', "%$search%");
                            });
                        }
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
    
            $communities = Community::where('is_archived', 0)
                ->orderBy('english_name', 'ASC')
                ->get();
            $energySystemTypes = EnergySystemType::where('is_archived', 0)->get();

            return view('request.energy.index', compact('communities', 'energySystemTypes'));
        } else {

            return view('errors.not-found');
        }
    }

    /**
     * Postponed the requested household
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function returnEnergyDeletedRequest(Request $request)
    {
        $id = $request->id;

        $deletedHousehold = DeletedRequestedHousehold::find($id);
        $status = "Requested";
        $statusHousehold = HouseholdStatus::where('status', 'like', '%' . $status . '%')->first();

        if($deletedHousehold) {
            
            $deletedHousehold->delete();
            
            if($statusHousehold) {

                $household = Household::findOrFail($deletedHousehold->household_id);
                $household->household_status_id = $statusHousehold->id;
                $household->save();
            }
        } 

        $response['success'] = 1;
        $response['msg'] = 'Requested Household Returned successfully'; 

        return response()->json($response); 
    }

    /**
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteEnergyDeletedRequest(Request $request)
    {
        $id = $request->id;

        $deletedHousehold = DeletedRequestedHousehold::find($id);

        if($deletedHousehold) {
            
            $deletedHousehold->is_archived = 1;
            $deletedHousehold->save();
        }

        $response['success'] = 1;
        $response['msg'] = 'Deleted Requested Household Removed successfully'; 

        return response()->json($response); 
    }
}
