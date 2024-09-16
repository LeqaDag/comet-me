<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = ['english_name', 'vendor_username_id', 'arabic_name', 'phone_number'];


    public function VendorUsername()
    {
        return $this->belongsTo(VendorUsername::class, 'vendor_username_id', 'id');
    }

    public function Community()
    {
        return $this->belongsTo(Community::class, 'community_id', 'id');
    }

    public function Town()
    {
        return $this->belongsTo(Town::class, 'town_id', 'id');
    }

    public function VendorRegion()
    {
        return $this->belongsTo(VendorRegion::class, 'vendor_region_id', 'id');
    }
}