<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use DB;
use Route;
use App\Models\User;
use App\Models\Community;
use App\Models\CommunityDonor;
use App\Models\Donor;
use App\Models\InternetUser;
use App\Models\InternetUserDonor;
use App\Models\Household;
use App\Models\Region;
use Carbon\Carbon;
use Image;
use DataTables;

class InternetUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = DB::table('internet_users')
                ->join('communities', 'internet_users.community_id', '=', 'communities.id')
                ->join('households', 'internet_users.household_id', '=', 'households.id')
                ->join('internet_statuses', 'internet_users.internet_status_id', '=', 'internet_statuses.id')
                ->select('internet_users.number_of_people', 'internet_users.number_of_contract',
                    'internet_users.id as id', 'internet_users.created_at as created_at', 
                    'internet_users.updated_at as updated_at', 
                    'internet_users.start_date',
                    'communities.english_name as community_name',
                    'households.english_name as household_name',
                    'internet_statuses.name')
                ->latest(); 

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row) {

                    $updateButton = "<a type='button' class='updateInternetUser' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#updateInternetUserModal' ><i class='fa-solid fa-pen-to-square text-success'></i></a>";
                    $deleteButton = "<a type='button' class='deleteInternetUser' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";
                    
                    return $updateButton." ".$deleteButton;
   
                })
                ->filter(function ($instance) use ($request) {
                    if (!empty($request->get('search'))) {
                            $instance->where(function($w) use($request) {
                            $search = $request->get('search');
                            $w->orWhere('communities.english_name', 'LIKE', "%$search%")
                            ->orWhere('households.english_name', 'LIKE', "%$search%")
                            ->orWhere('communities.arabic_name', 'LIKE', "%$search%")
                            ->orWhere('households.arabic_name', 'LIKE', "%$search%")
                            ->orWhere('internet_statuses.name', 'LIKE', "%$search%")
                            ->orWhere('internet_users.start_date', 'LIKE', "%$search%")
                            ->orWhere('internet_users.number_of_contract', 'LIKE', "%$search%")
                            ->orWhere('internet_users.number_of_people', 'LIKE', "%$search%");
                        });
                    }
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('users.internet.index');
    }
}
