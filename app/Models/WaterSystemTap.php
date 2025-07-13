<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaterSystemTap extends Model
{
    use HasFactory;
    
    public function WaterSystem()
    {
        return $this->belongsTo(WaterSystem::class, 'water_system_id', 'id');
    }

    public function model()
    {
        return $this->belongsTo(WaterTap::class, 'water_tap_id', 'id');
    }
}
