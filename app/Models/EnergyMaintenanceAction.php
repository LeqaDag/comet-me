<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnergyMaintenanceAction extends Model
{
    use HasFactory;

    protected $fillable = ['energy_maintenance_issue_type_id', 'energy_maintenance_issue_id'];

    public function EnergyMaintenanceIssue()
    {
        return $this->belongsTo(EnergyMaintenanceIssue::class, 'energy_maintenance_issue_id', 'id');
    }

    public function EnergyMaintenanceIssueType()
    {
        return $this->belongsTo(EnergyMaintenanceIssueType::class, 'energy_maintenance_issue_type_id', 'id');
    }
}
