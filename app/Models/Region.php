<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use HasFactory;

    protected $fillable = ['english_name', 'arabic_name'];

    public function subRegions()
    {
        return $this->hasMany(SubRegion::class);
    }
    
    public function communities()
    {
        return $this->hasManyThrough(Community::class, SubRegion::class, 'region_id', 'sub_region_id');
    }
    
    public function cameraCommunities()
    {
        return $this->hasManyThrough(CameraCommunity::class, Community::class, 'sub_region_id', 'community_id');
    }
}
