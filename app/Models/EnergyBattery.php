<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnergyBattery extends Model
{
    use HasFactory;

    protected $fillable = ['battery_model'];
}
