<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllEnergyMeterDonor extends Model
{
    use HasFactory;

    public function Donor()
    {
        
        return $this->belongsTo(Donor::class, 'donor_id', 'id');
    }
}
