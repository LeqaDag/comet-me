<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternetMaintenanceCall extends Model
{
    use HasFactory; 

    protected $fillable = ['community_id', 'maintenance_type_id', 'maintenance_status_id', 
        'date_of_call', 'date_completed', 'user_id'];

    public function Community()
    {
        return $this->belongsTo(Community::class, 'community_id', 'id');
    }

    public function User()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function MaintenanceType()
    {
        return $this->belongsTo(MaintenanceType::class, 'maintenance_type_id', 'id');
    }

    public function MaintenanceStatus()
    {
        return $this->belongsTo(MaintenanceStatus::class, 'maintenance_status_id', 'id');
    }

    public function InternetUser()
    {
        return $this->belongsTo(InternetUser::class, 'internet_user_id', 'id');
    }
}
