<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NetworkCabinetInternetSystem extends Model
{
    use HasFactory;

    public function InternetSystem()
    {
        return $this->belongsTo(InternetSystem::class, 'internet_system_id', 'id');
    } 

    public function model()
    {
        return $this->belongsTo(NetworkCabinet::class, 'network_cabinet_id', 'id');
    }

    public function networkCabinet()
    {
        return $this->belongsTo(NetworkCabinet::class, 'network_cabinet_id');
    }

    public function components()
    {
        return $this->hasMany(NetworkCabinetComponent::class, 'network_cabinet_internet_system_id');
    }
}
