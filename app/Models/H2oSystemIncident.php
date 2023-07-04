<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class H2oSystemIncident extends Model
{
    use HasFactory;

    protected $fillable = ['community_id', 'all_water_holder_id', 'incident_id', 
        'incident_status_id', 'date'];

    public function Community()
    {
        return $this->belongsTo(Community::class, 'community_id', 'id');
    }

    public function AllWaterHolder()
    {
        return $this->belongsTo(AllWaterHolder::class, 'all_water_holder_id', 'id');
    }

    public function Incident()
    {
        return $this->belongsTo(Incident::class, 'incident_id', 'id');
    }

    public function IncidentStatus()
    {
        return $this->belongsTo(IncidentStatus::class, 'incident_status_id', 'id');
    }
}
