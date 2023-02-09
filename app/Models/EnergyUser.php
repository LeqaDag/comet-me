<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnergyUser extends Model
{
    use HasFactory;

    protected $fillable = ['community_id', 'household_id', 'meter_number', 'energy_system_id',
        'energy_system_type_id', 'meter_case_id', 'meter_active'];

    public function Community()
    {
        return $this->belongsTo(Community::class, 'community_id', 'id');
    }

    public function Household()
    {
        return $this->belongsTo(Household::class, 'household_id', 'id');
    }

    public function EnergySystemType()
    {
        return $this->belongsTo(EnergySystemType::class, 'energy_system_type_id', 'id');
    }

    public function EnergySystem()
    {
        return $this->belongsTo(EnergySystem::class, 'energy_system_id', 'id');
    }

    public function MeterCase()
    {
        return $this->belongsTo(MeterCase::class, 'meter_case_id', 'id');
    }
}
