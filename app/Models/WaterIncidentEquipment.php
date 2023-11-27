<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaterIncidentEquipment extends Model
{
    use HasFactory;

    protected $fillable = ['incident_equipment_id', 'h2o_system_incident_id'];

    public function IncidentEquipment()
    {
        return $this->belongsTo(IncidentEquipment::class, 'incident_equipment_id', 'id');
    }

    public function H2oSystemIncident()
    {
        return $this->belongsTo(H2oSystemIncident::class, 'h2o_system_incident_id', 'id');
    }
}
