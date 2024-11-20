<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB; 

class MgIncidentSystem implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, 
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
            ->join('communities', 'mg_incidents.community_id', 'communities.id')
            ->join('regions', 'communities.region_id', 'regions.id')
            ->join('sub_regions', 'communities.sub_region_id', 'sub_regions.id')
            ->join('energy_systems', 'mg_incidents.energy_system_id', 'energy_systems.id')
            ->join('incidents', 'mg_incidents.incident_id', 'incidents.id')
            ->join('incident_status_mg_systems', 'mg_incidents.incident_status_mg_system_id', 
                'incident_status_mg_systems.id')
            ->leftJoin('mg_incident_equipment', 'mg_incident_equipment.mg_incident_id', 
                'mg_incidents.id')
            ->leftJoin('incident_equipment', 'mg_incident_equipment.incident_equipment_id', 
                'incident_equipment.id') 
            ->leftJoin('community_donors', 'community_donors.community_id', 'communities.id')
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
                DB::raw('group_concat(DISTINCT incident_equipment.name) as equipment'),
                'incident_status_mg_systems.name as mg_status',
                'mg_incidents.monetary_losses',  
                'mg_incidents.response_date',
                'mg_incidents.order_number', 'mg_incidents.order_date', 
                'mg_incidents.geolocation_lat', 'mg_incidents.geolocation_long', 
                'mg_incidents.hearing_date', 
                'mg_incidents.building_permit_request_number', 
                'mg_incidents.building_permit_request_submission_date', 
                'mg_incidents.illegal_construction_case_number', 
                'mg_incidents.district_court_case_number', 
                'mg_incidents.supreme_court_case_number', 
                'mg_incidents.case_chronology', 
                'mg_incidents.structure_description',
                DB::raw('group_concat(DISTINCT donors.donor_name) as donors'),
                'mg_incidents.notes'
            ])
            ->groupBy('mg_incidents.id')
            ->orderBy('mg_incidents.date', 'desc'); 

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
        return ["MG System", "Community", "Region", "Sub Region", "Incident", "Incident Year", 
            "Incident Date", "Equipment Damaged", "Status", "Monetary Losses", "Response date", 
            "Order Number", "Order Date", "Geolocation Lat", "Geolocation Long", "Date of hearing", 
            "Building permit request Number", "Building permit request date", "Illegal Construction Case Number", 
            "District Court Case Number", "Supreme Court Case Number", "Case Chronology",
            "Description of structure", "Donors", "Notes"];
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
        $sheet->setAutoFilter('A1:Y1');

        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}