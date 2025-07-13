<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllWaterIncident extends Model
{
    use HasFactory;

    public function AllWaterIncident()
    {
        return $this->belongsTo(AllWaterIncident::class, 'all_water_incident_id', 'id');
    }

    public function WaterSystem()
    {
        return $this->belongsTo(WaterSystem::class, 'water_system_id', 'id');
    }

    public function AllWaterHolder()
    {
        return $this->belongsTo(AllWaterHolder::class, 'all_water_holder_id', 'id');
    }

    public function affectedHouseholds()
    {
        return $this->hasMany(AllWaterIncidentAffectedHousehold::class, 'all_water_incident_id');
    }

    public function equipmentDamaged()
    {
        return $this->hasMany(AllWaterIncidentDamagedEquipment::class, 'all_water_incident_id');
    }

    public function photos()
    {
        return $this->hasMany(AllWaterIncidentPhoto::class, 'all_water_incident_id');
    }

    public function damagedSystemEquipments()
    {
        return $this->hasMany(AllWaterIncidentSystemDamagedEquipment::class, 'all_water_incident_id');
    }
}
