<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllEnergyIncidentSystemDamagedEquipment extends Model
{
    use HasFactory;

    public function batteryMount()
    {
        return $this->belongsTo(EnergySystemBatteryMount::class, 'energy_system_battery_mount_id');
    }

    public function pv()
    {
        return $this->belongsTo(EnergySystemPv::class, 'energy_system_pv_id');
    }

    public function inverter()
    {
        return $this->belongsTo(EnergySystemInverter::class, 'energy_system_inverter_id');
    }

    public function battery()
    {
        return $this->belongsTo(EnergySystemBattery::class, 'energy_system_battery_id');
    }

    public function batteryStatusProcessor()
    {
        return $this->belongsTo(EnergySystemBatteryStatusProcessor::class, 'energy_system_battery_status_processor_id');
    }

    public function batteryTemperatureSensor()
    {
        return $this->belongsTo(EnergySystemBatteryTemperatureSensor::class, 'energy_system_battery_temperature_sensor_id');
    }

    public function chargeController()
    {
        return $this->belongsTo(EnergySystemChargeController::class, 'energy_system_charge_controller_id');
    }

    public function generator()
    {
        return $this->belongsTo(EnergySystemGenerator::class, 'energy_system_generator_id');
    }

    public function monitoring()
    {
        return $this->belongsTo(EnergySystemMonitoring::class, 'energy_system_monitoring_id');
    }

    public function loadRelay()
    {
        return $this->belongsTo(EnergySystemLoadRelay::class, 'energy_system_load_relay_id');
    }

    public function mcbChargeController()
    {
        return $this->belongsTo(EnergySystemMcbChargeController::class, 'energy_system_mcb_charge_controller_id');
    }

    public function mcbInverter()
    {
        return $this->belongsTo(EnergySystemMcbInverter::class, 'energy_system_mcb_inverter_id');
    }

    public function mcbPv()
    {
        return $this->belongsTo(EnergySystemMcbPv::class, 'energy_system_mcb_pv_id');
    }

    public function pvMount()
    {
        return $this->belongsTo(EnergySystemPvMount::class, 'energy_system_pv_mount_id');
    }

    public function relayDriver()
    {
        return $this->belongsTo(EnergySystemRelayDriver::class, 'energy_system_relay_driver_id');
    }

    public function remoteControlCenter()
    {
        return $this->belongsTo(EnergySystemRemoteControlCenter::class, 'energy_system_remote_control_center_id');
    }

    public function windTurbine()
    {
        return $this->belongsTo(EnergySystemWindTurbine::class, 'energy_system_wind_turbine_id');
    }

    public function airConditioner()
    {
        return $this->belongsTo(EnergySystemAirConditioner::class, 'energy_system_air_conditioner_id');
    }
}
