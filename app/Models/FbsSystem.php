<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FbsSystem extends Model
{
    use HasFactory;

    public function EnergySystem()
    {
        return $this->belongsTo(EnergySystem::class, 'energy_system_id', 'id');
    }

    public function EnergyBattery()
    {
        return $this->belongsTo(EnergyBattery::class, 'battery_type_id', 'id');
    }

    public function EnergyChargeController()
    {
        return $this->belongsTo(EnergyChargeController::class, 'charge_controller_type_id', 'id');
    }

    public function EnergyMcbChargeController()
    {
        return $this->belongsTo(EnergyMcbChargeController::class, 'charge_controller_mcb_type_id', 'id');
    }

    public function EnergyPv()
    {
        return $this->belongsTo(EnergyPv::class, 'solar_panel_type_id', 'id');
    }

    public function EnergyInverter()
    {
        return $this->belongsTo(EnergyInverter::class, 'invertor_type_id', 'id');
    }

    public function EnergyRelayDriver()
    {
        return $this->belongsTo(EnergyRelayDriver::class, 'relay_driver_type_id', 'id');
    }

    public function EnergyLoadRelay()
    {
        return $this->belongsTo(EnergyLoadRelay::class, 'load_relay_id', 'id');
    }

    public function EnergyBatteryStatusProcessor()
    {
        return $this->belongsTo(EnergyBatteryStatusProcessor::class, 'bsp_type_id', 'id');
    }

    public function EnergyMcbInverter()
    {
        return $this->belongsTo(EnergyMcbInverter::class, 'invertor_mcb_type_id', 'id');
    }

    public function EnergyMonitoring()
    {
        return $this->belongsTo(EnergyMonitoring::class, 'logger_type_id', 'id');
    }

    public function EnergyMcbPv()
    {
        return $this->belongsTo(EnergyMcbPv::class, 'pv_mcb_type_id', 'id');
    }
}
