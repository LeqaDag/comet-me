<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NetworkCabinet extends Model
{
    public function internetSystems()
    {
        return $this->belongsToMany(InternetSystem::class, 'network_cabinet_internet_systems')
                    ->withPivot('id')
                    ->withTimestamps();
    }

    public function components()
    {
        return $this->hasManyThrough(
            NetworkCabinetComponent::class,
            NetworkCabinetInternetSystem::class,
            'network_cabinet_id',               // FK on NetworkCabinetInternetSystem
            'network_cabinet_internet_system_id', // FK on NetworkCabinetComponent
            'id',                             // Local key on NetworkCabinet
            'id'                              // Local key on NetworkCabinetInternetSystem
        );
    }
}

