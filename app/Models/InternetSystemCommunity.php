<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternetSystemCommunity extends Model
{
    use HasFactory;

    protected $fillable = ['community_id', 'internet_system_id'];

    public function Community()
    {
        return $this->belongsTo(Community::class, 'community_id', 'id');
    }

    public function InternetSystem()
    {
        return $this->belongsTo(InternetSystem::class, 'internet_system_id', 'id');
    }
}