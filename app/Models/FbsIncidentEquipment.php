<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FbsIncidentEquipment extends Model
{
    use HasFactory;

    protected $fillable = ['incident_equipment_id', 'fbs_user_incident_id'];

    public function IncidentEquipment()
    {
        return $this->belongsTo(IncidentEquipment::class, 'incident_equipment_id', 'id');
    }

    public function FbsUserIncident()
    {
        return $this->belongsTo(FbsUserIncident::class, 'fbs_user_incident_id', 'id');
    }
}
