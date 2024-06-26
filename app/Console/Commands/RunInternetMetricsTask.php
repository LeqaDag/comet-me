<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use App\Models\InternetMetric;
use App\Models\InternetMetricCluster;
use App\Models\InternetCluster;
use App\Models\InternetClusterCommunity;
use Illuminate\Support\Facades\DB;

class RunInternetMetricsTask extends Command
{
    protected $signature = 'internet:metrics';

    protected $description = 'Update internet metrics';

    public function handle()
    {
        $dataApi = Http::get('http://185.190.140.86/api/data/');
        $clusterApi = Http::get('http://185.190.140.86/api/clusters/');

        $metrics = json_decode($dataApi, true);
        $clusters = json_decode($clusterApi, true);
    
        // $lastRecord = InternetMetric::latest('created_at')->first();
        // $date_from = Carbon::parse($lastRecord->date_to)->addDay(1)->toDateString();
        // $date_to = Carbon::now()->toDateString();

        $lastRecord = InternetMetric::latest('updated_at')->first();
        $date_from = Carbon::parse($lastRecord->date_to)->addDay(1); 
        $date_to = Carbon::now();

        // Calculate the difference in days (one week)
        $diffInDays = $date_from->diffInDays($date_to);

        // Check if the difference two dates is exactly one week (7 days) or greater, but not more than 9 days
        $isOneWeek = $diffInDays >= 7 && $diffInDays <= 9;

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
                $exist->total_cash = $metrics[0]["total_cash_income"];
                $exist->total_hotspot_communities = $metrics[0]["total_hotspot_communities"];
                $exist->total_broadband_communities = $metrics[0]["total_broadband_communities"];
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
                $internetMetric->total_cash = $metrics[0]["total_cash_income"];
                $internetMetric->total_hotspot_communities = $metrics[0]["total_hotspot_communities"];
                $internetMetric->total_broadband_communities = $metrics[0]["total_broadband_communities"];
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

        $this->info('Internet metrics updated successfully!');
    }
}
