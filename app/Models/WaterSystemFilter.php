<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaterSystemFilter extends Model
{
    use HasFactory;

    protected $fillable = ['water_system_id', 'water_filter_id'];


    public function WaterSystem()
    {
        return $this->belongsTo(WaterSystem::class, 'water_system_id', 'id');
    }

    public function WaterFilter()
    {
        return $this->belongsTo(WaterFilter::class, 'water_filter_id', 'id');
    }

    public function model()
    {
        return $this->belongsTo(WaterFilter::class, 'water_filter_id', 'id');
    }
}
