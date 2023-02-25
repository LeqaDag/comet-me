<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SwitchInternetSystem extends Model
{
    use HasFactory;

    protected $fillable = ['switch_id', 'internet_system_id', 'switch_units'];

    public function Switche()
    {
        return $this->belongsTo(Switche::class, 'switch_id', 'id');
    }

    public function InternetSystem()
    {
        return $this->belongsTo(InternetSystem::class, 'internet_system_id', 'id');
    }
}
