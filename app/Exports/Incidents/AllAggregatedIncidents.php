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
use Illuminate\Support\Str;
use DB;

class AllAggregatedIncidents implements FromCollection, WithHeadings, WithTitle, 
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
        
        $equipmentSubqueryEnergy = IncidentEquipmentQueryBuilder::getEnergyEquipmentSubquery();
        $equipmentSubqueryWater = IncidentEquipmentQueryBuilder::getWaterEquipmentSubquery();
        $equipmentSubqueryInternet = IncidentEquipmentQueryBuilder::getInternetEquipmentSubquery();
        $equipmentSubqueryInternetNetwork = IncidentEquipmentQueryBuilder::getNetworkEquipmentSubquery();
        
        $data = DB::table('all_incidents')
            ->join('communities', 'all_incidents.community_id', 'communities.id')
            ->join('regions', 'communities.region_id', 'regions.id')
            ->join('service_types', 'all_incidents.service_type_id', 'service_types.id')
            ->join('incidents', 'all_incidents.incident_id', 'incidents.id')
            ->join('all_incident_occurred_statuses', 'all_incidents.id', 'all_incident_occurred_statuses.all_incident_id')
            ->join('all_incident_statuses', 'all_incident_statuses.id', 'all_incident_occurred_statuses.all_incident_status_id')
            ->where('all_incidents.is_archived', 0)
            //->where('all_incidents.incident_id', '!=', 4)

            ->leftJoin('all_energy_incidents', 'all_incidents.id', 'all_energy_incidents.all_incident_id')
            ->leftJoin('all_energy_meters', 'all_energy_meters.id', 'all_energy_incidents.all_energy_meter_id')
            ->leftJoin('households as energy_users', 'all_energy_meters.household_id', 'energy_users.id')
            ->leftJoin('public_structures as energy_publics', 'all_energy_meters.public_structure_id', 'energy_publics.id')
            ->leftJoin('energy_systems', 'energy_systems.id', 'all_energy_incidents.energy_system_id')
            ->leftJoin('all_energy_incident_damaged_equipment as energy_holder_equipment', 'all_energy_incidents.id', 
                'energy_holder_equipment.all_energy_incident_id')
            ->leftJoin('incident_equipment as energy_equipment', 'energy_equipment.id', 'energy_holder_equipment.incident_equipment_id')
            
            ->leftJoinSub($equipmentSubqueryEnergy, 'energy_equipments', function ($join) {
                $join->on('all_energy_incidents.id', '=', 'energy_equipments.all_energy_incident_id');
            })

            ->leftJoin('all_water_incidents', 'all_incidents.id', 'all_water_incidents.all_incident_id')
            ->leftJoin('all_water_holders', 'all_water_holders.id', 'all_water_incidents.all_water_holder_id')
            ->leftJoin('households as water_users', 'all_water_holders.household_id', 'water_users.id')
            ->leftJoin('public_structures as water_publics', 'all_water_holders.public_structure_id', 'water_publics.id')
            ->leftJoin('water_systems', 'water_systems.id', 'all_water_incidents.water_system_id')
            ->leftJoin('all_water_incident_damaged_equipment', 'all_water_incidents.id', 
                'all_water_incident_damaged_equipment.all_water_incident_id')
            ->leftJoin('incident_equipment as water_equipment', 'all_water_incident_damaged_equipment.incident_equipment_id', 
                'water_equipment.id')

            ->leftJoinSub($equipmentSubqueryWater, 'water_equipments', function ($join) {
                $join->on('all_water_incidents.id', '=', 'water_equipments.all_water_incident_id');
            })


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
            ->leftJoinSub($equipmentSubqueryInternet, 'internet_equipments', function ($join) {
                $join->on('all_internet_incidents.id', '=', 'internet_equipments.all_internet_incident_id');
            })

            ->leftJoinSub($equipmentSubqueryInternetNetwork, 'internet_network_equipments', function ($join) {
                $join->on('all_internet_incidents.id', '=', 'internet_network_equipments.all_internet_incident_id');
            })

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
                DB::raw("CASE 
                    WHEN energy_users.english_name IS NOT NULL THEN 'Energy User'
                    WHEN energy_publics.english_name IS NOT NULL THEN 'Energy Public Structure'
                    WHEN water_users.english_name IS NOT NULL THEN 'Water User'
                    WHEN water_publics.english_name IS NOT NULL THEN 'Water Public Structure'
                    WHEN internet_holders.english_name IS NOT NULL THEN 'Internet User'
                    WHEN internet_publics.english_name IS NOT NULL THEN 'Internet Public Structure'
                    WHEN energy_systems.name IS NOT NULL THEN 'Energy System'
                    WHEN water_systems.name IS NOT NULL THEN 'Water System'
                    WHEN internet_systems.system_name IS NOT NULL THEN 'Internet System'
                    WHEN cameras_communities.english_name IS NOT NULL THEN 'Camera Community'
                    ELSE 'Unknown'
                END as agent_type"),

                DB::raw("COALESCE(energy_users.english_name, energy_publics.english_name, 
                    water_users.english_name, water_publics.english_name, 
                    internet_holders.english_name, internet_publics.english_name,
                    energy_systems.name, water_systems.name, internet_systems.system_name, 
                    cameras_communities.english_name ) as agent"),

                'all_incidents.date',

                "communities.english_name as community_name",
                'regions.english_name as region', 
                'incidents.english_name as incident',
                "service_types.service_name as department",

                DB::raw("GROUP_CONCAT(DISTINCT CASE WHEN all_incidents.service_type_id = 1 THEN 
                    all_incident_statuses.status END SEPARATOR ', ') as energy_status"),

                DB::raw("GROUP_CONCAT(DISTINCT CASE WHEN energy_holder_equipment.count IS NULL 
                    THEN energy_equipment.name ELSE CONCAT(energy_equipment.name, 
                    ' (', energy_holder_equipment.count, ')') END SEPARATOR ', ') as energy_equipment"),
                DB::raw('SUM(energy_holder_equipment.cost) as total_energy_cost'),
                'energy_equipments.equipment_models as energy_system_equipment',
                'energy_equipments.total_energy_system_cost as energy_system_cost',


                DB::raw("GROUP_CONCAT(DISTINCT CASE WHEN all_incidents.service_type_id = 2 THEN 
                    all_incident_statuses.status END SEPARATOR ', ') as water_status"),

                DB::raw("GROUP_CONCAT(DISTINCT CASE WHEN all_water_incident_damaged_equipment.count IS NULL 
                    THEN water_equipment.name ELSE CONCAT(water_equipment.name, 
                    ' (', all_water_incident_damaged_equipment.count, ')') END SEPARATOR ', ') as water_equipment"),
                DB::raw('SUM(all_water_incident_damaged_equipment.cost) as total_water_cost'),
                'water_equipments.equipment_models as water_system_equipment',
                'water_equipments.total_water_system_cost as water_system_cost',

                DB::raw("GROUP_CONCAT(DISTINCT CASE WHEN all_incidents.service_type_id = 3 THEN 
                    all_incident_statuses.status END SEPARATOR ', ') as internet_status"),

                DB::raw("GROUP_CONCAT(DISTINCT CASE WHEN all_internet_incident_damaged_equipment.count IS NULL 
                    THEN internet_equipment.name ELSE CONCAT(internet_equipment.name, 
                    ' (', all_internet_incident_damaged_equipment.count, ')') END SEPARATOR ', ') as internet_equipment"),
                DB::raw('SUM(all_internet_incident_damaged_equipment.cost) as total_internet_cost'),
                'internet_equipments.equipment_models as internet_system_equipment',
                'internet_equipments.total_internet_system_cost as internet_system_cost',

                'internet_network_equipments.component_ids as component_ids',
                'internet_network_equipments.component_types as component_types',
                'internet_network_equipments.cabinet_models as cabinet_models',
                'internet_network_equipments.total_internet_system_cost as network_cost',


                DB::raw("GROUP_CONCAT(DISTINCT CASE WHEN all_incidents.service_type_id = 4 THEN all_incident_statuses.status END SEPARATOR ', ') as camera_status"),
                DB::raw("GROUP_CONCAT(DISTINCT CASE WHEN all_camera_incident_damaged_equipment.count IS NULL THEN camera_equipment.name ELSE CONCAT(camera_equipment.name, ' (', all_camera_incident_damaged_equipment.count, ')') END SEPARATOR ', ') as camera_equipment"),
                DB::raw('SUM(all_camera_incident_damaged_equipment.cost) as total_camera_cost'),

                'all_incidents.notes',
                'all_incidents.description',
                'all_incidents.manager_description',

                DB::raw("GROUP_CONCAT(DISTINCT COALESCE(
                    CASE WHEN community_donors.service_id = 1 THEN energy_system_donors.donor_name ELSE energy_holder_donors.donor_name END
                ) SEPARATOR ', ') as energy_donor_name"),

                DB::raw("GROUP_CONCAT(DISTINCT COALESCE(water_holder_donors.donor_name, '') SEPARATOR ', ') as water_donor_name"),

                DB::raw("GROUP_CONCAT(DISTINCT COALESCE(
                    CASE WHEN community_donors.service_id = 3 THEN internet_system_donors.donor_name ELSE internet_holder_donors.donor_name END
                ) SEPARATOR ', ') as internet_donor_name"),

                DB::raw("GROUP_CONCAT(DISTINCT COALESCE(camera_donors.donor_name, '') SEPARATOR ', ') as camera_donor_name"),


                DB::raw("
                    COALESCE(SUM(DISTINCT CASE WHEN energy_users.id IS NOT NULL THEN 
                        energy_users.number_of_adults ELSE 0 END), 0) +
                    COALESCE(SUM(DISTINCT CASE WHEN water_users.id IS NOT NULL THEN 
                        water_users.number_of_adults ELSE 0 END), 0) +
                    COALESCE(SUM(DISTINCT CASE WHEN internet_holders.id IS NOT NULL 
                        THEN internet_holders.number_of_adults ELSE 0 END), 0)
                    AS number_of_adults
                "),
                DB::raw("
                    COALESCE(SUM(DISTINCT CASE WHEN energy_users.id IS NOT NULL THEN 
                        energy_users.number_of_children ELSE 0 END), 0) +
                    COALESCE(SUM(DISTINCT CASE WHEN water_users.id IS NOT NULL THEN 
                        water_users.number_of_children ELSE 0 END), 0) +
                    COALESCE(SUM(DISTINCT CASE WHEN internet_holders.id IS NOT NULL 
                        THEN internet_holders.number_of_children ELSE 0 END), 0)
                    AS number_of_children
                "),
                DB::raw("
                    COALESCE(SUM(DISTINCT CASE WHEN energy_users.id IS NOT NULL THEN 
                        energy_users.number_of_male ELSE 0 END), 0) +
                    COALESCE(SUM(DISTINCT CASE WHEN water_users.id IS NOT NULL THEN 
                        water_users.number_of_male ELSE 0 END), 0) +
                    COALESCE(SUM(DISTINCT CASE WHEN internet_holders.id IS NOT NULL 
                        THEN internet_holders.number_of_male ELSE 0 END), 0)
                    AS number_of_male
                "),
                DB::raw("
                    COALESCE(SUM(DISTINCT CASE WHEN energy_users.id IS NOT NULL THEN 
                        energy_users.number_of_female ELSE 0 END), 0) +
                    COALESCE(SUM(DISTINCT CASE WHEN water_users.id IS NOT NULL THEN 
                        water_users.number_of_female ELSE 0 END), 0) +
                    COALESCE(SUM(DISTINCT CASE WHEN internet_holders.id IS NOT NULL 
                        THEN internet_holders.number_of_female ELSE 0 END), 0)
                    AS number_of_female
                "),
            ])
            ->groupBy('all_incidents.id')
            ->get();

        foreach ($data as $row) {

            $componentIds = $row->component_ids !== 'N/A' ? explode(',', $row->component_ids) : [];
            $componentTypes = $row->component_types !== 'N/A' ? explode(',', $row->component_types) : [];
            $cabinetModel = $row->cabinet_models !== 'N/A' ? $row->cabinet_models : 'Unknown Cabinet';

            $componentLines = [];

            foreach ($componentIds as $index => $componentId) {

                $type = $componentTypes[$index] ?? null;
                $componentId = trim($componentId);

                if ($type && $componentId) {
                    $className = trim(str_replace('\\\\', '\\', $type));

                    try {
                        if (class_exists($className)) {
                            $instance = app($className)->find($componentId);

                            if ($instance) {
                                $readableType = class_basename($className);
                                $readableTypeFormatted = match ($readableType) {
                                    default => $readableType,
                                };

                                $componentModel = $instance->model ?? 'Unknown Model';
                                $unit = $instance->unit ?? 1; 

                                $componentLines[] = "{$cabinetModel} - {$componentModel} ({$readableTypeFormatted}) ({$unit})";
                            }
                        }
                    } catch (\Throwable $e) {
                        $componentLines[] = "{$cabinetModel} - Error loading model ({$type})";
                    }
                }
            }

            $row->formatted_components = $componentLines;
            unset($row->component_types, $row->component_ids);
        }


        if($this->request->service_ids1) {

            $data->whereIn('all_incidents.service_type_id', $this->request->service_ids1);
        } 
        if($this->request->community_id1) {

            $data->where("all_incidents.community_id", $this->request->community_id1);
        }
        if($this->request->incident_id1) {

            $data->where("all_incidents.incident_id", $this->request->incident_id1);
        }
        if($this->request->date1) {

            $data->where("all_incidents.date", ">=", $this->request->date1);
        }


        $filtered = $data->map(function ($item) {
            return [
                'Agent Type' => $item->agent_type,
                'User/Public/System' => $item->agent,
                'Incident Date' => $item->date,
                'Community' => $item->community_name,
                'Region' => $item->region,
                'Incident Type' => $item->incident,
                'Service Types' => $item->department,
                'Energy Incident Status' => $item->energy_status,
                'Energy Holder Equipment Damaged' => $item->energy_equipment,
                'Losses Energy Holder (ILS)' => $item->total_energy_cost,
                'Energy System Equipment Damaged' => $item->energy_system_equipment,
                'Losses Energy System (ILS)' => $item->energy_system_cost,
                'Water Incident Status' => $item->water_status,
                'Water Equipment Damaged' => $item->water_equipment,
                'Losses Water (ILS)' => $item->total_water_cost,
                'Water System Equipment Damaged' => $item->water_system_equipment,
                'Losses Water System (ILS)' => $item->water_system_cost,
                'Internet Incident Status' => $item->internet_status,
                'Internet Equipment Damaged' => $item->internet_equipment,
                'Losses Internet (ILS)' => $item->total_internet_cost,
                'Internet System Equipment Damaged' => $item->internet_system_equipment ?? $item->formatted_components,
                'Losses Internet System (ILS)' => $item->internet_system_cost ?? $item->network_cost,
                'Camera Incident Status' => $item->camera_status,
                'Camera Equipment Damaged' => $item->camera_equipment,
                'Losses Cameras (ILS)' => $item->total_camera_cost,
                'Description of Incident' => $item->notes,
                'Description (User - USS)' => $item->description,
                'Description (Manager - USS)' => $item->manager_description,
                'Donor (Energy)' => $item->energy_donor_name,
                'Donor (Water)' => $item->water_donor_name,
                'Donor (Internet)' => $item->internet_donor_name,
                'Donor (Camera)' => $item->camera_donor_name,
                '# of Adult' => $item->number_of_adults,
                '# of Children' => $item->number_of_children,
                '# of Male' => $item->number_of_male,
                '# of Female' => $item->number_of_female
            ];
        });


        //die( $filtered);
        return $filtered;
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function headings(): array 
    {
        return ["Agent Type", "User/Public/System", "Incident Date", "Community", "Region", "Incident Type", 
            "Service Types", "Energy Incident Status", "Energy Holder Equipment Damaged", "Losses Energy Holder (ILS)", 
            "Energy System Equipment Damaged", "Losses Energy System (ILS)", "Water Incident Status", 
            "Water Equipment Damaged", "Losses Water (ILS)", "Water System Equipment Damaged", "Losses Water System (ILS)", 
            "Internet Incident Status", "Internet Equipment Damaged", "Losses Internet (ILS)", 
            "Internet System Equipment Damaged", "Losses Internet System (ILS)", "Camera Incident Status", 
            "Camera Equipment Damaged", "Losses Cameras (ILS)", "Description of Incident", "Description (User - USS)", 
            "Description (Manager - USS)", "Donor (Energy)", "Donor (Water)", "Donor (Internet)", 
            "Donor (Camera)", "# of Adult", "# of Children", "# of Male", "# of Female"];
    }

    public function title(): string
    {
        return 'All Aggregated Incidents';
    }

    /**
     * Styling
     *
     * @return response()
     */
    public function styles(Worksheet $sheet)
    {
        $sheet->setAutoFilter('A1:AJ1');

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