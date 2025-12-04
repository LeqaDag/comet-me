<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;

class GridUserController extends Controller
{
    // This method for generating the action buttons
    private function generateActionButtons($row)
    {
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
        $yearFilter = $request->input('year_filter');

        if ($request->ajax()) {

            $query = DB::table('all_water_holders') 
                ->join('communities', 'all_water_holders.community_id', 'communities.id')
                ->LeftJoin('public_structures', 'all_water_holders.public_structure_id', 
                    'public_structures.id')
                ->LeftJoin('households', 'all_water_holders.household_id', 'households.id')
                ->LeftJoin('water_holder_statuses', 'households.water_holder_status_id', 'water_holder_statuses.id')
                ->join('grid_users', 'households.id', 'grid_users.household_id')
                ->where('grid_users.is_archived', 0)
                ->select(
                    'all_water_holders.id',
                    'households.english_name as holder',
                    'communities.english_name as community_name',
                    'grid_users.grid_integration_large',
                    'grid_users.large_date',
                    'grid_users.grid_integration_small',
                    'grid_users.small_date',
                    'all_water_holders.created_at'
                );

            if ($request->search) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('households.english_name', 'LIKE', "%{$search}%")
                      ->orWhere('communities.english_name', 'LIKE', "%{$search}%")
                      ->orWhere('households.arabic_name', 'LIKE', "%{$search}%")
                      ->orWhere('communities.arabic_name', 'LIKE', "%{$search}%")
                      ->orWhere('grid_users.grid_integration_large', 'LIKE', "%{$search}%")
                      ->orWhere('grid_users.large_date', 'LIKE', "%{$search}%")
                      ->orWhere('grid_users.grid_integration_small', 'LIKE', "%{$search}%")
                      ->orWhere('grid_users.small_date', 'LIKE', "%{$search}%")
                      ;
                });
            }

            if ($regionFilter) $query->where('communities.region_id', $regionFilter);
            if ($communityFilter) $query->where('communities.id', $communityFilter);
            if ($yearFilter != null) $query->where('all_water_holders.installation_year', $yearFilter);

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
}
