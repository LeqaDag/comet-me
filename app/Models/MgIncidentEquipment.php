<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MgIncidentEquipment extends Model
{
    use HasFactory;

    protected $fillable = ['incident_equipment_id', 'mg_incident_id'];

    public function IncidentEquipment()
    {
        return $this->belongsTo(IncidentEquipment::class, 'incident_equipment_id', 'id');
    }

    public function MgIncident()
    {
        return $this->belongsTo(MgIncident::class, 'mg_incident_id', 'id');
    }
}
