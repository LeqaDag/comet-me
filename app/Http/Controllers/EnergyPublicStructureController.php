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
        if ($request->ajax()) {

            $dataPublic = DB::table('energy_public_structures')
                ->join('public_structures', 'energy_public_structures.public_structure_id', '=', 'public_structures.id')
                ->join('communities', 'public_structures.community_id', '=', 'communities.id')
                ->join('energy_systems', 'energy_public_structures.energy_system_id', '=', 'energy_systems.id')
                ->join('energy_system_types', 'energy_public_structures.energy_system_type_id', '=', 'energy_system_types.id')
                ->join('meter_cases', 'energy_public_structures.meter_case_id', '=', 'meter_cases.id')
                ->select('energy_public_structures.meter_number', 
                    'energy_public_structures.id as id', 'energy_public_structures.created_at as created_at', 
                    'energy_public_structures.updated_at as updated_at', 
                    'communities.english_name as community_name',
                    'public_structures.english_name as public_name',
                    'energy_systems.name as energy_name', 
                    'energy_system_types.name as energy_type_name',)
                ->latest(); 

             
            return Datatables::of($dataPublic)
                ->addIndexColumn()
                ->addColumn('action', function($row) {

                    $updateButton = "<a type='button' class='updateEnergyPublic' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#updateEnergyUserModal' ><i class='fa-solid fa-pen-to-square text-success'></i></a>";
                    $deleteButton = "<a type='button' class='deleteEnergyPublic' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";
                    
                    return $updateButton." ".$deleteButton;
   
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
                            ->orWhere('energy_public_structures.meter_number', 'LIKE', "%$search%");
                        });
                    }
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $communities = Community::all();
        $households = Household::all();
        $energySystems = EnergySystem::all();
        $energySystemTypes = EnergySystemType::all();
        $meters = MeterCase::all();
        $energyUsersNumbers = EnergyUser::count();
        $energyMgNumbers = EnergyUser::where("energy_system_type_id", 1)
            ->where("meter_active", "Yes")
            ->count();
        $energyFbsNumbers = EnergyUser::where("energy_system_type_id", 2)
            ->where("meter_active", "Yes")
            ->count();
        $energyMmgNumbers = EnergyUser::where("energy_system_type_id", 3)
            ->where("meter_active", "Yes")
            ->count();
        $energySmgNumbers = EnergyUser::where("energy_system_type_id", 4)
            ->where("meter_active", "Yes")
            ->count();
        $householdMeterNumbers = HouseholdMeter::count();

        $schools = DB::table('energy_public_structures')
            ->join('public_structures', 'energy_public_structures.public_structure_id', 
                '=', 'public_structures.id')
            ->join('public_structure_categories', 'public_structures.public_structure_category_id1', 
                '=', 'public_structure_categories.id')
            ->where('public_structures.public_structure_category_id1', 1)
            ->orWhere('public_structures.public_structure_category_id2', 1)
            ->orWhere('public_structures.public_structure_category_id3', 1)
            ->count();

        $clinics = DB::table('energy_public_structures')
            ->join('public_structures', 'energy_public_structures.public_structure_id', 
                '=', 'public_structures.id')
            ->join('public_structure_categories', 'public_structures.public_structure_category_id1', 
                '=', 'public_structure_categories.id')
            ->where('public_structures.public_structure_category_id1', 3)
            ->orWhere('public_structures.public_structure_category_id2', 3)
            ->orWhere('public_structures.public_structure_category_id3', 3)
            ->count();  

        $mosques = DB::table('energy_public_structures')
            ->join('public_structures', 'energy_public_structures.public_structure_id', 
                '=', 'public_structures.id')
            ->join('public_structure_categories', 'public_structures.public_structure_category_id1', 
                '=', 'public_structure_categories.id')
            ->where('public_structures.public_structure_category_id1', 2)
            ->orWhere('public_structures.public_structure_category_id2', 2)
            ->orWhere('public_structures.public_structure_category_id3', 2)
            ->count(); 

        $madafah = DB::table('energy_public_structures')
            ->join('public_structures', 'energy_public_structures.public_structure_id', 
                '=', 'public_structures.id')
            ->join('public_structure_categories', 'public_structures.public_structure_category_id1', 
                '=', 'public_structure_categories.id')
            ->where('public_structures.public_structure_category_id1', 7)
            ->orWhere('public_structures.public_structure_category_id2', 7)
            ->orWhere('public_structures.public_structure_category_id3', 7)
            ->count(); 

        $kindergarten = DB::table('energy_public_structures')
            ->join('public_structures', 'energy_public_structures.public_structure_id', 
                '=', 'public_structures.id')
            ->join('public_structure_categories', 'public_structures.public_structure_category_id1', 
                '=', 'public_structure_categories.id')
            ->where('public_structures.public_structure_category_id1', 5)
            ->orWhere('public_structures.public_structure_category_id2', 5)
            ->orWhere('public_structures.public_structure_category_id3', 5)
            ->count();

        $center = DB::table('energy_public_structures')
            ->join('public_structures', 'energy_public_structures.public_structure_id', 
                '=', 'public_structures.id')
            ->join('public_structure_categories', 'public_structures.public_structure_category_id1', 
                '=', 'public_structure_categories.id')
            ->where('public_structures.public_structure_category_id1', 6)
            ->orWhere('public_structures.public_structure_category_id2', 6)
            ->orWhere('public_structures.public_structure_category_id3', 6)
            ->count(); 

        $dataPublicStructures = DB::table('energy_public_structures')
            ->join('meter_cases', 'energy_public_structures.meter_case_id', '=', 'meter_cases.id')
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
        $publicEnergy = new EnergyPublicStructure();
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

        $energyPublic = EnergyPublicStructure::find($id);

        if($energyPublic->delete()) {

            $response['success'] = 1;
            $response['msg'] = 'Energy Public Structure Delete successfully'; 
        } else {

            $response['success'] = 0;
            $response['msg'] = 'Invalid ID.';
        }

        return response()->json($response); 
    }
}
