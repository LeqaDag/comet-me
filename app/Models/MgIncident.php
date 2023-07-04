<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MgIncident extends Model
{
    use HasFactory;

    protected $fillable = ['community_id', 'energy_system_id', 'incident_id', 
        'incident_status_mg_system_id', 'date'];

    public function Community()
    {
        return $this->belongsTo(Community::class, 'community_id', 'id');
    }

    public function EnergySystem()
    {
        return $this->belongsTo(EnergySystem::class, 'energy_system_id', 'id');
    }

    public function Incident()
    {
        return $this->belongsTo(Incident::class, 'incident_id', 'id');
    }

    public function IncidentStatusMgSystem()
    {
        return $this->belongsTo(IncidentStatusMgSystem::class, 'incident_status_mg_system_id', 'id');
    }
}
