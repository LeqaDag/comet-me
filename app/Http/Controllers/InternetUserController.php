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
use App\Exports\InternetUserExport;
use Carbon\Carbon;
use Image;
use DataTables;
use Excel;

class InternetUserController extends Controller
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

                $data = DB::table('internet_users')
                    ->join('communities', 'internet_users.community_id', '=', 'communities.id')
                    ->leftJoin('households', 'internet_users.household_id', '=', 'households.id')
                    ->leftJoin('public_structures', 'internet_users.public_structure_id', 
                        '=', 'public_structures.id')
                    ->join('internet_statuses', 'internet_users.internet_status_id', '=', 'internet_statuses.id')
                    ->select('internet_users.number_of_people', 'internet_users.number_of_contract',
                        'internet_users.id as id', 'internet_users.created_at as created_at', 
                        'internet_users.updated_at as updated_at', 
                        'internet_users.start_date',
                        'public_structures.english_name as public_name',
                        'communities.english_name as community_name',
                        'households.english_name as household_name',
                        'internet_statuses.name')
                    ->latest(); 
     
                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
    
                        $empty = "";
                        $updateButton = "<a type='button' class='updateInternetUser' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#updateInternetUserModal' ><i class='fa-solid fa-pen-to-square text-success'></i></a>";
                        $deleteButton = "<a type='button' class='deleteInternetUser' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";
                        
                        if(Auth::guard('user')->user()->user_type_id == 1 || 
                            Auth::guard('user')->user()->user_type_id == 2 ||
                            Auth::guard('user')->user()->user_type_id == 6 ||
                            Auth::guard('user')->user()->user_type_id == 10) 
                        {
                                
                            return $updateButton." ".$deleteButton;
                        } else return $empty;
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
                                ->orWhere('internet_users.number_of_people', 'LIKE', "%$search%")
                                ->orWhere('public_structures.english_name', 'LIKE', "%$search%")
                                ->orWhere('public_structures.arabic_name', 'LIKE', "%$search%");
                            });
                        }
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
    
            $communities = Community::where('is_archived', 0)
                ->orderBy('english_name', 'ASC')
                ->get();
            $donors = Donor::where('is_archived', 0)
                ->orderBy('donor_name', 'ASC')
                ->get();
    
            return view('users.internet.index', compact('communities', 'donors'));
            
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
        $allInternetHolder = InternetUser::findOrFail($id);

        return response()->json($allInternetHolder);
    }

    /**
     * View Edit page.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $allInternetHolder = InternetUser::findOrFail($id);
        $allInternetHolderDonors = InternetUserDonor::where("internet_user_id", $id)
            ->where('is_archived', 0)->get();
        $donors = Donor::where('is_archived', 0)->get();

        return view('users.internet.all.edit', compact('allInternetHolder', 
            'allInternetHolderDonors', 'donors'));
    }

    /**
     * Update an existing resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request, int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $internetUser = InternetUser::findOrFail($id);

        if($request->donors) {

            for($i=0; $i < count($request->donors); $i++) {

                $internetHolderDonor = new InternetUserDonor();
                $internetHolderDonor->donor_id = $request->donors[$i];
                $internetHolderDonor->internet_user_id = $id;
                $internetHolderDonor->community_id = $internetUser->community_id;
                $internetHolderDonor->save();
            }
        }

        if($request->new_donors) {

            for($i=0; $i < count($request->new_donors); $i++) {

                $internetHolderDonor = new InternetUserDonor();
                $internetHolderDonor->donor_id = $request->new_donors[$i];
                $internetHolderDonor->internet_user_id = $id;
                $internetHolderDonor->community_id = $internetUser->community_id;
                $internetHolderDonor->save();
            }
        }

        return redirect('/internet-user')->with('message', 'Internet User Updated Successfully!');
    }

    /**
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteInternetDonor(Request $request)
    {
        $id = $request->id;
        $internetHolderDonor = InternetUserDonor::findOrFail($id);

        if($internetHolderDonor) {

            $internetHolderDonor->is_archived = 1;
            $internetHolderDonor->save();

            $response['success'] = 1;
            $response['msg'] = 'Internet Donor Deleted successfully'; 
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
                
        return Excel::download(new InternetUserExport($request), 'internet_holders.xlsx');
    }
}
