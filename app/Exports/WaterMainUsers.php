<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB;

class WaterMainUsers implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, 
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
            ->join('communities', 'h2o_system_incidents.community_id', 'communities.id')
            ->join('regions', 'communities.region_id', 'regions.id')
            ->join('sub_regions', 'communities.sub_region_id', 'sub_regions.id')
            ->leftJoin('water_systems', 'h2o_system_incidents.water_system_id', 'water_systems.id')
            ->leftJoin('all_water_holders', 'h2o_system_incidents.all_water_holder_id', 
                'all_water_holders.id')
            ->leftJoin('households', 'all_water_holders.household_id', 'households.id')
            ->leftJoin('public_structures', 'all_water_holders.public_structure_id', 
                'public_structures.id')
            ->join('incidents', 'h2o_system_incidents.incident_id', 'incidents.id')
            ->leftJoin('h2o_incident_statuses', 'h2o_system_incidents.id', 
                'h2o_incident_statuses.h2o_system_incident_id')
            ->leftJoin('incident_statuses', 
                'h2o_incident_statuses.incident_status_id', 
                'incident_statuses.id')
            ->leftJoin('water_incident_equipment', 'water_incident_equipment.h2o_system_incident_id', 
                'h2o_system_incidents.id')
            ->leftJoin('incident_equipment', 'water_incident_equipment.incident_equipment_id', 
                'incident_equipment.id')
            ->LeftJoin('all_water_holder_donors', 'all_water_holders.id', 
                'all_water_holder_donors.all_water_holder_id')
            ->leftJoin('donors', 'all_water_holder_donors.donor_id', 'donors.id')
            ->where('h2o_system_incidents.is_archived', 0) 
            ->where('h2o_incident_statuses.is_archived', 0) 
            //->where('water_incident_equipment.is_archived', 0) 
            ->select([
                DB::raw('COALESCE(households.english_name, public_structures.english_name, 
                    water_systems.name) as exported_value'),
                'all_water_holders.is_main',
                'communities.english_name as community_name', 
                'regions.english_name as region', 'sub_regions.english_name as sub_region',
                'households.number_of_male', 'households.number_of_female', 
                'households.number_of_children', 'households.number_of_adults',
                'incidents.english_name as incident', 'h2o_system_incidents.year',
                'h2o_system_incidents.date', 
                DB::raw('group_concat(DISTINCT incident_statuses.name) as incident_status'),
                'h2o_system_incidents.monetary_losses',  
                'h2o_system_incidents.response_date',
                'h2o_system_incidents.order_number', 'h2o_system_incidents.order_date', 
                'h2o_system_incidents.geolocation_lat', 'h2o_system_incidents.geolocation_long', 
                'h2o_system_incidents.hearing_date', 
                'h2o_system_incidents.building_permit_request_number', 
                'h2o_system_incidents.building_permit_request_submission_date', 
                'h2o_system_incidents.illegal_construction_case_number', 
                'h2o_system_incidents.district_court_case_number', 
                'h2o_system_incidents.supreme_court_case_number', 
                'h2o_system_incidents.case_chronology', 'h2o_system_incidents.structure_description',
                DB::raw('group_concat(DISTINCT donors.donor_name) as donors'),
                DB::raw('group_concat(DISTINCT incident_equipment.name) as equipment'),
                'h2o_system_incidents.notes'
            ])
            ->groupBy('h2o_system_incidents.id')
            ->orderBy('h2o_system_incidents.date', 'desc'); 

        if($this->request->community) {

            $query->where("communities.english_name", $this->request->community);
        } 
        if($this->request->donor) {

            $query->where("all_water_holder_donors.donor_id", $this->request->donor);
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
        return ["Water Holder", "Main User", "Community", "Region", "Sub Region", 
            "# of Male", "# of Female", "# of Children", "# of Adults", 
            "Incident", "Incident Year", "Incident Date", "Status", 
            "Monetary Losses", "Response Date",  "Order Number", "Order Date", 
            "Geolocation Lat", "Geolocation Long", "Date of hearing", 
            "Building permit request Number", "Building permit request date", "Illegal Construction Case Number", 
            "District Court Case Number", "Supreme Court Case Number", "Case Chronology",
            "Description of structure", "Donors", "Equipment Damaged", "Notes"];
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
        $sheet->setAutoFilter('A1:X1');
        $sheet->getStyle('w1')->getAlignment()->setWrapText(true);

        $sheet->getColumnDimension('w')->setAutoSize(false)->setWidth(40);

        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}