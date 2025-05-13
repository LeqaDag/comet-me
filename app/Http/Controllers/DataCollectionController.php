<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Imports\ImportAcHousehold;
use App\Imports\ImportHousehold;
use App\Imports\ImportCommunity;
use App\Imports\ImportRequestedHousehold;
use App\Exports\DataCollection\DataCollectionExport;
use App\Exports\DataCollection\Households;
use App\Exports\DataCollection\RequestedHouseholds;
use App\Exports\DataCollection\AcSurvey\AllFormExport;
use App\Exports\DataCollection\CommunityCompound\CommunityCompoundExport;
use App\Exports\DataCollection\Community;
use App\Exports\DataCollection\Incidents\MainFileExport;
use App\Exports\DataCollection\Displacement\MainFileDisplacementExport;
use App\Exports\DataCollection\Agriculture\MainFile;
use App\Exports\DataCollection\MISC\MainMisc;
use App\Exports\DataCollection\Workshops\MainFileWorkshop;
use Illuminate\Support\Facades\URL;
use mikehaertl\wkhtmlto\Pdf;
use App\Models\Region;
use App\Models\SubRegion; 
use Auth;
use DB;
use Route;
use DataTables;
use Excel;
use Carbon\Carbon;

class DataCollectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {	
        if (Auth::guard('user')->user() != null) {

            $regions = Region::where('is_archived', 0)->get();
            $subregions = SubRegion::where('is_archived', 0)->get();

            return view('collection.index', compact('regions', 'subregions'));
        } else {

            return view('errors.not-found');
        }
    }

    /** 
     * 
     * @return \Illuminate\Support\Collection
     */
    public function exportHousehold(Request $request) 
    {
                
        return Excel::download(new Households($request), 'households.xlsx');
    }

    /** 
     * 
     * @return \Illuminate\Support\Collection
     */
    public function exportRequestedHouseholds(Request $request) 
    {
                
        return Excel::download(new RequestedHouseholds($request), 'requested_households.xlsx');
    }

    /** 
     * 
     * @return \Illuminate\Support\Collection
     */
    public function exportAll(Request $request) 
    {
                
        return Excel::download(new AllFormExport($request), 'all_data.xlsx');
    }

    /** 
     * 
     * @return \Illuminate\Support\Collection
     */
    public function export(Request $request) 
    {
                
        return Excel::download(new DataCollectionExport($request), 'Updating Households.xlsx');
    }

    /** 
     * 
     * @return \Illuminate\Support\Collection
     */
    public function exportCommunity(Request $request) 
    {
                
        return Excel::download(new CommunityCompoundExport($request), 'Updating Communities-Compounds.xlsx');
    }

    /** 
     * 
     * @return \Illuminate\Support\Collection
     */
    public function exportAllCommunities(Request $request) 
    {
                
        return Excel::download(new Community($request), 'communities.xlsx');
    }

    /** 
     * 
     * @return \Illuminate\Support\Collection
     */
    public function exportIncident(Request $request) 
    {
                
        return Excel::download(new MainFileExport($request), 'Incidents.xlsx');
    }

    /** 
     * 
     * @return \Illuminate\Support\Collection
     */
    public function exportDisplacement(Request $request) 
    {
                
        return Excel::download(new MainFileDisplacementExport($request), 'Displacement.xlsx');
    }

    /** 
     * 
     * @return \Illuminate\Support\Collection
     */
    public function exportAgriculture(Request $request) 
    {
                
        return Excel::download(new MainFile($request), 'Agriculture Survey.xlsx');
    }

    /** 
     * 
     * @return \Illuminate\Support\Collection
     */
    public function exportWorkshop(Request $request) 
    {
                
        return Excel::download(new MainFileWorkshop($request), 'Workshops.xlsx');
    }

    /** 
     * 
     * @return \Illuminate\Support\Collection
     */
    public function exportRequestedHousehold(Request $request) 
    {
                
        return Excel::download(new MainMisc($request), 'Requested Households.xlsx');
    }

     /**
     * 
     * @return \Illuminate\Support\Collection
     */
    public function importCommunity(Request $request)
    {
        $file = $request->file('excel_file');

        if ($file->isValid()) {

            $extension = $file->getClientOriginalExtension();
    
            if (in_array($extension, ['xlsx', 'xls', 'csv'])) {

                Excel::import(new ImportCommunity, $file);
            } else {

                return redirect()->back()->with('error', 'Invalid file format');
            }
    
            return redirect()->back()->with('success', 'Community Data Imported Successfully!');
        } else {

            return redirect()->back()->with('error', 'File upload failed');
        }
    }

    /**
     * 
     * @return \Illuminate\Support\Collection
     */
    public function import(Request $request)
    {
        $file = $request->file('excel_file');

        if ($file->isValid()) {

            $extension = $file->getClientOriginalExtension();
    
            if (in_array($extension, ['xlsx', 'xls', 'csv'])) {

                Excel::import(new ImportHousehold, $file);
            } else {

                return redirect()->back()->with('error', 'Invalid file format');
            }
    
            return redirect()->back()->with('success', 'Household Data Imported Successfully!');
        } else {

            return redirect()->back()->with('error', 'File upload failed');
        }
    }

    /**
     * 
     * @return \Illuminate\Support\Collection
     */
    public function importAc(Request $request)
    {
        $file = $request->file('excel_file');

        if ($file->isValid()) {

            $extension = $file->getClientOriginalExtension();
    
            if (in_array($extension, ['xlsx', 'xls', 'csv'])) {

                Excel::import(new ImportAcHousehold, $file);
            } else {

                return redirect()->back()->with('error', 'Invalid file format');
            }
     
            return redirect()->back()->with('success', 'AC Data Imported Successfully!');
        } else {

            return redirect()->back()->with('error', 'File upload failed');
        }
    }

    /**
     * 
     * @return \Illuminate\Support\Collection
     */
    public function importRequested(Request $request)
    {
        $file = $request->file('excel_file');

        if ($file->isValid()) {

            $extension = $file->getClientOriginalExtension();
    
            if (in_array($extension, ['xlsx', 'xls', 'csv'])) {

                Excel::import(new ImportRequestedHousehold, $file);
            } else {

                return redirect()->back()->with('error', 'Invalid file format');
            }
    
            return redirect()->back()->with('success', 'Requested Households Imported Successfully!');
        } else {

            return redirect()->back()->with('error', 'File upload failed');
        }
    }
}
