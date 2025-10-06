<?php

namespace App\Exports\Incidents;

use Illuminate\Support\Facades\DB;

class IncidentEquipmentQueryBuilder
{
    public static function getEnergyEquipmentSubquery()
    {
        return DB::table('all_energy_incident_system_damaged_equipment as energy_system_equipment')
            ->leftJoin('energy_system_batteries', 'energy_system_batteries.id', 'energy_system_equipment.energy_system_battery_id') 
            ->leftJoin('energy_batteries', 'energy_system_batteries.battery_type_id', 'energy_batteries.id')
            ->leftJoin('energy_system_battery_mounts', 'energy_system_battery_mounts.id', 'energy_system_equipment.energy_system_battery_mount_id') 
            ->leftJoin('energy_battery_mounts', 'energy_system_battery_mounts.energy_battery_mount_id', 'energy_battery_mounts.id')
            ->leftJoin('energy_system_battery_status_processors', 'energy_system_battery_status_processors.id', 'energy_system_equipment.energy_system_battery_status_processor_id') 
            ->leftJoin('energy_battery_status_processors', 'energy_system_battery_status_processors.energy_battery_status_processor_id', 'energy_battery_status_processors.id')
            ->leftJoin('energy_system_battery_temperature_sensors', 'energy_system_battery_temperature_sensors.id', 'energy_system_equipment.energy_system_battery_temperature_sensor_id')
            ->leftJoin('energy_battery_temperature_sensors', 'energy_system_battery_temperature_sensors.energy_battery_temperature_sensor_id', 'energy_battery_temperature_sensors.id')
            ->leftJoin('energy_system_charge_controllers', 'energy_system_charge_controllers.id', 'energy_system_equipment.energy_system_charge_controller_id')
            ->leftJoin('energy_charge_controllers', 'energy_system_charge_controllers.energy_charge_controller_id', 'energy_charge_controllers.id')
            ->leftJoin('energy_system_generators', 'energy_system_generators.id', 'energy_system_equipment.energy_system_generator_id')
            ->leftJoin('energy_generators', 'energy_system_generators.energy_generator_id', 'energy_generators.id')
            ->leftJoin('energy_system_inverters', 'energy_system_inverters.id', 'energy_system_equipment.energy_system_inverter_id')
            ->leftJoin('energy_inverters', 'energy_system_inverters.energy_inverter_id', 'energy_inverters.id')
            ->leftJoin('energy_system_load_relays', 'energy_system_load_relays.id', 'energy_system_equipment.energy_system_load_relay_id')
            ->leftJoin('energy_load_relays', 'energy_system_load_relays.energy_load_relay_id', 'energy_load_relays.id')
            ->leftJoin('energy_system_mcb_charge_controllers', 'energy_system_mcb_charge_controllers.id', 'energy_system_equipment.energy_system_mcb_charge_controller_id')
            ->leftJoin('energy_mcb_charge_controllers', 'energy_system_mcb_charge_controllers.energy_mcb_charge_controller_id', 'energy_mcb_charge_controllers.id')
            ->leftJoin('energy_system_mcb_inverters', 'energy_system_mcb_inverters.id', 'energy_system_equipment.energy_system_mcb_inverter_id')
            ->leftJoin('energy_mcb_inverters', 'energy_system_mcb_inverters.energy_mcb_inverter_id', 'energy_mcb_inverters.id')
            ->leftJoin('energy_system_mcb_pvs', 'energy_system_mcb_pvs.id', 'energy_system_equipment.energy_system_mcb_pv_id')
            ->leftJoin('energy_mcb_pvs', 'energy_system_mcb_pvs.energy_mcb_pv_id', 'energy_mcb_pvs.id')
            ->leftJoin('energy_system_monitorings', 'energy_system_monitorings.id', 'energy_system_equipment.energy_system_monitoring_id')
            ->leftJoin('energy_monitorings', 'energy_system_monitorings.energy_monitoring_id', 'energy_monitorings.id')
            ->leftJoin('energy_system_pvs', 'energy_system_pvs.id', 'energy_system_equipment.energy_system_pv_id')
            ->leftJoin('energy_pvs', 'energy_system_pvs.pv_type_id', 'energy_pvs.id')
            ->leftJoin('energy_system_pv_mounts', 'energy_system_pv_mounts.id', 'energy_system_equipment.energy_system_pv_mount_id')
            ->leftJoin('energy_pv_mounts', 'energy_system_pv_mounts.energy_pv_mount_id', 'energy_pv_mounts.id')
            ->leftJoin('energy_system_relay_drivers', 'energy_system_relay_drivers.id', 'energy_system_equipment.energy_system_relay_driver_id')
            ->leftJoin('energy_relay_drivers', 'energy_system_relay_drivers.relay_driver_type_id', 'energy_relay_drivers.id')
            ->leftJoin('energy_system_remote_control_centers', 'energy_system_remote_control_centers.id', 'energy_system_equipment.energy_system_remote_control_center_id')
            ->leftJoin('energy_remote_control_centers', 'energy_system_remote_control_centers.energy_remote_control_center_id', 'energy_remote_control_centers.id')
            ->leftJoin('energy_system_wind_turbines', 'energy_system_wind_turbines.id', 'energy_system_equipment.energy_system_wind_turbine_id')
            ->leftJoin('energy_wind_turbines', 'energy_system_wind_turbines.energy_wind_turbine_id', 'energy_wind_turbines.id')
            ->leftJoin('energy_system_air_conditioners', 'energy_system_air_conditioners.id', 'energy_system_equipment.energy_system_air_conditioner_id')
            ->leftJoin('energy_air_conditioners', 'energy_system_air_conditioners.energy_air_conditioner_id', 'energy_air_conditioners.id')
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
                DB::raw('SUM(energy_system_equipment.cost * energy_system_equipment.count) as total_energy_system_cost')
            )
            ->groupBy('energy_system_equipment.all_energy_incident_id');
    }

    public static function getWaterEquipmentSubquery() 
    {

        return DB::table('all_water_incident_system_damaged_equipment as water_system_equipment')
        
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
    }

    public static function getInternetEquipmentSubquery() 
    {
        return DB::table('all_internet_incident_system_damaged_equipment as internet_system_equipment')
        
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
    }
    
    public static function getNetworkEquipmentSubquery() 
    {
        return DB::table('all_internet_incident_system_damaged_equipment')
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
    }
}