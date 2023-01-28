<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compound extends Model
{
    use HasFactory;

    protected $fillable = ['english_name', 'arabic_name', 'community_id'];
    

    public function Community()
    {
        return $this->belongsTo(Community::class, 'community_id', 'id');
    }
}
