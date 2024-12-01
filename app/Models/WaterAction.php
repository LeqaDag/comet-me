<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaterAction extends Model
{
    use HasFactory;

    public function ActionCategory()
    {
        return $this->belongsTo(ActionCategory::class, 'action_category_id', 'id');
    }
}
