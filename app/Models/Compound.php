<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compound extends Model
{
    use HasFactory;

    protected $fillable = ['english_name', 'arabic_name', 'community_id'];
    

    public function Community()
    {
        return $this->belongsTo(Community::class, 'community_id', 'id');
    }

    public function KindergartenTown()
    {
        return $this->belongsTo('App\Models\Town', 'kindergarten_town_id', 'id');
    }

    public function SchoolTown()
    {
        return $this->belongsTo('App\Models\Town', 'school_town_id', 'id');
    }

    public function CommunityStatus()
    {
        return $this->belongsTo(CommunityStatus::class, 'community_status_id', 'id');
    }

    public function EnergySystemCycle()
    {
        return $this->belongsTo(EnergySystemCycle::class, 'energy_system_cycle_id', 'id');
    }
}