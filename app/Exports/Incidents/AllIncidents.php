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
use Cache;

class AllIncidents implements FromCollection, WithHeadings, WithTitle, 
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

    
        $data = Cache::remember('incidents_'.$this->request->donorName, 60, function () use($equipmentSubqueryEnergy, 
            $equipmentSubqueryWater, $equipmentSubqueryInternet, $equipmentSubqueryInternetNetwork) {
            return DB::table('all_incidents')
                ->join('communities', 'all_incidents.community_id', 'communities.id')
                ->join('regions', 'communities.region_id', 'regions.id')
                ->join('service_types', 'all_incidents.service_type_id', 'service_types.id')
                ->join('incidents', 'all_incidents.incident_id', 'incidents.id')
                ->join('all_incident_occurred_statuses', 'all_incidents.id', 'all_incident_occurred_statuses.all_incident_id')
                ->join('all_incident_statuses', 'all_incident_statuses.id', 'all_incident_occurred_statuses.all_incident_status_id')
                ->where('all_incidents.is_archived', 0)
                ->where('all_incidents.incident_id', '!=', 4)

                ->leftJoin('all_energy_incidents', 'all_incidents.id', 'all_energy_incidents.all_incident_id')
                ->leftJoin('all_energy_meters', 'all_energy_meters.id', 'all_energy_incidents.all_energy_meter_id')
                ->leftJoin('households as energy_users', 'all_energy_meters.household_id', 'energy_users.id')
                ->leftJoin('displaced_households as displaced_energy', 'displaced_energy.new_community_id', 'energy_users.community_id')
                ->leftJoin('communities as old_communities_energy', 'old_communities_energy.id', 'displaced_energy.old_community_id')

                ->leftJoin('public_structures as energy_publics', 'all_energy_meters.public_structure_id', 'energy_publics.id')
                ->leftJoin('energy_systems', 'energy_systems.id', 'all_energy_incidents.energy_system_id')
                ->leftJoin('all_energy_incident_damaged_equipment as energy_holder_equipment', 'all_energy_incidents.id', 
                    'energy_holder_equipment.all_energy_incident_id')
                ->leftJoin('incident_equipment as energy_equipment', 'energy_equipment.id', 
                    'energy_holder_equipment.incident_equipment_id')
                
                ->leftJoinSub($equipmentSubqueryEnergy, 'energy_equipments', function ($join) {
                    $join->on('all_energy_incidents.id', '=', 'energy_equipments.all_energy_incident_id');
                }) 

                ->leftJoin('all_water_incidents', 'all_incidents.id', 'all_water_incidents.all_incident_id')
                ->leftJoin('all_water_holders', 'all_water_holders.id', 'all_water_incidents.all_water_holder_id')
                ->leftJoin('households as water_users', 'all_water_holders.household_id', 'water_users.id')
                ->leftJoin('displaced_households as displaced_water', 'displaced_water.new_community_id', 'water_users.community_id')
                ->leftJoin('communities as old_communities_water', 'old_communities_water.id', 'displaced_water.old_community_id')
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
                ->leftJoin('displaced_households as displaced_internet', 'displaced_internet.new_community_id', 'internet_holders.community_id')
                ->leftJoin('communities as old_communities_internet', 'old_communities_internet.id', 'displaced_internet.old_community_id')
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
                    'all_incidents.date',
                    DB::raw('
                        CASE
                            WHEN old_communities_energy.english_name IS NOT NULL THEN CONCAT(old_communities_energy.english_name, " (", communities.english_name, ")")
                            WHEN old_communities_water.english_name IS NOT NULL THEN CONCAT(old_communities_water.english_name, " (", communities.english_name, ")")
                            WHEN old_communities_internet.english_name IS NOT NULL THEN CONCAT(old_communities_internet.english_name, " (", communities.english_name, ")")
                            ELSE communities.english_name
                        END AS community_name
                    '),

                    DB::raw("GROUP_CONCAT(DISTINCT regions.english_name SEPARATOR ', ') as region"),
                    DB::raw("GROUP_CONCAT(DISTINCT COALESCE(energy_users.english_name, energy_publics.english_name, 
                        water_users.english_name, water_publics.english_name, internet_holders.english_name , 
                        internet_publics.english_name) SEPARATOR ', ') as user"),
                    DB::raw("GROUP_CONCAT(DISTINCT COALESCE(energy_systems.name, water_systems.name, internet_systems.system_name, 
                        cameras_communities.english_name) SEPARATOR ', ') as system"),
                    DB::raw("GROUP_CONCAT(DISTINCT COALESCE(service_types.service_name) SEPARATOR ', ') as department"),
                    'incidents.english_name as incident',


                    DB::raw("GROUP_CONCAT(DISTINCT CASE WHEN energy_holder_equipment.count IS NULL 
                        THEN energy_equipment.name ELSE CONCAT(energy_equipment.name, 
                        ' (', energy_holder_equipment.count, ')') END SEPARATOR ', ') as energy_equipment"),
                    'energy_equipments.equipment_models as energy_system_equipment',
                    DB::raw('SUM(DISTINCT energy_holder_equipment.cost) as total_energy_cost'),
                    'energy_equipments.total_energy_system_cost as energy_system_cost',
                    DB::raw("GROUP_CONCAT(DISTINCT COALESCE(
                        CASE WHEN community_donors.service_id = 1 THEN energy_system_donors.donor_name ELSE energy_holder_donors.donor_name END
                    ) SEPARATOR ', ') as energy_donor_name"),
                    DB::raw("GROUP_CONCAT(DISTINCT CASE WHEN all_incidents.service_type_id = 1 THEN 
                        all_incident_statuses.status END SEPARATOR ', ') as energy_status"),


                    DB::raw("GROUP_CONCAT(DISTINCT CASE WHEN all_water_incident_damaged_equipment.count IS NULL 
                        THEN water_equipment.name ELSE CONCAT(water_equipment.name, 
                        ' (', all_water_incident_damaged_equipment.count, ')') END SEPARATOR ', ') as water_equipment"),
                    'water_equipments.equipment_models as water_system_equipment',
                    DB::raw('SUM(DISTINCT all_water_incident_damaged_equipment.cost) as total_water_cost'),
                    'water_equipments.total_water_system_cost as water_system_cost',
                    DB::raw("GROUP_CONCAT(DISTINCT COALESCE(water_holder_donors.donor_name, '') SEPARATOR ', ') as water_donor_name"),
                    DB::raw("GROUP_CONCAT(DISTINCT CASE WHEN all_incidents.service_type_id = 2 THEN 
                        all_incident_statuses.status END SEPARATOR ', ') as water_status"),


                    DB::raw("GROUP_CONCAT(DISTINCT CASE WHEN all_internet_incident_damaged_equipment.count IS NULL 
                        THEN internet_equipment.name ELSE CONCAT(internet_equipment.name, 
                        ' (', all_internet_incident_damaged_equipment.count, ')') END SEPARATOR ', ') as internet_equipment"),
                    'internet_equipments.equipment_models as internet_system_equipment',
                    'internet_network_equipments.component_ids as component_ids',
                    'internet_network_equipments.component_types as component_types',
                    'internet_network_equipments.cabinet_models as cabinet_models',
                    DB::raw('SUM(DISTINCT all_internet_incident_damaged_equipment.cost) as total_internet_cost'),
                    'internet_equipments.total_internet_system_cost as internet_system_cost',
                    'internet_network_equipments.total_internet_system_cost as network_cost',
                    DB::raw("GROUP_CONCAT(DISTINCT COALESCE(CASE WHEN community_donors.service_id = 3 THEN 
                        internet_system_donors.donor_name ELSE internet_holder_donors.donor_name END
                        ) SEPARATOR ', ') as internet_donor_name"),
                    DB::raw("GROUP_CONCAT(DISTINCT CASE WHEN all_incidents.service_type_id = 3 THEN 
                        all_incident_statuses.status END SEPARATOR ', ') as internet_status"),

                    
                    DB::raw("GROUP_CONCAT(DISTINCT CASE WHEN all_camera_incident_damaged_equipment.count IS NULL 
                        THEN camera_equipment.name ELSE CONCAT(camera_equipment.name, ' (', 
                        all_camera_incident_damaged_equipment.count, ')') END SEPARATOR ', ') as camera_equipment"),
                    DB::raw('SUM(DISTINCT all_camera_incident_damaged_equipment.cost) as total_camera_cost'),
                    DB::raw("GROUP_CONCAT(DISTINCT COALESCE(camera_donors.donor_name, '') SEPARATOR ', ') as camera_donor_name"),
                    DB::raw("GROUP_CONCAT(DISTINCT CASE WHEN all_incidents.service_type_id = 4 THEN 
                        all_incident_statuses.status END SEPARATOR ', ') as camera_status"),

                    'all_incidents.notes',
                    'all_incidents.description',
                    'all_incidents.manager_description',

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
                ])->orderBy('all_incidents.date', 'desc')
                  ->groupBy('all_incidents.date', 'all_incidents.community_id', 'all_incidents.incident_id')
                  ->get();
        });


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
        if($this->request->date) {

            $data->where("all_incidents.date_to", "<=", $this->request->date);
        }


        foreach ($data as $row) {
            // Safe component handling with optional() to avoid errors
            $componentIds = optional($row)->component_ids !== 'N/A' ? explode(',', $row->component_ids) : [];
            $componentTypes = optional($row)->component_types !== 'N/A' ? explode(',', $row->component_types) : [];
            $cabinetModel = optional($row)->cabinet_models !== 'N/A' ? $row->cabinet_models : 'Unknown Cabinet';

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

        $filtered = $data->map(function ($item) {
            return [
                'Incident Date' => $item->date,
                'Community' => $item->community_name,
                'Region' => $item->region,
                'User/Public (any energy user - MG or FBS); Water user, Internet user' => $item->user,
                'System' => $item->system,
                'Service Types' => $item->department,
                'Incident Type' => $item->incident,
                
                
                'Energy Holder Equipment Damaged' => $item->energy_equipment,
                'Energy System Equipment Damaged' => $item->energy_system_equipment,
                'Losses Energy Holder (ILS)' => $item->total_energy_cost,
                'Losses Energy System (ILS)' => $item->energy_system_cost,
                'Donor (Energy)' => $item->energy_donor_name,
                'Energy Incident Status' => $item->energy_status,
                
                'Water Equipment Damaged' => $item->water_equipment,
                'Water System Equipment Damaged' => $item->water_system_equipment,
                'Losses Water (ILS)' => $item->total_water_cost,
                'Losses Water System (ILS)' => $item->water_system_cost,
                'Donor (Water)' => $item->water_donor_name,
                'Water Incident Status' => $item->water_status,

                'Internet Equipment Damaged' => $item->internet_equipment,
                'Internet System Equipment Damaged' => $item->internet_system_equipment ?? $item->formatted_components,
                'Losses Internet (ILS)' => $item->total_internet_cost,
                'Losses Internet System (ILS)' => $item->internet_system_cost ?? $item->network_cost,
                'Donor (Internet)' => $item->internet_donor_name,
                'Internet Incident Status' => $item->internet_status,

                'Camera Incident Status' => $item->camera_status,
                'Camera Equipment Damaged' => $item->camera_equipment,
                'Losses Cameras (ILS)' => $item->total_camera_cost,
                'Donor (Camera)' => $item->camera_donor_name,

                'Description of Incident' => $item->notes,
                'Description (User - USS)' => $item->description,
                'Description (Manager - USS)' => $item->manager_description,

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
        return [
            "Incident Date", "Community", "Region", 
            "User/Public (any energy user - MG or FBS); Water user, Internet user",
            "System", "Service Types", "Incident Type",  
            
            "Energy Holder Equipment Damaged", "Losses Energy Holder (ILS)", "Energy MG System Equipment Damaged", 
            "Losses Energy MG System (ILS)", "Donor (Energy)", "Energy Incident Status",
            
            "Water Equipment Damaged", "Water System Equipment Damaged", "Losses Water (ILS)", 
            "Losses Water System (ILS)", "Donor (Water)", "Water Incident Status", 
            
            "Internet Equipment Damaged", "Internet System Equipment Damaged", "Losses Internet (ILS)",
            "Losses Internet System (ILS)", "Donor (Internet)", "Internet Incident Status", 
            
            "Camera Incident Status", "Camera Equipment Damaged", "Losses Cameras (ILS)", "Donor (Camera)", 

            "Description of Incident", "Description (User - USS)", "Description (Manager - USS)",  
            "# of Adult", "# of Children", "# of Male", "# of Female"
        ];
    }

    public function title(): string
    {
        return 'Total Incidents';
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