<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaterSystemPump extends Model
{
    use HasFactory;

    protected $fillable = ['water_system_id', 'water_pump_id'];


    public function WaterSystem()
    {
        return $this->belongsTo(WaterSystem::class, 'water_system_id', 'id');
    }

    public function WaterPump()
    {
        return $this->belongsTo(WaterPump::class, 'water_pump_id', 'id');
    }

    public function model()
    {
        return $this->belongsTo(WaterPump::class, 'water_pump_id', 'id');
    }
}
