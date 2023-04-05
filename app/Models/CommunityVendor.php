<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommunityVendor extends Model
{
    use HasFactory;

    protected $fillable = ['community_id', 'vendor_username_id', 'nis'];


    public function Community()
    {
        return $this->belongsTo(Community::class, 'community_id', 'id');
    }

    public function VendorUsername()
    {
        return $this->belongsTo(VendorUsername::class, 'vendor_username_id', 'id');
    }
}