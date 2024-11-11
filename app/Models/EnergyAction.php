<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnergyAction extends Model
{
    use HasFactory;

    public function EnergyActionCategory()
    {
        return $this->belongsTo(EnergyActionCategory::class, 'energy_action_category_id', 'id');
    }
}
