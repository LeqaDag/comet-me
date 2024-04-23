<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllEnergyMeter extends Model
{
    use HasFactory;

    protected $fillable = ['household_id', 'meter_case_id', 'energy_system_id', 'installation_type_id'];

    public function Community()
    {
        return $this->belongsTo(Community::class, 'community_id', 'id');
    }

    public function Household()
    {
        return $this->belongsTo(Household::class, 'household_id', 'id');
    }

    public function MeterCase()
    {
        return $this->belongsTo(MeterCase::class, 'meter_case_id', 'id');
    }

    public function EnergySystem()
    {
        return $this->belongsTo(EnergySystem::class, 'energy_system_id', 'id');
    }

    public function PublicStructure()
    {
        
        return $this->belongsTo(PublicStructure::class, 'public_structure_id', 'id');
    }

    public function InstallationType()
    {
        
        return $this->belongsTo(InstallationType::class, 'installation_type_id', 'id');
    }

    public function EnergySystemCycle()
    {
        return $this->belongsTo(EnergySystemCycle::class, 'energy_system_cycle_id', 'id');
    }
}
