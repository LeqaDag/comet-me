<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllInternetIncident extends Model
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

    public function InternetSystemCommunity()
    {
        return $this->hasOne(InternetSystemCommunity::class, 'community_id', 'community_id');
    }

    public function InternetUser()
    {
        return $this->belongsTo(InternetUser::class, 'internet_user_id', 'id');
    }

    public function affectedAreas()
    {
        return $this->hasMany(AllInternetIncidentAffectedArea::class, 'all_internet_incident_id');
    }

    public function affectedHouseholds()
    {
        return $this->hasMany(AllInternetIncidentAffectedHousehold::class, 'all_internet_incident_id');
    }

    public function equipmentDamaged()
    {
        return $this->hasMany(AllInternetIncidentDamagedEquipment::class, 'all_internet_incident_id');
    }

    public function photos()
    {
        return $this->hasMany(AllInternetIncidentPhoto::class, 'all_internet_incident_id');
    }

    public function damagedSystemEquipments()
    {
        return $this->hasMany(AllInternetIncidentSystemDamagedEquipment::class, 'all_internet_incident_id');
    }
}
