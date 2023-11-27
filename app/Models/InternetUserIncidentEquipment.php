<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternetUserIncidentEquipment extends Model
{
    use HasFactory;

    protected $fillable = ['incident_equipment_id', 'internet_user_incident_id'];

    public function IncidentEquipment()
    {
        return $this->belongsTo(IncidentEquipment::class, 'incident_equipment_id', 'id');
    }

    public function InternetUserIncident()
    {
        return $this->belongsTo(InternetUserIncident::class, 'internet_user_incident_id', 'id');
    }
}
