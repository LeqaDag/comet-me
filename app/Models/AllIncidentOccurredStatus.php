<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllIncidentOccurredStatus extends Model
{
    use HasFactory;

    public function AllIncident()
    {
        return $this->belongsTo(AllIncident::class, 'all_incident_id', 'id');
    }

    public function AllIncidentStatus()
    {
        return $this->belongsTo(AllIncidentStatus::class, 'all_incident_status_id', 'id');
    }
}
