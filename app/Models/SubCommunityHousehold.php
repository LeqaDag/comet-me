<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCommunityHousehold extends Model
{
    use HasFactory;

    protected $fillable = ['community_id', 'sub_community_id', 'household_id'];

    public function Community()
    {
        
        return $this->belongsTo(Community::class, 'community_id', 'id');
    }

    public function SubCommunity()
    {
        
        return $this->belongsTo(SubCommunity::class, 'sub_community_id', 'id');
    }

    public function Household()
    {
        
        return $this->belongsTo(Household::class, 'household_id', 'id');
    }
}
