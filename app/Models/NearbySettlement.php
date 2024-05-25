<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NearbySettlement extends Model
{
    use HasFactory;

    protected $fillable = ['settlement_id', 'community_id'];

    public function Community()
    {
        return $this->belongsTo(Community::class, 'community_id', 'id');
    }

    public function Settlement()
    {
        return $this->belongsTo(Settlement::class, 'settlement_id', 'id');
    }
}
