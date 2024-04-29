<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CameraIncident extends Model
{
    use HasFactory;

    public function Community()
    {
        
        return $this->belongsTo(Community::class, 'community_id', 'id');
    }

    public function Repository()
    {
        
        return $this->belongsTo(Repository::class, 'repository_id', 'id');
    }

    public function Incident()
    {
        return $this->belongsTo(Incident::class, 'incident_id', 'id');
    }

    public function InternetIncidentStatus()
    {
        return $this->belongsTo(InternetIncidentStatus::class, 
            'internet_incident_status_id', 'id');
    } 
}
