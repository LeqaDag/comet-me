<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cistern extends Model
{
    use HasFactory;

    protected $fillable = ['household_id', 'number_of_cisterns'];


    public function Household()
    {
        return $this->belongsTo(Household::class, 'household_id', 'id');
    }
}
