<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewElectricityMaintenanceCall extends Model
{
    use HasFactory;

    protected $fillable = ['community_id', 'maintenance_type_id', 'maintenance_status_id', 
        'maintenance_new_electricity_action_id', 'date_of_call', 'date_completed', 'user_id'];

    public function Community()
    {
        return $this->belongsTo(Community::class, 'community_id', 'id');
    }

    public function User()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
