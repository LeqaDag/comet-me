<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternetNetworkAffectedHousehold extends Model
{
    use HasFactory;

    protected $fillable = ['household_id', 'internet_network_incident_id'];

    public function Household()
    {
        return $this->belongsTo(Household::class, 'household_id', 'id');
    }

    public function InternetNetworkIncident()
    {
        return $this->belongsTo(InternetNetworkIncident::class, 'internet_network_incident_id', 'id');
    }
}
