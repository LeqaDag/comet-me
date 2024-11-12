<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllEnergyMeterPhase extends Model
{
    use HasFactory;

    public function AllEnergyMeter()
    {
        return $this->belongsTo(AllEnergyMeter::class, 'household_id', 'id');
    }

    public function ElectricityPhase()
    {
        return $this->belongsTo(ElectricityPhase::class, 'electricity_phase_id', 'id');
    }

    public function ElectricityCollectionBox()
    {
        return $this->belongsTo(ElectricityCollectionBox::class, 'electricity_collection_box_id', 'id');
    }
}
