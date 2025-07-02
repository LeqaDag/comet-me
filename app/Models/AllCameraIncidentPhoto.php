<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllCameraIncidentPhoto extends Model
{
    use HasFactory;

    public function AllCameraIncident()
    {
        return $this->belongsTo(AllCameraIncident::class, 'all_camera_incident_id', 'id');
    }
}
