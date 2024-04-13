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
}
