<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaterSystem extends Model
{
    use HasFactory;

    public function Community()
    {
        return $this->belongsTo(Community::class, 'community_id', 'id');
    }

    public function WaterSystemType()
    {
        return $this->belongsTo(WaterSystemType::class, 'water_system_type_id', 'id');
    }

    public function WaterSystemCycle()
    {
        return $this->belongsTo(WaterSystemCycle::class, 'water_system_cycle_id', 'id');
    }
}
