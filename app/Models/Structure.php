<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Structure extends Model
{
    use HasFactory;

    protected $fillable = ['number_of_structures', 'household_id', 'number_of_kitchens'];


    public function Household()
    {
        
        return $this->belongsTo(Household::class, 'household_id', 'id');
    }
}
