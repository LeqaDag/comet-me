<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnergyPublicStructureDonor extends Model
{
    use HasFactory;

    protected $fillable = ['energy_public_structure_id', 'donor_id'];

    public function EnergyPublicStructure()
    {
        return $this->belongsTo(EnergyPublicStructure::class, 'energy_public_structure_id', 'id');
    }

    public function Donor()
    {
        return $this->belongsTo(Donor::class, 'donor_id', 'id');
    }
}
