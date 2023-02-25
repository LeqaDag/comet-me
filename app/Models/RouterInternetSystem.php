<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RouterInternetSystem extends Model
{
    use HasFactory;

    protected $fillable = ['router_id', 'internet_system_id', 'router_units'];

    public function Router()
    {
        return $this->belongsTo(Router::class, 'router_id', 'id');
    }

    public function InternetSystem()
    {
        return $this->belongsTo(InternetSystem::class, 'internet_system_id', 'id');
    }
}
