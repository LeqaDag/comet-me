<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgricultureSystem extends Model
{
    use HasFactory;

    protected $fillable = [
        'is_archived',
        'comet_id', 
        'name',
        'agriculture_system_cycle_id',
        'installation_year',
        'fake_meter_number',
        'azolla_type_id',
        'agriculture_system_type_id',
        'description'
    ];

    /**
     * Boot function to automatically generate comet_id and fake_meter_number
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->comet_id)) {
                $model->comet_id = \App\Helpers\SequenceHelper::generateAgricultureSystemCometId();
            }
            if (empty($model->fake_meter_number)) {
                $model->fake_meter_number = \App\Helpers\SequenceHelper::generateAgricultureFakeMeterNumber($model->comet_id);
            }
        });
    }

    /**
     * Relationship to AgriCultureSystemCycle
     */
    public function agricultureSystemCycle()
    {
        return $this->belongsTo(AgricultureSystemCycle::class, 'agriculture_system_cycle_id', 'id');
    }

    /**
     * Relationship to AzollaType
     */
    public function azollaType()
    {
        return $this->belongsTo(AzollaType::class, 'azolla_type_id', 'id');
    }

    /**
     * Self-referencing relationship to AgricultureSystem as type
     */
    public function agricultureSystemType()
    {
        return $this->belongsTo(AgricultureSystem::class, 'agriculture_system_type_id', 'id');
    }
}