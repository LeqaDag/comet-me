<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCommunity extends Model
{
    use HasFactory;

    protected $fillable = ['community_id', 'english_name', 'arabic_name', 'household_id'];

    public function Community()
    {
        
        return $this->belongsTo(Community::class, 'community_id', 'id');
    }

    public function Household()
    {
        
        return $this->belongsTo(Household::class, 'household_id', 'id');
    }
}