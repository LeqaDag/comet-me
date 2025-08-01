<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HouseholdMeter extends Model
{
    use HasFactory;

    public function mainEnergyMeter()
    {
        return $this->belongsTo(AllEnergyMeter::class, 'energy_user_id');
    }
}