<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\User;
use App\Models\Community;
use App\Models\CommunityDonor;
use App\Models\CommunityStatus;
use App\Models\CommunityRepresentative;
use App\Models\CommunityRole;
use App\Models\Compound;
use App\Models\Donor;
use App\Models\EnergySystem;
use App\Models\EnergySystemType;
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
use App\Models\Town;
use Carbon\Carbon;
use Auth;
use DataTables;
use DB;
use Image;
use Route;

class EnergySystemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {	
        if ($request->ajax()) {

            $data = DB::table('energy_systems')
                ->join('energy_system_types', 'energy_systems.energy_system_type_id', 
                    '=', 'energy_system_types.id')
                ->select('energy_systems.id as id', 'energy_systems.created_at',
                    'energy_systems.updated_at', 'energy_systems.name',
                    'energy_systems.installation_year', 'energy_systems.upgrade_year1',
                    'energy_system_types.name as type')
                ->latest();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row) {

                    $viewButton = "<a type='button' class='viewEnergySystem' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#viewEnergySystemModal' ><i class='fa-solid fa-eye text-info'></i></a>";

                    return $viewButton;
                })
               
                ->filter(function ($instance) use ($request) {
                    if (!empty($request->get('search'))) {
                            $instance->where(function($w) use($request){
                            $search = $request->get('search');
                            $w->orWhere('energy_systems.name', 'LIKE', "%$search%")
                            ->orWhere('energy_systems.installation_year', 'LIKE', "%$search%")
                            ->orWhere('energy_systems.upgrade_year1', 'LIKE', "%$search%")
                            ->orWhere('energy_system_types.name', 'LIKE', "%$search%");
                        });
                    }
                })
            ->rawColumns(['action'])
            ->make(true);
        }

        $communities = Community::all();
		$donors = Donor::paginate();
        $services = ServiceType::all();

        $dataEnergySystem = DB::table('energy_systems')
            ->join('energy_system_types', 'energy_systems.energy_system_type_id', 
                '=', 'energy_system_types.id')
            ->select(
                DB::raw('energy_system_types.name as name'),
                DB::raw('count(*) as number'))
            ->groupBy('energy_system_types.name')
            ->get();
        $arrayEnergySystem[] = ['System Type', 'Number'];
        
        foreach($dataEnergySystem as $key => $value) {

            $arrayEnergySystem[++$key] = 
            [$value->name, $value->number];
        }

		return view('system.energy.index', compact('communities', 'donors', 'services'))
        ->with(
            'energySystemData', json_encode($arrayEnergySystem));

    }
}