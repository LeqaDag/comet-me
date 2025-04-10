<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkshopCommunity extends Model
{
    use HasFactory;

    public function Community()
    {
        
        return $this->belongsTo(Community::class, 'community_id', 'id');
    }

    public function WorkshopType()
    {
        
        return $this->belongsTo(WorkshopType::class, 'workshop_type_id', 'id');
    }

    public function Compound()
    {
        
        return $this->belongsTo(Compound::class, 'compound_id', 'id');
    }

    public function User()
    {
        
        return $this->belongsTo(User::class, 'lead_by', 'id');
    }
}
 