<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; 
use Illuminate\Database\Eloquent\Model;

class InternetNetworkAffectedArea extends Model
{
    use HasFactory;

    protected $fillable = ['affected_community_id', 'internet_network_incident_id'];

    public function Community()
    {
        return $this->belongsTo(Community::class, 'affected_community_id', 'id');
    }

    public function InternetNetworkIncident()
    {
        return $this->belongsTo(InternetNetworkIncident::class, 'internet_network_incident_id', 'id');
    }
}
