<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class H2oUser extends Model
{
    use HasFactory;

    protected $fillable = ['community_id', 'household_id', 'h2o_status_id', 'bsf_status_id', 
        'number_of_h20', 'installation_year', 'number_of_bsf'];

    public function Community()
    {
        return $this->belongsTo(Community::class, 'community_id', 'id');
    }

    public function Household()
    {
        return $this->belongsTo(Household::class, 'household_id', 'id');
    } 

    public function H2oStatus()
    {
        return $this->belongsTo(H2oStatus::class, 'h2o_status_id', 'id');
    }

    public function BsfStatus()
    {
        return $this->belongsTo(BsfStatus::class, 'bsf_status_id', 'id');
    }
}
