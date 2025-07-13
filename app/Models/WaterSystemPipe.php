<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaterSystemPipe extends Model
{
    use HasFactory;

    protected $fillable = ['water_system_id', 'water_pipe_id'];


    public function WaterSystem()
    {
        return $this->belongsTo(WaterSystem::class, 'water_system_id', 'id');
    }

    public function WaterPipe()
    {
        return $this->belongsTo(WaterPipe::class, 'water_pipe_id', 'id');
    }

    public function model()
    {
        return $this->belongsTo(WaterPipe::class, 'water_pipe_id', 'id');
    }
}
