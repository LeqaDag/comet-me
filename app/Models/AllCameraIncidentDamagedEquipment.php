<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllCameraIncidentDamagedEquipment extends Model
{
    use HasFactory;

    public function AllCameraIncident()
    {
        return $this->belongsTo(AllCameraIncident::class, 'all_camera_incident_id', 'id');
    }

    public function IncidentEquipment()
    {
        return $this->belongsTo(IncidentEquipment::class, 'incident_equipment_id', 'id');
    }
}
