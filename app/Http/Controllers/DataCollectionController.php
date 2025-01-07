<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Imports\ImportHousehold;
use App\Exports\DataCollection\DataCollectionExport;
use App\Exports\DataCollection\Households;
use App\Exports\DataCollection\AllForm\AllFormExport;
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
}
