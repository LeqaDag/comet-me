<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgricultureHolder extends Model
{
    use HasFactory;
    protected $fillable = [
        'community_id',
        'household_id',
        'agriculture_holder_status_id',
        'confirmation_date',
        'agriculture_installation_types_id',
        'area_of_installation',
        'azolla_unit',
        'size_of_herds',
        'size_of_goat',
        'size_of_cow',
        'size_of_camel',
        'size_of_chicken',
        'agriculture_system_cycle_id',
        'requested_date',
        'completed_date',
        'contribution_rate',
        'area',
        'alternative_area',
        'notes'
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'requested_date' => 'date',
        'confirmation_date' => 'date',
        'completed_date' => 'date',
        'contribution_rate' => 'decimal:2',
        'azolla_unit' => 'integer',
        'size_of_herds' => 'integer',
        'size_of_goat' => 'integer',
        'size_of_cow' => 'integer',
        'size_of_camel' => 'integer',
        'size_of_chicken' => 'integer',
    ];
    public function community()
    {
        return $this->belongsTo(Community::class, 'community_id');
    }

    public function household()
    {
        return $this->belongsTo(Household::class, 'household_id');
    }
    public function agricultureHolderStatus()
    {
        return $this->belongsTo(AgricultureHolderStatus::class, 'agriculture_holder_status_id');
    }
    public function agricultureSystemCycle()
    {
        return $this->belongsTo(AgricultureSystemCycle::class, 'agriculture_system_cycle_id');
    }

    /**
     * Relationship to AgricultureHolderSystem (systems assigned to this holder)
     */
    public function agricultureHolderSystems()
    {
        return $this->hasMany(AgricultureHolderSystem::class, 'agriculture_holder_id');
    }

    /**
     * Relationship to AgricultureSystem through pivot table
     */
    public function agricultureSystems()
    {
        return $this->belongsToMany(AgricultureSystem::class, 'agriculture_holder_systems', 'agriculture_holder_id', 'agriculture_system_id');
    }

    /**
     * Shared herd entries related to this agriculture holder
     */
    public function agricultureSharedHolders()
    {
        return $this->hasMany(AgricultureSharedHolder::class, 'agriculture_holder_id');
    }

    /**
     * Donors linked to this agriculture holder
     */
    public function agricultureHolderDonors()
    {
        return $this->hasMany(AgricultureHolderDonor::class, 'agriculture_holder_id');
    }
}
