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
                'internet_metric_clusters.bandwidth_consumption');

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
            $row->bandwidth_consumption
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
        $sheet->setAutoFilter('A1:F1');

        $sheet->setCellValue('A1', 'Count/Value');
        $sheet->setCellValue('B1', 'Cluster Name');
        $sheet->setCellValue('C1', 'ISP');
        $sheet->setCellValue('D1', 'Attached Communities');
        $sheet->setCellValue('E1', 'Active Contracts');
        $sheet->setCellValue('F1', 'Total Bandwidth Mbps');

        $sheet->getStyle('B1')->getAlignment()->setWrapText(true);
        $sheet->getStyle('C1')->getAlignment()->setWrapText(true);
        $sheet->getStyle('D1')->getAlignment()->setWrapText(true);
        $sheet->getStyle('E1')->getAlignment()->setWrapText(true);

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