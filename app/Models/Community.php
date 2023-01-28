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
        'water_service', 'water_service_beginning_year', 'internet_service', 
        'internet_service_beginning_year','grid_access', 'is_archived'];
    

    public function Region()
    {
        return $this->belongsTo(Region::class, 'region_id', 'id');
    }

    public function SubRegion()
    {
        return $this->belongsTo(SubRegion::class, 'sub_region_id', 'id');
    }
}
