<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnergySystemBattery extends Model
{
    use HasFactory;

    public function energySystem()
    {
        return $this->belongsTo(EnergySystem::class, 'energy_system_id');
    }

    public function model() 
    {
        return $this->belongsTo(EnergyBattery::class, 'battery_type_id');
    }
}
