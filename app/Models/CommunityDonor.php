<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommunityDonor extends Model
{
    use HasFactory;

    protected $fillable = ['community_id', 'service_id', 'donor_id'];


    public function Community()
    {
        
        return $this->belongsTo(Community::class, 'community_id', 'id');
    }

    public function ServiceType()
    {
        
        return $this->belongsTo(ServiceType::class, 'service_id', 'id');
    }

    public function Donor()
    {
        
        return $this->belongsTo(Donor::class, 'donor_id', 'id');
    }
}
