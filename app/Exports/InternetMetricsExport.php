<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell; 
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Illuminate\Support\Facades\Http;
use App\Models\Community;
use App\Models\InternetUser;
use App\Models\InternetCluster;
use App\Models\InternetMetric;
use App\Models\InternetMetricCluster;
use App\Models\InternetClusterCommunity;
use App\Models\Household;
use Carbon\Carbon;
use DB; 

class InternetMetricsExport implements FromCollection, WithTitle, 
    WithStyles, WithCustomStartCell, WithMapping, ShouldAutoSize, WithColumnFormatting
{
    protected $request;
    protected $query;

    function __construct($request) {

        $this->request = $request;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
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

        // Check if the difference two dates is exactly one week (7 days) or greater, but not more than 11 days
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

        $data = DB::table('internet_metrics');

        $this->query = $data->get();

        return $data->get();
    }

    /**
     * Start Cell
     *
     * @return response()
     */
    public function startCell(): string
    {
        return 'B2';
    }

    /**
     * Values
     *
     * @return response()
     */
    public function map($row): array
    {
        return [
            $row->active_community,
            $row->inactive_community,
            $row->total_hotspot_communities,
            $row->total_broadband_communities,
            $row->total_contracts,
            $row->active_contracts,
            $row->hotspot_total_users,
            $row->ppp_total_users,
            $row->hotspot_active_users,
            $row->ppp_active_users,
            $row->expire_contacts,
            $row->expire_contacts_over_month,
            $row->expire_contacts_less_month,
            $row->hotspot_expire_users,
            $row->ppp_expire_users,
            $row->sale_points,
            $row->total_cash
        ];
    }

    /**
     * Title
     *
     * @return response()
     */
    public function title(): string
    {
        return 'Metrics Report';
    }

    /**
     * Column
     *
     * @return response()
     */
    public function columnFormats(): array
    {
        return [
            'A' => '@', // Set the text wrapping for column A
            // Add more columns and formats as needed
        ];
    }

    /**
     * Styling
     *
     * @return response()
     */
    public function styles(Worksheet $sheet)
    {
        $sheet->setAutoFilter('A1:R1');

        $sheet->setCellValue('A1', 'Details');
        $sheet->setCellValue('B1', 'Active Communities');
        $sheet->setCellValue('C1', 'Inactive Communities (without subscriptions)');
        $sheet->setCellValue('D1', 'Total Hotspot Communities');
        $sheet->setCellValue('E1', 'Total Broadband Communities');
        $sheet->setCellValue('F1', 'Total Family Contracts');
        $sheet->setCellValue('G1', 'Active Contracts (With 150 shekel upfront or vending visit)');
        $sheet->setCellValue('H1', 'Total Hotspot Contracts');
        $sheet->setCellValue('I1', 'Total Broadband Contracts');
        $sheet->setCellValue('J1', 'Total Hotspot Active Contracts');
        $sheet->setCellValue('K1', 'Total Broadband Active Contracts');
        $sheet->setCellValue('L1', 'Expire Contracts');
        $sheet->setCellValue('M1', 'Inactive Contracts (Exceeding 30 days without vending visit)');
        $sheet->setCellValue('N1', 'Contracts (Ended within 30 days & not renewed)');
        $sheet->setCellValue('O1', 'Hotspot Expire Contracts');
        $sheet->setCellValue('P1', 'Broadband Expire Contracts');
        $sheet->setCellValue('Q1', 'Number of Vending Points');
        $sheet->setCellValue('R1', 'Total vending points debt');

        $sheet->getStyle('B1')->getAlignment()->setWrapText(true);
        $sheet->getStyle('C1')->getAlignment()->setWrapText(true);
        $sheet->getStyle('D1')->getAlignment()->setWrapText(true);
        $sheet->getStyle('E1')->getAlignment()->setWrapText(true);
        $sheet->getStyle('F1')->getAlignment()->setWrapText(true);
        $sheet->getStyle('G1')->getAlignment()->setWrapText(true);
        $sheet->getStyle('H1')->getAlignment()->setWrapText(true);
        $sheet->getStyle('I1')->getAlignment()->setWrapText(true);
        $sheet->getStyle('J1')->getAlignment()->setWrapText(true);
        $sheet->getStyle('K1')->getAlignment()->setWrapText(true);
        $sheet->getStyle('L1')->getAlignment()->setWrapText(true); 
        $sheet->getStyle('M1')->getAlignment()->setWrapText(true); 
        $sheet->getStyle('N1')->getAlignment()->setWrapText(true); 
        $sheet->getStyle('O1')->getAlignment()->setWrapText(true); 
        $sheet->getStyle('P1')->getAlignment()->setWrapText(true); 
        $sheet->getStyle('Q1')->getAlignment()->setWrapText(true); 
        $sheet->getStyle('P1')->getAlignment()->setWrapText(true); 
        $sheet->getStyle('Q1')->getAlignment()->setWrapText(true); 
        $sheet->getStyle('R1')->getAlignment()->setWrapText(true); 

        $sheet->getColumnDimension('B')->setAutoSize(false)->setWidth(30);
        $sheet->getColumnDimension('C')->setAutoSize(false)->setWidth(35);
        $sheet->getColumnDimension('D')->setAutoSize(false)->setWidth(30);
        $sheet->getColumnDimension('E')->setAutoSize(false)->setWidth(35);
        $sheet->getColumnDimension('F')->setAutoSize(false)->setWidth(30);
        $sheet->getColumnDimension('G')->setAutoSize(false)->setWidth(35);
        $sheet->getColumnDimension('H')->setAutoSize(false)->setWidth(35);
        $sheet->getColumnDimension('I')->setAutoSize(false)->setWidth(30);
        $sheet->getColumnDimension('J')->setAutoSize(false)->setWidth(30);
        $sheet->getColumnDimension('K')->setAutoSize(false)->setWidth(30);
        $sheet->getColumnDimension('L')->setAutoSize(false)->setWidth(30);
        $sheet->getColumnDimension('M')->setAutoSize(false)->setWidth(30);
        $sheet->getColumnDimension('N')->setAutoSize(false)->setWidth(30);
        $sheet->getColumnDimension('O')->setAutoSize(false)->setWidth(30);
        $sheet->getColumnDimension('P')->setAutoSize(false)->setWidth(30);
        $sheet->getColumnDimension('Q')->setAutoSize(false)->setWidth(30);
        $sheet->getColumnDimension('R')->setAutoSize(false)->setWidth(30);

        for ($i=0; $i < count($this->query); $i++) { 

            $sheet->setCellValue('A'.$i+2, "Count / Value (". $this->query[$i]->date_from. 
                " to ". $this->query[$i]->date_to. " )");
        }

        $range = 'A1:' . $sheet->getHighestColumn() . $sheet->getHighestRow();

        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true, 'size' => 12]],
            $range => [
                'alignment' => [
                    'horizontal' => 'center',
                    'vertical' => 'center',
                ],
            ],
        ];
    }
}