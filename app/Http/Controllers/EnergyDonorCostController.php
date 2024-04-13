<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\User;
use App\Models\Community; 
use App\Models\AllEnergyMeter;
use App\Models\CommunityDonor;
use App\Models\CommunityStatus;
use App\Models\CommunityRepresentative;
use App\Models\CommunityRole;
use App\Models\Compound;
use App\Models\Donor;
use App\Models\EnergySystem;
use App\Models\EnergyBattery; 
use App\Models\EnergyPv;
use App\Models\EnergyAirConditioner;
use App\Models\EnergyBatteryStatusProcessor;
use App\Models\EnergyBatteryTemperatureSensor;
use App\Models\EnergyChargeController;
use App\Models\EnergyGenerator;
use App\Models\EnergyInverter;
use App\Models\EnergyLoadRelay;
use App\Models\EnergyMcbAc;
use App\Models\EnergyMcbPv;
use App\Models\EnergyMonitoring;
use App\Models\EnergyWindTurbine;
use App\Models\EnergyMcbChargeController;
use App\Models\EnergyMcbInverter;
use App\Models\EnergyRelayDriver;
use App\Models\EnergyRemoteControlCenter;
use App\Models\EnergySystemType;
use App\Models\EnergySystemRelayDriver;
use App\Models\EnergySystemBattery;
use App\Models\EnergySystemBatteryMount;
use App\Models\EnergySystemMonitoring;
use App\Models\EnergySystemPv;
use App\Models\EnergySystemPvMount;
use App\Models\EnergySystemChargeController;
use App\Models\EnergySystemWindTurbine;
use App\Models\EnergySystemGenerator;
use App\Models\EnergySystemBatteryStatusProcessor;
use App\Models\EnergySystemBatteryTemperatureSensor;
use App\Models\EnergySystemInverter;
use App\Models\EnergySystemLoadRelay;
use App\Models\EnergySystemMcbPv;
use App\Models\EnergySystemMcbChargeController;
use App\Models\EnergySystemRemoteControlCenter;
use App\Models\EnergySystemMcbInverter;
use App\Models\EnergySystemAirConditioner;
use App\Models\EnergySystemWiringHouse;
use App\Models\EnergySystemFbsWiring;
use App\Models\EnergySystemFbsLock;
use App\Models\EnergySystemFbsFan;
use App\Models\EnergySystemFbsCabinet;
use App\Models\EnergySystemRefrigeratorCost;
use App\Models\EnergyDonorFundCost;
use App\Models\Household;
use App\Models\Photo;
use App\Models\Region;
use App\Models\SubRegion;
use App\Models\SubCommunity;
use App\Models\Settlement;
use App\Models\ServiceType;
use App\Models\PublicStructure;
use App\Models\PublicStructureCategory;
use App\Models\ProductType;
use App\Models\CommunityWaterSource;
use App\Models\GridCommunityCompound;
use App\Models\IncidentStatusSmallInfrastructure;
use App\Models\FbsSystem;
use App\Models\Town;
use App\Models\CompoundHousehold;
use App\Exports\EnergyCostExport;
use Carbon\Carbon;
use Auth;
use DataTables;
use DB;
use Excel;
use Image;
use Route;

