<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NearbyTown extends Model
{
    use HasFactory;

    protected $fillable = ['town_id', 'community_id'];

    public function Community()
    {
        return $this->belongsTo(Community::class, 'community_id', 'id');
    }

    public function Town()
    {
        return $this->belongsTo(Town::class, 'town_id', 'id');
    }
}
