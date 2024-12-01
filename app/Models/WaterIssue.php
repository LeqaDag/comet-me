<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaterIssue extends Model
{
    use HasFactory;

    public function WaterAction()
    {
        return $this->belongsTo(WaterAction::class, 'water_action_id', 'id');
    }
}
