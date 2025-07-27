<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternetSystemCommunity extends Model
{
    use HasFactory;

    public function InternetSystem()
    { 
        return $this->belongsTo(InternetSystem::class, 'internet_system_id', 'id');
    }

    public function Compound()
    { 
        return $this->belongsTo(Compound::class, 'compound_id', 'id');
    }
}
