<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaterSystemConnector extends Model
{
    use HasFactory;

    protected $fillable = ['water_system_id', 'water_connector_id'];


    public function WaterSystem()
    {
        return $this->belongsTo(WaterSystem::class, 'water_system_id', 'id');
    }

    public function WaterConnector()
    {
        return $this->belongsTo(WaterConnector::class, 'water_connector_id', 'id');
    }

    public function model()
    {
        return $this->belongsTo(WaterConnector::class, 'water_connector_id', 'id');
    }
}
