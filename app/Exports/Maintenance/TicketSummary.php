<?php

namespace App\Exports\Maintenance;

use App\Models\EnergyUser;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB;

class TicketSummary implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, 
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
      $data = DB::table('all_maintenance_tickets as t')
        ->join('communities', 't.community_id', '=', 'communities.id')
        ->join('maintenance_statuses as ms', 't.maintenance_status_id', '=', 'ms.id')
        ->join('households', 't.comet_id', '=', 'households.comet_id')
        ->where('t.is_archived', 0)
        ->where('t.is_duplicated', 0)
        ->select([
            'communities.english_name as community',

            // Count of unique households with at least one ticket
            DB::raw("COUNT(DISTINCT t.comet_id) as total_households_with_tickets"),

            // Count of unique households with 'New' tickets
            DB::raw("COUNT(DISTINCT CASE WHEN ms.name = 'New' THEN t.comet_id END) as total_new"),

            // Count of unique households with 'In Progress' tickets
            DB::raw("COUNT(DISTINCT CASE WHEN ms.name = 'In Progress' THEN t.comet_id END) as total_in_progress"),

            // Count of unique households with 'Completed' tickets
            DB::raw("COUNT(DISTINCT CASE WHEN ms.name = 'Completed' THEN t.comet_id END) as total_completed"),

            // Completed by household (created_by is NULL or empty string)
            DB::raw("COUNT(DISTINCT CASE 
                WHEN (t.created_by IS NULL OR t.created_by = '') AND ms.name = 'Completed' 
                THEN t.comet_id END) as completed_by_household"),

            // Completed by user support
            DB::raw("COUNT(DISTINCT CASE 
                WHEN t.created_by = 'الدعم الفني' AND ms.name = 'Completed' 
                THEN t.comet_id END) as completed_by_user_support"),

            // Completed by team (other values)
            DB::raw("COUNT(DISTINCT CASE 
                WHEN t.created_by IS NOT NULL 
                    AND t.created_by != '' 
                    AND t.created_by != 'الدعم الفني' 
                    AND ms.name = 'Completed' 
                THEN t.comet_id END) as completed_by_team"),

            // Sum of completed by all sources
            DB::raw("(
                COUNT(DISTINCT CASE 
                    WHEN (t.created_by IS NULL OR t.created_by = '') AND ms.name = 'Completed' 
                    THEN t.comet_id END) +
                COUNT(DISTINCT CASE 
                    WHEN t.created_by = 'الدعم الفني' AND ms.name = 'Completed' 
                    THEN t.comet_id END) +
                COUNT(DISTINCT CASE 
                    WHEN t.created_by IS NOT NULL 
                        AND t.created_by != '' 
                        AND t.created_by != 'الدعم الفني' 
                        AND ms.name = 'Completed' 
                    THEN t.comet_id END)
            ) as total_completed_uploaded"),
        ])
        ->groupBy('communities.english_name')
        ->orderBy('communities.english_name');

        if($this->request->community_id) {

            $data->where("t.community_id", $this->request->community_id);
        }
        if($this->request->service_id) {

            $data->where("t.service_type_id", $this->request->service_id);
        }
        if($this->request->maintenance_status_id) {

            $data->where("maintenance_statuses.id", $this->request->maintenance_status_id);
        }
        if($this->request->maintenance_type_id) {

            $data->where("maintenance_types.id", $this->request->maintenance_type_id);
        }
        if($this->request->completed_date_from) {

            $data->where("t.completed_date", ">=", $this->request->completed_date_from);
        }
        if($this->request->completed_date_to) {

            $data->where("t.completed_date", "<=", $this->request->completed_date_to);
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
        return ["Community", "Total Tickets", "New Tickets", "In-Progress Tickets", "Completed Tickets",  
            "Uploaded by household", "Uploaded by user support", "Uploaded by team", "Total Uploaded-Completed"];
    }

    public function title(): string
    {
        return 'Tickets Summary';
    }

    /**
     * Styling
     *
     * @return response()
     */
    public function styles(Worksheet $sheet)
    {
        $sheet->setAutoFilter('A1:I1');

        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}