<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublicStructure extends Model
{
    use HasFactory;

    protected $fillable = ['english_name', 'arabic_name', 'public_structure_category_id1', 
        'public_structure_category_id2',
        'public_structure_category_id3'];

    public function Category1()
    {
        return $this->belongsTo(PublicStructureCategory::class, 'public_structure_category_id1', 'id');
    }

    public function Category2() 
    {
        return $this->belongsTo(PublicStructureCategory::class, 'public_structure_category_id2', 'id');
    }

    public function Category3()
    {
        return $this->belongsTo(PublicStructureCategory::class, 'public_structure_category_id3', 'id');
    }

    public function Compound()
    {
        return $this->belongsTo(Compound::class, 'compound_id', 'id');
    }

    public function EnergySystemType()
    {
        return $this->belongsTo(EnergySystemType::class, 'energy_system_type_id', 'id');
    }

    public function EnergySystemCycle()
    {
        return $this->belongsTo(EnergySystemCycle::class, 'energy_system_cycle_id', 'id');
    }

    public function PublicStructureStatus()
    {
        return $this->belongsTo(PublicStructureStatus::class, 'public_structure_status_id', 'id');
    }

    public function WaterHolderStatus()
    {
        return $this->belongsTo(WaterHolderStatus::class, 'water_holder_status_id', 'id');
    }
}
