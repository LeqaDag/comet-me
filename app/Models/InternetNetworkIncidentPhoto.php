<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternetNetworkIncidentPhoto extends Model
{
    use HasFactory;

    protected $fillable = ['internet_network_incident_id'];

    public function InternetNetworkIncident()
    {
        return $this->belongsTo(InternetNetworkIncident::class, 'internet_network_incident_id', 'id');
    }
}
