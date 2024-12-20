<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ElectricityMaintenanceCall extends Model
{
    use HasFactory;

    protected $fillable = ['community_id', 'maintenance_type_id', 'maintenance_status_id', 
        'maintenance_electricity_action_id', 'date_of_call', 'date_completed', 'user_id'];

    public function Community()
    {
        return $this->belongsTo(Community::class, 'community_id', 'id');
    }
 
    public function User()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function EnergySystem()
    {
        return $this->belongsTo(EnergySystem::class, 'energy_system_id', 'id');
    }

    public function EnergyTurbineCommunity()
    {
        return $this->belongsTo(EnergyTurbineCommunity::class, 'energy_turbine_community_id', 'id');
    }

    public function EnergyGeneratorCommunity()
    {
        return $this->belongsTo(EnergyGeneratorCommunity::class, 'energy_generator_community_id', 'id');
    }

    public function MaintenanceType()
    {
        return $this->belongsTo(MaintenanceType::class, 'maintenance_type_id', 'id');
    }

    public function MaintenanceStatus()
    {
        return $this->belongsTo(MaintenanceStatus::class, 'maintenance_status_id', 'id');
    }

    public function MaintenanceElectricityAction()
    {
        return $this->belongsTo(MaintenanceElectricityAction::class, 'maintenance_electricity_action_id', 'id');
    }

    public function Household()
    {
        return $this->belongsTo(Household::class, 'household_id', 'id');
    }

    public function AllEnergyMeter()
    {
        return $this->belongsTo(AllEnergyMeter::class, 'energy_user_id', 'id');
    }

    public function PublicStructure()
    {
        return $this->belongsTo(PublicStructure::class, 'public_structure_id', 'id');
    }
}
