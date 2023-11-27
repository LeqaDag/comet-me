<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GridSharedUser extends Model
{
    use HasFactory;

    protected $fillable = ['household_id'];

    public function Household()
    {
        return $this->belongsTo(Household::class, 'household_id', 'id');
    }
}
