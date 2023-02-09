<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnergyDonor extends Model
{
    use HasFactory;

    protected $fillable = ['community_id', 'household_id', 'donor_id'];

    public function Community()
    {
        return $this->belongsTo(Community::class, 'community_id', 'id');
    }

    public function Household()
    {
        return $this->belongsTo(Household::class, 'household_id', 'id');
    }

    public function Donor()
    {
        return $this->belongsTo(Donor::class, 'donor_id', 'id');
    }
}
