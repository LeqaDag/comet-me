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
use App\Models\User;
use App\Models\Community;
use App\Models\CommunityDonor;
use App\Models\Donor;
use App\Models\InternetCluster; 
use App\Models\InternetClusterCommunity; 
use App\Models\InternetUser; 
use App\Models\InternetUserDonor;
use App\Models\InternetStatus;
use App\Models\InternetMetric;
use App\Models\InternetMetricCluster; 
use App\Models\InternetSystemType; 
use App\Models\Household;
use App\Models\PublicStructure;
use App\Models\Region; 
use Illuminate\Support\Facades\Http;
use App\Exports\InternetExport;
use Carbon\Carbon;
use Image;
use DataTables; 
use Excel;

class InternetUserController extends Controller
{

    public function getMetrix() {

        $dataApi = Http::get('http://185.190.140.86/api/data/');
        $clusterApi = Http::get('http://185.190.140.86/api/clusters/');

        $metrics = json_decode($dataApi, true);
        $clusters = json_decode($clusterApi, true);

        $lastRecord = InternetMetric::latest('updated_at')->first();
        $date_from = Carbon::parse($lastRecord->date_to)->addDay(1); 
        $date_to = Carbon::now();

        // Calculate the difference in days (one week)
        $diffInDays = $date_from->diffInDays($date_to);

        // Check if the difference two dates is exactly one week (7 days) or greater, but not more than 9 days
        $isOneWeek = $diffInDays >= 7 && $diffInDays <= 11;

        if ($isOneWeek) {
            
            $exist = InternetMetric::where("date_from", $date_from)->first();

            if($exist) {

                $exist->date_to = $date_to;
                $exist->total_community = $metrics[0]["total_communities"];
                $exist->active_contracts = $metrics[0]["total_active_contracts"];
                $exist->total_contracts = $metrics[0]["total_contracts"];
                $exist->active_community = $metrics[0]["total_active_communities"];
                $exist->inactive_community = $metrics[0]["total_inactive_communities"];
                $exist->expire_contacts_less_month = $metrics[0]["total_accounts_expired_less_30_days"];
                $exist->expire_contacts_over_month = $metrics[0]["total_accounts_expired_over_30_days"];
                $exist->expire_contacts = $metrics[0]["total_accounts_expired_less_30_days"] + 
                    $metrics[0]["total_accounts_expired_over_30_days"];
                $exist->sale_points = $metrics[0]["total_sale_points"];
                $exist->total_cash = $metrics[0]["total_paid_cash"]; 
                $exist->total_hotspot_communities = $metrics[0]["total_hotspot_communities"];
                $exist->total_broadband_communities = $metrics[0]["total_broadband_communities"];
                $exist->hotspot_expire_users = $metrics[0]["hotspot_expire_users"];
                $exist->ppp_total_users = $metrics[0]["ppp_total_users"];
                $exist->hotspot_total_users = $metrics[0]["hotspot_total_users"];
                $exist->hotspot_active_users = $metrics[0]["hotspot_active_users"];
                $exist->ppp_expire_users = $metrics[0]["ppp_expire_users"];
                $exist->ppp_active_users = $metrics[0]["ppp_active_users"];
                $exist->save();

            } else {
    
                $internetMetric = new InternetMetric();
                $internetMetric->date_from = $date_from;
                $internetMetric->date_to = $date_to;
                $internetMetric->total_community = $metrics[0]["total_communities"];
                $internetMetric->active_contracts = $metrics[0]["total_active_contracts"];
                $internetMetric->total_contracts = $metrics[0]["total_contracts"];
                $internetMetric->active_community = $metrics[0]["total_active_communities"];
                $internetMetric->inactive_community = $metrics[0]["total_inactive_communities"];
                $internetMetric->expire_contacts_less_month = $metrics[0]["total_accounts_expired_less_30_days"];
                $internetMetric->expire_contacts_over_month = $metrics[0]["total_accounts_expired_over_30_days"];
                $internetMetric->expire_contacts = $metrics[0]["total_accounts_expired_less_30_days"] + 
                    $metrics[0]["total_accounts_expired_over_30_days"];
                $internetMetric->sale_points = $metrics[0]["total_sale_points"];
                $internetMetric->total_cash = $metrics[0]["total_paid_cash"];
                $internetMetric->total_hotspot_communities = $metrics[0]["total_hotspot_communities"];
                $internetMetric->total_broadband_communities = $metrics[0]["total_broadband_communities"];
                $internetMetric->hotspot_expire_users = $metrics[0]["hotspot_expire_users"];
                $internetMetric->ppp_total_users = $metrics[0]["ppp_total_users"];
                $internetMetric->hotspot_total_users = $metrics[0]["hotspot_total_users"];
                $internetMetric->hotspot_active_users = $metrics[0]["hotspot_active_users"];
                $internetMetric->ppp_expire_users = $metrics[0]["ppp_expire_users"];
                $internetMetric->ppp_active_users = $metrics[0]["ppp_active_users"];
                $internetMetric->save();
            }
        
            $lastInsertedMetric = InternetMetric::where("date_from", $date_from)->first();
            if($lastInsertedMetric) {

                foreach($clusters as $cluster) {

                    $internetCluster = InternetCluster::where("name", $cluster["cluster_name"])->first();
                    
                    if($internetCluster) {

                        $internetMetricCluster = InternetMetricCluster::where("internet_cluster_id", 
                            $internetCluster->id)
                            ->where("internet_metric_id", $lastInsertedMetric->id)
                            ->first();
            
                            $userCountsByCluster = InternetClusterCommunity::join('internet_users', 
                                'internet_cluster_communities.community_id', 'internet_users.community_id')
                                ->join('internet_clusters', 'internet_cluster_communities.internet_cluster_id', 'internet_clusters.id')
                                ->where('internet_users.is_archived', 0)
                                ->where('internet_clusters.id', $internetCluster->id)
                                ->select(
                                    'internet_clusters.name', 
                                    DB::raw('COUNT(internet_users.community_id) as user_count'),
                                    DB::raw('COUNT(CASE WHEN internet_users.paid = 1 THEN 1 END) as total_paid'),
                                    DB::raw('COUNT(CASE WHEN internet_users.paid = 0 THEN 1 END) as total_un_paid'),
                                )
                                ->groupBy('internet_cluster_communities.internet_cluster_id')
                                ->first();
                            
                        if($internetMetricCluster) {
                        
                            $internetMetricCluster->source_of_connection = $cluster["isp"];
                            $internetMetricCluster->attached_communities = $cluster["attached_communities"];
                            $internetMetricCluster->active_contracts = $cluster["active_contracts"];
                            $internetMetricCluster->total_contracts = $cluster["active_contracts"] + $cluster["expired_contracts"];
                            $internetMetricCluster->weekly_max_in = $cluster["weekly_max_in"];
                            $internetMetricCluster->weekly_max_out = $cluster["weekly_max_out"];
                            $internetMetricCluster->weekly_avg_in = $cluster["weekly_avg_in"];
                            $internetMetricCluster->weekly_avg_out = $cluster["weekly_avg_out"];
                            $internetMetricCluster->weekly_now_in = $cluster["weekly_now_in"];
                            $internetMetricCluster->weekly_now_out = $cluster["weekly_now_out"];
                            $internetMetricCluster->monthly_max_in = $cluster["monthly_max_in"];
                            $internetMetricCluster->monthly_max_out = $cluster["monthly_max_out"];
                            $internetMetricCluster->monthly_avg_in = $cluster["monthly_avg_in"];
                            $internetMetricCluster->monthly_avg_out = $cluster["monthly_avg_out"];
                            $internetMetricCluster->monthly_now_in = $cluster["monthly_now_in"];
                            $internetMetricCluster->monthly_now_out = $cluster["monthly_now_out"];
                            
                            if($userCountsByCluster ) {

                                $internetMetricCluster->total_paid = $userCountsByCluster->total_paid;
                                $internetMetricCluster->total_unpaid = $userCountsByCluster->total_un_paid;
                            }

                            $internetMetricCluster->save();
                        } else { 
            
                            $newMetricCluster = new InternetMetricCluster();
                            $newMetricCluster->internet_metric_id = $lastInsertedMetric->id;
                            $newMetricCluster->internet_cluster_id = $internetCluster->id;
                            $newMetricCluster->source_of_connection = $cluster["isp"];
                            $newMetricCluster->attached_communities = $cluster["attached_communities"];
                            $newMetricCluster->total_contracts = $cluster["active_contracts"] + $cluster["expired_contracts"];
                            $newMetricCluster->active_contracts = $cluster["active_contracts"];
                            $newMetricCluster->weekly_max_in = $cluster["weekly_max_in"];
                            $newMetricCluster->weekly_max_out = $cluster["weekly_max_out"];
                            $newMetricCluster->weekly_avg_in = $cluster["weekly_avg_in"];
                            $newMetricCluster->weekly_avg_out = $cluster["weekly_avg_out"];
                            $newMetricCluster->weekly_now_in = $cluster["weekly_now_in"];
                            $newMetricCluster->weekly_now_out = $cluster["weekly_now_out"];
                            $newMetricCluster->monthly_max_in = $cluster["monthly_max_in"];
                            $newMetricCluster->monthly_max_out = $cluster["monthly_max_out"];
                            $newMetricCluster->monthly_avg_in = $cluster["monthly_avg_in"];
                            $newMetricCluster->monthly_avg_out = $cluster["monthly_avg_out"];
                            $newMetricCluster->monthly_now_in = $cluster["monthly_now_in"];
                            $newMetricCluster->monthly_now_out = $cluster["monthly_now_out"];
                        // $newMetricCluster->bandwidth_consumption = $cluster["total_bandwidth"];
                            if($userCountsByCluster ) {

                                $newMetricCluster->total_paid = $userCountsByCluster->total_paid;
                                $newMetricCluster->total_unpaid = $userCountsByCluster->total_un_paid;
                            }
                            
                            $newMetricCluster->save();
                        }

                    } else {

                        $internetCluster = new InternetCluster();
                        $internetCluster->name = $cluster["cluster_name"];
                        $internetCluster->save();
        
                        $userCountsByCluster = InternetClusterCommunity::join('internet_users', 
                            'internet_cluster_communities.community_id', 'internet_users.community_id')
                            ->join('internet_clusters', 'internet_cluster_communities.internet_cluster_id', 'internet_clusters.id')
                            ->where('internet_users.is_archived', 0)
                            ->where('internet_clusters.id', $internetCluster->id)
                            ->select( 
                                'internet_clusters.name', 
                                DB::raw('COUNT(internet_users.community_id) as user_count'),
                                DB::raw('COUNT(CASE WHEN internet_users.paid = 1 THEN 1 END) as total_paid'),
                                DB::raw('COUNT(CASE WHEN internet_users.paid = 0 THEN 1 END) as total_un_paid'),
                            )
                            ->groupBy('internet_cluster_communities.internet_cluster_id')
                            ->first();

                        $newMetricCluster = new InternetMetricCluster();
                        $newMetricCluster->internet_metric_id = $lastInsertedMetric->id;
                        $newMetricCluster->internet_cluster_id = $internetCluster->id;
                        $newMetricCluster->source_of_connection = $cluster["isp"];
                        $newMetricCluster->attached_communities = $cluster["attached_communities"];
                        $newMetricCluster->total_contracts = $cluster["active_contracts"] + $cluster["expired_contracts"];
                        $newMetricCluster->active_contracts = $cluster["active_contracts"];
                        $newMetricCluster->weekly_max_in = $cluster["weekly_max_in"];
                        $newMetricCluster->weekly_max_out = $cluster["weekly_max_out"];
                        $newMetricCluster->weekly_avg_in = $cluster["weekly_avg_in"];
                        $newMetricCluster->weekly_avg_out = $cluster["weekly_avg_out"];
                        $newMetricCluster->weekly_now_in = $cluster["weekly_now_in"];
                        $newMetricCluster->weekly_now_out = $cluster["weekly_now_out"];
                        $newMetricCluster->monthly_max_in = $cluster["monthly_max_in"];
                        $newMetricCluster->monthly_max_out = $cluster["monthly_max_out"];
                        $newMetricCluster->monthly_avg_in = $cluster["monthly_avg_in"];
                        $newMetricCluster->monthly_avg_out = $cluster["monthly_avg_out"];
                        $newMetricCluster->monthly_now_in = $cluster["monthly_now_in"];
                        $newMetricCluster->monthly_now_out = $cluster["monthly_now_out"];
                    // $newMetricCluster->bandwidth_consumption = $cluster["total_bandwidth"];

                        if($userCountsByCluster ) {

                            $newMetricCluster->total_paid = $userCountsByCluster->total_paid;
                            $newMetricCluster->total_unpaid = $userCountsByCluster->total_un_paid;
                        }
                        $newMetricCluster->save();
                    }
                }
            }
        } else {
            
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Auth::guard('user')->user() != null) {

            $this->getMetrix();

            $communityFilter = $request->input('community_filter');
            $clusterFilter = $request->input('cluster_filter');
            $dateFilter = $request->input('date_filter');

            if ($request->ajax()) {
  
                $data = DB::table('internet_users')
                    ->join('communities', 'internet_users.community_id', 'communities.id')
                    ->leftJoin('households', 'internet_users.household_id', 'households.id')
                    ->leftJoin('public_structures', 'internet_users.public_structure_id', 
                        'public_structures.id')
                    ->join('internet_statuses', 'internet_users.internet_status_id', 'internet_statuses.id')
                    ->where('internet_users.is_archived', 0);

                if($communityFilter != null) {

                    $data->where('communities.id', $communityFilter);
                }
                if ($clusterFilter != null) {

                    $data->leftJoin('internet_cluster_communities', 'communities.id', 
                            'internet_cluster_communities.community_id')
                        ->leftJoin('internet_clusters', 'internet_clusters.id',
                            'internet_cluster_communities.internet_cluster_id')
                        ->where('internet_clusters.id', $clusterFilter);
                }
                if ($dateFilter != null) {

                    $data->where('internet_users.start_date', $dateFilter);
                }
     
                $data->select(
                    'internet_users.number_of_people', 'internet_users.number_of_contract',
                    'internet_users.id as id', 'internet_users.created_at as created_at', 
                    'internet_users.updated_at as updated_at', 
                    'internet_users.start_date',
                    'public_structures.english_name as public_name',
                    'communities.english_name as community_name',
                    DB::raw('IFNULL(households.english_name, households.arabic_name) 
                        as household_name'),
                    'internet_statuses.name'
                )->latest();

                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {

                        $detailsButton = "<a type='button' class='detailsInternetButton' data-bs-toggle='modal' data-bs-target='#internetHolderDetails' data-id='".$row->id."'><i class='fa-solid fa-eye text-primary'></i></a>";
                        $updateButton = "<a type='button' class='updateInternetUser' data-id='".$row->id."' data-bs-toggle='modal' data-bs-target='#updateInternetUserModal' ><i class='fa-solid fa-pen-to-square text-success'></i></a>";
                        $deleteButton = "<a type='button' class='deleteInternetUser' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";
                        
                        if(Auth::guard('user')->user()->user_type_id == 1 || 
                            Auth::guard('user')->user()->user_type_id == 2 ||
                            Auth::guard('user')->user()->user_type_id == 6 ||
                            Auth::guard('user')->user()->user_type_id == 10||
                            Auth::guard('user')->user()->user_type_id == 13) 
                        {
                                
                            return  $detailsButton." ".$updateButton." ".$deleteButton;
                        } else return $detailsButton;
                    })
                    ->addColumn('holder', function($row) {

                        if($row->household_name != null) $holder = $row->household_name;
                        else if($row->public_name != null) $holder = $row->public_name;
                        else $holder = "";

                        return $holder;
                    })
                    ->filter(function ($instance) use ($request) {
                        if (!empty($request->get('search'))) {
                                $instance->where(function($w) use($request) {
                                $search = $request->get('search');
                                $w->orWhere('communities.english_name', 'LIKE', "%$search%")
                                ->orWhere('households.english_name', 'LIKE', "%$search%")
                                ->orWhere('communities.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('households.arabic_name', 'LIKE', "%$search%")
                                ->orWhere('internet_statuses.name', 'LIKE', "%$search%")
                                ->orWhere('internet_users.start_date', 'LIKE', "%$search%")
                                ->orWhere('internet_users.number_of_contract', 'LIKE', "%$search%")
                                ->orWhere('internet_users.number_of_people', 'LIKE', "%$search%")
                                ->orWhere('public_structures.english_name', 'LIKE', "%$search%")
                                ->orWhere('public_structures.arabic_name', 'LIKE', "%$search%");
                            });
                        }
                    })
                    ->rawColumns(['action', 'holder'])
                    ->make(true);
            }

            $communities = Community::where('is_archived', 0)
                ->orderBy('english_name', 'ASC')
                ->get();
            $donors = Donor::where('is_archived', 0)
                ->orderBy('donor_name', 'ASC')
                ->get(); 
    
            $dataApi = Http::get('http://185.190.140.86/api/data/');
            $clusterApi = Http::get('http://185.190.140.86/api/clusters/');

            $dataJson = json_decode($dataApi, true);
            $clustersJson = json_decode($clusterApi, true);

            $InternetUsersCounts = DB::table('internet_users')
                ->where('internet_users.is_archived', 0)
                ->whereNotNull('internet_users.household_id');

            $InternetPublicCount = DB::table('internet_users')
                ->where('internet_users.is_archived', 0)
                ->whereNull('internet_users.household_id')
                ->count();
            
            $allInternetPeople=0;

            $activeInternetCommuntiies = Community::where('internet_service', 'Yes');
            

            foreach($activeInternetCommuntiies->get() as $activeInternetCommuntiy) 
            {
                $allInternetPeople+= Household::where('community_id', $activeInternetCommuntiy->id)
                    ->where('is_archived', 0)
                    ->count();
            }
            $internetPercentage = round(($InternetUsersCounts->count())/$allInternetPeople * 100, 2);

            $activeInternetCommuntiiesCount = $activeInternetCommuntiies->count();

            $allContractHolders = DB::table('internet_users')
                ->where('internet_users.is_archived', 0)
                ->count();

            $allInternetUsersCounts = $InternetUsersCounts
                ->join('households', 'internet_users.household_id', 'households.id')
                ->where('households.internet_holder_young', 0)
                ->count();

            $youngInternetHolders = DB::table('internet_users')
                ->where('internet_users.is_archived', 0)
                ->join('households', 'internet_users.household_id', 'households.id')
                //->join('communities', 'internet_users.community_id', 'communities.id')
            // ->where('communities.internet_service', 'Yes')
                ->whereNotNull('internet_users.household_id')
                ->where('households.internet_holder_young', 1)
                ->count();

            $communitiesInternet = Community::where("internet_service", "yes")
                ->where('is_archived', 0)
                ->get(); 

            $internetClusters = InternetCluster::get();

            $data = DB::table('internet_metric_clusters')
                ->join('internet_metrics', 'internet_metrics.id', 'internet_metric_clusters.internet_metric_id')
                ->join('internet_clusters', 'internet_clusters.id', 'internet_metric_clusters.internet_cluster_id')
                ->where('internet_metric_clusters.total_unpaid', '!=', NULL)
                ->select(
                    'internet_metric_clusters.total_contracts',
                    'internet_metric_clusters.total_unpaid',
                    'internet_metrics.date_from',
                    'internet_metrics.date_to',
                    'internet_metrics.id',
                    'internet_clusters.name as cluster_name'
                )
                ->get();
            
            // Group data by date and cluster
            $groupedData = [];
            foreach ($data as $item) {
                $period = $item->date_from . ' - ' . $item->date_to;
                $clusterName = $item->cluster_name;

                if($item->total_unpaid) {

                    if (!isset($groupedData[$period][$clusterName])) {
                        $groupedData[$period][$clusterName] = [
                            'total_contracts' => 0,
                            'total_unpaid' => 0,
                            'date_from' => $item->date_from,
                            'date_to' => $item->date_to,
                        ];
                    }
                }

                $groupedData[$period][$clusterName]['total_contracts'] += $item->total_contracts;
                $groupedData[$period][$clusterName]['total_unpaid'] += $item->total_unpaid;
            }

            // Calculate percentage for each group
            $percentageData = [];
            foreach ($groupedData as $period => $clusterss) {

                foreach ($clusterss as $clusterName => $values) {

                    $totalContract = $values['total_contracts'];
                    $unpaid = $values['total_unpaid'];
                    $unpaidPercentage = number_format($totalContract != 0 ? ($values['total_unpaid'] / $totalContract) * 100 : 0, 2);
    
                    $percentageData[$period][$clusterName] = [
                        'total_unpaid' => $unpaid,
                        'total_contracts' => $totalContract,
                        'unpaid_percentage' => $unpaidPercentage,
                        'date_from' => $values['date_from'],
                        'date_to' => $values['date_to'],
                    ];
                }
            }

            $unPaidData =  DB::table('internet_metric_clusters')
                ->join('internet_metrics', 'internet_metrics.id', 'internet_metric_clusters.internet_metric_id')
                ->whereNotNull('internet_metric_clusters.total_unpaid') 
                ->select(
                    DB::raw('SUM(internet_metric_clusters.total_contracts) as total_contracts'),
                    DB::raw('SUM(internet_metric_clusters.total_unpaid) as total_unpaid'),
                    'internet_metrics.date_from',
                    'internet_metrics.date_to'
                )
                ->groupBy('internet_metrics.date_from')
                ->get();

            // Format the data for Chart.js
            $labels = [];
            $totalContractsData = [];
            $totalUnpaidData = [];

            foreach ($unPaidData as $data) {
                $labels[] = $data->date_to;
                $totalContractsData[] = $data->total_contracts;
                $totalUnpaidData[] = $data->total_unpaid;
            }

            // Prepare data for chart
            $chartData = [
                'labels' => $labels,
                'datasets' => [
                    [
                        'label' => 'Total Contracts',
                        'data' => $totalContractsData,
                        'backgroundColor' => 'rgba(54, 162, 235, 0.2)', 
                        'borderColor' => 'rgba(54, 162, 235, 1)', 
                        'borderWidth' => 1,
                    ],
                    [
                        'label' => 'Unpaid Contracts',
                        'data' => $totalUnpaidData,
                        'backgroundColor' => 'rgba(255, 99, 132, 0.2)', 
                        'borderColor' => 'rgba(255, 99, 132, 1)', 
                        'borderWidth' => 1,
                    ],
                ],
            ];

            return view('users.internet.index', compact('communities', 'donors', 'dataJson', 
                'clustersJson', 'internetPercentage', 'allInternetPeople', 'activeInternetCommuntiiesCount',
                'allContractHolders', 'allInternetUsersCounts', 'youngInternetHolders', 'chartData',
                'communitiesInternet', 'InternetPublicCount', 'internetClusters', 'percentageData')
            );
             
        } else {

            return view('errors.not-found');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $communities = Community::where('is_archived', 0)
            ->orderBy('english_name', 'ASC')
            ->get();
        $internetSystemTypes = InternetSystemType::orderBy('name', 'ASC')
            ->get();

        return view('users.internet.create', compact('communities', 'internetSystemTypes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $internetHolder = new InternetUser();
        $internetHolder->community_id = $request->community_id;
        if($request->public_user == "user") $internetHolder->household_id = $request->household_public_id;
        else if($request->public_user == "public") $internetHolder->public_structure_id = $request->household_public_id;
        $internetHolder->start_date = $request->start_date;
        if($request->internet_type == 2) $internetHolder->is_hotspot = 1;
        else if($request->internet_type == 3) $internetHolder->is_ppp = 1;
        $internetHolder->notes = $request->notes;
        $internetHolder->save();

        return redirect('/internet-user')
            ->with('message', 'New Contract Holder Added Successfully!');
    }

    /**
     * Show the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $household = null;
        $public = null;
        $energyHolder = null;

        $internetUser = InternetUser::findOrFail($id);

        if($internetUser->household_id) {

            $household = Household::where('id', $internetUser->household_id)->first();
            $energyHolder = AllEnergyMeter::where('is_archived', 0)
                ->where('household_id', $internetUser->household_id)
                ->first();
        }
        if($internetUser->public_structure_id) {

            $public = PublicStructure::where('id', $internetUser->public_structure_id)->first();
            $energyHolder = AllEnergyMeter::where('is_archived', 0)
                ->where('public_structure_id', $internetUser->public_structure_id)
                ->first();
        }

        $community = Community::where('id', $internetUser->community_id)->first();
        $internetStatus = InternetStatus::where('id', $internetUser->internet_status_id)->first();
        $donors = DB::table('internet_user_donors')
            ->where('internet_user_donors.is_archived', 0)
            ->where('internet_user_donors.internet_user_id', $id)
            ->join('donors', 'internet_user_donors.donor_id', 'donors.id')
            ->select('donors.donor_name', 'internet_user_donors.internet_user_id')
            ->get();

        $internetIncidents = DB::table('internet_user_incidents')
            ->join('internet_users', 'internet_user_incidents.internet_user_id', 
                'internet_users.id')
            ->join('incidents', 'internet_user_incidents.incident_id', 'incidents.id')
            ->leftJoin('internet_incident_statuses', 'internet_user_incidents.internet_incident_status_id', 
                'internet_incident_statuses.id')
            ->where('internet_user_incidents.is_archived', 0)
            ->where('internet_user_incidents.internet_user_id', $id)
            ->select('internet_user_incidents.date as incident_date',
                'incidents.english_name as incident', 
                'internet_incident_statuses.name as incident_status',
                'internet_user_incidents.response_date')
            ->get(); 

        $response['household'] = $household;
        $response['public'] = $public;
        $response['internetUser'] = $internetUser;
        $response['community'] = $community;
        $response['internetStatus'] = $internetStatus;
        $response['donors'] = $donors;
        $response['internetIncidents'] = $internetIncidents;
        $response['energyHolder'] = $energyHolder;

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
        $allInternetHolder = InternetUser::findOrFail($id);

        return response()->json($allInternetHolder);
    }

    /**
     * View Edit page.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $allInternetHolder = InternetUser::findOrFail($id);
        $allInternetHolderDonors = InternetUserDonor::where("internet_user_id", $id)
            ->where('is_archived', 0)->get(); 
        $donors = Donor::where('is_archived', 0)->get();

        return view('users.internet.all.edit', compact('allInternetHolder', 
            'allInternetHolderDonors', 'donors'));
    }

    /**
     * Update an existing resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request, int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $internetUser = InternetUser::findOrFail($id);
 
        if($request->household_english_name) {

            $household = Household::findOrFail($internetUser->household_id);
            $household->english_name = $request->household_english_name;
            $household->save();
        } else if($request->public_english_name) {

            $public = PublicStructure::findOrFail($internetUser->public_structure_id);
            $public->english_name = $request->public_english_name;
            $public->save();
        }
    
        if($request->donors) {

            for($i=0; $i < count($request->donors); $i++) {

                $internetHolderDonor = new InternetUserDonor();
                $internetHolderDonor->donor_id = $request->donors[$i];
                $internetHolderDonor->internet_user_id = $id;
                $internetHolderDonor->community_id = $internetUser->community_id;
                $internetHolderDonor->save();
            }
        }

        if($request->new_donors) {

            for($i=0; $i < count($request->new_donors); $i++) {

                $internetHolderDonor = new InternetUserDonor();
                $internetHolderDonor->donor_id = $request->new_donors[$i];
                $internetHolderDonor->internet_user_id = $id;
                $internetHolderDonor->community_id = $internetUser->community_id;
                $internetHolderDonor->save();
            }
        }

        return redirect('/internet-user')->with('message', 'Internet User Updated Successfully!');
    }

    /**
     * Get households by community_id.
     *
     * @param  int $id, String $is_household
     * @return \Illuminate\Http\Response
     */
    public function getHousholdsPublicByCommunity(int $id, String $is_household)
    {
        $html = '<option selected disabled>Choose One...</option>';

        if($is_household == "user") {

            $holders = Household::where('community_id', $id)
                ->where('is_archived', 0)
                ->orderBy('english_name', 'ASC')
                ->get();
        } else if($is_household == "public") {

            $holders = PublicStructure::where('community_id', $id)
                ->where('is_archived', 0)
                ->orderBy('english_name', 'ASC')
                ->get();
        }

        foreach ($holders as $holder) {

            $name = "";
            if($holder->english_name == null) $name = $holder->arabic_name;
            else $name = $holder->english_name;

            $html .= '<option value="'. $holder->id. '">'. $name. '</option>';
        }

        return response()->json(['html' => $html]);
    }

    /**
     * Get households by community_id.
     *
     * @param  int $id, String $is_household
     * @return \Illuminate\Http\Response
     */
    public function getDetailsByHouseholdPublic(int $id, String $is_household)
    {
        if (!$id) {

            $holders = '';
            $internetDetails = '';
        } else {

            if($is_household == "user") {

                $holders = DB::table('households') 
                    ->leftJoin('all_energy_meters', 'households.id', 'all_energy_meters.household_id')
                    ->where('households.is_archived', 0)
                    ->where('households.id', $id)
                    ->select(
                        'all_energy_meters.is_main', 'all_energy_meters.meter_number',
                        'households.internet_holder_young')                        
                    ->first();

                $mainUser = DB::table('household_meters') 
                    ->join('all_energy_meters', 'all_energy_meters.id', 'household_meters.energy_user_id')
                    ->join('households', 'households.id', 'all_energy_meters.household_id')
                    ->where('household_meters.is_archived', 0)
                    ->where('household_meters.household_id', $id)
                    ->select('households.english_name') 
                    ->first();

                $internetDetails = InternetUser::where('household_id', $id)
                    ->where('is_archived', 0)
                    ->first();
            } else if($is_household == "public") {

                $holders = AllEnergyMeter::where('public_structure_id', $id)
                    ->where('is_archived', 0)
                    ->select('is_main', 'meter_number', 'public_structure_id as Public')
                    ->first();

                $mainUser = DB::table('household_meters') 
                    ->join('all_energy_meters', 'all_energy_meters.id', 'household_meters.energy_user_id')
                    ->leftJoin('public_structures', 'public_structures.id', 'all_energy_meters.public_structure_id')
                    ->leftJoin('households', 'households.id', 'all_energy_meters.household_id')
                    ->where('household_meters.is_archived', 0)
                    ->where('household_meters.public_structure_id', $id)
                    ->select(DB::raw('IFNULL(households.english_name, public_structures.english_name) 
                        as english_name'),) 
                    ->first();

                $internetDetails = InternetUser::where('public_structure_id', $id)
                    ->where('is_archived', 0)
                    ->first();
            }
        }

        return response()->json([
            'holders' => $holders,
            'mainUser' => $mainUser,
            'internetDetails' => $internetDetails
        ]);
    }

    /**
     * Delete a resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteInternetHolder(Request $request)
    {
        $id = $request->id;
        $internetHolder = InternetUser::findOrFail($id);

        if($internetHolder) {

            $internetHolder->is_archived = 1;
            $internetHolder->save();

            $response['success'] = 1;
            $response['msg'] = 'Internet User Deleted successfully'; 
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
    public function deleteInternetDonor(Request $request)
    {
        $id = $request->id;
        $internetHolderDonor = InternetUserDonor::findOrFail($id);

        if($internetHolderDonor) {

            $internetHolderDonor->delete();

            $response['success'] = 1;
            $response['msg'] = 'Internet Donor Deleted successfully'; 
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
                
        return Excel::download(new InternetExport($request), 'internet_holders_metrics.xlsx');
    }
}