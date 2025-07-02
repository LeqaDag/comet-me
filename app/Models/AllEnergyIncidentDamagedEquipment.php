<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllEnergyIncidentDamagedEquipment extends Model
{
    use HasFactory;

    public function AllEnergyIncident()
    {
        return $this->belongsTo(AllEnergyIncident::class, 'all_energy_incident_id', 'id');
    }

    public function IncidentEquipment()
    {
        return $this->belongsTo(IncidentEquipment::class, 'incident_equipment_id', 'id');
    }
}
