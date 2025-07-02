<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllWaterIncidentAffectedHousehold extends Model
{
    use HasFactory;

    public function AllWaterIncident()
    {
        return $this->belongsTo(AllWaterIncident::class, 'all_water_incident_id', 'id');
    }

    public function Household()
    {
        return $this->belongsTo(Household::class, 'household_id', 'id');
    }
}
