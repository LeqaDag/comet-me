<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllIncident extends Model
{
    use HasFactory;

    public function Community()
    {
        return $this->belongsTo(Community::class, 'community_id', 'id');
    }

    public function Incident()
    {
        return $this->belongsTo(Incident::class, 'incident_id', 'id');
    }

    public function ServiceType()
    {
        return $this->belongsTo(ServiceType::class, 'service_type_id', 'id');
    }
 
    public function incidentStatuses()
    {
        return $this->hasMany(AllIncidentOccurredStatus::class, 'all_incident_id');
    }

    public function energyIncident()
    {
        return $this->hasOne(AllEnergyIncident::class, 'all_incident_id', 'id');
    }

    public function waterIncident()
    {
        return $this->hasOne(AllWaterIncident::class, 'all_incident_id', 'id');
    }

    public function internetIncident()
    {
        return $this->hasOne(AllInternetIncident::class, 'all_incident_id', 'id');
    }

}
