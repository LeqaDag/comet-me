<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnergySystem extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'energy_system_type_id', 'installation_year'];


    public function Community()
    {
        
        return $this->belongsTo(Community::class, 'community_id', 'id');
    }

    public function EnergySystemType()
    {
        
        return $this->belongsTo(EnergySystemType::class, 'energy_system_type_id', 'id');
    }

    public function EnergySystemCycle()
    {
        
        return $this->belongsTo(EnergySystemCycle::class, 'energy_system_cycle_id', 'id');
    }

    public function batteries()
    {
        return $this->belongsToMany(
            EnergyBattery::class, 
            'energy_system_batteries', 'energy_system_id', 'battery_type_id')
        ->withPivot('id', 'cost'); 
    }

    public function pvs() {

        return $this->belongsToMany(
            EnergyPv::class, 
            'energy_system_pvs', 'energy_system_id', 'pv_type_id')
        ->withPivot('id', 'cost');
    }

    public function batteryMount() {

        return $this->belongsToMany(
            EnergyBatteryMount::class, 
            'energy_system_battery_mounts', 'energy_system_id', 'energy_battery_mount_id')
        ->withPivot('id', 'cost');
    }

    public function airConditioners() {

        return $this->belongsToMany(
            EnergyAirConditioner::class, 
            'energy_system_air_conditioners', 'energy_system_id', 'energy_air_conditioner_id')
        ->withPivot('id', 'cost');
    }
    
    public function bsp() {

        return $this->belongsToMany(
            EnergyBatteryStatusProcessor::class, 
            'energy_system_battery_status_processors', 'energy_system_id', 'energy_battery_status_processor_id')
        ->withPivot('id', 'cost');
    }

    public function bts() {

        return $this->belongsToMany(
            EnergyBatteryTemperatureSensor::class, 
            'energy_system_battery_temperature_sensors', 'energy_system_id', 'energy_battery_temperature_sensor_id')
        ->withPivot('id', 'cost');
    }

    public function chargeController() {

        return $this->belongsToMany(
            EnergyChargeController::class, 
            'energy_system_charge_controllers', 'energy_system_id', 'energy_charge_controller_id')
        ->withPivot('id', 'cost');
    }

    public function generator() {

        return $this->belongsToMany(
            EnergyGenerator::class, 
            'energy_system_generators', 'energy_system_id', 'energy_generator_id')
        ->withPivot('id', 'cost');
    }

    public function inverter() {

        return $this->belongsToMany(
            EnergyInverter::class, 
            'energy_system_inverters', 'energy_system_id', 'energy_inverter_id')
        ->withPivot('id', 'cost');
    }

    public function loadRelay() {

        return $this->belongsToMany(
            EnergyLoadRelay::class, 
            'energy_system_load_relays', 'energy_system_id', 'energy_load_relay_id')
        ->withPivot('id', 'cost');
    }

    public function mcbChargeController() {

        return $this->belongsToMany(
            EnergyMcbChargeController::class, 
            'energy_system_mcb_charge_controllers', 'energy_system_id', 'energy_mcb_charge_controller_id')
        ->withPivot('id', 'cost');
    }

    public function mcbInverter() {

        return $this->belongsToMany(
            EnergyMcbInverter::class, 
            'energy_system_mcb_inverters', 'energy_system_id', 'energy_mcb_inverter_id')
        ->withPivot('id', 'cost');
    }

    public function mcbPv() {

        return $this->belongsToMany(
            EnergyMcbPv::class, 
            'energy_system_mcb_pvs', 'energy_system_id', 'energy_mcb_pv_id')
        ->withPivot('id', 'cost');
    }

    public function monitoring() {

        return $this->belongsToMany(
            EnergyMonitoring::class, 
            'energy_system_monitorings', 'energy_system_id', 'energy_monitoring_id')
        ->withPivot('id', 'cost');
    }

    public function pvMount() {

        return $this->belongsToMany(
            EnergyPvMount::class, 
            'energy_system_pv_mounts', 'energy_system_id', 'energy_pv_mount_id')
        ->withPivot('id', 'cost');
    }

    public function relayDriver() {

        return $this->belongsToMany(
            EnergyRelayDriver::class, 
            'energy_system_relay_drivers', 'energy_system_id', 'relay_driver_type_id')
        ->withPivot('id', 'cost');
    }

    public function remoteControlCenter() {

        return $this->belongsToMany(
            EnergyRemoteControlCenter::class, 
            'energy_system_remote_control_centers', 'energy_system_id', 'energy_remote_control_center_id')
        ->withPivot('id', 'cost');
    }

    public function windTurbine() {

        return $this->belongsToMany(
            EnergyWindTurbine::class, 
            'energy_system_wind_turbines', 'energy_system_id', 'energy_wind_turbine_id')
        ->withPivot('id', 'cost');
    }
}
