<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FbsUserIncident extends Model
{
    use HasFactory;

    protected $fillable = ['community_id', 'energy_user_id', 'incident_id', 
        'public_structure_id', 'date'];

    public function Community()
    {
        return $this->belongsTo(Community::class, 'community_id', 'id');
    }

    public function AllEnergyMeter()
    {
        return $this->belongsTo(AllEnergyMeter::class, 'energy_user_id', 'id');
    }

    public function Incident()
    {
        return $this->belongsTo(Incident::class, 'incident_id', 'id');
    }

    public function PublicStructure()
    {
        
        return $this->belongsTo(PublicStructure::class, 'public_structure_id', 'id');
    }
    
    public function IncidentStatusSmallInfrastructure()
    {
        return $this->belongsTo(IncidentStatusSmallInfrastructure::class, 
            'incident_status_small_infrastructure_id', 'id');
    }
}
