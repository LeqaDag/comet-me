<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FbsIncidentStatus extends Model
{
    use HasFactory;

    protected $fillable = ['fbs_user_incident_id', 
        'incident_status_small_infrastructure_id'];

    public function FbsUserIncident()
    {
        return $this->belongsTo(FbsUserIncident::class, 'fbs_user_incident_id', 'id');
    }

    public function IncidentStatusSmallInfrastructure()
    {
        return $this->belongsTo(IncidentStatusSmallInfrastructure::class, 
            'incident_status_small_infrastructure_id', 'id');
    }
} 
