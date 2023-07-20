<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB;

class MgIncidentExport implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, 
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
        $query = DB::table('mg_incidents')
            ->join('communities', 'mg_incidents.community_id', '=', 'communities.id')
            ->join('regions', 'communities.region_id', '=', 'regions.id')
            ->join('sub_regions', 'communities.sub_region_id', '=', 'sub_regions.id')
            ->join('energy_systems', 'mg_incidents.energy_system_id', '=', 'energy_systems.id')
            ->join('incidents', 'mg_incidents.incident_id', '=', 'incidents.id')
            ->join('incident_status_mg_systems', 'mg_incidents.incident_status_mg_system_id', 
                '=', 'incident_status_mg_systems.id')
            ->leftJoin('community_donors', 'community_donors.community_id', '=', 'communities.id')
            ->leftJoin('donors', 'community_donors.donor_id', 'donors.id')
            ->where('energy_systems.energy_system_type_id', 1)
            ->where('community_donors.service_id', 1)
            ->where('mg_incidents.is_archived', 0)
            ->select([
                'energy_systems.name as energy_name', 
                'communities.english_name as community_name',
                'regions.english_name as region', 'sub_regions.english_name as sub_region',
                'incidents.english_name as incident',
                'mg_incidents.year', 'mg_incidents.date',
                'incident_status_mg_systems.name as mg_status', 
                DB::raw('group_concat(donors.donor_name) as donors')
            ])
            ->groupBy('mg_incidents.id');

        if($this->request->community) {

            $query->where("communities.english_name", $this->request->community);
        } 
        if($this->request->donor) {

            $query->where("community_donors.donor_id", $this->request->donor);
        }
        if($this->request->date) {

            $query->where("mg_incidents.date", ">=", $this->request->date);
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
        return ["MG System", "Community", "Region", "Sub Region", 
            "Incident", "Incident Year", "Incident Date", "Status", "Donor"];
    }

    public function title(): string
    {
        return 'MG Incidents';
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