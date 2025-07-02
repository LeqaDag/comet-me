<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllWaterHolder extends Model
{
    use HasFactory;

    protected $fillable = ['community_id', 'household_id', 'public_structure_id'];

    public function Community()
    {
        return $this->belongsTo(Community::class, 'community_id', 'id');
    }

    public function Household()
    {
        return $this->belongsTo(Household::class, 'household_id', 'id');
    }

    public function PublicStructure()
    {
        
        return $this->belongsTo(PublicStructure::class, 'public_structure_id', 'id');
    }

    public function h2oUser()
    {
        return $this->hasOne(H2oUser::class, 'household_id', 'household_id');
    }

    public function gridUser()
    {
        return $this->hasOne(GridUser::class, 'household_id', 'household_id');
    }

    public function getSystemType()
    {
        $types = [];

        if ($this->h2oUser) {
            $types[] = 'H2O';
        }

        if ($this->gridUser) {
            $types[] = 'Grid Integration';
        }

        return implode(' / ', $types);
    }

    public function getInstallationDate()
    {
        if ($this->h2oUser) {
            return $this->h2oUser->h2o_installation_date;
        }

        if ($this->gridUser) {
            return $this->gridUser->grid_installation_date; // adjust field name
        }

        return null;
    }
}
