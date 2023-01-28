<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;

    protected $fillable = ['slug', 'is_archive', 'community_id'];

    public function Community()
    {
        
        return $this->belongsTo(Community::class, 'community_id', 'id');
    }
}