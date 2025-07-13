<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ControllerInternetSystem extends Model
{
    use HasFactory; 

    public function InternetSystem()
    {
        return $this->belongsTo(InternetSystem::class, 'internet_system_id', 'id');
    }

    public function model()
    {
        return $this->belongsTo(InternetController::class, 'internet_controller_id', 'id');
    }
}
