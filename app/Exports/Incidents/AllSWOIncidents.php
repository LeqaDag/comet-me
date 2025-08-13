<?php

namespace App\Exports\Incidents;

use App\Models\EnergyUser;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use DB;

class AllSWOIncidents implements FromCollection, WithHeadings, WithTitle, 
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
        $data = DB::table('all_incidents')
            ->join('communities', 'all_incidents.community_id', 'communities.id')
            ->join('regions', 'communities.region_id', 'regions.id')
            ->join('service_types', 'all_incidents.service_type_id', 'service_types.id')
            ->join('incidents', 'all_incidents.incident_id', 'incidents.id')
            ->join('all_incident_occurred_statuses', 'all_incidents.id', 'all_incident_occurred_statuses.all_incident_id')
            ->join('all_incident_statuses', 'all_incident_statuses.id', 'all_incident_occurred_statuses.all_incident_status_id')
            ->where('all_incidents.is_archived', 0)
            ->where('all_incidents.incident_id', 4)

            ->leftJoin('all_energy_incidents', 'all_incidents.id', 'all_energy_incidents.all_incident_id')
            ->leftJoin('all_energy_meters', 'all_energy_meters.id', 'all_energy_incidents.all_energy_meter_id')
            ->leftJoin('households as energy_users', 'all_energy_meters.household_id', 'energy_users.id')
            ->leftJoin('public_structures as energy_publics', 'all_energy_meters.public_structure_id', 'energy_publics.id')
            ->leftJoin('energy_systems', 'energy_systems.id', 'all_energy_incidents.energy_system_id')
            ->leftJoin('all_energy_incident_damaged_equipment', 'all_energy_incidents.id', 
                'all_energy_incident_damaged_equipment.all_energy_incident_id')
            ->leftJoin('incident_equipment as energy_equipment', 'all_energy_incident_damaged_equipment.incident_equipment_id', 
                'energy_equipment.id')

            ->leftJoin('all_water_incidents', 'all_incidents.id', 'all_water_incidents.all_incident_id')
            ->leftJoin('all_water_holders', 'all_water_holders.id', 'all_water_incidents.all_water_holder_id')
            ->leftJoin('households as water_users', 'all_water_holders.household_id', 'water_users.id')
            ->leftJoin('public_structures as water_publics', 'all_water_holders.public_structure_id', 'water_publics.id')
            ->leftJoin('water_systems', 'water_systems.id', 'all_water_incidents.water_system_id')
            ->leftJoin('all_water_incident_damaged_equipment', 'all_water_incidents.id', 
                'all_water_incident_damaged_equipment.all_water_incident_id')
            ->leftJoin('incident_equipment as water_equipment', 'all_water_incident_damaged_equipment.incident_equipment_id', 
                'water_equipment.id')

            ->leftJoin('all_internet_incidents', 'all_incidents.id', 'all_internet_incidents.all_incident_id')
            ->leftJoin('internet_users', 'internet_users.id', 'all_internet_incidents.internet_user_id')
            ->leftJoin('households as internet_holders', 'internet_holders.id', 'internet_users.household_id')
            ->leftJoin('public_structures as internet_publics', 'internet_users.public_structure_id', 'internet_publics.id')
            ->leftJoin('internet_system_communities', 'internet_system_communities.community_id', 'all_internet_incidents.community_id')
            ->leftJoin('internet_systems', 'internet_systems.id', 'internet_system_communities.internet_system_id')
            ->leftJoin('all_internet_incident_damaged_equipment', 'all_internet_incidents.id', 
                'all_internet_incident_damaged_equipment.all_internet_incident_id')
            ->leftJoin('incident_equipment as internet_equipment', 'all_internet_incident_damaged_equipment.incident_equipment_id', 
                'internet_equipment.id')

            ->leftJoin('all_camera_incidents', 'all_incidents.id', 'all_camera_incidents.all_incident_id')
            ->leftJoin('communities as cameras_communities', 'cameras_communities.id', 'all_camera_incidents.community_id')
            ->leftJoin('all_camera_incident_damaged_equipment', 'all_camera_incidents.id', 
                'all_camera_incident_damaged_equipment.all_camera_incident_id')
            ->leftJoin('incident_equipment as camera_equipment', 'all_camera_incident_damaged_equipment.incident_equipment_id', 
                'camera_equipment.id')

            // Energy, Internet system donors
            ->leftJoin('community_donors', 'community_donors.community_id', 'communities.id')
            ->leftJoin('donors as energy_system_donors', 'community_donors.donor_id', 'energy_system_donors.id')
            ->leftJoin('donors as internet_system_donors', 'community_donors.donor_id', 'internet_system_donors.id')

            // Energy holder donors
            ->leftJoin('all_energy_meter_donors', 'all_energy_meter_donors.all_energy_meter_id','all_energy_meters.id')
            ->leftJoin('donors as energy_holder_donors', 'all_energy_meter_donors.donor_id', 'energy_holder_donors.id')

            // Water holder donors
            ->LeftJoin('all_water_holder_donors', 'all_water_holders.id', 'all_water_holder_donors.all_water_holder_id')
            ->leftJoin('donors as water_holder_donors', 'all_water_holder_donors.donor_id', 'water_holder_donors.id')

            // Internet holder donors
            ->leftJoin('internet_user_donors', 'internet_user_donors.internet_user_id','internet_users.id')
            ->leftJoin('donors as internet_holder_donors', 'internet_user_donors.donor_id', 'internet_holder_donors.id')

            // Camera donors
            ->leftJoin('camera_communities', 'all_incidents.community_id','camera_communities.community_id')
            ->leftJoin('camera_community_donors', 'camera_community_donors.camera_community_id','camera_communities.id')
            ->leftJoin('donors as camera_donors', 'camera_community_donors.donor_id', 'camera_donors.id')


            ->select([

                //DB::raw('ROW_NUMBER() OVER (ORDER BY (SELECT 1)) AS row_number'),
                'all_incidents.date',

                DB::raw("COALESCE(energy_users.english_name, energy_publics.english_name, 
                    water_users.english_name, water_publics.english_name, internet_holders.english_name, 
                    internet_publics.english_name) as user"),

                DB::raw("COALESCE(energy_systems.name, water_systems.name, internet_systems.system_name, 
                    cameras_communities.english_name) as system"),

                'communities.english_name as community_name',
                'regions.english_name as region',

                DB::raw("COALESCE(energy_users.number_of_children, water_users.number_of_children, 
                    internet_holders.number_of_children, 0) as number_of_children"),

                'service_types.service_name as department',
                DB::raw("COALESCE(all_incident_statuses.status) as status"),

                DB::raw("CASE WHEN all_energy_incident_damaged_equipment.count IS NULL 
                    THEN energy_equipment.name 
                    ELSE CONCAT(energy_equipment.name, '(', all_energy_incident_damaged_equipment.count,')') 
                    END as energy_equipment"),

                'all_energy_incident_damaged_equipment.cost as total_energy_cost',

                DB::raw("CASE WHEN all_water_incident_damaged_equipment.count IS NULL 
                    THEN water_equipment.name 
                    ELSE CONCAT(water_equipment.name, ' (', all_water_incident_damaged_equipment.count, ')') 
                    END as water_equipment"),

                'all_water_incident_damaged_equipment.cost as total_water_cost',

                DB::raw("CASE WHEN all_internet_incident_damaged_equipment.count IS NULL 
                    THEN internet_equipment.name 
                    ELSE CONCAT(internet_equipment.name, ' (', all_internet_incident_damaged_equipment.count, ')') 
                    END as internet_equipment"),

                'all_internet_incident_damaged_equipment.cost as total_internet_cost',

                DB::raw("CASE WHEN all_camera_incident_damaged_equipment.count IS NULL 
                    THEN camera_equipment.name 
                    ELSE CONCAT(camera_equipment.name, ' (', all_camera_incident_damaged_equipment.count, ')') 
                    END as camera_equipment"),

                'all_camera_incident_damaged_equipment.cost as total_camera_cost',

                'all_incidents.notes',

                'all_incidents.order_number', 'all_incidents.order_date', 
                'all_incidents.geolocation_lat', 'all_incidents.geolocation_long', 
                'all_incidents.hearing_date', 
                'all_incidents.building_permit_request_number', 
                'all_incidents.building_permit_request_submission_date', 
                'all_incidents.illegal_construction_case_number', 
                'all_incidents.district_court_case_number', 
                'all_incidents.supreme_court_case_number', 
                'all_incidents.case_chronology', 
                'all_incidents.structure_description',

                DB::raw("COALESCE(
                    CASE WHEN community_donors.service_id = 1 THEN energy_system_donors.donor_name END,
                    energy_holder_donors.donor_name,
                    water_holder_donors.donor_name,
                    CASE WHEN community_donors.service_id = 3 THEN internet_system_donors.donor_name END,
                    internet_holder_donors.donor_name,
                    camera_donors.donor_name
                ) as donor_name"),
            ])->groupBy('all_incidents.id')
            ->orderBy('all_incidents.date', 'desc');

        if($this->request->service_ids) {

            $data->whereIn('all_incidents.service_type_id', $this->request->service_ids);
        }
        if($this->request->community_id) {

            $data->where("all_incidents.community_id", $this->request->community_id);
        }
        if($this->request->incident_id) {

            $data->where("all_incidents.incident_id", $this->request->incident_id);
        }
        if($this->request->date) {

            $data->where("all_incidents.date", ">=", $this->request->date);
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
        return [
            "Incident Date", "User/Public (any energy user - MG or FBS); Water user, Internet user",
            "System", "Community", "Region", "# of Children", "Service Types", "Incident Status", "Energy Equipment Damaged", 
            "Losses Energy (ILS)", "Water Equipment Damaged", "Losses Water (ILS)", "Internet Equipment Damaged", 
            "Losses Internet (ILS)", "Camera Equipment Damaged", "Losses Cameras (ILS)", "Description of Incident", 
            "Order Number", "Order Date", "Geolocation Lat", "Geolocation Long", "Date of hearing", "Building permit request Number", 
            "Building permit request date", "Illegal Construction Case Number", "District Court Case Number", "Supreme Court Case Number",
            "Case Chronology", "Description of structure", "Donors"];
    }

    public function title(): string
    {
        return 'Total SWO Incidents';
    }

    /**
     * Styling
     *
     * @return response()
     */
    public function styles(Worksheet $sheet)
    {
        $sheet->setAutoFilter('A1:AI1');

        $highestRow = $sheet->getHighestRow();           
        $highestColumn = $sheet->getHighestColumn();        
        $fullRange = "A1:{$highestColumn}{$highestRow}";

        // Wrap text and vertical top alignment for all cells
        $sheet->getStyle($fullRange)->getAlignment()
            ->setWrapText(true)
            ->setVertical(Alignment::VERTICAL_TOP);

        // Convert highest column letter to index
        $highestColumnIndex = Coordinate::columnIndexFromString($highestColumn);

        // Set fixed column width for all columns properly
        for ($col = 1; $col <= $highestColumnIndex; $col++) {
            $columnLetter = Coordinate::stringFromColumnIndex($col);
            $sheet->getColumnDimension($columnLetter)->setWidth(40);
        }

        // Auto row height for all rows
        for ($row = 1; $row <= $highestRow; $row++) {
            $sheet->getRowDimension($row)->setRowHeight(-1);
        }

        // Header font style
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
 
}