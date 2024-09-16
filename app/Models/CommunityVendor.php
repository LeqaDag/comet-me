<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommunityVendor extends Model
{
    use HasFactory;

    public function Community()
    {
        return $this->belongsTo(Community::class, 'community_id', 'id');
    }
}
