<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MgAffectedHousehold extends Model
{
    use HasFactory;

    protected $fillable = ['household_id', 'mg_incident_id'];

    public function Household()
    {
        return $this->belongsTo(Household::class, 'household_id', 'id');
    }

    public function MgIncident()
    {
        return $this->belongsTo(MgIncident::class, 'mg_incident_id', 'id');
    }
}
