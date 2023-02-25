<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceActionType extends Model
{
    use HasFactory;

    protected $fillable = ['maintenance_action_h2o', 'maintenance_action_h2o_english'];
}
