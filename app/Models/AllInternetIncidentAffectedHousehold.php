<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllInternetIncidentAffectedHousehold extends Model
{
    use HasFactory;

    public function AllInternetIncident()
    {
        return $this->belongsTo(AllInternetIncident::class, 'all_internet_incident_id', 'id');
    }

    public function Household()
    {
        return $this->belongsTo(Household::class, 'household_id', 'id');
    }
}
