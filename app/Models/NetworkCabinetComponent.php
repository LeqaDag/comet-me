<?php

namespace App\Models;
use App\Models\Router;
use App\Models\Switche;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NetworkCabinetComponent extends Model
{
    use HasFactory;

    public function networkCabinetInternetSystem()
    {
        return $this->belongsTo(NetworkCabinetInternetSystem::class, 'network_cabinet_internet_system_id');
    }

    public function router()
    {
        return $this->belongsTo(Router::class, 'router_id');
    }

    public function switche()
    {
        return $this->belongsTo(Switche::class, 'switche_id');
    }

    public function component()
    {
        return $this->morphTo(); 
    }
}