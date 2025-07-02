<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllInternetIncidentPhoto extends Model
{
    use HasFactory;

    public function AllInternetIncident()
    {
        return $this->belongsTo(AllInternetIncident::class, 'all_internet_incident_id', 'id');
    }
}
