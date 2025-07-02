<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllInternetIncidentDamagedEquipment extends Model
{
    use HasFactory;

    public function AllInternetIncident()
    {
        return $this->belongsTo(AllInternetIncident::class, 'all_internet_incident_id', 'id');
    }

    public function IncidentEquipment()
    {
        return $this->belongsTo(IncidentEquipment::class, 'incident_equipment_id', 'id');
    }
}
