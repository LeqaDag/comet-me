<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActionItem extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'action_status_id', 'action_priority_id'];

    public function User()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function ActionStatus()
    {
        return $this->belongsTo(ActionStatus::class, 'action_status_id', 'id');
    }

    public function ActionPriority()
    {
        return $this->belongsTo(ActionPriority::class, 'action_priority_id', 'id');
    }
}
