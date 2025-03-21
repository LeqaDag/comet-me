<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefrigeratorHolder extends Model
{
    use HasFactory;

    protected $fillable = ['community_id', 'refrigerator_type_id'];

    public function Community()
    {
        return $this->belongsTo(Community::class, 'community_id', 'id');
    }

    public function RefrigeratorType()
    {
        return $this->belongsTo(RefrigeratorType::class, 'refrigerator_type_id', 'id');
    }

    public function Household()
    {
        return $this->belongsTo(Household::class, 'household_id', 'id');
    }

    public function PublicStructure()
    {
        return $this->belongsTo(PublicStructure::class, 'public_structure_id', 'id');
    }
}