class EnergyDonorCostController extends Controller
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

                $donorFilter = $request->input('donor_filter');
                $yearFilter = $request->input('year_filter');

                $data = DB::table('energy_donor_fund_costs')
                    ->join('donors', 'energy_donor_fund_costs.donor_id', 
                        'donors.id')
                    ->where('energy_donor_fund_costs.is_archived', 0);

                if($donorFilter != null) {

                    $data->where('donors.id', $donorFilter);
                }
                if ($yearFilter != null) {

                    $data->where('energy_donor_fund_costs.year', '>=', $yearFilter);
                }

                $data
                ->select('energy_donor_fund_costs.id as id', 'energy_donor_fund_costs.created_at',
                    'energy_donor_fund_costs.updated_at', 'energy_donor_fund_costs.household',
                    'energy_donor_fund_costs.year', 'energy_donor_fund_costs.fund',
                    'donors.donor_name as donor', 'energy_donor_fund_costs.commitment_household',
                    'energy_donor_fund_costs.commitment_fund')
                ->latest();

                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
    
                        $viewButton = "<a type='button' class='viewEnergyDonorCost' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#viewEnergyDonorCostModal' ><i class='fa-solid fa-eye text-info'></i></a>";
                        $updateButton = "<a type='button' class='updateEnergyDonorCost' data-id='".$row->id."' ><i class='fa-solid fa-pen-to-square text-success'></i></a>";
                        $deleteButton = "<a type='button' class='deleteEnergyDonorCost' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";
                        
                        if(Auth::guard('user')->user()->user_type_id == 1) 
                        {
                                
                            return $viewButton." ". $updateButton." ".$deleteButton;
                        } else return $viewButton;
                    })
                    ->addColumn('fund', function($row) {

                        $fund = number_format($row->fund);
                      
                        return $fund;
                    })
                    ->filter(function ($instance) use ($request) {
                        if (!empty($request->get('search'))) {
                                $instance->where(function($w) use($request){
                                $search = $request->get('search');
                                $w->orWhere('donors.donor_name', 'LIKE', "%$search%")
                                ->orWhere('energy_donor_fund_costs.year', 'LIKE', "%$search%")
                                ->orWhere('energy_donor_fund_costs.household', 'LIKE', "%$search%")
                                ->orWhere('energy_donor_fund_costs.commitment_household', 'LIKE', "%$search%");
                            });
                        }
                    })
                ->rawColumns(['action', 'fund'])
                ->make(true);
            }
    
            $communities = Community::where('is_archived', 0)
                ->orderBy('english_name', 'ASC')
                ->get();
            $donors = Donor::where('is_archived', 0)
                ->orderBy('donor_name', 'ASC')
                ->get();
        
            return view('costs.energy.donor.index', compact('communities', 'donors'));
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
        $energyDonorCost = EnergyDonorFundCost::findOrFail($id);

        return response()->json($energyDonorCost);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {     
        $energyDonorCost = new EnergyDonorFundCost();

        $energyDonorCost->donor_id = $request->donor_id;
        $energyDonorCost->year = $request->year;
        $energyDonorCost->fund = $request->fund;
        $energyDonorCost->household = $request->household;
        $energyDonorCost->commitment_fund = $request->commitment_fund;
        $energyDonorCost->commitment_household = $request->commitment_household;

        $energyDonorCost->remaining_fund = $request->fund - $request->commitment_fund;
        $energyDonorCost->remaining_household = $request->household - $request->commitment_household;
        $energyDonorCost->save();
  
        return redirect()->back()
            ->with('message', 'New Donor Cost Added Successfully!');
    }

    /**
     * View Edit page.
     *
     * @param  int $id 
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $energyDonorCost = EnergyDonorFundCost::findOrFail($id);

        $donors = Donor::where('is_archived', 0)
            ->orderBy('donor_name', 'ASC')
            ->get();
        
        return view('costs.energy.donor.edit', compact('energyDonorCost', 'donors'));
    }

    /**
     * Update an existing resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request, int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $energyDonorCost = EnergyDonorFundCost::find($id);

        $energyDonorCost->fund = $request->fund;
        $energyDonorCost->household = $request->household;
        $energyDonorCost->commitment_fund = $request->commitment_fund;
        $energyDonorCost->commitment_household = $request->commitment_household;

        $energyDonorCost->remaining_fund = $request->fund - $request->commitment_fund;
        $energyDonorCost->remaining_household = $request->household - $request->commitment_household;
        $energyDonorCost->save();

        return redirect('/donor-cost')->with('message', 'Energy Donor Cost Updated Successfully!');
    }

    /**
     * View show page.
     *
     * @param  int $id 
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $energyDonorCost = EnergyDonorFundCost::find($id);
        $donor = Donor::find($energyDonorCost->donor_id);

        $response['energyDonorCost'] = $energyDonorCost;
        $response['donor'] = $donor;

        return response()->json($response);
    }


    /**
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteEnergyDonorCost(Request $request)
    {
        $id = $request->id;

        $energyDonorCost = EnergyDonorFundCost::find($id);

        if($energyDonorCost) {

            $energyDonorCost->is_archived = 1;
            $energyDonorCost->save();

            $response['success'] = 1;
            $response['msg'] = 'Energy Donor Cost Deleted successfully'; 
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
                
        return Excel::download(new EnergyCostExport($request), 'energy_costs.xlsx');
    }
}