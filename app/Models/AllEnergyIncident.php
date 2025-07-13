<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllEnergyIncident extends Model
{
    use HasFactory;

    public function AllIncident() 
    {
        return $this->belongsTo(AllIncident::class, 'all_incident_id', 'id');
    }

    public function EnergySystem()
    {
        return $this->belongsTo(EnergySystem::class, 'energy_system_id', 'id');
    }

    public function AllEnergyMeter()
    {
        return $this->belongsTo(AllEnergyMeter::class, 'all_energy_meter_id', 'id');
    }

    public function affectedHouseholds()
    {
        return $this->hasMany(AllEnergyIncidentAffectedHousehold::class, 'all_energy_incident_id');
    }

    public function equipmentDamaged()
    {
        return $this->hasMany(AllEnergyIncidentDamagedEquipment::class, 'all_energy_incident_id');
    } 

    public function photos()
    {
        return $this->hasMany(AllEnergyIncidentPhoto::class, 'all_energy_incident_id');
    }

    public function damagedSystemEquipments()
    {
        return $this->hasMany(AllEnergyIncidentSystemDamagedEquipment::class, 'all_energy_incident_id');
    }
}