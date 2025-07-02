<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CameraCommunity extends Model
{
    use HasFactory;

    protected $fillable = ['community_id', 'household_id', 'repository_id'];


    public function Community()
    {
        
        return $this->belongsTo(Community::class, 'community_id', 'id');
    }

    public function Household()
    {
        
        return $this->belongsTo(Household::class, 'household_id', 'id');
    }

    public function Repository()
    {
        
        return $this->belongsTo(Repository::class, 'repository_id', 'id');
    }

    public function cameraCommunityTypes()
    {
        return $this->hasMany(CameraCommunityType::class, 'camera_community_id');
    }

    public function getTotalCamerasAttribute()
    {
        return $this->cameraCommunityTypes->sum('number');
    }
}
