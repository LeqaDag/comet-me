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
        
        $equipmentSubqueryEnergy = DB::table('all_energy_incident_system_damaged_equipment as energy_system_equipment')
        
            ->leftJoin('energy_system_batteries', 'energy_system_batteries.id',
                'energy_system_equipment.energy_system_battery_id') 
            ->LeftJoin('energy_batteries', 'energy_system_batteries.battery_type_id', 'energy_batteries.id')

            ->leftJoin('energy_system_battery_mounts', 'energy_system_battery_mounts.id',
                'energy_system_equipment.energy_system_battery_mount_id') 
            ->LeftJoin('energy_battery_mounts', 'energy_system_battery_mounts.energy_battery_mount_id', 
                'energy_battery_mounts.id')

            ->leftJoin('energy_system_battery_status_processors', 'energy_system_battery_status_processors.id',
                'energy_system_equipment.energy_system_battery_status_processor_id') 
            ->LeftJoin('energy_battery_status_processors', 'energy_system_battery_status_processors.energy_battery_status_processor_id', 
                'energy_battery_status_processors.id')

            ->LeftJoin('energy_system_battery_temperature_sensors', 'energy_system_battery_temperature_sensors.id',
                'energy_system_equipment.energy_system_battery_temperature_sensor_id')
            ->LeftJoin('energy_battery_temperature_sensors', 'energy_system_battery_temperature_sensors.energy_battery_temperature_sensor_id',  
                'energy_battery_temperature_sensors.id')

            ->LeftJoin('energy_system_charge_controllers', 'energy_system_charge_controllers.id',
                'energy_system_equipment.energy_system_charge_controller_id')
            ->LeftJoin('energy_charge_controllers', 'energy_system_charge_controllers.energy_charge_controller_id',  
                'energy_charge_controllers.id')

            ->LeftJoin('energy_system_generators', 'energy_system_generators.id',
                'energy_system_equipment.energy_system_generator_id')
            ->LeftJoin('energy_generators', 'energy_system_generators.energy_generator_id',  
                'energy_generators.id')

            ->LeftJoin('energy_system_inverters', 'energy_system_inverters.id',
                'energy_system_equipment.energy_system_inverter_id')
            ->LeftJoin('energy_inverters', 'energy_system_inverters.energy_inverter_id',  
                'energy_inverters.id')

            ->LeftJoin('energy_system_load_relays', 'energy_system_load_relays.id',
                'energy_system_equipment.energy_system_load_relay_id')
            ->LeftJoin('energy_load_relays', 'energy_system_load_relays.energy_load_relay_id',  
                'energy_load_relays.id')

            ->LeftJoin('energy_system_mcb_charge_controllers', 'energy_system_mcb_charge_controllers.id',
                'energy_system_equipment.energy_system_mcb_charge_controller_id')
            ->LeftJoin('energy_mcb_charge_controllers', 'energy_system_mcb_charge_controllers.energy_mcb_charge_controller_id',  
                'energy_mcb_charge_controllers.id')

            ->LeftJoin('energy_system_mcb_inverters', 'energy_system_mcb_inverters.id',
                'energy_system_equipment.energy_system_mcb_inverter_id')
            ->LeftJoin('energy_mcb_inverters', 'energy_system_mcb_inverters.energy_mcb_inverter_id', 
                'energy_mcb_inverters.id')

            ->LeftJoin('energy_system_mcb_pvs', 'energy_system_mcb_pvs.id',
                'energy_system_equipment.energy_system_mcb_pv_id')
            ->LeftJoin('energy_mcb_pvs', 'energy_system_mcb_pvs.energy_mcb_pv_id',  
                'energy_mcb_pvs.id')

            ->LeftJoin('energy_system_monitorings', 'energy_system_monitorings.id',
                'energy_system_equipment.energy_system_monitoring_id')
            ->LeftJoin('energy_monitorings', 'energy_system_monitorings.energy_monitoring_id',  
                'energy_monitorings.id')

            ->LeftJoin('energy_system_pvs', 'energy_system_pvs.id',
                'energy_system_equipment.energy_system_pv_id')
            ->LeftJoin('energy_pvs', 'energy_system_pvs.pv_type_id',  
                'energy_pvs.id')

            ->LeftJoin('energy_system_pv_mounts', 'energy_system_pv_mounts.id',
                'energy_system_equipment.energy_system_pv_mount_id')
            ->LeftJoin('energy_pv_mounts', 'energy_system_pv_mounts.energy_pv_mount_id',  
                'energy_pv_mounts.id')

            ->LeftJoin('energy_system_relay_drivers', 'energy_system_relay_drivers.id',
                'energy_system_equipment.energy_system_relay_driver_id')
            ->LeftJoin('energy_relay_drivers', 'energy_system_relay_drivers.relay_driver_type_id',  
                'energy_relay_drivers.id')

            ->LeftJoin('energy_system_remote_control_centers', 'energy_system_remote_control_centers.id',
                'energy_system_equipment.energy_system_remote_control_center_id')
            ->LeftJoin('energy_remote_control_centers', 'energy_system_remote_control_centers.energy_remote_control_center_id',  
                'energy_remote_control_centers.id')

            ->LeftJoin('energy_system_wind_turbines', 'energy_system_wind_turbines.id',
                'energy_system_equipment.energy_system_wind_turbine_id')
            ->LeftJoin('energy_wind_turbines', 'energy_system_wind_turbines.energy_wind_turbine_id',  
                'energy_wind_turbines.id')

            ->LeftJoin('energy_system_air_conditioners', 'energy_system_air_conditioners.id',
                'energy_system_equipment.energy_system_air_conditioner_id')
            ->LeftJoin('energy_air_conditioners', 'energy_system_air_conditioners.energy_air_conditioner_id',  
                'energy_air_conditioners.id')

            ->select(
                'energy_system_equipment.all_energy_incident_id',
                DB::raw("GROUP_CONCAT(DISTINCT COALESCE(
                    CONCAT(energy_batteries.battery_model, ' (Battery - ', energy_system_equipment.count, ')'),
                    CONCAT(energy_battery_mounts.model, ' (Battery Mount - ', energy_system_equipment.count, ')'),
                    CONCAT(energy_battery_status_processors.model, ' (BSP - ', energy_system_equipment.count, ')'),
                    CONCAT(energy_battery_temperature_sensors.BTS_model, ' (BTS - ', energy_system_equipment.count, ')'),
                    CONCAT(energy_charge_controllers.charge_controller_model, ' (Charge Controller - ', energy_system_equipment.count, ')'),
                    CONCAT(energy_generators.generator_model, ' (Generator - ', energy_system_equipment.count, ')'),
                    CONCAT(energy_inverters.inverter_model, ' (Inverter - ', energy_system_equipment.count, ')'),
                    CONCAT(energy_load_relays.load_relay_model, ' (Load Relay - ', energy_system_equipment.count, ')'),
                    CONCAT(energy_mcb_charge_controllers.model, ' (Charge Controller MCB) - ', energy_system_equipment.count, ')'),
                    CONCAT(energy_mcb_pvs.model, ' (PV MCB - ', energy_system_equipment.count, ')'),
                    CONCAT(energy_pvs.pv_model, ' (PV - ', energy_system_equipment.count, ')'),
                    CONCAT(energy_pv_mounts.model, ' (PV Mount - ', energy_system_equipment.count, ')'),
                    CONCAT(energy_relay_drivers.model, ' (Relay Driver - ', energy_system_equipment.count, ')'),
                    CONCAT(energy_remote_control_centers.model, ' (RCC - ', energy_system_equipment.count, ')'),
                    CONCAT(energy_wind_turbines.wind_turbine_model, ' (Turbine - ', energy_system_equipment.count, ')'),
                    CONCAT(energy_air_conditioners.model, ' (Air Conditioner - ', energy_system_equipment.count, ')'),
                    CONCAT(energy_monitorings.monitoring_model, ' (Logger - ', energy_system_equipment.count, ')'),
                    CONCAT(energy_mcb_inverters.inverter_MCB_model, ' (Inverter MCB - ', energy_system_equipment.count, ')')
                    ) SEPARATOR ', ') as equipment_models"),
                DB::raw('SUM(energy_system_equipment.cost * energy_system_equipment.count) as total_energy_system_cost'),
            )
            ->groupBy('energy_system_equipment.all_energy_incident_id');


        $equipmentSubqueryWater = DB::table('all_water_incident_system_damaged_equipment as water_system_equipment')
        
            ->leftJoin('water_system_connectors', 'water_system_connectors.id',
                'water_system_equipment.water_system_connector_id') 
            ->LeftJoin('water_connectors', 'water_system_connectors.water_connector_id', 'water_connectors.id')

            ->leftJoin('water_system_filters', 'water_system_filters.id',
                'water_system_equipment.water_system_filter_id') 
            ->LeftJoin('water_filters', 'water_system_filters.water_filter_id', 
                'water_filters.id')

            ->leftJoin('water_system_pipes', 'water_system_pipes.id',
                'water_system_equipment.water_system_pipe_id') 
            ->LeftJoin('water_pipes', 'water_system_pipes.water_pipe_id', 
                'water_pipes.id')

            ->LeftJoin('water_system_pumps', 'water_system_pumps.id',
                'water_system_equipment.water_system_pump_id')
            ->LeftJoin('water_pumps', 'water_system_pumps.water_pump_id',  
                'water_pumps.id')

            ->LeftJoin('water_system_tanks', 'water_system_tanks.id',
                'water_system_equipment.water_system_tank_id')
            ->LeftJoin('water_tanks', 'water_system_tanks.water_tank_id',  
                'water_tanks.id')

            ->LeftJoin('water_system_taps', 'water_system_taps.id',
                'water_system_equipment.water_system_tap_id')
            ->LeftJoin('water_taps', 'water_system_taps.water_tap_id',  
                'water_taps.id')

            ->LeftJoin('water_system_valves', 'water_system_valves.id',
                'water_system_equipment.water_system_valve_id')
            ->LeftJoin('water_valves', 'water_system_valves.water_valve_id',  
                'water_valves.id')


            ->select(
                'water_system_equipment.all_water_incident_id',
                DB::raw("GROUP_CONCAT(DISTINCT COALESCE(
                    CONCAT(water_connectors.model, ' (Connector - ', water_system_equipment.count, ')'),
                    CONCAT(water_filters.model, ' (Filter - ', water_system_equipment.count, ')'),
                    CONCAT(water_pipes.model, ' (Pipe - ', water_system_equipment.count, ')'),
                    CONCAT(water_pumps.model, ' (Pump - ', water_system_equipment.count, ')'),
                    CONCAT(water_tanks.model, ' (Tank - ', water_system_equipment.count, ')'),
                    CONCAT(water_taps.model, ' (Tap - ', water_system_equipment.count, ')'),
                    CONCAT(water_valves.model, ' (Valve - ', water_system_equipment.count, ')')
                    ) SEPARATOR ', ') as equipment_models"),
                DB::raw('SUM(water_system_equipment.cost * water_system_equipment.count) as total_water_system_cost'),
            )
            ->groupBy('water_system_equipment.all_water_incident_id');


        $equipmentSubqueryInternet = DB::table('all_internet_incident_system_damaged_equipment as internet_system_equipment')
        
            ->leftJoin('router_internet_systems', 'router_internet_systems.id',
                'internet_system_equipment.router_internet_system_id')
            ->LeftJoin('routers', 'router_internet_systems.router_id', 'routers.id')


            ->leftJoin('switch_internet_systems', 'switch_internet_systems.id',
                'internet_system_equipment.switch_internet_system_id')
            ->LeftJoin('switches', 'switch_internet_systems.switch_id', 'switches.id')


            ->leftJoin('controller_internet_systems', 'controller_internet_systems.id',
                'internet_system_equipment.controller_internet_system_id')
            ->LeftJoin('internet_controllers', 'controller_internet_systems.internet_controller_id', 'internet_controllers.id')


            ->leftJoin('ptp_internet_systems', 'ptp_internet_systems.id',
                'internet_system_equipment.ptp_internet_system_id')
            ->LeftJoin('internet_ptps', 'ptp_internet_systems.internet_ptp_id', 'internet_ptps.id')


            ->leftJoin('ap_internet_systems', 'ap_internet_systems.id',
                'internet_system_equipment.ap_internet_system_id')
            ->LeftJoin('internet_aps', 'ap_internet_systems.internet_ap_id', 'internet_aps.id')


            ->leftJoin('ap_lite_internet_systems', 'ap_lite_internet_systems.id',
                'internet_system_equipment.ap_lite_internet_system_id')
            ->LeftJoin('internet_aps as aplites', 'ap_internet_systems.internet_ap_id', 'aplites.id')


            ->leftJoin('uisp_internet_systems', 'uisp_internet_systems.id',
                'internet_system_equipment.uisp_internet_system_id')
            ->LeftJoin('internet_uisps', 'uisp_internet_systems.internet_uisp_id', 'internet_uisps.id')


            ->leftJoin('connector_internet_systems', 'connector_internet_systems.id',
                'internet_system_equipment.connector_internet_system_id')
            ->LeftJoin('internet_connectors', 'connector_internet_systems.internet_connector_id', 'internet_connectors.id')

            ->leftJoin('electrician_internet_systems', 'electrician_internet_systems.id',
                'internet_system_equipment.electrician_internet_system_id')
            ->LeftJoin('internet_electricians', 'electrician_internet_systems.internet_electrician_id', 'internet_electricians.id')

            ->select(
                'internet_system_equipment.all_internet_incident_id',
                DB::raw("GROUP_CONCAT(DISTINCT COALESCE(
                    CONCAT(routers.model, ' (Router - ', internet_system_equipment.count, ')'),
                    CONCAT(switches.model, ' (Switch - ', internet_system_equipment.count, ')'),
                    CONCAT(internet_uisps.model, ' (UISP - ', internet_system_equipment.count, ')'),
                    CONCAT(internet_ptps.model, ' (PTP - ', internet_system_equipment.count, ')'),
                    CONCAT(internet_connectors.model, ' (Connector - ', internet_system_equipment.count, ')'),
                    CONCAT(internet_electricians.model, ' (Electrician - ', internet_system_equipment.count, ')'),
                    CONCAT(internet_aps.model, ' (AP - ', internet_system_equipment.count, ')'),
                    CONCAT(aplites.model, ' (AP Lite - ', internet_system_equipment.count, ')'),
                    CONCAT(internet_controllers.model, ' (Controller - ', internet_system_equipment.count, ')')
                    ) SEPARATOR ', ') as equipment_models"),
                DB::raw('SUM(internet_system_equipment.cost * internet_system_equipment.count) as total_internet_system_cost'),
            )
            ->groupBy('internet_system_equipment.all_internet_incident_id');


        $equipmentSubqueryInternetNetwork = DB::table('all_internet_incident_system_damaged_equipment')
            ->leftJoin('network_cabinet_components', 'network_cabinet_components.id',
                'all_internet_incident_system_damaged_equipment.network_cabinet_component_id')
            ->leftJoin('network_cabinet_internet_systems', 'network_cabinet_internet_systems.id',
                'network_cabinet_components.network_cabinet_internet_system_id')
            ->leftJoin('network_cabinets', 'network_cabinet_internet_systems.network_cabinet_id', 'network_cabinets.id')
            ->select(
                'all_internet_incident_system_damaged_equipment.all_internet_incident_id',
                DB::raw("COALESCE(GROUP_CONCAT(network_cabinet_components.component_type), 'N/A') as component_types"),
                DB::raw("COALESCE(GROUP_CONCAT(network_cabinet_components.component_id), 'N/A') as component_ids"),
                DB::raw("COALESCE(GROUP_CONCAT(DISTINCT network_cabinets.model), 'N/A') as cabinet_models"),
                DB::raw('SUM(all_internet_incident_system_damaged_equipment.cost * 
                    all_internet_incident_system_damaged_equipment.count) as total_internet_system_cost'),
            )

            ->groupBy('all_internet_incident_system_damaged_equipment.all_internet_incident_id');

    
        // foreach ($equipmentSubqueryInternetNetwork as $row) {

        //     $componentIds = $row->component_ids !== 'N/A' ? explode(',', $row->component_ids) : [];
        //     $componentTypes = $row->component_types !== 'N/A' ? explode(',', $row->component_types) : [];
        //     $cabinetModel = $row->cabinet_models !== 'N/A' ? $row->cabinet_models : 'Unknown Cabinet';

        //     $componentLines = [];

        //     foreach ($componentIds as $index => $componentId) {

        //         $type = $componentTypes[$index] ?? null;
        //         $componentId = trim($componentId);

        //         if ($type && $componentId) {
        //             $className = trim(str_replace('\\\\', '\\', $type));

        //             try {
        //                 if (class_exists($className)) {
        //                     $instance = app($className)->find($componentId);

        //                     if ($instance) {
        //                         $readableType = class_basename($className);
        //                         $readableTypeFormatted = match ($readableType) {
        //                             default => $readableType,
        //                         };

        //                         $componentModel = $instance->model ?? 'Unknown Model';
        //                         $unit = $instance->unit ?? 1; 

        //                         $componentLines[] = "{$cabinetModel} - {$componentModel} ({$readableTypeFormatted}) ({$unit})";
        //                     }
        //                 }
        //             } catch (\Throwable $e) {
        //                 $componentLines[] = "{$cabinetModel} - Error loading model ({$type})";
        //             }
        //         }
        //     }

        //     $row->formatted_components = $componentLines;
        //     unset($row->component_types, $row->component_ids);
        // }


        

        // die($equipmentSubqueryInternetNetwork); 
        
        $data = DB::table('all_incidents')
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
                'all_incidents.date',
                DB::raw("GROUP_CONCAT(DISTINCT COALESCE(energy_users.english_name, energy_publics.english_name, 
                    water_users.english_name, water_publics.english_name, internet_holders.english_name , 
                    internet_publics.english_name) SEPARATOR ', ') as user"),
                DB::raw("GROUP_CONCAT(DISTINCT COALESCE(energy_systems.name, water_systems.name, internet_systems.system_name, 
                    cameras_communities.english_name) SEPARATOR ', ') as system"),
                DB::raw("GROUP_CONCAT(DISTINCT communities.english_name SEPARATOR ', ') as community_name"),
                'regions.english_name as region', 
                'incidents.english_name as incident',
                DB::raw("GROUP_CONCAT(DISTINCT COALESCE(service_types.service_name) SEPARATOR ', ') as department"),

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
            ->orderBy('all_incidents.date', 'desc')
            ->groupBy('all_incidents.date')
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


        $filtered = $data->map(function ($item) {
            return [
                'Incident Date' => $item->date,
                'User/Public (any energy user - MG or FBS); Water user, Internet user' => $item->user,
                'System' => $item->system,
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
                'Description (USS)' => $item->description,
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
        return ["Incident Date", "User/Public (any energy user - MG or FBS); Water user, Internet user",
            "System", "Community", "Region", "Incident Type", "Service Types", "Energy Incident Status", 
            "Energy Holder Equipment Damaged", "Losses Energy Holder (ILS)", "Energy System Equipment Damaged", 
            "Losses Energy System (ILS)", "Water Incident Status", "Water Equipment Damaged", "Losses Water (ILS)", 
            "Water System Equipment Damaged", "Losses Water System (ILS)", "Internet Incident Status", 
            "Internet Equipment Damaged", "Losses Internet (ILS)", "Internet System Equipment Damaged", 
            "Losses Internet System (ILS)", "Camera Incident Status", "Camera Equipment Damaged", 
            "Losses Cameras (ILS)", "Description of Incident", "Description (USS)", "Donor (Energy)", 
            "Donor (Water)", "Donor (Internet)", "Donor (Camera)", "# of Adult", "# of Children", 
            "# of Male", "# of Female"];
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