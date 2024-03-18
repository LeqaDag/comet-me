<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisplacedHousehold extends Model
{
    use HasFactory;

    protected $fillable = ['old_community_id', 'household_id', 'new_community_id',
        'old_energy_system_id', 'new_energy_system_id'];

    public function DisplacedHouseholdStatus()
    {
        
        return $this->belongsTo(DisplacedHouseholdStatus::class, 'displaced_household_status_id', 'id');
    }

    public function OldCommunity()
    {
        
        return $this->belongsTo(Community::class, 'old_community_id', 'id');
    } 

    public function NewCommunity()
    {
        
        return $this->belongsTo(Community::class, 'new_community_id', 'id');
    }

    public function Household()
    {
        
        return $this->belongsTo(Household::class, 'household_id', 'id');
    }

    public function OldEnergySystem()
    {
        
        return $this->belongsTo(EnergySystem::class, 'old_energy_system_id', 'id');
    }

    public function NewEnergySystem()
    {
        
        return $this->belongsTo(EnergySystem::class, 'new_energy_system_id', 'id');
    }

    public function SubRegion()
    {
        
        return $this->belongsTo(SubRegion::class, 'sub_region_id', 'id');
    }
}
