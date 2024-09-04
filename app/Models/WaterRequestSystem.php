<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaterRequestSystem extends Model
{
    use HasFactory;

    public function Community() 
    {
        return $this->belongsTo(Community::class, 'community_id', 'id');
    }

    public function Household() 
    {
        return $this->belongsTo(Household::class, 'household_id', 'id');
    }

    public function PublicStructure() 
    {
        return $this->belongsTo(PublicStructure::class, 'public_structure_id', 'id');
    }

    public function WaterRequestStatus() 
    {
        return $this->belongsTo(WaterRequestStatus::class, 'water_request_status_id', 'id');
    }

    public function WaterSystemType() 
    {
        return $this->belongsTo(WaterSystemType::class, 'water_system_type_id', 'id');
    }
}