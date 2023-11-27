<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternetUserIncident extends Model
{
    use HasFactory;

    protected $fillable = ['community_id', 'internet_user_id', 'incident_id', 
        'internet_incident_status_id', 'date'];

    public function Community()
    {
        return $this->belongsTo(Community::class, 'community_id', 'id');
    }

    public function InternetUser()
    {
        return $this->belongsTo(InternetUser::class, 'internet_user_id', 'id');
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
