<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnergyPublicStructure extends Model
{
    use HasFactory;

    protected $fillable = ['community_id ', 'public_structure_id', 'meter_case_id', 
        'energy_system_id'];

    public function Community()
    {
        
        return $this->belongsTo(Community::class, 'community_id', 'id');
    }

    public function PublicStructure()
    {
        
        return $this->belongsTo(PublicStructure::class, 'public_structure_id', 'id');
    }

    public function MeterCase()
    {
        return $this->belongsTo(MeterCase::class, 'meter_case_id', 'id');
    }

    public function EnergySystem()
    {
        return $this->belongsTo(EnergySystem::class, 'energy_system_id', 'id');
    }
}