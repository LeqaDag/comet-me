<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CameraCommunityDonor extends Model
{
    use HasFactory;

    public function Donor()
    {
        
        return $this->belongsTo(Donor::class, 'donor_id', 'id');
    }

    public function CameraCommunity()
    {
        
        return $this->belongsTo(CameraCommunity::class, 'camera_community_id', 'id');
    }
}
