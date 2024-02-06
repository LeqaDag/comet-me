<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Household extends Model
{
    use HasFactory;

    protected $fillable = ['english_name', 'arabic_name', 'community_id', 'phone_number', 
        'number_of_family_members', 'number_of_male', 'number_of_female', 'number_of_adults',
        'number_of_children', 'profession_id'];


    public function Community()
    {
        return $this->belongsTo(Community::class, 'community_id', 'id');
    }

    public function Profession()
    {
        return $this->belongsTo(Profession::class, 'profession_id', 'id');
    }

    public function EnergySystemType()
    {
        return $this->belongsTo(EnergySystemType::class, 'energy_system_type_id', 'id');
    }

    public function HouseholdStatus()
    {
        return $this->belongsTo(HouseholdStatus::class, 'household_status_id', 'id');
    }

}
