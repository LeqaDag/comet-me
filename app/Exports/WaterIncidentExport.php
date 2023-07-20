<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB;

class WaterIncidentExport implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, 
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
        $query = DB::table('h2o_system_incidents')
            ->join('communities', 'h2o_system_incidents.community_id', '=', 'communities.id')
            ->join('regions', 'communities.region_id', '=', 'regions.id')
            ->join('sub_regions', 'communities.sub_region_id', '=', 'sub_regions.id')
            ->join('h2o_users', 'h2o_system_incidents.h2o_user_id', '=', 'h2o_users.id')
            ->join('households', 'h2o_users.household_id', '=', 'households.id')
            ->join('incidents', 'h2o_system_incidents.incident_id', '=', 'incidents.id')
            ->join('incident_statuses', 
                'h2o_system_incidents.incident_status_id', 
                '=', 'incident_statuses.id')
            ->join('all_water_holders', 'h2o_system_incidents.all_water_holder_id', 
                '=', 'all_water_holders.id')
            ->LeftJoin('all_water_holder_donors', 'all_water_holders.id', 
                '=', 'all_water_holder_donors.all_water_holder_id')
            ->leftJoin('donors', 'all_water_holder_donors.donor_id', 'donors.id')
            ->where('h2o_system_incidents.is_archived', 0) 
            ->select(['households.english_name as household_name',
                'communities.english_name as community_name', 
                'regions.english_name as region', 'sub_regions.english_name as sub_region',
                'incidents.english_name as incident', 'h2o_system_incidents.year',
                'h2o_system_incidents.date', 'incident_statuses.name as incident_status', 
                DB::raw('group_concat(donors.donor_name) as donors')
            ])
            ->groupBy('h2o_system_incidents.id');

        if($this->request->community) {

            $query->where("communities.english_name", $this->request->community);
        } 
        if($this->request->donor) {

            $query->where("community_donors.donor_id", $this->request->donor);
        }
        if($this->request->date) {

            $query->where("h2o_system_incidents.date", ">=", $this->request->date);
        }

        return $query->get();
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function headings(): array
    {
        return ["H2O User", "Community", "Region", "Sub Region", 
            "Incident", "Incident Year", "Incident Date", "Status", "Donor"];
    }

    public function title(): string
    {
        return 'Water Incidents';
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