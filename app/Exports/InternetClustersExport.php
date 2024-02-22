<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell; 
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Facades\Http;
use App\Models\Community;
use App\Models\InternetUser;
use App\Models\InternetCluster;
use App\Models\InternetMetric;
use App\Models\InternetMetricCluster;
use App\Models\Household;
use Carbon\Carbon; 
use DB;

class InternetClustersExport implements FromCollection, WithTitle, 
    WithStyles, WithCustomStartCell, WithMapping, ShouldAutoSize
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
        $data = DB::table('internet_metric_clusters')
            ->join('internet_metrics', 'internet_metric_clusters.internet_metric_id', 
                'internet_metrics.id')
            ->join('internet_clusters', 'internet_metric_clusters.internet_cluster_id', 
                'internet_clusters.id') 
            ->select('internet_metrics.date_from', 'internet_metrics.date_to', 
                'internet_clusters.name', 
                'internet_metric_clusters.source_of_connection', 
                'internet_metric_clusters.attached_communities', 
                'internet_metric_clusters.active_contracts', 
                'internet_metric_clusters.bandwidth_consumption',
                'internet_metric_clusters.weekly_max_in', 
                'internet_metric_clusters.weekly_max_out',
                'internet_metric_clusters.weekly_avg_in',
                'internet_metric_clusters.weekly_avg_out', 
                'internet_metric_clusters.weekly_now_in', 
                'internet_metric_clusters.weekly_now_out', 
                'internet_metric_clusters.monthly_max_in', 
                'internet_metric_clusters.monthly_max_out', 
                'internet_metric_clusters.monthly_avg_in', 
                'internet_metric_clusters.monthly_avg_out', 
                'internet_metric_clusters.monthly_now_in', 
                'internet_metric_clusters.monthly_now_out');

        $metricsData = DB::table('internet_metrics');
        $this->query = $metricsData->get();

        return $data->get();
    }

    /**
     * Start Cell
     *
     * @return response()
     */
    public function startCell(): string
    {
        return 'A2';
    }

    /**
     * Values
     *
     * @return response()
     */
    public function map($row): array
    {
        return [
            $row->date_from ." to ". $row->date_to,
            $row->name,
            $row->source_of_connection,
            $row->attached_communities,
            $row->active_contracts,
            $row->bandwidth_consumption,
            $row->weekly_max_in,
            $row->weekly_max_out,
            $row->weekly_avg_in,
            $row->weekly_avg_out,
            $row->weekly_now_in,
            $row->weekly_now_out,
            $row->monthly_max_in,
            $row->monthly_max_out,
            $row->monthly_avg_in,
            $row->monthly_avg_out,
            $row->monthly_now_in,
            $row->monthly_now_out
        ];
    }

    /**
     * Title
     *
     * @return response()
     */
    public function title(): string
    {
        return 'Clusters Summary';
    } 

    /**
     * Styling
     *
     * @return response()
     */
    public function styles(Worksheet $sheet)
    {
        $sheet->setAutoFilter('A1:R1');

        $sheet->setCellValue('A1', 'Count/Value');
        $sheet->setCellValue('B1', 'Cluster Name');
        $sheet->setCellValue('C1', 'ISP');
        $sheet->setCellValue('D1', 'Attached Communities');
        $sheet->setCellValue('E1', 'Active Contracts');
        $sheet->setCellValue('F1', 'Total Bandwidth Mbps');
        $sheet->setCellValue('G1', 'Weekly Max In Bandwidth Mbps');
        $sheet->setCellValue('H1', 'Weekly Max Out Bandwidth Mbps');
        $sheet->setCellValue('I1', 'Weekly Avg In Bandwidth Mbps');
        $sheet->setCellValue('J1', 'Weekly Avg Out Bandwidth Mbps');
        $sheet->setCellValue('K1', 'Weekly Now In Bandwidth Mbps');
        $sheet->setCellValue('L1', 'Weekly Now Out Bandwidth Mbps');
        $sheet->setCellValue('M1', 'Monthly Max In Bandwidth Mbps');
        $sheet->setCellValue('N1', 'Monthly Max Out Bandwidth Mbps');
        $sheet->setCellValue('O1', 'Monthly Avg In Bandwidth Mbps');
        $sheet->setCellValue('P1', 'Monthly Avg Out Bandwidth Mbps');
        $sheet->setCellValue('Q1', 'Monthly Now In Bandwidth Mbps');
        $sheet->setCellValue('R1', 'Monthly Now Out Bandwidth Mbps');
 
        $sheet->getStyle('B1')->getAlignment()->setWrapText(true);
        $sheet->getStyle('C1')->getAlignment()->setWrapText(true);
        $sheet->getStyle('D1')->getAlignment()->setWrapText(true);
        $sheet->getStyle('E1')->getAlignment()->setWrapText(true);
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
        $sheet->getStyle('R1')->getAlignment()->setWrapText(true);

        $sheet->getColumnDimension('A')->setAutoSize(false)->setWidth(40);
 
        // for ($i=0; $i < count($this->query); $i++) { 

        //     $sheet->setCellValue('A'.$i+2, "Count / Value (". $this->query[$i]->date_from. 
        //         " to ". $this->query[$i]->date_to. " )");
        // }

        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true, 'size' => 12]]
        ];
    }
}