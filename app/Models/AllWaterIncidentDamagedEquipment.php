<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllWaterIncidentDamagedEquipment extends Model
{
    use HasFactory;

    public function AllWaterIncident()
    {
        return $this->belongsTo(AllWaterIncident::class, 'all_water_incident_id', 'id');
    }

    public function IncidentEquipment()
    {
        return $this->belongsTo(IncidentEquipment::class, 'incident_equipment_id', 'id');
    }
}
