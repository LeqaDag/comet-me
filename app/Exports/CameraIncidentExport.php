<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB;

class CameraIncidentExport implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, 
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
        $query = DB::table('camera_incidents')
            ->leftJoin('communities', 'camera_incidents.community_id', 'communities.id')
            ->leftJoin('repositories', 'camera_incidents.repository_id', 'repositories.id')
            ->leftJoin('camera_communities as camera_community', 'communities.id', 'camera_community.community_id')
            ->leftJoin('camera_communities as camera_repository', 'repositories.id', 'camera_repository.repository_id')
            ->join('incidents', 'camera_incidents.incident_id', 'incidents.id')
            ->join('internet_incident_statuses', 'camera_incidents.internet_incident_status_id', 
                'internet_incident_statuses.id')
            ->leftJoin('camera_incident_equipment', 'camera_incident_equipment.camera_incident_id', 
                'camera_incidents.id')
            ->leftJoin('incident_equipment', 'camera_incident_equipment.incident_equipment_id', 
                'incident_equipment.id') 
            ->where('camera_incidents.is_archived', 0)
            ->select([
                DB::raw('IFNULL(communities.english_name, repositories.name) as exported_value'),
                DB::raw('IFNULL(camera_community.date, camera_repository.date) as exported_date'),
                'incidents.english_name as incident', 'camera_incidents.year', 'camera_incidents.date', 
                'internet_incident_statuses.name as camera_status', 'camera_incidents.monetary_losses',  
                'camera_incidents.response_date',
                'camera_incidents.order_number', 'camera_incidents.order_date', 
                'camera_incidents.geolocation_lat', 'camera_incidents.geolocation_long', 
                'camera_incidents.hearing_date', 
                'camera_incidents.building_permit_request_number', 
                'camera_incidents.building_permit_request_submission_date', 
                'camera_incidents.illegal_construction_case_number', 
                'camera_incidents.district_court_case_number', 
                'camera_incidents.supreme_court_case_number', 
                'camera_incidents.case_chronology', 'camera_incidents.structure_description',
                DB::raw('group_concat(DISTINCT incident_equipment.name) as equipment'),
                'camera_incidents.notes'
            ])
            ->groupBy('camera_incidents.id')
            ->orderBy('camera_incidents.date', 'desc'); 


        if($this->request->date) {

            $query->where("camera_incidents.date", ">=", $this->request->date);
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
        return ["Holder", "Installation Date", "Incident", "Incident Year", "Incident Date", "Status", 
            "Monetary Losses", "Response Date", "Order Number", "Order Date", "Geolocation Lat", 
            "Geolocation Long", "Date of hearing", "Building permit request Number", "Building permit request date", 
            "Illegal Construction Case Number", "District Court Case Number", "Supreme Court Case Number", "Case Chronology",
            "Description of structure", "Equipment Damaged", "Notes"];
    }

    public function title(): string
    {
        return 'Camera Incidents';
    }

    /**
     * Styling
     *
     * @return response()
     */
    public function styles(Worksheet $sheet)
    {
        $sheet->setAutoFilter('A1:V1');

        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}