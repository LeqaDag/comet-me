<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefrigeratorIssue extends Model
{
    use HasFactory;

    public function RefrigeratorAction()
    {
        return $this->belongsTo(RefrigeratorAction::class, 'refrigerator_action_id', 'id');
    }
}
