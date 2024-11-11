<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllEnergyMeterHistoryCase extends Model
{
    use HasFactory;

    public function AllEnergyMeter()
    {
        return $this->belongsTo(AllEnergyMeter::class, 'all_energy_meter_id', 'id');
    }

    public function OldMeterCase()
    {
        return $this->belongsTo(MeterCase::class, 'old_meter_case_id', 'id');
    }

    public function NewMeterCase()
    {
        return $this->belongsTo(MeterCase::class, 'new_meter_case_id', 'id');
    }
}
