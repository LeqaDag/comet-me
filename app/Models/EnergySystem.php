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

    public function batteries() {

        return $this->hasMany(EnergySystemBattery::class, 'energy_system_id');
    }

    public function pvs() {

        return $this->hasMany(EnergySystemPv::class, 'energy_system_id');
    }

    public function airConditioners() {

        return $this->hasMany(EnergySystemAirConditioner::class, 'energy_system_id');
    }

    public function batteryMount() {

        return $this->hasMany(EnergySystemBatteryMount::class, 'energy_system_id');
    }

    public function bsp() {

        return $this->hasMany(EnergySystemBatteryStatusProcessor::class, 'energy_system_id');
    }

    public function bts() {

        return $this->hasMany(EnergySystemBatteryTemperatureSensor::class, 'energy_system_id');
    }

    public function chargeController() {

        return $this->hasMany(EnergySystemChargeController::class, 'energy_system_id');
    }

    public function generator() {

        return $this->hasMany(EnergySystemGenerator::class, 'energy_system_id');
    }

    public function inverter() {

        return $this->hasMany(EnergySystemInverter::class, 'energy_system_id');
    }

    public function loadRelay() {

        return $this->hasMany(EnergySystemLoadRelay::class, 'energy_system_id');
    }

    public function mcbChargeController() {

        return $this->hasMany(EnergySystemMcbChargeController::class, 'energy_system_id');
    }

    public function mcbInverter() {

        return $this->hasMany(EnergySystemMcbInverter::class, 'energy_system_id');
    }

    public function mcbPv() {

        return $this->hasMany(EnergySystemMcbPv::class, 'energy_system_id');
    }

    public function monitoring() {

        return $this->hasMany(EnergySystemMonitoring::class, 'energy_system_id');
    }

    public function pvMount() {

        return $this->hasMany(EnergySystemPvMount::class, 'energy_system_id');
    }

    public function relayDriver() {

        return $this->hasMany(EnergySystemRelayDriver::class, 'energy_system_id');
    }

    public function remoteControlCenter() {

        return $this->hasMany(EnergySystemRemoteControlCenter::class, 'energy_system_id');
    }

    public function windTurbine() {

        return $this->hasMany(EnergySystemWindTurbine::class, 'energy_system_id');
    }
}
