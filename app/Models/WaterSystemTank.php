<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaterSystemTank extends Model
{
    use HasFactory;

    protected $fillable = ['water_system_id', 'water_tank_id'];


    public function WaterSystem()
    {
        return $this->belongsTo(WaterSystem::class, 'water_system_id', 'id');
    }

    public function WaterTank()
    {
        return $this->belongsTo(WaterTank::class, 'water_tank_id', 'id');
    }

    public function model()
    {
        return $this->belongsTo(WaterTank::class, 'water_tank_id', 'id');
    }
}
