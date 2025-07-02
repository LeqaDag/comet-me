<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllWaterIncidentPhoto extends Model
{
    use HasFactory;

    public function AllWaterIncident()
    {
        return $this->belongsTo(AllWaterIncident::class, 'all_water_incident_id', 'id');
    }
}
