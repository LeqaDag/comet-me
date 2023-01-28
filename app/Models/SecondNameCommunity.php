<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecondNameCommunity extends Model
{
    use HasFactory;

    protected $fillable = ['community_id', 'english_name', 'arabic_name'];

    public function Community()
    {
        
        return $this->belongsTo(Community::class, 'community_id', 'id');
    }
}
