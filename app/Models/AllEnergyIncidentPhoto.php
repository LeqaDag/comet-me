<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllEnergyIncidentPhoto extends Model
{
    use HasFactory;

    public function AllEnergyIncident()
    {
        return $this->belongsTo(AllEnergyIncident::class, 'all_energy_incident_id', 'id');
    }
}
