<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternetNetworkIncidentEquipment extends Model
{
    use HasFactory;

    protected $fillable = ['incident_equipment_id', 'internet_network_incident_id'];

    public function IncidentEquipment()
    {
        return $this->belongsTo(IncidentEquipment::class, 'incident_equipment_id', 'id');
    }

    public function InternetNetworkIncident()
    {
        return $this->belongsTo(InternetNetworkIncident::class, 'internet_network_incident_id', 'id');
    }
}
