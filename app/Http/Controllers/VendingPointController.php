<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\VendorRegion;
use App\Models\Vendor;
use App\Models\VendorUserName;
use App\Models\CommunityVendor;
use App\Models\User;
use App\Models\Community;
use App\Models\Region;
use App\Models\Town;
use App\Exports\MissingHouseholdExport;
use Carbon\Carbon;
use Auth;
use DB;
use DataTables;
use Excel;
use Route;

class VendingPointController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {	
        if (Auth::guard('user')->user() != null) {
 
            $regionFilter = $request->input('region_filter');
            $communityFilter = $request->input('community_filter');
            $townFilter = $request->input('town_filter');

            if ($request->ajax()) {
                
                $data = DB::table('vendors')
                    ->join('vendor_user_names', 'vendors.vendor_username_id', 'vendor_user_names.id')
                    ->leftJoin('vendor_regions', 'vendors.vendor_region_id', 'vendor_regions.id')
                    ->leftJoin('communities', 'vendors.community_id', 'communities.id')
                    ->leftJoin('towns', 'vendors.town_id', 'towns.id')
                    ->where('vendors.is_archived', 0);

                if ($regionFilter != null) {

                    $data->where('vendor_regions.id', $regionFilter);
                }
                if ($communityFilter != null) {
                    
                    $data->where('communities.id', $communityFilter);
                }
                if ($townFilter != null) {
                    
                    $data->where('towns.id', $townFilter);
                }

                $data->select(
                    'vendors.english_name as english_name',
                    'vendors.arabic_name as arabic_name',
                    'vendor_user_names.name', 'vendors.phone_number',
                    'vendors.id as id', 'vendor_regions.english_name as region', 
                    'vendors.created_at as created_at',
                    'vendors.updated_at as updated_at'
                    )
                    ->groupBy('vendors.id')
                    ->latest();

                if (!empty($request->get('search'))) {
                    $search = $request->get('search');
                    $data->where(function($w) use ($search) {
                        $w->orWhere('vendors.english_name', 'LIKE', "%$search%")
                          ->orWhere('vendor_regions.english_name', 'LIKE', "%$search%")
                          ->orWhere('vendor_regions.arabic_name', 'LIKE', "%$search%")
                          ->orWhere('communities.arabic_name', 'LIKE', "%$search%")
                          ->orWhere('communities.english_name', 'LIKE', "%$search%")
                          ->orWhere('towns.arabic_name', 'LIKE', "%$search%")
                          ->orWhere('towns.english_name', 'LIKE', "%$search%")
                          ->orWhere('vendors.arabic_name', 'LIKE', "%$search%");
                    });
                }

                $data = $data->get();

                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
                        $detailsButton = "<a type='button' class='detailsVendorButton' data-bs-toggle='modal' data-bs-target='#vendorDetails' data-id='".$row->id."'><i class='fa-solid fa-eye text-primary'></i></a>";
                        $updateButton = "<a type='button' class='updateVendor' data-id='".$row->id."'><i class='fa-solid fa-pen-to-square text-success'></i></a>";
                        $deleteButton = "<a type='button' class='deleteVendor' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";
                        
                        if(Auth::guard('user')->user()->user_type_id != 1 || 
                            Auth::guard('user')->user()->user_type_id != 2 || 
                            Auth::guard('user')->user()->user_type_id != 4) 
                        {
                                
                            return $detailsButton." ". $updateButton." ".$deleteButton;
                        } else return $detailsButton; 

                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
             
            $communities = Community::where('is_archived', 0)
                ->orderBy('english_name', 'ASC')
                ->get();
            $vendorRegions = VendorRegion::where('is_archived', 0)->get();
            $vendorUsers = VendorUserName::all();
            $towns = Town::where('is_archived', 0)
                ->orderBy('english_name', 'ASC')
                ->get();

            return view('vendor.index', compact('communities', 'vendorRegions', 'vendorUsers', 'towns'));
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
        $vendor = new Vendor();
        $vendor->english_name = $request->english_name;
        $vendor->arabic_name = $request->arabic_name;
        $vendor->vendor_region_id = $request->vendor_region_id;
        $vendor->vendor_username_id  = $request->vendor_user_name_id;
        $vendor->phone_number = $request->phone_number;
        $vendor->additional_phone_number = $request->additional_phone_number;
        $vendor->notes = $request->notes;
        if($request->community_town == "community") $vendor->community_id = $request->community_town_id;
        else if($request->community_town == "town") $vendor->town_id = $request->community_town_id;
        $vendor->save();

        if($request->community_id) {
            for($i=0; $i < count($request->community_id); $i++) {

                $communityVendor = new CommunityVendor();
                $communityVendor->community_id = $request->community_id[$i];
                $communityVendor->vendor_username_id = $request->vendor_user_name_id;
                $communityVendor->save();
            }
        }

        return redirect('/vendor')
            ->with('message', 'New Vending Point Added Successfully!');
    }

    /**
     * Show the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    { 
        $town = null; 
        $community = null;
        $vendorRegion = null;

        $vendor = Vendor::findOrFail($id);
        if($vendor->vendor_region_id) $vendorRegion = VendorRegion::findOrFail($vendor->vendor_region_id);
        $vendorUserName = VendorUserName::findOrFail($vendor->vendor_username_id);
            
        if($vendor->community_id) $community = Community::findOrFail($vendor->community_id);
        if($vendor->town_id) $town = Town::findOrFail($vendor->town_id);

        $vendorCommunities = DB::table('community_vendors')
            ->join('communities', 'community_vendors.community_id', 'communities.id')
            ->where('community_vendors.vendor_username_id', $vendor->vendor_username_id)
            ->select('communities.english_name')
            ->get();
           
        $response['town'] = $town;
        $response['community'] = $community;
        $response['vendor'] = $vendor;
        $response['vendorRegion'] = $vendorRegion;
        $response['vendorUserName'] = $vendorUserName;
        $response['vendorCommunities'] = $vendorCommunities;

        return response()->json($response);
    }

    /**
     * View Edit page.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) 
    {
        $vendingPoint = Vendor::findOrFail($id);
        $vendingRegion = null;
        $community = null;
        $town = null;

        if($vendingPoint->vendor_region_id) $vendingRegion = VendorRegion::findOrFail($vendingPoint->vendor_region_id);
        $vendorUserName = VendorUserName::findOrFail($vendingPoint->vendor_username_id);

        if($vendingPoint->community_id) $community = Community::findOrFail($vendingPoint->community_id);
        if($vendingPoint->town_id) $town = Town::findOrFail($vendingPoint->town_id);

        $communities = Community::where('is_archived', 0)
            ->orderBy('english_name', 'ASC')
            ->get();
        $vendorRegions = VendorRegion::where('is_archived', 0)->get();
        $vendorUsers = VendorUserName::all();
        $towns = Town::where('is_archived', 0)
            ->orderBy('english_name', 'ASC')
            ->get();

        $vendorCommunities = DB::table('community_vendors')
            ->join('communities', 'community_vendors.community_id', 'communities.id')
            ->where('community_vendors.vendor_username_id', $vendingPoint->vendor_username_id)
            ->select('communities.english_name', 'communities.id as id')
            ->get();

        return view('vendor.edit', compact('vendingPoint', 'vendingRegion', 'vendorUserName', 'communities',
            'community', 'town', 'vendorRegions', 'vendorUsers', 'towns', 'vendorCommunities'));
    }
    
    /**
     * Update an existing resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request, int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $vendor = Vendor::findOrFail($id);
        if($request->english_name) $vendor->english_name = $request->english_name;
        if($request->arabic_name) $vendor->arabic_name = $request->arabic_name;
        if($request->vendor_region_id) $vendor->vendor_region_id = $request->vendor_region_id;
        if($request->vendor_username_id) $vendor->vendor_username_id  = $request->vendor_user_name_id;
        if($request->phone_number) $vendor->phone_number = $request->phone_number;
        if($request->additional_phone_number) $vendor->additional_phone_number = $request->additional_phone_number;
        if($request->notes) $vendor->notes = $request->notes;
        if($request->community_id) $vendor->community_id = $request->community_town_id;
        if($request->town_id) $vendor->town_id = $request->community_town_id;
        if($request->community_town == "community") $vendor->community_id = $request->community_town_id;
        else if($request->community_town == "town") $vendor->town_id = $request->community_town_id;
        $vendor->save();

        if($request->new_community) {
 
            for($i=0; $i < count($request->new_community); $i++) {

                $communityVendingPoint = new CommunityVendor();
                $communityVendingPoint->community_id = $request->new_community[$i];
                $communityVendingPoint->vendor_username_id = $vendor->vendor_username_id;
                $communityVendingPoint->save();
            }
        }

        if($request->more_community) {

            for($i=0; $i < count($request->more_community); $i++) {

                $communityVendingPoint = new CommunityVendor();
                $communityVendingPoint->community_id = $request->more_community[$i];
                $communityVendingPoint->vendor_username_id = $vendor->vendor_username_id;
                $communityVendingPoint->save();
            }
        }

        return redirect('/vending-point')->with('message', 'Vending Point Updated Successfully!');
    }

    /**
     * Get households by community_id.
     *
     * @param  String $is_household
     * @return \Illuminate\Http\Response
     */
    public function getVendingPointPlace(String $community_town)
    {
        $html = "<option disabled selected>Choose one...</option>";

        if($community_town == "community") {

            $places = Community::where('is_archived', 0)
                ->orderBy('english_name', 'ASC')
                ->get();
        } else if($community_town == "town") {

            $places = Town::where('is_archived', 0)
                ->orderBy('english_name', 'ASC')
                ->get(); 
        }
        
        foreach($places as $place) {

            $html .= '<option value="'.$place->id.'">'.$place->english_name.'</option>';
        }

        return response()->json([
            'html' => $html
        ]);
    }

    /**
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteVendor(Request $request)
    {
        $id = $request->id;

        $vendor = Vendor::find($id);

        if($vendor) {

            $vendor->is_archived = 1;
            $vendor->save();

            $response['success'] = 1;
            $response['msg'] = 'Vending Point Deleted successfully'; 
        } else {

            $response['success'] = 0;
            $response['msg'] = 'Invalid ID.';
        }

        return response()->json($response); 
    }

    /**
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteServedCommunity(Request $request)
    {
        $id = $request->id;

        $communityVendingPoint = CommunityVendor::find($id);

        if($communityVendingPoint) {

            $communityVendingPoint->is_archived = 1;
            $communityVendingPoint->save();

            $response['success'] = 1;
            $response['msg'] = 'Served Community Deleted successfully'; 
        } else {

            $response['success'] = 0;
            $response['msg'] = 'Invalid ID.';
        }

        return response()->json($response); 
    }
}
 