<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WaterNetworkController extends Controller
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

        if ($request->ajax()) {

            $query = DB::table('all_water_holders')
                ->join('communities', 'all_water_holders.community_id', 'communities.id')
                ->leftJoin('public_structures', 'all_water_holders.public_structure_id', 'public_structures.id')
                ->leftJoin('households', 'all_water_holders.household_id', 'households.id')
                ->leftJoin('water_holder_statuses', 'households.water_holder_status_id', 'water_holder_statuses.id')
                ->join('water_network_users', 'water_network_users.household_id', 'households.id')
                ->where('water_network_users.is_archived', 0)
                ->select(
                    'all_water_holders.id',
                    'households.english_name as holder',
                    'communities.english_name as community_name',
                    'water_network_users.is_delivery as delivered',
                    'water_network_users.is_complete as completed',
                    'water_network_users.is_paid as paid',
                    'all_water_holders.created_at'
                );

            if ($request->search) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('households.english_name', 'LIKE', "%{$search}%")
                      ->orWhere('communities.english_name', 'LIKE', "%{$search}%");
                });
            }

            if ($request->region_filter) {
                $query->where('communities.region_id', $request->region_filter);
            }

            if ($request->community_filter) {
                $query->where('communities.id', $request->community_filter);
            }

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
