<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnergySystemAirConditioner extends Model
{
    use HasFactory;

    public function energySystem()
    {
        return $this->belongsTo(EnergySystem::class, 'energy_system_id');
    }

    public function model()
    {
        return $this->belongsTo(EnergyAirConditioner::class, 'energy_air_conditioner_id');
    }
}
