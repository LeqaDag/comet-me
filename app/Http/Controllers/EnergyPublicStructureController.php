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
use App\Models\CommunityDonor;
use App\Models\Donor;
use App\Models\EnergyDonor;
use App\Models\EnergySystem;
use App\Models\EnergySystemType;
use App\Models\EnergyUser;
use App\Models\EnergyHolder;
use App\Models\EnergyPublicStructure;
use App\Models\EnergyPublicStructureDonor;
use App\Models\Household;
use App\Models\HouseholdMeter;
use App\Models\MeterCase;
use App\Models\ServiceType;
use App\Models\PublicStructure;
use App\Models\PublicStructureCategory;
use App\Models\Region;
use App\Models\VendorUsername;
use Carbon\Carbon;
use Image;
use DataTables;

class EnergyPublicStructureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Auth::guard('user')->user() != null) {

        } else {

            return view('errors.not-found');
        }
        
        if ($request->ajax()) {

            $dataPublic = DB::table('all_energy_meters')
                ->join('public_structures', 'all_energy_meters.public_structure_id', '=', 'public_structures.id')
                ->join('communities', 'public_structures.community_id', '=', 'communities.id')
                ->join('energy_systems', 'all_energy_meters.energy_system_id', '=', 'energy_systems.id')
                ->join('energy_system_types', 'all_energy_meters.energy_system_type_id', '=', 'energy_system_types.id')
                ->join('meter_cases', 'all_energy_meters.meter_case_id', '=', 'meter_cases.id')
                ->select('all_energy_meters.meter_number', 
                    'all_energy_meters.id as id', 'all_energy_meters.created_at as created_at', 
                    'all_energy_meters.updated_at as updated_at', 
                    'communities.english_name as community_name',
                    'public_structures.english_name as public_name',
                    'energy_systems.name as energy_name', 
                    'energy_system_types.name as energy_type_name',)
                ->latest(); 

             
            return Datatables::of($dataPublic)
                ->addIndexColumn()
                ->addColumn('action', function($row) {

                    $viewButton = "<a type='button' class='viewEnergyPublic' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#viewEnergyPublicModal' ><i class='fa-solid fa-eye text-info'></i></a>";
                    $updateButton = "<a type='button' class='updateEnergyPublic' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#updateEnergyUserModal' ><i class='fa-solid fa-pen-to-square text-success'></i></a>";
                    $deleteButton = "<a type='button' class='deleteEnergyPublic' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";

                    return $viewButton." ".$updateButton." ".$deleteButton;
   
                })
                ->filter(function ($instance) use ($request) {
                    if (!empty($request->get('search'))) {
                            $instance->where(function($w) use($request) {
                            $search = $request->get('search');
                            $w->orWhere('communities.english_name', 'LIKE', "%$search%")
                            ->orWhere('communities.arabic_name', 'LIKE', "%$search%")
                            ->orWhere('energy_systems.name', 'LIKE', "%$search%")
                            ->orWhere('energy_system_types.name', 'LIKE', "%$search%")
                            ->orWhere('public_structures.english_name', 'LIKE', "%$search%")
                            ->orWhere('public_structures.arabic_name', 'LIKE', "%$search%")
                            ->orWhere('all_energy_meters.meter_number', 'LIKE', "%$search%");
                        });
                    }
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $communities = Community::where('is_archived', 0)->get();
        $households = Household::all();
        $energySystems = EnergySystem::all();
        $energySystemTypes = EnergySystemType::all();
        $meters = MeterCase::all();

        $schools = DB::table('all_energy_meters')
            ->join('public_structures', 'all_energy_meters.public_structure_id', 
                '=', 'public_structures.id')
            ->join('public_structure_categories', 'public_structures.public_structure_category_id1', 
                '=', 'public_structure_categories.id')
            ->where('public_structures.public_structure_category_id1', 1)
            ->orWhere('public_structures.public_structure_category_id2', 1)
            ->orWhere('public_structures.public_structure_category_id3', 1)
            ->count();

        $clinics = DB::table('all_energy_meters')
            ->join('public_structures', 'all_energy_meters.public_structure_id', 
                '=', 'public_structures.id')
            ->join('public_structure_categories', 'public_structures.public_structure_category_id1', 
                '=', 'public_structure_categories.id')
            ->where('public_structures.public_structure_category_id1', 3)
            ->orWhere('public_structures.public_structure_category_id2', 3)
            ->orWhere('public_structures.public_structure_category_id3', 3)
            ->count(); 

        $mosques = DB::table('all_energy_meters')
            ->join('public_structures', 'all_energy_meters.public_structure_id', 
                '=', 'public_structures.id')
            ->join('public_structure_categories', 'public_structures.public_structure_category_id1', 
                '=', 'public_structure_categories.id')
            ->where('public_structures.public_structure_category_id1', 2)
            ->orWhere('public_structures.public_structure_category_id2', 2)
            ->orWhere('public_structures.public_structure_category_id3', 2)
            ->count(); 

        $madafah = DB::table('all_energy_meters')
            ->join('public_structures', 'all_energy_meters.public_structure_id', 
                '=', 'public_structures.id')
            ->join('public_structure_categories', 'public_structures.public_structure_category_id1', 
                '=', 'public_structure_categories.id')
            ->where('public_structures.public_structure_category_id1', 7)
            ->orWhere('public_structures.public_structure_category_id2', 7)
            ->orWhere('public_structures.public_structure_category_id3', 7)
            ->count(); 

        $kindergarten = DB::table('all_energy_meters')
            ->join('public_structures', 'all_energy_meters.public_structure_id', 
                '=', 'public_structures.id')
            ->join('public_structure_categories', 'public_structures.public_structure_category_id1', 
                '=', 'public_structure_categories.id')
            ->where('public_structures.public_structure_category_id1', 5)
            ->orWhere('public_structures.public_structure_category_id2', 5)
            ->orWhere('public_structures.public_structure_category_id3', 5)
            ->count();

        $center = DB::table('all_energy_meters')
            ->join('public_structures', 'all_energy_meters.public_structure_id', 
                '=', 'public_structures.id')
            ->join('public_structure_categories', 'public_structures.public_structure_category_id1', 
                '=', 'public_structure_categories.id')
            ->where('public_structures.public_structure_category_id1', 6)
            ->orWhere('public_structures.public_structure_category_id2', 6)
            ->orWhere('public_structures.public_structure_category_id3', 6)
            ->count(); 

        $dataPublicStructures = DB::table('all_energy_meters')
            ->join('meter_cases', 'all_energy_meters.meter_case_id', '=', 'meter_cases.id')
            ->where('meter_cases.meter_case_name_english', '!=', "Not Activated")
            ->select(
                    DB::raw('meter_cases.meter_case_name_english as name'),
                    DB::raw('count(*) as number'))
            ->groupBy('meter_cases.meter_case_name_english')
            ->get();

          
        $arrayPublicStructures[] = ['Meter Case', 'Total'];
        
        foreach($dataPublicStructures as $key => $value) {

            $arrayPublicStructures[++$key] = [$value->name, $value->number];
        }
        
        return view('users.energy.public.index', compact('communities', 'households', 'madafah',
            'energySystems', 'energySystemTypes', 'meters', 'schools', 'clinics', 'mosques',
            'kindergarten', 'center'))
            ->with('energy_public_structures', json_encode($arrayPublicStructures)
        );
    }

    /**
     * Get resources from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getByCommunity($community_id)
    {
        $html = '<option disabled selected>Choose One ...</option>';

        $publics = PublicStructure::where('community_id', $community_id)
            ->get();
        

        foreach ($publics as $public) {
            $html .= '<option value="'.$public->id.'">'.$public->english_name.'</option>';
        }

        return response()->json(['html' => $html]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $publicEnergy = new AllEnergyMeter();
        $publicEnergy->misc = $request->misc;
        $publicEnergy->community_id = $request->community_id;
        $publicEnergy->meter_number = $request->meter_number;
        $publicEnergy->public_structure_id = $request->public_structure_id;
        $publicEnergy->energy_system_id = $request->energy_system_id;
        $publicEnergy->energy_system_type_id = $request->energy_system_type_id;
        $publicEnergy->meter_case_id = $request->meter_case_id;
        $publicEnergy->installation_date = $request->installation_date;
        $publicEnergy->daily_limit = $request->daily_limit;
        $publicEnergy->notes = $request->notes;
        $publicEnergy->save();

        return redirect()->back()->with('message', 'New Public Structure Added Successfully!');
    }

    /**
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteEnergyPublic(Request $request)
    {
        $id = $request->id;

        $energyPublic = AllEnergyMeter::find($id);

        if($energyPublic->delete()) {

            $response['success'] = 1;
            $response['msg'] = 'Energy Public Structure Delete successfully'; 
        } else {

            $response['success'] = 0;
            $response['msg'] = 'Invalid ID.';
        }

        return response()->json($response); 
    }

    /**
     * Get resources from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getEnergySystemByType($energy_type_id, $community_id)
    {
        if($community_id == 0) {

            $energySystems = EnergySystem::where('energy_system_type_id', $energy_type_id)->get();
        } else {

            $energySystems = EnergySystem::where('energy_system_type_id', $energy_type_id)
                ->where('community_id', $community_id)
                ->get();
        }
        
 
        if (!$energy_type_id) {

            $html = '<option value="">Choose One...</option>';
        } else {

            $html = '';
            if($community_id == 0) {

                $energySystems = EnergySystem::where('energy_system_type_id', $energy_type_id)->get();
            } else {
                
                $energySystems = EnergySystem::where('energy_system_type_id', $energy_type_id)
                    ->where('community_id', $community_id)
                    ->get();
            }

            foreach ($energySystems as $energyType) {
                $html .= '<option value="'.$energyType->id.'">'.$energyType->name.'</option>';
            }
        }

        return response()->json(['html' => $html]);
    }

    /**
     * Get resources from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getEnergySystemByCommunity($energy_type_id, $community_id)
    {
        if($community_id == 0) {

            $energySystems = EnergySystem::where('energy_system_type_id', $energy_type_id)->get();
        } else {

            $energySystems = EnergySystem::where('energy_system_type_id', $energy_type_id)
                ->where('community_id', $community_id)
                ->get();
        }
 
        if (!$energy_type_id) {

            $html = '<option value="">Choose One...</option>';
        } else {

            $html = '';
            if($community_id == 0) {

                $energySystems = EnergySystem::where('energy_system_type_id', $energy_type_id)->get();
            } else {
                
                $energySystems = EnergySystem::where('energy_system_type_id', $energy_type_id)
                    ->where('community_id', $community_id)
                    ->get();
            }

            foreach ($energySystems as $energyType) {
                $html .= '<option value="'.$energyType->id.'">'.$energyType->name.'</option>';
            }
        } 

        return response()->json(['html' => $html]);
    }

    /**
     * Show the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $energyPublic = AllEnergyMeter::findOrFail($id);
        $energyMeterDonors = DB::table('all_energy_meter_donors')
            ->where('all_energy_meter_donors.all_energy_meter_id', $id)
            ->join('donors', 'all_energy_meter_donors.donor_id', '=', 'donors.id')
            ->select('donors.donor_name', 'all_energy_meter_donors.all_energy_meter_id')
            ->get();

        $community = Community::where('id', $energyPublic->community_id)->first();
        $public = PublicStructure::where('id', $energyPublic->public_structure_id)->first();
        $meter = MeterCase::where('id', $energyPublic->meter_case_id)->first();
        $systemType = EnergySystemType::where('id', $energyPublic->energy_system_type_id)->first();
        $system = EnergySystem::where('id', $energyPublic->energy_system_id)->first();

        $response['energyPublic'] = $energyPublic;
        $response['energyMeterDonors'] = $energyMeterDonors;
        $response['community'] = $community;
        $response['public'] = $public;
        $response['meter'] = $meter;
        $response['type'] = $systemType;
        $response['system'] = $system;

        return response()->json($response);
    }

    /**
     * View Edit page.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function editPage($id)
    {
        $energyPublic = AllEnergyMeter::findOrFail($id);

        return response()->json($energyPublic);
    } 

    /**
     * View Edit page.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) 
    {
        $energyPublic = AllEnergyMeter::findOrFail($id);
        $energyDonors = AllEnergyMeterDonor::where("all_energy_meter_id", $id)->get();
        $community_id = Community::findOrFail($energyPublic->community_id);
        $communities = Community::all();
        $communityVendors = DB::table('community_vendors')
            ->where('community_id', $community_id->id)
            ->join('vendor_usernames', 'community_vendors.vendor_username_id', 
                '=', 'vendor_usernames.id')
            ->select('vendor_usernames.name', 'community_vendors.id as id',
                'vendor_usernames.id as vendor_username_id')
            ->get();

        $publicStructures = PublicStructure::findOrFail($energyPublic->public_structure_id);
        $meterCases = MeterCase::all();
        $vendor = VendorUsername::where('id', $energyPublic->vendor_username_id)->first();

        $energySystems = EnergySystem::all();
        $donors = Donor::all();

        return view('users.energy.public.edit', compact('publicStructures', 'communities',
            'meterCases', 'energyPublic', 'communityVendors', 'vendor', 'energySystems',
            'energyDonors', 'donors'));
    }

    /**
     * Update an existing resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request, int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
       // dd($request->all());
        $energyPublic = AllEnergyMeter::find($id);

        $energyPublic->meter_number = $request->meter_number;
        $energyPublic->daily_limit = $request->daily_limit;
        $energyPublic->installation_date = $request->installation_date;
        if($request->misc) $energyPublic->misc = $request->misc;

        if($request->meter_active) $energyPublic->meter_active = $request->meter_active;

        if($request->vendor_username_id) $energyPublic->vendor_username_id = $request->vendor_username_id;

        if($request->energy_system_id) $energyPublic->energy_system_id = $request->energy_system_id;

        if($request->notes) $energyPublic->notes = $request->notes;


        if($request->meter_case_id == 1 || $request->meter_case_id == 2 ||
            $request->meter_case_id == 3 || $request->meter_case_id == 4 ||
            $request->meter_case_id == 5 || $request->meter_case_id == 6 ||
            $request->meter_case_id == 7 || $request->meter_case_id == 8 ||
            $request->meter_case_id == 9 || $request->meter_case_id == 10 ||
            $request->meter_case_id == 11 || $request->meter_case_id == 12 ||
            $request->meter_case_id == 13 || $request->meter_case_id == 14) 
        {

            if($request->meter_case_id == 1) {
                $household = Household::findOrFail($energyPublic->household_id);
                $household->household_status_id = 4;
                $household->energy_service = "Yes";
                $household->energy_meter = "Yes";
                $household->save();
            }

            $energyPublic->meter_case_id = $request->meter_case_id;
        }

        $energyPublic->save(); 

        if($request->donors) {
            for($i=0; $i < count($request->donors); $i++) {

                $energyMeterDonor = new AllEnergyMeterDonor();
                $energyMeterDonor->donor_id = $request->donors[$i];
                $energyMeterDonor->all_energy_meter_id = $id;
                $energyMeterDonor->community_id = $energyPublic->community_id;
                $energyMeterDonor->save();
            }
        }

        if($request->new_donors) {
            for($i=0; $i < count($request->new_donors); $i++) {

                $energyMeterDonor = new AllEnergyMeterDonor();
                $energyMeterDonor->donor_id = $request->new_donors[$i];
                $energyMeterDonor->all_energy_meter_id = $id;
                $energyMeterDonor->community_id = $energyPublic->community_id;
                $energyMeterDonor->save();
            }
        }

        return redirect('/energy-public')->with('message', 'Energy Public Updated Successfully!');
    }
}