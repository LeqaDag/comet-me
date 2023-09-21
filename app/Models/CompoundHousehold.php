<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompoundHousehold extends Model
{
    use HasFactory;

    protected $fillable = ['community_id', 'household_id', 'compound_id'];

    public function Community()
    {
        
        return $this->belongsTo(Community::class, 'community_id', 'id');
    }

    public function Compound()
    {
        
        return $this->belongsTo(Compound::class, 'compound_id', 'id');
    }

    public function Household()
    {
        
        return $this->belongsTo(Household::class, 'household_id', 'id');
    }
}
