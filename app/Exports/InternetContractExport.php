<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB;

class InternetContractExport implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, 
WithStyles
{

    protected $request;

    function __construct($request) {
 
        $this->request = $request;
    }
 
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $maxDateFrom = DB::table('internet_metrics')->max('date_from');
        $maxDateTo = DB::table('internet_metrics')->max('date_to');

        $data = DB::table('internet_users')
            ->join('communities', 'internet_users.community_id', 'communities.id')
            ->leftJoin('households', 'internet_users.household_id', 'households.id')
            ->leftJoin('public_structures', 'internet_users.public_structure_id', 
                'public_structures.id')
            ->join('internet_cluster_communities', 'internet_cluster_communities.community_id', 
                'communities.id')
            ->join('internet_clusters', 'internet_cluster_communities.internet_cluster_id', 
                'internet_clusters.id') 
            ->join('internet_metric_clusters', 'internet_metric_clusters.internet_cluster_id', 
                'internet_clusters.id')
            ->join('internet_metrics', 'internet_metric_clusters.internet_metric_id', 
                'internet_metrics.id')
            ->where('internet_users.is_archived', 0)
            ->whereRaw('internet_users.start_date BETWEEN ? AND ?', [$maxDateFrom, $maxDateTo])
            ->select(
                DB::raw('IFNULL(households.english_name, public_structures.english_name) as exported_value'),
                'communities.english_name as community_name',
                'internet_clusters.name as cluster_name',
                'internet_metrics.date_from', 'internet_metrics.date_to',
                'internet_users.start_date', 
            )
            ->groupBy('internet_users.id');

        if($this->request->community) {

            $data->where("communities.english_name", $this->request->community);
        } 
        if($this->request->donor) {

            $data->where("community_donors.donor_id", $this->request->donor);
        }
        if($this->request->start_date) {
            
            $data->where("internet_users.start_date", ">=", $this->request->start_date);
        } 

        return $data->get();
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function headings(): array
    {
        return ["Internet Holder", "Community", "Cluster Name", "Date From", "Date To",
            "Start Date (Installation)"];
    }

    public function title(): string
    {
        return 'New Holders Since Last Report';
    }

    /**
     * Styling
     * 
     * @return response()
     */
    public function styles(Worksheet $sheet)
    {
        $sheet->setAutoFilter('A1:F1');

        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}