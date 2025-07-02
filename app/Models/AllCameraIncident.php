<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllCameraIncident extends Model
{
    use HasFactory;

    public function AllIncident()
    {
        return $this->belongsTo(AllIncident::class, 'all_incident_id', 'id');
    }

    public function Community()
    {
        return $this->belongsTo(Community::class, 'community_id', 'id');
    }

    public function Repository()
    {
        return $this->belongsTo(Repository::class, 'repository_id', 'id');
    }

    public function CameraInstallation()
    {
        return $this->hasOne(CameraCommunity::class, 'community_id', 'community_id'); 
    }

    public function equipmentDamaged()
    {
        return $this->hasMany(AllCameraIncidentDamagedEquipment::class, 'all_camera_incident_id');
    }

    public function photos()
    {
        return $this->hasMany(AllCameraIncidentPhoto::class, 'all_camera_incident_id');
    }
}
