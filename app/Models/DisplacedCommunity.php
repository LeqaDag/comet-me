<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisplacedCommunity extends Model
{
    use HasFactory;

    protected $fillable = [
        'community_id',
        'year'
    ];
}