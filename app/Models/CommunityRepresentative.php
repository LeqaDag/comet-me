<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommunityRepresentative extends Model
{
    use HasFactory; 

    protected $fillable = ['community_id', 'community_role_id', 'household_id'];


    public function Community()
    {
        
        return $this->belongsTo(Community::class, 'community_id', 'id');
    }

    public function Household()
    {
        
        return $this->belongsTo(Household::class, 'household_id', 'id');
    }

    public function CommunityRole()
    {
        
        return $this->belongsTo(CommunityRole::class, 'community_role_id', 'id');
    }
}
