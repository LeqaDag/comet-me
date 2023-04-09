<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnergyUser extends Model
{
    use HasFactory;

    protected $fillable = ['household_id', 'meter_case_id'];


    public function Household()
    {
        return $this->belongsTo(Household::class, 'household_id', 'id');
    }

    public function MeterCase()
    {
        return $this->belongsTo(MeterCase::class, 'meter_case_id', 'id');
    }
}
