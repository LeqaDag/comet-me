<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Community extends Model
{
    use HasFactory;

    protected $fillable = ['english_name', 'arabic_name', 'region_id', 'is_bedouin', 
        'is_fallah', 'number_of_compound', 'number_of_people', 'number_of_households', 
        'sub_region_id', 'location_gis', 'energy_service', 'energy_service_beginning_year',
        'water_service', 'water_service_beginning_year', 'internet_service', 'product_type_id',
        'internet_service_beginning_year', 'grid_access', 'is_archived', 'community_status_id'];
    

    public function Region()  
    {
        return $this->belongsTo(Region::class, 'region_id', 'id');
    }

    public function SubRegion()
    {
        return $this->belongsTo(SubRegion::class, 'sub_region_id', 'id');
    }

    public function ProductType()
    {
        return $this->belongsTo(ProductType::class, 'product_type_id', 'id');
    }

    public function CommunityStatus()
    {
        return $this->belongsTo(CommunityStatus::class, 'community_status_id', 'id');
    }

    public function EnergySystemCycle()
    {
        return $this->belongsTo(EnergySystemCycle::class, 'energy_system_cycle_id', 'id');
    }

    public function communityServices() {

        return $this->hasMany(communityServices::class);
    }

    public function KindergartenTown()
    {
        return $this->belongsTo('App\Models\Town', 'kindergarten_town_id', 'id');
    }

    public function SchoolTown()
    {
        return $this->belongsTo('App\Models\Town', 'school_town_id', 'id');
    }

    public function displacedHouseholds()
    {
        return $this->hasMany(DisplacedHousehold::class, 'old_community_id');
    }

    public function households()
    {
        return $this->hasMany(Household::class);
    }
}
