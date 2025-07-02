<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CameraCommunityType extends Model
{
    use HasFactory;

    public function Camera()
    {
        return $this->belongsTo(Camera::class, 'camera_id', 'id');
    }

    public function CameraCommunity()
    {
        return $this->belongsTo(CameraCommunity::class, 'camera_community_id', 'id');
    }
}
