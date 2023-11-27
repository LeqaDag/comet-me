<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class H2oSharedPublicStructure extends Model
{
    use HasFactory;

    protected $fillable = ['public_Structure_id', ''];

    public function PublicStructure()
    {
        
        return $this->belongsTo(PublicStructure::class, 'public_structure_id', 'id');
    }

    public function H2oPublicStructure()
    {
        
        return $this->belongsTo(H2oPublicStructure::class, 'h2o_public_structure_id', 'id');
    }
}
