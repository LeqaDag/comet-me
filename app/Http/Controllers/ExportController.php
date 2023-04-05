<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\User;
use App\Models\Community;
use Carbon\Carbon;
use App\Models\CommunityDonor;
use App\Models\CommunityStatus;
use App\Models\CommunityRepresentative;
use App\Models\CommunityRole;
use App\Models\Compound;
use App\Models\Donor;
use App\Models\EnergySystem;
use App\Models\EnergySystemType;
use App\Models\EnergyUser;
use App\Models\Household;
use App\Models\HouseholdMeter;
use App\Models\H2oUser;
use App\Models\GridUser;
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
use App\Models\BsfStatus;
use App\Models\H2oSharedUser;
use App\Models\H2oStatus;
use App\Models\Incident;
use App\Models\MgIncident;
use App\Models\IncidentStatusMgSystem;
use App\Models\InternetUser;
use App\Models\MeterList;
use Auth;
use Route;
use DB;
use Excel;
use PDF;
use App\Exports\EnergyUsersExport;
use App\Imports\EnergyUsersImport;

class ExportController extends Controller
{
    // /**
    //  * Build DataTable class.
    //  *
    //  * @param Controller\ExportDataTable $dataTable
    //  * @return 
    //  */
    // public function index(ExportDataTable $dataTable)
    // {
        
    //     return $dataTable->render('export');
    // }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function index()
    {
        $energyUsers = DB::table('energy_users')
            ->join('energy_systems', 'energy_users.energy_system_id', '=', 'energy_systems.id')
            ->join('households', 'energy_users.household_id', '=', 'households.id')
            ->join('energy_donors', 'energy_users.household_id', '=', 'energy_donors.household_id')
            ->where('energy_donors.donor_id', '=', 1)
            ->where(function ($query) {
                $query->where("energy_users.energy_system_id", 62)
                      ->orWhere("energy_users.energy_system_id", 61);
            })
            ->join('professions', 'households.profession_id', '=', 'professions.id')
            ->join('communities', 'energy_users.community_id', '=', 'communities.id')
            ->join('regions', 'communities.region_id', '=', 'regions.id')
            ->join('sub_regions', 'communities.sub_region_id', '=', 'sub_regions.id')
            ->join('donors', 'energy_donors.donor_id', '=', 'donors.id')
            ->select('households.english_name as english_name', 'households.arabic_name as arabic_name',
                'communities.english_name as name', 'regions.english_name as region',
                'sub_regions.english_name as sub_region',
                'professions.profession_name as profession_name',
                'households.phone_number', 'households.number_of_male', 
                'households.number_of_female', 'households.number_of_adults', 
                'households.number_of_children', 'households.size_of_herd', 
                'energy_users.meter_number', 'energy_users.daily_limit', 
                'energy_users.installation_date', 'energy_systems.name as energy_system',
                'donors.donor_name')
            ->get();
   
        return view('export', compact('energyUsers'));
    }
        
    /**
    * @return \Illuminate\Support\Collection
    */
    public function export() 
    {
        return Excel::download(new EnergyUsersExport, 'users.xlsx');
    }
       
    /**
    * @return \Illuminate\Support\Collection
    */
    public function import() 
    {
        Excel::import(new EnergyUsersImport, request()->file('file'));
               
        return back();
    }
}
