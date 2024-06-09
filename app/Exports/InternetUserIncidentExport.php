<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB;

class InternetUserIncidentExport implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, 
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
        $query = DB::table('internet_user_incidents')
            ->join('communities', 'internet_user_incidents.community_id', 'communities.id')
            ->join('regions', 'communities.region_id', 'regions.id')
            ->join('sub_regions', 'communities.sub_region_id', 'sub_regions.id')
            ->join('internet_users', 'internet_user_incidents.internet_user_id', 
                'internet_users.id')
            ->leftJoin('households', 'internet_users.household_id', 'households.id')
            ->leftJoin('public_structures', 'internet_users.public_structure_id', 'public_structures.id')
            ->join('incidents', 'internet_user_incidents.incident_id', 'incidents.id')
            ->join('internet_incident_statuses', 
                'internet_user_incidents.internet_incident_status_id', 
                'internet_incident_statuses.id')
            ->leftJoin('internet_user_incident_equipment', 'internet_user_incident_equipment.internet_user_incident_id', 
                'internet_user_incidents.id')
            ->leftJoin('incident_equipment', 'internet_user_incident_equipment.incident_equipment_id', 
                'incident_equipment.id') 
            ->leftJoin('internet_user_donors', 'internet_user_donors.internet_user_id',
                'internet_users.id')
            ->leftJoin('donors', 'internet_user_donors.donor_id', 'donors.id')
            ->where('internet_user_incidents.is_archived', 0)
            ->where('internet_user_incident_equipment.is_archived', 0)
            ->select([
                DB::raw('IFNULL(households.english_name, public_structures.english_name) 
                    as exported_value'),
                'communities.english_name as community_name', 
                'regions.english_name as region', 'sub_regions.english_name as sub_region',
                'households.number_of_male', 'households.number_of_female', 
                'households.number_of_children', 'households.number_of_adults', 
                'incidents.english_name as incident', 
                'internet_user_incidents.year', 'internet_user_incidents.date', 
                'internet_incident_statuses.name',
                'internet_user_incidents.response_date',
                'internet_user_incidents.order_number', 'internet_user_incidents.order_date', 
                'internet_user_incidents.geolocation_lat', 'internet_user_incidents.geolocation_long', 
                'internet_user_incidents.hearing_date', 'internet_user_incidents.structure_description',
                DB::raw('group_concat(DISTINCT donors.donor_name) as donors'),
                DB::raw('group_concat(DISTINCT incident_equipment.name) as equipment'),
                'internet_user_incidents.notes'
            ])
            ->groupBy('internet_user_incidents.id');

        if($this->request->community) {

            $query->where("communities.english_name", $this->request->community);
        } 
        if($this->request->donor) {

            $query->where("internet_user_donors.donor_id", $this->request->donor);
        }
        if($this->request->date) {

            $query->where("internet_user_incidents.date", ">=", $this->request->date);
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
        return ["Internet Holder", "Community", "Region", "Sub Region", 
            "# of Male", "# of Female", "# of Children", "# of Adults", "Incident", 
            "Incident Year", "Incident Date", "Status", "Response Date", "Order Number", 
            "Order Date", "Geolocation Lat", "Geolocation Long", "Date of hearing", 
            "Description of structure", "Donor", "Equipment Damaged", "Notes"];
    }

    public function title(): string
    {
        return 'Internet User Incidents';
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