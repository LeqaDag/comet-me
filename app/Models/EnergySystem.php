<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnergySystem extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'energy_system_type_id', 'installation_year'];


    public function EnergySystemType()
    {
        
        return $this->belongsTo(EnergySystemType::class, 'energy_system_type_id', 'id');
    }
}
