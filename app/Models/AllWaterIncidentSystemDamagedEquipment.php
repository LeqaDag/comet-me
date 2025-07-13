<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllWaterIncidentSystemDamagedEquipment extends Model
{
    use HasFactory; 

    public function tank()
    {
        return $this->belongsTo(WaterSystemTank::class, 'water_system_tank_id');
    }

    public function tap()
    {
        return $this->belongsTo(WaterSystemTap::class, 'water_system_tap_id');
    }

    public function filter()
    {
        return $this->belongsTo(WaterSystemFilter::class, 'water_system_filter_id');
    }

    public function connector()
    {
        return $this->belongsTo(WaterSystemConnector::class, 'water_system_connector_id');
    }

    public function pipe()
    {
        return $this->belongsTo(WaterSystemPipe::class, 'water_system_pipe_id');
    }

    public function pump()
    {
        return $this->belongsTo(WaterSystemPump::class, 'water_system_pump_id');
    }

    public function valve()
    {
        return $this->belongsTo(WaterSystemValve::class, 'water_system_valve_id');
    }
}
