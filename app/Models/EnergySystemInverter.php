<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnergySystemInverter extends Model
{
    use HasFactory;

    public function energySystem()
    {
        return $this->belongsTo(EnergySystem::class, 'energy_system_id');
    }

    public function model()
    {
        return $this->belongsTo(EnergyInverter::class, 'energy_inverter_id');
    }
}
