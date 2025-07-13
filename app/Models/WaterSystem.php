<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaterSystem extends Model
{
    use HasFactory;

    public function Community()
    {
        return $this->belongsTo(Community::class, 'community_id', 'id');
    }

    public function WaterSystemType()
    {
        return $this->belongsTo(WaterSystemType::class, 'water_system_type_id', 'id');
    }

    public function WaterSystemCycle()
    {
        return $this->belongsTo(WaterSystemCycle::class, 'water_system_cycle_id', 'id');
    }

    public function waterTankSystem()
    {
        return $this->hasMany(WaterSystemTank::class, 'water_system_id');
    }

    public function tanks()
    {
        return $this->belongsToMany(
            WaterTank::class, 
            'water_system_tanks', 'water_system_id', 'water_tank_id')
        ->withPivot('id', 'tank_costs');
    }

    public function pipes()
    {
        return $this->belongsToMany(
            WaterPipe::class, 
            'water_system_pipes', 'water_system_id', 'water_pipe_id')
        ->withPivot('id', 'pipe_costs');
    }

    public function pumps()
    {
        return $this->belongsToMany(
            WaterPump::class, 
            'water_system_pumps', 'water_system_id', 'water_pump_id')
        ->withPivot('id', 'pump_costs');
    }

    public function filters()
    {
        return $this->belongsToMany(
            WaterFilter::class, 
            'water_system_filters', 'water_system_id', 'water_filter_id')
        ->withPivot('id', 'filter_costs');
    }

    public function connectors()
    {
        return $this->belongsToMany(
            WaterConnector::class, 
            'water_system_connectors', 'water_system_id', 'water_connector_id')
        ->withPivot('id', 'connector_costs');
    }

    public function valves()
    {
        return $this->belongsToMany(
            WaterValve::class, 
            'water_system_valves', 'water_system_id', 'water_valve_id')
        ->withPivot('id', 'valve_costs');
    }

    public function taps()
    {
        return $this->belongsToMany(
            WaterTap::class, 
            'water_system_taps', 'water_system_id', 'water_tap_id')
        ->withPivot('id', 'tap_costs');
    }
}
