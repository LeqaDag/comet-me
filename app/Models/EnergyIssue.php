<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnergyIssue extends Model
{
    use HasFactory;

    public function EnergyMaintenanceIssueType()
    {
        return $this->belongsTo(EnergyMaintenanceIssueType::class, 'energy_maintenance_issue_type_id', 'id');
    }

    public function EnergyAction()
    {
        return $this->belongsTo(EnergyAction::class, 'energy_action_id', 'id');
    }
}