<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnergySystemPv extends Model
{
    use HasFactory;

    public function EnergyPv()
    {
        return $this->belongsTo(EnergyPv::class, 'pv_type_id', 'id');
    }

    public function energySystem()
    {
        return $this->belongsTo(EnergySystem::class, 'energy_system_id');
    }

    public function model()
    {
        return $this->belongsTo(EnergyPv::class, 'pv_type_id');
    }
}
