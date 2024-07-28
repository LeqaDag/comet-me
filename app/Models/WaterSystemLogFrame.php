<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaterSystemLogFrame extends Model
{
    use HasFactory;

    public function WaterSystem()
    {
        return $this->belongsTo(WaterSystem::class, 'water_system_id', 'id');
    }
}
