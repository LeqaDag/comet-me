<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecommendedCommunityEnergySystem extends Model
{
    use HasFactory;

    protected $fillable = ['energy_system_type_id', 'community_id'];

    public function Community()
    {
        return $this->belongsTo(Community::class, 'community_id', 'id');
    }

    public function EnergySystemType()
    {
        return $this->belongsTo(EnergySystemType::class, 'energy_system_type_id', 'id');
    }
}
