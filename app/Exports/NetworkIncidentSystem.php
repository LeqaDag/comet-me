<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB;

class NetworkIncidentSystem implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, 
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
        $query = DB::table('internet_network_incidents')
            ->join('communities', 'internet_network_incidents.community_id', 'communities.id')
            ->join('regions', 'communities.region_id', '=', 'regions.id')
            ->join('sub_regions', 'communities.sub_region_id', '=', 'sub_regions.id')
            ->leftJoin('incidents', 'internet_network_incidents.incident_id', 'incidents.id')
            ->leftJoin('internet_incident_statuses', 
                'internet_network_incidents.internet_incident_status_id', 
                'internet_incident_statuses.id')
            ->leftJoin('internet_network_incident_equipment', 'internet_network_incidents.id',
                'internet_network_incident_equipment.internet_network_incident_id')
            ->leftJoin('incident_equipment', 'incident_equipment.id',
                'internet_network_incident_equipment.incident_equipment_id') 
            ->leftJoin('community_donors', 'community_donors.community_id', 'communities.id')
            ->leftJoin('donors', 'community_donors.donor_id', 'donors.id')
            ->leftJoin('internet_network_affected_households', 'internet_network_incidents.id',
                'internet_network_affected_households.internet_network_incident_id') 
            ->leftJoin('households', 'households.id',
                'internet_network_affected_households.household_id') 
            ->where('internet_network_incidents.is_archived', 0)
            ->where('community_donors.service_id', 3)
            ->select([
                'communities.english_name as community_name', 
                'regions.english_name as region', 'sub_regions.english_name as sub_region',
                'incidents.english_name as incident', 
                'internet_network_incidents.year', 'internet_network_incidents.date', 
                'internet_incident_statuses.name as incident_status',
                'internet_network_incidents.monetary_losses',  
                'internet_network_incidents.response_date',
                'internet_network_incidents.order_number', 'internet_network_incidents.order_date', 
                'internet_network_incidents.geolocation_lat', 'internet_network_incidents.geolocation_long', 
                'internet_network_incidents.hearing_date', 
                'internet_network_incidents.building_permit_request_number', 
                'internet_network_incidents.building_permit_request_submission_date', 
                'internet_network_incidents.illegal_construction_case_number', 
                'internet_network_incidents.district_court_case_number', 
                'internet_network_incidents.supreme_court_case_number', 
                'internet_network_incidents.case_chronology', 'internet_network_incidents.structure_description',
                DB::raw('group_concat(DISTINCT households.english_name) as households'),
                DB::raw('group_concat(DISTINCT incident_equipment.name) as equipment'),
                DB::raw('group_concat(DISTINCT donors.donor_name) as donors'),
                'internet_network_incidents.notes',
                'internet_network_incidents.next_step'
            ])
            ->groupBy('internet_network_incidents.id')
            ->orderBy('internet_network_incidents.date', 'desc'); 

        if($this->request->community) {

            $query->where("communities.english_name", $this->request->community);
        } 
        if($this->request->donor) {

            $query->where("community_donors.donor_id", $this->request->donor);
        }
        if($this->request->date) {

            $query->where("internet_network_incidents.date", ">=", $this->request->date);
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
        return ["Community", "Region", "Sub Region", "Incident", "Incident Year", 
            "Incident Date", "Status", "Monetary Losses", "Response Date", "Order Number", "Order Date", 
            "Geolocation Lat", "Geolocation Long", "Date of hearing",  
            "Building permit request Number", "Building permit request date", "Illegal Construction Case Number", 
            "District Court Case Number", "Supreme Court Case Number", "Case Chronology",
            "Description of structure", "Households", "Equipment Damaged", 
            "Donors", "Notes", "Next Step"];
    }

    public function title(): string
    {
        return 'Network Incidents';
    }

    /**
     * Styling
     *
     * @return response()
     */
    public function styles(Worksheet $sheet)
    {
        $sheet->setAutoFilter('A1:Z1');
        $sheet->getStyle('K1')->getAlignment()->setWrapText(true);

        $sheet->getColumnDimension('K')->setAutoSize(false)->setWidth(40);

        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}