<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NvrCommunityType extends Model
{
    use HasFactory;

    public function NvrCamera()
    {
        
        return $this->belongsTo(NvrCamera::class, 'nvr_camera_id', 'id');
    }
}
