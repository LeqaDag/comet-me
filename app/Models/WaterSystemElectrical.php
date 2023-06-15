<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaterSystemElectrical extends Model
{
    use HasFactory;

    protected $fillable = ['water_system_id', 'water_electrical_id'];


    public function WaterSystem()
    {
        return $this->belongsTo(WaterSystem::class, 'water_system_id', 'id');
    }

    public function WaterElectrical()
    {
        return $this->belongsTo(WaterElectrical::class, 'water_electrical_id', 'id');
    }
}
