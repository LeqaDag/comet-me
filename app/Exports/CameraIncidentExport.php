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
                'internet_incident_statuses.name as camera_status', 'camera_incidents.response_date',
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
        return ["Holder", "Installation Date", "Incident", "Incident Year", "Incident Date", "Status", "Response Date", 
            "Equipment Damaged", "Notes"];
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
        $sheet->setAutoFilter('A1:I1');

        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}