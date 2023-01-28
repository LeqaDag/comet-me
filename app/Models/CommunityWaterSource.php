<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommunityWaterSource extends Model
{
    use HasFactory;

    protected $fillable = ['community_id ', 'water_source_id'];


    public function Community()
    {
        
        return $this->belongsTo(Community::class, 'community_id', 'id');
    }

    public function WaterSource()
    {
        
        return $this->belongsTo(WaterSource::class, 'water_source_id', 'id');
    }
}
