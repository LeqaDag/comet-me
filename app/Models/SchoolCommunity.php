<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolCommunity extends Model
{
    use HasFactory;

    protected $fillable = ['community_id', 'public_structure_id'];

    public function Community()
    {
        return $this->belongsTo(Community::class, 'community_id', 'id');
    }

    public function PublicStructure()
    {
        
        return $this->belongsTo(PublicStructure::class, 'public_structure_id', 'id');
    }
}
