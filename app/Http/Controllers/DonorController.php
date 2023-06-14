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
use App\Models\EnergyDonor;
use App\Models\ServiceType;
use Carbon\Carbon;
use Image;
use DataTables;

class DonorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {	
        if (Auth::guard('user')->user() != null) {

            $communities = Community::where('communities.is_archived', 0)->get();
            $donors = Donor::paginate();
            $services = ServiceType::all();
        
            if ($request->ajax()) {
                $data = DB::table('community_donors')
                    ->join('communities', 'community_donors.community_id', '=', 'communities.id')
                    ->join('donors', 'community_donors.donor_id', '=', 'donors.id')
                    ->join('service_types', 'community_donors.service_id', '=', 'service_types.id')
                    ->select('communities.english_name as english_name', 
                        'communities.arabic_name as arabic_name',
                        'community_donors.id as id', 'community_donors.created_at as created_at', 
                        'community_donors.updated_at as updated_at',
                        'donors.donor_name as donor_name',
                        'service_types.service_name as service_name')
                    ->latest(); 
                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {

                        $deleteButton = "<a type='button' class='deleteDonor' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></s>";
                        
                        return $deleteButton;
                    })
                
                    ->filter(function ($instance) use ($request) {
                        if (!empty($request->get('search'))) {
                                $instance->where(function($w) use($request){
                                $search = $request->get('search');
                                $w->orWhere('communities.english_name', 'LIKE', "%$search%")
                                ->orWhere('donors.donor_name', 'LIKE', "%$search%")
                                ->orWhere('communities.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('service_types.service_name', 'LIKE', "%$search%");
                            });
                        }
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }

            $householdDonors = DB::table('energy_donors')
                ->join('communities', 'energy_donors.community_id', '=', 'communities.id')
                ->join('donors', 'energy_donors.donor_id', '=', 'donors.id')
                ->where('donors.id', 1)
                ->orWhere('donors.id', 2)
                ->orWhere('donors.id', 3)
                ->orWhere('donors.id', 4)
                ->orWhere('donors.id', 5)
                ->orWhere('donors.id', 6)
                ->select(
                        DB::raw('donors.donor_name as donor_name'),
                        DB::raw('count(*) as number'))
                ->groupBy('donors.donor_name')
                ->get();
            $householdDonorsArray[] = ['Donor Name', 'Number'];
            
            $otherDonors = DB::table('energy_donors')
                ->join('communities', 'energy_donors.community_id', '=', 'communities.id')
                ->join('donors', 'energy_donors.donor_id', '=', 'donors.id')
                ->where('donors.id', '!=', 1)
                ->orWhere('donors.id', '!=', 2)
                ->orWhere('donors.id', '!=', 3)
                ->orWhere('donors.id', '!=', 4)
                ->orWhere('donors.id', '!=', 5)
                ->orWhere('donors.id', '!=', 6)
                ->select(
                        DB::raw('donors.donor_name as donor_name'),
                        DB::raw('count(*) as number'))
                ->groupBy('donors.donor_name')
                ->get();
            $sum = 0;
            $otherDonorsArray[] = ['Donor Name', 'Number'];
            foreach($otherDonors as $key => $value) {

                if($value->donor_name == "0" || $value->donor_name == "DCA/NCA" ||
                    $value->donor_name == "Swiss Olive Oil Campaign" ||
                    $value->donor_name == "EWB Denmark" || 
                    $value->donor_name == "New Zealand Embassy (via IPCRI)" || 
                    $value->donor_name == "ACF" || 
                    $value->donor_name == "New Zealand" || 
                    $value->donor_name == "Swedish Postcode Lottery" || 
                    $value->donor_name == "France") {

                    $sum+=$value->number;

                    $otherDonorsArray[1] = ["Other", $sum];
                }
            }

            foreach($householdDonors as $key => $value) {

                $householdDonorsArray[++$key] = [$value->donor_name, $value->number];
            }

            $householdDonorsArray[++$key] = [$otherDonorsArray[1][0], $otherDonorsArray[1][1]];
        
            $dataWater = DB::table('community_donors')
                ->join('communities', 'community_donors.community_id', '=', 'communities.id')
                ->join('donors', 'community_donors.donor_id', '=', 'donors.id')
                ->join('service_types', 'community_donors.service_id', '=', 'service_types.id')
                ->where('service_types.service_name', 'Water')
                ->select(
                        DB::raw('donors.donor_name as donor_name'),
                        DB::raw('count(*) as number'))
                ->groupBy('donors.donor_name')
                ->get();
            $arrayWater[] = ['Donor Name', 'Number'];
            
            foreach($dataWater as $key => $value) {

                $arrayWater[++$key] = [$value->donor_name, $value->number];
            }

            $dataInternet = DB::table('community_donors')
                ->join('communities', 'community_donors.community_id', '=', 'communities.id')
                ->join('donors', 'community_donors.donor_id', '=', 'donors.id')
                ->join('service_types', 'community_donors.service_id', '=', 'service_types.id')
                ->where('service_types.service_name', 'Internet')
                ->select(
                        DB::raw('donors.donor_name as donor_name'),
                        DB::raw('count(*) as number'))
                ->groupBy('donors.donor_name')
                ->get();
            $arrayInternet[] = ['Donor Name', 'Number'];
            
            foreach($dataInternet as $key => $value) {

                $arrayInternet[++$key] = [$value->donor_name, $value->number];
            }

            $dataUserDonors= DB::table('h2o_user_donors')
                ->join('donors', 'h2o_user_donors.donor_id', '=', 'donors.id')
                ->select(
                        DB::raw('donors.donor_name as donor_name'),
                        DB::raw('count(*) as number'))
                ->groupBy('donors.donor_name')
                ->get();

            $arrayUserDonors[] = ['Donor Name', 'Number'];
            
            foreach($dataUserDonors as $key => $value) {

                $arrayUserDonors[++$key] = [$value->donor_name, $value->number];
            }

            $dataGridDonors= DB::table('grid_user_donors')
                ->join('donors', 'grid_user_donors.donor_id', '=', 'donors.id')
                ->select(
                        DB::raw('donors.donor_name as donor_name'),
                        DB::raw('count(*) as number'))
                ->groupBy('donors.donor_name')
                ->get();

            $arrayGridDonors[] = ['Donor Name', 'Number'];
            
            foreach($dataGridDonors as $key => $value) {

                $arrayGridDonors[++$key] = [$value->donor_name, $value->number];
            }

            return view('admin.donor.index', compact('communities', 'donors', 'services'))
                ->with('donorsWaterData', json_encode($arrayWater))
                ->with('donorsInternetData', json_encode($arrayInternet))
                ->with('householdDonorsEnergyData', json_encode($householdDonorsArray))
                ->with('waterUserDonors', json_encode($arrayUserDonors))
                ->with('gridUserDonors', json_encode($arrayGridDonors));

        } else {

            return view('errors.not-found');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $donor = Donor::create($request->all());
        $donor->save();

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $donor = Donor::findOrFail($id);
        $donor->is_archived = 1;
        $donor->save();

        return redirect()->back();
    }
}
