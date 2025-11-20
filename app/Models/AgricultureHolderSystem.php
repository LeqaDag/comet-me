<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgricultureHolderSystem extends Model
{
    use HasFactory;
    protected $fillable = [
        'is_archived',
        'agriculture_holder_id',
        'agriculture_system_id',
    ];
    public function agricultureHolder()
    {
        return $this->belongsTo(AgricultureHolder::class, 'agriculture_holder_id');
    }
    
    public function agricultureSystem()
    {
        return $this->belongsTo(AgricultureSystem::class, 'agriculture_system_id');
    }
}
