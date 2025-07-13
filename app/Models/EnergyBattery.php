<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnergyBattery extends Model
{
    use HasFactory;

    protected $table = 'energy_batteries'; 
    protected $fillable = ['battery_model'];
    
    public function getComponentNameAttribute()
    {
        return $this->battery_model;
    }
}
