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
use App\Models\Compound;
use App\Models\InternetUser;
use App\Models\InternetUserDonor;
use App\Models\EnergyDonor;
use App\Models\ServiceType;
use App\Exports\DonorExport;
use Carbon\Carbon;
use Image;
use Excel;
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

            $communities = Community::where('is_archived', 0)
                ->orderBy('english_name', 'ASC')
                ->get();
            $donors = Donor::paginate();
            $services = ServiceType::where('is_archived', 0)->get();
        
            $communityFilter = $request->input('community_filter');
            $serviceFilter = $request->input('service_filter');
            $donorFilter = $request->input('donor_filter');

            if ($request->ajax()) {

                $data = DB::table('community_donors')
                    ->leftJoin('communities', 'community_donors.community_id', 'communities.id')
                    ->leftJoin('compounds', 'community_donors.compound_id', 'compounds.id')
                    ->join('donors', 'community_donors.donor_id', 'donors.id')
                    ->join('service_types', 'community_donors.service_id', 'service_types.id')
                    ->where('community_donors.is_archived', 0);
                    
                if($communityFilter != null) {

                    $data->where('communities.id', $communityFilter);
                }
                if ($serviceFilter != null) {

                    $data->where('service_types.id', $serviceFilter);
                }
                if ($donorFilter != null) {

                    $data->where('donors.id',  $donorFilter);
                }

                $data->select(
                    DB::raw('IFNULL(communities.english_name, compounds.english_name) 
                        as value'),
                    'community_donors.id as id', 'community_donors.created_at as created_at', 
                    'community_donors.updated_at as updated_at',
                    'donors.donor_name as donor_name',
                    'service_types.service_name as service_name'
                )->latest(); 

                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {

                        $empty = "";
                        $updateButton = "<a type='button' class='updateDonor' data-id='".$row->id."'><i class='fa-solid fa-pen-to-square text-success'></i></a>";
                        $deleteButton = "<a type='button' class='deleteDonor' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></s>";
                        
                        if(Auth::guard('user')->user()->user_type_id == 1 ) 
                        {
                                
                            return $updateButton. " ". $deleteButton;
                        } else return $empty; 
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

            $householdDonors = DB::table('all_energy_meter_donors')
                ->join('communities', 'all_energy_meter_donors.community_id', '=', 'communities.id')
                ->join('donors', 'all_energy_meter_donors.donor_id', '=', 'donors.id')
                ->where('all_energy_meter_donors.is_archived', 0)
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
            
            $otherDonors = DB::table('all_energy_meter_donors')
                ->join('communities', 'all_energy_meter_donors.community_id', '=', 'communities.id')
                ->join('donors', 'all_energy_meter_donors.donor_id', '=', 'donors.id')
                ->where('all_energy_meter_donors.is_archived', 0)
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
                ->where('community_donors.is_archived', 0)
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
                ->where('community_donors.is_archived', 0)
                ->select(
                        DB::raw('donors.donor_name as donor_name'),
                        DB::raw('count(*) as number'))
                ->groupBy('donors.donor_name')
                ->get();
            $arrayInternet[] = ['Donor Name', 'Number'];
            
            foreach($dataInternet as $key => $value) {

                $arrayInternet[++$key] = [$value->donor_name, $value->number];
            }

            $dataUserDonors= DB::table('all_water_holder_donors')
                ->join('donors', 'all_water_holder_donors.donor_id', '=', 'donors.id')
                ->join('all_water_holders', 'all_water_holder_donors.all_water_holder_id', 
                    '=', 'all_water_holders.id')
                ->join('h2o_users', 'all_water_holders.household_id', '=', 
                    'h2o_users.household_id')
                ->where('all_water_holder_donors.is_archived', 0)
                ->whereNotNull('h2o_users.number_of_h20')
                ->select(
                    DB::raw('donors.donor_name as donor_name'),
                    DB::raw('count(*) as number'))
                ->groupBy('donors.donor_name')
                ->get();

            $arrayUserDonors[] = ['Donor Name', 'Number'];
            
            foreach($dataUserDonors as $key => $value) {

                $arrayUserDonors[++$key] = [$value->donor_name, $value->number];
            }

            $dataGridDonors= DB::table('all_water_holder_donors')
                ->join('donors', 'all_water_holder_donors.donor_id', '=', 'donors.id')
                ->join('all_water_holders', 'all_water_holder_donors.all_water_holder_id', 
                    '=', 'all_water_holders.id')
                ->join('grid_users', 'all_water_holders.household_id', '=', 
                    'grid_users.household_id')
                ->where('all_water_holder_donors.is_archived', 0)
                ->select(
                    DB::raw('donors.donor_name as donor_name'),
                    DB::raw('count(*) as number'))
                ->groupBy('donors.donor_name')
                ->get();

            $arrayGridDonors[] = ['Donor Name', 'Number'];
            
            foreach($dataGridDonors as $key => $value) {

                $arrayGridDonors[++$key] = [$value->donor_name, $value->number];
            }

            $compounds = Compound::where('is_archived', 0)
                ->orderBy('english_name', 'ASC')
                ->get();

            return view('admin.donor.index', compact('communities', 'donors', 'services', 'compounds'))
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
        $donor = new Donor();
        $donor->donor_name = $request->donor_name;
        $donor->save();

        return redirect()->back()->with('message', 'New Donor Inserted Successfully!');
    }

    /**
     * View Edit page.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function editPage($id)
    {
        $communityDonor = CommunityDonor::findOrFail($id);

        return response()->json($communityDonor);
    }

    /**
     * View Edit page.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) 
    {
        $communityDonor = CommunityDonor::findOrFail($id);
        $communityId = $communityDonor->community_id;
    
        $donors = Donor::where('is_archived', 0)->get();
        $services = ServiceType::where('is_archived', 0)->get();

        $serviceDonors = DB::table('community_donors')
            ->where('community_donors.community_id', $communityId)
            ->join('donors', 'community_donors.donor_id', '=', 'donors.id')
            ->select('donors.donor_name', 'community_donors.id')
            ->get(); 

        return view('admin.donor.community.edit', compact('communityDonor', 'donors', 
            'services', 'serviceDonors'));
    }

    /**
     * Update an existing resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request, int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $communityDonor = CommunityDonor::findOrFail($id);
        if($request->donor_id) {
            
            $communityDonor->donor_id = $request->donor_id;
            $communityDonor->save();
        }

        return redirect('/donor')->with('message', 'Donor Updated Successfully!');
    }

    /**
     * Get a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getDonorData(int $id)
    {
        $communityDonor = CommunityDonor::findOrFail($id);

        $response = array();

        if(!empty($communityDonor)) {

            $community = Community::findOrFail($communityDonor->community_id);
            $service = ServiceType::findOrFail($communityDonor->service_id);
            $donor = Donor::findOrFail($communityDonor->donor_id);

            $response['donor_id'] = $communityDonor->donor_id;
            $response['donor'] = $donor->donor_name;
            $response['community'] = $community->english_name;
            $response['service'] = $service->service_name;
            $response['service_id'] = $service->id;
            $response['id'] = $id;
            $response['success'] = 1;
        } else {

            $response['success'] = 0;
        }

        return response()->json($response);
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

    /**
     * 
     * @return \Illuminate\Support\Collection
     */
    public function export(Request $request) 
    {

        return Excel::download(new DonorExport($request), 'donors.xlsx'); 
    }
}
