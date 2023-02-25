<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommunityHousehold extends Model
{
    use HasFactory;

    protected $fillable = ['household_id', 'is_there_house_in_town', 'is_there_izbih',
        'how_long', 'length_of_stay'];


    public function Household()
    {
        return $this->belongsTo(Household::class, 'household_id', 'id');
    }
}
